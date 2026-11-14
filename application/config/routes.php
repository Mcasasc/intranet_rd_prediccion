<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */

$route['default_controller'] = 'page/login'; //Ruta por defecto - Login
$route['(:any)'] = 'page/$1'; //Ruta al Home del sistema
$route['login/(:any)'] = 'login/$1'; //Ruta Modulo Login
//MODULO USUARIOS
$route['usuario/(:any)/(:any)'] = 'usuario/usuario/$1/$2';
$route['usuario/(:any)'] = 'usuario/usuario/$1';
//MODULO APODERADO
$route['apoderado/(:any)/(:any)'] = 'apoderado/apoderado/$1/$2';
$route['apoderado/(:any)'] = 'apoderado/apoderado/$1';
//MODULO MATRICULA
$route['matricula/(:any)/(:any)'] = 'matricula/matricula/$1/$2';
$route['matricula/(:any)'] = 'matricula/matricula/$1';
//MODULO ALUMNO
$route['alumno/(:any)/(:any)'] = 'alumno/alumno/$1/$2';
$route['alumno/(:any)'] = 'alumno/alumno/$1';
//MODULO REGISTRO AUXILIAR
$route['registro_auxiliar/(:any)/(:any)/(:any)'] = 'registro_auxiliar/registro_auxiliar/$1/$2/$3';
$route['registro_auxiliar/(:any)/(:any)'] = 'registro_auxiliar/registro_auxiliar/$1/$2';
$route['registro_auxiliar/(:any)'] = 'registro_auxiliar/registro_auxiliar/$1';
//MODULO NOTAS
$route['inicial/(:any)/(:any)/(:any)/(:any)'] = 'nota/inicial/$1/$2/$3/$4';
$route['inicial/(:any)'] = 'nota/inicial/$1';
$route['primaria/(:any)/(:any)/(:any)/(:any)'] = 'nota/primaria/$1/$2/$3/$4';
$route['primaria/(:any)'] = 'nota/primaria/$1';
$route['secundaria_v2/(:any)/(:any)/(:any)/(:any)'] = 'nota/secundaria_v2/$1/$2/$3/$4';
$route['secundaria_v2/(:any)'] = 'nota/secundaria_v2/$1';
$route['secundaria/(:any)/(:any)/(:any)/(:any)'] = 'nota/secundaria/$1/$2/$3/$4';
$route['secundaria/(:any)'] = 'nota/secundaria/$1';
//MODULO CONFIGURACION
$route['configuracion/(:any)'] = 'configuracion/configuracion/$1';
$route['configuracion/(:any)/(:any)'] = 'configuracion/configuracion/$1/$2';
//MODULO COMPORTAMIENTO
$route['comportamiento/(:any)/(:any)/(:any)/(:any)'] = 'comportamiento/comportamiento/$1/$2/$3/$4';
$route['comportamiento/(:any)/(:any)'] = 'comportamiento/comportamiento/$1/$2';
$route['comportamiento/(:any)'] = 'comportamiento/comportamiento/$1';
//MODULO ASISTENCIA
$route['asistencia/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'asistencia/asistencia/$1/$2/$3/$4/$5';
$route['asistencia/(:any)/(:any)/(:any)/(:any)'] = 'asistencia/asistencia/$1/$2/$3/$4';
$route['asistencia/(:any)/(:any)'] = 'asistencia/asistencia/$1/$2';
$route['asistencia/(:any)'] = 'asistencia/asistencia/$1';
//MODULO MENSAJE
$route['mensaje/(:any)/(:any)'] = 'mensaje/mensaje/$1/$2';
$route['mensaje/(:any)'] = 'mensaje/mensaje/$1';
//MODULO REPORTE
$route['reporte/(:any)/(:any)'] = 'reporte/reporte/$1/$2'; 
$route['reporte/(:any)'] = 'reporte/reporte/$1';
//MODULO TUTORIA
$route['tutoria/(:any)/(:any)'] = 'tutoria/tutoria/$1/$2'; 
$route['tutoria/(:any)'] = 'tutoria/tutoria/$1';
//MODULO PREDICCION
$route['prediccion/(:any)/(:any)'] = 'prediccion/prediccion/$1/$2';
$route['prediccion/(:any)'] = 'prediccion/prediccion/$1';
//MODULO PSICOLOGIA
$route['psicologia/(:any)/(:any)/(:any)'] = 'psicologia/psicologia/$1/$2/$3';
$route['psicologia/(:any)/(:any)'] = 'psicologia/psicologia/$1/$2';
$route['psicologia/(:any)'] = 'psicologia/psicologia/$1';
//MODULO ALERTA ACADEMICA
$route['alerta_academica/(:any)/(:any)'] = 'alerta_academica/AlertaAcademica/$1/$2';
$route['alerta_academica/(:any)'] = 'alerta_academica/AlertaAcademica/$1';

//ERROR
$route['404_override'] = 'error';
$route['translate_uri_dashes'] = FALSE;
