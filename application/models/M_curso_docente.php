<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_curso_docente extends Modelo_DB{

    public function __construct() {
        parent::__construct();
        parent::setTabla('curso_docente');
        parent::setAlias('curso_docente');
        parent::setTabla_id('id_curso_docente');
    }

    public function get_query() {
        $this->CI->db->select("curso_docente.*, persona.nombres as nombre_docente, persona.ape_paterno as apellido_paterno_docente,
        persona.ape_materno as apellido_materno_docente");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('persona persona', 'curso_docente.id_usuario = persona.id_usuario', 'inner');
        $this->CI->db->order_by("curso_docente.id_curso_docente", "asc");
    }

    /* Funcion para obtener los docentes con cursos asignados sin duplicar los docentes */
    public function docentes(){
        $resultado = $this->CI->db->select("*")->from($this->tabla)->group_by("id_usuario");
        return $this->CI->db->get()->result_array();
    }

}
