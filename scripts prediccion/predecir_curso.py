"""
Genera la prediccion de alerta academica (proximo bimestre) para todos los
alumnos de un curso, usando el modelo ya entrenado
(modelos/modelo_entrenado.joblib).

Recibe un solo parametro: --id_curso. No recibe una "seccion" aparte porque
en el esquema real cada id_curso ya pertenece a un unico id_grado (que a su
vez ya incluye la seccion) y a un unico anio_escolar (cada anio escolar tiene
sus propias filas en la tabla curso) -- id_curso identifica per se
curso+grado+seccion+anio.

Para cada alumno matriculado en el curso, calcula las variables de Nivel 1 y
Nivel 2 exactamente como en scripts/01_extraccion/construir_dataset_maestro.py
(misma correccion de fecha_nacimiento, mismos nombres de columnas), a partir
del bimestre anterior mas reciente que ya cerro. El bimestre_objetivo se
determina automaticamente por alumno: es el bimestre siguiente al ultimo
bimestre cerrado de ese alumno en ese curso (nunca el bimestre 1, nunca uno
ya cerrado).

n1_promedio, n2_promedio, n3_promedio y promedio_final NUNCA se leen crudos
de nota_alumno_secundaria: se reconstruyen desde las notas atomicas
(n1_s1..n1_s8, n2_s1..n2_s8, n3_rc1/co1/rc2/co2, examen_mensual,
examen_bimestral) con la MISMA logica de scripts/comun/calidad_academica.py
que usa construir_dataset_maestro.py para el dataset de entrenamiento --
incluyendo el filtro de escala 0-20 sobre cada nota atomica y el respaldo
historico de n3 cuando no hay desglose semanal. "Bimestre cerrado" tambien
se determina por promedio_final_reconstruido (no el crudo). Esto evita que
produccion reciba features fuera de la distribucion con la que se entreno
el modelo (ver diagnostico de inconsistencia entrenamiento/produccion,
2026-08).

Guarda el resultado en predicciones_riesgo (INSERT ... ON DUPLICATE KEY
UPDATE) e imprime un JSON con el detalle de cada alumno.

Este script SI escribe en la base de datos (a diferencia del script de
extraccion), pero solo en predicciones_riesgo: nunca hace INSERT/UPDATE/
DELETE sobre las tablas academicas originales.
"""
import argparse
import json
import math
import os
import sys
import warnings
from datetime import datetime

import joblib
import numpy as np
import pandas as pd
import mysql.connector
import shap

warnings.filterwarnings("ignore", message="pandas only supports SQLAlchemy")

# La consola de Windows no siempre usa UTF-8 por defecto; forzarlo evita que
# falle al imprimir nombres de curso/grado con tildes (p. ej. cuando PHP
# invoque este script con exec() en el servidor real).
sys.stdout.reconfigure(encoding="utf-8")

BASE_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
RUTA_MODELO = os.path.join(BASE_DIR, "modelos", "modelo_entrenado.joblib")
RUTA_COLUMNAS = os.path.join(BASE_DIR, "modelos", "columnas_features.joblib")
RUTA_DATASET_REFERENCIA = os.path.join(BASE_DIR, "outputs", "dataset_maestro.csv")

_SCRIPTS_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)
from comun.calidad_academica import (  # noqa: E402
    calcular_edad_relativa_meses,
    corregir_fecha_nacimiento,
    limpiar_escala,
    reconstruir_notas_bimestre,
    COLUMNAS_N1_SEMANAL,
    COLUMNAS_N2_SEMANAL,
    COLUMNAS_N3_DETALLE,
)

# Conexion LOCAL de desarrollo (XAMPP). Para el servidor real, cambiar por
# las credenciales del usuario restringido modelo_predictivo (ver
# scripts/03_produccion/01_crear_tabla_prediccion.sql) -- nunca usar root ahi.
DB_HOST = "localhost"
DB_USER = "root"
DB_PASSWORD = ""
DB_NAME = "intranet_rd"

MES_A_BIMESTRE = {
    3: 1, 4: 1,
    5: 2, 6: 2, 7: 2,
    8: 3, 9: 3,
    10: 4, 11: 4, 12: 4,
}

UMBRAL_MODERADA = 0.33
UMBRAL_ALTA = 0.66

