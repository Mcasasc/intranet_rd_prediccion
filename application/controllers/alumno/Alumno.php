<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Alumno extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_alumno", "m_distrito", "m_usuario");
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
        $this->items['usuario_activo'] = 'active';
    }

    /* Funcion para listar todos los alumnos */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Alumnos';
        $data['titulo_modulo'] = "Lista de Alumnos";
        /* ------------------------------------------------------------ */

        $lista = $this->m_alumno->mostrar_todo();
        
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($lista)) {
                $i = 1;
                foreach ($lista AS $items) {
                    $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $items["id_usuario"]));
                    $accion = $this->mantenimiento->accion($items['id_alumno'], 'editar|denegar|permitir', 'alumno', $usuario["estado"]);
                    $data['lista'][] = array(
                        'numero' => $i,
                        'nombre_alumno' => "{$items["apellido_paterno"]} {$items["apellido_materno"]} {$items["nombre"]}",
                        'usuario' => $usuario["usuario"],
                        'dni' => $items["dni"],
                        'fecha_nacimiento' => $items["fecha_nacimiento"],
                        'fecha_registro' => $items["fecha_registro"],
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
        $this->smarty_tpl->view('internas/alumno/listar_alumno', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para cargar el formulario con los datos del alumno */
    public function editar($id_alumno = '') {
        //Validamos sesion
        $login = $this->session_sistema->datos_usuario_logueado();
        //Validamos que el id_usuario no este vacio
        if ($id_alumno == '') {
            echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
            exit;
        }
        $data['titulo_pagina'] = "{$this->items["proyecto"]} | Editar Alumno";
        $data['titulo_modulo'] = "Editar Alumno";
        /* ------------------------------------------------------------ */
        $alumno = $this->m_alumno->mostrar(array('alumno.id_alumno' => $id_alumno));
        $usuario = $this->m_usuario->mostrar(array('usuario.id_usuario' => $alumno["id_usuario"]));
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador O super usuario, puede editar
            if (!empty($alumno)) {
                $data['id_alumno'] = $alumno['id_alumno'];
                $data['dni'] = $alumno['dni'];
                $data['nombres'] = $alumno['nombre'];
                $data['ape_paterno'] = $alumno['apellido_paterno'];
                $data['ape_materno'] = $alumno['apellido_materno'];
                $data['fecha_nacimiento'] = $alumno['fecha_nacimiento'];
                $data['direccion'] = $alumno['direccion'];
                $data['usuario'] = $usuario['usuario'];
                $data['id_usuario'] = $usuario['id_usuario'];
            } else {
                echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
                exit;
            }

            /* ------------------------------------------------------------ */

            //Carga combo de distritos con valor de bd
            foreach ($this->m_distrito->mostrar_activos() as $distr) {
                $this->_result[$distr['id_distrito']] = $distr['nombre'];
            }

            $data['id_distrito'] = $this->documento->generar_dropdown($this->_result, 'id_distrito', $alumno['id_distrito'], 'Seleccione una opción');
            unset($this->_result);
        } else {
            echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
            EXIT;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/alumno/form_alumno', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para editar alumno */
    public function accion() {
        //Validamos sesion
        $this->session_sistema->datos_usuario_logueado();

        //Definimos zona horaria
        date_default_timezone_set("America/Lima");

        //Obtenemos datos
        $id_alumno = $this->input->post('id_alumno');
        $dni = trim($this->input->post('dni'));
        $nombres = trim($this->input->post('nombres'));
        $ape_paterno = trim($this->input->post('ape_paterno'));
        $ape_materno = trim($this->input->post('ape_materno'));
        $fecha_nacimiento = $this->input->post('fecha_nacimiento');
        $direccion = trim($this->input->post('direccion'));
        $id_distrito = $this->input->post('id_distrito');
        $usuario = $this->input->post('usuario');
        $contrasena = $this->input->post('contrasena');
        $id_usuario = $this->input->post('id_usuario');

        //Validamos campos
        $error = '';
        $error .= $this->mantenimiento->validacion($dni, 'required|numeric|minlenght[8]|maxlenght[8]', 'DNI');
        $error .= $this->mantenimiento->validacion($nombres, 'required|alphaspecial|maxlenght[100]', 'Nombres');
        $error .= $this->mantenimiento->validacion($ape_paterno, 'required|alphaspecial|maxlenght[100]', 'Apellido Paterno');
        $error .= $this->mantenimiento->validacion($ape_materno, 'required|alphaspecial|maxlenght[100]', 'Apellido Materno');
        $error .= $this->mantenimiento->validacion($fecha_nacimiento, 'required', 'Fecha de Nacimiento');
        $error .= $this->mantenimiento->validacion($direccion, 'required', 'Dirección');
        $error .= $this->mantenimiento->validacion($id_distrito, 'required', 'Distrito');
        $error .= $this->mantenimiento->validacion($usuario, 'required', 'Usuario');

        //Si hay error de validacion
        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }

        if (!empty($id_alumno)) { //Si no esta vacio el id_alumno
            //Validamos DNI
            if ($this->m_alumno->valida_dni_editar($id_alumno, $dni)) {
                //Data a actualizar
                $data_alumno = array(
                    'dni' => $dni,
                    'nombre' => $nombres,
                    'apellido_paterno' => $ape_paterno,
                    'apellido_materno' => $ape_materno,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'direccion' => $direccion,
                    'id_distrito' => $id_distrito,
                    'fecha_modificacion' => date("Y-m-d H:i:s"),
                );
                //Actualizamos en la tabla alumno
                $resultSet = $this->m_alumno->actualizar($data_alumno, 'id_alumno', $id_alumno);

                //Actualizamos usuario
                $this->actualiza_usuario($usuario, $contrasena, $id_usuario);

                if ($resultSet) { //Si se actualizo correctamente el alumno
                    echo $this->alerta->alerta_exito('Se actualizó correctamente');
                    echo $this->url_comp->direccionar_tiempo(base_url() . 'alumno/listar', '1000');
                    exit;
                }
            } else {
                echo $this->alerta->alerta_error('El DNI existe');
                exit;
            }
        }

    }

    //Funcion para actualizar usuario del alumno
    private function actualiza_usuario($usuario, $contrasena = "", $id_usuario) {
        if ($this->m_usuario->existe_usuario($usuario, $id_usuario) === TRUE) {
            echo $this->alerta->alerta_error('El usuario ingresado ya existe');
            exit;
        } else {
            $user = $this->m_usuario->mostrar(array('usuario.id_usuario' => $id_usuario));

            if ($contrasena == '') {
                $NuevaClave = $user['clave'];
            } else {
                $NuevaClave = md5($contrasena);
            }
            $data = array(
                'usuario' => $usuario,
                'clave' => $NuevaClave,
                'fecha_modificacion' => date("Y-m-d H:i:s"),
            );
            $this->m_usuario->actualizar($data, 'id_usuario', $id_usuario);
        }
    }

    public function accion_denegar($id_alumno = '') {
        if ($id_alumno == '') {
            echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
            exit;
        }

        $resultSet = $this->m_alumno->exists(array('alumno.id_alumno' => $id_alumno));
        if ($resultSet === FALSE) {
            echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
            exit;
        }
        $alumno = $this->m_alumno->mostrar(array("alumno.id_alumno" => $id_alumno));
        $this->m_usuario->ocultar($alumno["id_usuario"]);
        echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
    }

    public function accion_permitir($id_alumno = '') {
        if ($id_alumno == '') {
            echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
            exit;
        }
        $resultSet = $this->m_alumno->exists(array('alumno.id_alumno' => $id_alumno));
        if ($resultSet === FALSE) {
            echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
            exit;
        }
        $alumno = $this->m_alumno->mostrar(array("alumno.id_alumno" => $id_alumno));
        $this->m_usuario->permitir($alumno["id_usuario"]);
        echo $this->url_comp->direccionar(base_url() . 'alumno/listar', TRUE);
    }

}
