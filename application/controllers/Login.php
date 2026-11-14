<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Login extends CI_Controller {

    private $_process;
    private $_result;

    public function __construct() {
        parent::__construct();
        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('alerta', 'session_sistema', 'mantenimiento', 'session');
        $helper = array('url');
        $model = array('m_usuario');
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        /*
         * Configuración personalizada
         */
        $this->config->load('serinfo', TRUE, TRUE);
        $this->_session = $this->session_sistema->datos_usuario('user_data');
    }

    public function ingresar() {
        date_default_timezone_set("America/Lima"); //Definimos zona horaria

        $username = $this->input->post('txt_usuario');
        $password = $this->input->post('txt_contrasena');

        $error = '';
        //mantenimiento
        $error .= $this->mantenimiento->validacion($username, 'required|trim|alphanumeric|minlenght[3]|maxlenght[25]', 'Usuario');
        $error .= $this->mantenimiento->validacion($password, 'required|trim|minlenght[5]|maxlenght[25]', 'Contraseña');
        //$error .= $this->mantenimiento->validacion($captcha, 'required', 'Codigo de seguridad');
        
        if ($error != '') {
            echo $this->alerta->alerta_error($error);
            exit;
        } else if ($this->m_usuario->existe_usuario($username) === FALSE) {
            echo $this->alerta->alerta_error('El usuario no existe.');
            exit;
        }

        $info = $this->m_usuario->success_employee($username, md5($password));
        if ($info !== NULL) {
            $data = array(
                'user_data' => array(
                    'sys_id' => $info->id_usuario,
                    'sys_grade' => $info->id_privilegio,
                    'sys_username' => $info->usuario,
                    'sys_password' => $info->clave,
                ),
            );
        
            $this->session->set_userdata($data);
        
        } else {
            echo $this->alerta->alerta_error('Datos incorrectos.');
            exit;
        }
        echo $this->alerta->location_href(base_url() . 'home');
        exit;
    }

    public function salir() {
        date_default_timezone_set("America/Lima"); //Definimos zona horaria
        $this->m_usuario->update_disconnect($this->_session->sys_id);
        $this->session_sistema->destruir_session('user_data');
        echo $this->alerta->location_href(base_url() . 'login', TRUE);
        exit;
    }

}
