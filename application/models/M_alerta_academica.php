<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_alerta_academica extends Modelo_DB {

    const ID_NIVEL_SECUNDARIA = 3;

    public function __construct() {
        parent::__construct();
        parent::setTabla('predicciones_riesgo');
        parent::setAlias('predicciones_riesgo');
        parent::setTabla_id('id_prediccion');
    }

    public function get_query() {
        $this->CI->db->select("predicciones_riesgo.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_prediccion", "asc");
    }

    /* Bimestre que se preselecciona en "Bimestre a predecir" (2026-11-11): el siguiente al ultimo
       bimestre que ya tiene notas en la mayoria de registros de secundaria de ese anio escolar (al
       menos la mitad del bimestre con mas notas). Se cuenta como "con nota" un registro con alguna
       nota atomica (semanal, cuaderno/conducta, examen) o con n3_promedio mayor a 0, la misma base de
       la reconstruccion de notas: un promedio_final=0 sin notas registradas no cuenta. Solo sugiere:
       el usuario puede elegir otro. Resultado entre 2 y 4. */
    public function bimestre_sugerido($id_anio_escolar) {
        $atomicas = array();
        foreach (array('n1', 'n2') as $c) {
            for ($s = 1; $s <= 8; $s++) {
                $atomicas[] = "n.{$c}_s{$s}";
            }
        }
        $atomicas = array_merge($atomicas, array('n.n3_rc1', 'n.n3_co1', 'n.n3_rc2', 'n.n3_co2', 'n.examen_mensual', 'n.examen_bimestral'));
        $sql = "SELECT n.id_bimestre, SUM(COALESCE(" . implode(', ', $atomicas) . ") IS NOT NULL OR n.n3_promedio > 0) AS con_nota
                FROM nota_alumno_secundaria n
                INNER JOIN matricula m ON m.id_matricula = n.id_matricula
                WHERE m.id_anio_escolar = ?
                GROUP BY n.id_bimestre";
        $filas = $this->CI->db->query($sql, array((int) $id_anio_escolar))->result_array();

        $con_nota = array();
        foreach ($filas as $f) {
            $con_nota[(int) $f['id_bimestre']] = (int) $f['con_nota'];
        }
        $maximo = empty($con_nota) ? 0 : max($con_nota);
        if ($maximo == 0) {
            return 2;
        }
        $ultimo = 1;
        foreach ($con_nota as $bimestre => $cantidad) {
            if ($cantidad * 2 >= $maximo && $bimestre > $ultimo) {
                $ultimo = $bimestre;
            }
        }
        return max(2, min(4, $ultimo + 1));
    }

    /* Cursos de secundaria asignados a un docente en un anio escolar (selector "Mis Cursos") */
    public function cursos_docente($id_usuario, $id_anio_escolar) {
        $this->CI->db->select("curso_docente.id_usuario, curso.id_curso, curso.nombre as nombre_curso,
            grado.nombre as nombre_grado, grado.seccion as seccion_grado");
        $this->CI->db->from('curso_docente');
        $this->CI->db->join('curso curso', 'curso.id_curso = curso_docente.id_curso', 'inner');
        $this->CI->db->join('grado grado', 'grado.id_grado = curso.id_grado', 'inner');
        $this->CI->db->where('curso_docente.id_usuario', $id_usuario);
        $this->CI->db->where('curso_docente.id_anio_escolar', $id_anio_escolar);
        $this->CI->db->where('grado.id_nivel_educativo', self::ID_NIVEL_SECUNDARIA);
        $this->CI->db->order_by('grado.nombre', 'asc');
        $this->CI->db->order_by('curso.nombre', 'asc');
        return $this->CI->db->get()->result_array();
    }

    /* Todos los cursos de secundaria con docente asignado en un anio escolar (Super Admin, sin filtro de docente) */
    public function cursos_todos($id_anio_escolar) {
        $this->CI->db->select("curso_docente.id_usuario, curso.id_curso, curso.nombre as nombre_curso,
            curso.id_grado, grado.nombre as nombre_grado, grado.seccion as seccion_grado");
        $this->CI->db->from('curso_docente');
        $this->CI->db->join('curso curso', 'curso.id_curso = curso_docente.id_curso', 'inner');
        $this->CI->db->join('grado grado', 'grado.id_grado = curso.id_grado', 'inner');
        $this->CI->db->where('curso_docente.id_anio_escolar', $id_anio_escolar);
        $this->CI->db->where('grado.id_nivel_educativo', self::ID_NIVEL_SECUNDARIA);
        $this->CI->db->order_by('grado.nombre', 'asc');
        $this->CI->db->order_by('curso.nombre', 'asc');
        return $this->CI->db->get()->result_array();
    }

    /* Verifica que el docente tenga asignado este curso en el mismo anio escolar del curso,
       antes de ejecutar el script de prediccion */
    public function docente_tiene_curso($id_usuario, $id_curso) {
        return $this->CI->db->from('curso_docente')
            ->join('curso curso', 'curso.id_curso = curso_docente.id_curso AND curso.id_anio_escolar = curso_docente.id_anio_escolar', 'inner')
            ->where('curso_docente.id_usuario', $id_usuario)
            ->where('curso_docente.id_curso', $id_curso)
            ->count_all_results() > 0;
    }

    /* Nombre, grado y anio escolar de un curso de secundaria (barra "Volver a la prediccion de...") */
    public function datos_curso($id_curso) {
        $this->CI->db->select("curso.id_curso, curso.nombre as nombre_curso, curso.id_anio_escolar,
            grado.nombre as nombre_grado, grado.seccion as seccion_grado");
        $this->CI->db->from('curso curso');
        $this->CI->db->join('grado grado', 'grado.id_grado = curso.id_grado', 'inner');
        $this->CI->db->where('curso.id_curso', $id_curso);
        $this->CI->db->where('grado.id_nivel_educativo', self::ID_NIVEL_SECUNDARIA);
        return $this->CI->db->get()->row_array();
    }

    /* Verifica que el alumno tenga notas registradas en este curso (enlace "Ver tendencia en Power BI") */
    public function alumno_en_curso($id_alumno, $id_curso) {
        return $this->CI->db->from('nota_alumno_secundaria')
            ->join('matricula matricula', 'matricula.id_matricula = nota_alumno_secundaria.id_matricula', 'inner')
            ->where('matricula.id_alumno', $id_alumno)
            ->where('nota_alumno_secundaria.id_curso', $id_curso)
            ->count_all_results() > 0;
    }

    /* Alumnos de secundaria matriculados en un anio escolar (campo "Alumno (opcional)" del modal
       "Generar por Grado / Aula", solo Super Admin), ordenados por apellido */
    public function alumnos_anio($id_anio_escolar) {
        $this->CI->db->select('matricula.id_alumno, matricula.id_grado, alumno.nombre, alumno.apellido_paterno, alumno.apellido_materno');
        $this->CI->db->from('matricula');
        $this->CI->db->join('alumno alumno', 'alumno.id_alumno = matricula.id_alumno', 'inner');
        $this->CI->db->where('matricula.id_anio_escolar', $id_anio_escolar);
        $this->CI->db->where('matricula.id_nivel_educativo', self::ID_NIVEL_SECUNDARIA);
        $this->CI->db->order_by('alumno.apellido_paterno', 'asc');
        $this->CI->db->order_by('alumno.apellido_materno', 'asc');
        $this->CI->db->order_by('alumno.nombre', 'asc');
        return $this->CI->db->get()->result_array();
    }

}
