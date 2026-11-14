<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_alumno extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('alumno');
        parent::setAlias('alumno');
        parent::setTabla_id('id_alumno');
    }

    public function get_query() {
        $this->CI->db->select("alumno.*, distrito.nombre as nombre_distrito, privilegio.nombre as nombre_privilegio");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('privilegio privilegio', 'alumno.id_privilegio = privilegio.id_privilegio', 'inner');
        $this->CI->db->join('distrito distrito', 'distrito.id_distrito = alumno.id_distrito', 'inner');
        $this->CI->db->order_by("{$this->tabla}.id_alumno", "desc");
    }

    /* Funcion para verificar si el alumno existe */
    public function existe_alumno($dni) {
        $resulSet = $this->CI->db->select('id_alumno')
            ->from('alumno')
            ->where('alumno.dni', $dni)
            ->get()
            ->row_array();
        return $resulSet['id_alumno'];
    }

    /* Funcion para verificar que el dni del alumno a editar se haya cambiado o sigue siendo el mismo */
    public function valida_dni_editar($id_alumno, $dni_nuevo) {
        //Obtenemos el dni actual segun el id_alumno
        $resulSet = $this->CI->db->select("dni")
            ->from($this->tabla)
            ->where("$this->tabla.id_alumno", $id_alumno)
            ->get()
            ->row_array();
        $dni_actual = $resulSet["dni"];

        //Comparamos dni_actual con dni_nuevo
        if ($dni_actual == $dni_nuevo) {
            return true;
        } else {
            //Buscamos el dni_nuevo en la tabla alumno
            $resulSet = $this->CI->db->select('*')
                ->from($this->tabla)
                ->where("$this->tabla.dni", $dni_nuevo)
                ->count_all_results();
            return $resulSet == 1 ? false : true;
        }
    }

}
