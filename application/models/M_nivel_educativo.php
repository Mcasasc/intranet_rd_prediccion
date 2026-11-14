<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_nivel_educativo extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('nivel_educativo');
        parent::setAlias('nivel_educativo');
        parent::setTabla_id('id_nivel_educativo');
    }

    public function get_query() {
        $this->CI->db->select("nivel_educativo.*");
        $this->CI->db->from($this->tabla);
    }

}
