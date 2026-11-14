<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_asistencia_alumno extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('asistencia_alumno');
        parent::setAlias('asistencia_alumno');
        parent::setTabla_id('id_asistencia_alumno');
    }

    public function get_query() {
        $this->CI->db->select("asistencia_alumno.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('matricula matricula', 'matricula.id_matricula = asistencia_alumno.id_matricula', 'left');
        $this->CI->db->join('alumno alumno', 'alumno.id_alumno = matricula.id_alumno', 'inner');
        $this->CI->db->order_by("alumno.apellido_paterno", "asc");
        $this->CI->db->order_by("alumno.apellido_materno", "asc");
        
        //$this->CI->db->order_by("{$this->tabla}.id_asistencia_alumno", "desc");
    }

}
