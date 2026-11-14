<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Matricula extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_matricula", "m_distrito", "m_grado", "m_alumno", "m_anio_escolar", "m_bimestre", "m_curso", "m_nivel_educativo",
            "m_mes_escolar", "m_nota_alumno_primaria", "m_asistencia_alumno", "m_comportamiento", "m_nota_alumno_secundaria", "m_usuario");
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
        $this->items['matricula_activo'] = 'active';
    }

    /* Funcion para listar todas las matrículas */
    public function listar($id_anio_escolar = "") {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Matriculados';
        $data['titulo_modulo'] = "Lista de Matriculados";
        /* ------------------------------------------------------------ */
        $id_anio_escolar = $id_anio_escolar != "" ? $id_anio_escolar : $this->m_anio_escolar->maximo_id("id_anio_escolar");

        //$id_anio_escolar_actual = $this->m_anio_escolar->maximo_id("id_anio_escolar"); // Ultimo año escolar aperturado
        $lista = $this->m_matricula->mostrar_cuando(array("matricula.id_anio_escolar" => $id_anio_escolar)); //Muestra todas las matriculas

        //Carga combo de año escolar
        $anio_escolar = $this->m_anio_escolar->mostrar_activos();
        if (!empty($anio_escolar)) {
            foreach ($anio_escolar as $ae) {
                $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar_matricula', $id_anio_escolar, array());
            unset($this->_result);
        }

        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($lista)) {
                $i = 1;
                foreach ($lista as $l) {
                    $alumno = $this->m_alumno->mostrar(array('alumno.id_alumno' => $l['id_alumno']));
                    $grado = $this->m_grado->mostrar(array('grado.id_grado' => $l['id_grado']));
                    $nivel_educativo = $this->m_nivel_educativo->mostrar(array('nivel_educativo.id_nivel_educativo' => $l['id_nivel_educativo']));
                    $anio_escolar = $this->m_anio_escolar->mostrar(array('anio_escolar.id_anio_escolar' => $l['id_anio_escolar']));
                    $usuario_alumno = $this->m_usuario->mostrar(array('usuario.id_usuario' => $alumno['id_usuario']));
                    $accion = $this->mantenimiento->accion($l['id_matricula'], 'editar', 'matricula', $l['estado']);
                    $data['lista'][] = array(
                        'numero' => $i,
                        'nombre' => "{$alumno["apellido_paterno"]} {$alumno["apellido_materno"]} {$alumno["nombre"]}",
                        'grado' => $grado['nombre'],
                        'seccion' => $grado['seccion'],
                        'dni' => $alumno['dni'],
                        'nivel_educativo' => $grado['nombre_nivel_educativo'],
                        'anio_escolar' => $anio_escolar['nombre'],
                        'usuario' => $usuario_alumno['usuario'],
                        'accion' => $accion,
                    );
                    $i++;
                }
            }
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/matricula/listar_matricula', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para cargar plantilla para matricular alumno */
    public function agregar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Agregar Matrícula';
        $data['titulo_modulo'] = "Agregar Matricula";
        $data['tipo'] = "agregar";
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador, puede editar

            //Carga combo de distritos
            foreach ($this->m_distrito->mostrar_activos() as $dist) {
                $this->_result[$dist['id_distrito']] = $dist['nombre'];
            }
            $data['id_distrito'] = $this->documento->generar_dropdown($this->_result, 'id_distrito', '', 'Seleccione una opción', 'search');
            unset($this->_result);

            //Carga combo de grados
            $grados = $this->m_grado->mostrar_activos();
            foreach ($grados as $gra) {
                $seccion_grado = $gra['seccion'] == 'Única' ? '' : $gra['seccion'];
                $this->_result[$gra['id_grado']] = $gra['nombre'] . ' ' . $seccion_grado;
            }
            $data['id_grado'] = $this->documento->generar_dropdown($this->_result, 'id_grado', '', 'Seleccione una opción');
            unset($this->_result);

            //Carga combo de año escolar
            $anio_escolar = $this->m_anio_escolar->mostrar_activos();
            if (!empty($anio_escolar)) {
                foreach ($anio_escolar as $ae) {
                    $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
                }
                $data['id_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', '', 'Seleccione una opción');
                unset($this->_result);
            }

        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/matricula/form_matricula', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para agregar o modificar matricula */
    public function accion() {
        date_default_timezone_set("America/Lima"); //Definimos zona horaria

        $id_matricula = $this->input->post('id_matricula');
        $dni = trim($this->input->post('dni'));
        $nombres = trim($this->input->post('nombres'));
        $ape_paterno = trim($this->input->post('ape_paterno'));
        $ape_materno = trim($this->input->post('ape_materno'));
        $fecha_nacimiento = $this->input->post('fecha_nacimiento') == '' ? null : $this->input->post('fecha_nacimiento');
        $direccion = trim($this->input->post('direccion'));
        $id_distrito = $this->input->post('id_distrito');
        $id_anio_escolar = $this->input->post('id_anio_escolar');
        $id_nivel_educativo = $this->input->post('id_nivel_educativo');
        $id_grado = $this->input->post('id_grado');
        $usuario = trim($this->input->post('usuario'));
        $contrasena = trim($this->input->post('contrasena'));

        //var_dump($_POST); exit;

        //VALIDACION DE CAMPOS
        $error = '';
        $error .= $this->mantenimiento->validacion($dni, 'required|minlenght[8]|maxlenght[15]', 'DNI');
        $error .= $this->mantenimiento->validacion($nombres, 'required|maxlenght[100]', 'Nombres');
        $error .= $this->mantenimiento->validacion($ape_paterno, 'required|maxlenght[100]', 'Apellido Paterno');
        $error .= $this->mantenimiento->validacion($ape_materno, 'require|maxlenght[100]', 'Apellido Materno');
        $error .= $this->mantenimiento->validacion($fecha_nacimiento, 'required', 'Fecha de Nacimiento');
        $error .= $this->mantenimiento->validacion($direccion, 'required', 'Dirección');
        $error .= $this->mantenimiento->validacion($id_distrito, 'required', 'Distrito');
        $error .= $this->mantenimiento->validacion($id_anio_escolar, 'required', 'Año Escolar');
        $error .= $this->mantenimiento->validacion($id_nivel_educativo, 'required', 'Nivel Educativo');
        $error .= $this->mantenimiento->validacion($id_grado, 'required', 'Grado');

        //Si hay error de validacion, manda mensaje de error
        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }

        switch ($id_matricula) {
        case '': //AGREGAR

            //Consultamos alumno segun su dni
            $alumno = $this->m_alumno->existe_alumno($dni);
            //Validamos que el alumno no exista en la tabla alumno
            if ($alumno) { //Si es true es porque el alumno existe y no se deberia a agregar en la tabla alumno pero si en la tabla matricula
                //Validamos que el alumno no esté matriculado en el año escolar
                if ($this->m_matricula->existe_matricula($dni, $id_anio_escolar)) { //Si es true es porque la matrícula existe y mandamos mensaje de error
                    echo $this->alerta->alerta_error("El alumno ya está matriculado.");
                    exit;
                } else { //Si no está matriculado, entonces lo insertamos en la tabla matrícula
                    //Insertamos en la tabla matricula
                    $resultSetMatricula = $this->registrar_matricula($alumno, $id_grado, $id_nivel_educativo, $id_anio_escolar);

                }
            } else { //Si no existe el alumno

                //Hacemos las validaciones antes de registrar el usuario para el alumno
                $error .= $this->mantenimiento->validacion($usuario, 'required|alphanumeric|maxlenght[50]', 'Usuario');
                $error .= $this->mantenimiento->validacion($contrasena, 'required|minlenght[5]|maxlenght[50]', 'Contraseña');

                //Si hay error de validacion
                if ($error != '') {
                    echo $this->alerta->alerta_error($error, TRUE);
                    exit;
                }

                //Verificamos que el usuario no exista
                if ($this->m_usuario->existe_usuario($usuario) === TRUE) {
                    echo $this->alerta->alerta_error('El usuario ya está registrado.', FALSE);
                    exit;
                }

                //Insertamos en la tabla usuario
                $resultSet_usuario = $this->registra_usuario($usuario, $contrasena);
                //Insertamos en la tabla alumno
                $resultSetAlumno = $this->registrar_alumno($nombres, $ape_paterno, $ape_materno, $dni, $direccion, $id_distrito, $fecha_nacimiento, $resultSet_usuario);
                //Insertamos en la tabla matricula
                $resultSetMatricula = $this->registrar_matricula($resultSetAlumno, $id_grado, $id_nivel_educativo, $id_anio_escolar);
            }
            if($id_nivel_educativo != 1){ //SI NO ES INICIAL GENERA SUS REGISTROS..
                if ($resultSetMatricula) { //Si se matriculo correctamente generamos todas sus notas
                    $resultSetNotasCursos = $this->genera_notas_cursos($resultSetMatricula, $id_grado, $id_nivel_educativo, $id_anio_escolar);
                    $resultSetAsistencia = $this->genera_asistencia($resultSetMatricula, $id_anio_escolar, $id_grado);
                    $resultSetComportamiento = $this->genera_comportamiento($resultSetMatricula, $id_anio_escolar, $id_grado);
                }
            }
            //if ($resultSetNotasCursos && $resultSetAsistencia && $resultSetComportamiento) {
                echo $this->alerta->alerta_exito('Se ha matriculado correctamente');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'matricula/listar', '1000');
                exit;
            //}
            break;

        default: //EDITAR
            //----- Para la edicion de la matricula, no se editan campos del alumno, solo netamente matricula -----//
            $resultSet = true;
            //Evaluamos que no se haya cambiado año escolar, nivel educativo o grado
            //$resultSet = $this->proceso_actualizar_matricula($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Actualizamos la matricula y todas las notas registradas
            if ($this->valida_cambio_matricula($id_matricula, $id_anio_escolar, $id_nivel_educativo, $id_grado)) { //Si hay cambios
                $resultSet = $this->proceso_actualizar_matricula($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Actualizamos la matricula y todas las notas registradas
            }
            if ($resultSet) { //Si se actualizo correctamente la matricula
                echo $this->alerta->alerta_exito('Se actualizó correctamente');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'matricula/listar', '1000');
                exit;
            } else {
                echo $this->alerta->alerta_error('Error al actualizar');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'matricula/listar', '1000');
                exit;
            }
            break;
        }
    }

    /* Funcion para cargar plantilla del alumno con los datos */
    public function editar($id_matricula = '') {
        $login = $this->session_sistema->datos_usuario_logueado();
        if ($id_matricula == '') {
            echo $this->url_comp->direccionar(base_url() . 'matricula/listar', TRUE);
            exit;
        }
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Editar Matrícula';
        $data['titulo_modulo'] = "Editar Matrícula";
        $data['tipo'] = 'editar';
        /* ------------------------------------------------------------ */
        $matricula = $this->m_matricula->mostrar(array('matricula.id_matricula' => $id_matricula));
        $alumno = $this->m_alumno->mostrar(array('alumno.id_alumno' => $matricula['id_alumno']));

        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador, puede editar
            if (!empty($matricula)) {
                $data['id_matricula'] = $matricula['id_matricula'];
                $data['dni'] = $alumno['dni'];
                $data['nombres'] = $alumno['nombre'];
                $data['ape_paterno'] = $alumno['apellido_paterno'];
                $data['ape_materno'] = $alumno['apellido_materno'];
                $data['fecha_nacimiento'] = $alumno['fecha_nacimiento'];
                $data['direccion'] = $alumno['direccion'];
                $data['id_nivel_educativo'] = $matricula['id_nivel_educativo'];
            } else {
                echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
                exit;
            }

            //Carga combo de distritos con valor de bd
            foreach ($this->m_distrito->mostrar_activos() as $distr) {
                $this->_result[$distr['id_distrito']] = $distr['nombre'];
            }
            $data['id_distrito'] = $this->documento->generar_dropdown($this->_result, 'id_distrito', $alumno['id_distrito'], 'Seleccione una opción');
            unset($this->_result);

            //Carga combo de año escolar
            $anio_escolar = $this->m_anio_escolar->mostrar_activos();
            foreach ($anio_escolar as $ae) {
                $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
            }
            $data['id_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', $matricula["id_anio_escolar"], 'Seleccione una opción');
            unset($this->_result);

            //Carga y selecciona combo de grados
            $grados = $this->m_grado->mostrar_activos();
            foreach ($grados as $gra) {
                $seccion_grado = $gra['seccion'] == 'Única' ? '' : $gra['seccion'];
                $this->_result[$gra['id_grado']] = $gra['nombre'] . ' ' . $seccion_grado;
            }
            $data['id_grado'] = $this->documento->generar_dropdown($this->_result, 'id_grado', $matricula['id_grado'], 'Seleccione una opción');
            unset($this->_result);
        } else {
            echo $this->url_comp->direccionar(base_url() . 'matricula/listar', TRUE);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/matricula/form_matricula', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para buscar alumno por dni */
    public function buscar_alumno() {
        //Recibimos datos
        $dni_alumno = $this->input->post("dni_alumno");
        $alumno = $this->m_alumno->mostrar(array('alumno.dni' => $dni_alumno));

        if (!empty($alumno)) {
            $data = array(
                'id_alumno' => $alumno['id_alumno'],
                'nombre' => $alumno['nombre'],
                'apellido_paterno' => $alumno['apellido_paterno'],
                'apellido_materno' => $alumno['apellido_materno'],
                'dni' => $alumno['dni'],
                'fecha_nacimiento' => $alumno['fecha_nacimiento'],
                'direccion' => $alumno['direccion'],
                'id_distrito' => $alumno['id_distrito'],
                'encontrado' => true,
            );
        } else {
            //$data = false;
            $data = array(
                'dni' => $dni_alumno,
                'encontrado' => false,
            );
        }
        echo json_encode($data);
    }

    /* Funcion para filtrar grados segun nivel educativo */
    public function grados_por_nivel() {
        //Recibimos datos
        $id_nivel_educativo = $this->input->post('id_nivel_educativo');

        //Lista todos los grados segun nivel
        $grados = $this->m_grado->mostrar_cuando(array('grado.id_nivel_educativo' => $id_nivel_educativo));
        if (!empty($grados)) { //Si no esta vacio
            foreach ($grados as $grad) { //Recorre todos los cursos
                $seccion = $grad['seccion'] == 'Única' ? '' : $grad['seccion'];
                $data[] = array(
                    'id_grado' => (int) $grad['id_grado'],
                    'nombre_grado' => (string) $grad['nombre'] . ' ' . $seccion,
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

    /* ------------------------------------ FUNCIONES PRIVADAS ------------------------------------ */
    /* -------------------------------------------------------------------------------------------- */

    /* Funcion para registrar Matricula OK */
    private function registrar_matricula($id_alumno, $id_grado, $id_nivel_educativo, $id_anio_escolar) {
        //Data a insertar en la tabla matricula
        $data_matricula = array(
            'id_alumno' => $id_alumno,
            'id_grado' => $id_grado,
            'id_nivel_educativo' => $id_nivel_educativo,
            'id_anio_escolar' => $id_anio_escolar,
            'fecha_matricula' => date("Y-m-d H:i:s"),
        );
        //Insertamos en la tabla matricula
        return $this->m_matricula->insertar($data_matricula);
    }

    /* Funcion para actualizar Matricula OK*/
    private function actualizar_matricula($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar) {
        //Data a insertar en la tabla matricula
        $data_matricula = array(
            'id_grado' => $id_grado,
            'id_nivel_educativo' => $id_nivel_educativo,
            'id_anio_escolar' => $id_anio_escolar,
            'fecha_modificacion' => date("Y-m-d H:i:s"),
        );
        //Actualizamos en la tabla matricula
        return $this->m_matricula->actualizar($data_matricula, 'id_matricula', $id_matricula);
    }

    /* Funcion para actualizar Asistencia OK */
    private function actualizar_asistencia($id_matricula, $id_grado, $id_anio_escolar) {
        //Data a actualizar en la tabla asistencia_alumno
        $data_asistencia = array(
            'id_grado' => $id_grado,
            'id_anio_escolar' => $id_anio_escolar,
        );
        //Actualizamos en la tabla asistencia_alumno
        return $this->m_asistencia_alumno->actualizar($data_asistencia, 'id_matricula', $id_matricula);
    }

    /* Funcion para actualizar Comportamiento OK */
    private function actualizar_comportamiento($id_matricula, $id_grado, $id_anio_escolar) {
        //Data a insertar en la tabla comportamiento
        $data_comportamiento = array(
            'id_grado' => $id_grado,
            'id_anio_escolar' => $id_anio_escolar,
        );
        //Actualizamos en la tabla comportamiento
        return $this->m_comportamiento->actualizar($data_comportamiento, 'id_matricula', $id_matricula);
    }

    /* Funcion para actualizar Matricula, se eliminan todas las notas registradas del alumno y se vuelve
    a insertar sus nuevos registros de notas para el nuevo grado OK*/
    private function proceso_actualizar_matricula($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar) {

        //Obtenemos matricula actual
        $matricula_actual = $this->m_matricula->mostrar(array("matricula.id_matricula" => $id_matricula));
        $id_nivel_educativo_actual = $matricula_actual["id_nivel_educativo"];
        //Eliminamos las notas actuales segun el nivel educativo actual de la matricula
        if ($id_nivel_educativo_actual == 1) { //INICIAL
            //$this->m_nota_alumno_inicial->eliminar(array("nota_alumno_inicial.id_matricula" => $id_matricula)); //Eliminamos todos los registros de notas actuales
        }
        if ($id_nivel_educativo_actual == 2) { //PRIMARIA
            $this->m_nota_alumno_primaria->eliminar(array("nota_alumno_primaria.id_matricula" => $id_matricula)); //Eliminamos todos los registros de notas actuales
        }
        if ($id_nivel_educativo_actual == 3) { //SECUNDARIA
            $this->m_nota_alumno_secundaria->eliminar(array("nota_alumno_secundaria.id_matricula" => $id_matricula));
        }

        //Evaluamos el nuevo nivel educativo
        switch ($id_nivel_educativo) {
        case "1": //INICIAL
            //$this->actualizar_matricula($id_matricula, $id_apoderado, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Actualizamos matricula
            //$this->actualizar_asistencia($id_matricula, $id_grado, $id_anio_escolar); //Actualizamos asistencia
            //$this->actualizar_actitudinal($id_matricula, $id_grado, $id_anio_escolar); //Actualizamos actitudinal - PARA INICIAL SE CALIFICA ACTITUDINAL
            //return $this->genera_notas_cursos($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Insertamos nuevos registros y retornamos respuesta
            break;
        case "2": //PRIMARIA
            $this->actualizar_matricula($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Actualizamos matricula
            $this->actualizar_asistencia($id_matricula, $id_grado, $id_anio_escolar); //Actualizamos asistencia
            $this->actualizar_comportamiento($id_matricula, $id_grado, $id_anio_escolar); //Actualizamos actitudinal
            return $this->genera_notas_cursos($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Insertamos nuevos registros y retornamos respuesta

            break;

        case "3": //SECUNDARIA
            $this->actualizar_matricula($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Actualizamos matricula
            $this->actualizar_asistencia($id_matricula, $id_grado, $id_anio_escolar); //Actualizamos asistencia
            $this->actualizar_comportamiento($id_matricula, $id_grado, $id_anio_escolar); //Actualizamos actitudinal
            return $this->genera_notas_cursos($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar); //Insertamos nuevos registros y retornamos respuesta
            break;
        }
    }

    /* Funcion para registrar Alumno OK */
    private function registrar_alumno($nombres, $ape_paterno, $ape_materno, $dni, $direccion, $id_distrito, $fecha_nacimiento, $id_usuario) {
        //Datos a insertar en la tabla Alumno
        $data_alumno = array(
            'nombre' => $nombres,
            'apellido_paterno' => $ape_paterno,
            'apellido_materno' => $ape_materno,
            'dni' => $dni,
            'direccion' => $direccion,
            'id_distrito' => $id_distrito,
            'fecha_nacimiento' => $fecha_nacimiento,
            'fecha_registro' => date("Y-m-d H:i:s"),
            'id_usuario' => $id_usuario,
        );
        return $this->m_alumno->insertar($data_alumno); //Insertamos en la tabla Alumno
    }

    /* Funcion para agregar notas en blanco del alumno por cada curso y bimestre OK */
    private function genera_notas_cursos($id_matricula, $id_grado, $id_nivel_educativo, $id_anio_escolar) {

        //Obtenemos todos los bimestres
        $bimestres = $this->m_bimestre->mostrar_todo();
        //Obtenemos todos los cursos segun el grado
        $cursos = $this->m_curso->mostrar_cuando(array("curso.id_grado" => $id_grado, "curso.id_anio_escolar" => $id_anio_escolar));

        switch ($id_nivel_educativo) {
        case "1": //INICIAL
            /*//Recorremos cada bimestre para insertar las notas
            foreach ($cursos as $c) {
            foreach ($bimestres as $b) {
            $data_nota_inicial = array(
            'id_matricula' => $id_matricula,
            'id_bimestre' => $b["id_bimestre"],
            'id_anio_escolar' => $id_anio_escolar,
            'id_curso' => $c["id_curso"],
            );
            //Insertamos en la tabla nota_alumno_inicial
            $resultSetNota = $this->m_nota_alumno_inicial->insertar($data_nota_inicial);
            }
            }*/
            break;

        case "2": //PRIMARIA
            //Recorremos cada bimestre para insertar las notas
            foreach ($cursos as $c) {
                foreach ($bimestres as $b) {
                    $data_nota_primaria = array(
                        'id_matricula' => $id_matricula,
                        'id_bimestre' => $b["id_bimestre"],
                        'id_anio_escolar' => $id_anio_escolar,
                        'id_curso' => $c["id_curso"],
                    );
                    //Insertamos en la tabla nota_alumno_primaria
                    $resultSetNota = $this->m_nota_alumno_primaria->insertar($data_nota_primaria);
                }
            }
            break;

        case "3": //SECUNDARIA
            //Recorremos cada bimestre para insertar las notas
            foreach ($cursos as $c) {
                foreach ($bimestres as $b) {
                    $data_nota_secundaria = array(
                        'id_matricula' => $id_matricula,
                        'id_bimestre' => $b["id_bimestre"],
                        'id_anio_escolar' => $id_anio_escolar,
                        'id_curso' => $c["id_curso"],
                    );
                    //Insertamos en la tabla nota_alumno_secundaria
                    $resultSetNota = $this->m_nota_alumno_secundaria->insertar($data_nota_secundaria);
                }
            }
            break;
        }
        return $resultSetNota;
    }

    /* Funcion para validar que no se haya cambiado año escolar, nivel educativo o grado.
    Retorna TRUE si hay cambios y FALSE si no hay cambios  OK */
    private function valida_cambio_matricula($id_matricula, $id_anio_escolar, $id_nivel_educativo, $id_grado) {
        //Obtenemos los datos de la matricula actual
        $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $id_matricula));
        if ($id_anio_escolar != $matricula["id_anio_escolar"] || $id_nivel_educativo != $matricula["id_nivel_educativo"] || $id_grado != $matricula["id_grado"]) {
            return true;
        }
        return false;
    }

    /* Funcion para generar todas las asistencias de un alumno para todo el año escolar (Marzo - Diciembre) OK */
    private function genera_asistencia($id_matricula, $id_anio_escolar, $id_grado) {
        //Obtenemos todos los meses escolares
        $meses_escolares = $this->m_mes_escolar->mostrar_todo();
        //Recorremos cada mes e insertamos
        foreach ($meses_escolares as $me) {
            $data_asistencia = array(
                "id_grado" => $id_grado,
                "id_anio_escolar" => $id_anio_escolar,
                "id_mes_escolar" => $me["id_mes_escolar"],
                "id_matricula" => $id_matricula,
            );
            //Insertamos en la tabla asistencia_alumno
            $resultSetAsistencia = $this->m_asistencia_alumno->insertar($data_asistencia);
        }
        return $resultSetAsistencia;
    }

    /* Funcion para generar todos los registros de comportamiento de un alumno para todo el año escolar (Marzo - Diciembre) OK*/
    private function genera_comportamiento($id_matricula, $id_anio_escolar, $id_grado) {
        //Obtenemos todos los meses escolares
        $bimestres_escolares = $this->m_bimestre->mostrar_todo();
        //Recorremos cada bimestre e insertamos
        foreach ($bimestres_escolares as $be) {
            $data_comportamiento = array(
                "id_grado" => $id_grado,
                "id_bimestre" => $be["id_bimestre"],
                "id_anio_escolar" => $id_anio_escolar,
                "id_matricula" => $id_matricula,
            );
            //Insertamos en la tabla comportamiento
            $resultSetComportamiento = $this->m_comportamiento->insertar($data_comportamiento);
        }
        return $resultSetComportamiento;
    }

    /* Funcion para registrar usuario del alumno */
    private function registra_usuario($usuario, $contrasena) {
        //Data para el registro de usuario
        $data_usuario = array(
            'usuario' => $usuario,
            'clave' => $contrasena == '' ? $contrasena : md5($contrasena),
            'fecha_registro' => date("Y-m-d H:i:s"),
            'id_privilegio' => 3, //Privilegio de Alumno
        );
        //Insertamos usuario
        return $this->m_usuario->insertar($data_usuario);
    }

}
