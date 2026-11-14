<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_matricula_documento_grado extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('matricula_documento_grado');
        parent::setAlias('matricula_documento_grado');
        //parent::setTabla_id('id_parentesco');
    }

    public function get_query() {
        $this->CI->db->select("matricula_documento_grado.*");
        $this->CI->db->from($this->tabla);
        ///$this->CI->db->order_by("parentesco.id_parentesco", "asc");
    }
}
