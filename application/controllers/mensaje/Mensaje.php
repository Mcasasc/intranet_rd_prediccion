<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Mensaje extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_mensaje", "m_mensaje_contenido", "m_anio_escolar", "m_matricula", "m_grado", "m_nivel_educativo", "m_mensaje_matricula", "m_matricula_documento_grado", "m_matricula_documento");
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
        $this->items['mensaje_activo'] = 'active';
        $this->ruta_guardar_adjunto = "./documents/adjuntos/";
        $this->ruta_mostrar_adjunto = "documents/adjuntos/";

        $this->ruta_adjunto_matricula = "documents/matricula/";
    }

    /* Funcion para listar todos los mensajes */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        
        $id_privilegio = $login["usuario_privilegio"];
        $data["id_privilegio"] = $id_privilegio;
        $data['titulo_pagina'] = "Lista de Mensajes | {$this->items['proyecto']}";
        $data['titulo_modulo'] = "Lista de Mensajes";
        /* ------------------------------------------------------------ */
        if ($id_privilegio == 1 || $id_privilegio == 7 || $id_privilegio == 3 || $id_privilegio == 9) { //Admin, Alumno o Psicologa

            if ($id_privilegio == 3) { //Vista para el alumno
                //Combo de años escolares
                $lista_anio_escolar = $this->m_anio_escolar->mostrar_todo();
                $ultimo_anio_escolar = $this->m_anio_escolar->maximo_id("id_anio_escolar"); //Obtenemos el ultimo año escolar registrado en la bd
                foreach ($lista_anio_escolar as $items) {
                    $this->_result[$items['id_anio_escolar']] = $items['nombre'];
                }
                $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'combo_mensaje_anio_escolar', $ultimo_anio_escolar, 'Seleccione una opción');
                unset($this->_result);

                //Obtenemos data del alumno segun el usuario logueado
                $alumno = $this->m_alumno->mostrar(array("alumno.id_usuario" => $login["usuario_id"]));
                
                //Obtenemos data de la matricula segun id_alumno y ultimo año escolar registrado en la bd
                $matricula = $this->m_matricula->mostrar(array("matricula.id_alumno" => $alumno["id_alumno"], "matricula.id_anio_escolar" => $ultimo_anio_escolar));
                $data["pdf_lista_utiles"] = $this->obtenerListaUtiles($matricula["id_grado"]);
                $data["id_ultimo_anio_escolar"] = $ultimo_anio_escolar;
                //Lista de mensajes
                $lista_mensajes_matricula = $this->m_mensaje_matricula->mostrar_cuando(array("mensaje_matricula.id_matricula" => $matricula["id_matricula"]));

                $i = 1;
                foreach ($lista_mensajes_matricula AS $items) {
                    $accion = $this->mantenimiento->accion($items['id_mensaje'], 'ver', 'mensaje', "");
                    $data['lista'][] = array(
                        'numero' => $i,
                        'id_mensaje' => $items['id_mensaje'],
                        'asunto' => $items['asunto'],
                        'fecha_envio' => $items['fecha_envio'],
                        'archivo_adjunto' => $items['archivo_adjunto'],
                        'leido' => $items['leido'],
                        'accion' => $accion,
                    );
                    $i++;
                }

                $data["documentos"] = $this->obtener_documentos($ultimo_anio_escolar, $matricula["id_grado"]);

            } else { //Vista para administrador
                //Lista de mensajes
                $lista = $this->m_mensaje->mostrar_todo();

                $i = 1;
                foreach ($lista AS $items) {
                    $accion = $this->mantenimiento->accion($items['id_mensaje'], 'editar|eliminar|vistos', 'mensaje', "");
                    $data['lista'][] = array(
                        'numero' => $i,
                        'id_mensaje' => $items['id_mensaje'],
                        'destinatarios' => $this->parsea_destinatarios($items['destinatarios']),
                        'asunto' => $items['asunto'],
                        'fecha_envio' => $items['fecha_envio'],
                        'archivo_adjunto' => $items['archivo_adjunto'],
                        'accion' => $accion,
                    );
                    $i++;
                }

            }

        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/mensaje/listar_mensaje', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para mostrar el formulario de registro de mensaje */
    public function agregar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = "Agregar Mensaje | {$this->items["proyecto"]}";
        $data['titulo_modulo'] = "Agregar Mensaje";
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador, puede editar

            //Combo de años escolares
            $lista_anio_escolar = $this->m_anio_escolar->mostrar_todo();
            foreach ($lista_anio_escolar as $items) {
                $this->_result[$items['id_anio_escolar']] = $items['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', "", 'Seleccione una opción');
            unset($this->_result);

            $data['tiene_adjunto'] = "";

        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/mensaje/form_mensaje', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para agregar o editar usuario */
    public function accion() {
        //Validamos sesion
        $login = $this->session_sistema->datos_usuario_logueado();
        $id_usuario = $login["usuario_id"];

        //Definimos zona horaria
        date_default_timezone_set("America/Lima");
        //Obtenemos datos
        $id_mensaje = $this->input->post('id_mensaje');
        $para = $this->input->post('para');
        $array_destinatarios = explode(',', $para); //Convertimos los id a array
        $nombres_destinatarios = $this->input->post('nombres_destinatarios');
        $array_nombres_destinatarios = json_encode(explode(',', $nombres_destinatarios), JSON_UNESCAPED_UNICODE); //Convertimos los nombres a array
        $tipo = $this->input->post('tipo');
        $id_anio_escolar = $this->input->post('id_anio_escolar');
        $asunto = $this->input->post('asunto');
        $adjunto = isset($_FILES['adjunto']) ? $_FILES['adjunto'] : "";
        $nombre_input_adjunto = "adjunto"; //nombre del atributo name del input
        $contenido = trim($this->input->post('contenido'));

        //Validamos campos
        $error = '';
        if (empty($id_mensaje)) {
            $error .= $this->mantenimiento->validacion($para, 'required', 'Para');
        }
        $error .= $this->mantenimiento->validacion($asunto, 'required', 'Asunto');
        $error .= $this->mantenimiento->validacion($contenido, 'required', 'Contenido');

        //Si hay error de validacion
        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }

        switch ($id_mensaje) {
        case '': //AGREGAR
            if (isset($adjunto["name"]) && $adjunto["name"] != "") { //Si al agregar tenemos archivo adjunto
                //Primero subimos archivo adjunto
                $adjunto_subido = $this->documento->subir_adjunto($nombre_input_adjunto, $this->ruta_guardar_adjunto);
                if (!$adjunto_subido) {
                    echo $this->alerta->alerta_error('Error al cargar el adjunto.');
                    exit;
                }
            } else {
                $adjunto_subido["file_name"] = "";
            }

            //Agregamos cabecera de mensaje (mensaje)
            $data_mensaje = array(
                'destinatarios' => $array_nombres_destinatarios,
                'asunto' => $asunto,
                'fecha_envio' => date("Y-m-d H:i:s"),
                'id_usuario' => $id_usuario,
            );
            //Insertamos mensaje
            $result_mensaje = $this->m_mensaje->insertar($data_mensaje);

            //Agregamos contenido de mensaje (mensaje_contenido)
            $data_mensaje_contenido = array(
                'id_mensaje' => $result_mensaje,
                'contenido' => $contenido,
                'archivo_adjunto' => $adjunto_subido["file_name"], //Nombre del adjunto
            );
            //Insertamos mensaje_contenido
            $this->m_mensaje_contenido->insertar($data_mensaje_contenido);

            //Agregamos el mensaje a cada matriculado (mensaje_matricula)
            switch ($tipo) {
            case 'alumno':
                foreach ($array_destinatarios as $d) { //Recorremos los id de los alumnos
                    //Data para el registro de mensaje
                    $data_mensaje_matricula = array(
                        'id_mensaje' => $result_mensaje,
                        'id_matricula' => $d,
                    );
                    //Insertamos mensaje
                    $resultSet = $this->m_mensaje_matricula->insertar($data_mensaje_matricula);
                }
                break;
            case 'grado':
                //obtenemos todos los alumnos pertenecientes al grado
                foreach ($array_destinatarios as $d) { //Recorremos los id de los grados
                    $lista_matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $d, "matricula.id_anio_escolar" => $id_anio_escolar));
                    foreach ($lista_matriculados as $lm) {
                        $data_mensaje_matricula = array(
                            'id_mensaje' => $result_mensaje,
                            'id_matricula' => $lm["id_matricula"],
                        );
                        //Insertamos mensaje
                        $resultSet = $this->m_mensaje_matricula->insertar($data_mensaje_matricula);
                    }
                }
                break;
            case 'nivel_educativo':
                //obtenemos todos los alumnos pertenecientes al grado
                foreach ($array_destinatarios as $d) { //Recorremos los id del nivel educativo
                    $lista_matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_nivel_educativo" => $d, "matricula.id_anio_escolar" => $id_anio_escolar));
                    foreach ($lista_matriculados as $lm) {
                        $data_mensaje_matricula = array(
                            'id_mensaje' => $result_mensaje,
                            'id_matricula' => $lm["id_matricula"],
                        );
                        //Insertamos mensaje
                        $resultSet = $this->m_mensaje_matricula->insertar($data_mensaje_matricula);
                    }
                }
                break;
            }

            //Si se inserto correctamente
            if ($resultSet) {
                echo $this->alerta->alerta_exito('Se ha registrado correctamente.');
                echo $this->url_comp->direccionar_tiempo(base_url() . 'mensaje/listar', '1000');
                exit;
            }
            break;

        default: //EDITAR
            //Consultamos el contenido del mensaje para obtener el nombre del archivo adjunto
            $mensaje_contenido = $this->m_mensaje_contenido->mostrar(array("mensaje_contenido.id_mensaje" => $id_mensaje));

            if (isset($adjunto["name"]) && $adjunto["name"] != "") { //si hemos adjuntado un nuevo documento
                //Subimos nuevo documento
                $adjunto_subido = $this->documento->subir_adjunto($nombre_input_adjunto, $this->ruta_guardar_adjunto);
                if (!$adjunto_subido) {
                    echo $this->alerta->alerta_error('Error al cargar el adjunto.');
                    exit;
                }
                //Eliminamos documento anterior
                unlink("$this->ruta_guardar_adjunto{$mensaje_contenido["archivo_adjunto"]}");

            } else { //Si no hemos adjuntado nada nuevo, se mantiene el mismo documento
                $adjunto_subido["file_name"] = $mensaje_contenido["archivo_adjunto"];
            }

            //Data para actualizar mensaje
            $data_mensaje = array(
                'asunto' => $asunto,
                'fecha_modificacion' => date("Y-m-d H:i:s"),
                'id_usuario' => $id_usuario,
            );
            //Actualizamos mensaje
            $result_mensaje = $this->m_mensaje->actualizar($data_mensaje, 'id_mensaje', $id_mensaje);

            //Data para actualizar mensaje_contenido
            $data_mensaje_contenido = array(
                'contenido' => $contenido,
                'archivo_adjunto' => $adjunto_subido["file_name"],
            );
            //Actualizamos mensaje
            $this->m_mensaje_contenido->actualizar($data_mensaje_contenido, 'id_mensaje', $id_mensaje);

            echo $this->alerta->alerta_exito('Se ha editado correctamente.');
            echo $this->url_comp->direccionar_tiempo(base_url() . 'mensaje/listar', '1000');
            exit;
            break;
        }

        if ($resultSet === FALSE) {
            echo $this->alerta->alerta_error('Hubo algunos errores.');
            exit;
        }

    }

    /* Funcion para editar un mensaje */
    public function editar($id_mensaje = '') {
        //Validamos sesion
        $login = $this->session_sistema->datos_usuario_logueado();

        $data['titulo_pagina'] = "Editar Mensaje | {$this->items["proyecto"]}";
        $data['titulo_modulo'] = "Editar Mensaje";

        /* ------------------------------------------------------------ */
        $mensaje = $this->m_mensaje->mostrar(array('mensaje.id_mensaje' => $id_mensaje));
        $mensaje_contenido = $this->m_mensaje_contenido->mostrar(array('mensaje_contenido.id_mensaje' => $id_mensaje));

        if (!empty($mensaje)) {
            $data['id_mensaje'] = $mensaje['id_mensaje'];
            $data['asunto'] = $mensaje['asunto'];
            $data['contenido'] = $mensaje_contenido['contenido'];
            $data['archivo_adjunto'] = base_url() . $this->ruta_mostrar_adjunto . $mensaje_contenido["archivo_adjunto"];
            $data['tiene_adjunto'] = $mensaje_contenido["archivo_adjunto"] == "" ? "no" : "si";

            //Combo de años escolares
            $lista_anio_escolar = $this->m_anio_escolar->mostrar_todo();
            foreach ($lista_anio_escolar as $items) {
                $this->_result[$items['id_anio_escolar']] = $items['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', "", 'Seleccione una opción');
            unset($this->_result);

        } else {
            echo $this->url_comp->direccionar(base_url() . 'mensaje/listar', true);
            exit;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/mensaje/form_mensaje', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para ver un mensaje (Vista de Alumno) */
    public function ver($id_mensaje = '') {
        //Validamos sesion
        $login = $this->session_sistema->datos_usuario_logueado();

        $data['titulo_pagina'] = "Ver Mensaje | {$this->items["proyecto"]}";
        $data['titulo_modulo'] = "Ver Mensaje";

        /* ------------------------------------------------------------ */
        $mensaje = $this->m_mensaje->mostrar(array('mensaje.id_mensaje' => $id_mensaje));
        $mensaje_contenido = $this->m_mensaje_contenido->mostrar(array('mensaje_contenido.id_mensaje' => $id_mensaje));
        //print_r($mensaje_contenido);
        if (!empty($mensaje)) {
            $data['id_mensaje'] = $mensaje['id_mensaje'];
            $data['asunto'] = $mensaje['asunto'];
            $data['contenido'] = $mensaje_contenido['contenido'];
            $data['archivo_adjunto'] = base_url() . "$this->ruta_mostrar_adjunto{$mensaje_contenido["archivo_adjunto"]}";
            $data['tiene_adjunto'] = $mensaje_contenido["archivo_adjunto"] == "" ? "no" : "si";
            //var_dump($data['contenido']); //exit;
            //Combo de años escolares
            $lista_anio_escolar = $this->m_anio_escolar->mostrar_todo();
            foreach ($lista_anio_escolar as $items) {
                $this->_result[$items['id_anio_escolar']] = $items['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', "", 'Seleccione una opción');
            unset($this->_result);

            //Actualizamos campo leido
            //Data para actualizar mensaje
            $data_leido = array(
                'leido' => 1, //Mensaje le
                'fecha_leido' => date("Y-m-d H:i:s"),
            );

            $ultimo_anio_escolar = $this->m_anio_escolar->maximo_id("id_anio_escolar"); //Obtenemos el ultimo año escolar registrado en la bd
            //Obtenemos data del alumno segun el usuario logueado
            $alumno = $this->m_alumno->mostrar(array("alumno.id_usuario" => $login["usuario_id"]));
            //Obtenemos data de la matricula segun id_alumno y ultimo año escolar registrado en la bd
            $matricula = $this->m_matricula->mostrar(array("matricula.id_alumno" => $alumno["id_alumno"], "matricula.id_anio_escolar" => $ultimo_anio_escolar));
            //Actualizamos mensaje
            $this->m_mensaje_matricula->actualizar($data_leido, array("id_mensaje" => $id_mensaje, "id_matricula" => $matricula["id_matricula"]));

        } else {
            echo $this->url_comp->direccionar(base_url() . 'mensaje/listar', true);
            exit;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/mensaje/ver_mensaje', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para listar todos los mensajes */
    public function filtro($id_anio_escolar) {
        $login = $this->session_sistema->datos_usuario_logueado();
        $id_privilegio = $login["usuario_privilegio"];
        $data["id_privilegio"] = $id_privilegio;
        $data['titulo_pagina'] = "Lista de Mensajes | {$this->items['proyecto']}";
        $data['titulo_modulo'] = "Lista de Mensajes";
        /* ------------------------------------------------------------ */
        if ($id_privilegio == 3) { //Admin o Alumno

            //Combo de años escolares
            $lista_anio_escolar = $this->m_anio_escolar->mostrar_todo();
            foreach ($lista_anio_escolar as $items) {
                $this->_result[$items['id_anio_escolar']] = $items['nombre'];
            }
            $data['combo_anio_escolar'] = $this->documento->generar_dropdown($this->_result, 'combo_mensaje_anio_escolar', $id_anio_escolar, 'Seleccione una opción');
            unset($this->_result);

            //Obtenemos data del alumno segun el usuario logueado
            $alumno = $this->m_alumno->mostrar(array("alumno.id_usuario" => $login["usuario_id"]));
            
            //Obtenemos data de la matricula segun id_alumno y ultimo año escolar registrado en la bd
            $matricula = $this->m_matricula->mostrar(array("matricula.id_alumno" => $alumno["id_alumno"], "matricula.id_anio_escolar" => $id_anio_escolar));
            $data["pdf_lista_utiles"] = $this->obtenerListaUtiles($matricula["id_grado"]);
            $data["id_ultimo_anio_escolar"] = $id_anio_escolar;
            //Lista de mensajes
            $lista_mensajes_matricula = $this->m_mensaje_matricula->mostrar_cuando(array("mensaje_matricula.id_matricula" => $matricula["id_matricula"]));

            $i = 1;
            foreach ($lista_mensajes_matricula AS $items) {
                $accion = $this->mantenimiento->accion($items['id_mensaje'], 'ver', 'mensaje', "");
                $data['lista'][] = array(
                    'numero' => $i,
                    'id_mensaje' => $items['id_mensaje'],
                    'asunto' => $items['asunto'],
                    'fecha_envio' => $items['fecha_envio'],
                    'archivo_adjunto' => $items['archivo_adjunto'],
                    'accion' => $accion,
                );
                $i++;
            }

            $data["documentos"] = $this->obtener_documentos($id_anio_escolar, $matricula["id_grado"]);


        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            exit;
        }

        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/mensaje/listar_mensaje', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para obtener lista de destinatarios */
    public function obtener_listas() {
        header('Content-Type: application/json');

        $tipo = $this->input->post("tipo");
        $id_anio_escolar = $this->input->post("id_anio_escolar");

        switch ($tipo) {
        case 'alumno': //Retornamos combo de matriculados
            $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_anio_escolar" => $id_anio_escolar));

            foreach ($matriculados as $items) {
                $this->_result[$items["id_matricula"]] = "{$items["apellido_paterno"]} {$items["apellido_materno"]}, {$items["nombre"]} - {$items["dni"]}";
            }
            $data = $this->documento->generar_dropdown($this->_result, 'id_matricula', "", null, "multiple");
            break;

        case "grado": //Retornamos combo de grados
            $grados = $this->m_grado->mostrar_activos();
            foreach ($grados as $items) {
                $this->_result[$items["id_grado"]] = "{$items["nombre"]} - {$items["nombre_nivel_educativo"]}";
            }
            $data = $this->documento->generar_dropdown($this->_result, 'id_grado', "", null, "multiple");
            break;

        case "nivel_educativo": //Retornamos combo de niveles educativo
            $nivel_educativo = $this->m_nivel_educativo->mostrar_activos();
            foreach ($nivel_educativo as $items) {
                $this->_result[$items["id_nivel_educativo"]] = "{$items["nombre"]}";
            }
            $data = $this->documento->generar_dropdown($this->_result, 'id_nivel_educativo', "", null, "multiple");
            break;
        }
        unset($this->_result);
        echo json_encode($data);
    }

    /* Funcion para consultar los id del combo selecionado segun sea el tipo*/
    public function obtener_destinatarios() {
        header('Content-Type: application/json');

        //Recibimos datos
        $tipo = $this->input->post("tipo");
        $destinatarios = $this->input->post("destinatarios"); //Array
        if (empty($destinatarios)) {
            $data = null;
        } else {
            switch ($tipo) {
            case 'alumno':
                foreach ($destinatarios as $d) {
                    $matriculado = $this->m_matricula->mostrar(array("matricula.id_matricula" => $d));
                    $data[] = array(
                        'id' => $matriculado['id_matricula'],
                        'nombre' => "{$matriculado['nombre']} {$matriculado['apellido_paterno']} {$matriculado['apellido_materno']}",
                    );
                }
                break;
            case 'grado':
                foreach ($destinatarios as $d) {
                    $grado = $this->m_grado->mostrar(array("grado.id_grado" => $d));
                    $data[] = array(
                        'id' => $grado['id_grado'],
                        'nombre' => "{$grado['nombre']} de {$grado["nombre_nivel_educativo"]}",
                    );
                }
                break;
            case 'nivel_educativo':
                foreach ($destinatarios as $d) {
                    $nivel_educativo = $this->m_nivel_educativo->mostrar(array("nivel_educativo.id_nivel_educativo" => $d));
                    $data[] = array(
                        'id' => $nivel_educativo['id_nivel_educativo'],
                        'nombre' => "{$nivel_educativo['nombre']}",
                    );
                }
                break;
            }
        }
        echo json_encode($data);
    }

    /* Funcion para eliminar el mensaje enviado */
    public function accion_eliminar($id_mensaje) {
        if ($id_mensaje == '') {
            echo $this->url_comp->direccionar(base_url() . 'mensaje/listar', TRUE);
            exit;
        }

        //eliminamos todos los mensajes enviados a los alumnos (mensaje_matricula)
        $this->m_mensaje_matricula->eliminar(array("mensaje_matricula.id_mensaje" => $id_mensaje));
        //Eliminamos el contenido del mensaje (mensaje_contenido)
        $this->m_mensaje_contenido->eliminar(array("mensaje_contenido.id_mensaje" => $id_mensaje));
        //Eliminamos el mensaje (mensaje)
        $this->m_mensaje->eliminar(array("mensaje.id_mensaje" => $id_mensaje));

        echo $this->url_comp->direccionar(base_url() . 'mensaje/listar', TRUE);
    }

    public function mostrar_leidos() {
        header('Content-Type: application/json');
        $id_mensaje = $this->input->post("id_mensaje");

        $lista_mensajes_matricula = $this->m_mensaje_matricula->mostrar_cuando(array("mensaje_matricula.id_mensaje" => $id_mensaje));
        //Declaramos arrays vacios
        $data["leidos"] = array();
        $data["no_leidos"] = array();
        //Recorremos toda la lista
        foreach ($lista_mensajes_matricula as $lm) {
            $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $lm["id_matricula"]));
            if ($lm["leido"] == 1) { //Leido
                $data["leidos"][] = array(
                    "alumno" => "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}",
                    "fecha_leido" => $lm["fecha_leido"],
                );
            } else { // 0 -> No leido
                $data["no_leidos"][] = array(
                    "alumno" => "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}",
                );
            }

        }
        //Ordenamos ambos arrays por valor
        //var_dump($this->orderMultiDimensionalArray($data["leidos"], "fecha_leido", true)); exit;
        $data["leidos"] = $this->mantenimiento->ordenaArrayMultidimensional($data["leidos"], "fecha_leido", true);
        $data["no_leidos"] = $this->mantenimiento->ordenaArrayMultidimensional($data["no_leidos"], "alumno", false);
        echo json_encode($data);
    }

    /* Funcion para recorrer los destinatarios y mostrarlos formateados */
    public function parsea_destinatarios($data_destinatarios) {
        //Recorremos los destinatarios
        $lista_array = json_decode($data_destinatarios);

        $html = "";
        //foreach ($lista_array as $la) {
        for ($i = 0; $i < count($lista_array); $i++) {
            $html .= "<span class='badge badge-primary ml-2 h6'>{$lista_array["$i"]}</span>";
        }
        return $html;
    }


    /* Funcion para obtener pdf de lista de utiles segun el grado */
    private function obtenerListaUtiles($id_grado){
        $data = array(
            "archivo_pdf" => "",
            "etiqueta_pdf" => ""
        );

        switch ($id_grado) {
            case 1: //1RO PRIM
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_1RO_PRIMARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 1ro de Primaria";
            break;
            case 2: //2DO PRIM
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_2DO_PRIMARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 2do de Primaria";
            break;
            case 3: //3ER PRIM
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_3RO_PRIMARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 3ro de Primaria";
            break;
            case 4: //4TO PRIM
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_4TO_PRIMARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 4to de Primaria";
            break;
            case 5: //5TO PRIM
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_5TO_PRIMARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 5to de Primaria";
            break;
            case 6: //6TO PRIM
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_6TO_PRIMARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 6to de Primaria";
            break;
            case 7: //1RO SEC
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_1RO_SECUNDARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 1ro de Secundaria";
            break;
            case 8: //2DO SEC
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_2DO_SECUNDARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 2do de Secundaria";
            break;
            case 9: //3RO SEC
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_3RO_SECUNDARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 3ro de Secundaria";
            break;
            case 10: //4TO SEC
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_4TO_SECUNDARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 4to de Secundaria";
            break;
            case 11: //5TO SEC
                $data["archivo_pdf"] = base_url() . "documents/documentos_matricula/2022/LISTA_UTILES_5TO_SECUNDARIA_2022.pdf";
                $data["etiqueta_pdf"] = "Lista de Útiles 2022 - 5to de Secundaria";
            break;
        }
        return $data;
    }

    private function obtener_documentos($id_anio_escolar, $id_grado){

        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        
        $matricula_documento       = $this->m_matricula_documento->mostrar(array("matricula_documento.id_anio_escolar" => $id_anio_escolar));
        $matricula_documento_grado = $this->m_matricula_documento_grado->mostrar(array("matricula_documento_grado.id_anio_escolar" => $id_anio_escolar, "matricula_documento_grado.id_grado" => $id_grado));
        //print_r($matricula_documento_grado);
        //print_r($matricula_documento);
        //exit;
        $ruta     = base_url() . $this->ruta_adjunto_matricula . $anio_escolar["nombre"] . "/" . $grado["codigo"]. "/";
        $ruta_gen = base_url() . $this->ruta_adjunto_matricula . $anio_escolar["nombre"] . "/". "GENERAL/";

        $data["matricula_documento"] = array(
             "nombre_doc_1"  => $matricula_documento["nombre_doc_1"],
                    "doc_1"         => $ruta_gen . $matricula_documento["doc_1"],
                    "mostrar_doc_1" => $matricula_documento["mostrar_doc_1"],
                  "nombre_doc_2"  => $matricula_documento["nombre_doc_2"],
                    "doc_2"         => $ruta_gen . $matricula_documento["doc_2"],
                    "mostrar_doc_2" => $matricula_documento["mostrar_doc_2"],
                  "nombre_doc_3"  => $matricula_documento["nombre_doc_3"],
                    "doc_3"         => $ruta_gen . $matricula_documento["doc_3"],
                    "mostrar_doc_3" => $matricula_documento["mostrar_doc_3"],
                 "nombre_doc_4"  => $matricula_documento["nombre_doc_4"],
                    "doc_4"         => $ruta_gen . $matricula_documento["doc_4"],
                    "mostrar_doc_4" => $matricula_documento["mostrar_doc_4"],
              "nombre_doc_5"  => $matricula_documento["nombre_doc_5"],
                    "doc_5"         => $ruta_gen . $matricula_documento["doc_5"],
                    "mostrar_doc_5" => $matricula_documento["mostrar_doc_5"]
            
        );

        $data["matricula_documento_grado"] = array(
            
                "nombre_doc_1"  => $matricula_documento_grado["nombre_doc_1"],
                "doc_1"         => $ruta . $matricula_documento_grado["doc_1"],
                "mostrar_doc_1" => $matricula_documento_grado["mostrar_doc_1"],
            
                "nombre_doc_2"  => $matricula_documento_grado["nombre_doc_2"],
                "doc_2"         => $ruta . $matricula_documento_grado["doc_2"],
                "mostrar_doc_2" => $matricula_documento_grado["mostrar_doc_2"],
            
                "nombre_doc_3"  => $matricula_documento_grado["nombre_doc_3"],
                "doc_3"         => $ruta . $matricula_documento_grado["doc_3"],
                "mostrar_doc_3" => $matricula_documento_grado["mostrar_doc_3"]
        );

        return $data;
    }

}
