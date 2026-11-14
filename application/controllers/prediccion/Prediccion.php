<?php

@session_cache_limiter('private, must-revalidate');
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", FALSE);
@header("Pragma: no-cache");

// require_once APPPATH . "/libraries/fuzzy-logic-class.php";


//error_reporting(0);

class Prediccion extends CI_Controller {

    private $_result;

    public function __construct() {
        parent::__construct();

        /*
         * Configuración para librerias, helpers y modelos
         */
        $library = array('smarty_tpl', 'session_sistema', 'url_comp', 'mantenimiento', 'documento', 'archivo');
        $helper = array('url');
        $model = array('m_usuario', 'm_privilegio', 'm_persona', 'm_estado_civil', 'm_distrito', 'm_prediccion', 'm_prediccion_log');
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
        $this->items['prediccion_activo'] = 'active';
    }

    /* Funcion para listar todo */
    public function listar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $data['titulo_pagina'] = $this->items['proyecto'] . ' | Lista de Predicciones';
        $data['titulo_modulo'] = "Lista de Predicciones";
        /* ------------------------------------------------------------ */
        
        $lista = $this->m_prediccion_log->mostrar_todo();
        /* ------------------------------------------------------------ */
        if ($login['usuario_privilegio'] == 1 || $login['usuario_privilegio'] == 7) { //Si tiene privilegios de administrador
            if (!empty($lista)) {
                $i = 1;
                foreach ($lista AS $items) {

                    
                    $alumno = $this->m_alumno->mostrar(array('alumno.id_alumno' => $items['id_alumno']));
                    $data['lista'][] = array(
                        'numero' => $i,
                        'nombre' => $alumno['nombre'] . ' ' . $alumno['apellido_paterno'] . ' ' . $alumno['apellido_materno'],
                        'promedio_nota_ingresada' => $items['promedio_nota_ingresada'],
                        'promedio_nota_historica' => $items['promedio_nota_historica'],
                        'promedio_nota_final' => $items['promedio_nota_final'],
                        'promedio_psicologia' => $items['promedio_psicologia'],
                        'promedio_asistencia' => $items['promedio_asistencia'],
                        'color_nivel_ld' => $this->evaluaColor($items['respuesta_nivel_ld']),
                        'respuesta_nivel_ld' => strtoupper($items['respuesta_nivel_ld']),
                        'respuesta_recomendacion_ld' => nl2br($items['respuesta_recomendacion_ld']),
                        'agregado' => date("d/m/Y H:i:s", strtotime($items['agregado'])),
                    );
                    $i++;
                    
                }
            }
        } else {
            echo $this->url_comp->direccionar(base_url() . 'home', TRUE);
            EXIT;
        }
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/prediccion/listar_prediccion', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para mostrar el formulario de registro de prediccion */
    public function agregar() {
        $login = $this->session_sistema->datos_usuario_logueado();
        $privilegio_sesion = $login["usuario_privilegio"];
        $data['titulo_pagina'] = "{$this->items["proyecto"]} | Generar Predicción";
        $data['titulo_modulo'] = "Generar Predicción";
        /* ------------------------------------------------------------ */
        $idAlumno = $login["alumno_id"];
        $data["id_alumno"] = $idAlumno;

        # Obtenemos y calculamos
        $listaAsistencias = $this->m_prediccion->historicoNotasAsistencias($idAlumno);
        $listaSocioEconomico = $this->m_prediccion->historicoNotasSocioEconomico($idAlumno);
        

        $data["promedio_asistencia"] = $this->calculaPromedio($listaAsistencias, "promedio");
        $data["promedio_psicologia"] = $this->calculaPromedio($listaSocioEconomico, "nota");

        
        /* ------------------------------------------------------------ */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $login);
        $this->smarty_tpl->view('estructura/header', $data);
        $this->smarty_tpl->view('estructura/inter_header', $data);
        $this->smarty_tpl->view('internas/prediccion/form_prediccion', $data);
        $this->smarty_tpl->view('estructura/inter_footer', $data);
        $this->smarty_tpl->view('estructura/footer', $data);
    }

    /* Funcion para procesar */

    public function accion() {
        header('Content-Type: application/json');
        # Validamos sesion
        $this->session_sistema->datos_usuario_logueado();

        # Definimos zona horaria
        date_default_timezone_set("America/Lima");

        # Obtenemos datos
        $idAlumno = $this->input->post('id_alumno');
        $examenMensual = $this->input->post('examen_mensual');
        $examenBimestral = $this->input->post('examen_bimestral');
        $revisionCuaderno = $this->input->post('revision_cuaderno');
        $participacionTarea = $this->input->post('participacion_tarea');
        $promedioPsicologia = $this->input->post('promedio_psicologia');
        $promedioAsistencia = $this->input->post('promedio_asistencia');

        //Validamos campos
        $error = '';
        $error .= $this->mantenimiento->validacion($examenMensual, 'required|calificacion', 'Examen Mensual');
        $error .= $this->mantenimiento->validacion($examenBimestral, 'required|calificacion', 'Examen Bimestral');
        $error .= $this->mantenimiento->validacion($revisionCuaderno, 'required|calificacion', 'Revisión de Cuadernos');
        $error .= $this->mantenimiento->validacion($participacionTarea, 'required|calificacion', 'Participación y Tareas');
        $error .= $this->mantenimiento->validacion($promedioPsicologia, 'required|calificacion', 'Promedio Psicología');
        $error .= $this->mantenimiento->validacion($promedioAsistencia, 'required|calificacion', 'Promedio Asistencia a clases');
        
        //Si hay error de validacion
        if ($error != '') {
            echo $this->alerta->alerta_error($error, TRUE);
            exit;
        }        
        
        # Calculamos Promedio ingresado por el alumno
        $promedioNotasIngresadas = ($examenMensual + $examenBimestral + $revisionCuaderno + $participacionTarea) / 4;
        
        $listaNotasAcademicas = $this->m_prediccion->historicoNotasAcademicas($idAlumno);
        $promedioNotasHistoricas = $this->calculaPromedio($listaNotasAcademicas, "promedio_final");

        # Lo promediamos contra la nota historica
        $notaPromedioFinal = ($promedioNotasIngresadas + $promedioNotasHistoricas) / 2;

      
        /* -------------------------------------------- */
        /* Proceso de envio al endpoint Python          */
        /* -------------------------------------------- */

        // URL de tu API Flask
        $url = 'https://python.renedescartes.edu.pe/prediccion';

        // Datos a enviar a la API
        $data_lg = array(
            'promedio_notas' => floatval($notaPromedioFinal),
            'promedio_evaluacion' => floatval($promedioPsicologia),
            'promedio_asistencias' => floatval($promedioAsistencia)
        );

        // Inicializar una nueva sesión cURL
        $ch = curl_init();

        // Configurar la solicitud cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_lg));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realizar la solicitud a la API Flask y obtener la respuesta
        $response = curl_exec($ch);

        // Cerrar la sesión cURL
        curl_close($ch);

        // Decodificar la respuesta JSON
        $result = json_decode($response, true);

        $respuestaNivelLD = $result["nivel"];
        $listaRecomendacionLD = $result["recomendaciones"];
        
        $respuestaRecomendacionLD = "";
        foreach ($listaRecomendacionLD as $value) {
            $respuestaRecomendacionLD .= $value. "\n";
        }
     
        //Data para el registro de usuario
        $data_log = array(
            'id_alumno' => $idAlumno,
            'promedio_nota_ingresada' => $promedioNotasIngresadas,
            'promedio_nota_historica' => $promedioNotasHistoricas,
            'promedio_nota_final' => $notaPromedioFinal,
            'promedio_psicologia' => $promedioPsicologia,
            'promedio_asistencia' => $promedioAsistencia,
            'respuesta_nivel_ld' => $respuestaNivelLD,
            'respuesta_recomendacion_ld' => $respuestaRecomendacionLD,
            'agregado' => date("Y-m-d H:i:s"),
        );
        //Insertamos log
        $resultSet = $this->m_prediccion_log->insertar($data_log);

        //Si se inserto correctamente
        if ($resultSet) {
            # Mostramos la respuesta de la prediccion
            $data["respuesta"] = $result;

            $data["promedios"] = array(
                "promedio_notas_ingresadas" => $promedioNotasIngresadas,
                "promedio_notas_historicas" => $promedioNotasHistoricas,
                "promedio_psicologia" => $promedioPsicologia,
                "promedio_asistencia" => $promedioAsistencia
            );

            echo json_encode($data);
            exit;
        }

        if ($resultSet === FALSE) {
            echo $this->alerta->alerta_error('Hubo algunos errores, intente nuevamente.');
            exit;
        }

    }

    /*
     $data es la variable que contiene el arreglo de datos a recorrer
     $clave es la llave del arreglo que se tiene que promediar
    */
    private function calculaPromedio($data, $clave){

        $promedioFinalAsistencia = 0;

        if(!empty($data)){
            $sumaPromedios = 0;
            $totalPromedios = 0;

            foreach ($data as $da) {
                if($da[$clave] > 0){ # Sumamos todos los promedios
                    $sumaPromedios += $da[$clave];
                    $totalPromedios++;
                }
            }

            # Promediamos
            $promedioFinalAsistencia = $sumaPromedios / $totalPromedios;
        }

        return round($promedioFinalAsistencia, 2);
    }

    private function evaluaColor($nivel){

        $color = "";

        switch ($nivel) {
            case 'alto':
                $color = "badge badge-pill badge-success";
                break;
            case 'medio':
                $color = "badge badge-pill badge-info";
                break;
            case 'bajo':
                $color = "badge badge-pill badge-warning";
                break;
        }

        return $color;
    }
}
