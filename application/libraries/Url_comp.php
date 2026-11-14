<?php

class Url_comp {

    public function direccionar($url) {
        $string = '<script>location.href="';
        $string .= $url;
        $string .= '"</script>';
        return $string;
    }

    public function actualizar() {
        $string = '<script>';
        $string .= 'parent.location.reload()';
        $string .= '</script>';
        return $string;
    }

    public function actualizar_tiempo($tiempo = 3000) {
        $string = '<script>';
        $string .= 'setTimeout(function(){ parent.location.reload() }, ' . $tiempo . ');';
        $string .= '</script>';
        return $string;
    }

    public function direccionar_tiempo($url, $tiempo = 3000) {
        $string = '<script>';
        $string .= 'setTimeout(function(){ parent.location.href="';
        $string .= $url;
        $string .= '" }, ' . $tiempo . ');';
        $string .= '</script>';
        return $string;
    }

}
