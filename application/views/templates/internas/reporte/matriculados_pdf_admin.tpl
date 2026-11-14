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

        .cuerpo {
            margin-top: 30px;
            border-collapse: collapse;
            border: 1px solid #000000;
            width: 100%;
        }

        .fecha {
            text-align: right;
        }

        .fondo {
            background-color: #d3d3d3;
        }

        .center-text {
            text-align: center;
        }

        .bold{
            font-weight: bold;
        }

        .padding-left{
            padding-left: 3px;
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
            <th class="titulo-head">REPORTE DE MATRICULADOS {$anio_escolar} </th>
            <th rowspan="2">
                <img src="/var/www/html/intranet_rd/public/imagenes/insignia-pdf.png" class="insignia">
            </th>
        </tr>
        <tr>
            <th class="sub-titulo-head">{$grado}</th>
        </tr>
    </table>

    <table border="1" class="cuerpo">
        <tr class="fondo">
            <th class="center-text">N°</th>
            <th class="padding-left">APELLIDOS Y NOMBRES</th>
            <th class="padding-left">DNI</th>
            <th class="padding-left">FECHA DE MATRICULA</th>
        </tr>

        {foreach $lista_matriculados as $lm}
        <tr>
            <td class="center-text bold">{$lm.numero}</td>
            <td class="padding-left">{$lm.nombre}</td>
            <td class="padding-left">{$lm.dni}</td>
            <td class="padding-left">{$lm.fecha_matricula}</td>
        </tr>
        {/foreach}
    </table>
</body>

</html>