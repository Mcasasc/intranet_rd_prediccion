<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_bimestre extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('bimestre');
        parent::setAlias('bimestre');
        parent::setTabla_id('id_bimestre');
    }

    public function get_query() {
        $this->CI->db->select("bimestre.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_bimestre", "asc");
    }
}