# Cada variable explicable define:
#   texto     -> sujeto de la frase que ve el docente (ver redactar_factor):
#                para notas, "Su nota de ..." (se completa con "del bimestre
#                anterior fue X/20, más baja/alta que la de la mayoría de sus
#                compañeros"); para conteos, el par singular/plural
#                ("falta", "faltas"). La asistencia real se redacta aparte.
#   categoria -> "academico" | "asistencia" | "conducta" -- unicas 3
#                categorias PEDAGOGICAMENTE ACCIONABLES para un docente (ver
#                MAX_FACTORES_POR_CATEGORIA en seleccionar_factores)
#   direccion -> "menor_es_peor" | "mayor_es_peor". Se usa UNICAMENTE para el
#                filtro de doble condicion (ver cumple_condicion_objetiva):
#                confirma, comparando contra la mediana historica, que el
#                valor observado es objetivamente malo (modo "riesgo") o
#                bueno (modo "protector") para esa variable especifica -- no
#                se usa para puntuar (eso lo hace SHAP).
#   icono     -> icono FontAwesome 5 solido, solo para la vista de la intranet
#
# Variables de entrada del modelo que NUNCA se muestran como factor (siguen
# siendo columnas de entrada, con su propio valor SHAP, pero no estan en
# FACTORES_EXPLICABLES, asi que no pueden aparecer como factor visible):
# - edad_relativa_meses y origen_num_bimestres_previos: no sugieren ninguna
#   intervencion docente ("edad relativa: 142 meses").
# - origen_ultimo_promedio_final, origen_promedio_historico_final y
#   origen_tendencia (decision de presentacion de la autora, 2026-10-28): son
#   resumenes de las mismas notas. "Promedio histórico: 11/20" solo repite que
#   el alumno tiene notas bajas, sin decirle al docente por donde intervenir;
#   al excluirlos, la explicacion muestra la nota concreta (participacion,
#   cuaderno, examen...), la asistencia, las faltas o la conducta que SHAP
#   identifica como relevantes. Excepcion (2026-11-10): origen_tendencia y
#   origen_ultimo_promedio_final pueden mostrarse como plan B, solo si un
#   alumno en alerta se queda sin factores (ver FACTORES_RESPALDO).
FACTORES_EXPLICABLES = {
    "origen_ultimo_n1_promedio": {
        "texto": "Su nota de Participación y Tareas", "direccion": "menor_es_peor",
        "categoria": "academico", "icono": "fa-tasks", "es_nota_veinte": True,
    },
    "origen_ultimo_n2_promedio": {
        # Nota subjetiva que el docente asigna en el concepto "Asistencia y
        # Practicas Diarias" del curso -- distinta de la asistencia REAL de
        # asistencia_alumno (ver CLAUDE.md). La frase dice "Su nota de...",
        # y la asistencia real se redacta en dias ("Asistió X de Y días"),
        # para que nunca se confundan.
        "texto": "Su nota de Asistencia y Prácticas Diarias", "direccion": "menor_es_peor",
        "categoria": "academico", "icono": "fa-book-reader", "es_nota_veinte": True,
    },
    "origen_ultimo_n3_promedio": {
        # Nota de ESTE curso y docente, distinta de "Su nota de conducta
        # (tutoría)" mas abajo (nota_conducta, de toda la tutoria/bimestre).
        "texto": "Su nota de Revisión de Cuaderno y Conducta", "direccion": "menor_es_peor",
        "categoria": "academico", "icono": "fa-book", "es_nota_veinte": True,
    },
    "origen_ultimo_examen_mensual": {
        "texto": "Su nota en el examen mensual", "direccion": "menor_es_peor",
        "categoria": "academico", "icono": "fa-file-alt", "es_nota_veinte": True,
    },
    "origen_ultimo_examen_bimestral": {
        "texto": "Su nota en el examen bimestral", "direccion": "menor_es_peor",
        "categoria": "academico", "icono": "fa-file-signature", "es_nota_veinte": True,
    },
    "origen_ultimo_total_dias_asistencia": {
        # Asistencia REAL (asistencia_alumno), no la nota n2. Se redacta en
        # dias sobre los dias de clase del alumno ("Asistió 13 de 35 días"),
        # no en porcentaje (decision de la autora: un porcentaje es menos
        # claro para un docente). Ver calcular_asistencia_bimestre_anterior.
        "texto": None, "direccion": "menor_es_peor",
        "categoria": "asistencia", "icono": "fa-calendar-times", "es_nota_veinte": False,
    },
    "origen_asistencia_historica_promedio": {
        "texto": None, "direccion": "menor_es_peor",
        "categoria": "asistencia", "icono": "fa-calendar-times", "es_nota_veinte": False,
    },
    "origen_ultimo_total_falta": {
        # Mas faltas/tardanzas = desfavorable, menos = favorable (mayor_es_peor).
        "texto": ("falta", "faltas"), "direccion": "mayor_es_peor",
        "categoria": "asistencia", "icono": "fa-user-times", "es_nota_veinte": False,
    },
    "origen_ultimo_total_tardanza": {
        "texto": ("tardanza", "tardanzas"), "direccion": "mayor_es_peor",
        "categoria": "asistencia", "icono": "fa-user-clock", "es_nota_veinte": False,
    },
    "origen_ultimo_total_falta_justificada": {
        "texto": ("falta justificada", "faltas justificadas"), "direccion": "mayor_es_peor",
        "categoria": "asistencia", "icono": "fa-file-medical", "es_nota_veinte": False,
    },
    "origen_ultimo_total_tardanza_justificada": {
        "texto": ("tardanza justificada", "tardanzas justificadas"), "direccion": "mayor_es_peor",
        "categoria": "asistencia", "icono": "fa-file-medical", "es_nota_veinte": False,
    },
    "origen_ultimo_nota_conducta": {
        "texto": "Su nota de conducta (tutoría)", "direccion": "menor_es_peor",
        "categoria": "conducta", "icono": "fa-handshake", "es_nota_veinte": True,
    },
}

# Maximo de factores visibles por categoria (y 3 en total). Ya sin los
# promedios agregados, dos notas academicas distintas (ej. participacion y
# cuaderno) o dos datos de asistencia distintos (ej. faltas y tardanzas) no
# repiten la misma idea, y le dicen al docente por donde intervenir.
MAX_FACTORES_POR_CATEGORIA = {"academico": 2, "asistencia": 2, "conducta": 1}

# Estas dos variables cuentan lo mismo (dias asistidos: el ultimo bimestre y
# el promedio historico); nunca se muestran juntas.
VARIABLES_DIAS_ASISTIDOS = {"origen_ultimo_total_dias_asistencia", "origen_asistencia_historica_promedio"}

TITULO_FACTORES = "Principales factores de esta predicción"

# Orden de prioridad entre categorias cuando dos tienen el mismo valor SHAP
# (empate exacto, poco frecuente pero posible con floats): desempata a favor
# de la categoria pedagogicamente mas accionable primero.
ORDEN_CATEGORIAS = ["academico", "asistencia", "conducta"]

# --- Filtro de PRESENTACION (no de modelo) ---------------------------------
# Umbral de relevancia: un factor solo se muestra si su peso relativo dentro
# del grupo de candidatos (SHAP en la direccion del modo, con el limite por
# categoria de MAX_FACTORES_POR_CATEGORIA) es >= a esto.
# Esto es puramente de presentacion: no cambia el modelo, no cambia la
# probabilidad ni la clasificacion de riesgo/alerta, solo decide que factor
# vale la pena explicarle a un docente. Sin este filtro pueden aparecer
# factores como "Tardanzas: 0 (2% de influencia)" -- matematicamente validos
# (SHAP en arboles puede asignarle una contribucion pequeña y positiva a una
# variable aunque su valor sea igual a la mediana, por interacciones con
# otras variables) pero confusos para alguien sin formacion en ML.
UMBRAL_RELEVANCIA_FACTOR_PCT = 5.0

# Validacion final de COHERENCIA PEDAGOGICA (tambien puramente de
# presentacion): segun la tabla `rango` de CLAUDE.md (AD=17-20, A=13-16,
# B=11-12, C=0-10), una nota 0-20 (es_nota_veinte=True en
# FACTORES_EXPLICABLES) solo puede mostrarse como factor DESFAVORABLE de
# una alerta si es 13 o menos: B o C (0-12), o 13, el limite inferior de
# A. Una nota de 14 a 20 nunca se muestra como factor negativo, aunque su
# SHAP sea positivo y este por debajo de la mediana historica -- en un
# colegio donde la mayoria saca notas muy altas, la mediana puede ser 18
# o 19, y marcar un 17/20 (AD) como "factor desfavorable" solo porque esta
# "bajo la mediana" no tiene sentido pedagogico para un docente. El 13 si
# se muestra (decision de la autora, 2026-11-10: hasta entonces el corte
# era 12, y un 13 con el mayor SHAP del alumno quedaba oculto) y se
# redacta como "en el limite inferior del logro" (ver redactar_factor).
# Esta validacion NO aplica en modo protector (una nota A/AD si puede
# mostrarse como factor favorable).
NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE = 13

# Conteos que la frase de "dias asistidos del bimestre anterior" ya
# desglosa ("de los 12 dias restantes, 9 fueron faltas justificadas y 3
# sin justificar"). Si esa frase se muestra, estos conteos no se muestran
# aparte (decision de la autora, 2026-11-10: el dato se repetia); su peso
# SHAP se suma al de los dias asistidos y el espacio libre lo ocupa el
# siguiente factor que cumpla las reglas.
VARIABLES_EN_DESGLOSE_ASISTENCIA = [
    "origen_ultimo_total_falta_justificada",
    "origen_ultimo_total_falta",
    "origen_ultimo_total_tardanza",
    "origen_ultimo_total_tardanza_justificada",
]

