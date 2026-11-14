<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Inicial extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_curso", "m_matricula", "m_alumno", "m_bimestre", "m_anio_escolar", "m_curso_docente", "m_competencia_curso_inicial", "m_indicador_competencia_curso_inicial",
            "m_nota_alumno_inicial");
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
        $this->items['nota_activo'] = 'active';
    }

    /* Funcion para cargar las competencias y alumnos del nivel inicial */
    public function calificar($id_curso, $id_anio_escolar, $id_bimestre) {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Calificar';
        $data['titulo_modulo'] = "Calificar";

        /* ------------------------------------------------------------ */
        //Obtenemos datos
        $curso = $this->m_curso->mostrar(array("curso.id_curso" => $id_curso));
        $bimestre = $this->m_bimestre->mostrar(array("bimestre.id_bimestre" => $id_bimestre));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $docente = $this->m_curso_docente->mostrar(array("curso_docente.id_curso" => $id_curso, "curso_docente.id_anio_escolar" => $id_anio_escolar));
        $id_grado = $curso["id_grado"];
        $id_nivel_educativo = $curso["grado_id_nivel_educativo"];
        $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $id_grado, "matricula.id_nivel_educativo" => $id_nivel_educativo, "matricula.id_anio_escolar" => $id_anio_escolar));

        //Datos Generales
        $data["nombre_bimestre"] = $bimestre["nombre"];
        $data["nombre_grado"] = $curso["nombre_grado"];
        $data["seccion_grado"] = $curso["seccion_grado"];
        $data["nombre_nivel_educativo"] = $curso["nombre_nivel_educativo"];
        $data["nombre_curso"] = $curso["nombre"];
        $data["nombre_anio_escolar"] = $anio_escolar["nombre"];
        $data["nombre_docente"] = "{$docente["nombre_docente"]} {$docente["apellido_paterno_docente"]} {$docente["apellido_materno_docente"]}";

        //Datos hidden
        $data["id_curso"] = $id_curso;
        $data["id_bimestre"] = $id_bimestre;
        $data["id_anio_escolar"] = $id_anio_escolar;
        $data["id_grado"] = $id_grado;
        $data["id_nivel_educativo"] = $id_nivel_educativo;

        //Competencias del curso
        $competencias = $this->m_competencia_curso_inicial->mostrar(array("competencia_curso_inicial.id_curso" => $id_curso));
    
        $data["nombre_competencia_1"] = $competencias["nombre_competencia_1"];
        $data["nombre_competencia_2"] = $competencias["nombre_competencia_2"];
        $data["nombre_competencia_3"] = $competencias["nombre_competencia_3"];

        //Lista de Alumnos
        $i = 1;
        foreach ($matriculados as $m) {
            $alumno = $this->m_alumno->mostrar(array("alumno.id_alumno" => $m["id_alumno"]));
            $data["lista_alumnos"][] = array(
                "numero" => $i,
                "nombre_alumno" => "{$alumno["apellido_paterno"]} {$alumno["apellido_materno"]}, {$alumno["nombre"]}",
            );
            $i++;
        }
        //var_dump($data["lista_alumnos"]); exit;
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/nota/registro_inicial', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para obtener indicadores segun el numero de competencia que se seleccione y las notas que pertenecen al indicador */
    public function obtener_notas_indicadores() {
        header('Content-Type: application/json');
        //Recibimos datos
        $id_curso = $this->input->post("id_curso");
        $id_bimestre = $this->input->post("id_bimestre");
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_grado = $this->input->post("id_grado");
        $id_nivel_educativo = $this->input->post("id_nivel_educativo");
        $numero_competencia = $this->input->post("numero_competencia");

        //Obtenemos indicadores
        $indicadores = $this->m_indicador_competencia_curso_inicial->mostrar(array("indicador_competencia_curso_inicial.id_curso" => $id_curso, "indicador_competencia_curso_inicial.id_bimestre" => $id_bimestre, "indicador_competencia_curso_inicial.id_anio_escolar" => $id_anio_escolar, "indicador_competencia_curso_inicial.id_grado" => $id_grado, "indicador_competencia_curso_inicial.id_nivel_educativo" => $id_nivel_educativo));
        $fin_rango = $numero_competencia * 8; //Numero de competencias * numero de indicadores (8)
        $inicio_rango = $fin_rango - 7; //Restamos 7 para obtener el inicio de los indicadores que le pertenecen a la competencia
        $inicio_rango_2 = $fin_rango - 7; //Restamos 7 para obtener el inicio de los indicadores que le pertenecen a la competencia
        $indice_nota_1 = $inicio_rango_2++;
        $indice_nota_2 = $inicio_rango_2++;
        $indice_nota_3 = $inicio_rango_2++;
        $indice_nota_4 = $inicio_rango_2++;
        $indice_nota_5 = $inicio_rango_2++;
        $indice_nota_6 = $inicio_rango_2++;
        $indice_nota_7 = $inicio_rango_2++;
        $indice_nota_8 = $inicio_rango_2++;

        //Enviamos rangos para las notas
        $data["inicio_rango"] = $inicio_rango;
        $data["fin_rango"] = $fin_rango;
        $data["id_indicador_competencia_curso_inicial"] = $indicadores["id_indicador_competencia_curso_inicial"];
        //Armamos arreglo de indicadores segun el rango de inicio y fin
        for ($i = $inicio_rango; $i <= $fin_rango; $i++) {
            $data["nombre_indicadores"]["indicador_$inicio_rango"] = $indicadores["indicador_$inicio_rango"] == null ? "" : $indicadores["indicador_$inicio_rango"];
            $inicio_rango++;
        }

        //Obtenemos notas
        $notas_alumnos = $this->m_nota_alumno_inicial->mostrar_cuando(array("nota_alumno_inicial.id_bimestre" => $id_bimestre, "nota_alumno_inicial.id_anio_escolar" => $id_anio_escolar, "nota_alumno_inicial.id_curso" => $id_curso));
        //Armamos arreglo de notas y promedio de competencia
        foreach ($notas_alumnos as $na) {
            $data["notas_alumnos"][] = array(
                "id_nota_alumno_inicial" => $na["id_nota_alumno_inicial"],
                "nota_$indice_nota_1" => $na["nota_$indice_nota_1"] == null ? "" : $na["nota_$indice_nota_1"],
                "nota_$indice_nota_2" => $na["nota_$indice_nota_2"] == null ? "" : $na["nota_$indice_nota_2"],
                "nota_$indice_nota_3" => $na["nota_$indice_nota_3"] == null ? "" : $na["nota_$indice_nota_3"],
                "nota_$indice_nota_4" => $na["nota_$indice_nota_4"] == null ? "" : $na["nota_$indice_nota_4"],
                "nota_$indice_nota_5" => $na["nota_$indice_nota_5"] == null ? "" : $na["nota_$indice_nota_5"],
                "nota_$indice_nota_6" => $na["nota_$indice_nota_6"] == null ? "" : $na["nota_$indice_nota_6"],
                "nota_$indice_nota_7" => $na["nota_$indice_nota_7"] == null ? "" : $na["nota_$indice_nota_7"],
                "nota_$indice_nota_8" => $na["nota_$indice_nota_8"] == null ? "" : $na["nota_$indice_nota_8"],
                "promedio_competencia_$numero_competencia" => $na["promedio_competencia_$numero_competencia"] == null ? "" : $na["promedio_competencia_$numero_competencia"],
            );
        }
        echo json_encode($data);

    }

    /* Funcion para guardar notas del alumno */
    public function guardar_notas() {
        header('Content-Type: application/json');
        //Recibimos datos
        $id_nota_alumno_inicial = $this->input->post("id_nota_alumno_inicial");
        $nota_1 = $this->input->post("nota_1");
        $nota_2 = $this->input->post("nota_2");
        $nota_3 = $this->input->post("nota_3");
        $nota_4 = $this->input->post("nota_4");
        $nota_5 = $this->input->post("nota_5");
        $nota_6 = $this->input->post("nota_6");
        $nota_7 = $this->input->post("nota_7");
        $nota_8 = $this->input->post("nota_8");
        $name_promedio_competencia = $this->input->post("name_promedio_competencia");
        $promedio_competencia = $this->mantenimiento->promedio_competencia($nota_1[1], $nota_2[1], $nota_3[1], $nota_4[1], $nota_5[1], $nota_6[1], $nota_7[1], $nota_8[1]);
        //Preparamos data para actualizar nota del alumno
        $data_notas = array(
            $nota_1[0] => $nota_1[1] == "" ? null : $nota_1[1],
            $nota_2[0] => $nota_2[1] == "" ? null : $nota_2[1],
            $nota_3[0] => $nota_3[1] == "" ? null : $nota_3[1],
            $nota_4[0] => $nota_4[1] == "" ? null : $nota_4[1],
            $nota_5[0] => $nota_5[1] == "" ? null : $nota_5[1],
            $nota_6[0] => $nota_6[1] == "" ? null : $nota_6[1],
            $nota_7[0] => $nota_7[1] == "" ? null : $nota_7[1],
            $nota_8[0] => $nota_8[1] == "" ? null : $nota_8[1],
            $name_promedio_competencia => $promedio_competencia,
        );

        $resultSet = $this->m_nota_alumno_inicial->actualizar($data_notas, 'id_nota_alumno_inicial', $id_nota_alumno_inicial);
        if ($resultSet) {
            echo json_encode($promedio_competencia);
        }

    }

    /* Funcion para guardar indicador */
    public function guardar_indicador() {
        //Recibimos datos
        $id_indicador_competencia_curso_inicial = $this->input->post("id_indicador_competencia_curso_inicial");
        $indicador = $this->input->post("indicador");
        $name_indicador = $this->input->post("name_indicador");

        //Preparamos data para actualizar indicador
        $data_indicador = array(
            $name_indicador => $indicador,
        );

        $resultSet = $this->m_indicador_competencia_curso_inicial->actualizar($data_indicador, 'id_indicador_competencia_curso_inicial', $id_indicador_competencia_curso_inicial);
        if ($resultSet) {
            echo json_encode("Se guardo correctamente el indicador"); //Mensaje para mostrar por consola DEBUG
        }
    }

}
