<?php

/*
 * Tablero de alertas (Power BI) - Modulo de Alerta Academica
 * ----------------------------------------------------------
 * Unico archivo de configuracion de los enlaces a Power BI.
 *
 * url_reporte      --->    URL de insercion del reporte publicado. Vacia = el
 *                          tablero aun no esta publicado: los botones muestran
 *                          el aviso "El tablero aún no está publicado" en vez de
 *                          abrir una pagina rota.
 *                          Decision de la autora (2026-11-10): "Publicar en la web"
 *                          (enlace app.powerbi.com/view?r=...), porque el tablero no
 *                          puede pedir inicio de sesion y Power BI Embedded tiene
 *                          costo. Ese enlace es publico para quien lo tenga y no
 *                          acepta filtros por URL (ver url_tablero() en
 *                          AlertaAcademica.php). La insercion segura ("Sitio web o
 *                          portal", reportEmbed?...) tambien funciona aqui, pero pide
 *                          iniciar sesion.
 * pagina_resumen   --->    Nombre interno (pageName, ej. "ReportSection1a2b3c")
 * pagina_curso             de cada pagina del reporte: 1 resumen institucional,
 * pagina_alumno            2 detalle por grado y curso, 3 detalle por alumno.
 *                          Vacio = se abre la pagina por defecto del reporte.
 * filtro_curso     --->    Tabla/Columna del modelo de Power BI que se filtra con
 * filtro_alumno            el id numerico (filter=Tabla/Columna eq N). En la URL
 *                          solo van numeros (id_curso, id_alumno), nunca nombres.
 */
$config['url_reporte'] = 'https://app.powerbi.com/view?r=eyJrIjoiMTkzMDI5N2YtZDA1ZC00Y2E3LWJkMjctZDc3YWJhZDQ5Njk0IiwidCI6ImM0OGMyMmI5LTE0MDMtNDEyZi05ZTQyLWIwNzE0YTIyMDIxZCIsImMiOjR9';
$config['pagina_resumen'] = '87b973d64883408da1e3';
$config['pagina_curso'] = '4773a21b0445002051d5';
$config['pagina_alumno'] = '15805edb16ea8201adc3';
$config['filtro_curso'] = 'curso/id_curso';
$config['filtro_alumno'] = 'matricula/id_alumno';

/*
 * Boton "Actualizar tablero" (2026-11-11)
 * ruta_script_powerbi  --->    Ruta absoluta a actualizar_powerbi.py, que pide a Power BI
 *                              (API REST) actualizar el modelo semantico. Se ejecuta con el
 *                              mismo Python de serinfo.php (ruta_python). Sus datos (tenant,
 *                              aplicacion, modelo) van en config_powerbi.json junto al script.
 *                              Vacia = el boton no se muestra.
 * Valor de DESARROLLO LOCAL (XAMPP). Cambiar al pasar al servidor real.
 */
$config['ruta_script_powerbi'] = 'C:\\MARCIA\\Tesis\\scripts\\03_produccion\\actualizar_powerbi.py';