# --- Plan B (decision de la autora, 2026-11-10; solo presentacion) ---------
# Si un alumno EN ALERTA se queda sin factores visibles, se usa como
# respaldo UNO de estos dos resumenes (normalmente ocultos, ver arriba), el
# de mayor SHAP, solo si sube la alerta (SHAP > 0) y su valor confirma la
# direccion frente a la mediana historica:
# - origen_tendencia = promedio del curso en el bimestre anterior menos el
#   promedio de los bimestres cerrados de antes (al predecir el bimestre 3:
#   B2 - B1; el 4: B3 - promedio(B1, B2); el 2 no tiene). -3 = su ultimo
#   promedio quedo 3 puntos por debajo del de antes. Ademas debe ser
#   negativa, para que "bajaron" sea cierto.
# - origen_ultimo_promedio_final: ademas debe ser 13 o menos
#   (NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE, misma regla que las notas).
# Si ninguno cumple, se muestra MENSAJE_ALERTA_SIN_FACTORES. Sin sugerencia
# de seguimiento (misma decision que los factores normales). La edad y el
# numero de bimestres previos siguen ocultos siempre.
FACTORES_RESPALDO = {
    "origen_tendencia": {"direccion": "menor_es_peor", "categoria": "academico", "icono": "fa-chart-line"},
    "origen_ultimo_promedio_final": {"direccion": "menor_es_peor", "categoria": "academico", "icono": "fa-clipboard-list"},
}
MENSAJE_ALERTA_SIN_FACTORES = (
    "Esta alerta se debe a la combinación de varios aspectos de su desempeño, ninguno destacado por sí solo. "
    "Se sugiere revisar su avance general en el curso y conversar con el estudiante."
)

# ---------------------------------------------------------------------------
# Conexion
# ---------------------------------------------------------------------------

def conectar():
    return mysql.connector.connect(
        host=DB_HOST, user=DB_USER, password=DB_PASSWORD, database=DB_NAME, charset="latin1",
    )


# ---------------------------------------------------------------------------
# Extraccion (solo lectura sobre tablas academicas)
# ---------------------------------------------------------------------------

def obtener_curso(conn, id_curso):
    query = """
        SELECT c.id_curso, c.nombre AS nombre_curso, c.id_grado, c.id_anio_escolar,
               g.nombre AS nombre_grado
        FROM curso c
        JOIN grado g ON g.id_grado = c.id_grado
        WHERE c.id_curso = %s
    """
    df = pd.read_sql(query, conn, params=(id_curso,))
    if df.empty:
        return None
    return df.iloc[0].to_dict()


def obtener_anio_calendario(conn, id_anio_escolar):
    query = "SELECT nombre FROM anio_escolar WHERE id_anio_escolar = %s"
    df = pd.read_sql(query, conn, params=(id_anio_escolar,))
    if df.empty:
        return None
    return int(df.iloc[0]["nombre"])


def obtener_notas_curso(conn, id_curso):
    columnas_detalle = ", ".join(
        f"n.{c}" for c in COLUMNAS_N1_SEMANAL + COLUMNAS_N2_SEMANAL + COLUMNAS_N3_DETALLE
    )
    query = f"""
        SELECT n.id_matricula, m.id_alumno, n.id_bimestre,
               n.n1_promedio, n.n2_promedio, n.n3_promedio,
               n.examen_mensual, n.examen_bimestral, n.promedio_final,
               {columnas_detalle}
        FROM nota_alumno_secundaria n
        JOIN matricula m ON m.id_matricula = n.id_matricula
        WHERE n.id_curso = %s
    """
    return pd.read_sql(query, conn, params=(id_curso,))


def obtener_asistencia(conn, matriculas):
    if not matriculas:
        return pd.DataFrame(columns=["id_matricula", "id_mes_escolar", "asistencia", "falta", "tardanza",
                                      "falta_justificada", "tardanza_justificada"])
    placeholders = ",".join(["%s"] * len(matriculas))
    query = f"""
        SELECT id_matricula, id_mes_escolar, asistencia, falta, tardanza,
               falta_justificada, tardanza_justificada
        FROM asistencia_alumno
        WHERE id_matricula IN ({placeholders})
    """
    return pd.read_sql(query, conn, params=tuple(matriculas))


def obtener_conducta(conn, matriculas):
    if not matriculas:
        return pd.DataFrame(columns=["id_matricula", "id_bimestre", "nota_conducta"])
    placeholders = ",".join(["%s"] * len(matriculas))
    query = f"""
        SELECT id_matricula, id_bimestre, nota AS nota_conducta
        FROM comportamiento
        WHERE id_matricula IN ({placeholders})
    """
    return pd.read_sql(query, conn, params=tuple(matriculas))


def obtener_fechas_nacimiento(conn, ids_alumno):
    if not ids_alumno:
        return {}
    placeholders = ",".join(["%s"] * len(ids_alumno))
    query = f"SELECT id_alumno, fecha_nacimiento FROM alumno WHERE id_alumno IN ({placeholders})"
    df = pd.read_sql(query, conn, params=tuple(ids_alumno))
    df["fecha_nacimiento"] = df["fecha_nacimiento"].apply(corregir_fecha_nacimiento)
    return dict(zip(df["id_alumno"], df["fecha_nacimiento"]))


# ---------------------------------------------------------------------------
# Limpieza y calculos (replican construir_dataset_maestro.py via
# scripts/comun/calidad_academica.py -- limpiar_escala y
# reconstruir_notas_bimestre importados arriba)
# ---------------------------------------------------------------------------

# corregir_fecha_nacimiento y calcular_edad_relativa_meses (con el rango
# valido 130-245 meses) se importan de scripts/comun/calidad_academica.py,
# compartidas con construir_dataset_maestro.py.


def agregar_asistencia_por_bimestre(asistencia):
    df = asistencia.copy()
    df["id_bimestre"] = df["id_mes_escolar"].map(MES_A_BIMESTRE)
    df = df.dropna(subset=["id_bimestre"])

    def suma_min_count(serie):
        return serie.sum(min_count=1)

    return df.groupby(["id_matricula", "id_bimestre"], as_index=False).agg(
        total_dias_asistencia=("asistencia", suma_min_count),
        total_falta=("falta", suma_min_count),
        total_tardanza=("tardanza", suma_min_count),
        total_falta_justificada=("falta_justificada", suma_min_count),
        total_tardanza_justificada=("tardanza_justificada", suma_min_count),
    )


def determinar_bimestre_objetivo(bimestres_cerrados):
    """Bimestre siguiente al ultimo cerrado. None si no hay ningun bimestre
    cerrado o si ya se cerraron los 4 (nada que predecir)."""
    if not bimestres_cerrados:
        return None
    ultimo_cerrado = max(bimestres_cerrados)
    objetivo = ultimo_cerrado + 1
    if objetivo not in (2, 3, 4):
        return None
    return objetivo


