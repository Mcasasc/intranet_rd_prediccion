<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Comportamiento extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_comportamiento", "m_matricula", "m_grado", "m_nivel_educativo", "m_anio_escolar", "m_bimestre");
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
        $this->items['comportamiento_activo'] = 'active';
    }

    /* Funcion que lista los grados que son calificados con comportamiento (Primaria y Secundaria) */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Comportamiento';
        $data['titulo_modulo'] = "Comportamiento";
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
                    $accion = $this->mantenimiento->accion(array($grado["id_grado"], $anio_escolar["id_anio_escolar"]), 'comportamiento', 'comportamiento', "");
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
        $this->smarty_tpl->view('internas/comportamiento/listar_comportamiento', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion que carga las notas del comportamiento */
    public function calificar($id_grado = '', $id_bimestre = '', $id_anio_escolar = '') {
        $login = $this->session_sistema->datos_usuario_logueado();
        if ($id_grado == '' || $id_bimestre == '' || $id_anio_escolar == '') {
            echo $this->url_comp->direccionar(base_url() . 'comportamiento/listar', TRUE);
            exit;
        }
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Comportamiento';
        $data['titulo_modulo'] = "Comportamiento";
        /* ------------------------------------------------------------ */

        //Lista de Alumnos
        $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $id_grado, "matricula.id_anio_escolar" => $id_anio_escolar));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $bimestre = $this->m_bimestre->mostrar(array("bimestre.id_bimestre" => $id_bimestre));
        if (!empty($matriculados)) {
            $r = 1;
            foreach ($matriculados as $m) {
                $comportamiento = $this->m_comportamiento->mostrar(array('comportamiento.id_grado' => $id_grado, 'comportamiento.id_bimestre' => $id_bimestre, 'comportamiento.id_anio_escolar' => $id_anio_escolar, 'comportamiento.id_matricula' => $m["id_matricula"]));
                $data['lista_alumnos'][] = array(
                    'numero' => $r,
                    'id_matricula' => $m['id_matricula'],
                    'nombre_alumno' => "{$m["apellido_paterno"]} {$m["apellido_materno"]} {$m["nombre"]}",
                    'alumno_retirado' => $m['estado'], // 1 -> Retirado
                    'id_comportamiento' => $comportamiento['id_comportamiento'],
                    'id_grado' => $comportamiento['id_grado'],
                    'id_bimestre' => $comportamiento['id_bimestre'],
                    'id_anio_escolar' => $comportamiento['id_anio_escolar'],
                    'nota' => $comportamiento['nota'],
                );
                $r++;
            }

            $data['id_grado'] = $id_grado;
            $data['id_bimestre'] = $id_bimestre;
            $data['id_anio_escolar'] = $id_anio_escolar;
            $data['nombre_grado'] = $grado['nombre'];
            $data['seccion_grado'] = $grado['seccion'];
            $data['nombre_nivel_educativo'] = $grado['nombre_nivel_educativo'];
            $data['nombre_anio_escolar'] = $anio_escolar['nombre'];
            $data['nombre_bimestre'] = $bimestre['nombre'];
        } else {
            echo $this->url_comp->direccionar(base_url() . 'estructura/comportamiento/listar', TRUE);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/comportamiento/form_calificar_comportamiento', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para guardar nota de comportamiento del alumno */
    public function guardar_notas() {
        header('Content-Type: application/json');
        date_default_timezone_set("America/Lima"); //Definimos zona horaria

        $id_comportamiento = $this->input->post('id_comportamiento');
        $nota = $this->input->post('nota');

        /* ------------------------------------------------------------------------- */
        $data = array(
            'nota' => $nota == '' ? null : $nota,
        );
        $resultSet = $this->m_comportamiento->actualizar($data, array('id_comportamiento' => $id_comportamiento));

        if ($resultSet) {
            echo json_encode("Se guardo correctamente");
        }
    }

    /* Funcion para obtener los meses escolares (JSON) */
    public function obtener_bimestres_escolares() {
        header('Content-Type: application/json');
        //Recibimos datos
        $id_grado = $this->input->post("id_grado");
        //Obtenemos grado
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));

        $data["nombre_grado"] = $grado["nombre"];
        $data["seccion_grado"] = $grado["seccion"];
        //Obtenemos meses escolares
        $bimestres = $this->m_bimestre->mostrar_activos();

        foreach ($bimestres as $bi) {
            $data["bimestres"][] = array(
                "id_bimestre" => $bi["id_bimestre"],
                "nombre" => $bi["nombre"],
            );
        }

        //Armamos arreglo de notas y promedio de competencia
        echo json_encode($data);
    }

}
