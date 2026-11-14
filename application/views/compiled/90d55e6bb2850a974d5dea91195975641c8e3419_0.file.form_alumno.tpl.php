<?php
/* Smarty version 3.1.39, created on 2023-11-24 13:29:01
  from '/var/www/html/intranet_rd/application/views/templates/internas/alumno/form_alumno.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6560a51dae14e0_09882689',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '90d55e6bb2850a974d5dea91195975641c8e3419' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/alumno/form_alumno.tpl',
      1 => 1583142352,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6560a51dae14e0_09882689 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
alumno/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label class="font-weight-bold" for="dni"><i class="fas fa-asterisk text-danger"></i> DNI:</label>
                            <div class=input-group>
                                <input type="text" class="form-control dni" id="dni" name="dni" value="<?php if ((isset($_smarty_tpl->tpl_vars['dni']->value))) {
echo $_smarty_tpl->tpl_vars['dni']->value;
}?>"/>
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div>
                            </div>
                        </div>                                                                                                     
                        <div class="form-group">
                            <label class="font-weight-bold" for="nombres"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                            <div class="input-group">
                                <input type="text" class="form-control nombres" id="nombres" name="nombres" value="<?php if ((isset($_smarty_tpl->tpl_vars['nombres']->value))) {
echo $_smarty_tpl->tpl_vars['nombres']->value;
}?>" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-child"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">                            
                            <label class="font-weight-bold" for="ape_paterno"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                            <div class="input-group">
                                <input type="text" class="form-control ape_paterno" id="ape_paterno" name="ape_paterno" value="<?php if ((isset($_smarty_tpl->tpl_vars['ape_paterno']->value))) {
echo $_smarty_tpl->tpl_vars['ape_paterno']->value;
}?>" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-child"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="ape_materno"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                            <div class=input-group>
                                <input type="text" class="form-control ape_materno" id="ape_materno" name="ape_materno" value="<?php if ((isset($_smarty_tpl->tpl_vars['ape_materno']->value))) {
echo $_smarty_tpl->tpl_vars['ape_materno']->value;
}?>" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-child"></i></div></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" for="usuario"><i class="fas fa-asterisk text-danger"></i> Usuario:</label>
                            <div class="input-group">                            
                                <input type="text" class="form-control" name="usuario" id="usuario" value="<?php if ((isset($_smarty_tpl->tpl_vars['usuario']->value))) {
echo $_smarty_tpl->tpl_vars['usuario']->value;
}?>" placeholder="Usuario" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                            </div>                                                
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="contrasena"><i class="fas fa-asterisk text-danger"></i> Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="contrasena" placeholder="Contraseña"/>
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-key"></i></div></div>
                            </div>
                        </div>

                                                                                          
                    </div>
                    <div class="col-md-6">                                                                        
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Fecha de Nacimiento:</label>
                            <div class=input-group>
                                <input type="date" class="form-control fecha_nacimiento" name="fecha_nacimiento" value="<?php if ((isset($_smarty_tpl->tpl_vars['fecha_nacimiento']->value))) {
echo $_smarty_tpl->tpl_vars['fecha_nacimiento']->value;
}?>" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="direccion"><i class="fas fa-asterisk text-danger"></i> Dirección:</label>
                            <div class=input-group>
                                <input type="text" class="form-control direccion" id="direccion" name="direccion" value="<?php if ((isset($_smarty_tpl->tpl_vars['direccion']->value))) {
echo $_smarty_tpl->tpl_vars['direccion']->value;
}?>" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Distrito:</label>
                            <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_distrito']->value))) {
echo $_smarty_tpl->tpl_vars['id_distrito']->value;
}?></div>
                        </div>                                                 
                         <div class="form-group">                            
                            <button class="btn btn-social btn-block btn-primary btn-flat">
                                <i class="fa fa-save"></i> Editar Alumno</button>
                            <input class="id_alumno" type="hidden" name="id_alumno" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_alumno']->value))) {
echo $_smarty_tpl->tpl_vars['id_alumno']->value;
}?>" />
                            <input class="id_usuario" type="hidden" name="id_usuario" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_usuario']->value))) {
echo $_smarty_tpl->tpl_vars['id_usuario']->value;
}?>" />
                        </div>     
                    </div>                                                                                                  
                </div>
            </div>        
    </div>
    </form>
</section>
</div><?php }
}