def construir_features_alumno(notas_alumno, asistencia_map, conducta_map, id_matricula,
                               edad_relativa_meses, bimestre_forzado=None):
    """notas_alumno: DataFrame con las filas de nota_alumno_secundaria de un
    alumno en este curso (todas las que tenga, cualquier bimestre), YA con
    las columnas n1_reconstruido/n2_reconstruido/n3_reconstruido/
    promedio_final_reconstruido agregadas por reconstruir_notas_bimestre()
    (scripts/comun/calidad_academica.py) -- ver llamada en main().

    "Bimestre cerrado" se determina por promedio_final_reconstruido, NUNCA
    por el promedio_final crudo almacenado (que puede contener 0 artificial,
    ver docstring del modulo).

    bimestre_forzado (2026-11-11, selector "Bimestre a predecir" de la intranet):
    si se indica (2, 3 o 4), se predice ESE bimestre para todos. El alumno es
    apto solo si tiene cerrado el bimestre inmediato anterior (el que alimenta el
    Nivel 1); si no, devuelve None y se excluye. Solo se usan bimestres
    ANTERIORES al objetivo (los posteriores, aunque tengan notas, se ignoran:
    el modelo nunca "ve el futuro"). Las variables se calculan con exactamente
    la misma logica que el modo automatico; sin bimestre_forzado el
    comportamiento es identico al anterior (bimestre siguiente al ultimo cerrado)."""
    notas_alumno = notas_alumno.set_index("id_bimestre")
    bimestres_cerrados = [
        b for b in notas_alumno.index if pd.notna(notas_alumno.loc[b, "promedio_final_reconstruido"])
    ]

    if bimestre_forzado is None:
        objetivo = determinar_bimestre_objetivo(bimestres_cerrados)
    else:
        objetivo = bimestre_forzado if (bimestre_forzado - 1) in bimestres_cerrados else None
    if objetivo is None:
        return None, bimestres_cerrados

    ultimo = objetivo - 1
    fila_ultimo = notas_alumno.loc[ultimo]

    asis_ultimo = asistencia_map.get((id_matricula, ultimo))
    nota_conducta_ultimo = limpiar_escala(conducta_map.get((id_matricula, ultimo)))

    anteriores = [b for b in range(1, ultimo) if b in bimestres_cerrados]
    pf_anteriores = [notas_alumno.loc[b, "promedio_final_reconstruido"] for b in anteriores]

    asistencia_historica_vals = []
    if asis_ultimo is not None and not pd.isna(asis_ultimo["total_dias_asistencia"]):
        asistencia_historica_vals.append(asis_ultimo["total_dias_asistencia"])
    for b in anteriores:
        asis_b = asistencia_map.get((id_matricula, b))
        if asis_b is not None and not pd.isna(asis_b["total_dias_asistencia"]):
            asistencia_historica_vals.append(asis_b["total_dias_asistencia"])

    pf_ultimo = fila_ultimo["promedio_final_reconstruido"]
    if pf_anteriores:
        tendencia = pf_ultimo - (sum(pf_anteriores) / len(pf_anteriores))
    else:
        tendencia = np.nan

    todos_pf = [pf_ultimo] + pf_anteriores
    promedio_historico_final = sum(todos_pf) / len(todos_pf)
    asistencia_historica_promedio = (
        sum(asistencia_historica_vals) / len(asistencia_historica_vals) if asistencia_historica_vals else np.nan
    )

    features = {
        "origen_ultimo_n1_promedio": fila_ultimo["n1_reconstruido"],
        "origen_ultimo_n2_promedio": fila_ultimo["n2_reconstruido"],
        "origen_ultimo_n3_promedio": fila_ultimo["n3_reconstruido"],
        "origen_ultimo_examen_mensual": limpiar_escala(fila_ultimo["examen_mensual"]),
        "origen_ultimo_examen_bimestral": limpiar_escala(fila_ultimo["examen_bimestral"]),
        "origen_ultimo_promedio_final": pf_ultimo,
        "origen_ultimo_nota_conducta": nota_conducta_ultimo,
        "origen_ultimo_total_dias_asistencia": asis_ultimo["total_dias_asistencia"] if asis_ultimo else np.nan,
        "origen_ultimo_total_falta": asis_ultimo["total_falta"] if asis_ultimo else np.nan,
        "origen_ultimo_total_tardanza": asis_ultimo["total_tardanza"] if asis_ultimo else np.nan,
        "origen_ultimo_total_falta_justificada": asis_ultimo["total_falta_justificada"] if asis_ultimo else np.nan,
        "origen_ultimo_total_tardanza_justificada": asis_ultimo["total_tardanza_justificada"] if asis_ultimo else np.nan,
        "origen_promedio_historico_final": promedio_historico_final,
        "origen_tendencia": tendencia,
        "origen_asistencia_historica_promedio": asistencia_historica_promedio,
        "origen_num_bimestres_previos": len(todos_pf),
        "edad_relativa_meses": edad_relativa_meses,
    }
    return {"objetivo": objetivo, "features": features}, bimestres_cerrados


def calcular_asistencia_bimestre_anterior(features):
    """Devuelve (dias_asistidos, dias_lectivos, porcentaje) del bimestre
    anterior de un alumno, o (None, None, None) si falta algun dato.

    dias_lectivos NO es un numero inventado ni tomado de un calendario
    generico de fechas de bimestre: es la suma de los 5 conteos que ya se
    calculan para el modelo (Nivel 1) -- total_dias_asistencia + total_falta
    + total_tardanza + total_falta_justificada + total_tardanza_justificada.
    Se verifico contra los datos reales de asistencia_alumno (que registra
    un codigo por cada dia del mes: 'A', 'F', 'T', 'F.J.', 'T.J.' o 'NA'
    para dias no lectivos) que estas 5 categorias son EXHAUSTIVAS y
    mutuamente excluyentes sobre los dias no-'NA': no existe ningun otro
    codigo en la tabla. Por eso su suma reproduce exactamente cuantos dias
    lectivos se registraron para ESE alumno en ESE bimestre -- puede variar
    entre alumnos de la misma seccion (matricula tardia, traslados, etc.),
    lo cual es correcto: es el denominador real de ese alumno, no uno
    generico del calendario escolar."""
    componentes = [
        features.get("origen_ultimo_total_dias_asistencia"),
        features.get("origen_ultimo_total_falta"),
        features.get("origen_ultimo_total_tardanza"),
        features.get("origen_ultimo_total_falta_justificada"),
        features.get("origen_ultimo_total_tardanza_justificada"),
    ]
    if any(c is None or pd.isna(c) for c in componentes):
        return None, None, None

    dias_asistidos = componentes[0]
    dias_lectivos = sum(componentes)
    if dias_lectivos <= 0:
        return dias_asistidos, dias_lectivos, None
    porcentaje = round(dias_asistidos / dias_lectivos * 100)
    return dias_asistidos, dias_lectivos, porcentaje


