<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_anio_escolar extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('anio_escolar');
        parent::setAlias('anio_escolar');
        parent::setTabla_id('id_anio_escolar');
    }

    public function get_query() {
        $this->CI->db->select("anio_escolar.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_anio_escolar", "desc");
    }

//Obtenemos el ultimo id de la tabla
    public function id_ultimo_anio_escolar() {
        $resulSet = $this->CI->db->last_row();
        if (count($resulSet) > 0) {
            return $resulSet[0];
        } else {
            return FALSE;
        }
    }
}
