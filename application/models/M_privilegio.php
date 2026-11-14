<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_privilegio extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('privilegio');
        parent::setAlias('privilegio');
        parent::setTabla_id('id_privilegio');
    }

    public function get_query() {
        $this->CI->db->select("privilegio.*");
        $this->CI->db->from($this->tabla);
    }
}
