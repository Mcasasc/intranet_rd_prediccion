<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Psicologia extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_comportamiento", "m_matricula", "m_grado", "m_nivel_educativo", "m_anio_escolar", "m_bimestre", "m_socio_economico");
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
        $this->items['psicologia_activo'] = 'active';
    }

    /* Funcion que lista los grados que son calificados con psicologia */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Evaluaciones Psicológicas';
        $data['titulo_modulo'] = "Lista de Evaluaciones Psicológicas";
        /* ------------------------------------------------------------ */

        //Muestra todos los grados aperturados
        $lista_grados = $this->m_matricula->grados();
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($lista_grados)) {
                $i = 1;
                foreach ($lista_grados AS $items) {
                    $grado = $this->m_grado->mostrar(array('grado.id_grado' => $items['id_grado']));
                    $nivel_educativo = $this->m_nivel_educativo->mostrar(array('nivel_educativo.id_nivel_educativo' => $items['id_nivel_educativo']));
                    $anio_escolar = $this->m_anio_escolar->mostrar(array('anio_escolar.id_anio_escolar' => $items['id_anio_escolar']));
                    if ($grado['id_nivel_educativo'] == 1) { //Si es inicial
                        //No muestra nada
                    } else { //Si es primaria o secundaria

                        $data['lista_grados'][] = array(
                            'numero' => $i,
                            'grado' => $grado['nombre'],
                            'seccion' => $grado['seccion'],
                            'nivel_educativo' => $nivel_educativo['nombre'],
                            'anio_escolar' => $anio_escolar['nombre'],
                            'id_grado' => $grado["id_grado"],
                            'id_anio_escolar' => $anio_escolar["id_anio_escolar"],
                        );
                        $i++;
                    }
                }
            }
        }
        else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            EXIT;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/psicologia/listar_psicologia', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion que carga las notas psicologicas */
    public function calificar($id_grado = '', $id_anio_escolar = '') {
        $login = $this->session_sistema->datos_usuario_logueado();

        if ($id_grado == '' || $id_anio_escolar == '') {
            echo $this->url_comp->direccionar(base_url() . 'psicologia/listar', TRUE);
            exit;
        }
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Registro de Nota Psicológica';
        $data['titulo_modulo'] = "Registro de Nota Psicológica";
        /* ------------------------------------------------------------ */

        //Lista de Alumnos
        $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $id_grado, "matricula.id_anio_escolar" => $id_anio_escolar));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));

        if (!empty($matriculados)) {
            $r = 1;
            foreach ($matriculados as $m) {
                $socio_economico = $this->m_socio_economico->mostrar(array('socio_economico.id_grado' => $id_grado, 'socio_economico.id_anio_escolar' => $id_anio_escolar, 'socio_economico.id_matricula' => $m["id_matricula"]));
                $data['lista_alumnos'][] = array(
                    'numero' => $r,
                    'id_matricula' => $m['id_matricula'],
                    'nombre_alumno' => strtoupper("{$m["apellido_paterno"]} {$m["apellido_materno"]} {$m["nombre"]}"),
                    'id_psicologia' => $socio_economico['id_socio_economico'],
                    'id_grado' => $socio_economico['id_grado'],
                    'id_anio_escolar' => $socio_economico['id_anio_escolar'],
                    'nota' => $socio_economico['nota'],
                );
                $r++;
            }

            $data['id_grado'] = $id_grado;
            $data['id_anio_escolar'] = $id_anio_escolar;
            $data['nombre_grado'] = $grado['nombre'];
            $data['seccion_grado'] = $grado['seccion'];
            $data['nombre_nivel_educativo'] = $grado['nombre_nivel_educativo'];
            $data['nombre_anio_escolar'] = $anio_escolar['nombre'];
        } else {
            echo $this->url_comp->direccionar(base_url() . 'estructura/psicologia/listar', TRUE);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/psicologia/form_psicologia', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para guardar nota de psicologia */
    public function guardar_evaluacion() {

        header('Content-Type: application/json');
        date_default_timezone_set("America/Lima"); //Definimos zona horaria

        $id_psicologia = $this->input->post('id_psicologia');
        $nota = $this->input->post('nota');

        /* ------------------------------------------------------------------------- */
        $data = array(
            'nota' => $nota == '' ? null : $nota,
        );
        $resultSet = $this->m_socio_economico->actualizar($data, array('id_socio_economico' => $id_psicologia));

        if ($resultSet) {
            echo json_encode("Se guardo correctamente");
        }
    }

}
