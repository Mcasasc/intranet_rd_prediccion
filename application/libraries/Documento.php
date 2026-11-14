<?php

class Documento {

    public function generar_dropdown($data, $name, $default = '', $string = 'Seleccione una opción', $type = 'none') {
        //genera los grados de la base de datos en un </select>
        $this->ci = &get_instance();
        $this->ci->load->helper('form');
        switch ($type) {
        case 'search':
            $_type = 'data-live-search="true"';
            $option[''] = $string;
            break;
        case 'none':
            $_type = '';
            $option[''] = $string;
            break;
        case 'multiple':
            $_type = 'multiple';
            break;
        }

        foreach ($data as $key => $value) {
            $option[$key] = $value;
        }

        $extra = 'id="' . $name . '" class="selectpicker" ' . $_type;
        $result = form_dropdown($name, $option, $default, $extra);
        unset($option);
        return $result;
    }

    /* Funcion para subir adjunto */
    public function subir_adjunto($nombre_input, $ruta) {
        $config['upload_path'] = $ruta; //Ruta
        $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg|xlsx|xls|docx|doc|pptx|ppt|rar|zip'; //Extensiones permitidas
        $config['max_size'] = '10000'; //Tamaño maximo del peso del adjunto
        $this->ci = &get_instance();
        $this->ci->load->library('upload', $config); //Cargamos la libreria upload (propia de codeigniter)
        $this->ci->upload->initialize($config);


        //Verificamos si la ruta del archivo existe, sino creamos las carpetas
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }
        
        if (!$this->ci->upload->do_upload($nombre_input)) {
            $resultado = $this->ci->upload->display_errors();
            print_r($resultado); exit;
            return false;
        } else {
            //Subimos el adjunto
            $resultado = $this->ci->upload->data();
            return $resultado;
        }
    }

    public function procesa_nombre_documento($nombre_adjunto) {
        if(!empty($nombre_adjunto)){
            $this->ci = &get_instance();
            $this->ci->load->helper('text'); //Cargamos la libreria upload (propia de codeigniter)
            $nombre = convert_accented_characters(url_title(pathinfo($nombre_adjunto, PATHINFO_FILENAME), "-", TRUE));
            $extension = pathinfo($nombre_adjunto, PATHINFO_EXTENSION);
            return $nombre . "." . $extension;
        }
    }

}
