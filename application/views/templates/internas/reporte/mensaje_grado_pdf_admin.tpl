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

        table {
            border-collapse: collapse;
        }

        th {
            padding-left: 2px;
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

        .fecha {
            text-align: right;
        }

        .fondo {
            background-color: #d3d3d3;
        }

        .espacio-top {
            padding-top: 7px;
        }

        .alumno {
            padding-top: 3px !important;
            white-space: normal;
        }

        .cuerpo {
            margin-top: 40px;
            border: 1px solid #000000;
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
            <th class="titulo-head">REPORTE DE MENSAJE </th>
            <th rowspan="2">
                <img src="/var/www/html/intranet_rd/public/imagenes/insignia-pdf.png" class="insignia">
            </th>
        </tr>
        <tr>
            <th class="sub-titulo-head">{$grado}</th>
        </tr>
    </table>

    <table border="1" class="cuerpo" style="font-size: 10px; width: 100%;">
        <tr class="fondo">
            <th style="width: 25% !important;">ASUNTO</th>
            <th style="width: 37% !important;" colspan="2">LEIDOS</th>
            <th style="width: 38% !important;">NO LEIDOS</th>
        </tr>
        {foreach $mensaje as $m}
        <tr>
            <td style="font-weight: bold;" class="espacio-top fondo">{$m.asunto}</td>
            <td style="border-right-style: none;" class="espacio-top">
                {foreach $m.leidos as $le}
                <div class="alumno" style="text-align: left; width: 200px;">{$le.alumno}</div>
                {/foreach}
            </td>
            <td style="border-left-style: none;" class="espacio-top">
                {foreach $m.leidos as $le}
                <div class="alumno" style="text-align: right; width: 95px;"> {$le.fecha_leido}</div>
                {/foreach}
            </td>
            <td class="espacio-top">
                {foreach $m.no_leidos as $nole}
                <div class="alumno"> {$nole.alumno}</div>
                {/foreach}
            </td>
        </tr>
        {/foreach}
    </table>


</body>

</html>