<?php
/* Smarty version 3.1.39, created on 2023-11-18 01:26:21
  from '/var/www/html/intranet_rd/application/views/templates/internas/reporte/asistencia_pdf.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655812bdefc140_24470564',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0baee13e4933f19b472e4d190f605e61b7488446' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/reporte/asistencia_pdf.tpl',
      1 => 1625685416,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655812bdefc140_24470564 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
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
                <label class="bold">Fecha:</label> <?php echo $_smarty_tpl->tpl_vars['fecha_hora']->value;?>

            </td>
        </tr>
        <tr>
            <td class="bold"><?php echo $_smarty_tpl->tpl_vars['usuario']->value;?>
</td>
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
            <th class="sub-titulo-head"><?php echo $_smarty_tpl->tpl_vars['grado']->value;?>
 de <?php echo $_smarty_tpl->tpl_vars['nivel_educativo']->value;?>
</th>
        </tr>
    </table>

    <table class="centrar-tabla">
        <tr>
            <td class="bold">Estudiante:</td>
            <td class="estudiante"><?php echo $_smarty_tpl->tpl_vars['nombre_alumno']->value;?>
</td>
            <td class="bold">Mes:</td>
            <td class="mes"><?php echo $_smarty_tpl->tpl_vars['mes']->value;?>
</td>
        </tr>

    </table>

    <table border="1" class="cuerpo">
        <tr class="fondo">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista_dias']->value, 'ld');
$_smarty_tpl->tpl_vars['ld']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ld']->value) {
$_smarty_tpl->tpl_vars['ld']->do_else = false;
?>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['ld']->value['nombre'];?>
 </br> <?php echo $_smarty_tpl->tpl_vars['ld']->value['numero'];?>
</td>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tr>
        <tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista_dias']->value, 'dia');
$_smarty_tpl->tpl_vars['dia']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['dia']->value) {
$_smarty_tpl->tpl_vars['dia']->do_else = false;
?>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['dia']->value['valor'];?>
</td>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tr>
    </table>

    <table border="1" class="resumen">
        <tr class="fondo" style="text-align: center;">
            <th colspan="2">RESUMEN</th>
        </tr>
        <tr>
            <td class="bold">Asistencias</td>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['resumen']->value['total_asistencias'];?>
</td>
        </tr>
        <tr>
            <td class="bold">Faltas</td>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['resumen']->value['total_faltas'];?>
</td>
        </tr>
        <tr>
            <td class="bold">Tardanzas</td>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['resumen']->value['total_tardanzas'];?>
</td>
        </tr>
        <tr>
            <td class="bold">Faltas Justificadas</td>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['resumen']->value['total_faltas_justificadas'];?>
</td>
        </tr>
        <tr>
            <td class="bold">Tardanzas Justificadas</td>
            <td class="data"><?php echo $_smarty_tpl->tpl_vars['resumen']->value['total_tardanzas_justificadas'];?>
</td>
        </tr>
    </table>
</body>

</html><?php }
}
