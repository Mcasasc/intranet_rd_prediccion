<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_grado extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('grado');
        parent::setAlias('grado');
        parent::setTabla_id('id_grado');
    }

    public function get_query() {
        $this->CI->db->select("grado.*, nivel_educativo.nombre as nombre_nivel_educativo");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('nivel_educativo nivel_educativo', 'nivel_educativo.id_nivel_educativo = grado.id_nivel_educativo', 'inner');
        $this->CI->db->order_by("grado.id_grado", "asc");
    }
}
