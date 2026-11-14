<?php

class Mantenimiento {

    /* Funcion para obtener las acciones a realizar  */
    public function accion($id, $data, $controlador, $estado) {
        $items = explode('|', $data);
        $string = '';
        foreach ($items as $row) {
            switch (trim($row)) {
            case 'editar':
                $string .= "<a class='btn btn-primary btn-sm' href='" . base_url() . "{$controlador}/editar/{$id}' title='Modificar' style= 'margin: 1px;'><i class='fas fa-edit'></i></a>";
                break;
            case 'ver':
                $string .= "<a class='btn btn-primary btn-sm' href='" . base_url() . "{$controlador}/ver/{$id}' title='Leer Mensaje' style= 'margin: 1px;'><i class='fas fa-eye'></i></a>";
                break;
            case 'denegar':
                if ($estado == 0) {
                    $string .= "<a href='' class='btn btn-danger btn-sm bloquear' data-url='" . base_url() . "{$controlador}/accion_denegar/{$id}' title='Bloquear' style= 'margin: 1px;'><i class='fa fa-ban'></i></a>";
                }
                break;
            case 'permitir':
                if ($estado == 1) {
                    $string .= "<a href='' class='btn btn-success btn-sm desbloquear' data-url='" . base_url() . "{$controlador}/accion_permitir/{$id}' title='Desbloquear' style= 'margin: 1px;'><i class='fa fa-check'></i></a>";
                }
                break;
            case 'cursos':
                $string .= "<a class='btn btn-primary btn-sm' href='" . base_url() . "{$controlador}/listar_cursos_docente/{$id}' title='Listar Cursos' style= 'margin: 1px;'><i class='fas fa-list-ul'></i></a>";
                break;
            case 'aperturar_cuenta':
                $string .= "<a class='btn btn-success btn-sm text-white boton-aperturar-cuenta' data-toggle='modal' title='Aperturar Cuenta' data-target='#modal_aperturar_cuenta' data-idmatricula='{$id}' style='margin: 1px;'><i class='far fa-money-bill-alt'></i></a>";
                break;
            case 'estado_cuenta':
                $string .= "<a class='btn btn-primary btn-sm text-white boton-estado-cuenta' data-toggle='modal' title='Ver Estado de Cuenta' data-target='#modal_estado_cuenta' data-idcuentaalumno='{$id}' style='margin: 1px;'><i class='fas fa-search'></i></a>";
                break;
            case 'comportamiento':
                $string .= "<a class='btn btn-primary btn-sm text-white boton-comportamiento' data-toggle='modal' title='Mostrar Bimestres' data-target='#modal_comportamiento' data-idgrado='{$id[0]}' data-idanioescolar='{$id[1]}'  style='margin: 1px;' ><i class='fas fa-list'></i></a>";
                break;
            case 'asistencia':
                $string .= "<a class='btn btn-primary btn-sm text-white boton-asistencia' data-toggle='modal' title='Mostrar Meses' data-target='#modal_asistencia' data-idgrado='{$id[0]}' data-idanioescolar='{$id[1]}'  style='margin: 1px;' ><i class='fas fa-list'></i></a>";
                break;
            case 'eliminar':
                $string .= "<a href='' class='btn btn-danger btn-sm eliminar' data-url='" . base_url() . "{$controlador}/accion_eliminar/{$id}' title='Eliminar' style= 'margin: 1px;'><i class='fas fa-trash-alt'></i></a>";
                break;
            case 'vistos':
                $string .= "<a class='btn btn-info btn-sm text-white boton-vistos' data-toggle='modal' title='Vieron el mensaje' data-target='#modal_vistos' data-idmensaje='{$id}' style='margin: 1px;' ><i class='fas fa-list'></i></a>";
                $string .= '<br />';
                break;
            default:
                break;
            }
        }
        return $string;
    }

