<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_mensaje extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('mensaje');
        parent::setAlias('mensaje');
        parent::setTabla_id('id_mensaje');
    }

    public function get_query() {
        $this->CI->db->select("mensaje.*, mensaje_contenido.contenido, mensaje_contenido.archivo_adjunto");
        $this->CI->db->from($this->tabla);
        $this->CI->db->join('mensaje_contenido mensaje_contenido', 'mensaje_contenido.id_mensaje = mensaje.id_mensaje', 'right');
        $this->CI->db->order_by("{$this->tabla}.fecha_envio", "desc");
    }
}
