<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_mes_escolar extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('mes_escolar');
        parent::setAlias('mes_escolar');
        parent::setTabla_id('id_mes_escolar');
    }

    public function get_query() {
        $this->CI->db->select("mes_escolar.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("mes_escolar.id_mes_escolar", "asc");
    }
}
