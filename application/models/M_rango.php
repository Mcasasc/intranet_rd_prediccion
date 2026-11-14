<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_rango extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('rango');
        parent::setAlias('rango');
        parent::setTabla_id('id_rango');
    }

    public function get_query() {
        $this->CI->db->select("rango.*");
        $this->CI->db->from($this->tabla);
    }
}
