-- =============================================================================
-- Tabla predicciones_riesgo
-- =============================================================================
-- Guarda el resultado mas reciente de la prediccion de rendimiento academico
-- (proximo bimestre) para cada combinacion alumno-curso-bimestre_objetivo,
-- generada por scripts/03_produccion/predecir_curso.py.
--
-- Terminologia (ver CLAUDE.md): esta tabla, como el resto del proyecto, usa
-- "riesgo" internamente (probabilidad_riesgo, en_riesgo_predicho,
-- clasificacion). La traduccion a "alerta" de tres niveles (Sin alerta /
-- Alerta moderada / Alerta alta) ocurre SOLO en la capa de presentacion
-- (intranet y Power BI), nunca aqui.
--
-- Entorno: este script fue disenado para ejecutarse contra la base de datos
-- LOCAL de XAMPP (root, sin contrasena) durante el desarrollo del modulo.
-- La seccion final (comentada) crea el usuario restringido modelo_predictivo
-- para el servidor real; no se ejecuta todavia.
-- =============================================================================

USE intranet_rd;

CREATE TABLE IF NOT EXISTS predicciones_riesgo (
    id_prediccion         INT(11)         NOT NULL AUTO_INCREMENT,

    -- A que alumno-curso-bimestre corresponde esta prediccion.
    -- id_matricula ya identifica alumno + anio_escolar + grado (ver tabla
    -- matricula); id_curso ya esta scopeado a un anio_escolar especifico
    -- (cada anio escolar tiene sus propias filas en la tabla curso), asi que
    -- (id_matricula, id_curso, bimestre_objetivo) identifica de forma unica
    -- la fila alumno-curso-bimestre_objetivo del diseno del dataset.
    id_matricula          INT(11)         NOT NULL,
    id_curso              INT(11)         NOT NULL,
    bimestre_objetivo     TINYINT(1)      NOT NULL COMMENT 'Solo 2, 3 o 4 (ver CLAUDE.md: el bimestre 1 nunca se predice)',

    -- Salida cruda del modelo (nombres internos, sin traducir a "alerta").
    probabilidad_riesgo   DECIMAL(5,4)    NOT NULL COMMENT 'Probabilidad de en_riesgo=1 segun el modelo, de 0.0000 a 1.0000',
    en_riesgo_predicho    TINYINT(1)      NOT NULL COMMENT 'Clase predicha por el modelo (0/1) a partir de probabilidad_riesgo',
    clasificacion         ENUM('sin_riesgo', 'riesgo_moderado', 'riesgo_alto') NOT NULL
                          COMMENT 'Derivada de probabilidad_riesgo (<33% / 33-66% / >66%). La intranet y Power BI la muestran como "Sin alerta" / "Alerta moderada" / "Alerta alta"',

    -- IA explicable: 2-3 factores principales de ESTA prediccion puntual,
    -- como frase corta (ver CLAUDE.md, seccion "Entrenamiento del modelo").
    factores_explicables  VARCHAR(500)    NOT NULL,

    fecha_prediccion      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id_prediccion),

    -- Una sola prediccion vigente por alumno-curso-bimestre_objetivo: si se
    -- vuelve a generar (INSERT ... ON DUPLICATE KEY UPDATE), se actualiza en
    -- vez de duplicarse.
    UNIQUE KEY uq_prediccion (id_matricula, id_curso, bimestre_objetivo),

    -- Para las paginas de Power BI "detalle por grado y curso".
    KEY idx_curso_bimestre (id_curso, bimestre_objetivo),

    CONSTRAINT fk_prediccion_matricula FOREIGN KEY (id_matricula) REFERENCES matricula (id_matricula),
    CONSTRAINT fk_prediccion_curso FOREIGN KEY (id_curso) REFERENCES curso (id_curso)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- =============================================================================
-- Usuario de escritura restringido para el SERVIDOR REAL (NO ejecutar aqui)
-- =============================================================================
-- Solo lectura sobre las tablas academicas usadas por el proyecto, y lectura +
-- escritura unicamente sobre predicciones_riesgo. Lo debe ejecutar el
-- administrador del colegio en el servidor real; en local (XAMPP) seguimos
-- usando root.
--
-- CREATE USER 'modelo_predictivo'@'localhost' IDENTIFIED BY 'CAMBIAR_ESTA_CONTRASENA';
--
-- GRANT SELECT ON intranet_rd.matricula                TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.nota_alumno_secundaria    TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.asistencia_alumno         TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.comportamiento            TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.curso                     TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.grado                     TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.bimestre                  TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.anio_escolar               TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.rango                     TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT (fecha_nacimiento, id_alumno) ON intranet_rd.alumno TO 'modelo_predictivo'@'localhost';
-- GRANT SELECT ON intranet_rd.curso_docente             TO 'modelo_predictivo'@'localhost';
--
-- GRANT SELECT, INSERT, UPDATE ON intranet_rd.predicciones_riesgo TO 'modelo_predictivo'@'localhost';
--
-- FLUSH PRIVILEGES;