# ---------------------------------------------------------------------------
# IA explicable
# ---------------------------------------------------------------------------

def construir_explicador_shap(pipeline):
    """Crea el TreeExplainer de SHAP a partir del RandomForestClassifier ya
    entrenado dentro del pipeline. Se construye UNA sola vez por corrida del
    script (no por alumno): TreeExplainer solo depende de la estructura de
    los arboles ya ajustados, no de los datos de cada prediccion puntual.

    Por que SHAP y no la comparacion contra la mediana (enfoque anterior):
    el metodo anterior ordenaba candidatos multiplicando feature_importances_
    (una importancia GLOBAL, fija para todo el dataset) por que tan lejos
    estaba el valor de la mediana. Esto tiene dos problemas: (1) mezcla dos
    cosas distintas -- "que tan importante es esta variable en general" con
    "que tan raro es este valor puntual" -- sin ninguna garantia matematica
    de que el resultado refleje el aporte real de esa variable a ESTA
    prediccion; y (2) como varias notas academicas (n1, n2, n3, examen
    mensual, examen bimestral, promedio final, promedio historico) suelen
    moverse juntas en un alumno con bajo rendimiento, el ranking se llenaba
    de factores redundantes que repetian la misma idea ("las notas estan
    bajas") en vez de dar una explicacion variada.

    SHAP (SHapley Additive exPlanations, Lundberg & Lee 2017) resuelve el
    problema (1): calcula, para una prediccion puntual, cuanto aporto cada
    variable a esa probabilidad especifica, con la garantia matematica
    (valores de Shapley, teoria de juegos cooperativos) de que la suma de
    los aportes de las 17 variables mas el valor base del modelo reproduce
    exactamente la probabilidad predicha (ver verificacion de aditividad
    hecha antes de integrar esto: expected_value + sum(shap) == predict_proba).
    TreeExplainer, ademas, lee directamente la estructura de los arboles del
    Random Forest ya entrenado (no aproxima con un modelo lineal externo),
    asi que refleja relaciones no lineales que el modelo realmente aprendio,
    no una direccion "menor_es_peor" fija que alguien asumio de antemano.

    El problema (2) -- redundancia entre variables correlacionadas -- SHAP
    por si solo no lo resuelve (una variable importante para el arbol puede
    seguir arrastrando a sus correlacionadas). Por eso se combina con el
    agrupamiento por categoria pedagogica en seleccionar_factores (ver
    MAX_FACTORES_POR_CATEGORIA) y con la exclusion de los promedios
    agregados de FACTORES_EXPLICABLES, que solo resumian las mismas notas.
    """
    return shap.TreeExplainer(pipeline.named_steps["clasificador"])


def calcular_shap_fila(explainer, pipeline, columnas_features, features):
    """Devuelve un dict {feature: valor_shap} para la clase en_riesgo=1 de
    UNA fila. TreeExplainer necesita los datos tal como los ve el
    clasificador (ya imputados), por eso se pasa por el imputador del
    pipeline antes de calcular SHAP -- el imputador no aporta contribucion
    propia, es preprocesamiento, asi que no aparece como "factor"."""
    X = pd.DataFrame([features])[columnas_features]
    X_imputado = pipeline.named_steps["imputador"].transform(X)
    shap_values = explainer.shap_values(X_imputado)  # shape: (1, n_features, n_clases)
    shap_clase_riesgo = shap_values[0, :, 1]
    return dict(zip(columnas_features, shap_clase_riesgo))


def cargar_estadisticas_referencia(columnas_features):
    """Mediana de cada variable en el dataset de referencia (bimestres ya
    cerrados, mismo que se uso para entrenar). Se usa UNICAMENTE como la
    segunda condicion del filtro de doble condicion (ver
    cumple_condicion_objetiva) -- confirma objetivamente que un valor es
    malo/bueno frente a la historia del colegio, no solo frente a lo que el
    arbol considero relevante para ESTE alumno puntual."""
    df_ref = pd.read_csv(RUTA_DATASET_REFERENCIA)
    return df_ref[columnas_features].median()


def cumple_condicion_objetiva(meta, valor, mediana, modo):
    """Segunda condicion del filtro de doble condicion. Un valor SHAP
    relevante (primera condicion, ver seleccionar_factores) no es
    suficiente por si solo: el arbol puede asignarle contribucion a una
    variable por INTERACCIONES con otras (ej. "Asistencia y Practicas
    Diarias: 13/20" en un alumno con promedio 16/20, o "1 falta
    justificada" con 89% de asistencia real) sin que el valor en si luzca
    objetivamente malo/bueno para un docente. Esta funcion confirma esa
    intuicion comparando el valor real contra la mediana historica de esa
    misma variable, respetando su direccion (menor_es_peor / mayor_es_peor):

    - modo "riesgo": el valor debe estar del lado MALO de la mediana.
    - modo "protector": el valor debe estar del lado BUENO de la mediana.

    Un valor exactamente igual a la mediana no cumple ninguna de las dos
    (ni objetivamente malo ni objetivamente bueno) -- se descarta. Sin
    mediana de referencia (columna sin datos suficientes) tampoco se puede
    confirmar objetivamente, asi que tambien se descarta: es mas seguro
    pedagogicamente omitir un factor que mostrar uno sin verificar."""
    if mediana is None or pd.isna(mediana):
        return False
    peor_si_menor = meta["direccion"] == "menor_es_peor"
    if modo == "riesgo":
        return valor < mediana if peor_si_menor else valor > mediana
    return valor > mediana if peor_si_menor else valor < mediana