    /* Funcion para validar los campos que se ingresan por formulario y retorna el mensaje de error (INICIAL - PRIMARIA) */
    public function validacion($string, $config, $field) {
        $data = explode('|', $config);
        $message = '';
        foreach ($data as $items) {
            $value = trim($items);
            $new_value = '';
            if (strpos($value, 'minlenght') !== FALSE) {
                $pre_value = str_replace('minlenght', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $new_value = (int) $pre_value;
            }
            if (strpos($value, 'maxlenght') !== FALSE) {
                $pre_value = str_replace('maxlenght', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $new_value = (int) $pre_value;
            }
            if (strpos($value, 'size') !== FALSE) {
                $pre_value = str_replace('size', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $new_value = (int) $pre_value;
            }
            if (strpos($value, 'matched') !== FALSE) {
                $pre_value = str_replace('matched', '', $value);
                $pre_value = str_replace('[', '', $pre_value);
                $pre_value = str_replace(']', '', $pre_value);
                $data = explode('%', $pre_value);
                $data_field = (string) $data[0];
                $data_value = (string) $data[1];
            }
            if (($value == 'trim') && ($string != trim($string))) {
                $message .= "No se permiten espacios en los extremos. Campo: $field <br>";
            } elseif (($value == 'required') && ($string == '')) {
                $message .= "Es necesario llenar este campo. Campo: $field <br>";
            } elseif (($value == 'alpha') && ($string != '') && (!preg_match("/^([[:alpha:]])*$/", $string))) {
                $message .= "Se permiten unicamente caracteres alfabéticos. Campo: $field <br>";
            } elseif (($value == 'alphanumeric') && ($string != '') && (!preg_match("/^([[:alnum:]])*$/", $string))) {
                $message .= "Se permiten unicamente caracteres alfanuméricos. Campo: $field <br>";
            } elseif (($value == 'numeric') && ($string != '') && (!preg_match("/^([[:digit:]])*$/", $string))) {
                $message .= "Se permiten unicamente caracteres numéricios. Campo: $field <br>";
            } elseif (($value == 'alphaspace') && ($string != '') && (!ctype_alpha(str_replace(' ', '', $string)))) {
                $message .= "Se permiten únicamente caracteres alfabéticos y espacios. Campo: $field <br>";
            } elseif (($value == 'decimal') && ($string != '') && (!preg_match("/^[0-9]+(\.[0-9]+)?$/", $string))) {
                $message .= "Se permiten únicamente números enteros y decimales. Campo: $field <br>";
            } elseif (($value == 'date') && ($string != '') && (!preg_match('/^(\d\d\-\d\d\-\d\d\d\d){1,1}$/', $string))) {
                $message .= "Se permiten únicamente fechas con formato dd-mm-yyyy. Campo: $field <br>";
            } elseif (($value == 'alphaspecial') && ($string != '') && (!preg_match('/^[a-zñÑáéíóú\d_\s]+$/i', $string))) {
                $message .= "Se permiten únicamente caracteres alfabéticos especiales. Campo: $field <br>";
            } elseif (($value == 'url') && ($string != '') && (!preg_match('/^[http:\/\/|www.|https:\/\/]/i', $string))) {
                $message .= "Se permiten únicamente direcciones url. Campo: $field <br>";
            } elseif ((strpos($value, 'minlenght') !== FALSE) && ($string != '') && (strlen($string) < $new_value)) {
                $message .= "El texto ingresado es menor a $new_value caracteres. Campo: $field <br>";
            } elseif ((strpos($value, 'maxlenght') !== FALSE) && ($string != '') && (strlen($string) > $new_value)) {
                $message .= "El texto ingresado es mayor a $new_value caracteres. Campo: $field <br>";
            } elseif (($value == 'email') && ($string != '') && (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,4}$/', $string))) {
                $message .= "Se permiten únicamente direcciones de correo. Campo: $field <br>";
            } elseif ((strpos($value, 'size') !== FALSE) && ($string != '') && (strlen($string) != $new_value)) {
                $message .= "El texto ingresado debe tener $new_value caracteres. Campo: $field <br>";
            } elseif ((strpos($value, 'matched') !== FALSE) && ($string != '' || $data_value != '') && ($string != $data_value)) {
                $message .= "El campo $field no se relaciona con el campo $data_field <br>";
            } elseif (($value == 'calificacion') && ($string != '') && ($string < 0 || $string > 20)) {
                $message .= "La nota ingresada debe estar entre 0 y 20. Campo: $field <br>";
            }
        }
        if ($message != '') {
            return $message;
        } else {
            return '';
        }
    }

    /* Funcion para calcular promedio de notas segun la cantidad de notas ingresadas */
    public function promedio_concepto($nota_1 = "", $nota_2 = "", $nota_3 = "", $nota_4 = "", $nota_5 = "", $nota_6 = "", $nota_7 = "", $nota_8 = "") {
        $arreglo_notas = [$nota_1, $nota_2, $nota_3, $nota_4, $nota_5, $nota_6, $nota_7, $nota_8]; //Pasamos las notas a un arreglo para luego recorrerlas
        $contador = 0;
        $promedio = 0;
        $suma_nota = 0;
        //Obtenemos la cantidad de notas ingresadas
        for ($i = 0; $i < 8; $i++) {
            if ($arreglo_notas[$i] != "") {
                $contador++;
                $suma_nota += $arreglo_notas[$i]; //Suma nota cada vez que no esta vacia
            }
        }
        if ($contador <= 0) {
            $promedio = "";
        } else {
            $promedio = round($suma_nota / $contador);
        }

        return $promedio;
    }

    /* Funcion para calcular promedios */
    public function promedio_bimestral($promedio_c1, $promedio_c2, $promedio_c3, $promedio_examen_mensual, $nota_examen_bimestral) {
        //Evaluamos que sean valores numericos
        $promedio_c1 = is_numeric($promedio_c1) == true ? $promedio_c1 : 0;
        $promedio_c2 = is_numeric($promedio_c2) == true ? $promedio_c2 : 0;
        $promedio_c3 = is_numeric($promedio_c3) == true ? $promedio_c3 : 0;
        $promedio_examen_mensual = is_numeric($promedio_examen_mensual) == true ? $promedio_examen_mensual : 0;
        $nota_examen_bimestral = is_numeric($nota_examen_bimestral) == true ? $nota_examen_bimestral : 0;

        //Calculamos promedio bimestral segun la formula
        $promedio_bimestral = ($promedio_c1 + $promedio_c2 + $promedio_c3 + $promedio_examen_mensual + $nota_examen_bimestral) / 5;
        return round($promedio_bimestral);
    }

    /*
    Ordenar array multidimensional por un campo
    $toOrderArray -> Array a ordenar
    $field -> Campo del array por el que queremos ordenarlo (entre comillas).
    $inverse -> Su valor será true o false. El valor true lo ordenará de manera descendente y el false (valor por defecto) lo ordenará de manera ascendente.
     */
    public function ordenaArrayMultidimensional($toOrderArray, $field, $inverse = false) {
        $position = array();
        $newRow = array();
        foreach ($toOrderArray as $key => $row) {
            $position[$key] = $row[$field];
            $newRow[$key] = $row;
        }
        if ($inverse) {
            arsort($position);
        } else {
            asort($position);
        }
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
        return $returnArray;
    }

}
