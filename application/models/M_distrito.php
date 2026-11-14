<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_distrito extends Modelo_DB{
    
    public function __construct() {
        parent::__construct();
        parent::setTabla('distrito');
        parent::setAlias('distrito');
        parent::setTabla_id('id_distrito');
    }
    
    public function get_query() {
        $this->CI->db->select("distrito.*");
        $this->CI->db->from($this->tabla);
    }
}
