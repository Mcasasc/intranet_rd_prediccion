<?php

class Archivo {

    public function generar_dropdown($data, $name, $default = '', $string = 'Seleccione una opción', $type = 'none') {
        //genera los grados de la base de datos en un </select>
        $this->ci = &get_instance();
        $this->ci->load->helper('form');
        switch ($type) {
        case 'search':
            $_type = 'data-live-search="true"';
            break;
        case 'none':
            $_type = '';
            break;
        }
        $option[''] = $string;
        foreach ($data as $key => $value) {
            $option[$key] = $value;
        }

        $extra = 'id="' . $name . '" class="form-control selectpicker" ' . $_type;
        $result = form_dropdown($name, $option, $default, $extra);
        unset($option);
        return $result;
    }

}
