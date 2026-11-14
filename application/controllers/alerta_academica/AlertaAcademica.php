<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

class AlertaAcademica extends CI_Controller {

    /* Privilegios: 1 y 7 = Super Admin / direccion; 2 y 5 = docente */
    private $privilegios_admin = array(1, 7);
    private $privilegios_docente = array(2, 5);

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'documento');
        $helper = array('url');
        $model = array('m_anio_escolar', 'm_usuario', 'm_alumno', 'm_alerta_academica');
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        /*
         * Configuración personalizada
         */
        $this->config->load('serinfo', TRUE, TRUE);
        $this->config->load('powerbi', TRUE, TRUE);
        $this->_session = $this->session_sistema->datos_usuario('user_data');

        $this->items['proyecto'] = $this->config->item('nombre_proyecto', 'serinfo');
        $this->items['favicon'] = $this->config->item('url_favicon', 'serinfo');
        $this->items['base_url'] = base_url();
        $this->items['alerta_academica_activo'] = 'active';
    }

    /* Funcion para mostrar los cursos disponibles y el area de resultados (todo en la misma pagina) */
    public function listar($id_anio_escolar = '') {
        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio = $login['usuario_privilegio'];

        if (!in_array($privilegio, array_merge($this->privilegios_admin, $this->privilegios_docente))) {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Alerta Académica';
        $data['titulo_modulo'] = 'Alerta Académica';
        /* ------------------------------------------------------------ */
        $id_anio_escolar_actual = $id_anio_escolar != '' ? $id_anio_escolar : $this->m_anio_escolar->maximo_id('id_anio_escolar');
        $es_administrador = in_array($privilegio, $this->privilegios_admin);
        $data['es_administrador'] = $es_administrador;

        if ($es_administrador) {
            $cursos = $this->m_alerta_academica->cursos_todos($id_anio_escolar_actual);
        } else {
            $cursos = $this->m_alerta_academica->cursos_docente($login['usuario_id'], $id_anio_escolar_actual);
        }

        $data['lista_cursos'] = array();
        $cursos_lote = array();
        $grados_lote = array();
        $i = 1;
        foreach ($cursos as $c) {
            $nombre_docente = '';
            if ($es_administrador) {
                $docente = $this->m_usuario->mostrar(array('usuario.id_usuario' => $c['id_usuario']));
                $nombre_docente = !empty($docente) ? "{$docente['nombres']} {$docente['ape_paterno']} {$docente['ape_materno']}" : '';
            }
            $seccion = $c['seccion_grado'] == 'Única' ? '' : $c['seccion_grado'];
            $data['lista_cursos'][] = array(
                'numero' => $i,
                'id_curso' => $c['id_curso'],
                'nombre_curso' => $c['nombre_curso'],
                'nombre_grado' => trim("{$c['nombre_grado']} $seccion"),
                'nombre_docente' => $nombre_docente,
            );
            //Lista para la generacion institucional (solo Super Admin): un curso con dos docentes
            //aparece dos veces en el listado, pero se genera una sola vez (sin repetir id_curso)
            if ($es_administrador && !isset($cursos_lote[$c['id_curso']])) {
                $cursos_lote[$c['id_curso']] = array(
                    'id_curso' => (int) $c['id_curso'],
                    'id_grado' => (int) $c['id_grado'],
                    'nombre_curso' => $c['nombre_curso'],
                    'nombre_grado' => trim("{$c['nombre_grado']} $seccion"),
                );
                $grados_lote[$c['id_grado']] = trim("{$c['nombre_grado']} $seccion");
            }
            $i++;
        }

        $opciones_json = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        $data['cursos_lote_json'] = json_encode(array_values($cursos_lote), $opciones_json);
        $data['grados_lote'] = $grados_lote;

        //Alumnos de esos grados, para el campo "Alumno (opcional)" del modal por grado (solo Super
        //Admin, que ya puede ver a todos los alumnos). Mismo formato de nombre que generar().
        $alumnos_lote = array();
        if ($es_administrador) {
            foreach ($this->m_alerta_academica->alumnos_anio($id_anio_escolar_actual) as $a) {
                if (isset($grados_lote[$a['id_grado']])) {
                    $alumnos_lote[] = array(
                        'id_alumno' => (int) $a['id_alumno'],
                        'id_grado' => (int) $a['id_grado'],
                        'nombre' => "{$a['apellido_paterno']} {$a['apellido_materno']}, {$a['nombre']}",
                    );
                }
            }
        }
        $data['alumnos_lote_json'] = json_encode($alumnos_lote, $opciones_json);
        $data['powerbi_publicado'] = $this->url_tablero(0, 0) !== '';
        //"Bimestre a predecir": se preselecciona el que corresponde segun las notas ya registradas
        $data['bimestre_sugerido'] = $this->m_alerta_academica->bimestre_sugerido($id_anio_escolar_actual);

        //Regreso desde el tablero de Power BI: se vuelve a abrir el resultado de este curso
        $data['volver_id_curso'] = 0;
        $data['volver_nombre_curso'] = '';
        $volver_id_curso = filter_var($this->input->get('volver_curso'), FILTER_VALIDATE_INT);
        if ($volver_id_curso !== FALSE && $volver_id_curso > 0) {
            $curso_volver = $this->m_alerta_academica->datos_curso($volver_id_curso);
            if (!empty($curso_volver)) {
                $data['volver_id_curso'] = $volver_id_curso;
                $data['volver_nombre_curso'] = $this->nombre_curso_grado($curso_volver);
            }
        }

        //Carga combo de año escolar
        $anio_escolar = $this->m_anio_escolar->mostrar_activos();
        if (!empty($anio_escolar)) {
            $combo = array();
            foreach ($anio_escolar as $ae) {
                $combo[$ae['id_anio_escolar']] = $ae['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($combo, 'id_anio_escolar_alerta', $id_anio_escolar_actual, array());
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/alerta_academica/listar', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion AJAX: ejecuta el modelo para un curso y devuelve el detalle (no redirige a otra pagina) */
    public function generar() {
        header('Content-Type: application/json');

        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio = $login['usuario_privilegio'];
        $es_administrador = in_array($privilegio, $this->privilegios_admin);
        $es_docente = in_array($privilegio, $this->privilegios_docente);

        if (!$es_administrador && !$es_docente) {
            echo json_encode(array('error' => 'No tiene permisos para generar predicciones.'));
            exit;
        }

        //Validamos id_curso como entero positivo antes de usarlo (evita inyeccion de comandos en exec())
        $id_curso = filter_var($this->input->post('id_curso'), FILTER_VALIDATE_INT);
        if ($id_curso === FALSE || $id_curso <= 0) {
            echo json_encode(array('error' => 'Curso inválido.'));
            exit;
        }

        //Un docente solo puede generar predicciones de sus propios cursos asignados
        if ($es_docente && !$this->m_alerta_academica->docente_tiene_curso($login['usuario_id'], $id_curso)) {
            echo json_encode(array('error' => 'Este curso no está asignado a su usuario.'));
            exit;
        }

        //Bimestre a predecir: SOLO el bimestre en curso del anio escolar del curso (el siguiente al ultimo
        //con la mayoria de notas registradas). Vacio = ese mismo; cualquier otro se rechaza, aunque se
        //manipule la pagina. Se valida como entero 2-4 antes de usarlo en exec().
        $curso = $this->m_alerta_academica->datos_curso($id_curso);
        if (empty($curso)) {
            echo json_encode(array('error' => 'Curso inválido.'));
            exit;
        }
        $bimestre_en_curso = (int) $this->m_alerta_academica->bimestre_sugerido($curso['id_anio_escolar']);
        $bimestre = $this->input->post('bimestre');
        if ($bimestre === NULL || $bimestre === '') {
            $bimestre = $bimestre_en_curso;
        }
        $bimestre = filter_var($bimestre, FILTER_VALIDATE_INT, array('options' => array('min_range' => 2, 'max_range' => 4)));
        if ($bimestre === FALSE || $bimestre !== $bimestre_en_curso) {
            $textos = array(2 => '2.º', 3 => '3.er', 4 => '4.º');
            echo json_encode(array('error' => 'Solo se puede predecir el ' . $textos[$bimestre_en_curso] . ' bimestre (bimestre en curso).'));
            exit;
        }

        $ruta_python = $this->config->item('ruta_python', 'serinfo');
        $ruta_script = $this->config->item('ruta_script_prediccion', 'serinfo');

        $comando = escapeshellarg($ruta_python) . ' ' . escapeshellarg($ruta_script) . ' --id_curso ' . escapeshellarg($id_curso)
            . ' --bimestre ' . escapeshellarg($bimestre);

        $salida = array();
        $codigo_retorno = 0;
        exec($comando . ' 2>&1', $salida, $codigo_retorno);

        $resultado = json_decode(implode("\n", $salida), TRUE);

        if ($resultado === NULL) {
            echo json_encode(array('error' => 'No se pudo generar la predicción. Intente nuevamente.'));
            exit;
        }
        if (isset($resultado['error'])) {
            echo json_encode(array('error' => $resultado['error']));
            exit;
        }

        //Enriquecemos cada resultado con el nombre del alumno y la traduccion a "alerta"
        //(terminologia de presentacion; internamente el modelo sigue hablando de riesgo)
        $conteo = array('sin_riesgo' => 0, 'riesgo_moderado' => 0, 'riesgo_alto' => 0);
        foreach ($resultado['resultados'] as &$r) {
            $alumno = $this->m_alumno->mostrar(array('alumno.id_alumno' => $r['id_alumno']));
            $r['nombre_alumno'] = !empty($alumno) ? "{$alumno['apellido_paterno']} {$alumno['apellido_materno']}, {$alumno['nombre']}" : '';
            $r['etiqueta_alerta'] = $this->etiqueta_alerta($r['clasificacion']);
            $r['clase_alerta'] = $this->clase_alerta($r['clasificacion']);
            if (isset($conteo[$r['clasificacion']])) {
                $conteo[$r['clasificacion']]++;
            }
        }
        unset($r);

        //Nombre de los alumnos sin prediccion (para el detalle "Ver detalle" de la intranet)
        if (!empty($resultado['alumnos_excluidos'])) {
            foreach ($resultado['alumnos_excluidos'] as &$ex) {
                $alumno = $this->m_alumno->mostrar(array('alumno.id_alumno' => $ex['id_alumno']));
                $ex['nombre_alumno'] = !empty($alumno) ? "{$alumno['apellido_paterno']} {$alumno['apellido_materno']}, {$alumno['nombre']}" : '';
            }
            unset($ex);
        }

        $resultado['resumen_alertas'] = array(
            'sin_alerta' => $conteo['sin_riesgo'],
            'alerta_moderada' => $conteo['riesgo_moderado'],
            'alerta_alta' => $conteo['riesgo_alto'],
        );

        echo json_encode($resultado);
    }

    /* Pagina con el tablero de Power BI dentro de un iframe, con barra propia de la intranet.
       Parametros opcionales por URL (solo numeros): id_curso -> pagina "Detalle por grado y curso";
       id_curso + id_alumno -> pagina "Detalle por alumno"; sin parametros -> pagina de resumen.
       El filtro por URL solo elige que se muestra: el control de acceso a los datos del reporte
       es el de Power BI (inicio de sesion / seguridad de filas), no este filtro. */
    public function tablero() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio = $login['usuario_privilegio'];
        $es_administrador = in_array($privilegio, $this->privilegios_admin);
        $es_docente = in_array($privilegio, $this->privilegios_docente);

        if (!$es_administrador && !$es_docente) {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Tablero de alertas';
        $data['titulo_modulo'] = 'Tablero de alertas';
        /* ------------------------------------------------------------ */
        $id_curso = 0;
        $id_alumno = 0;
        $error_tablero = '';

        if ($this->input->get('id_curso') !== NULL) {
            $id_curso = filter_var($this->input->get('id_curso'), FILTER_VALIDATE_INT);
            if ($id_curso === FALSE || $id_curso <= 0) {
                $id_curso = 0;
                $error_tablero = 'Curso inválido.';
            }
        }
        if ($error_tablero == '' && $this->input->get('id_alumno') !== NULL) {
            $id_alumno = filter_var($this->input->get('id_alumno'), FILTER_VALIDATE_INT);
            if ($id_alumno === FALSE || $id_alumno <= 0 || $id_curso == 0) {
                $id_alumno = 0;
                $error_tablero = 'Alumno inválido.';
            }
        }

        $curso = array();
        if ($error_tablero == '' && $id_curso > 0) {
            $curso = $this->m_alerta_academica->datos_curso($id_curso);
            if (empty($curso)) {
                $error_tablero = 'Curso inválido.';
            } elseif ($es_docente && !$this->m_alerta_academica->docente_tiene_curso($login['usuario_id'], $id_curso)) {
                $error_tablero = 'Este curso no está asignado a su usuario.';
            } elseif ($id_alumno > 0 && !$this->m_alerta_academica->alumno_en_curso($id_alumno, $id_curso)) {
                $error_tablero = 'El alumno no pertenece a este curso.';
            }
        }

        $data['error_tablero'] = $error_tablero;
        //?vista=curso: pagina "Detalle por Grado y Curso" aunque no venga un curso (boton del lote)
        $vista_curso = $this->input->get('vista') === 'curso';
        $data['url_tablero'] = $error_tablero == '' ? $this->url_tablero($id_curso, $id_alumno, $vista_curso) : '';
        //"Publicar en la web" trae una franja inferior (zoom, paginas y compartir en redes) que no se
        //puede quitar desde Power BI: la vista la deja fuera del recuadro visible
        $data['ocultar_franja_powerbi'] = strpos($data['url_tablero'], '/view?r=') !== FALSE;

        //Boton "Actualizar tablero": solo si el reporte esta publicado y el script configurado
        $data['boton_actualizar_tablero'] = $data['url_tablero'] != ''
            && trim((string) $this->config->item('ruta_script_powerbi', 'powerbi')) != '';

        //Barra "Volver a la prediccion de [curso - grado]": solo si el curso es valido y accesible
        $data['volver_id_curso'] = 0;
        $data['volver_nombre_curso'] = '';
        $data['url_volver'] = base_url() . 'alerta_academica/listar';
        if ($error_tablero == '' && !empty($curso)) {
            $data['volver_id_curso'] = $id_curso;
            $data['volver_nombre_curso'] = $this->nombre_curso_grado($curso);
            $data['url_volver'] = base_url() . 'alerta_academica/listar/' . (int) $curso['id_anio_escolar'] . '?volver_curso=' . $id_curso;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/alerta_academica/tablero', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion AJAX: pide a Power BI actualizar los datos del tablero (como "Actualizar ahora" en
       app.powerbi.com). Si ya hay una actualizacion en curso, no inicia otra: solo la informa. */
    public function actualizar_tablero() {
        $this->responder_script_powerbi('--actualizar');
    }

    /* Funcion AJAX: estado de la ultima actualizacion del tablero (en_curso / completada / fallida) */
    public function estado_tablero() {
        $this->responder_script_powerbi('--estado');
    }

    /* ------------------------------------ FUNCIONES PRIVADAS ------------------------------------ */
    /* -------------------------------------------------------------------------------------------- */

    /* Ejecuta actualizar_powerbi.py con una accion de la lista fija (nunca un dato del usuario) y
       devuelve su JSON. Mismos permisos que la pagina del tablero. */
    private function responder_script_powerbi($accion) {
        header('Content-Type: application/json');

        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio = $login['usuario_privilegio'];
        if (!in_array($privilegio, array_merge($this->privilegios_admin, $this->privilegios_docente))) {
            echo json_encode(array('error' => 'No tiene permisos para actualizar el tablero.'));
            exit;
        }
        if (!in_array($accion, array('--actualizar', '--estado'), TRUE)) {
            echo json_encode(array('error' => 'Acción inválida.'));
            exit;
        }

        $ruta_python = $this->config->item('ruta_python', 'serinfo');
        $ruta_script = trim((string) $this->config->item('ruta_script_powerbi', 'powerbi'));
        if ($ruta_script == '') {
            echo json_encode(array('error' => 'La actualización automática del tablero aún no está configurada.'));
            exit;
        }

        //Sin "2>&1": solo el JSON de stdout llega aqui (cualquier aviso de Python va al log de Apache)
        $comando = escapeshellarg($ruta_python) . ' ' . escapeshellarg($ruta_script) . ' ' . $accion;
        $salida = array();
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);

        $resultado = json_decode(implode("\n", $salida), TRUE);
        if (!is_array($resultado)) {
            echo json_encode(array('error' => 'No se pudo consultar el tablero. Intente nuevamente.'));
            exit;
        }
        if (isset($resultado['error'])) {
            //El detalle tecnico se registra en el log de la intranet, nunca se muestra al usuario
            if (!empty($resultado['detalle'])) {
                log_message('error', 'Tablero Power BI: ' . (is_string($resultado['detalle']) ? $resultado['detalle'] : json_encode($resultado['detalle'])));
            }
            echo json_encode(array('error' => $resultado['error']));
            exit;
        }
        unset($resultado['detalle']);
        echo json_encode($resultado);
    }

    /* Traduce la clasificacion interna (riesgo) al nivel de alerta que ve el usuario final */
    private function etiqueta_alerta($clasificacion) {
        switch ($clasificacion) {
            case 'riesgo_alto':
                return 'Alerta alta';
            case 'riesgo_moderado':
                return 'Alerta moderada';
            default:
                return 'Sin alerta';
        }
    }

    /* Arma la URL del reporte de Power BI segun config/powerbi.php ('' si aun no esta publicado).
       Solo recibe ids numericos ya validados; nunca nombres. */
    private function url_tablero($id_curso, $id_alumno, $vista_curso = FALSE) {
        $url = trim($this->config->item('url_reporte', 'powerbi'));
        if ($url == '') {
            return '';
        }

        //Un enlace de "Publicar en la web" (app.powerbi.com/view?r=...) no acepta filtros por URL:
        //se abre la pagina correspondiente y el filtro se elige dentro del reporte
        $acepta_filtros = strpos($url, '/view?r=') === FALSE;

        $parametros = array();
        if ($id_alumno > 0) {
            $pagina = $this->config->item('pagina_alumno', 'powerbi');
            if ($acepta_filtros) {
                $parametros['filter'] = $this->config->item('filtro_alumno', 'powerbi') . ' eq ' . (int) $id_alumno;
            }
        } elseif ($id_curso > 0 || $vista_curso) {
            $pagina = $this->config->item('pagina_curso', 'powerbi');
            if ($acepta_filtros && $id_curso > 0) {
                $parametros['filter'] = $this->config->item('filtro_curso', 'powerbi') . ' eq ' . (int) $id_curso;
            }
        } else {
            $pagina = $this->config->item('pagina_resumen', 'powerbi');
        }
        if ($pagina != '') {
            $parametros['pageName'] = $pagina;
        }

        if (empty($parametros)) {
            return $url;
        }
        return $url . (strpos($url, '?') === FALSE ? '?' : '&') . http_build_query($parametros, '', '&', PHP_QUERY_RFC3986);
    }

    /* "Biología - 2do Año A" (sin la seccion "Única"), igual que el listado de cursos */
    private function nombre_curso_grado($curso) {
        $seccion = $curso['seccion_grado'] == 'Única' ? '' : $curso['seccion_grado'];
        return $curso['nombre_curso'] . ' - ' . trim("{$curso['nombre_grado']} $seccion");
    }

    private function clase_alerta($clasificacion) {
        switch ($clasificacion) {
            case 'riesgo_alto':
                return 'badge badge-danger';
            case 'riesgo_moderado':
                return 'badge badge-warning';
            default:
                return 'badge badge-success';
        }
    }

}
