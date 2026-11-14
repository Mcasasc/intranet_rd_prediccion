<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_estado_civil extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('estado_civil');
        parent::setAlias('estado_civil');
        parent::setTabla_id('id_estado_civil');
    }

    public function get_query() {
        $this->CI->db->select("estado_civil.*");
        $this->CI->db->from($this->tabla);
    }
}
