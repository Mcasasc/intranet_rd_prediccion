<?php
/* Smarty version 3.1.39, created on 2023-11-17 02:55:10
  from '/var/www/html/intranet_rd/application/views/templates/internas/reporte/ver.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6556d60e31c5e9_81810511',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2cb47cc9db6ae8963cec1ca770c4aec6ee6af9c6' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/reporte/ver.tpl',
      1 => 1636706582,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6556d60e31c5e9_81810511 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-6 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Reporte de Asistencia
                    </div>
                    <div class="card-body p-2">
                        <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/reporte_asistencia" method="post" target="_blank">
                            <div class="form-group row">
                                <label class="col-md-2 font-weight-bold"> Año Escolar</label>
                                <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anios_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anios_escolares']->value;?>
 <?php }?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 font-weight-bold">Mes</label>
                                <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_meses_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_meses_escolares']->value;?>
 <?php }?>
                                </div>
                            </div>
                            <div class="form-group row float-right">
                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-social btn-primary btn-flat"
                                        value="Ver Reporte">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Reporte de Notas
                    </div>
                    <div class="card-body p-2">
                        <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/reporte_notas" method="post" target="_blank">
                            <div class="form-group row">
                                <label class="col-md-2 font-weight-bold"> Año Escolar</label>
                                <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anios_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anios_escolares']->value;?>
 <?php }?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">Bimestre</label>
                                <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_bimestres']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_bimestres']->value;?>
 <?php }?>
                                </div>
                            </div>
                            <?php if ((isset($_smarty_tpl->tpl_vars['estado_usuario']->value)) && $_smarty_tpl->tpl_vars['estado_usuario']->value == 0) {?> 
                                <div class="form-group row float-right">
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Reporte">
                                    </div>
                                </div>
                            <?php }?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div><?php }
}
