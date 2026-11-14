<?php
/* Smarty version 3.1.39, created on 2024-06-01 16:31:24
  from '/var/www/html/intranet_rd/application/views/templates/internas/psicologia/form_psicologia.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_665b4cdc163879_58669368',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5b1150dd124242e6b549e27f5286457a32c69ab6' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/psicologia/form_psicologia.tpl',
      1 => 1717259307,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_665b4cdc163879_58669368 (Smarty_Internal_Template $_smarty_tpl) {
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
                            <input type="hidden" class="registro-inicial-anioescolar" name="id_anio_escolar" value="<?php echo $_smarty_tpl->tpl_vars['id_anio_escolar']->value;?>
">
                            <input type="hidden" class="registro-inicial-grado" name="id_grado" value="<?php echo $_smarty_tpl->tpl_vars['id_grado']->value;?>
">
                        </table>
                    </div>
                </div>
            </div>                        
        </div>
        <div class="row m-3">            
                <table class="table table-bordered table-striped table-sm mb-0">
                    <thead class="thead-nota">
                        <tr class="tr-nota">
                            <th class="th-numero">N°</th>
                            <th class="th-alumno">Apellidos y Nombres</th>
                            <th class="th-nota list-group-item-info" style="width: 78px !important;">Nota</th>
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
">
                            <td class="td-numero"><?php echo $_smarty_tpl->tpl_vars['la']->value['numero'];?>
</td>
                            <td class="td-alumno"><?php echo $_smarty_tpl->tpl_vars['la']->value['nombre_alumno'];?>
</td>                            
                            <td class='td-nota celda-nota'>
                                <input type='number' name="nota" class='form-control m-0 psicologia' value="<?php if ((isset($_smarty_tpl->tpl_vars['la']->value['nota']))) {
echo $_smarty_tpl->tpl_vars['la']->value['nota'];
}?>" style="width: 70px;">
                                <input type="hidden" name="id_psicologia" class="id_psicologia" value="<?php if ((isset($_smarty_tpl->tpl_vars['la']->value['id_psicologia']))) {
echo $_smarty_tpl->tpl_vars['la']->value['id_psicologia'];
}?>">
                            </td>
                        </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>            
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?php }
}
