<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Apoderado extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_apoderado", "m_parentesco", "m_persona", "m_matricula", "m_anio_escolar");
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
        $this->items['apoderado_activo'] = 'active';
    }

    /* Funcion para listar todos los apoderados */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Apoderados';
        $data['titulo_modulo'] = "Lista de Apoderados";
        /* ------------------------------------------------------------ */
        $lista = $this->m_apoderado->mostrar_todo(); //Muestra todos los apoderados

        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($lista)) {
                $i = 1;
                foreach ($lista AS $items) {
                    $accion = $this->mantenimiento->accion($items['id_apoderado'], 'editar|denegar|permitir', 'apoderado', $items['estado']);
                    $parentesco = $this->m_parentesco->mostrar(array('parentesco.id_parentesco' => $items['id_parentesco']));
                    $data['lista'][] = array(
                        'numero' => $i,
                        'nombre' => $items['apellido_paterno'] . ' ' . $items['apellido_materno'] . ' ' . $items['nombres'],
                        'dni' => $items['dni'],
                        'parentesco' => $parentesco['nombre'],
                        'celular' => $items['celular_1'],
                        'fecha_registro' => $items['fecha_registro'],
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
        $this->smarty_tpl->view('internas/apoderado/listar_apoderado', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para mostrar el formulario de registro de apoderado */
    public function agregar() {
        //Validamos sesion
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Agregar Apoderado';
        $data['titulo_modulo'] = "Agregar Apoderado";
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            //Carga combo de parentesco
            foreach ($this->m_parentesco->mostrar_activos() as $parentesco) {
                $this->_result[$parentesco['id_parentesco']] = $parentesco['nombre'];
            }
            $data['id_parentesco'] = $this->documento->generar_dropdown($this->_result, 'id_parentesco', '', 'Seleccione una opción');
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
        $this->smarty_tpl->view('internas/apoderado/form_apoderado', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para agregar o editar apoderado */
    public function accion() {
        //Validamos sesion
        $this->session_sistema->datos_usuario_logueado();

        //Definimos zona horaria
        date_default_timezone_set("America/Lima");

        $id_apoderado = $this->input->post('id_apoderado');
        $id_parentesco = $this->input->post('id_parentesco');
        $nombres = trim($this->input->post('nombres'));
        $apellido_paterno = trim($this->input->post('apellido_paterno'));
        $apellido_materno = trim($this->input->post('apellido_materno'));
        $dni = trim($this->input->post('dni'));
        $fecha_nacimiento = $this->input->post('fecha_nacimiento') == '' ? null : $this->input->post('fecha_nacimiento');
        $direccion = trim($this->input->post('direccion'));
        $email = trim($this->input->post('email'));
        $celular_1 = trim($this->input->post('celular_1'));
        $celular_2 = trim($this->input->post('celular_2'));
        $telefono = trim($this->input->post('telefono'));
        $referencia_direccion = trim($this->input->post('referencia_direccion'));
        $profesion_ocupacion = trim($this->input->post('profesion_ocupacion'));
        $direccion_trabajo = trim($this->input->post('direccion_trabajo'));
        $id_matricula = $this->input->post('id_matricula');

        //VALIDACION DE CAMPOS
        $error = '';
        $error .= $this->mantenimiento->validacion($id_parentesco, 'required', 'Parentesco');
        $error .= $this->mantenimiento->validacion($nombres, 'required|alphaspecial|maxlenght[100]', 'Nombres');
        $error .= $this->mantenimiento->validacion($apellido_paterno, 'required|alphaspecial|maxlenght[100]', 'Apellido Paterno');
        $error .= $this->mantenimiento->validacion($apellido_materno, 'required|alphaspecial|maxlenght[100]', 'Apellido Materno');
        $error .= $this->mantenimiento->validacion($dni, 'required|numeric|minlenght[8]|maxlenght[8]', 'DNI');
        $error .= $this->mantenimiento->validacion($direccion, 'required', 'Dirección');
        $error .= $this->mantenimiento->validacion($celular_1, 'required', 'Celular');
        $error .= $this->mantenimiento->validacion($id_matricula, 'required', 'Alumno');

        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }

        switch ($id_apoderado) {
        case '': //AGREGAR

            if ($this->m_apoderado->exists(array('apoderado.dni' => $dni)) != '0') { //Si el apoderado esta registrado
                echo $this->alerta->alerta_error('El Apoderado ya ha sido registrado.', FALSE);
                exit;
            }

            $data_apoderado = array(
                'nombres' => $nombres,
                'apellido_paterno' => $apellido_paterno,
                'apellido_materno' => $apellido_materno,
                'dni' => $dni,
                'id_parentesco' => $id_parentesco,
                'celular_1' => $celular_1,
                'celular_2' => $celular_2,
                'telefono' => $telefono,
                'direccion' => $direccion,
                'email' => $email,
                'referencia_direccion' => $referencia_direccion,
                'profesion_ocupacion' => $profesion_ocupacion,
                'direccion_trabajo' => $direccion_trabajo,
                'fecha_nacimiento' => $fecha_nacimiento,
                'fecha_registro' => date("Y-m-d H:i:s"),
                'id_matricula' => $id_matricula,
            );

            $this->m_apoderado->insertar($data_apoderado);

            echo $this->alerta->alerta_exito('Se ha registrado correctamente...');
            echo $this->url_comp->direccionar_tiempo(base_url() . 'apoderado/listar', '1000');
            exit;
            break;

        default: //EDITAR
            if ($this->m_apoderado->exists(array('apoderado.id_apoderado' => $id_apoderado)) !== FALSE) {
                $apoderado = $this->m_apoderado->mostrar(array('apoderado.id_apoderado' => $id_apoderado)); //Obtenemos data del apoderado

                $data = array(
                    'nombres' => $nombres,
                    'apellido_paterno' => $apellido_paterno,
                    'apellido_materno' => $apellido_materno,
                    'dni' => $dni,
                    'id_parentesco' => $id_parentesco,
                    'celular_1' => $celular_1,
                    'celular_2' => $celular_2,
                    'telefono' => $telefono,
                    'direccion' => $direccion,
                    'email' => $email,
                    'referencia_direccion' => $referencia_direccion,
                    'profesion_ocupacion' => $profesion_ocupacion,
                    'direccion_trabajo' => $direccion_trabajo,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'id_matricula' => $id_matricula,
                    'fecha_modificacion' => date("Y-m-d H:i:s"),
                );

                $resultSet = $this->m_apoderado->actualizar($data, 'id_apoderado', $id_apoderado);
                echo $this->alerta->alerta_exito('Se ha editado correctamente.');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'apoderado/listar', '1000');
                exit;
            } else {
                echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
                exit;
            }
            break;
        }

        if ($resultSet === FALSE) {
            echo $this->alerta->mensaje_error('Hubo algunos errores.');
            exit;
        }

    }

    /* Funcion que muestra el formulario de registro de apoderado con todos sus datos */
    public function editar($id_apoderado = '') {
        $login = $this->session_sistema->datos_usuario_logueado();
        if ($id_apoderado == '') {
            echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
            exit;
        }
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Editar Apoderado';
        $data['titulo_modulo'] = "Editar Apoderado";
        /* ------------------------------------------------------------ */
        $apoderado = $this->m_apoderado->mostrar(array('apoderado.id_apoderado' => $id_apoderado));

        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($apoderado)) {
                $data['id_apoderado'] = $apoderado['id_apoderado'];
                $data['id_parentesco'] = $apoderado['id_parentesco'];
                $data['nombres'] = $apoderado['nombres'];
                $data['apellido_paterno'] = $apoderado['apellido_paterno'];
                $data['apellido_materno'] = $apoderado['apellido_materno'];
                $data['dni'] = $apoderado['dni'];
                $data['celular_1'] = $apoderado['celular_1'];
                $data['celular_2'] = $apoderado['celular_2'];
                $data['telefono'] = $apoderado['telefono'];
                $data['direccion'] = $apoderado['direccion'];
                $data['email'] = $apoderado['email'];
                $data['fecha_nacimiento'] = $apoderado['fecha_nacimiento'];
                $data['referencia_direccion'] = $apoderado['referencia_direccion'];
                $data['profesion_ocupacion'] = $apoderado['profesion_ocupacion'];
                $data['direccion_trabajo'] = $apoderado['direccion_trabajo'];
            } else {
                echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
                exit;
            }

            //Carga combo de parentesco con valor de bd
            foreach ($this->m_parentesco->mostrar_activos() as $parentesco) {
                $this->_result[$parentesco['id_parentesco']] = $parentesco['nombre'];
            }
            $data['id_parentesco'] = $this->documento->generar_dropdown($this->_result, 'id_parentesco', $apoderado['id_parentesco'], 'Seleccione una opción');
            unset($this->_result);

            //Carga combo de año escolar con valor de la bd
            $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $apoderado["id_matricula"]));
            $anio_escolar = $this->m_anio_escolar->mostrar_activos();
            if (!empty($anio_escolar)) {
                foreach ($anio_escolar as $ae) {
                    $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
                }
                $data['id_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', $matricula["id_anio_escolar"], 'Seleccione una opción');
                unset($this->_result);
            }

            //Carga combo de matriculados segun el año escolar con valor de la bd

            $lista_matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_anio_escolar" => $matricula["id_anio_escolar"]));
            if (!empty($lista_matriculados)) {
                foreach ($lista_matriculados as $lm) {
                    $this->_result[$lm['id_matricula']] = "{$lm["dni"]} - {$lm["apellido_paterno"]} {$lm["apellido_materno"]}, {$lm["nombre"]}";
                }
                $data['id_matriculados'] = $this->documento->generar_dropdown($this->_result, 'id_matricula', $matricula["id_matricula"], 'Seleccione una opción', 'search');
                unset($this->_result);
            }

        } else {
            echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/apoderado/form_apoderado', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para obtener la lista de matriculados segun el año escolar al momento de registrar un apoderado*/
    public function matriculados_anio_escolar() {
        header('Content-Type: application/json');
        //Recibimos datos
        $id_anio_escolar = $this->input->post('id_anio_escolar');
        //Carga combo de matriculados
        $matriculados = $this->m_matricula->mostrar_cuando(array('matricula.id_anio_escolar' => $id_anio_escolar));

        if (!empty($matriculados)) {
            foreach ($matriculados as $m) {
                $this->_result[$m['id_matricula']] = "{$m["dni"]} - {$m["apellido_paterno"]} {$m["apellido_materno"]}, {$m["nombre"]}";
            }
            $data['combo_matriculados'] = $this->documento->generar_dropdown($this->_result, 'id_matricula', '', 'Seleccione una opción', 'search');
            unset($this->_result);
        } else {
            $data['combo_matriculados'] = "";
        }

        echo json_encode($data);
    }

    public function accion_denegar($id_apoderado = '') {
        if ($id_apoderado == '') {
            echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
            exit;
        }

        $resultSet = $this->m_apoderado->exists(array('apoderado.id_apoderado' => $id_apoderado));
        if ($resultSet === FALSE) {
            echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
            exit;
        }
        $this->m_apoderado->ocultar($id_apoderado);
        echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
    }

    public function accion_permitir($id_apoderado = '') {
        if ($id_apoderado == '') {
            echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
            exit;
        }
        $resultSet = $this->m_apoderado->exists(array('apoderado.id_apoderado' => $id_apoderado));
        if ($resultSet === FALSE) {
            echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
            exit;
        }
        $this->m_apoderado->permitir($id_apoderado);
        echo $this->url_comp->direccionar(base_url() . 'apoderado/listar', TRUE);
    }

}
