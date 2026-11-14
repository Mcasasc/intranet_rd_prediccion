<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_parentesco extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('parentesco');
        parent::setAlias('parentesco');
        parent::setTabla_id('id_parentesco');
    }

    public function get_query() {
        $this->CI->db->select("parentesco.*");
        $this->CI->db->from($this->tabla . " parentesco");
        $this->CI->db->order_by("parentesco.id_parentesco", "asc");
    }
}
