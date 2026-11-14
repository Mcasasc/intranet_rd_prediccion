<?php

class Modelo_DB {

    var $CI;
    var $tabla;
    var $alias;
    var $tabla_id;
    var $tabla_oculto = "estado";
    var $buscar = 0;

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->database();
    }

    public function setTabla($tabla) {
        $this->tabla = $tabla;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }

    public function setTabla_id($tabla_id) {
        $this->tabla_id = $tabla_id;
    }

    public function setTabla_oculto($tabla_oculto) {
        $this->tabla_oculto = $tabla_oculto;
    }

    public function insertar($data) {
        $insertar = $this->CI->db->insert($this->tabla, $data);
        if ($insertar) {
            return $this->CI->db->insert_id(); //Retorna el ultimo id
        } else {
            return false;
        }
    }

    public function actualizar($data, $where, $valor = FALSE) {
        $this->get_where($where, $valor);
        $update = $this->CI->db->update($this->tabla, $data);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function ocultar($where, $valor = FALSE) {
        $d_ocultar[$this->tabla_oculto] = 1;
        $this->get_where($where, $valor);
        if ($this->CI->db->update($this->tabla, $d_ocultar)) {
            return true;
        } else {
            return false;
        }
    }

    public function permitir($where, $valor = FALSE) {
        $d_ocultar[$this->tabla_oculto] = 0;
        $this->get_where($where, $valor);
        if ($this->CI->db->update($this->tabla, $d_ocultar)) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminar($where, $valor = FALSE) {
        $this->get_where($where, $valor);
        $delete = $this->CI->db->delete($this->tabla);
        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    public function mostrar($where, $valor = FALSE) {
        $this->get_query();
        $this->get_where($where, $valor);
        return $this->CI->db->get()->row_array();
    }

    public function mostrar_todo($limite = FALSE, $offset = FALSE) {
        $this->get_query();
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_activos($limite = FALSE, $offset = FALSE) {
        $where = $this->alias . '.' . $this->tabla_oculto;
        $valor = 0;
        $this->get_query();
        $this->get_where($where, $valor);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_inactivos($limite = FALSE, $offset = FALSE) {
        $where = $this->alias . '.' . $this->tabla_oculto;
        $valor = 1;
        $this->get_query();
        $this->get_where($where, $valor);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_cuando($where, $limite = FALSE, $offset = FALSE) {
        $this->get_query();
        $this->get_where($where);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function mostrar_cuando_uno($where, $limite = FALSE, $offset = FALSE) {
        $this->get_query();
        $this->get_where($where);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->row_array();
        } else {
            return $this->CI->db->get()->row_array();
        }
    }

    public function buscar($like) {
        $this->get_query();
        $this->get_like($like);
        $this->CI->db->where($this->alias . '.estado', '0');

        return $this->CI->db->get()->result_array();
    }

    public function buscar_todo($like) {
        $this->get_query();
        $this->get_like($like);
        return $this->CI->db->get()->result_array();
    }

    public function buscar_activos($like, $limite = FALSE, $offset = FALSE) {
        $this->get_query();
        $this->get_like($like);
        $this->CI->db->where($this->alias . '.estado', '0');
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    public function exists($where, $valor = FALSE) {
        $this->get_query();
        $this->get_where($where, $valor);
        return $this->CI->db->count_all_results();
    }

    public function total_filas($where = FALSE, $valor = FALSE) {
        $this->get_query();
        if ($where) {
            $this->get_where($where, $valor);
        }
        return $this->CI->db->get()->num_rows();
    }

    public function get_where($where, $value = FALSE) {
        if (is_array($where)) {
            foreach ($where as $k => $v) {
                $this->CI->db->where($k, $v);
            }
        } else if ($where !== FALSE) {
            if ($value === FALSE) {
                $value = $where;
                $where = $this->tabla_id;
            }
            $this->CI->db->where($where, $value);
        }
    }

    public function get_like($like) {
        if (is_array($like)) {
            foreach ($like as $k => $v) {
                $this->CI->db->or_like($k, $v);
            }
        }
    }

    /* Funcion para obtener el maximo id pasandole el campo de la base de datos*/
    public function maximo_id($campo) {
        $this->CI->db->select_max($campo);
        return $this->CI->db->get($this->tabla)->row_array()[$campo];
    }

    /* Funcion para consultar con or_where */
    public function mostrar_cuando_where($where, $or_where, $limite = FALSE, $offset = FALSE) {
        $this->get_query();
        $this->get_where($where);
        $this->CI->db->or_where($or_where);
        if ($limite) {
            return $this->CI->db->limit($limite, $offset)->get()->result_array();
        } else {
            return $this->CI->db->get()->result_array();
        }
    }

    /* Funcion para ejecutar cualquier query y retornar el resultado */
    public function ejecuta_query($sql) {
        return $this->CI->db->query($sql)->result_array();
    }
}
