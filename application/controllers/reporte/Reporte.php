<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");

require_once 'application/third_party/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

//error_reporting(0);

class Reporte extends CI_Controller {

    private $_result;
    private $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo', "pdf", "excel");
        $helper = array('url');
        $model = array(
            "m_alumno",
            "m_usuario",
            "m_mes_escolar",
            "m_anio_escolar",
            "m_matricula",
            "m_asistencia_alumno",
            "m_grado",
            "m_nota_alumno_secundaria",
            "m_curso",
            "m_comportamiento",
            "m_nota_alumno_primaria",
            "m_bimestre",
            "m_mensaje_matricula"
        );
        $this->load->library($library);
        $this->load->helper($helper);
        $this->load->model($model);
        /*
         * Configuración personalizada
         */
        $this->config->load('serinfo', true, true);
        $this->_session = $this->session_sistema->datos_usuario('user_data');

        $this->items['proyecto'] = $this->config->item('nombre_proyecto', 'serinfo');
        $this->items['favicon'] = $this->config->item('url_favicon', 'serinfo');
        $this->items['base_url'] = base_url();
        $this->items['usuario_activo'] = 'active';
    }

    /* Funcion para ver el formulario de reportes - Modo Alumno */
    public function ver() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = "Mis Reportes | {$this->items['proyecto']}";
        $data['titulo_modulo'] = "Mis Reportes";
        /* ------------------------------------------------------------ */

        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7 || $login['usuario_privilegio'] == 3) { //Si tiene privilegios de administrador, alumno

            $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $login["usuario_id"]));
            $data["estado_usuario"] = $usuario["estado"];

            $meses_escolares = $this->m_mes_escolar->mostrar_activos();
            //Carga combo de meses escolares
            foreach ($meses_escolares as $me) {
                $this->_result[$me['id_mes_escolar']] = $me['nombre'];
            }
            $data['combo_meses_escolares'] = $this->documento->generar_dropdown($this->_result, 'id_mes_escolar', '', array());
            unset($this->_result);

            //Carga combo de anios escolares
            $anios_escolares = $this->m_anio_escolar->mostrar_activos();
            foreach ($anios_escolares as $ae) {
                $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
            }
            $data['combo_anios_escolares'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', '', array());
            unset($this->_result);

            //Carga combo de bimestres
            $bimestres = $this->m_bimestre->mostrar_activos();
            foreach ($bimestres as $bi) {
                $this->_result[$bi['id_bimestre']] = $bi['nombre'];
            }
            $data['combo_bimestres'] = $this->documento->generar_dropdown($this->_result, 'id_bimestre', '', array());
            unset($this->_result);
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', true);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/reporte/ver', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para ver el formulario de reportes - Modo Administrador */
    public function ver_admin() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = "Reportes | {$this->items['proyecto']}";
        $data['titulo_modulo'] = "Reportes";
        /* ------------------------------------------------------------ */

        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            $meses_escolares = $this->m_mes_escolar->mostrar_activos();

            //Carga combo de meses escolares
            foreach ($meses_escolares as $me) {
                $this->_result[$me['id_mes_escolar']] = $me['nombre'];
            }
            $data['combo_meses_escolares'] = $this->documento->generar_dropdown($this->_result, 'id_mes_escolar', '', array());
            unset($this->_result);

            //Carga combo de anios escolares
            $anios_escolares = $this->m_anio_escolar->mostrar_activos();
            foreach ($anios_escolares as $ae) {
                $this->_result[$ae['id_anio_escolar']] = $ae['nombre'];
            }
            $data['combo_anios_escolares'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar', '', array());
            $data['combo_anio_escolar_nota'] = $this->documento->generar_dropdown($this->_result, 'id_anio_escolar_nota', '', array());
            unset($this->_result);

            //Carga combo de bimestres
            $bimestres = $this->m_bimestre->mostrar_activos();
            foreach ($bimestres as $bi) {
                $this->_result[$bi['id_bimestre']] = $bi['nombre'];
            }
            $data['combo_bimestres'] = $this->documento->generar_dropdown($this->_result, 'id_bimestre', '', array());
            unset($this->_result);

            //Carga combo de bimestres
            $grados = $this->m_grado->mostrar_activos();

            foreach ($grados as $gr) {
                $this->_result[$gr['id_grado']] = "{$gr['nombre']} {$gr['nombre_nivel_educativo']}";
            }
            $data['combo_grados'] = $this->documento->generar_dropdown($this->_result, 'id_grado', '', array());
            unset($this->_result);
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', true);
            exit;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/reporte/ver_admin', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para obtener reporte de ASISTENCIA - Modo Alumno */
    public function reporte_asistencia() {
        $login = $this->session_sistema->datos_usuario_logueado();

        $id_mes_escolar = $this->input->post("id_mes_escolar");
        $id_anio_escolar = $this->input->post("id_anio_escolar");

        $alumno = $this->m_alumno->mostrar(array("alumno.id_usuario" => $login["usuario_id"]));
        $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $alumno["id_usuario"]));
        $matricula = $this->m_matricula->mostrar(array("matricula.id_anio_escolar" => $id_anio_escolar, "matricula.id_alumno" => $alumno["id_alumno"]));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $matricula["id_grado"]));
        $mes_escolar = $this->m_mes_escolar->mostrar(array("mes_escolar.id_mes_escolar" => $id_mes_escolar));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $asistencia_alumno = $this->m_asistencia_alumno->mostrar(array(
                                                                     "asistencia_alumno.id_matricula"   => $matricula["id_matricula"],
                                                                     "asistencia_alumno.id_mes_escolar" => $id_mes_escolar
                                                                 )
        );
        $data["grado"] = $grado["nombre"];
        $data["nivel_educativo"] = $grado["nombre_nivel_educativo"];
        $data["nombre_alumno"] = "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}";
        $data["mes"] = $mes_escolar["nombre"];
        $data["usuario"] = $usuario["usuario"];
        $data["fecha_hora"] = date("Y-m-d H:i:s");

        $data["resumen"] = $this->obtener_resumen($asistencia_alumno);

        if ($id_mes_escolar == '4' || $id_mes_escolar == '6' || $id_mes_escolar == '9' || $id_mes_escolar == '11') { //MESES CON 30 DIAS
            $total_dias = 30;
        } else { //MESES CON 31 DIAS
            $total_dias = 31;
        }

        for ($f = 1; $f <= $total_dias; $f++) {
            $data['cantidad_dias'] = $total_dias;
            $nombre_dia = $this->dias[date('N', strtotime($anio_escolar['nombre'] . '-' . $id_mes_escolar . '-' . $f))];
            if (($nombre_dia != "Domingo" && $nombre_dia != "Sábado")) {
                $data["lista_dias"][] = array(
                    "nombre" => $nombre_dia,
                    "numero" => $f,
                    "valor"  => $asistencia_alumno["dia_$f"] == "NA" ? "-" : $asistencia_alumno["dia_$f"],
                );
            }
        }

        $html = $this->smarty_tpl->view('internas/reporte/asistencia_pdf', $data, true);
        $nombre_pdf = "ReporteAsistencia_{$mes_escolar["nombre"]}_{$usuario["usuario"]}";
        $contenido = $this->pdf->generar_pdf("A4", "landscape", $html, $nombre_pdf);
    }

    /* Funcion para obtener reporte de ASISTENCIA - Modo Administrador */
    public function reporte_asistencia_admin() {
        $login = $this->session_sistema->datos_usuario_logueado();

        //Recibimos datos
        $id_mes_escolar = $this->input->post("id_mes_escolar");
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $radio_exportar_por = $this->input->post("radio_exportar_por");

        //Obtenemos datos necesarios
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $mes_escolar = $this->m_mes_escolar->mostrar(array("mes_escolar.id_mes_escolar" => $id_mes_escolar));
        $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $login["usuario_id"]));

        //Evaluamos si es reporte por alumno o grado
        switch ($radio_exportar_por) {
            case "alumno":
                $id_matricula = $this->input->post("id_matricula"); //Alumno matriculado
                $asistencia_alumno = $this->m_asistencia_alumno->mostrar(array(
                                                                             "asistencia_alumno.id_matricula"   => $id_matricula,
                                                                             "asistencia_alumno.id_mes_escolar" => $id_mes_escolar
                                                                         )
                );
                $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $id_matricula));
                $grado = $this->m_grado->mostrar(array("grado.id_grado" => $matricula["id_grado"]));

                $data["grado"] = $grado["nombre"];
                $data["nivel_educativo"] = $grado["nombre_nivel_educativo"];
                $data["mes"] = $mes_escolar["nombre"];
                $data["usuario"] = $usuario["usuario"];
                $data["fecha_hora"] = date("Y-m-d H:i:s");
                $data["nombre_alumno"] = "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}";
                $data["lista_dias"] = $this->procesa_asistencia($id_mes_escolar, $anio_escolar["nombre"], $asistencia_alumno);
                $data["resumen"] = $this->obtener_resumen($asistencia_alumno);

                $html = $this->smarty_tpl->view('internas/reporte/asistencia_pdf_admin', $data, true);
                $nombre_pdf = "ReporteAsistencia_{$mes_escolar["nombre"]}_{$usuario["usuario"]}";
                $contenido = $this->pdf->generar_pdf("A4", "landscape", $html, $nombre_pdf);
                break;

            case "grado":
                $id_grado = $this->input->post("id_grado"); //Grado seleccionado
                $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
                $data["grado"] = $grado["nombre"];
                $data["nivel_educativo"] = $grado["nombre_nivel_educativo"];
                $data["mes"] = $mes_escolar["nombre"];
                $data["usuario"] = $usuario["usuario"];
                $data["fecha_hora"] = date("Y-m-d H:i:s");
                //Data de asistencias de los alumnos segun año escolar, mes escolar y grado
                $asistencia_alumnos = $this->m_asistencia_alumno->mostrar_cuando(array(
                                                                                     "asistencia_alumno.id_grado"        => $id_grado,
                                                                                     "asistencia_alumno.id_mes_escolar"  => $id_mes_escolar,
                                                                                     "asistencia_alumno.id_anio_escolar" => $id_anio_escolar
                                                                                 )
                );
                $data["dias"] = $this->procesa_dias_grado($id_mes_escolar, $anio_escolar["nombre"]);
                $i = 1;
                foreach ($asistencia_alumnos as $al) {
                    $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $al["id_matricula"]));
                    $data["data"][] = array(
                        "numero"     => $i,
                        "alumno"     => "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}",
                        "asistencia" => $this->procesa_asistencia_grado($id_mes_escolar, $anio_escolar["nombre"], $al),
                    );
                    $i++;
                }
                //var_dump($data);exit;
                $html = $this->smarty_tpl->view('internas/reporte/asistencia_grado_pdf_admin', $data, true);
                $nombre_pdf = "ReporteAsistencia_{$mes_escolar["nombre"]}_{$usuario["usuario"]}";
                $contenido = $this->pdf->generar_pdf("A4", "landscape", $html, $nombre_pdf);
                break;
        }
    }

    /* Funcion para obtener combo filtrado */
    public function filtro() {
        header('Content-Type: application/json');

        //Recibimos datos
        $exportar_por = $this->input->post("exportar_por");
        $id_anio_escolar = $this->input->post("id_anio_escolar");

        switch ($exportar_por) {
            case 'alumno': //Retornamos combo de matriculados
                $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_anio_escolar" => $id_anio_escolar));

                foreach ($matriculados as $items) {
                    $this->_result[$items["id_matricula"]] = "{$items["apellido_paterno"]} {$items["apellido_materno"]}, {$items["nombre"]} - {$items["dni"]}";
                }
                $data = $this->documento->generar_dropdown($this->_result, 'id_matricula', "", array(), "search");
                break;

            case "grado": //Retornamos combo de grados
                $grados = $this->m_grado->mostrar_activos();
                foreach ($grados as $items) {
                    $this->_result[$items["id_grado"]] = "{$items["nombre"]} - {$items["nombre_nivel_educativo"]}";
                }
                $data = $this->documento->generar_dropdown($this->_result, 'id_grado', "", array(), "search");
                break;
        }
        unset($this->_result);
        echo json_encode($data);
    }

    /* Funcion para obtener combo filtrado */
    public function filtro_reporte_notas() {
        header('Content-Type: application/json');

        //Recibimos datos
        $exportar_por = $this->input->post("exportar_por");
        $id_anio_escolar = $this->input->post("id_anio_escolar");

        switch ($exportar_por) {
            case 'alumno': //Retornamos combo de matriculados
                $matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_anio_escolar" => $id_anio_escolar));
                //print_r($matriculados); exit;
                foreach ($matriculados as $items) {
                    $this->_result[$items["id_matricula"]] = "{$items["apellido_paterno"]} {$items["apellido_materno"]}, {$items["nombre"]} - {$items["dni"]}";
                }
                $data = $this->documento->generar_dropdown($this->_result, 'id_matricula', "", array(), "search");
                break;

            case "grado": //Retornamos combo de grados
                $grados = $this->m_grado->mostrar_activos();
                foreach ($grados as $items) {
                    $this->_result[$items["id_grado"]] = "{$items["nombre"]} - {$items["nombre_nivel_educativo"]}";
                }
                $data = $this->documento->generar_dropdown($this->_result, 'id_grado', "", array(), "search");
                break;
        }
        unset($this->_result);
        echo json_encode($data);
    }

    /* Funcion para obtener el reporte de notas - Modo Alumno */
    public function reporte_notas() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_bimestre = $this->input->post("id_bimestre");

        $alumno = $this->m_alumno->mostrar(array("alumno.id_usuario" => $login["usuario_id"]));
        $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $alumno["id_usuario"]));
        $matricula = $this->m_matricula->mostrar(array("matricula.id_anio_escolar" => $id_anio_escolar, "matricula.id_alumno" => $alumno["id_alumno"]));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $matricula["id_grado"]));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $id_nivel_educativo = $matricula["id_nivel_educativo"];
        switch ($id_nivel_educativo) {
            case 1: //INICIAL
                //PENDIENTE!!
                break;

            case 2: //PRIMARIA
            case 3: //SECUNDARIA

                $cursos = $this->m_curso->mostrar_cuando(array("curso.id_grado" => $matricula["id_grado"], "curso.estado" => 0, "curso.id_anio_escolar" => $id_anio_escolar));
                $data["alumno"] = "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}";
                $data["grado"] = "{$grado["nombre"]} de {$grado["nombre_nivel_educativo"]}";
                $data["anio_escolar"] = $anio_escolar["nombre"];
                $data["id_bimestre"] = $id_bimestre;

                $i = 1;
                foreach ($cursos as $c) {
                    if ($matricula["id_nivel_educativo"] == 2) { //PRIMARIA
                        $data_notas_alumnos = $this->m_nota_alumno_primaria->mostrar_cuando(array(
                                                                                                "nota_alumno_primaria.id_matricula" => $matricula["id_matricula"],
                                                                                                "nota_alumno_primaria.id_curso"     => $c["id_curso"]
                                                                                            )
                        );
                    } else { //SECUNDARIA
                        $data_notas_alumnos = $this->m_nota_alumno_secundaria->mostrar_cuando(array(
                                                                                                  "nota_alumno_secundaria.id_matricula" => $matricula["id_matricula"],
                                                                                                  "nota_alumno_secundaria.id_curso"     => $c["id_curso"]
                                                                                              )
                        );
                    }
                    $data["notas"][] = array(
                        "numero"              => $i,
                        "curso"               => $c["nombre"],
                        "promedios_bimestres" => $this->procesa_notas($data_notas_alumnos),
                    );
                    $i++;
                }

                $data_notas_comportamiento = $this->m_comportamiento->mostrar_cuando(array("comportamiento.id_matricula" => $matricula["id_matricula"]));
                $data["promedios_comportamiento"] = $this->procesa_comportamiento($data_notas_comportamiento);

                $data_asistencia = $this->m_asistencia_alumno->mostrar_cuando(array("asistencia_alumno.id_matricula" => $matricula["id_matricula"]));
                $data["asistencia_alumno"] = $this->procesa_asistencia_pdf($data_asistencia);

                $data2["lista_alumnos"][] = $data; //Guardamos dentro de un array para almacenar cada iteracion
                $data["notas"] = array();          //Seteamos vacio a las notas

                switch ($id_nivel_educativo) {
                    case 1:
                        //$html = $this->smarty_tpl->view('internas/reporte/boleta_notas_primaria_pdf', $data, TRUE);
                        break;
                    case 2:
                        //$html = $this->smarty_tpl->view('internas/reporte/primaria/boleta_notas_primaria_pdf', $data, TRUE);
                        //$html = $this->smarty_tpl->view('internas/reporte/primaria/boleta_notas_primaria_pdf_admin_v2', $data, true);
                        $html = $this->smarty_tpl->view('internas/reporte/primaria/boleta_notas_primaria_pdf_admin_masivo_v2', $data2, true);
                        break;
                    case 3:
                        //$html = $this->smarty_tpl->view('internas/reporte/secundaria/boleta_notas_secundaria_pdf', $data, TRUE);
                        //$html = $this->smarty_tpl->view('internas/reporte/secundaria/boleta_notas_secundaria_pdf_admin_v2', $data, true);
                        $html = $this->smarty_tpl->view('internas/reporte/secundaria/boleta_notas_secundaria_pdf_admin_masivo_v2', $data2, true);
                        break;
                }

                $nombre_pdf = "BoletaNotas_{$usuario["usuario"]}";
                $contenido = $this->pdf->generar_pdf("A4", "portrait", $html, $nombre_pdf);
                break;
        }
    }

    /* Funcion para obtener el reporte de notas - Modo Administrador */
    public function reporte_notas_admin() {
        $this->session_sistema->datos_usuario_logueado();

        $id_anio_escolar = $this->input->post("id_anio_escolar_nota");
        $id_bimestre = $this->input->post("id_bimestre");
        $radio_notas_exportar_por = $this->input->post("radio_notas_exportar_por");
        $id_matricula = $this->input->post("id_matricula");
        $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $id_matricula));
        $alumno = $this->m_alumno->mostrar(array("alumno.id_alumno" => $matricula["id_alumno"]));
        $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $alumno["id_usuario"]));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $matricula["id_grado"]));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));
        $id_nivel_educativo = $matricula["id_nivel_educativo"];

        switch ($radio_notas_exportar_por) {
            case "alumno":
                switch ($id_nivel_educativo) {
                    case 1: //INICIAL
                        //PENDIENTE!!
                        break;

                    case 2: //PRIMARIA
                    case 3: //SECUNDARIA

                        $cursos = $this->m_curso->mostrar_cuando(array(
                                                                     "curso.id_grado"        => $matricula["id_grado"],
                                                                     "curso.estado"          => 0,
                                                                     "curso.id_anio_escolar" => $id_anio_escolar
                                                                 )
                        );
                        $data["alumno"] = "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}";
                        $data["grado"] = "{$grado["nombre"]} de {$grado["nombre_nivel_educativo"]}";
                        $data["anio_escolar"] = $anio_escolar["nombre"];
                        $data["id_bimestre"] = $id_bimestre;
                        $i = 1;
                        foreach ($cursos as $c) {
                            if ($matricula["id_nivel_educativo"] == 2) { //PRIMARIA

                                $data_notas_alumnos = $this->m_nota_alumno_primaria->mostrar_cuando(array(
                                                                                                        "nota_alumno_primaria.id_matricula" => $matricula["id_matricula"],
                                                                                                        "nota_alumno_primaria.id_curso"     => $c["id_curso"]
                                                                                                    )
                                );
                            } else { //SECUNDARIA
                                $data_notas_alumnos = $this->m_nota_alumno_secundaria->mostrar_cuando(array(
                                                                                                          "nota_alumno_secundaria.id_matricula" => $matricula["id_matricula"],
                                                                                                          "nota_alumno_secundaria.id_curso"     => $c["id_curso"]
                                                                                                      )
                                );
                            }

                            $data["notas"][] = array(
                                "numero"              => $i,
                                "curso"               => $c["nombre"],
                                "promedios_bimestres" => $this->procesa_notas($data_notas_alumnos),
                            );
                            $i++;
                        }

                        $data_notas_comportamiento = $this->m_comportamiento->mostrar_cuando(array("comportamiento.id_matricula" => $matricula["id_matricula"]));
                        $data["promedios_comportamiento"] = $this->procesa_comportamiento($data_notas_comportamiento);

                        $data_asistencia = $this->m_asistencia_alumno->mostrar_cuando(array("asistencia_alumno.id_matricula" => $matricula["id_matricula"]));
                        $data["asistencia_alumno"] = $this->procesa_asistencia_pdf($data_asistencia);

                        $data2["lista_alumnos"][] = $data; //Guardamos dentro de un array para almacenar cada iteracion

                        switch ($id_nivel_educativo) {
                            case 1:
                                //$html = $this->smarty_tpl->view('internas/reporte/boleta_notas_primaria_pdf', $data, TRUE);
                                break;
                            case 2:
                                //$html = $this->smarty_tpl->view('internas/reporte/primaria/boleta_notas_primaria_pdf_admin_v2', $data, true);
                                $html = $this->smarty_tpl->view('internas/reporte/primaria/boleta_notas_primaria_pdf_admin_masivo_v2', $data2, true);
                                break;
                            case 3:
                                //$html = $this->smarty_tpl->view('internas/reporte/secundaria/boleta_notas_secundaria_pdf_admin_v2', $data, true);
                                $html = $this->smarty_tpl->view('internas/reporte/secundaria/boleta_notas_secundaria_pdf_admin_masivo_v2', $data2, true);
                                break;
                        }
                        $nombre_pdf = "BoletaNotas_{$usuario["usuario"]}";
                        $contenido = $this->pdf->generar_pdf("A4", "portrait", $html, $nombre_pdf);
                        break;
                }
                break;

            case "grado":
                $id_grado = $this->input->post("id_grado");
                $lista_matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $id_grado, "matricula.id_anio_escolar" => $id_anio_escolar));

                $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));

                foreach ($lista_matriculados as $lm) {
                    switch ($lm["id_nivel_educativo"]) {
                        case 1: //INICIAL
                            //PENDIENTE!!
                            break;

                        case 2: //PRIMARIA
                        case 3: //SECUNDARIA

                            $cursos = $this->m_curso->mostrar_cuando(array(
                                                                         "curso.id_grado"        => $lm["id_grado"],
                                                                         "curso.estado"          => 0,
                                                                         "curso.id_anio_escolar" => $lm["id_anio_escolar"]
                                                                     )
                            );

                            $data["alumno"] = "{$lm["apellido_paterno"]} {$lm["apellido_materno"]}, {$lm["nombre"]}";
                            $data["grado"] = "{$grado["nombre"]} de {$grado["nombre_nivel_educativo"]}";
                            $data["anio_escolar"] = $anio_escolar["nombre"];
                            $data["id_bimestre"] = $id_bimestre;
                            $data_notas_comportamiento = $this->m_comportamiento->mostrar_cuando(array("comportamiento.id_matricula" => $lm["id_matricula"]));
                            $data["promedios_comportamiento"] = $this->procesa_comportamiento($data_notas_comportamiento);

                            $data_asistencia = $this->m_asistencia_alumno->mostrar_cuando(array("asistencia_alumno.id_matricula" => $lm["id_matricula"]));
                            $data["asistencia_alumno"] = $this->procesa_asistencia_pdf($data_asistencia);

                            $i = 1;
                            foreach ($cursos as $c) {
                                if ($lm["id_nivel_educativo"] == 2) { //PRIMARIA
                                    $data_notas_alumnos = $this->m_nota_alumno_primaria->mostrar_cuando(array(
                                                                                                            "nota_alumno_primaria.id_matricula" => $lm["id_matricula"],
                                                                                                            "nota_alumno_primaria.id_curso"     => $c["id_curso"]
                                                                                                        )
                                    );
                                } else { //SECUNDARIA
                                    $data_notas_alumnos = $this->m_nota_alumno_secundaria->mostrar_cuando(array(
                                                                                                              "nota_alumno_secundaria.id_matricula" => $lm["id_matricula"],
                                                                                                              "nota_alumno_secundaria.id_curso"     => $c["id_curso"]
                                                                                                          )
                                    );
                                }

                                $data["notas"][] = array(
                                    "numero"              => $i,
                                    "curso"               => $c["nombre"],
                                    "promedios_bimestres" => $this->procesa_notas($data_notas_alumnos),
                                );
                                $i++;
                            }
                            break;
                    }
                    $data2["lista_alumnos"][] = $data; //Guardamos dentro de un array para almacenar cada iteracion
                    $data["notas"] = array();          //Seteamos vacio a las notas
                }

                $data2["total_elementos"] = count($data2["lista_alumnos"]);

                switch ($grado["id_nivel_educativo"]) {
                    case 1:
                        //$html = $this->smarty_tpl->view('internas/reporte/boleta_notas_primaria_pdf', $data, TRUE);
                        break;
                    case 2:
                        $html = $this->smarty_tpl->view('internas/reporte/primaria/boleta_notas_primaria_pdf_admin_masivo_v2', $data2, true);
                        break;
                    case 3:
                        $html = $this->smarty_tpl->view('internas/reporte/secundaria/boleta_notas_secundaria_pdf_admin_masivo_v2', $data2, true);
                        break;
                }
                $nombre_pdf = "BoletaNotas_{$usuario["usuario"]}";
                $this->pdf->generar_pdf("A4", "portrait", $html, $nombre_pdf);
                break;
        }
    }

    /* Funcion para obtener el reporte de mensajes segun anio escolar y grado */
    public function reporte_mensajes_admin() {
        $login = $this->session_sistema->datos_usuario_logueado();

        //Recibimos datos
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_grado = $this->input->post("id_grado");
        $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $login["usuario_id"]));
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        $data2["grado"] = "{$grado["nombre"]} de {$grado["nombre_nivel_educativo"]}";
        $data2["usuario"] = $usuario["usuario"];
        $data2["fecha_hora"] = date("Y-m-d H:i:s");

        $query = "SELECT men.asunto, mm.id_mensaje, m.id_grado FROM mensaje_matricula mm
                    INNER JOIN matricula m
                        ON mm.id_matricula = m.id_matricula
                    INNER JOIN mensaje men
                        ON mm.id_mensaje = men.id_mensaje
                    WHERE m.id_grado = {$id_grado} AND m.id_anio_escolar = {$id_anio_escolar}
                    GROUP BY id_mensaje;";

        $mensajes_grado = $this->m_mensaje_matricula->ejecuta_query($query);

        foreach ($mensajes_grado as $mg) {
            //Obtenemos los destinatarios segun id_mensaje
            $lista_mensajes_matricula = $this->m_mensaje_matricula->mostrar_cuando(array("mensaje_matricula.id_mensaje" => $mg["id_mensaje"]));
            //Declaramos arrays vacios
            $data["leidos"] = array();
            $data["no_leidos"] = array();
            //Recorremos toda la lista
            $data["asunto"] = $mg["asunto"];
            foreach ($lista_mensajes_matricula as $lm) {
                $matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $lm["id_matricula"]));

                if ($matricula["id_grado"] == $id_grado) {
                    if ($lm["leido"] == 1) { //Leido
                        $data["leidos"][] = array(
                            "alumno"      => "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}",
                            "fecha_leido" => $lm["fecha_leido"],
                        );
                    } else { // 0 -> No leido
                        $data["no_leidos"][] = array(
                            "alumno" => "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}",
                        );
                    }
                }
            }
            //Ordenamos arrays
            $data["leidos"] = $this->mantenimiento->ordenaArrayMultidimensional($data["leidos"], "fecha_leido", true);
            $data["no_leidos"] = $this->mantenimiento->ordenaArrayMultidimensional($data["no_leidos"], "alumno", false);
            $data2["mensaje"][] = $data;
            $data["leidos"] = array();
            $data["no_leidos"] = array();
        }
        $html = $this->smarty_tpl->view('internas/reporte/mensaje_grado_pdf_admin', $data2, true);
        $nombre_pdf = "ReporteMensaje_ {$grado["nombre"]}_{$grado["nombre_nivel_educativo"]}";
        $contenido = $this->pdf->generar_pdf("A4", "portrait", $html, $nombre_pdf);
    }

    /* Funcion para obtener los alumnos matriculados segun el año escolar y el grado y exportarlo a excel */
    public function reporte_matriculados_admin() {
        $login = $this->session_sistema->datos_usuario_logueado();
        //Recibimos datos
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_grado = $this->input->post("id_grado");
        $usuario = $this->m_usuario->mostrar(array("usuario.id_usuario" => $login["usuario_id"]));

        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));

        //Obtenemos los matriculados
        $lista_matriculados = $this->m_matricula->mostrar_cuando(array("matricula.id_grado" => $id_grado, "matricula.id_anio_escolar" => $id_anio_escolar));
        $i = 1;
        $n = 9;

        //Seteamos
        $this->excel->setActiveSheetIndex(0);                //Ponemos la Primera hoja activa
        $this->excel->getActiveSheet()->setTitle('Reporte'); //Nombre de la Hoja Excel
        $this->excel->getActiveSheet()->setCellValue('A1', "Intranet - I.E.P. René Descartes");
        $this->excel->getActiveSheet()->mergeCells('A1:B1');
        $this->excel->getActiveSheet()->mergeCells('A2:B2');
        $this->excel->getActiveSheet()->setCellValue('A2', $usuario["usuario"]);
        $this->excel->getActiveSheet()->setCellValue('D1', "Fecha: " . date("Y-m-d H:i:s"));
        $this->excel->getActiveSheet()->mergeCells('A4:D4');
        $this->excel->getActiveSheet()->setCellValue('A4', "REPORTE DE MATRICULADOS {$anio_escolar["nombre"]}");
        $this->excel->getActiveSheet()->mergeCells('A5:D5');
        $this->excel->getActiveSheet()->setCellValue('A5', "{$grado["nombre"]} de {$grado["nombre_nivel_educativo"]}");
        $this->excel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->setCellValue('A8', "N°");
        $this->excel->getActiveSheet()->setCellValue('B8', "APELLIDOS Y NOMBRES");
        $this->excel->getActiveSheet()->setCellValue('C8', "DNI");
        $this->excel->getActiveSheet()->setCellValue('D8', "FECHA DE MATRÍCULA");
        $this->excel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
        $this->excel->getActiveSheet()->getStyle("A4:A5")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle("A8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle("B8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("C")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("D")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $this->excel->getActiveSheet()->getStyle("A1:D8")->getFont()->setBold(true);
        foreach ($lista_matriculados as $lm) {
            $this->excel->getActiveSheet()->setCellValue("A{$n}", $i);
            $this->excel->getActiveSheet()->setCellValue("B{$n}", "{$lm["apellido_paterno"]} {$lm["apellido_materno"]}, {$lm["nombre"]}");
            $this->excel->getActiveSheet()->setCellValue("C{$n}", $lm["dni"]);
            $this->excel->getActiveSheet()->setCellValue("D{$n}", $lm["fecha_matricula"]);
            $this->excel->getActiveSheet()->getStyle("A{$n}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $i++;
            $n++;
        }

        $nombre_pdf = "ReporteMensaje_ {$grado["nombre"]}_{$grado["nombre_nivel_educativo"]}";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename={$nombre_pdf}.xls");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }

    public function consolidado() {
        $this->session_sistema->datos_usuario_logueado();

        //Recibimos datos
        $id_anio_escolar = $this->input->post("id_anio_escolar");
        $id_grado = $this->input->post("id_grado");

        //Obtenemos grado
        $grado = $this->m_grado->mostrar(array("grado.id_grado" => $id_grado));
        //Obtenemos año escolar
        $anio_escolar = $this->m_anio_escolar->mostrar(array("anio_escolar.id_anio_escolar" => $id_anio_escolar));

        $id_nivel_educativo = $grado["id_nivel_educativo"];

        $fecha_hoy = date("Y-m-d");

        $spreadsheet = new Spreadsheet();
        $color_fondo_pf = "E6B8B7";
        $color_borde_celdas = "000000";
        $spreadsheet->getProperties()
            ->setTitle("Reporte Consolidado $fecha_hoy}")
            ->setSubject("Reporte Consolidado $fecha_hoy}");
        //Armamos titulo
        $titulo = strtoupper("CONSOLIDADO ANUAL - {$grado["nombre"]} de {$grado["nombre_nivel_educativo"]} {$anio_escolar["nombre"]}");

        //Adding data to the excel sheet
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('A9:A10')
            ->mergeCells('B9:B10')
            ->setCellValue('A9', 'N°')
            ->setCellValue('B9', 'NOMBRE')
            ->setCellValue('D9', 'Nota');

        $spreadsheet->getActiveSheet()
            ->setCellValue('B3', $titulo)
            ->getStyle("B3")->getFont()
            ->setSize(20)
            ->setBold(true);

        //Seteamos insignia
        $sheeti = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $sheeti->setPath('./public/imagenes/insignia-pdf.png');
        $sheeti->setHeight(110);
        $sheeti->setCoordinates("C2");
        $sheeti->setWorksheet($spreadsheet->getActiveSheet());


        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle("A9:A10")->getBorders()->getOutline()->
        setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
        $spreadsheet->getActiveSheet()->getStyle("B9:B10")->getBorders()->getOutline()->
        setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
        $spreadsheet->getActiveSheet()->getStyle("A9:B10")->getFont()->setBold(true);


        //Obtenemos matriculados segun año escolar, grado y nivel educativo
        $matriculados = $this->m_matricula->mostrar_cuando(array(
                                                               "matricula.id_anio_escolar"    => $id_anio_escolar,
                                                               "matricula.id_nivel_educativo" => $id_nivel_educativo,
                                                               "matricula.id_grado"           => $id_grado
                                                           )
        );

        //Obtenemos cursos
        $cursos = $this->m_curso->mostrar_cuando(array("curso.id_grado" => $id_grado, "curso.id_anio_escolar" => $id_anio_escolar));
        //Obtenemos bimestres
        $bimestres = $this->m_bimestre->mostrar_activos();

        //Recorremos y armamos data

        $i = 1;
        $indice_curso = 9;
        $indice_letra_inicial = 2; // Inicia en C
        $indice_letra_final = 6;
        $indice_letra_bimestre = 2; // Inicia en C

        //Recorremos cada curso
        foreach ($cursos as $curso) {
            $letra_celda_inicial_curso = $this->obtenerLetraExcel($indice_letra_inicial);
            $letra_celda_final_curso = $this->obtenerLetraExcel($indice_letra_final);

            //Armamos cabecera de los cursos
            $spreadsheet->getActiveSheet()
                ->mergeCells("$letra_celda_inicial_curso$indice_curso:$letra_celda_final_curso$indice_curso")      //Combinando celdas
                ->setCellValue("$letra_celda_inicial_curso$indice_curso", $curso["nombre"]);                   //Seteando nombre de curso a la celda combinada
            $spreadsheet->getActiveSheet()->getStyle("$letra_celda_inicial_curso$indice_curso")->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle("$letra_celda_inicial_curso$indice_curso:$letra_celda_final_curso$indice_curso")->getBorders()->getOutline()->
            setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
            $spreadsheet->getActiveSheet()->getStyle("$letra_celda_inicial_curso$indice_curso")->getFont()->setBold(true);
            $indice_letra_inicial += 5;
            $indice_letra_final += 5;


            //Recorremos cada bimestre
            foreach ($bimestres as $bimestre) {
                //Armamos cabecera de bimestres
                $letra_bimestre = $this->obtenerLetraExcel($indice_letra_bimestre);
                $spreadsheet->getActiveSheet()->setCellValue("{$letra_bimestre}10",
                                                             $bimestre["nombre_abreviado"]
                );//Seteando nombre del bimestre en la celda (4 bimestres)
                $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre}10")->getAlignment()->setHorizontal('center');
                $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre}10")->getBorders()->getOutline()->
                setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
                $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre}10")->getFont()->setBold(true);
                $indice_letra_bimestre++;
            }

            //Agregamos cabecera de Promedio Final
            $indice_letra_pf = $indice_letra_bimestre++;
            $letra_bimestre_pf = $this->obtenerLetraExcel($indice_letra_pf);
            $spreadsheet->getActiveSheet()->setCellValue("{$letra_bimestre_pf}10", "PF");//Seteando nombre del bimestre en la celda (4 bimestres)
            $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre_pf}10")->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre_pf}10")->getFill()->
            setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color_fondo_pf);
            $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre_pf}10")->getBorders()->getOutline()->
            setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
            $spreadsheet->getActiveSheet()->getStyle("{$letra_bimestre_pf}10")->getFont()->setBold(true);
        }

        $indice_alumno = 11;
        foreach ($matriculados as $matricula) {
            $spreadsheet->getActiveSheet()
                ->setCellValue("A$indice_alumno", $i)
                ->setCellValue("B$indice_alumno", "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}");
            $spreadsheet->getActiveSheet()->getStyle("A$indice_alumno")->getBorders()->getOutline()->
            setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
            $spreadsheet->getActiveSheet()->getStyle("B$indice_alumno")->getBorders()->getOutline()->
            setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));

            $indice_alumno++;

            $tabla = $id_nivel_educativo == 2 ? "nota_alumno_primaria" : "nota_alumno_secundaria";
            $query = "SELECT id_matricula, id_bimestre, id_anio_escolar, id_curso, promedio_final FROM $tabla
                        WHERE id_matricula = {$matricula["id_matricula"]} ORDER BY id_curso, id_bimestre ASC;";

            $query = $this->db->query($query);
            $notas = $query->result_array();

            $letra_inicial_nota_curso = 2; // C
            $numero_celda_nota = $indice_alumno - 1;
            $num = 1;
            $suma_promedios = 0;
            foreach ($notas as $nota) {
                $letra_celda_nota = $this->obtenerLetraExcel($letra_inicial_nota_curso);
                $spreadsheet->getActiveSheet()
                    //->setCellValue("$letra_celda_nota$numero_celda_nota", "$letra_celda_nota$numero_celda_nota");
                    ->setCellValue("$letra_celda_nota$numero_celda_nota", $nota["promedio_final"]);
                $spreadsheet->getActiveSheet()->getStyle("$letra_celda_nota$numero_celda_nota")->getAlignment()->setHorizontal('center');
                $spreadsheet->getActiveSheet()->getStyle("$letra_celda_nota$numero_celda_nota")->getBorders()->getOutline()->
                setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
                $suma_promedios += $nota["promedio_final"];

                //Validamos para poder obtener el promedio final de cada curso
                if ($num % 4 == 0) {
                    $letra_inicial_nota_curso++;                                             //Aumentamos en una posicion la letra de la celda para que sea el promedio final
                    $letra_celda_nota = $this->obtenerLetraExcel($letra_inicial_nota_curso); //Obtenemos la letra
                    $promedio_final = $suma_promedios / 4;                                   //Calculamos promedio final de los 4 bimestres
                    $spreadsheet->getActiveSheet()
                        ->setCellValue("$letra_celda_nota$numero_celda_nota", round($promedio_final, 0, PHP_ROUND_HALF_UP)); //Seteamos promedio final
                    $spreadsheet->getActiveSheet()->getStyle("$letra_celda_nota$numero_celda_nota")->getAlignment()->setHorizontal('center');
                    $spreadsheet->getActiveSheet()->getStyle("$letra_celda_nota$numero_celda_nota")->getFill()->
                    setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color_fondo_pf);
                    $spreadsheet->getActiveSheet()->getStyle("$letra_celda_nota$numero_celda_nota")->getBorders()->getOutline()->
                    setBorderStyle(Border::BORDER_THIN)->setColor(new Color($color_borde_celdas));
                    $spreadsheet->getActiveSheet()->getStyle("$letra_celda_nota$numero_celda_nota")->getFont()->setBold(true);
                    $suma_promedios = 0; //Volvemos a setear promedio en cero para que se vuelva a calcular con las otras notas
                }
                $num++;
                $letra_inicial_nota_curso++;
            }
            $i++;
        }


        $nombre = "Consolidado_{$anio_escolar["nombre"]}_{$grado["abreviatura"]}_{$fecha_hoy}.xlsx";
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($nombre) . '"');
        $writer->save('php://output');
    }

    /*----------------------------- FUNCIONES PRIVADAS -----------------------------*/
    /*------------------------------------------------------------------------------*/

    /* --------------- ASISTENCIA --------------- */
    /* Funcion para obtener resumen de todas las asistencias */
    private function obtener_resumen($data_asistencias_alumno) {
        //Seteamos variables en cero
        $total_asistencias = 0;
        $total_faltas = 0;
        $total_tardanzas = 0;
        $total_faltas_justificadas = 0;
        $total_tardanzas_justificadas = 0;

        //Recorremos cada dia para sumar su total
        $numero_dia = 1;
        for ($r = 0; $r < 31; $r++) {
            switch ($data_asistencias_alumno["dia_$numero_dia"]) {
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

    /* Funcion que devuelve las asistencias procesadas (Lunes a Viernes) */
    private function procesa_asistencia($id_mes_escolar, $nombre_anio_escolar, $data_asistencia_alumno) {
        if ($id_mes_escolar == '4' || $id_mes_escolar == '6' || $id_mes_escolar == '9' || $id_mes_escolar == '11') { //MESES CON 30 DIAS
            $total_dias = 30;
        } else { //MESES CON 31 DIAS
            $total_dias = 31;
        }

        for ($f = 1; $f <= $total_dias; $f++) {
            //$data['cantidad_dias'] = $total_dias;
            $nombre_dia = $this->dias[date('N', strtotime($nombre_anio_escolar . '-' . $id_mes_escolar . '-' . $f))];
            if (($nombre_dia != "Domingo" && $nombre_dia != "Sábado")) {
                $data[] = array(
                    "nombre" => $nombre_dia,
                    "numero" => $f,
                    "valor"  => $data_asistencia_alumno["dia_$f"] == "NA" ? "-" : $data_asistencia_alumno["dia_$f"],
                );
            }
        }
        return $data;
    }

    /* Funcion que devuelve las asistencias procesadas para masivos por grado (Lunes a Viernes) */
    private function procesa_asistencia_grado($id_mes_escolar, $nombre_anio_escolar, $data_asistencia_alumno) {
        if ($id_mes_escolar == '4' || $id_mes_escolar == '6' || $id_mes_escolar == '9' || $id_mes_escolar == '11') { //MESES CON 30 DIAS
            $total_dias = 30;
        } else { //MESES CON 31 DIAS
            $total_dias = 31;
        }
        //$matricula = $this->m_matricula->mostrar(array("matricula.id_matricula" => $data_asistencia_alumno["id_matricula"]));
        //$data["nombre_alumno"] = "{$matricula["apellido_paterno"]} {$matricula["apellido_materno"]}, {$matricula["nombre"]}";
        for ($f = 1; $f <= $total_dias; $f++) {
            //$data['cantidad_dias'] = $total_dias;
            $nombre_dia = $this->dias[date('N', strtotime($nombre_anio_escolar . '-' . $id_mes_escolar . '-' . $f))];
            if (($nombre_dia != "Domingo" && $nombre_dia != "Sábado")) {
                $data[] = array(
                    "nombre" => substr($nombre_dia, 0, 2),
                    "numero" => $f < 10 ? "0" . $f : $f,
                    "valor"  => $data_asistencia_alumno["dia_$f"] == "NA" ? "-" : $data_asistencia_alumno["dia_$f"],
                );
            }
        }
        return $data;
    }

    /* Funcion que devuelve las asistencias procesadas para masivos por grado (Lunes a Viernes) */
    private function procesa_dias_grado($id_mes_escolar, $nombre_anio_escolar) {
        if ($id_mes_escolar == '4' || $id_mes_escolar == '6' || $id_mes_escolar == '9' || $id_mes_escolar == '11') { //MESES CON 30 DIAS
            $total_dias = 30;
        } else { //MESES CON 31 DIAS
            $total_dias = 31;
        }
        for ($f = 1; $f <= $total_dias; $f++) {
            $nombre_dia = $this->dias[date('N', strtotime($nombre_anio_escolar . '-' . $id_mes_escolar . '-' . $f))];
            if (($nombre_dia != "Domingo" && $nombre_dia != "Sábado")) {
                $data[] = array(
                    "nombre" => substr($nombre_dia, 0, 2),
                    "numero" => $f < 10 ? "0" . $f : $f,
                );
            }
        }
        return $data;
    }
    /* --------------------------------------------------- */

    /* --------------- NOTAS --------------- */
    /* Funcion para obtener los 4 promedios bimestrales y calcular el promedio final */
    private function procesa_notas($nota_alumno) {
        $suma_promedios = 0;
        foreach ($nota_alumno as $n) {
            $campo = "promedio_bimestre_{$n["id_bimestre"]}"; //Nombre del campo para obtener su valor de la bd
            $promedio = $n["promedio_final"];                 //Promedio final de cada bimestre
            $suma_promedios += $promedio;                     //Sumamos los promedios obtenidos de cada bimestre
            $data[$campo] = $promedio;                        //Seteamos los promedios en la variable data

        }
        if ($data["promedio_bimestre_1"] == "" || $data["promedio_bimestre_2"] == "" || $data["promedio_bimestre_3"] == "" || $data["promedio_bimestre_4"] == "") {
            $data["promedio_final"] = "";
        } else {
            $prom = round($suma_promedios / 4);
            if ($prom == 0){
                $data["promedio_final"] = ""; //Calculamos el promedio final (Suma de los 4 bimestres)
            }
            else{
                $data["promedio_final"] = $prom; //Calculamos el promedio final (Suma de los 4 bimestres)
            }
            
        }
        return $data;
    }

    /* Funcion para obtener las 4 notas del comportamiento y calcular el promedio final*/
    private function procesa_comportamiento($nota_comportamiento) {
        $suma_promedios = 0;
        foreach ($nota_comportamiento as $nc) {
            $promedio = $nc["nota"];                                           //Promedio final de cada bimestre
            $suma_promedios += $promedio;                                      //Sumamos los promedios obtenidos de cada bimestre
            $data["comportamiento_bimestre_{$nc["id_bimestre"]}"] = $promedio; //Seteamos los promedios en la variable data
        }

        if ($data["comportamiento_bimestre_1"] == "" || $data["comportamiento_bimestre_2"] == "" || $data["comportamiento_bimestre_3"] == "" || $data["comportamiento_bimestre_4"] == "") {
            $data["promedio_comportamiento_final"] = "";
        } else {
            $data["promedio_comportamiento_final"] = round($suma_promedios / 4); //Calculamos el promedio final (Suma de los 4 bimestres)
        }
        return $data;
    }

    /* Funcion para obtener el resumen de asistencias de los 4 bimestres */
    private function procesa_asistencia_pdf($asistencia) {
        $asistencias_primer_bimestre = 0;
        $faltas_primer_bimestre = 0;
        $tardanzas_primer_bimestre = 0;
        $faltas_justificadas_primer_bimestre = 0;
        $tardanzas_justificadas_primer_bimestre = 0;

        $asistencias_segundo_bimestre = 0;
        $faltas_segundo_bimestre = 0;
        $tardanzas_segundo_bimestre = 0;
        $faltas_justificadas_segundo_bimestre = 0;
        $tardanzas_justificadas_segundo_bimestre = 0;

        $asistencias_tercer_bimestre = 0;
        $faltas_tercer_bimestre = 0;
        $tardanzas_tercer_bimestre = 0;
        $faltas_justificadas_tercer_bimestre = 0;
        $tardanzas_justificadas_tercer_bimestre = 0;

        $asistencias_cuarto_bimestre = 0;
        $faltas_cuarto_bimestre = 0;
        $tardanzas_cuarto_bimestre = 0;
        $faltas_justificadas_cuarto_bimestre = 0;
        $tardanzas_justificadas_cuarto_bimestre = 0;

        foreach ($asistencia as $a) {
            switch ($a["id_mes_escolar"]) {
                case 3: //MARZO - ABRIL (1ER BIMESTRE)
                case 4:
                    $asistencias_primer_bimestre += $a["asistencia"];
                    $faltas_primer_bimestre += $a["falta"];
                    $tardanzas_primer_bimestre += $a["tardanza"];
                    $faltas_justificadas_primer_bimestre += $a["falta_justificada"];
                    $tardanzas_justificadas_primer_bimestre += $a["tardanza_justificada"];
                    break;

                case 5: //MAYO - JUNIO - JULIO (2DO BIMESTRE)
                case 6:
                case 7:
                    $asistencias_segundo_bimestre += $a["asistencia"];
                    $faltas_segundo_bimestre += $a["falta"];
                    $tardanzas_segundo_bimestre += $a["tardanza"];
                    $faltas_justificadas_segundo_bimestre += $a["falta_justificada"];
                    $tardanzas_justificadas_segundo_bimestre += $a["tardanza_justificada"];
                    break;
                case 8: //AGOSTO - SETIEMBRE - OCTUBRE (3ER BIMESTRE)
                case 9:
                case 10:
                    $asistencias_tercer_bimestre += $a["asistencia"];
                    $faltas_tercer_bimestre += $a["falta"];
                    $tardanzas_tercer_bimestre += $a["tardanza"];
                    $faltas_justificadas_tercer_bimestre += $a["falta_justificada"];
                    $tardanzas_justificadas_tercer_bimestre += $a["tardanza_justificada"];
                    break;

                case 11: //NOVIEMBRE - DICIEMBRE (4TO BIMESTRE)
                case 12:
                    $asistencias_cuarto_bimestre += $a["asistencia"];
                    $faltas_cuarto_bimestre += $a["falta"];
                    $tardanzas_cuarto_bimestre += $a["tardanza"];
                    $faltas_justificadas_cuarto_bimestre += $a["falta_justificada"];
                    $tardanzas_justificadas_cuarto_bimestre += $a["tardanza_justificada"];
                    break;
            }
        }

        $data = array(
            "primer_bimestre"  => array(
                "asistencias"            => $asistencias_primer_bimestre,
                "faltas"                 => $faltas_primer_bimestre,
                "tardanzas"              => $tardanzas_primer_bimestre,
                "faltas_justificadas"    => $faltas_justificadas_primer_bimestre,
                "tardanzas_justificadas" => $tardanzas_justificadas_primer_bimestre,
            ),
            "segundo_bimestre" => array(
                "asistencias"            => $asistencias_segundo_bimestre,
                "faltas"                 => $faltas_segundo_bimestre,
                "tardanzas"              => $tardanzas_segundo_bimestre,
                "faltas_justificadas"    => $faltas_justificadas_segundo_bimestre,
                "tardanzas_justificadas" => $tardanzas_justificadas_segundo_bimestre,
            ),
            "tercer_bimestre"  => array(
                "asistencias"            => $asistencias_tercer_bimestre,
                "faltas"                 => $faltas_tercer_bimestre,
                "tardanzas"              => $tardanzas_tercer_bimestre,
                "faltas_justificadas"    => $faltas_justificadas_tercer_bimestre,
                "tardanzas_justificadas" => $tardanzas_justificadas_tercer_bimestre,
            ),
            "cuarto_bimestre"  => array(
                "asistencias"            => $asistencias_cuarto_bimestre,
                "faltas"                 => $faltas_cuarto_bimestre,
                "tardanzas"              => $tardanzas_cuarto_bimestre,
                "faltas_justificadas"    => $faltas_justificadas_cuarto_bimestre,
                "tardanzas_justificadas" => $tardanzas_justificadas_cuarto_bimestre,
            ),
            "totales"          => array(
                "asistencias"            => $asistencias_primer_bimestre + $asistencias_segundo_bimestre + $asistencias_tercer_bimestre + $asistencias_cuarto_bimestre,
                "faltas"                 => $faltas_primer_bimestre + $faltas_segundo_bimestre + $faltas_tercer_bimestre + $faltas_cuarto_bimestre,
                "tardanzas"              => $tardanzas_primer_bimestre + $tardanzas_segundo_bimestre + $tardanzas_tercer_bimestre + $tardanzas_cuarto_bimestre,
                "faltas_justificadas"    => $faltas_justificadas_primer_bimestre + $faltas_justificadas_segundo_bimestre + $faltas_justificadas_tercer_bimestre + $faltas_justificadas_cuarto_bimestre,
                "tardanzas_justificadas" => $tardanzas_justificadas_primer_bimestre + $tardanzas_justificadas_segundo_bimestre + $tardanzas_justificadas_tercer_bimestre + $tardanzas_justificadas_cuarto_bimestre,
            ),

        );
        return $data;
    }

    /* Funcion que retorna las letras del excel pasandole el numero */
    private function obtenerLetraExcel($num) {
        $numero = $num % 26;
        $letra = chr(65 + $numero);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->obtenerLetraExcel($num2 - 1) . $letra;
        } else {
            return $letra;
        }
    }

    /* Funcion para obtener array segun valor buscado */
    private function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if ($val['id_bimestre'] == $id) {
                return $array[$key];
            }
        }
        return null;
    }

}
