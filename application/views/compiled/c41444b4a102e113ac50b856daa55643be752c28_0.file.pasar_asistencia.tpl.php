<?php
/* Smarty version 3.1.39, created on 2023-11-21 02:10:22
  from '/var/www/html/intranet_rd/application/views/templates/internas/asistencia/pasar_asistencia.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655c118ea0bbc2_75066774',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c41444b4a102e113ac50b856daa55643be752c28' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/asistencia/pasar_asistencia.tpl',
      1 => 1561673936,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655c118ea0bbc2_75066774 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0 pr-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        <?php echo $_smarty_tpl->tpl_vars['nombre_grado']->value;?>

                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th>Mes Escolar</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_mes_escolar']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Grado y Sección</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_grado']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['seccion_grado']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Nivel Educativo</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_nivel_educativo']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Año Escolar</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_anio_escolar']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Día</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_dia']->value;?>
</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Resumen de Asistencias Mensual
                    </div>
                    <div class="card-body p-2 card-body-ips">
                        <div class="list-group">
                           <button type="button" class="list-group-item list-group-item-action p-1">1 - Total de Asistencias</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">2 - Total de Faltas</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">3 - Total de Tardanzas</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">4 - Total de Faltas Justificadas</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">5 - Total de Tardanzas Justificadas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-3">
            <table class="table table-bordered table-striped table-sm mb-0" id="tabla-asistencia">
                <thead class="thead-nota">
                    <tr class="tr-nota">
                        <th class="th-numero">N°</th>
                        <th class="th-alumno">Apellidos y Nombres</th>
                        <th class="th-nota"><i class="fas fa-arrow-circle-down"></i>
                            <!--
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input check_dialibre" id="checkbox_dia_libre"
                                    name="checkbox_dia_libre">
                                <label class="custom-control-label" for="checkbox_dia_libre"></label>
                            </div>
                            -->
                        </th>
                        <th class="th-nota">1</th>
                        <th class="th-nota">2</th>
                        <th class="th-nota">3</th>
                        <th class="th-nota">4</th>
                        <th class="th-nota">5</th>
                    </tr>
                </thead>
                <tbody class="tbody-nota">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista_alumnos']->value, 'la');
$_smarty_tpl->tpl_vars['la']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['la']->value) {
$_smarty_tpl->tpl_vars['la']->do_else = false;
?>
                    <tr id="fila_<?php echo $_smarty_tpl->tpl_vars['la']->value['numero'];?>
 fila">
                        <td class="td-numero"><?php echo $_smarty_tpl->tpl_vars['la']->value['numero'];?>
</td>
                        <td class="td-alumno"><?php echo $_smarty_tpl->tpl_vars['la']->value['nombre_alumno'];?>
</td>
                        <td class='td-nota celda-nota'>
                            <select class="box_asistencia_alumno selectpicker" data-width="fit" name="<?php echo $_smarty_tpl->tpl_vars['la']->value['numero_dia'];?>
">
                                <option value="A" <?php if ($_smarty_tpl->tpl_vars['la']->value['dia'] == 'A') {?>selected<?php }?>>A </option>
                                <option value="F" <?php if ($_smarty_tpl->tpl_vars['la']->value['dia'] == 'F') {?>selected<?php }?>>F </option>
                                <option value="T" <?php if ($_smarty_tpl->tpl_vars['la']->value['dia'] == 'T') {?> selected<?php }?>>T </option>
                                <option value="F.J." <?php if ($_smarty_tpl->tpl_vars['la']->value['dia'] == 'F.J.') {?>selected<?php }?>>F.J. </option>
                                <option value="T.J." <?php if ($_smarty_tpl->tpl_vars['la']->value['dia'] == 'T.J.') {?>selected<?php }?>>T.J. </option>
                                <option value="NA" <?php if ($_smarty_tpl->tpl_vars['la']->value['dia'] == 'NA') {?>selected<?php }?>>LIB </option>
                            </select>
                            <input type="hidden"name="id_asistencia_alumno" class="id_asistencia_alumno" value="<?php if ((isset($_smarty_tpl->tpl_vars['la']->value['id_asistencia_alumno']))) {
echo $_smarty_tpl->tpl_vars['la']->value['id_asistencia_alumno'];
}?>">
                        </td>
                        <td class="td-nota celda-nota total_asistencias text-center"><?php if ((isset($_smarty_tpl->tpl_vars['la']->value['total_asistencias']))) {
echo $_smarty_tpl->tpl_vars['la']->value['total_asistencias'];
}?></td>
                        <td class="td-nota celda-nota total_faltas text-center"><?php if ((isset($_smarty_tpl->tpl_vars['la']->value['total_asistencias']))) {
echo $_smarty_tpl->tpl_vars['la']->value['total_faltas'];
}?></td>
                        <td class="td-nota celda-nota total_tardanzas text-center"><?php if ((isset($_smarty_tpl->tpl_vars['la']->value['total_asistencias']))) {
echo $_smarty_tpl->tpl_vars['la']->value['total_tardanzas'];
}?></td>
                        <td class="td-nota celda-nota total_faltas_justificadas text-center"><?php if ((isset($_smarty_tpl->tpl_vars['la']->value['total_asistencias']))) {
echo $_smarty_tpl->tpl_vars['la']->value['total_faltas_justificadas'];
}?></td>
                        <td class="td-nota celda-nota total_tardanzas_justificadas text-center"><?php if ((isset($_smarty_tpl->tpl_vars['la']->value['total_asistencias']))) {
echo $_smarty_tpl->tpl_vars['la']->value['total_tardanzas_justificadas'];
}?></td>
                    </tr>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </tbody>
            </table>
        </div>
</div>
</section>
</div><?php }
}
