<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_mensaje_matricula extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('mensaje_matricula');
        parent::setAlias('mensaje_matricula');
        parent::setTabla_id('id_mensaje_matricula');
    }

    public function get_query() {
        $this->CI->db->select("mensaje_matricula.*, mensaje.asunto, mensaje_contenido.contenido, mensaje_contenido.archivo_adjunto, mensaje.fecha_envio");
        $this->CI->db->from($this->tabla);  
        $this->CI->db->join('mensaje mensaje', 'mensaje.id_mensaje = mensaje_matricula.id_mensaje', 'inner');
        $this->CI->db->join('mensaje_contenido mensaje_contenido', 'mensaje_contenido.id_mensaje = mensaje.id_mensaje', 'inner');
        $this->CI->db->order_by("mensaje.fecha_envio", "desc");
    }
}
