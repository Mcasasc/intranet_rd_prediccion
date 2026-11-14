<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_apoderado extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('apoderado');
        parent::setAlias('apoderado');
        parent::setTabla_id('id_apoderado');
    }

    public function get_query() {
        $this->CI->db->select("apoderado.*, parentesco.nombre as nombre_parentesco, privilegio.nombre as nombre_privilegio");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join("parentesco parentesco", "parentesco.id_parentesco = {$this->tabla}.id_parentesco", "inner");
        $this->CI->db->join("privilegio privilegio", "privilegio.id_privilegio = {$this->tabla}.id_privilegio", "inner");
        $this->CI->db->order_by("{$this->tabla}.id_apoderado", "desc");
    }

}
