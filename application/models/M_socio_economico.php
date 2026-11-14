<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_socio_economico extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('socio_economico');
        parent::setAlias('socio_economico');
        parent::setTabla_id('id_socio_economico');
    }

    public function get_query() {
        $this->CI->db->select("socio_economico.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_socio_economico", "desc");
    }
}
