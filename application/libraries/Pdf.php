<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'third_party/vendor/autoload.php';

use Dompdf\Dompdf;

class Pdf {

    /* Funcion para generar pdf a partir de un html */
    public function generar_pdf($papel = "", $orientacion = "", $html = "", $nombre) {
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($html);
        $dompdf->setPaper($papel, $orientacion);
        $dompdf->render();
        //$dompdf->stream("nombre.pdf");
        $dompdf->stream("$nombre.pdf", array("Attachment" => 1)); //0 => Abre el pdf en el navegador || 1 => Descarga directamente el pdf
        //return $dompdf->output();

    }
}
