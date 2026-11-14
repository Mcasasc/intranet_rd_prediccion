<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_mensaje_contenido extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('mensaje_contenido');
        parent::setAlias('mensaje_contenido');
        parent::setTabla_id('id_mensaje_contenido');
    }

    public function get_query() {
        $this->CI->db->select("mensaje_contenido.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('mensaje mensaje', 'mensaje.id_mensaje = mensaje_contenido.id_mensaje', 'inner');
    }
}
  