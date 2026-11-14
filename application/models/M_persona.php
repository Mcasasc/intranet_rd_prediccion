<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_persona extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('persona');
        parent::setAlias('persona');
        parent::setTabla_id('id_persona');
    }

    public function get_query() {
        $this->CI->db->select("persona.*, distrito.nombre as nombre_distrito");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('usuario usuario', 'usuario.id_usuario = persona.id_usuario', 'inner');
        $this->CI->db->join('distrito distrito', 'distrito.id_distrito = persona.id_distrito', 'inner');        
        $this->CI->db->order_by("persona.id_persona", "desc");
    }
}
