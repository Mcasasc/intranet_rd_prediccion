<?php
/* Smarty version 3.1.39, created on 2024-06-01 21:43:28
  from '/var/www/html/intranet_rd/application/views/templates/internas/reporte/mensaje_grado_pdf_admin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_665b9600c6bcd0_97958872',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b7a69a088c1c7a14d0ded4598780d51aad422d4' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/reporte/mensaje_grado_pdf_admin.tpl',
      1 => 1625685448,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_665b9600c6bcd0_97958872 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
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
            <th class="titulo-head">REPORTE DE MENSAJE </th>
            <th rowspan="2">
                <img src="/var/www/html/intranet_rd/public/imagenes/insignia-pdf.png" class="insignia">
            </th>
        </tr>
        <tr>
            <th class="sub-titulo-head"><?php echo $_smarty_tpl->tpl_vars['grado']->value;?>
</th>
        </tr>
    </table>

    <table border="1" class="cuerpo" style="font-size: 10px; width: 100%;">
        <tr class="fondo">
            <th style="width: 25% !important;">ASUNTO</th>
            <th style="width: 37% !important;" colspan="2">LEIDOS</th>
            <th style="width: 38% !important;">NO LEIDOS</th>
        </tr>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['mensaje']->value, 'm');
$_smarty_tpl->tpl_vars['m']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['m']->value) {
$_smarty_tpl->tpl_vars['m']->do_else = false;
?>
        <tr>
            <td style="font-weight: bold;" class="espacio-top fondo"><?php echo $_smarty_tpl->tpl_vars['m']->value['asunto'];?>
</td>
            <td style="border-right-style: none;" class="espacio-top">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['m']->value['leidos'], 'le');
$_smarty_tpl->tpl_vars['le']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['le']->value) {
$_smarty_tpl->tpl_vars['le']->do_else = false;
?>
                <div class="alumno" style="text-align: left; width: 200px;"><?php echo $_smarty_tpl->tpl_vars['le']->value['alumno'];?>
</div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </td>
            <td style="border-left-style: none;" class="espacio-top">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['m']->value['leidos'], 'le');
$_smarty_tpl->tpl_vars['le']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['le']->value) {
$_smarty_tpl->tpl_vars['le']->do_else = false;
?>
                <div class="alumno" style="text-align: right; width: 95px;"> <?php echo $_smarty_tpl->tpl_vars['le']->value['fecha_leido'];?>
</div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </td>
            <td class="espacio-top">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['m']->value['no_leidos'], 'nole');
$_smarty_tpl->tpl_vars['nole']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['nole']->value) {
$_smarty_tpl->tpl_vars['nole']->do_else = false;
?>
                <div class="alumno"> <?php echo $_smarty_tpl->tpl_vars['nole']->value['alumno'];?>
</div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </td>
        </tr>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </table>


</body>

</html><?php }
}
