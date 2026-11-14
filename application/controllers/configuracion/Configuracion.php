<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Configuracion extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_curso", "m_bimestre", "m_mes_escolar", "m_anio_escolar", "m_grado", "m_matricula_documento_grado", "m_matricula_documento");
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        /*
         * Configuración personalizada
         */

        $this->config->load('serinfo', TRUE, TRUE);
        $this->_session = $this->session_sistema->datos_usuario('user_data');

        $this->items['proyecto'] = $this->config->item('nombre_proyecto', 'serinfo');
        $this->items['favicon'] = $this->config->item('url_favicon', 'serinfo');
        $this->items['base_url'] = base_url();
        $this->items['configuracion_activo'] = 'active';
        $this->ruta_guardar_adjunto = "./documents/matricula/";
        $this->ruta_mostrar_adjunto = "documents/matricula/";
    }

    /* Funcion que carga el formulario para agregar año escolar */
    public function anio_escolar() {

        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Apertura de Año Escolar';
        $data['titulo_modulo'] = "Apertura de Año Escolar";
        /* ------------------------------------------------------------ */

        //Obtenemos grados de primaria
        $gradosPrimaria = $this->m_grado->mostrar_cuando(array("grado.id_nivel_educativo" => 2));
        foreach ($gradosPrimaria as $g) {
            
            $data["grados_primaria"][] = array(
                "id_grado" => $g["id_grado"],
                "nombre" => $g["nombre"],
                "codigo" => $g["codigo"]
            );
        }
        
        //Obtenemos grados de secundaria
        $gradosSecundaria = $this->m_grado->mostrar_cuando(array("grado.id_nivel_educativo" => 3));
        foreach ($gradosSecundaria as $gs) {
            $data["grados_secundaria"][] = array(
                "id_grado" => $gs["id_grado"],
                "nombre" => $gs["nombre"],
                "codigo" => $gs["codigo"]
            );
        }

        //Documentos Generalos
        //$matricula_documento = $this->m_matricula_documento->mostrar(array("matricula_documento.id_anio_escolar" => $id_anio_escolar));
        $data["gen_dococumentos_1"] = array(
            "gen_nombre_doc_1" => ""
        );

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/configuracion/form_apertura_anio_escolar', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    public function listar(){
        $login = $this->session_sistema->datos_usuario_logueado();

        $id_privilegio = $login["usuario_privilegio"];
        $data["id_privilegio"] = $id_privilegio;
        $data['titulo_pagina'] = "Lista de Años Aperturados | {$this->items['proyecto']}";
        $data['titulo_modulo'] = "Lista de Años Aperturados";

        $lista_anio_escolar = $this->m_anio_escolar->mostrar_todo();
        //print_r($lista_anio_escolar); exit;

        $i = 1;
        foreach ($lista_anio_escolar AS $items) {
            $accion = $this->mantenimiento->accion($items['id_anio_escolar'], 'editar', 'configuracion', "");
            $data['lista'][] = array(
                'numero' => $i,
                'id_anio_escolar' => $items['id_anio_escolar'],
                'nombre' => $items['nombre'],
                'accion' => $accion,
            );
            $i++;
        }


         /* ------------------------------------------------------------ */
         $data = array_merge($data, $this->items);
         $data = array_merge($data, $login);
         $this->smarty_tpl->view('estructura/header', $data);
         $this->smarty_tpl->view('estructura/inter_header', $data);
         $this->smarty_tpl->view('internas/configuracion/listar_anio_aperturado', $data);
         $this->smarty_tpl->view('estructura/inter_footer', $data);
         $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para editar año escolar aperturado */
    public function editar($id_anio_escolar = '') {
        $login = $this->session_sistema->datos_usuario_logueado();

        if ($id_anio_escolar == '') {
            echo $this->url_comp->direccionar(base_url() . 'configuracion/listar', TRUE);
            exit;
        }
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Editar Año Aperturado';
        $data['titulo_modulo'] = "Editar Año Aperturado";

        $data['tipo'] = 'editar';
        /* ------------------------------------------------------------ */
        $anio_escolar = $this->m_anio_escolar->mostrar(array('anio_escolar.id_anio_escolar' => $id_anio_escolar));
        $grado        = $this->m_grado->mostrar_todo();
    
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador, puede editar
            if (!empty($anio_escolar)) {
                $data['nombre_anio_escolar'] = $anio_escolar['nombre'];
                $anio_escolar                = $anio_escolar['nombre'];
                $data['id_anio_escolar']     = $id_anio_escolar;

                //Obtenemos grados de primaria
                $gradosPrimaria = $this->m_grado->mostrar_cuando(array("grado.id_nivel_educativo" => 2));
                foreach ($gradosPrimaria as $g) {
                    //Obtenemos data de matricula_documento_grado
                    $mat_doc_prim = $this->m_matricula_documento_grado->mostrar(array("matricula_documento_grado.id_anio_escolar" => $id_anio_escolar, "matricula_documento_grado.id_grado" => $g["id_grado"]));
                    $codigo_grado = $g["codigo"];
                    $data["grados_primaria"][] = array(
                        "id_grado"        => $g["id_grado"],
                        "nombre"          => $g["nombre"],
                        "codigo"          => $g["codigo"],
                        "nombre_doc_1"    => $mat_doc_prim["nombre_doc_1"],
                        "doc_1"           => base_url() . $this->ruta_mostrar_adjunto. "$anio_escolar/$codigo_grado/" . $mat_doc_prim["doc_1"],
                        "mostrar_doc_1"   => $mat_doc_prim["mostrar_doc_1"],
                        "tiene_adjunto_1" => $mat_doc_prim["doc_1"] == "" ? "no" : "si",
                        "nombre_doc_2"    => $mat_doc_prim["nombre_doc_2"],
                        "doc_2"           => base_url() . $this->ruta_mostrar_adjunto . "$anio_escolar/$codigo_grado/" . $mat_doc_prim["doc_2"],
                        "mostrar_doc_2"   => $mat_doc_prim["mostrar_doc_2"],
                        "tiene_adjunto_2" => $mat_doc_prim["doc_2"] == "" ? "no" : "si"
                    );
                }
        
                //Obtenemos grados de secundaria
                $gradosSecundaria = $this->m_grado->mostrar_cuando(array("grado.id_nivel_educativo" => 3));
                foreach ($gradosSecundaria as $gs) {
                    //Obtenemos data de matricula_documento_grado
                    $mat_doc_sec = $this->m_matricula_documento_grado->mostrar(array("matricula_documento_grado.id_anio_escolar" => $id_anio_escolar, "matricula_documento_grado.id_grado" => $gs["id_grado"]));
                    $codigo_grado = $gs["codigo"];
                    $data["grados_secundaria"][] = array(
                        "id_grado"        => $gs["id_grado"],
                        "nombre"          => $gs["nombre"],
                        "codigo"          => $gs["codigo"],
                        "nombre_doc_1"    => $mat_doc_sec["nombre_doc_1"],
                        "doc_1"           => base_url() . $this->ruta_mostrar_adjunto . "$anio_escolar/$codigo_grado/" . $mat_doc_sec["doc_1"],
                        "mostrar_doc_1"   => $mat_doc_sec["mostrar_doc_1"],
                        "tiene_adjunto_1" => $mat_doc_sec["doc_1"] == "" ? "no" : "si",
                        "nombre_doc_2"    => $mat_doc_sec["nombre_doc_2"],
                        "doc_2"           => base_url() . $this->ruta_mostrar_adjunto . "$anio_escolar/$codigo_grado/" . $mat_doc_sec["doc_2"],
                        "mostrar_doc_2"   => $mat_doc_sec["mostrar_doc_2"],
                        "tiene_adjunto_2" => $mat_doc_sec["doc_2"] == "" ? "no" : "si"
                    );
                }


                //Adjuntos Generales
                $matricula_documento = $this->m_matricula_documento->mostrar(array("matricula_documento.id_anio_escolar" => $id_anio_escolar));
                $data["gen_nombre_doc_1"] = $matricula_documento["nombre_doc_1"];
                $data["gen_nombre_doc_2"] = $matricula_documento["nombre_doc_2"];
                $data["gen_nombre_doc_3"] = $matricula_documento["nombre_doc_3"];
                $data["gen_nombre_doc_4"] = $matricula_documento["nombre_doc_4"];
                $data["gen_nombre_doc_5"] = $matricula_documento["nombre_doc_5"];

                $data["gen_mostrar_doc_1"] = $matricula_documento["mostrar_doc_1"];
                $data["gen_mostrar_doc_2"] = $matricula_documento["mostrar_doc_2"];
                $data["gen_mostrar_doc_3"] = $matricula_documento["mostrar_doc_3"];
                $data["gen_mostrar_doc_4"] = $matricula_documento["mostrar_doc_4"];
                $data["gen_mostrar_doc_5"] = $matricula_documento["mostrar_doc_5"];
                
                $data["gen_tiene_adjunto_1"] = $matricula_documento["doc_1"] == "" ? "no" : "si";
                $data["gen_tiene_adjunto_2"] = $matricula_documento["doc_2"] == "" ? "no" : "si";
                $data["gen_tiene_adjunto_3"] = $matricula_documento["doc_3"] == "" ? "no" : "si";
                $data["gen_tiene_adjunto_4"] = $matricula_documento["doc_4"] == "" ? "no" : "si";
                $data["gen_tiene_adjunto_5"] = $matricula_documento["doc_5"] == "" ? "no" : "si";

                $data["gen_doc_1"] = base_url() . $this->ruta_mostrar_adjunto . "{$anio_escolar}/GENERAL/". $matricula_documento["doc_1"];
                $data["gen_doc_2"] = base_url() . $this->ruta_mostrar_adjunto . "{$anio_escolar}/GENERAL/". $matricula_documento["doc_2"];
                $data["gen_doc_3"] = base_url() . $this->ruta_mostrar_adjunto . "{$anio_escolar}/GENERAL/". $matricula_documento["doc_3"];
                $data["gen_doc_4"] = base_url() . $this->ruta_mostrar_adjunto . "{$anio_escolar}/GENERAL/". $matricula_documento["doc_4"];
                $data["gen_doc_5"] = base_url() . $this->ruta_mostrar_adjunto . "{$anio_escolar}/GENERAL/". $matricula_documento["doc_5"];

            } else {
                echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
                exit;
            }

        } else {
            echo $this->url_comp->direccionar(base_url() . 'configuracion/listar', TRUE);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/configuracion/form_apertura_anio_escolar', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para guardar el nuevo año escolar aperturado donde al agregar
    un nuevo año se limpiara todos los registros del sistema */
    public function accion_apertura_anio_escolar() {
        date_default_timezone_set("America/Lima"); //Definimos zona horaria
        //Obtenemos datos
        $id_anio_escolar = trim($this->input->post('id_anio_escolar'));
        $nombre_anio_escolar = trim($this->input->post('anio_escolar'));
        $adjuntos = isset($_FILES) ? $_FILES : "";
        $data_adjuntos = $this->input->post("data");

        $gen_nombre_doc_1 = trim($this->input->post('gen_nombre_doc_1'));
        $gen_nombre_doc_2 = trim($this->input->post('gen_nombre_doc_2'));
        $gen_nombre_doc_3 = trim($this->input->post('gen_nombre_doc_3'));
        $gen_nombre_doc_4 = trim($this->input->post('gen_nombre_doc_4'));
        $gen_nombre_doc_5 = trim($this->input->post('gen_nombre_doc_5'));

        $gen_mostrar_doc_1 = trim($this->input->post('gen_mostrar_doc_1'));
        $gen_mostrar_doc_2 = trim($this->input->post('gen_mostrar_doc_2'));
        $gen_mostrar_doc_3 = trim($this->input->post('gen_mostrar_doc_3'));
        $gen_mostrar_doc_4 = trim($this->input->post('gen_mostrar_doc_4'));
        $gen_mostrar_doc_5 = trim($this->input->post('gen_mostrar_doc_5'));

        $gen_doc_1 = $_FILES["gen_doc_1"];
        $gen_doc_2 = $_FILES["gen_doc_2"];
        $gen_doc_3 = $_FILES["gen_doc_3"];
        $gen_doc_4 = $_FILES["gen_doc_4"];
        $gen_doc_5 = $_FILES["gen_doc_5"];
        $adjuntos_general = array($gen_doc_1, $gen_doc_2, $gen_doc_3, $gen_doc_4, $gen_doc_5);

        //Validamos campo
        $error = '';
        $error .= $this->mantenimiento->validacion($nombre_anio_escolar, 'numeric|required|minlenght[4]|maxlenght[4]', 'Año Escolar');
        if ($error != '') {
            echo $this->alerta->alerta_error($error, true);
            exit;
        }

        switch ($id_anio_escolar) {
            case '': //AGREGAR
                
                //Validamos que el año no sea repetido
                if ($this->m_anio_escolar->exists(array("anio_escolar.nombre" => $nombre_anio_escolar)) != 0) { //Si existe el año escolar
                    echo $this->alerta->alerta_error('El Año Escolar ya existe.', FALSE);
                    exit;
                }

                //Data a insertar
                $data_anio_escolar = array(
                    'nombre' => $nombre_anio_escolar,
                );

                //Insertamos año escolar
                $resultSet_anio = $this->m_anio_escolar->insertar($data_anio_escolar);

                //Recorremos adjuntos generales
                $ruta_adjunto_general = $this->ruta_guardar_adjunto . "{$nombre_anio_escolar}/GENERAL";
                $this->inserta_documentos_generales(  "agregar"         , $resultSet_anio      , $ruta_adjunto_general, $adjuntos_general
                                                    , $gen_nombre_doc_1 , $gen_nombre_doc_2    , $gen_nombre_doc_3    , $gen_nombre_doc_4 , $gen_nombre_doc_5
                                                    , $gen_mostrar_doc_1, $gen_mostrar_doc_2   , $gen_mostrar_doc_3   , $gen_mostrar_doc_4, $gen_mostrar_doc_5
                                                   );


                //Recorremos adjuntos por grado
                foreach ($adjuntos as $key => $adj) {
                    if (in_array($key, array("gen_doc_1", "gen_doc_2", "gen_doc_3", "gen_doc_4", "gen_doc_5"))) {
                        goto saltar;
                    }

                    $ruta_adjunto = $this->ruta_guardar_adjunto . "{$nombre_anio_escolar}/{$key}";
                    
                    //Adjunto 1
                    $nombre_adjunto_1                = $this->documento->procesa_nombre_documento($adj["name"]["doc_1"]);
                    $_FILES['adjunto_1']['name']     = $nombre_adjunto_1;             //$nombre_adjunto_1;
                    $_FILES['adjunto_1']['type']     = $adj["type"]["doc_1"];         //$_FILES['userfiles']['type'][$key];
                    $_FILES['adjunto_1']['tmp_name'] = $adj["tmp_name"]["doc_1"];     //$_FILES['userfiles']['tmp_name'][$key];
                    $_FILES['adjunto_1']['error']    = $adj["error"]["doc_1"];        //$_FILES['userfiles']['error'][$key];
                    $_FILES['adjunto_1']['size']     = $adj["size"]["doc_1"];         //$_FILES['userfiles']['size'][$key];
                
                    if (isset($nombre_adjunto_1) && $nombre_adjunto_1 != "") { //Si al agregar tenemos archivo adjunto               
                        //Primero subimos archivo adjunto
                        $adjunto_subido_1 = $this->documento->subir_adjunto("adjunto_1", $ruta_adjunto);
                        if (!$adjunto_subido_1) {
                            //echo $this->alerta->alerta_error('Error al cargar el adjunto.');
                            $adjunto_subido_1["file_name"] = "Error al cargar el adjunto.";
                        }
                        
                    } else {
                        $adjunto_subido_1["file_name"] = "";
                    }

                    //Adjunto 2
                    $nombre_adjunto_2                = $this->documento->procesa_nombre_documento($adj["name"]["doc_2"]);
                    $_FILES['adjunto_2']['name']     = $nombre_adjunto_2;             //$nombre_adjunto_2;
                    $_FILES['adjunto_2']['type']     = $adj["type"]["doc_2"];         //$_FILES['userfiles']['type'][$key];
                    $_FILES['adjunto_2']['tmp_name'] = $adj["tmp_name"]["doc_2"];     //$_FILES['userfiles']['tmp_name'][$key];
                    $_FILES['adjunto_2']['error']    = $adj["error"]["doc_2"];        //$_FILES['userfiles']['error'][$key];
                    $_FILES['adjunto_2']['size']     = $adj["size"]["doc_2"];         //$_FILES['userfiles']['size'][$key];
                
                    if (isset($nombre_adjunto_2) && $nombre_adjunto_2 != "") { //Si al agregar tenemos archivo adjunto               
                        //Primero subimos archivo adjunto
                        $adjunto_subido_2 = $this->documento->subir_adjunto("adjunto_2", $ruta_adjunto);
                        if (!$adjunto_subido_2) {
                            //echo $this->alerta->alerta_error('Error al cargar el adjunto.');
                            $adjunto_subido_2["file_name"] = "Error al cargar el adjunto.";
                        }
                        
                    } else {
                        $adjunto_subido_2["file_name"] = "";
                    }

                    /* Insertamos en  matricula_documento_grado */
                    //Obtenemos id_grado segun key
                    $grado = $this->m_grado->mostrar(array("grado.codigo" => $key));

                    //Data a insertar
                    $data_matricula_documento_grado = array(
                        'id_anio_escolar' => $resultSet_anio,
                        'id_grado'        => $grado["id_grado"],
                        'nombre_doc_1'    => $data_adjuntos["$key"]["nombre_doc_1"],
                        'doc_1'           => $adjunto_subido_1["file_name"],
                        'mostrar_doc_1'   => $data_adjuntos["$key"]["mostrar_doc_1"],
                        'nombre_doc_2'    => $data_adjuntos["$key"]["nombre_doc_2"],
                        'doc_2'           => $adjunto_subido_2["file_name"],
                        'mostrar_doc_2'   => $data_adjuntos["$key"]["mostrar_doc_2"],

                    );
                    //Insertamos documento_grado
                    $this->m_matricula_documento_grado->insertar($data_matricula_documento_grado);

                    saltar:
                }
    
            break;
    
            default: //EDITAR

                //Data a actualizar
                $data_anio_escolar = array(
                    'nombre' => $nombre_anio_escolar,
                );
                //Actualizamos año escolar
                $resultSet = $this->m_anio_escolar->actualizar($data_anio_escolar, 'id_anio_escolar', $id_anio_escolar);

                //AQUIIII
                //Recorremos adjuntos generales
                $ruta_adjunto_general = $this->ruta_guardar_adjunto . "{$nombre_anio_escolar}/GENERAL";
                $this->inserta_documentos_generales(  "editar"          , $id_anio_escolar     , $ruta_adjunto_general, $adjuntos_general
                                                    , $gen_nombre_doc_1 , $gen_nombre_doc_2    , $gen_nombre_doc_3    , $gen_nombre_doc_4 , $gen_nombre_doc_5
                                                    , $gen_mostrar_doc_1, $gen_mostrar_doc_2   , $gen_mostrar_doc_3   , $gen_mostrar_doc_4, $gen_mostrar_doc_5
                                                   );

                //Recorremos adjuntos
                foreach ($adjuntos as $key => $adj) {

                    if (in_array($key, array("gen_doc_1", "gen_doc_2", "gen_doc_3", "gen_doc_4", "gen_doc_5"))) {
                        goto saltar_edit;
                    }
                    
                    //Obtenemos id_grado segun key
                    $grado = $this->m_grado->mostrar(array("grado.codigo" => $key));
                    
                    $ruta_adjunto = $this->ruta_guardar_adjunto . "{$nombre_anio_escolar}/{$key}";

                    //Obtenemos adjunto anterior
                    $adj_anterior  = $this->m_matricula_documento_grado->mostrar(array("matricula_documento_grado.id_anio_escolar" => $id_anio_escolar, "matricula_documento_grado.id_grado" => $grado["id_grado"]));
                    $nom_adj_ant   = $adj_anterior["doc_1"];
                    $nom_adj_ant_2 = $adj_anterior["doc_2"];
                    
                    //Adjunto 1
                    $nombre_adjunto_1                = $this->documento->procesa_nombre_documento($adj["name"]["doc_1"]);
                    $_FILES['adjunto_1']['name']     = $nombre_adjunto_1;             //$nombre_adjunto_1;
                    $_FILES['adjunto_1']['type']     = $adj["type"]["doc_1"];         //$_FILES['userfiles']['type'][$key];
                    $_FILES['adjunto_1']['tmp_name'] = $adj["tmp_name"]["doc_1"];     //$_FILES['userfiles']['tmp_name'][$key];
                    $_FILES['adjunto_1']['error']    = $adj["error"]["doc_1"];        //$_FILES['userfiles']['error'][$key];
                    $_FILES['adjunto_1']['size']     = $adj["size"]["doc_1"];         //$_FILES['userfiles']['size'][$key];
                
                    if (isset($nombre_adjunto_1) && $nombre_adjunto_1 != "") { //Si al actualizar tenemos adjunto nuevo

                        //Obtener adjunto anterior, Eliminar adjunto anterior, cargar adjunto nuevo y actualizar tabla
                        //Eliminamos documento anterior
                        @unlink("$ruta_adjunto/$nom_adj_ant");

                        //Primero subimos archivo adjunto
                        $adjunto_subido_1 = $this->documento->subir_adjunto("adjunto_1", $ruta_adjunto);
                        if (!$adjunto_subido_1) {
                            //echo $this->alerta->alerta_error('Error al cargar el adjunto.');
                            $adjunto_subido_1["file_name"] = "Error al cargar el adjunto.";
                        }
                        
                    } else {
                        $adjunto_subido_1["file_name"] = $nom_adj_ant;
                    }

                    //Adjunto 2
                    $nombre_adjunto_2                = $this->documento->procesa_nombre_documento($adj["name"]["doc_2"]);
                    $_FILES['adjunto_2']['name']     = $nombre_adjunto_2;             //$nombre_adjunto_2;
                    $_FILES['adjunto_2']['type']     = $adj["type"]["doc_2"];         //$_FILES['userfiles']['type'][$key];
                    $_FILES['adjunto_2']['tmp_name'] = $adj["tmp_name"]["doc_2"];     //$_FILES['userfiles']['tmp_name'][$key];
                    $_FILES['adjunto_2']['error']    = $adj["error"]["doc_2"];        //$_FILES['userfiles']['error'][$key];
                    $_FILES['adjunto_2']['size']     = $adj["size"]["doc_2"];         //$_FILES['userfiles']['size'][$key];
                
                    if (isset($nombre_adjunto_2) && $nombre_adjunto_2 != "") { //Si al agregar tenemos archivo adjunto               

                        //Eliminamos documento anterior
                        @unlink("$ruta_adjunto/$nom_adj_ant_2");

                        //Primero subimos archivo adjunto
                        $adjunto_subido_2 = $this->documento->subir_adjunto("adjunto_2", $ruta_adjunto);
                        if (!$adjunto_subido_2) {
                            //echo $this->alerta->alerta_error('Error al cargar el adjunto.');
                            $adjunto_subido_2["file_name"] = "Error al cargar el adjunto.";
                        }
                        
                    } else {
                        $adjunto_subido_2["file_name"] = $nom_adj_ant_2;
                    }

                    /* Actualizamos en  matricula_documento_grado */
                    $data_matricula_documento_grado = array(
                        //'id_anio_escolar' => $id_anio_escolar,
                        //'id_grado'        => $grado["id_grado"],
                        'nombre_doc_1'    => $data_adjuntos["$key"]["nombre_doc_1"],
                        'doc_1'           => $adjunto_subido_1["file_name"],
                        'mostrar_doc_1'   => $data_adjuntos["$key"]["mostrar_doc_1"],
                        'nombre_doc_2'    => $data_adjuntos["$key"]["nombre_doc_2"],
                        'doc_2'           => $adjunto_subido_2["file_name"],
                        'mostrar_doc_2'   => $data_adjuntos["$key"]["mostrar_doc_2"],
                    );

                    //Actualizamos
                    $this->m_matricula_documento_grado->actualizar($data_matricula_documento_grado, array("id_anio_escolar" => $id_anio_escolar, "id_grado" => $grado["id_grado"]));

                    saltar_edit:
                }
            
            break;
            }

        //Mensaje al usuario
        echo $this->alerta->alerta_exito('Se guardó correctamente.');
        echo $this->url_comp->direccionar_tiempo(base_url() . 'configuracion/listar', '1000');
        exit;
    }

    private function inserta_documentos_generales($opcion           , $id_anio_escolar    , $ruta             , $adjunto
                                                , $gen_nombre_doc_1 , $gen_nombre_doc_2   , $gen_nombre_doc_3 , $gen_nombre_doc_4 , $gen_nombre_doc_5
                                                , $gen_mostrar_doc_1, $gen_mostrar_doc_2  , $gen_mostrar_doc_3, $gen_mostrar_doc_4, $gen_mostrar_doc_5
                                                ) {
        
        switch ($opcion) {
            case 'agregar':
                foreach ($adjunto as $adj) {
                    $nombre_adjunto                    = $this->documento->procesa_nombre_documento($adj["name"]);
                    $_FILES['adjunto_gen']['name']     = $nombre_adjunto;
                    $_FILES['adjunto_gen']['type']     = $adj["type"];
                    $_FILES['adjunto_gen']['tmp_name'] = $adj["tmp_name"];
                    $_FILES['adjunto_gen']['error']    = $adj["error"];
                    $_FILES['adjunto_gen']['size']     = $adj["size"];
        
                    if (isset($nombre_adjunto) && $nombre_adjunto != "") { //Si al agregar tenemos archivo adjunto               
                        //Primero subimos archivo adjunto
                        $adjunto_subido_gen = $this->documento->subir_adjunto("adjunto_gen", $ruta);
                        if (!$adjunto_subido_gen) {
                            $adjunto_subido_rpta[] = "Error al cargar el adjunto.";
                        }
                        else{
                            $adjunto_subido_rpta[] = $adjunto_subido_gen["file_name"];
                        }
                    } else {
                        $adjunto_subido_rpta[] = "";
                    }
                }
            
                //Data a insertar
                $data_matricula_documento = array(
                    "id_anio_escolar"  => $id_anio_escolar,
                    "nombre_doc_1"    => $gen_nombre_doc_1,
                    "doc_1"           => $adjunto_subido_rpta[0],
                    "mostrar_doc_1"   => $gen_mostrar_doc_1,
                    "nombre_doc_2"    => $gen_nombre_doc_2,
                    "doc_2"           => $adjunto_subido_rpta[1],
                    "mostrar_doc_2"   => $gen_mostrar_doc_2,
                    "nombre_doc_3"    => $gen_nombre_doc_3,
                    "doc_3"           => $adjunto_subido_rpta[2],
                    "mostrar_doc_3"   => $gen_mostrar_doc_3,
                    "nombre_doc_4"    => $gen_nombre_doc_4,
                    "doc_4"           => $adjunto_subido_rpta[3],
                    "mostrar_doc_4"   => $gen_mostrar_doc_4,
                    "nombre_doc_5"    => $gen_nombre_doc_5,
                    "doc_5"           => $adjunto_subido_rpta[4],
                    "mostrar_doc_5"   => $gen_mostrar_doc_5,
                );
                //Insertamos documento_grado
                $this->m_matricula_documento->insertar($data_matricula_documento);
            break;
            
            default: //EDITAR

                $matricula_documento = $this->m_matricula_documento->mostrar(array("matricula_documento.id_anio_escolar" => $id_anio_escolar));

                $adjunto_subido_rpta_ant[0] = $matricula_documento["doc_1"];
                $adjunto_subido_rpta_ant[1] = $matricula_documento["doc_2"];
                $adjunto_subido_rpta_ant[2] = $matricula_documento["doc_3"];
                $adjunto_subido_rpta_ant[3] = $matricula_documento["doc_4"];
                $adjunto_subido_rpta_ant[4] = $matricula_documento["doc_5"];

                foreach ($adjunto as $key => $adj) {
                    $nombre_adjunto                    = $this->documento->procesa_nombre_documento($adj["name"]);
                    $_FILES['adjunto_gen']['name']     = $nombre_adjunto;
                    $_FILES['adjunto_gen']['type']     = $adj["type"];
                    $_FILES['adjunto_gen']['tmp_name'] = $adj["tmp_name"];
                    $_FILES['adjunto_gen']['error']    = $adj["error"];
                    $_FILES['adjunto_gen']['size']     = $adj["size"];
        
                    if (isset($nombre_adjunto) && $nombre_adjunto != "") { //Si al agregar tenemos archivo adjunto
                        //Eliminamos documento anterior
                        @unlink("$ruta/$adjunto_subido_rpta_ant[$key]");

                        //Primero subimos archivo adjunto
                        $adjunto_subido_gen = $this->documento->subir_adjunto("adjunto_gen", $ruta);
                        if (!$adjunto_subido_gen) {
                            $adjunto_subido_rpta[] = "Error al cargar el adjunto.";
                        }
                        else{
                            $adjunto_subido_rpta[] = $adjunto_subido_gen["file_name"];
                        }
                    } else {
                        $adjunto_subido_rpta[] = $adjunto_subido_rpta_ant[$key];
                    }
                }
            
                //Data a insertar
                $data_matricula_documento = array(
                    "id_anio_escolar"  => $id_anio_escolar,
                    "nombre_doc_1"    => $gen_nombre_doc_1,
                    "doc_1"           => $adjunto_subido_rpta[0],
                    "mostrar_doc_1"   => $gen_mostrar_doc_1,
                    "nombre_doc_2"    => $gen_nombre_doc_2,
                    "doc_2"           => $adjunto_subido_rpta[1],
                    "mostrar_doc_2"   => $gen_mostrar_doc_2,
                    "nombre_doc_3"    => $gen_nombre_doc_3,
                    "doc_3"           => $adjunto_subido_rpta[2],
                    "mostrar_doc_3"   => $gen_mostrar_doc_3,
                    "nombre_doc_4"    => $gen_nombre_doc_4,
                    "doc_4"           => $adjunto_subido_rpta[3],
                    "mostrar_doc_4"   => $gen_mostrar_doc_4,
                    "nombre_doc_5"    => $gen_nombre_doc_5,
                    "doc_5"           => $adjunto_subido_rpta[4],
                    "mostrar_doc_5"   => $gen_mostrar_doc_5,
                );
                //Actualizamos documento_grado
                $this->m_matricula_documento->actualizar($data_matricula_documento, 'id_anio_escolar', $id_anio_escolar);
                
            break;
        }

    }
}
