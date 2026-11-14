<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_curso extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('curso');
        parent::setAlias('curso');
        parent::setTabla_id('id_curso');
    }

    public function get_query() {
        $this->CI->db->select("curso.*, grado.id_grado, grado.nombre as nombre_grado,"
            . "grado.seccion as seccion_grado, grado.id_nivel_educativo as grado_id_nivel_educativo, nivel_educativo.nombre as nombre_nivel_educativo");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('grado grado', 'grado.id_grado = curso.id_grado', 'inner');
        $this->CI->db->join('nivel_educativo nivel_educativo', 'nivel_educativo.id_nivel_educativo = grado.id_nivel_educativo', 'inner');
        $this->CI->db->order_by("curso.id_curso", "asc");
    }
}
