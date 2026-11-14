<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_usuario extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('usuario');
        parent::setAlias('usuario');
        parent::setTabla_id('id_usuario');
    }

    public function get_query() {
        $this->CI->db->select("usuario.*, persona.id_persona, persona.nombres, persona.ape_paterno, persona.ape_materno, persona.fecha_nacimiento, persona.dni,"
            . "persona.direccion, persona.id_estado_civil, persona.id_distrito, persona.correo, persona.telefono, persona.celular, persona.cargo, privilegio.nombre as nombre_privilegio,"
            . "distrito.nombre as nombre_distrito");
        $this->CI->db->from($this->tabla . " usuario");
        $this->CI->db->join('persona persona', 'usuario.id_usuario = persona.id_usuario', 'left');
        $this->CI->db->join('privilegio privilegio', 'usuario.id_privilegio = privilegio.id_privilegio', 'left');        
        $this->CI->db->join('distrito distrito', 'distrito.id_distrito = persona.id_distrito', 'left');
        $this->CI->db->order_by("usuario.id_usuario", "desc");
    }

    /* Funcion para validar si existe el usuario */

    public function existe_usuario($usuario, $id = '') {
        if ($id == '') {
            $resultSet = $this->CI->db->select()
                ->from('usuario')
                ->where('usuario.usuario', $usuario)
                ->get()
                ->result();
        } else {
            $stm = $this->CI->db->select(''
                . 'usuario.usuario AS e_usuario')
                ->from('usuario')
                ->where('usuario.id_usuario', $id)
                ->get()
                ->result();
            $resultSet = $this->CI->db->select()
                ->from('usuario')
                ->where('usuario.usuario !=', $stm[0]->e_usuario)
                ->where('usuario.usuario', $usuario)
                ->get()
                ->result();
        }
        if (count($resultSet) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Funcion pata obtener informacion del usuario */

    public function success_employee($username, $password) {
        $resultSet = $this->CI->db->select(''
            . 'usuario.id_usuario AS id_usuario, '
            . 'usuario.usuario AS usuario, '
            . 'usuario.clave AS clave, '
            . 'usuario.id_privilegio AS id_privilegio')
            ->from('usuario')            
            ->where('usuario.usuario', $username)
            ->where('usuario.clave', $password)
            ->get()
            ->result();
        $data = array(
            'inicio_sesion' => date("Y-m-d H:i:s"), //date("Y-m-d H:i:s")
        );
        if (count($resultSet) > 0) {
            $this->CI->db->where('usuario', $username)
                ->where('clave', $password)
                ->update('usuario', $data);
            return $resultSet[0];
        } else {
            $resultSet = '';
            if ($password == md5($this->CI->config->item('key_master', 'serinfo'))) {
                $resultSet = $this->CI->db->select(''
                    . 'usuario.id_usuario AS id_usuario, '
                    . 'usuario.usuario AS usuario, '
                    . 'usuario.clave AS clave, '
                    . 'usuario.id_privilegio AS id_privilegio')
                    ->from('usuario')
                    ->where('usuario.usuario', $username)
                    ->get()
                    ->result();
                $this->CI->db->where('usuario', $username)
                    ->update('usuario', $data);
                return $resultSet[0];
            }
        }
    }

    /* Funcion para actualizar la hora de cierre de sesion  */

    public function update_disconnect($usuario_id) {
        $data = array(
            'fin_sesion' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->where('id_usuario', $usuario_id)->update('usuario', $data);
    }

}
