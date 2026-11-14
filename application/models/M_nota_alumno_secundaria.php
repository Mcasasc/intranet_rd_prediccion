<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_nota_alumno_secundaria extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('nota_alumno_secundaria');
        parent::setAlias('nota_alumno_secundaria');
        parent::setTabla_id('id_nota_alumno_secundaria');
    }

    public function get_query() {
        $this->CI->db->select("nota_alumno_secundaria.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('matricula matricula', 'matricula.id_matricula = nota_alumno_secundaria.id_matricula', 'left');
        $this->CI->db->join('alumno alumno', 'alumno.id_alumno = matricula.id_alumno', 'inner');
        $this->CI->db->order_by("alumno.apellido_paterno", "asc");
        $this->CI->db->order_by("alumno.apellido_materno", "asc");
    }

}
