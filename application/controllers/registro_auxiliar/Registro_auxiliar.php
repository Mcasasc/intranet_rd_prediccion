<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Registro_auxiliar extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_anio_escolar", "m_curso", "m_grado", "m_curso_docente", "m_bimestre", "m_mes_escolar");
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        /*
         * Configuración personalizada
         */
        $this->config->load('serinfo', TRUE, TRUE);
        $this->_session = $this->session_sistema->datos_usuario('user_data');

        $this->items['proyecto'] = $this->config->item('nombre_proyecto', 'serinfo');
        $this->items['favicon'] = $this->config->item('url_favicon', 'serinfo');
        $this->items['base_url'] = base_url();
        $this->items['registro_auxiliar_activo'] = 'active';
    }

    /* Lista los docentes asignados */
    public function docentes() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Docentes';
        $data['titulo_modulo'] = "Lista de Docentes";
        /* ------------------------------------------------------------ */

        $lista_docentes = $this->m_curso_docente->docentes(); //Muestra todos los docentes con cursos asignados

        if (!empty($lista_docentes)) {
            $i = 1;
            foreach ($lista_docentes AS $items) {
                $docente = $this->m_usuario->mostrar(array('usuario.id_usuario' => $items['id_usuario']));
                $anio_escolar = $this->m_anio_escolar->mostrar(array('anio_escolar.id_anio_escolar' => $items['id_anio_escolar']));
                $accion = $this->mantenimiento->accion($items['id_usuario'], 'cursos', 'registro_auxiliar', '');
                $data['lista'][] = array(
                    'numero' => $i,
                    'nombre_docente' => "{$docente['nombres']} {$docente['ape_paterno']} {$docente['ape_materno']}",
                    'nombre_anio_escolar' => $anio_escolar["nombre"],
                    'accion' => $accion,
                );
                $i++;
            }
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/registro_auxiliar/listar_docentes', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para mostrar el formulario de asignar docente a un curso*/
    public function asignar_docente() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Asignar Docente';
        $data['titulo_modulo'] = "Asignar Docente";
        /* ------------------------------------------------------------ */

        //Carga combo de años escolares
        $anio_escolar = $this->m_anio_escolar->mostrar_activos();
        foreach ($anio_escolar as $ae) { //Recorremos arreglo y llenamos combo
            $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
        }
        $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', '', 'Seleccione una opción');
        unset($this->_result);

        //Carga combo de docentes
        //$docentes = $this->m_usuario->mostrar_cuando(array('usuario.id_privilegio' => 2));
        //$docentes = $this->m_usuario->mostrar_cuando_where(array('usuario.id_privilegio' => 2), array('usuario.id_privilegio' => 1, 'usuario.id_privilegio' => 5));
        $query = $this->db->query("SELECT US.id_usuario, PE.nombres, PE.ape_paterno, PE.ape_materno FROM usuario US , persona PE WHERE US.id_privilegio IN(1,2,5) AND PE.id_usuario = US.id_usuario;");
        $docentes = $query->result_array();
        //print_r($query->result_array()); exit;
    
        if (!empty($docentes)) {
            foreach ($docentes as $doc) { //Recorremos arreglo y llenamos combo
                $this->_result[$doc['id_usuario']] = $doc['nombres'] . ' ' . $doc['ape_paterno'] . ' ' . $doc['ape_materno'];
            }
            $data['combo_docente'] = $this->documento->generar_dropdown($this->_result, 'id_docente', '', 'Seleccione una opción', 'search');
            unset($this->_result);
        }

        //Carga combo de cursos
        //$cursos = $this->m_curso->mostrar_activos(); //Todos los cursos activos
        // foreach ($cursos as $cur) { //Recorremos arreglo y llenamos combo
        //     $this->_result[$cur['id_curso']] = $cur['nombre'];
        // }
        $this->_result = array();
        $data['combo_curso'] = $this->documento->generar_dropdown($this->_result, 'id_curso', '', 'Seleccione una opción', 'search');
        unset($this->_result);

        //Carga combo de grados
        //$grados = $this->m_grado->mostrar_activos();
        // foreach ($grados as $gra) {
        //     $seccion_grado = $gra['seccion'] == 'Única' ? '' : $gra['seccion'];
        //     $this->_result[$gra['id_grado']] = $gra['nombre'] . ' ' . $seccion_grado;
        // }
        $this->_result = array();
        $data['combo_grado'] = $this->documento->generar_dropdown($this->_result, 'id_grado', '', 'Seleccione una opción');
        unset($this->_result);

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/registro_auxiliar/form_asignar_docente', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para mostrar el formulario con los datos cargados para editar docente a un curso*/
    public function editar($id_curso_docente = '') {
        $login = $this->session_sistema->datos_usuario_logueado();
        if ($id_curso_docente == '') {
            echo $this->url_comp->direccionar(base_url() . 'registro_auxiliar/docentes', TRUE);
            EXIT;
        }
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Editar Docente al Curso';
        $data['titulo_modulo'] = "Editar Docente al Curso";
        /* ------------------------------------------------------------ */
        //Obtenemos data de la tabla curso_docente
        $curso_docente = $this->m_curso_docente->mostrar(array('curso_docente.id_curso_docente' => $id_curso_docente));

        //Validamos que exista data
        if (!empty($curso_docente)) {

            if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
                //Seteamos el id_curso_docente
                $data["id_curso_docente"] = $id_curso_docente;
                //Carga y selecciona combo de año escolar
                $anio_escolar = $this->m_anio_escolar->mostrar_activos();
                foreach ($anio_escolar as $ae) { //Recorremos arreglo y llenamos combo
                    $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
                }
                $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', $curso_docente['id_anio_escolar'], 'Seleccione una opción', 'search');
                unset($this->_result);

                //Seleccionamos el nivel educativo
                $curso = $this->m_curso->mostrar(array("curso.id_curso" => $curso_docente["id_curso"]));
                $grado = $this->m_grado->mostrar(array("grado.id_grado" => $curso["id_grado"]));
                $data["id_nivel_educativo"] = $grado["id_nivel_educativo"];

                //Carga y selecciona combo de grados
                $grados = $this->m_grado->mostrar_activos();
                foreach ($grados as $gra) {
                    $seccion_grado = $gra['seccion'] == 'Única' ? '' : $gra['seccion'];
                    $this->_result[$gra['id_grado']] = $gra['nombre'] . ' ' . $seccion_grado;
                }
                $data['combo_grado'] = $this->documento->generar_dropdown($this->_result, 'id_grado', $curso['id_grado'], 'Seleccione una opción');
                unset($this->_result);

                //Carga y selecciona combo de cursos
                $cursos = $this->m_curso->mostrar_activos(); //Todos los cursos activos
                foreach ($cursos as $cur) { //Recorremos arreglo y llenamos combo
                    $this->_result[$cur['id_curso']] = $cur['nombre'];
                }
                $data['combo_curso'] = $this->documento->generar_dropdown($this->_result, 'id_curso', $curso_docente['id_curso'], 'Seleccione una opción', 'search');
                unset($this->_result);

                //Carga y selecciona combo de docentes
                //$docentes = $this->m_usuario->mostrar_cuando(array('usuario.id_privilegio' => 2));
                $docentes = $this->m_usuario->mostrar_cuando_where(array('usuario.id_privilegio' => 2), array('usuario.id_privilegio' => 1));
                foreach ($docentes as $d) { //Recorremos arreglo y llenamos combo
                    $this->_result[$d['id_usuario']] = "{$d['nombres']} {$d['ape_paterno']} {$d['ape_materno']}";

                }
                $data['combo_docente'] = $this->documento->generar_dropdown($this->_result, 'id_docente', $curso_docente['id_usuario'], 'Seleccione una opción', 'search');
                unset($this->_result);

            } else {
                echo $this->url_comp->direccionar(base_url() . 'apertura_estudio/listar', TRUE);
                EXIT;
            }
        } else {
            echo $this->url_comp->direccionar(base_url() . 'apertura_estudio/listar', TRUE);
            EXIT;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/registro_auxiliar/form_asignar_docente', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para guardar la accion de asignar docente */
    public function accion() {
        date_default_timezone_set("America/Lima"); //Definimos zona horaria
        //Obtenemos datos
        $id_curso_docente = $this->input->post('id_curso_docente');
        $id_anio_escolar = $this->input->post('id_anio_escolar');
        $id_nivel_educativo = $this->input->post('id_nivel_educativo');
        $id_grado = $this->input->post('id_grado');
        $id_curso = $this->input->post('id_curso');
        $id_docente = $this->input->post('id_docente');

        //VALIDACION DE CAMPOS
        $error = '';
        $error .= $this->mantenimiento->validacion($id_anio_escolar, 'required', 'Año Escolar');
        $error .= $this->mantenimiento->validacion($id_nivel_educativo, 'required', 'Nivel Educativo');
        $error .= $this->mantenimiento->validacion($id_grado, 'required', 'Grado');
        $error .= $this->mantenimiento->validacion($id_curso, 'required', 'Curso');
        $error .= $this->mantenimiento->validacion($id_docente, 'required', 'Docente');

        //Si hay error de validacion, mostramos mensaje
        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }

        switch ($id_curso_docente) {
        case '': //AGREGAR
            //Validamos que el curso no tenga docente asignado
            if ($this->m_curso_docente->exists(array("curso_docente.id_curso" => $id_curso, "curso_docente.id_anio_escolar" => $id_anio_escolar)) > 0) {
                echo $this->alerta->alerta_error('El curso ya tiene docente asignado.');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'registro_auxiliar/docentes', '1000');
                exit;
            }
            //Data para agregar
            $data_curso_docente = array(
                'id_curso' => $id_curso,
                'id_usuario' => $id_docente,
                'id_anio_escolar' => $id_anio_escolar,
                'fecha_registro' => date("Y-m-d H:i:s"),
            );
            //Insertamos
            $resultSet = $this->m_curso_docente->insertar($data_curso_docente);
            if ($resultSet) {
                echo $this->alerta->alerta_exito('Se asignó el docente correctamente.');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'registro_auxiliar/docentes', '1000');
                EXIT;
            }
            break;

        default: //EDITAR
            //Data para agregar
            $data_curso_docente = array(
                'id_usuario' => $id_docente,
                'fecha_modificacion' => date("Y-m-d H:i:s"),
            );
            $resultSet = $this->m_curso_docente->actualizar($data_curso_docente, 'id_curso_docente', $id_curso_docente);
            if ($resultSet) {
                echo $this->alerta->alerta_exito('Se ha editado correctamente');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'registro_auxiliar/docentes', '1000');
                EXIT;
            }
            break;
        }
    }

    /* Filtra grados segun nivel educativo */
    public function grados_por_nivel() {
        //Recibimos datos
        $id_nivel_educativo = $this->input->post('id_nivel_educativo');
        //Lista todos los cursos segun nivel
        $grados = $this->m_grado->mostrar_cuando(array('grado.id_nivel_educativo' => $id_nivel_educativo));
        if (!empty($grados)) { //Si no esta vacio
            foreach ($grados as $grad) { //Recorre todos los cursos
                $seccion = $grad['seccion'] == 'Única' ? '' : $grad['seccion'];
                $data[] = array(
                    'id_grado' => (int) $grad['id_grado'],
                    'nombre_grado' => (string) "{$grad["nombre"]} $seccion",
                );
            }
        } else {
            $data[] = array(
                'id_curso' => 0,
                'nombre_grado' => (string) 'vacio',
            );
        }
        echo json_encode($data);
    }

    /* Filtra cursos segun grado */
    public function cursos_por_grado() {
        //Recibimos datos
        $id_grado = $this->input->post('id_grado');
        $id_anio_escolar = $this->input->post('id_anio_escolar');
        //Lista todos los cursos segun nivel
        $cursos = $this->m_curso->mostrar_cuando(array('curso.id_grado' => $id_grado, 'curso.id_anio_escolar' => $id_anio_escolar));
        if (!empty($cursos)) { //Si no esta vacio
            foreach ($cursos as $curs) { //Recorre todos los cursos
                $data[] = array(
                    'id_curso' => (int) $curs['id_curso'],
                    'nombre_curso' => (string) $curs['nombre'] . " - " . $curs['nombre_grado'],
                );
            }
        } else {
            $data[] = array(
                'id_curso' => 0,
                'nombre_curso' => (string) 'vacio',
            );
        }
        echo json_encode($data);
    }

    /* Funcion para mostrar todos los cursos a los que esta asignado el docente */
    public function listar_cursos_docente($id_docente, $id_anio_escolar = "") {
        $login = $this->session_sistema->datos_usuario_logueado();
        $docente = $this->m_usuario->mostrar(array('usuario.id_usuario' => $id_docente));
        $nombre_docente = "{$docente["nombres"]} {$docente["ape_paterno"]} {$docente["ape_materno"]}";
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Cursos del Docente';
        $data['titulo_modulo'] = "Cursos del Docente $nombre_docente";
        /* ------------------------------------------------------------ */
        $id_anio_escolar_actual = $id_anio_escolar != "" ? $id_anio_escolar : $this->m_anio_escolar->maximo_id("id_anio_escolar");
        $cursos_docente = $this->m_curso_docente->mostrar_cuando(array("curso_docente.id_usuario" => $id_docente, "curso_docente.id_anio_escolar" => $id_anio_escolar_actual));

        $data["id_docente"] = $id_docente;

        $i = 1;
        foreach ($cursos_docente AS $cd) {
            $curso = $this->m_curso->mostrar(array('curso.id_curso' => $cd['id_curso']));
            $anio_escolar = $this->m_anio_escolar->mostrar(array('anio_escolar.id_anio_escolar' => $cd['id_anio_escolar']));
            $accion = $login["usuario_privilegio"] == 2 ? "" : $this->mantenimiento->accion($cd['id_curso_docente'], 'editar', 'registro_auxiliar', "");

            $data['lista'][] = array(
                'numero' => $i,
                'nombre_curso' => $curso['nombre'],
                'nombre_nivel_educativo' => $curso['nombre_nivel_educativo'],
                'nombre_grado' => $curso['nombre_grado'],
                'seccion_grado' => $curso['seccion_grado'],
                'nombre_anio_escolar' => $anio_escolar['nombre'],
                'id_curso' => $curso['id_curso'],
                'id_anio_escolar' => $anio_escolar['id_anio_escolar'],
                'accion' => $accion,
            );
            $i++;
        }

        //Carga combo de año escolar
        $anio_escolar = $this->m_anio_escolar->mostrar_activos();
        if (!empty($anio_escolar)) {
            foreach ($anio_escolar as $ae) {
                $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar_docente', $id_anio_escolar_actual, array());
            unset($this->_result);
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/registro_auxiliar/listar_cursos_docente', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para obtener las rutas para poder calificar los cursos */
    public function calificar_curso() {
        header('Content-Type: application/json');
        //Obtenemos valores
        $id_curso = $this->input->post("id_curso");
        $id_anio_escolar = $this->input->post("id_anio_escolar");

        //Segun el curso podemos obtener el grado y el nivel educativo al que pertenece
        $curso = $this->m_curso->mostrar(array("curso.id_curso" => $id_curso));
        $id_nivel_educativo = $curso["grado_id_nivel_educativo"];

        //data a enviar
        $data["nombre_curso"] = $curso["nombre"];
        $data["id_nivel_educativo"] = $id_nivel_educativo;
        $data["id_grado"] = $curso["id_grado"];

        $bimestres = $this->m_bimestre->mostrar_todo();
        $data["nombre_bimestre_1"] = $bimestres[0]["nombre"];
        $data["nombre_bimestre_2"] = $bimestres[1]["nombre"];
        $data["nombre_bimestre_3"] = $bimestres[2]["nombre"];
        $data["nombre_bimestre_4"] = $bimestres[3]["nombre"];

        //Segun el nivel educativo armamos las rutas y los nombres para poder registrar la notas
        switch ($id_nivel_educativo) {
        case "1": //INICIAL
            /*
            $data["ruta_bimestre_1"] = base_url() . "inicial/calificar/$id_curso/$id_anio_escolar/{$bimestres[0]["id_bimestre"]}";
            $data["ruta_bimestre_2"] = base_url() . "inicial/calificar/$id_curso/$id_anio_escolar/{$bimestres[1]["id_bimestre"]}";
            $data["ruta_bimestre_3"] = base_url() . "inicial/calificar/$id_curso/$id_anio_escolar/{$bimestres[2]["id_bimestre"]}";
            $data["ruta_bimestre_4"] = base_url() . "inicial/calificar/$id_curso/$id_anio_escolar/{$bimestres[3]["id_bimestre"]}";
             */
            break;
        case "2": //PRIMARIA
            $data["ruta_bimestre_1"] = base_url() . "primaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[0]["id_bimestre"]}";
            $data["ruta_bimestre_2"] = base_url() . "primaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[1]["id_bimestre"]}";
            $data["ruta_bimestre_3"] = base_url() . "primaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[2]["id_bimestre"]}";
            $data["ruta_bimestre_4"] = base_url() . "primaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[3]["id_bimestre"]}";
            break;
        case "3": //SECUNDARIA
            $data["ruta_bimestre_1"] = base_url() . "secundaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[0]["id_bimestre"]}";
            $data["ruta_bimestre_2"] = base_url() . "secundaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[1]["id_bimestre"]}";
            $data["ruta_bimestre_3"] = base_url() . "secundaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[2]["id_bimestre"]}";
            $data["ruta_bimestre_4"] = base_url() . "secundaria/calificar/$id_curso/$id_anio_escolar/{$bimestres[3]["id_bimestre"]}";

            break;
        }
        echo json_encode($data);
    }

}
