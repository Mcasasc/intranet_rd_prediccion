<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Page extends CI_Controller {

    private $items = array();

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'parser', 'mantenimiento');
        $helper = array('url', 'string_helper');
        $model = array();
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        $this->rand = random_string('alnum', 5);
        /*
         * Configuración personalizada
         */
        $this->config->load('serinfo', TRUE, TRUE);
        $this->_session = $this->session_sistema->datos_usuario('user_data');
        $this->items['proyecto'] = $this->config->item('nombre_proyecto', 'serinfo');
        $this->items['favicon'] = $this->config->item('url_favicon', 'serinfo');
        $this->items['base_url'] = base_url();
    }

    public function login() {
        //Verificamos sesion iniciada
        $this->session_sistema->verificar_logueo();
        //Titulo de la Página (pestaña del navegador)
        $data['titulo_pagina'] = "Login | {$this->items['proyecto']}";
        /*
         * Contenido de la interna
         */

        /*
         * Impresión de páginas
         */
        $data = array_merge($data, $this->items);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('internas/login', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    public function home() {
        //Verificamos sesion iniciada
        $login = $this->session_sistema->datos_usuario_logueado();
        //Titulo de la Página (pestaña del navegador)
        $data['titulo_pagina'] = "Inicio | {$this->items['proyecto']}";
        $data['titulo_modulo'] = "Inicio";
        /*
         * Contenido de la interna
         */
        /*
         * Impresión de páginas
         */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/home', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

}
