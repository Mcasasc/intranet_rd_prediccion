<?php
/* Smarty version 3.1.39, created on 2024-06-01 21:41:40
  from '/var/www/html/intranet_rd/application/views/templates/internas/reporte/asistencia_grado_pdf_admin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_665b9594531583_41247679',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9af90952ea4116f822a50773eaeae6425377ef8f' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/reporte/asistencia_grado_pdf_admin.tpl',
      1 => 1625685398,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_665b9594531583_41247679 (Smarty_Internal_Template $_smarty_tpl) {
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
        <tr>
            <th class="sub-titulo-head"><?php echo $_smarty_tpl->tpl_vars['mes']->value;?>
</th>
        </tr>
    </table>

    <table border="1" class="cuerpo" style="font-size: 10px;">
        <tr class="fondo" style="vertical-align: bottom; font-weight: bold;">
            <td style="text-align: left; width: 20px;">N°</td>
            <td style="width: 280px;">Apellidos y Nombres</td>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['dias']->value, 'di');
$_smarty_tpl->tpl_vars['di']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['di']->value) {
$_smarty_tpl->tpl_vars['di']->do_else = false;
?>
            <td style="width: 30px; text-align: center;"><?php echo $_smarty_tpl->tpl_vars['di']->value['nombre'];?>
 <br> <?php echo $_smarty_tpl->tpl_vars['di']->value['numero'];?>
</td>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>            
        </tr>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value, 'da');
$_smarty_tpl->tpl_vars['da']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['da']->value) {
$_smarty_tpl->tpl_vars['da']->do_else = false;
?>
        <tr>
            <td><?php echo $_smarty_tpl->tpl_vars['da']->value['numero'];?>
</td>
            <td style="text-align: left;"><?php echo $_smarty_tpl->tpl_vars['da']->value['alumno'];?>
</td>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['da']->value['asistencia'], 'asis');
$_smarty_tpl->tpl_vars['asis']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['asis']->value) {
$_smarty_tpl->tpl_vars['asis']->do_else = false;
?>
            <td style="text-align: center;"><?php echo $_smarty_tpl->tpl_vars['asis']->value['valor'];?>
</td>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tr>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </table>


</body>

</html> <?php }
}
