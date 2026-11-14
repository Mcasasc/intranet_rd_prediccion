<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Asistencia extends CI_Controller {

    private $_result;
    private $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_matricula", "m_grado", "m_nivel_educativo", "m_anio_escolar", "m_mes_escolar", "m_asistencia_alumno");
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
        $this->items['asistencia_activo'] = 'active';
    }

    /* Funcion que lista los grados que son calificados con asistencia (Primaria y Secundaria) */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Asistencia';
        $data['titulo_modulo'] = "Asistencia";
        /* ------------------------------------------------------------ */

        //Muestra todos los grados aperturados
        $lista_grados = $this->m_matricula->grados();
        /* ------------------------------------------------------------ */
        if (!empty($lista_grados)) {
            $i = 1;
            foreach ($lista_grados AS $items) {
                $grado = $this->m_grado->mostrar(array('grado.id_grado' => $items['id_grado']));
                $nivel_educativo = $this->m_nivel_educativo->mostrar(array('nivel_educativo.id_nivel_educativo' => $items['id_nivel_educativo']));
                $anio_escolar = $this->m_anio_escolar->mostrar(array('anio_escolar.id_anio_escolar' => $items['id_anio_escolar']));
                if ($grado['id_nivel_educativo'] == 1) { //Si es inicial
                    //No muestra nada
                } else { //Si es primaria o secundaria
                    //Se esta enviando un arreglo para obtenerlo en mantenimiento mediante los indices y manejar el grado y año escolar al lanzar el modal
                    $accion = $this->mantenimiento->accion(array($grado["id_grado"], $anio_escolar["id_anio_escolar"]), 'asistencia', 'asistencia', "");
                    $data['lista_grados'][] = array(
                        'numero' => $i,
                        'grado' => $grado['nombre'],
                        'seccion' => $grado['seccion'],
                        'nivel_educativo' => $nivel_educativo['nombre'],
                        'anio_escolar' => $anio_escolar['nombre'],
                        'accion' => $accion,
                    );
                    $i++;
                }
            }
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/asistencia/listar_asistencia', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para cargar la asistencia de los alumnos */
    public function pasar_asistencia($id_grado = '', $id_mes_escolar = '', $id_anio_escolar = '', $numero_dia = '') {
        $login = $this->session_sistema->datos_usuario_logueado();
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp", "es");

        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Asistencia';
        $data['titulo_modulo'] = "Asistencia";

        if ($id_grado == "" || $id_mes_escolar == "" || $id_anio_escolar == "" || $numero_dia == "") {
            echo $this->url_comp->direccionar(base_url() . 'asistencia/listar', TRUE);
            exit;
        }

        //Lista de Alumnos
        $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $id_grado, "matricula.id_anio_escolar" => $id_anio_escolar));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $mes_escolar = $this->m_mes_escolar->mostrar(array("mes_escolar.id_mes_escolar" => $id_mes_escolar));
        $nombre_dia = $this->dias[date('N', strtotime($anio_escolar['nombre'] . '-' . $id_mes_escolar . '-' . $numero_dia))] . " - " . $numero_dia;

        if (!empty($matriculados)) {            
            $r = 1;
            foreach ($matriculados as $m) {                
                $asistencia_alumno = $this->m_asistencia_alumno->mostrar(array('asistencia_alumno.id_grado' => $id_grado, 'asistencia_alumno.id_mes_escolar' => $id_mes_escolar, 'asistencia_alumno.id_anio_escolar' => $id_anio_escolar, 'asistencia_alumno.id_matricula' => $m['id_matricula']));
                $resumen_asistencias = $this->obtener_resumen($asistencia_alumno["id_asistencia_alumno"]);
                $data['lista_alumnos'][] = array(
                    "numero" => $r,
                    "id_asistencia_alumno" => $asistencia_alumno['id_asistencia_alumno'],
                    "nombre_alumno" => $m['apellido_paterno'] . ' ' . $m['apellido_materno'] . ', ' . $m['nombre'],
                    "dia" => $asistencia_alumno["dia_$numero_dia"],
                    "numero_dia" => $numero_dia,
                    "total_asistencias" => $resumen_asistencias["total_asistencias"],
                    "total_faltas" => $resumen_asistencias["total_faltas"],
                    "total_tardanzas" => $resumen_asistencias["total_tardanzas"],
                    "total_faltas_justificadas" => $resumen_asistencias["total_faltas_justificadas"],
                    "total_tardanzas_justificadas" => $resumen_asistencias["total_tardanzas_justificadas"],
                );
                $r++;
            }

            $data['nombre_grado'] = $grado['nombre'];
            $data['seccion_grado'] = $grado['seccion'];
            $data['nombre_nivel_educativo'] = $grado['nombre_nivel_educativo'];
            $data['nombre_anio_escolar'] = $anio_escolar['nombre'];
            $data['nombre_mes_escolar'] = $mes_escolar['nombre'];
            $data['nombre_dia'] = $nombre_dia;
        } else {
            echo $this->url_comp->direccionar(base_url() . 'asistencia/listar', TRUE);
            exit;
        }
        //var_dump($data['lista_alumnos']); exit;
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/asistencia/pasar_asistencia', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para guardar la asistencia en la BD */
    public function guardar_asistencia() {
        header('Content-Type: application/json');
        date_default_timezone_set("America/Lima"); //Definimos zona horaria

        //Recibimos datos
        $id_asistencia_alumno = $this->input->post('id_asistencia_alumno');
        $box_asistencia = $this->input->post('asistencia');
        $numero_dia = $this->input->post('numero_dia');

        //Data a actualizar
        $data = array(
            "dia_$numero_dia" => $box_asistencia,
        );
        //Actualizamos data
        $resultSet = $this->m_asistencia_alumno->actualizar($data, array('id_asistencia_alumno' => $id_asistencia_alumno));

        //Obtenemos resumen actualziado
        $resumen_asistencias = $this->obtener_resumen($id_asistencia_alumno);

        //Actualizamos resumen actualizado
        $data_resumen = array(
            "asistencia" => $resumen_asistencias["total_asistencias"],
            "falta" => $resumen_asistencias["total_faltas"],
            "tardanza" => $resumen_asistencias["total_tardanzas"],
            "falta_justificada" => $resumen_asistencias["total_faltas_justificadas"],
            "tardanza_justificada" => $resumen_asistencias["total_tardanzas_justificadas"],
        );
        //Actualizamos data
        $resultSet = $this->m_asistencia_alumno->actualizar($data_resumen, array('id_asistencia_alumno' => $id_asistencia_alumno));

        if ($resultSet) {
            echo json_encode($resumen_asistencias);
            exit;
        }
    }

    /* Funcion para obtener los meses escolares (JSON) */
    public function obtener_meses_escolares() {
        header('Content-Type: application/json');
        //Recibimos datos
        $id_grado = $this->input->post("id_grado");
        //Obtenemos grado
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));

        $data["nombre_grado"] = $grado["nombre"];
        $data["seccion_grado"] = $grado["seccion"];
        //Obtenemos meses escolares
        $meses_escolares = $this->m_mes_escolar->mostrar_activos();

        foreach ($meses_escolares as $me) {
            $data["meses_escolares"][] = array(
                "id_mes_escolar" => $me["id_mes_escolar"],
                "nombre" => $me["nombre"],
            );
        }
        echo json_encode($data);
    }

    /* Funcion para obtener los dias del mes (JSON)*/
    public function obtener_dias_mes() {
        header('Content-Type: application/json');
        //Obtenemos datos
        $id_mes_escolar = $this->input->post("id_mes_escolar");
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_grado = $this->input->post("id_grado");
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $mes_escolar = $this->m_mes_escolar->mostrar(array("mes_escolar.id_mes_escolar" => $id_mes_escolar));

        if ($id_mes_escolar == '4' || $id_mes_escolar == '6' || $id_mes_escolar == '9' || $id_mes_escolar == '11') { //MESES CON 30 DIAS
            $total_dias = 30;
        } else { //MESES CON 31 DIAS
            $total_dias = 31;
        }

        //Obtenemos los dias por fecha - Meses con 30 días
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp", "es");
        for ($f = 1; $f <= $total_dias; $f++) {
            $data['cantidad_dias'] = $total_dias;
            $data['mes'] = $mes_escolar['nombre'];
            $data['id_grado'] = $id_grado;
            $data['id_mes_escolar'] = $mes_escolar['id_mes_escolar'];
            $data['id_anio_escolar'] = $anio_escolar['id_anio_escolar'];

            $nombre_dia = $this->dias[date('N', strtotime($anio_escolar['nombre'] . '-' . $id_mes_escolar . '-' . $f))];
            if ($nombre_dia != "Domingo" && $nombre_dia != "Sábado") {
                $this->_result[$f] = $nombre_dia . " - " . $f;
            }
            //data para el combo

        }
        $data['combo_dias'] = $this->documento->generar_dropdown($this->_result, 'numero_dia', '', 'Seleccione una opción', 'search');
        unset($this->_result);
        echo json_encode($data);
    }

    /* Funcion para obtener resumen de todas las asistencias */
    private function obtener_resumen($id_asistencia_alumno) {
        //Obtenemos todo el resumen de asistencias del alumno
        $asistencias_alumno = $this->m_asistencia_alumno->mostrar(array("asistencia_alumno.id_asistencia_alumno" => $id_asistencia_alumno));

        //Seteamos variables en cero
        $total_asistencias = 0;
        $total_faltas = 0;
        $total_tardanzas = 0;
        $total_faltas_justificadas = 0;
        $total_tardanzas_justificadas = 0;

        //Recorremos cada dia para sumar su total
        $numero_dia = 1;
        for ($r = 0; $r < 31; $r++) {
            switch ($asistencias_alumno["dia_$numero_dia"]) {
            case "A":
                ++$total_asistencias;
                break;
            case "F":
                ++$total_faltas;
                break;
            case "T":
                ++$total_tardanzas;
                break;
            case "F.J.":
                ++$total_faltas_justificadas;
                break;
            case "T.J.":
                ++$total_tardanzas_justificadas;
                break;
            }
            $numero_dia++;
        }

        //Retornamos valores
        $data["total_asistencias"] = $total_asistencias;
        $data["total_faltas"] = $total_faltas;
        $data["total_tardanzas"] = $total_tardanzas;
        $data["total_faltas_justificadas"] = $total_faltas_justificadas;
        $data["total_tardanzas_justificadas"] = $total_tardanzas_justificadas;
        return $data;
    }
}
