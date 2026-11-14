"""
Logica de calidad de datos y reconstruccion de notas academicas de
secundaria, COMPARTIDA entre scripts/01_extraccion/construir_dataset_maestro.py
(entrenamiento) y scripts/03_produccion/predecir_curso.py (produccion), para
garantizar que ambos calculen n1_promedio, n2_promedio, n3_promedio y
promedio_final de forma IDENTICA a partir de los mismos datos crudos. Antes
de este modulo, cada script tenia su propia copia (o directamente no tenia)
de esta logica, lo que producia features distintas en entrenamiento y en
produccion para el mismo alumno-curso-bimestre.

Contexto (ver CLAUDE.md y las sesiones de diagnostico de calidad de datos):
`nota_alumno_secundaria.promedio_final` (y en menor medida n1_promedio/
n2_promedio/n3_promedio) puede contener 0 artificial cuando ningun
componente fue realmente registrado por el docente, en vez de NULL. Causa
raiz identificada: application/libraries/Mantenimiento.php::promedio_bimestral()
en la intranet trata NULL como 0 al promediar en PHP. Confirmado con el
administrador del colegio: una nota no registrada NO equivale academicamente
a 0; el promedio institucional se calcula solo con las notas realmente
registradas, y si no hay ninguna, el resultado academico es vacio (la boleta
puede mostrar 0 solo por razones de presentacion a padres, no como
calificacion real).

Este modulo NUNCA escribe en la base de datos ni modifica el codigo
original de la intranet -- es logica de limpieza que vive unicamente en
este proyecto de tesis, sobre datos ya leidos (solo lectura) de
nota_alumno_secundaria.
"""
import math
from datetime import date

import numpy as np
import pandas as pd

# Columnas atomicas fuente de cada concepto, tal como existen en
# nota_alumno_secundaria.
COLUMNAS_N1_SEMANAL = [f"n1_s{i}" for i in range(1, 9)]
COLUMNAS_N2_SEMANAL = [f"n2_s{i}" for i in range(1, 9)]
COLUMNAS_N3_DETALLE = ["n3_rc1", "n3_co1", "n3_rc2", "n3_co2"]


# ---------------------------------------------------------------------------
# Filtro de calidad: notas fuera de escala 0-20 (ver CLAUDE.md)
# ---------------------------------------------------------------------------

def limpiar_escala(valor):
    """Un valor fuera de 0-20 se trata como dato faltante (error de captura
    confirmado, ver CLAUDE.md -- ej. notas de conducta con 43, 118, 1818).
    NULL/NaN se mantiene NULL/None. Un 0 dentro de escala es una nota
    valida, nunca se descarta."""
    if valor is None:
        return None
    if isinstance(valor, float) and math.isnan(valor):
        return None
    if 0 <= valor <= 20:
        return valor
    return None


# ---------------------------------------------------------------------------
# Redondeo institucional y promedio "ignora NULL"
# ---------------------------------------------------------------------------

def php_round(x):
    """Replica round() de PHP (round half away from zero) -- el mismo
    criterio de redondeo que usa Mantenimiento::promedio_concepto() en la
    intranet. Las notas academicas son siempre >= 0, asi que no hace falta
    manejar el caso negativo."""
    return math.floor(x + 0.5)


def promedio_concepto(valores):
    """Replica Mantenimiento::promedio_concepto() de la intranet: promedia
    unicamente los valores realmente registrados (ignora None/NaN), un 0
    explicito SI cuenta como nota, y devuelve None (nunca 0 artificial) si
    ningun valor esta registrado. No aplica limpieza de escala -- se asume
    que los valores recibidos ya pasaron por limpiar_escala()."""
    presentes = [v for v in valores if v is not None and not (isinstance(v, float) and math.isnan(v))]
    if not presentes:
        return None
    return php_round(sum(presentes) / len(presentes))


# ---------------------------------------------------------------------------
# Reconstruccion por concepto
# ---------------------------------------------------------------------------

def reconstruir_concepto_semanal(valores_semanales):
    """n1 (Participacion y Tareas) y n2 (Asistencia y Practicas Diarias) se
    reconstruyen igual: promedio de las notas semanales que esten dentro de
    escala 0-20 y realmente registradas (no NULL)."""
    limpias = [limpiar_escala(v) for v in valores_semanales]
    return promedio_concepto(limpias)


