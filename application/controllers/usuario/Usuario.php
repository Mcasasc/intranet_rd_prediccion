<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Usuario extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array('m_usuario', 'm_privilegio', 'm_persona', 'm_estado_civil', 'm_distrito');
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

    /* Funcion para listar todos los usuarios */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Usuarios';
        $data['titulo_modulo'] = "Lista de Usuarios";
        /* ------------------------------------------------------------ */
        
        $lista = $this->m_usuario->mostrar_todo();
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($lista)) {
                $i = 1;
                foreach ($lista AS $items) {
                    if ($items['id_privilegio'] != 7 && $items['id_privilegio'] != 3) { //No mostramos usuarios con privilegio de super usuario ni de alumno
                        $accion = $this->mantenimiento->accion($items['id_usuario'], 'editar|denegar|permitir', 'usuario', $items['estado']);
                        $persona = $this->m_persona->mostrar(array('persona.id_usuario' => $items['id_usuario']));
                        $data['lista'][] = array(
                            'numero' => $i,
                            'nombre' => $persona['nombres'] . ' ' . $persona['ape_paterno'] . ' ' . $persona['ape_materno'],
                            //'usuario' => $items['id_privilegio'] == 3 ? $items['codigo_alumno'] : $items['usuario'],
                            'usuario' => $items['usuario'],
                            'cargo' => $items['nombre_privilegio'],
                            'inicio_sesion' => $items['inicio_sesion'],
                            'fecha_registro' => $items['fecha_registro'],
                            'accion' => $accion,
                        );
                        $i++;
                    }
                }
            }
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            EXIT;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/usuario/listar_usuario', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para mostrar el formulario de registro de usuario */
    public function agregar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio_sesion = $login["usuario_privilegio"];
        $data['titulo_pagina'] = "{$this->items["proyecto"]} | Agregar Usuario";
        $data['titulo_modulo'] = "Agregar Usuario";
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador, puede editar

            //Enviamos privilegio de la sesion
            $data['privilegio_sesion'] = $privilegio_sesion;
            
            //Carga combo de privilegios
            foreach ($this->m_privilegio->mostrar_activos() as $items) {
                $this->_result[$items['id_privilegio']] = $items['nombre'];
            }
            $data['id_privilegio'] = $this->documento->generar_dropdown($this->_result, 'id_privilegio', '', 'Seleccione una opción');
            //var_dump($data['id_privilegio']);
            unset($this->_result);

            //Carga combo de estado civil
            foreach ($this->m_estado_civil->mostrar_activos() as $esta) {
                $this->_result[$esta['id_estado_civil']] = $esta['nombre'];
            }
            $data['id_estado_civil'] = $this->documento->generar_dropdown($this->_result, 'id_estado_civil', '', 'Seleccione una opción');
            //var_dump($data['id_estado_civil']);
            unset($this->_result);

            //Carga combo de distritos
            foreach ($this->m_distrito->mostrar_activos() as $dist) {
                $this->_result[$dist['id_distrito']] = $dist['nombre'];
            }
            $data['id_distrito'] = $this->documento->generar_dropdown($this->_result, 'id_distrito', '', 'Seleccione una opción', 'search');
            //var_dump($data['id_estado_civil']);
            unset($this->_result);
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }
        
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/usuario/form_usuario', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para agregar o editar usuario */

    public function accion() {
        //Validamos sesion
        $this->session_sistema->datos_usuario_logueado();

        //Definimos zona horaria
        date_default_timezone_set("America/Lima");

        //Obtenemos datos
        $id_usuario = $this->input->post('id_usuario');
        $usuario = trim($this->input->post('usuario'));
        $contrasena = trim($this->input->post('contrasena'));
        $id_privilegio = $this->input->post('id_privilegio');
        $nombres = trim($this->input->post('nombres'));
        $ape_paterno = trim($this->input->post('ape_paterno'));
        $ape_materno = trim($this->input->post('ape_materno'));
        $fecha_nacimiento = $this->input->post('fecha_nacimiento');
        $dni = trim($this->input->post('dni'));
        $email = trim($this->input->post('correo'));
        $telefono = trim($this->input->post('telefono'));
        $celular = trim($this->input->post('celular'));
        $id_estado_civil = $this->input->post('id_estado_civil');
        $id_distrito = $this->input->post('id_distrito');
        $direccion = trim($this->input->post('direccion'));
        $cargo = trim($this->input->post('cargo'));

        //Validamos campos
        $error = '';
        $error .= $this->mantenimiento->validacion($id_privilegio, 'required', 'Privilegio');
        $error .= $this->mantenimiento->validacion($usuario, 'required|alphanumeric|maxlenght[50]', 'Usuario');
        $error .= $this->mantenimiento->validacion($nombres, 'required|alphaspecial|maxlenght[100]', 'Nombres');
        $error .= $this->mantenimiento->validacion($ape_paterno, 'required|alphaspecial|maxlenght[100]', 'Apellido Paterno');
        $error .= $this->mantenimiento->validacion($ape_materno, 'required|alphaspecial|maxlenght[100]', 'Apellido Materno');
        $error .= $this->mantenimiento->validacion($email, 'required|email|minlenght[10]|maxlenght[100]', 'Correo Electrónico');
        $error .= $this->mantenimiento->validacion($dni, 'required|numeric|minlenght[8]|maxlenght[8]', 'D.N.I.');
        $error .= $this->mantenimiento->validacion($celular, 'required|numeric|minlenght[9]|maxlenght[9]', 'Celular');
        $error .= $this->mantenimiento->validacion($cargo, 'required', 'Cargo');

        //Si hay error de validacion
        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }

        switch ($id_usuario) {
        case '': //AGREGAR

            $error .= $this->mantenimiento->validacion($contrasena, 'required|minlenght[5]|maxlenght[50]', 'Contraseña');
            //Si hay error de validacion
            if ($error != '') {
                echo $this->alerta->alerta_error($error, TRUE);
                exit;
            }

            //Verificamos que el dni no exista
            if ($this->m_persona->exists(array('persona.dni' => $dni)) != '0') {
                echo $this->alerta->alerta_error('El DNI existe.', FALSE);
                exit;
            }

            //Verificamos que el usuario no exista
            if ($this->m_usuario->existe_usuario($usuario) === TRUE) {
                echo $this->alerta->alerta_error('El usuario ya está registrado.', FALSE);
                exit;
            }

            //Data para el registro de usuario
            $data_usuario = array(
                'usuario' => $usuario,
                'clave' => $contrasena == '' ? $contrasena : md5($contrasena),
                'fecha_registro' => date("Y-m-d H:i:s"),
                'id_privilegio' => $id_privilegio,
            );
            //Insertamos usuario
            $resultSet_usuario = $this->m_usuario->insertar($data_usuario);

            //Data para el registro de persona
            $data_persona = array(
                'nombres' => $nombres,
                'ape_paterno' => $ape_paterno,
                'ape_materno' => $ape_materno,
                'dni' => $dni,
                'correo' => $email == '' ? null : $email,
                'telefono' => $telefono == '' ? null : $telefono,
                'celular' => $celular == '' ? null : $celular,
                'fecha_nacimiento' => $fecha_nacimiento == '' ? "2015-01-01" : $fecha_nacimiento,
                'id_estado_civil' => $id_estado_civil == '' ? 1 : $id_estado_civil,
                'id_distrito' => $id_distrito == '' ? 1 : $id_distrito,
                'direccion' => $direccion,
                'id_usuario' => $resultSet_usuario,
                'cargo' => $cargo,
            );
            //Insertamos persona
            $resultSet = $this->m_persona->insertar($data_persona);
            //Si se inserto correctamente
            if ($resultSet) {
                echo $this->alerta->alerta_exito('Se ha registrado correctamente.');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'usuario/listar', '1000');
                exit;
            }

            break;

        default: //EDITAR
            if ($this->m_usuario->exists(array('usuario.id_usuario' => $id_usuario)) !== FALSE) {
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
                        'id_privilegio' => $id_privilegio,
                        'fecha_modificacion' => date("Y-m-d H:i:s"),
                    );
                    $resultSet = $this->m_usuario->actualizar($data, 'id_usuario', $id_usuario);
                    if ($resultSet === TRUE) {
                        $data_info = array(
                            'nombres' => $nombres,
                            'ape_paterno' => $ape_paterno,
                            'ape_materno' => $ape_materno,
                            'dni' => $dni,
                            'correo' => $email == '' ? null : $email,
                            'telefono' => $telefono == '' ? null : $telefono,
                            'celular' => $celular == '' ? null : $celular,
                            'fecha_nacimiento' => $fecha_nacimiento == '' ? "2015-01-01" : $fecha_nacimiento,
                            'id_estado_civil' => $id_estado_civil == '' ? 1 : $id_estado_civil,
                            'id_distrito' => $id_distrito == '' ? 1 : $id_distrito,
                            'direccion' => $direccion,
                            'cargo' => $cargo,
                        );

                        $resultSet = $this->m_persona->actualizar($data_info, 'id_usuario', $id_usuario);
                        echo $this->alerta->alerta_exito('Se ha editado correctamente...');
                        echo $this->url_comp->direccionar_tiempo(base_url() . 'usuario/listar', '1000');
                        exit;
                    }
                }
            } else {
                echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
                exit;
            }
            break;
        }

        if ($resultSet === FALSE) {
            echo $this->alerta->alerta_error('Hubo algunos errores.');
            exit;
        }

    }

    /* Funcion para editar un usuario */

    public function editar($id_usuario = '') {
        //Validamos sesion
        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio_sesion = $login["usuario_privilegio"];
        //Validamos que el id_usuario no este vacio
        if ($id_usuario == '') {
            echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
            exit;
        }

        if ($privilegio_sesion != 7 && $privilegio_sesion != 1) {            
            if ($id_usuario != $login["usuario_id"]) {
                echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            }
        }
        $data['titulo_pagina'] = "Editar Usuario | {$this->items["proyecto"]}";
        $data['titulo_modulo'] = "Editar Usuario";

        /* ------------------------------------------------------------ */
        $usuario = $this->m_usuario->mostrar(array('usuario.id_usuario' => $id_usuario));

        if (!empty($usuario)) {
            $data['id_usuario'] = $usuario['id_usuario'];
            $data['usuario'] = $usuario['usuario'];
            $data['nombres'] = $usuario['nombres'];
            $data['ape_paterno'] = $usuario['ape_paterno'];
            $data['ape_materno'] = $usuario['ape_materno'];
            $data['correo'] = $usuario['correo'];
            $data['dni'] = $usuario['dni'];
            $data['fecha_nacimiento'] = $usuario['fecha_nacimiento'];
            $data['telefono'] = $usuario['telefono'];
            $data['celular'] = $usuario['celular'];
            $data['direccion'] = $usuario['direccion'];
            $data['cargo'] = $usuario['cargo'];
            $data['privilegio_sesion'] = $privilegio_sesion;
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        /* ------------------------------------------------------------ */
        //Carga combo de privilegios con valor de bd
        if ($privilegio_sesion == 7 || $privilegio_sesion == 1) {
            foreach ($this->m_privilegio->mostrar_activos() as $items) {
                $this->_result[$items['id_privilegio']] = $items['nombre'];
            }

            $data['id_privilegio'] = $this->documento->generar_dropdown($this->_result, 'id_privilegio', $usuario['id_privilegio'], 'Seleccione una opción');
            unset($this->_result);
        } else {
            $data['id_privilegio'] = $usuario['id_privilegio'];
        }
        //Carga combo de estado civil con valor de bd
        foreach ($this->m_estado_civil->mostrar_activos() as $est) {
            $this->_result[$est['id_estado_civil']] = $est['nombre'];
        }

        $data['id_estado_civil'] = $this->documento->generar_dropdown($this->_result, 'id_estado_civil', $usuario['id_estado_civil'], 'Seleccione una opción');
        unset($this->_result);

        //Carga combo de distritos con valor de bd
        foreach ($this->m_distrito->mostrar_activos() as $distr) {
            $this->_result[$distr['id_distrito']] = $distr['nombre'];
        }

        $data['id_distrito'] = $this->documento->generar_dropdown($this->_result, 'id_distrito', $usuario['id_distrito'], 'Seleccione una opción');
        unset($this->_result);

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/usuario/form_usuario', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    public function accion_denegar($id_usuario = '') {
        if ($id_usuario == '') {
            echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
            EXIT;
        }

        $resultSet = $this->m_usuario->exists(array('usuario.id_usuario' => $id_usuario));
        if ($resultSet === FALSE) {
            echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
            EXIT;
        }
        $this->m_usuario->ocultar($id_usuario);
        echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
    }

    public function accion_permitir($id_usuario = '') {
        if ($id_usuario == '') {
            echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
            EXIT;
        }
        $resultSet = $this->m_usuario->exists(array('usuario.id_usuario' => $id_usuario));
        if ($resultSet === FALSE) {
            echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
            EXIT;
        }
        $this->m_usuario->permitir($id_usuario);
        echo $this->url_comp->direccionar(base_url() . 'usuario/listar', TRUE);
    }

}
