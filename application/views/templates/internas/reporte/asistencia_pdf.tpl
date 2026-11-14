<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>REPORTE</title>

    <style>
        html {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 20px;
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
            margin-top: 30px;
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
    </table>

    <table class="centrar-tabla">
        <tr>
            <td class="bold">Estudiante:</td>
            <td class="estudiante">{$nombre_alumno}</td>
            <td class="bold">Mes:</td>
            <td class="mes">{$mes}</td>
        </tr>

    </table>

    <table border="1" class="cuerpo">
        <tr class="fondo">
            {foreach $lista_dias as $ld}
            <td class="data">{$ld.nombre} </br> {$ld.numero}</td>
            {/foreach}
        </tr>
        <tr>
            {foreach $lista_dias as $dia}
            <td class="data">{$dia.valor}</td>
            {/foreach}
        </tr>
    </table>

    <table border="1" class="resumen">
        <tr class="fondo" style="text-align: center;">
            <th colspan="2">RESUMEN</th>
        </tr>
        <tr>
            <td class="bold">Asistencias</td>
            <td class="data">{$resumen.total_asistencias}</td>
        </tr>
        <tr>
            <td class="bold">Faltas</td>
            <td class="data">{$resumen.total_faltas}</td>
        </tr>
        <tr>
            <td class="bold">Tardanzas</td>
            <td class="data">{$resumen.total_tardanzas}</td>
        </tr>
        <tr>
            <td class="bold">Faltas Justificadas</td>
            <td class="data">{$resumen.total_faltas_justificadas}</td>
        </tr>
        <tr>
            <td class="bold">Tardanzas Justificadas</td>
            <td class="data">{$resumen.total_tardanzas_justificadas}</td>
        </tr>
    </table>
</body>

</html>