def reconstruir_n3(n3_detalle, n3_promedio_almacenado):
    """n3 (Revision de Cuaderno y Conducta) usa n3_detalle =
    [n3_rc1, n3_co1, n3_rc2, n3_co2].

    Si existe al menos una nota valida (0-20, no NULL) entre las 4: se
    reconstruye desde ese detalle, ignorando las que esten NULL o fuera de
    escala.

    Si las 4 estan vacias (NULL o fuera de escala): se usa
    n3_promedio_almacenado como RESPALDO HISTORICO, pero solo si, una vez
    limpiado por escala 0-20, es un valor real mayor a 0 (evidencia: 31
    casos verificados en 2020/2022 de bimestres completamente calificados
    -- n1, n2, examenes y promedio_final todos coherentes entre si -- donde
    el desglose semanal de este concepto en particular no se capturaba). Si
    el almacenado limpio es 0 o esta fuera de escala, se trata como
    ausencia de nota (None), no como un cero real."""
    detalle_limpio = [limpiar_escala(v) for v in n3_detalle]
    if any(v is not None for v in detalle_limpio):
        return promedio_concepto(detalle_limpio)

    n3_respaldo = limpiar_escala(n3_promedio_almacenado)
    if n3_respaldo is not None and n3_respaldo > 0:
        return n3_respaldo
    return None


def reconstruir_promedio_final(n1, n2, n3, examen_mensual, examen_bimestral):
    """n1/n2/n3 deben venir ya reconstruidos (ver funciones arriba).
    examen_mensual/examen_bimestral son valores atomicos (no se
    reconstruyen desde componentes): se limpian por escala aqui mismo antes
    de promediar."""
    return promedio_concepto([
        n1, n2, n3,
        limpiar_escala(examen_mensual),
        limpiar_escala(examen_bimestral),
    ])


# ---------------------------------------------------------------------------
# Punto de entrada unico: una fila cruda de nota_alumno_secundaria -> las
# 4 notas reconstruidas. Entrenamiento y produccion llaman a esta MISMA
# funcion para garantizar resultados identicos.
# ---------------------------------------------------------------------------

def reconstruir_notas_bimestre(fila):
    """fila: dict-like (fila de DataFrame de pandas, o dict) con al menos
    las columnas COLUMNAS_N1_SEMANAL + COLUMNAS_N2_SEMANAL +
    COLUMNAS_N3_DETALLE + ['n3_promedio', 'examen_mensual', 'examen_bimestral'].

    Devuelve un dict con n1_reconstruido, n2_reconstruido, n3_reconstruido
    y promedio_final_reconstruido."""
    n1 = reconstruir_concepto_semanal([fila[c] for c in COLUMNAS_N1_SEMANAL])
    n2 = reconstruir_concepto_semanal([fila[c] for c in COLUMNAS_N2_SEMANAL])
    n3 = reconstruir_n3([fila[c] for c in COLUMNAS_N3_DETALLE], fila["n3_promedio"])
    pf = reconstruir_promedio_final(n1, n2, n3, fila["examen_mensual"], fila["examen_bimestral"])
    return {
        "n1_reconstruido": n1,
        "n2_reconstruido": n2,
        "n3_reconstruido": n3,
        "promedio_final_reconstruido": pf,
    }


# ---------------------------------------------------------------------------
# Fecha de nacimiento y edad relativa (ver CLAUDE.md, "Diseno del dataset").
# Antes vivian en dos copias: construir_dataset_maestro.py NO aplicaba el
# rango valido de edad y predecir_curso.py SI (detectado 2026-10 en la
# auditoria de consistencia del dataset). Ahora ambos usan estas funciones.
# ---------------------------------------------------------------------------

# Rango valido de edad_relativa_meses (~10.8 a ~20.4 anios). Fuera de este
# rango, el valor es un error de captura que la correccion del prefijo "00"
# no resuelve, y se trata como dato faltante.
EDAD_MIN_MESES = 130
EDAD_MAX_MESES = 245


def corregir_fecha_nacimiento(fecha):
    """Corrige anios mal capturados (ej. '0011' en vez de '2011'),
    reemplazando el prefijo '00' por '20' -> sumar 2000 al anio."""
    if pd.isna(fecha):
        return pd.NaT
    if fecha.year < 100:
        try:
            return fecha.replace(year=fecha.year + 2000)
        except ValueError:
            return pd.NaT
    return fecha


def calcular_edad_relativa_meses(fecha_nacimiento, anio_calendario):
    """Edad en meses cumplida al 31 de marzo del anio escolar (fecha de
    corte MINEDU). fecha_nacimiento debe venir ya corregida con
    corregir_fecha_nacimiento(). Un resultado fuera de
    EDAD_MIN_MESES-EDAD_MAX_MESES se devuelve como NaN (dato faltante)."""
    if pd.isna(fecha_nacimiento) or anio_calendario is None:
        return np.nan
    fecha_corte = date(anio_calendario, 3, 31)
    meses = (fecha_corte.year - fecha_nacimiento.year) * 12 + (fecha_corte.month - fecha_nacimiento.month)
    if fecha_corte.day < fecha_nacimiento.day:
        meses -= 1
    if meses < EDAD_MIN_MESES or meses > EDAD_MAX_MESES:
        return np.nan
    return meses
