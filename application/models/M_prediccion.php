<?php

require_once APPPATH . 'libraries/Modelo_DB.php';

class M_prediccion extends Modelo_DB {

    public function __construct() {
        parent::__construct();
        parent::setTabla('prediccion');
        parent::setAlias('prediccion');
        parent::setTabla_id('id_prediccion');
    }

    public function get_query() {
        $this->CI->db->select("prediccion.*");
        $this->CI->db->from($this->tabla);
        $this->CI->db->order_by("{$this->tabla}.id_prediccion", "asc");
    }


    /* ----------------------------------------------------------- */
    /* OBTENEMOS TODAS LAS NOTAS HISTORICAS POR MES DEL ALUMNO     */
    /* SEGUN SU ASISTENCIA, SE HACE UN CALCULO PARA SEGUN LOS      */
    /* DIAS DE ASISTENCIAS SOBRE EL TOTAL SE SAQUE UN PROMEDIO     */
    /* ----------------------------------------------------------- */
    public function historicoNotasAsistencias($idAlumno) {

        $sql = "SELECT AL.id_alumno                                                           AS id_alumno
                     , AE.nombre                                                              AS anio_escolar
                     , ME.nombre                                                              AS mes_escolar
                     , CONCAT(AL.nombre, ' ', AL.apellido_paterno, ' ', AL.apellido_materno)  AS nombre_alumno
                     , AA.asistencia                                                          AS total_asistencias
                     , ME.dias_asistencia                                                     AS dias_asistencia
                     , ROUND((AA.asistencia * 20) / ME.dias_asistencia, 2)                    AS promedio
                  FROM asistencia_alumno AS AA
                    INNER JOIN mes_escolar AS ME
                      ON AA.id_mes_escolar = ME.id_mes_escolar
                    INNER JOIN matricula AS MA
                      ON MA.id_matricula = AA.id_matricula
                    INNER JOIN alumno AS AL
                      ON MA.id_alumno = AL.id_alumno
                    INNER JOIN anio_escolar AS AE
                      ON AA.id_anio_escolar = AE.id_anio_escolar
                 WHERE AA.id_matricula IN(SELECT id_matricula
                                            FROM matricula     AS MA
                                              INNER JOIN alumno AS AL
                                                ON MA.id_alumno = AL.id_alumno
                                           WHERE MA.id_alumno = $idAlumno
                                         );";

        return $this->CI->db->query($sql)->result_array();
    }

    /* --------------------------------------------------------------- */
    /* OBTENEMOS LAS NOTAS HISTORICAS DE LA EVALUACION SOCIOECONOMICA  */
    /* DEL ALUMNO, LA NOTA ES POR CADA MATRICULA EN CADA AÑO ESCOLAR   */
    /* --------------------------------------------------------------- */
    public function historicoNotasSocioEconomico($idAlumno) {

        $sql = "SELECT SE.*
                  FROM socio_economico AS SE
                 WHERE id_matricula IN(SELECT id_matricula
                                         FROM matricula     AS MA
                                           INNER JOIN alumno AS AL
                                             ON MA.id_alumno = AL.id_alumno
                                        WHERE MA.id_alumno = $idAlumno
                                       );";

        return $this->CI->db->query($sql)->result_array();
    }

    /* ---------------------------------------------------------------- */
    /* OBTENEMOS LAS NOTAS ACADEMICAS HISTORICAS DEL ALUMNO EN PRIMARIA */
    /* Y SECUNDARIA                                                     */
    /* ---------------------------------------------------------------- */
    public function historicoNotasAcademicas($idAlumno) {

        $sql = "SELECT NP.*
                  FROM nota_alumno_primaria AS NP
                 WHERE id_matricula IN(SELECT id_matricula
                                         FROM matricula     AS MA
                                          INNER JOIN alumno AS AL
                                            ON MA.id_alumno = AL.id_alumno
                                        WHERE MA.id_alumno = $idAlumno
                                      )
                            
                UNION ALL
     
                SELECT NS.*
                  FROM nota_alumno_secundaria AS NS
                 WHERE id_matricula IN(SELECT id_matricula
                                         FROM matricula     AS MA
                                          INNER JOIN alumno AS AL
                                            ON MA.id_alumno = AL.id_alumno
                                        WHERE MA.id_alumno = $idAlumno
                                      );";

        return $this->CI->db->query($sql)->result_array();
    }
}
