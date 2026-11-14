<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

//error_reporting(0);

class Secundaria extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array("m_curso", "m_matricula", "m_alumno", "m_bimestre", "m_anio_escolar", "m_curso_docente", "m_nota_alumno_secundaria");
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

    /* Funcion para cargar conceptos y alumnos del nivel secundaria  */
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
        $this->smarty_tpl->view('internas/nota/registro_secundaria', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para obtener notas que pertenecen al concepto */
    public function obtener_notas_concepto() {
        header('Content-Type: application/json');
        //Recibimos datos
        $id_curso = $this->input->post("id_curso");
        $id_bimestre = $this->input->post("id_bimestre");
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_grado = $this->input->post("id_grado");
        $id_nivel_educativo = $this->input->post("id_nivel_educativo");
        $numero_concepto = $this->input->post("numero_concepto");

        //Obtenemos notas
        $notas_alumnos = $this->m_nota_alumno_secundaria->mostrar_cuando(array("nota_alumno_secundaria.id_bimestre" => $id_bimestre, "nota_alumno_secundaria.id_anio_escolar" => $id_anio_escolar, "nota_alumno_secundaria.id_curso" => $id_curso));

        echo json_encode($this->obtener_notas_promedios($numero_concepto, $notas_alumnos));
    }

    /* Funcion para guardar notas del alumno */
    public function guardar_notas() {
        header('Content-Type: application/json');

        //Recibimos datos
        $id_nota_alumno_secundaria = $this->input->post("id_nota_alumno_secundaria");
        $nota_1 = $this->input->post("nota_1");
        $nota_2 = $this->input->post("nota_2");
        $nota_3 = $this->input->post("nota_3");
        $nota_4 = $this->input->post("nota_4");
        $nota_5 = $this->input->post("nota_5");
        $nota_6 = $this->input->post("nota_6");
        $nota_7 = $this->input->post("nota_7");
        $nota_8 = $this->input->post("nota_8");
        $name_promedio_concepto = $this->input->post("name_promedio_concepto");
        $numero_concepto = $this->input->post("numero_concepto");

        switch ($numero_concepto) {
            case 1:
            case 2:
                $promedio_concepto = $this->mantenimiento->promedio_concepto($nota_1[1], $nota_2[1], $nota_3[1], $nota_4[1], $nota_5[1], $nota_6[1], $nota_7[1], $nota_8[1]);
                break;
            case 3:
                $promedio_concepto = $this->mantenimiento->promedio_concepto($nota_1[1], $nota_2[1], $nota_3[1], $nota_4[1]);
                break;
            case 4:
                $promedio_concepto = $this->mantenimiento->promedio_concepto($nota_1[1], $nota_2[1]);
                    break;
        }

        /*var_dump("IdNotaAlumnoSec: " . $id_nota_alumno_secundaria);
        var_dump("Nota1: " . $nota_1[1]);
        var_dump("Nota2: " . $nota_2[1]);
        var_dump("Nota3: " . $nota_3[1]);
        var_dump("Nota4: " . $nota_4[1]);
        var_dump("Nota5: " . $nota_5[1]);
        var_dump("Nota6: " . $nota_6[1]);
        var_dump("Nota7: " . $nota_7[1]);
        var_dump("Nota8: " . $nota_8[1]);
        var_dump("PromConcepto: " . $promedio_concepto);
        var_dump("NamePromConcepto:" . $name_promedio_concepto);
        var_dump("NumeroConcepto:" . $numero_concepto);*/
        //exit;
  

        //Evaluamos como se actualizara la data, segun el numero de concepto
        switch ($numero_concepto) {
        case 1:
        case 2:
            $data_notas = array(
                $nota_1[0] => $nota_1[1] == "" ? null : $nota_1[1],
                $nota_2[0] => $nota_2[1] == "" ? null : $nota_2[1],
                $nota_3[0] => $nota_3[1] == "" ? null : $nota_3[1],
                $nota_4[0] => $nota_4[1] == "" ? null : $nota_4[1],
                $nota_5[0] => $nota_5[1] == "" ? null : $nota_5[1],
                $nota_6[0] => $nota_6[1] == "" ? null : $nota_6[1],
                $nota_7[0] => $nota_7[1] == "" ? null : $nota_7[1],
                $nota_8[0] => $nota_8[1] == "" ? null : $nota_8[1],
                $name_promedio_concepto => $promedio_concepto,
            );
            break;
        case 3:
            $data_notas = array(
                $nota_1[0] => $nota_1[1] == "" ? null : $nota_1[1],
                $nota_2[0] => $nota_2[1] == "" ? null : $nota_2[1],
                $nota_3[0] => $nota_3[1] == "" ? null : $nota_3[1],
                $nota_4[0] => $nota_4[1] == "" ? null : $nota_4[1],
                "n3_promedio" => $promedio_concepto,
            );
            break;
        case 4:
            $data_notas = array(
                $nota_1[0] => $nota_1[1] == "" ? null : $nota_1[1],
                $nota_2[0] => $nota_2[1] == "" ? null : $nota_2[1],
            );
            break;
        }
        
        //Actualizamos notas
        $resultSet_nota = $this->m_nota_alumno_secundaria->actualizar($data_notas, 'id_nota_alumno_secundaria', $id_nota_alumno_secundaria);

        //Obtenemos Promedios para calcular Promedio Final
        //$notas_alumno = $this->m_nota_alumno_secundaria->mostrar(array("nota_alumno_secundaria.id_nota_alumno_secundaria" => $id_nota_alumno_secundaria));
        //var_dump($notas_alumno);
        //Calculamos promedio bimestral final
        //$promedio_final_bimestral = $this->mantenimiento->promedio_bimestral($notas_alumno["n1_promedio"], $notas_alumno["n2_promedio"], $notas_alumno["n3_promedio"], $notas_alumno["examen_mensual"], $notas_alumno["examen_bimestral"]); //Promedio Final
        //var_dump("PromFinalBimestral " . $promedio_final_bimestral);
        //exit;
        //Actualizamos promedio bimestral final
        //$resultSet_promedio_final = $this->actualiza_promedio_final($id_nota_alumno_secundaria, $promedio_final_bimestral);
        //$data = $promedio_concepto;

        $query = $this->db->query("CALL s_regulariza_promedios(?, ?)", array('2', $id_nota_alumno_secundaria));

        //if ($resultSet_nota && $resultSet_promedio_final) {
        if ($resultSet_nota) {
            //echo json_encode($promedio_concepto);
            echo json_encode('');
        }

    }

    /* ------------------------------------ FUNCIONES PRIVADAS ------------------------------------ */
    /* -------------------------------------------------------------------------------------------- */

    /* Armamos arreglo de notas y promedio de concepto */
    private function obtener_notas_promedios($numero_concepto, $notas_alumnos) {
        foreach ($notas_alumnos as $na) {
            switch ($numero_concepto) {
            case 1: // PARTICIPACION Y TAREAS -CONCEPTO 1
            case 2: //ASISTENCIA Y PRACTICAS DIARIAS -CONCEPTO 2

                //Data para la leyenda
                $data["leyenda"] = array(
                    "l1" => "S1 = Semana 1",
                    "l2" => "S2 = Semana 2",
                    "l3" => "S3 = Semana 3",
                    "l4" => "S4 = Semana 4",
                    "l5" => "S5 = Semana 5",
                    "l6" => "S6 = Semana 6",
                    "l7" => "S7 = Semana 7",
                    "l8" => "S8 = Semana 8",
                    "prom" => "Prom = Promedio",
                );

                //Data para el encabezado de las notas
                $data["encabezado"] = array(
                    "n1" => "S1",
                    "n2" => "S2",
                    "n3" => "S3",
                    "n4" => "S4",
                    "n5" => "S5",
                    "n6" => "S6",
                    "n7" => "S7",
                    "n8" => "S8",
                    "promedio" => "Prom",
                );

                //Data con las notas de los alumnos
                $data["notas_alumnos"][] = array(
                    "id_nota_alumno_secundaria" => $na["id_nota_alumno_secundaria"],
                    "n{$numero_concepto}_s1" => $na["n{$numero_concepto}_s1"] == null ? "" : $na["n{$numero_concepto}_s1"],
                    "n{$numero_concepto}_s2" => $na["n{$numero_concepto}_s2"] == null ? "" : $na["n{$numero_concepto}_s2"],
                    "n{$numero_concepto}_s3" => $na["n{$numero_concepto}_s3"] == null ? "" : $na["n{$numero_concepto}_s3"],
                    "n{$numero_concepto}_s4" => $na["n{$numero_concepto}_s4"] == null ? "" : $na["n{$numero_concepto}_s4"],
                    "n{$numero_concepto}_s5" => $na["n{$numero_concepto}_s5"] == null ? "" : $na["n{$numero_concepto}_s5"],
                    "n{$numero_concepto}_s6" => $na["n{$numero_concepto}_s6"] == null ? "" : $na["n{$numero_concepto}_s6"],
                    "n{$numero_concepto}_s7" => $na["n{$numero_concepto}_s7"] == null ? "" : $na["n{$numero_concepto}_s7"],
                    "n{$numero_concepto}_s8" => $na["n{$numero_concepto}_s8"] == null ? "" : $na["n{$numero_concepto}_s8"],
                    "n{$numero_concepto}_promedio" => $na["n{$numero_concepto}_promedio"] == null ? "" : $na["n{$numero_concepto}_promedio"],
                );
                break;
            case 3: //REVISION DE CUADERNO Y CONDUCTA - CONCEPTO 3

                //Data para la leyenda
                $data["leyenda"] = array(
                    "l1" => "RC1 = Revisión de Cuaderno 1",
                    "l2" => "CO1 = Conducta 1",
                    "l3" => "RC2 = Revisión de Cuaderno 2",
                    "l4" => "CO2 = Conducta 2",
                    "prom" => "Prom = Promedio",
                );

                //Data para el encabezado de las notas
                $data["encabezado"] = array(
                    "n1" => "RC1",
                    "n2" => "CO1",
                    "n3" => "RC2",
                    "n4" => "CO2",
                    "promedio" => "Prom",
                );
                //Data con las notas de los alumnos
                $data["notas_alumnos"][] = array(
                    "id_nota_alumno_secundaria" => $na["id_nota_alumno_secundaria"],
                    "n{$numero_concepto}_rc1" => $na["n{$numero_concepto}_rc1"] == null ? "" : $na["n{$numero_concepto}_rc1"],
                    "n{$numero_concepto}_co1" => $na["n{$numero_concepto}_co1"] == null ? "" : $na["n{$numero_concepto}_co1"],
                    "n{$numero_concepto}_rc2" => $na["n{$numero_concepto}_rc2"] == null ? "" : $na["n{$numero_concepto}_rc2"],
                    "n{$numero_concepto}_co2" => $na["n{$numero_concepto}_co2"] == null ? "" : $na["n{$numero_concepto}_co2"],
                    "n{$numero_concepto}_promedio" => $na["n{$numero_concepto}_promedio"] == null ? "" : $na["n{$numero_concepto}_promedio"],
                );
                break;
            case 4: // EXAMEN MENSUAL, BIMESTRAL Y PROMEDIO FINAL - CONCEPTO 4
                //Data para el encabezado de las notas
                $data["leyenda"] = array(
                    "l1" => "E.M. = Exámen Mensual",
                    "l2" => "E.F. = Exámen Bimestral",
                    "prom" => "P.F. = Promedio Final",
                );

                //Data para el encabezado de las notas
                $data["encabezado"] = array(
                    "n1" => "E.M.",
                    "n2" => "E.B.",
                    "n3" => "P.F.",
                );
                //Data con las notas de los alumnos
                $data["notas_alumnos"][] = array(
                    "id_nota_alumno_secundaria" => $na["id_nota_alumno_secundaria"],
                    "examen_mensual" => $na["examen_mensual"] == null ? "" : $na["examen_mensual"],
                    "examen_bimestral" => $na["examen_bimestral"] == null ? "" : $na["examen_bimestral"],
                    "promedio_final" => $na["promedio_final"] == null ? "" : $na["promedio_final"],
                );
                break;
            }
        }
        return $data;
    }

    /* Funcion para actualizar promedios  */
    private function actualiza_promedio_final($id_nota_alumno_secundaria, $promedio_final_bimestral) {
        $data_notas = array(
            "promedio_final" => $promedio_final_bimestral, //Promedio Bimestral Final
        );
        return $this->m_nota_alumno_secundaria->actualizar($data_notas, 'id_nota_alumno_secundaria', $id_nota_alumno_secundaria);
    }

}
