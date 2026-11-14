<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_comportamiento extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('comportamiento');
        parent::setAlias('comportamiento');
        parent::setTabla_id('id_comportamiento');
    }

    public function get_query() {
        $this->CI->db->select("comportamiento.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_comportamiento", "desc");
    }
}