def seleccionar_factores(features, shap_fila, medianas, modo, max_factores=3,
                          umbral_relevancia_pct=UMBRAL_RELEVANCIA_FACTOR_PCT):
    """Agrupa las variables en categorias pedagogicas (academico, asistencia,
    conducta -- ver 'categoria' en FACTORES_EXPLICABLES; las variables que no
    estan en FACTORES_EXPLICABLES nunca participan aqui) y elige las de mayor
    contribucion SHAP en la direccion de "modo", con un maximo por categoria
    (MAX_FACTORES_POR_CATEGORIA) y sin repetir los dias asistidos
    (VARIABLES_DIAS_ASISTIDOS):

    - modo "riesgo" (alerta_alta / riesgo_moderado): SHAP positivo hacia
      en_riesgo=1 (empuja la probabilidad hacia arriba).
    - modo "protector" (sin_riesgo): SHAP negativo (empuja la probabilidad
      hacia abajo, aleja al alumno de la alerta) -- se usa su magnitud
      (valor absoluto) para ordenar/ponderar.

    FILTRO DE DOBLE CONDICION: un candidato solo se queda si ademas de tener
    SHAP relevante, su valor real confirma objetivamente esa direccion
    frente a la mediana historica (ver cumple_condicion_objetiva). Luego,
    entre los que sobreviven ambas condiciones, se aplica el FILTRO DE
    PRESENTACION por umbral (ver UMBRAL_RELEVANCIA_FACTOR_PCT): se calcula
    el peso relativo de cada uno dentro de este grupo y se descartan los que
    no llegan al umbral. Ademas, en modo "riesgo" se aplica una validacion
    final de COHERENCIA PEDAGOGICA sobre notas 0-20 (ver
    NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE): una nota de 14 a 20 nunca se
    muestra como factor desfavorable, sin importar mediana o SHAP.

    FUSION DE ASISTENCIA: si los dias asistidos del bimestre anterior son
    candidatos, las faltas/tardanzas (VARIABLES_EN_DESGLOSE_ASISTENCIA) no
    compiten aparte: su peso se suma al de los dias asistidos, porque la
    frase de asistencia ya las desglosa. Si aun asi los dias asistidos no
    quedan en la seleccion final, se elige de nuevo sin fusionar, para no
    perder esos conteos. Ninguno de estos
    filtros cambia el modelo, la probabilidad ni la clasificacion de alerta
    -- solo deciden que factor vale la pena explicarle a un docente. No se
    rellena artificialmente hasta max_factores: si solo 1 candidato
    sobrevive, se devuelve solo 1."""
    validos = []
    for feat, meta in FACTORES_EXPLICABLES.items():
        valor = features.get(feat)
        if valor is None or pd.isna(valor):
            continue  # sin dato real que mostrar, aunque el imputado tenga SHAP

        shap_val = shap_fila.get(feat)
        if shap_val is None:
            continue
        if modo == "riesgo":
            if shap_val <= 0:
                continue  # no empuja hacia la alerta en esta prediccion puntual
            peso = shap_val
        else:
            if shap_val >= 0:
                continue  # no aleja de la alerta en esta prediccion puntual
            peso = -shap_val

        if not cumple_condicion_objetiva(meta, valor, medianas.get(feat), modo):
            continue  # primera/segunda condicion cumplidas, pero el valor no luce objetivamente malo/bueno

        # Validacion final de COHERENCIA PEDAGOGICA (solo modo "riesgo", solo
        # notas 0-20): una nota A o AD (13-20, tabla `rango`) nunca se
        # muestra como factor DESFAVORABLE, aunque su SHAP sea positivo y
        # este por debajo de la mediana historica del colegio. Ver
        # NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE.
        if modo == "riesgo" and meta.get("es_nota_veinte") and valor > NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE:
            continue

        validos.append((peso, feat, meta, valor))

    dias = "origen_ultimo_total_dias_asistencia"
    if any(c[1] == dias for c in validos):
        en_desglose = [c for c in validos if c[1] in VARIABLES_EN_DESGLOSE_ASISTENCIA]
        if en_desglose:
            peso_extra = sum(c[0] for c in en_desglose)
            fusionados = [(c[0] + peso_extra, *c[1:]) if c[1] == dias else c
                          for c in validos if c[1] not in VARIABLES_EN_DESGLOSE_ASISTENCIA]
            seleccion = _elegir_factores(fusionados, max_factores, umbral_relevancia_pct)
            if any(c[1] == dias for c in seleccion):
                return seleccion
    return _elegir_factores(validos, max_factores, umbral_relevancia_pct)


def _elegir_factores(validos, max_factores, umbral_relevancia_pct):
    """Ordena por peso SHAP y aplica los limites por categoria, la exclusion
    de VARIABLES_DIAS_ASISTIDOS y el umbral de relevancia (ver
    seleccionar_factores)."""
    validos = sorted(validos, key=lambda c: (c[0], -ORDEN_CATEGORIAS.index(c[2]["categoria"])), reverse=True)
    candidatos = []
    usados_por_categoria = {c: 0 for c in MAX_FACTORES_POR_CATEGORIA}
    for c in validos:
        feat, categoria = c[1], c[2]["categoria"]
        if len(candidatos) == max_factores:
            break
        if usados_por_categoria[categoria] >= MAX_FACTORES_POR_CATEGORIA[categoria]:
            continue
        if feat in VARIABLES_DIAS_ASISTIDOS and any(x[1] in VARIABLES_DIAS_ASISTIDOS for x in candidatos):
            continue
        candidatos.append(c)
        usados_por_categoria[categoria] += 1
    if not candidatos:
        return []

    suma_candidatos = sum(c[0] for c in candidatos)
    seleccion = [c for c in candidatos if (c[0] / suma_candidatos * 100) >= umbral_relevancia_pct]
    return seleccion


def _cantidad(n, singular, plural):
    return f"{n} {singular if n == 1 else plural}"


def desglose_dias_restantes(features):
    """Dias de clase del bimestre anterior en los que el alumno no asistio
    puntualmente, como lista de (cantidad, texto), solo las categorias
    mayores a 0. Si hay faltas justificadas, las faltas sin justificar se
    abrevian ("9 faltas justificadas y 3 sin justificar")."""
    def conteo(feat):
        valor = features.get(feat)
        return 0 if valor is None or pd.isna(valor) else int(valor)
    falta = conteo("origen_ultimo_total_falta")
    falta_just = conteo("origen_ultimo_total_falta_justificada")
    tardanza = conteo("origen_ultimo_total_tardanza")
    tardanza_just = conteo("origen_ultimo_total_tardanza_justificada")
    partes = []
    if falta_just > 0:
        partes.append((falta_just, _cantidad(falta_just, "falta justificada", "faltas justificadas")))
    if falta > 0:
        texto = f"{falta} sin justificar" if falta_just > 0 else _cantidad(falta, "falta sin justificar", "faltas sin justificar")
        partes.append((falta, texto))
    if tardanza > 0:
        partes.append((tardanza, _cantidad(tardanza, "tardanza", "tardanzas")))
    if tardanza_just > 0:
        partes.append((tardanza_just, _cantidad(tardanza_just, "tardanza justificada", "tardanzas justificadas")))
    return partes


def _formatear_mediana(mediana):
    return f"{mediana:g}" if float(mediana) != int(mediana) else f"{int(mediana)}"


