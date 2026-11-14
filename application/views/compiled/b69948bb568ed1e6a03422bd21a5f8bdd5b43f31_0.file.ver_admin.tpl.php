<?php
/* Smarty version 3.1.39, created on 2023-11-17 02:29:37
  from '/var/www/html/intranet_rd/application/views/templates/internas/reporte/ver_admin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6556d0111ce150_17668074',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b69948bb568ed1e6a03422bd21a5f8bdd5b43f31' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/reporte/ver_admin.tpl',
      1 => 1621972486,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6556d0111ce150_17668074 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
   <section class="container-fluid pl-0">
      <div class="row m-3">
         <span class="response"></span>
         <div class="col-md-6 p-1">
            <div class="card border-primary">
               <div class="card-header font-weight-bold bg-primary text-white">Reporte de Asistencia</div>
               <div class="card-body p-2">
                  <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/reporte_asistencia_admin" method="post" target="_blank">
                     <div class="form-group row">
                        <label class="col-md-2"> Año Escolar</label>
                        <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anios_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anios_escolares']->value;?>
 <?php }?></div>
                     </div>

                     <div class="form-group row">
                        <label class="col-md-2">Mes</label>
                        <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_meses_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_meses_escolares']->value;?>
 <?php }?></div>
                     </div>

                     <div class="form-group row">
                        <label class="col-md-2">Exportar por</label>
                        <div class="col-md-6">
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="alumno" name="radio_exportar_por" value="alumno" class="custom-control-input radio-exportar">
                              <label class="custom-control-label" for="alumno">Alumno</label>
                           </div>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="grado" name="radio_exportar_por" value="grado" class="custom-control-input radio-exportar">
                              <label class="custom-control-label" for="grado">Grado</label>
                           </div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-md-4 combo-filtro"></div>
                     </div>
                     <div class="form-group row float-right">
                        <div class="col-md-4">
                           <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Reporte">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="col-md-6 p-1">
            <div class="card border-primary">
               <div class="card-header font-weight-bold bg-primary text-white">Reporte de Notas</div>
               <div class="card-body p-2">
                  <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/reporte_notas_admin" method="post" target="_blank">
                     <div class="form-group row">
                        <label class="col-md-2"> Año Escolar</label>
                        <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anio_escolar_nota']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anio_escolar_nota']->value;?>
 <?php }?></div>
                     </div>

                     <div class="form-group row">
                        <label class="col-md-2">Bimestre</label>
                        <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_bimestres']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_bimestres']->value;?>
 <?php }?></div>
                     </div>

                     <div class="form-group row">
                        <label class="col-md-2">Exportar por</label>
                        <div class="col-md-6">
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="alumno-nota" name="radio_notas_exportar_por" value="alumno" class="custom-control-input radio-exportar-nota">
                              <label class="custom-control-label" for="alumno-nota">Alumno</label>
                           </div>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="grado-nota" name="radio_notas_exportar_por" value="grado" class="custom-control-input radio-exportar-nota">
                              <label class="custom-control-label" for="grado-nota">Grado</label>
                           </div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-md-4 combo-filtro-alumno-notas"></div>
                     </div>
                     <div class="form-group row float-right">
                        <div class="col-md-4">
                           <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Reporte">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="col-md-6 p-1">
            <div class="card border-primary">
               <div class="card-header font-weight-bold bg-primary text-white">Reporte de Mensajes</div>
               <div class="card-body p-2">
                  <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/reporte_mensajes_admin" method="post" target="_blank">
                     <div class="form-group row">
                        <label class="col-md-2"> Año Escolar</label>
                        <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anios_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anios_escolares']->value;?>
 <?php }?></div>
                     </div>

                     <div class="form-group row">
                        <label class="col-md-2">Grado</label>
                        <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_grados']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_grados']->value;?>
 <?php }?></div>
                     </div>
                     <div class="form-group row float-right">
                        <div class="col-md-4">
                           <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Reporte">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <div class="col-md-6 p-1">
            <div class="card border-primary">
               <div class="card-header font-weight-bold bg-primary text-white">Reporte de Matriculados</div>
               <div class="card-body p-2">
                  <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/reporte_matriculados_admin" method="post" target="_blank">
                     <div class="form-group row">
                        <label class="col-md-2"> Año Escolar</label>
                        <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anios_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anios_escolares']->value;?>
 <?php }?></div>
                     </div>
                     <div class="form-group row">
                        <label class="col-md-2">Grado</label>
                        <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_grados']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_grados']->value;?>
 <?php }?></div>
                     </div>
                     <div class="form-group row float-right">
                        <div class="col-md-4">
                           <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Reporte">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <div class="col-md-6 p-1">
            <div class="card border-primary">
               <div class="card-header font-weight-bold bg-primary text-white">Reporte de Consolidados</div>
               <div class="card-body p-2">
                  <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/consolidado" method="post">
                     <div class="form-group row">
                        <label class="col-md-2"> Año Escolar</label>
                        <div class="col-md-6"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anios_escolares']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_anios_escolares']->value;?>
 <?php }?></div>
                     </div>
                     <div class="form-group row">
                        <label class="col-md-2">Grado</label>
                        <div class="col-md-6"> <?php if ((isset($_smarty_tpl->tpl_vars['combo_grados']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['combo_grados']->value;?>
 <?php }?></div>
                     </div>
                     <div class="form-group row float-right">
                        <div class="col-md-4">
                           <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Consolidado">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
</div><?php }
}
