<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_prediccion_log extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('prediccion_log');
        parent::setAlias('prediccion_log');
        parent::setTabla_id('id_prediccion_log');
    }

    public function get_query() {
        $this->CI->db->select("prediccion_log.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_prediccion_log", "desc");
    }

    
}