def redactar_factor(feat, meta, valor, features, modo, mediana):
    """Frase en lenguaje de docente para un factor ya seleccionado. "Por
    debajo/por encima de lo habitual en el colegio" corresponde al filtro de
    doble condicion: el valor esta del lado malo (modo "riesgo") o bueno
    (modo "protector") de la mediana historica del colegio
    (cumple_condicion_objetiva). En las notas, esa mediana se muestra entre
    parentesis. Redaccion decidida por la autora el 2026-11-10."""
    riesgo = modo == "riesgo"
    if meta["es_nota_veinte"]:
        referencia = f"({_formatear_mediana(mediana)}/20)"
        if riesgo and round(valor) == NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE:
            return (f"{meta['texto']} del bimestre anterior fue {valor:.0f}/20, en el límite inferior del logro "
                    f"y por debajo de lo habitual en el colegio {referencia}.")
        lado = "por debajo" if riesgo else "por encima"
        return f"{meta['texto']} del bimestre anterior fue {valor:.0f}/20, {lado} de lo habitual en el colegio {referencia}."

    lado_dias = "por debajo" if riesgo else "por encima"
    if feat == "origen_ultimo_total_dias_asistencia":
        dias_asistidos, dias_lectivos, _ = calcular_asistencia_bimestre_anterior(features)
        if dias_lectivos:
            base = (f"Asistió puntualmente {int(dias_asistidos)} de {int(dias_lectivos)} días de clase en el "
                    f"bimestre anterior, {lado_dias} de lo habitual en el colegio")
            restantes = int(dias_lectivos) - int(dias_asistidos)
            partes = desglose_dias_restantes(features)
            if restantes <= 0 or not partes:
                return base + "."
            if restantes == 1:
                return base + "; el día restante fue una " + partes[0][1].split(" ", 1)[1] + "."
            cantidad, texto = partes[0]
            if len(partes) == 1:
                return base + f"; de los {restantes} días restantes, los {cantidad} fueron " + texto.split(" ", 1)[1] + "."
            primero = f"{cantidad} {'fue' if cantidad == 1 else 'fueron'} " + texto.split(" ", 1)[1]
            textos = [primero] + [t for _, t in partes[1:]]
            return base + f"; de los {restantes} días restantes, " + ", ".join(textos[:-1]) + " y " + textos[-1] + "."
        dias = int(round(valor))
        return f"Asistió {_cantidad(dias, 'día', 'días')} en el bimestre anterior, {lado_dias} de lo habitual en el colegio."
    if feat == "origen_asistencia_historica_promedio":
        dias = int(round(valor))
        return f"En promedio asistió {_cantidad(dias, 'día', 'días')} por bimestre, {lado_dias} de lo habitual en el colegio."

    singular, plural = meta["texto"]
    n = int(valor)
    sustantivo = singular if n == 1 else plural
    if riesgo:
        return f"Tuvo {n} {sustantivo} en el bimestre anterior, por encima de lo habitual en el colegio."
    if n == 0:
        return f"No tuvo {plural} en el bimestre anterior."
    return f"Tuvo solo {n} {sustantivo} en el bimestre anterior, por debajo de lo habitual en el colegio."


def _formatear_nota(valor):
    valor = round(float(valor), 1)
    return f"{int(valor)}/20" if valor == int(valor) else f"{valor:g}/20"


def seleccionar_factor_respaldo(features, shap_fila, medianas):
    """Plan B para un alumno en alerta sin factores visibles (ver
    FACTORES_RESPALDO): devuelve (variable, frase) del respaldo de mayor SHAP
    que cumpla las condiciones, o None."""
    candidatos = []
    for feat, meta in FACTORES_RESPALDO.items():
        valor = features.get(feat)
        shap_val = shap_fila.get(feat)
        if valor is None or pd.isna(valor) or shap_val is None or shap_val <= 0:
            continue
        if not cumple_condicion_objetiva(meta, valor, medianas.get(feat), "riesgo"):
            continue
        if feat == "origen_tendencia":
            if valor >= 0:
                continue
            ultimo = features.get("origen_ultimo_promedio_final")
            frase = (f"Sus notas en este curso bajaron respecto a bimestres anteriores "
                     f"(de {_formatear_nota(ultimo - valor)} a {_formatear_nota(ultimo)}).")
        else:
            if valor > NOTA_MAXIMA_COMO_FACTOR_DESFAVORABLE:
                continue
            frase = (f"Su promedio del bimestre anterior en este curso fue {_formatear_nota(valor)}, por debajo de "
                     f"lo habitual en el colegio ({_formatear_nota(medianas.get(feat))}).")
        candidatos.append((shap_val, feat, frase))
    if not candidatos:
        return None
    _, feat, frase = max(candidatos)
    return feat, frase


def calcular_factores_explicables(features, shap_fila, medianas, clasificacion):
    """Devuelve (texto_corto, detalle, titulo, modo):
    - texto_corto: texto que se guarda en predicciones_riesgo.factores_explicables
      (titulo + las frases mostradas, pensado para reportes/Power BI).
    - detalle: lista de factores para la vista interactiva de la intranet
      (icono + frase), nunca se guarda en la BD.
    - titulo: TITULO_FACTORES, el mismo para todos los alumnos.
    - modo: "riesgo" (alerta_alta / riesgo_moderado) o "protector"
      (sin_riesgo) -- clasificacion == "sin_riesgo" determina el modo.

    Los factores van ordenados de mayor a menor peso SHAP, pero el peso
    NUNCA se muestra al docente (ni porcentaje ni etiqueta de posicion): solo
    se usa internamente para ordenar y filtrar. No hay sugerencia de
    seguimiento (decision de la autora, 2026-10-28: era un texto fijo por
    factor, que se repetia entre alumnos y no aportaba al docente)."""
    modo = "protector" if clasificacion == "sin_riesgo" else "riesgo"
    seleccion = seleccionar_factores(features, shap_fila, medianas, modo)

    if not seleccion and modo == "riesgo":
        respaldo = seleccionar_factor_respaldo(features, shap_fila, medianas)
        if respaldo is None:
            return MENSAJE_ALERTA_SIN_FACTORES, [], TITULO_FACTORES, modo
        feat, frase = respaldo
        meta = FACTORES_RESPALDO[feat]
        detalle = [{"icono": meta["icono"], "categoria": meta["categoria"], "frase": frase}]
        return TITULO_FACTORES + ": " + frase, detalle, TITULO_FACTORES, modo

    if not seleccion:
        if modo == "riesgo":
            texto_sin_factores = MENSAJE_ALERTA_SIN_FACTORES
        else:
            texto_sin_factores = "Desempeño dentro de lo esperado, sin factores individuales destacables."
        return texto_sin_factores, [], TITULO_FACTORES, modo

    frases = []
    detalle = []
    for _, feat, meta, valor in seleccion:
        frase = redactar_factor(feat, meta, valor, features, modo, medianas.get(feat))
        frases.append(frase)
        detalle.append({"icono": meta["icono"], "categoria": meta["categoria"], "frase": frase})

    # Sin em-dash ni viñetas: la conexion a MySQL usa charset "latin1" (ver
    # conectar()), que no puede codificar caracteres fuera de ISO-8859-1.
    texto_corto = TITULO_FACTORES + ": " + " ".join(frases)
    return texto_corto, detalle, TITULO_FACTORES, modo


# ---------------------------------------------------------------------------
# Guardado
# ---------------------------------------------------------------------------

def guardar_prediccion(conn, id_matricula, id_curso, bimestre_objetivo, probabilidad, en_riesgo, clasificacion, factores):
    query = """
        INSERT INTO predicciones_riesgo
            (id_matricula, id_curso, bimestre_objetivo, probabilidad_riesgo, en_riesgo_predicho, clasificacion, factores_explicables)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
            probabilidad_riesgo = VALUES(probabilidad_riesgo),
            en_riesgo_predicho = VALUES(en_riesgo_predicho),
            clasificacion = VALUES(clasificacion),
            factores_explicables = VALUES(factores_explicables)
    """
    cur = conn.cursor()
    cur.execute(query, (id_matricula, id_curso, bimestre_objetivo, float(probabilidad), int(en_riesgo), clasificacion, factores))
    conn.commit()
    cur.close()


