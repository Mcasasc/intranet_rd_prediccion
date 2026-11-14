<?php

class Session_sistema {

    public function __construct() {
        $this->ci = &get_instance();
        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('session', 'alerta', 'url_comp');
        $helper = array();
        $model = array("m_usuario", "m_alumno");
        $this->ci->load->library($library);
        $this->ci->load->helper($helper);
        $this->ci->load->model($model);
        /*
         * Configuración personalizada
         */
        $this->_session = $this->datos_usuario('user_data');
    }

    public function datos_usuario_logueado() {
        /*
         * Información de la sesion
         */

        if (isset($this->_session->sys_id) === FALSE) {
            $this->destruir_session('user_data');
            echo $this->ci->alerta->location_href(base_url());
            exit;
        } else {            
            switch ($this->_session->sys_grade) {
            case '3': //Privilegio de Alumno
                $info = $this->ci->m_alumno->mostrar(array("alumno.id_usuario" => $this->_session->sys_id, 'alumno.estado' => 0));
                break;

            default: //Cualquier otro privilegio
                $info = $this->ci->m_usuario->mostrar(array('usuario.id_usuario' => $this->_session->sys_id, 'usuario.estado' => 0));
                break;
            }
            if (empty($info)) {
                $this->destruir_session('user_data');
                echo $this->ci->url_comp->direccionar(base_url(), TRUE);
                exit;
            }

            $data['usuario_id'] = $this->_session->sys_id;
            $data['alumno_id'] = isset($info["id_alumno"]) === FALSE ? 0 : $info["id_alumno"];
            $data['usuario_nombres'] = $this->_session->sys_grade == 3 ? "{$info["nombre"]} {$info["apellido_paterno"]} {$info["apellido_materno"]}" : "{$info["nombres"]} {$info["ape_paterno"]} {$info["ape_materno"]}";
            $data['usuario_fecha_registro'] = $info['fecha_registro'];
            $data['usuario_privilegio'] = $info['id_privilegio'];
            $data['usuario_nombre_privilegio'] = $info['nombre_privilegio'];
        }
        return $data;
    }

    public function verificar_logueo() {
        /*
         * Información de la sesion
         */
        if (isset($this->_session->sys_id) === TRUE) {
            echo $this->ci->alerta->location_href(base_url() . 'home');
            exit;
        }
    }

    public function datos_usuario($array = 'user_data') {
        $session = $this->ci->session->all_userdata();
        if (isset($session[$array]) && is_array($session[$array])) {
            $data = new stdClass();
            foreach ($session[$array] as $key => $value) {
                $data->$key = $value;
            }
            return $data;
        } else {
            return FALSE;
        }
    }

    public function destruir_session($array = 'user_data') {
        $session = $this->ci->session->all_userdata();
        if (isset($session[$array]) && is_array($session[$array])) {
            $this->ci->session->unset_userdata($array);
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
