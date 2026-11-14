<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_matricula extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('matricula');
        parent::setAlias('matricula');
        parent::setTabla_id('id_matricula');
    }

    public function get_query() {
        $this->CI->db->select("matricula.*, alumno.id_alumno, alumno.nombre, alumno.apellido_paterno,
        alumno.apellido_materno, alumno.dni");
        $this->CI->db->from($this->tabla . " matricula");
        $this->CI->db->join('alumno alumno', 'alumno.id_alumno = matricula.id_alumno', 'inner');
        $this->CI->db->order_by("alumno.apellido_paterno", "asc");
        $this->CI->db->order_by("alumno.apellido_materno", "asc");
    }

    /* Funcion para saber si el alumno ya esta matriculado */
    public function existe_matricula($dni, $id_anio_escolar) {
        //Consultamos a la tabla alumno
        $alumno = $this->CI->db->select('id_alumno')
            ->from('alumno')
            ->where('alumno.dni', $dni)
            ->get()
            ->result();

        $id_alumno = !empty($alumno) ? $alumno[0]->id_alumno : 0;

        //Obteniendo el id_alumno del alumno, consultamos a la tabla matricula
        $resulSet = $this->CI->db->select('id_matricula')
            ->from('matricula')
            ->where('matricula.id_alumno', $id_alumno)
            ->where('matricula.id_anio_escolar', $id_anio_escolar)
            ->get()
            ->result();
        if (count($resulSet) > 0) {
            return TRUE; //Existe
        } else {
            return FALSE; //No existe
        }
    }

    /* Funcion para obtener los grados aperturados sin duplicados */
    public function grados() {
        $resultado = $this->CI->db->select("*")->from($this->tabla)->group_by("id_grado")->group_by("id_anio_escolar")->order_by("id_matricula", "desc");
        return $this->CI->db->get()->result_array();
    }

}