def clasificar(probabilidad):
    if probabilidad < UMBRAL_MODERADA:
        return "sin_riesgo"
    if probabilidad <= UMBRAL_ALTA:
        return "riesgo_moderado"
    return "riesgo_alto"


# ---------------------------------------------------------------------------
# Serializacion
# ---------------------------------------------------------------------------

def limpiar_para_json(obj):
    """Convierte tipos numpy a tipos nativos y NaN (python o numpy) a None,
    para que el resultado sea JSON valido (PHP no puede decodificar NaN)."""
    if isinstance(obj, dict):
        return {k: limpiar_para_json(v) for k, v in obj.items()}
    if isinstance(obj, list):
        return [limpiar_para_json(v) for v in obj]
    if isinstance(obj, np.floating):
        obj = float(obj)
    elif isinstance(obj, np.integer):
        obj = int(obj)
    if isinstance(obj, float) and math.isnan(obj):
        return None
    return obj


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

def main():
    parser = argparse.ArgumentParser(description="Predice el nivel de alerta academica del proximo bimestre para un curso.")
    parser.add_argument("--id_curso", required=True, type=int, help="id_curso (entero) de la tabla curso")
    parser.add_argument("--bimestre", type=int, choices=[2, 3, 4], default=None,
                        help="Bimestre a predecir para todos (2, 3 o 4). Sin este parametro: el "
                             "siguiente al ultimo cerrado de cada alumno (comportamiento original).")
    args = parser.parse_args()
    id_curso = args.id_curso
    bimestre_forzado = args.bimestre

    pipeline = joblib.load(RUTA_MODELO)
    columnas_features = joblib.load(RUTA_COLUMNAS)
    medianas = cargar_estadisticas_referencia(columnas_features)
    explicador_shap = construir_explicador_shap(pipeline)

    conn = conectar()
    try:
        curso_info = obtener_curso(conn, id_curso)
        if curso_info is None:
            print(json.dumps({"error": f"No existe id_curso={id_curso}"}, ensure_ascii=False))
            sys.exit(1)

        anio_calendario = obtener_anio_calendario(conn, curso_info["id_anio_escolar"])
        notas = obtener_notas_curso(conn, id_curso)
        # Reconstruye n1/n2/n3/promedio_final desde las notas atomicas (misma
        # logica que construir_dataset_maestro.py, ver scripts/comun/calidad_academica.py)
        # en vez de confiar en los agregados crudos de nota_alumno_secundaria.
        reconstruido = notas.apply(lambda row: pd.Series(reconstruir_notas_bimestre(row)), axis=1)
        notas = pd.concat([notas, reconstruido], axis=1)

        matriculas = sorted(notas["id_matricula"].unique().tolist())
        alumno_por_matricula = dict(zip(notas["id_matricula"], notas["id_alumno"]))
        ids_alumno = sorted(set(alumno_por_matricula.values()))

        asistencia = agregar_asistencia_por_bimestre(obtener_asistencia(conn, matriculas))
        asistencia_map = asistencia.set_index(["id_matricula", "id_bimestre"]).to_dict("index")

        conducta = obtener_conducta(conn, matriculas)
        conducta_map = dict(zip(zip(conducta["id_matricula"], conducta["id_bimestre"]), conducta["nota_conducta"]))

        fechas_nacimiento = obtener_fechas_nacimiento(conn, ids_alumno)

        resultados = []
        excluidos = []

        for id_matricula, notas_alumno in notas.groupby("id_matricula"):
            id_alumno = alumno_por_matricula[id_matricula]
            fecha_nac = fechas_nacimiento.get(id_alumno, pd.NaT)
            edad_relativa_meses = calcular_edad_relativa_meses(fecha_nac, anio_calendario)

            construido, bimestres_cerrados = construir_features_alumno(
                notas_alumno, asistencia_map, conducta_map, id_matricula, edad_relativa_meses,
                bimestre_forzado=bimestre_forzado,
            )
            if construido is None:
                if bimestre_forzado is not None:
                    motivo = f"sin notas del bimestre {bimestre_forzado - 1} en este curso"
                else:
                    motivo = "sin bimestre anterior cerrado" if not bimestres_cerrados else "ano escolar ya completo (bimestre 4 cerrado)"
                excluidos.append({"id_alumno": int(id_alumno), "id_matricula": int(id_matricula), "motivo": motivo})
                continue

            objetivo = construido["objetivo"]
            features = construido["features"]

            X = pd.DataFrame([features])[columnas_features]
            probabilidad = float(pipeline.predict_proba(X)[0, 1])
            en_riesgo = int(pipeline.predict(X)[0])
            clasificacion = clasificar(probabilidad)
            shap_fila = calcular_shap_fila(explicador_shap, pipeline, columnas_features, features)
            factores, factores_detalle, titulo_factores, modo_factores = calcular_factores_explicables(
                features, shap_fila, medianas, clasificacion
            )
            dias_asistidos, dias_lectivos, asistencia_pct = calcular_asistencia_bimestre_anterior(features)

            guardar_prediccion(conn, int(id_matricula), id_curso, objetivo, probabilidad, en_riesgo, clasificacion, factores)

            resultados.append({
                "id_alumno": int(id_alumno),
                "id_matricula": int(id_matricula),
                "bimestre_objetivo": objetivo,
                "probabilidad_riesgo": round(probabilidad, 4),
                "en_riesgo_predicho": en_riesgo,
                "clasificacion": clasificacion,
                "factores_explicables": factores,
                "factores_detalle": factores_detalle,
                "titulo_factores": titulo_factores,
                "modo_factores": modo_factores,
                "contexto_bimestre_anterior": {
                    "promedio_final": features["origen_ultimo_promedio_final"],
                    "asistencia_dias": dias_asistidos,
                    "asistencia_dias_lectivos": dias_lectivos,
                    "asistencia_pct": asistencia_pct,
                    "faltas": features["origen_ultimo_total_falta"],
                    "nota_conducta": features["origen_ultimo_nota_conducta"],
                },
            })

        resultados.sort(key=lambda r: r["probabilidad_riesgo"], reverse=True)

        salida = {
            "id_curso": id_curso,
            "nombre_curso": curso_info["nombre_curso"],
            "id_grado": int(curso_info["id_grado"]),
            "nombre_grado": curso_info["nombre_grado"],
            "generado_en": datetime.now().isoformat(timespec="seconds"),
            "bimestre_solicitado": bimestre_forzado,
            "total_alumnos_predichos": len(resultados),
            "total_alumnos_excluidos": len(excluidos),
            "alumnos_excluidos": excluidos,
            "resultados": resultados,
        }

        print(json.dumps(limpiar_para_json(salida), ensure_ascii=False, indent=2))
    finally:
        conn.close()


if __name__ == "__main__":
    main()
