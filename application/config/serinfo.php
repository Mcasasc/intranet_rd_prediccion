<?php

/*
 * Configuración del proyecto
 * --------------------------
 * 
 * project_name     --->    Nombre de la web.
 * project_favicon  --->    URL de la ubicación del favicon, necesita
 *                          ser la dirección de una imagen.
 * key_master       --->    Esta opcion es para administrar la clave 
 *                          de soporte en caso las claves se olviden
 *                          poner una clave dificil.
 * copyright        --->    Derechos de autor, puede ser en HTML - NO REMOVER.
 */
$config['nombre_proyecto'] = 'Intranet René Descartes';
$config['url_favicon'] = '{base_url}public/imagenes/rd-favicon.png';
$config['key_master'] = 'serinfo@wcasasc';

/*
 * Modulo de Alerta Academica (BI + ML)
 * -------------------------------------
 * ruta_python              --->    Ruta absoluta al ejecutable de Python
 *                                  que tiene instaladas las dependencias
 *                                  del modelo (scikit-learn, pandas, etc).
 * ruta_script_prediccion   --->    Ruta absoluta a predecir_curso.py.
 * Valores de DESARROLLO LOCAL (XAMPP). Cambiar al pasar al servidor real.
 */
$config['ruta_python'] = 'C:\\Users\\Marcia Casas\\AppData\\Local\\Programs\\Python\\Python313\\python.exe';
$config['ruta_script_prediccion'] = 'C:\\MARCIA\\Tesis\\scripts\\03_produccion\\predecir_curso.py';

