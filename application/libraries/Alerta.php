<?php

class Alerta {

    public function alerta_error($mensaje) {
        $string = "<script>" . "\n";
        $string .= "swal(" . "\n";
        $string .= "'" . '¡Error!' . "',";
        $string .= "'" . $mensaje . "',";
        $string .= "'" . 'error' . "'";
        $string .= ")";
        $string .= "\n</script>";
        return $string;
    }

    public function alerta_exito($mensaje) {
        $string = "<script>" . "\n";
        $string .= "swal(" . "\n";
        $string .= "'" . '¡Bien!' . "',";
        $string .= "'" . $mensaje . "',";
        $string .= "'" . 'success' . "'";
        $string .= ")";
        $string .= "\n</script>";
        return $string;
    }

    public function alerta_advertencia($mensaje) {
        $string = "<script>" . "\n";
        $string .= "swal(" . "\n";
        $string .= "'" . '¡Advertencia!' . "',";
        $string .= "'" . $mensaje . "',";
        $string .= "'" . 'warning' . "'";
        $string .= ")";
        $string .= "\n</script>";
        return $string;
    }

    public function mensaje($mensaje) {
        $string = '<div class="container row"><div class="row col-md-12"><div class="row col-md-6"><div class="box box-success box-solid">';
        $string .= '';
        $string .= '<div class="box-body">';
        $string .= $mensaje;
        $string .= '</div></div></div></div></div>';
        return $string;
    }

    public function location_href($url) {
        $string = '<script>location.href="';
        $string .= $url;
        $string .= '"</script>';
        return $string;
    }

    public function refrescar_tiempo($time = 3000) {
        $string = '<script>';
        $string .= 'setTimeout(function(){ parent.location.reload() }, ' . $time . ');';
        $string .= '</script>';
        return $string;
    }

}
