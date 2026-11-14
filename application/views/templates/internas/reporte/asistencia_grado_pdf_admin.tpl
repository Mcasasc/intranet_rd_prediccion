<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>REPORTE</title>

    <style>
        html {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 10px;
        }

        /* Ancho del encabezado */
        .ancho-head {
            width: 100%;
        }

        .bold {
            font-weight: 700;
        }

        /* Ancho del titulo */
        .ancho-titulo {
            width: 100%;
            height: 70px;
        }

        /* Tamaño del titulo */
        .titulo-head {
            font-size: 20px;
            width: 90%;
            text-align: center;
        }

        .insignia {
            width: 50px;

        }

        .sub-titulo-head {
            font-size: 15px;
            text-align: center;
            vertical-align: top;
        }

        .estudiante {
            width: 300px;
            color: #3889db;
            font-weight: bolder;
        }

        .mes {
            width: 150px;
            color: #3889db;
            font-weight: bold;
        }

        .cuerpo {
            margin-top: 80px;
            border-collapse: collapse;
            border: 1px solid #000000;            
        }

        .data {
            text-align: center;
            vertical-align: middle;
        }

        .fecha {
            text-align: right;
        }

        .fondo {
            background-color: #d3d3d3;
        }

        .resumen {
            margin-left: auto;
            margin-right: auto;
            position: fixed;
            top: 50%;
            border-collapse: collapse;
            border: 1px solid #000000;
        }

        .centrar-tabla {
            margin-left: auto;
            margin-right: auto;
        }

        .orientacion {
            writing-mode: vertical-lr;
            transform: rotate(270deg);
            
            
        }
    </style>
</head>

<body>

    <table class="ancho-head">
        <tr>
            <td class="bold">I.E.P. René Descartes</td>
            <td class="fecha">
                <label class="bold">Fecha:</label> {$fecha_hora}
            </td>
        </tr>
        <tr>
            <td class="bold">{$usuario}</td>
        </tr>
    </table>

    <table class="ancho-titulo">
        <tr>
            <th class="titulo-head">REPORTE DE ASISTENCIA </th>
            <th rowspan="2">
                <img src="/var/www/html/intranet_rd/public/imagenes/insignia-pdf.png" class="insignia">
            </th>
        </tr>
        <tr>
            <th class="sub-titulo-head">{$grado} de {$nivel_educativo}</th>
        </tr>
        <tr>
            <th class="sub-titulo-head">{$mes}</th>
        </tr>
    </table>

    <table border="1" class="cuerpo" style="font-size: 10px;">
        <tr class="fondo" style="vertical-align: bottom; font-weight: bold;">
            <td style="text-align: left; width: 20px;">N°</td>
            <td style="width: 280px;">Apellidos y Nombres</td>
            {foreach $dias as $di}
            <td style="width: 30px; text-align: center;">{$di.nombre} <br> {$di.numero}</td>
            {/foreach}            
        </tr>
        {foreach $data as $da}
        <tr>
            <td>{$da.numero}</td>
            <td style="text-align: left;">{$da.alumno}</td>
            {foreach $da.asistencia as $asis}
            <td style="text-align: center;">{$asis.valor}</td>
            {/foreach}
        </tr>
        {/foreach}
    </table>


</body>

</html> 