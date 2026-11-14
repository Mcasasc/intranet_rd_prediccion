<?php
/* Smarty version 3.1.39, created on 2023-11-21 03:50:44
  from '/var/www/html/intranet_rd/application/views/templates/internas/usuario/form_usuario.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655c2914329f24_61216001',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a4bea378e4a63af385b55b3eda402d81a35f7d45' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/usuario/form_usuario.tpl',
      1 => 1615534092,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655c2914329f24_61216001 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
usuario/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">
                    <?php if ($_smarty_tpl->tpl_vars['privilegio_sesion']->value == 7 || $_smarty_tpl->tpl_vars['privilegio_sesion']->value == 1) {?>
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Privilegio:</label>
                            <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_privilegio']->value))) {
echo $_smarty_tpl->tpl_vars['id_privilegio']->value;
}?></div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="id_privilegio" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_privilegio']->value))) {
echo $_smarty_tpl->tpl_vars['id_privilegio']->value;
}?>" />
                    <?php }?>
                    <div class="form-group">
                        <label class="font-weight-bold" for="usuario"><i class="fas fa-asterisk text-danger"></i> Usuario:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="usuario" id="usuario" value="<?php if ((isset($_smarty_tpl->tpl_vars['usuario']->value))) {
echo $_smarty_tpl->tpl_vars['usuario']->value;
}?>" placeholder="Usuario" autofocus <?php if ($_smarty_tpl->tpl_vars['privilegio_sesion']->value != 7 && $_smarty_tpl->tpl_vars['privilegio_sesion']->value != 1) {?> readonly <?php }?> autocomplete="new-password" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="contrasena"><i class="fas fa-asterisk text-danger"></i> Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="contrasena-usuario" name="contrasena" placeholder="Contraseña" autocomplete="new-password" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="mostrar-contrasena">
                            <label class="form-check-label" for="mostrar-contrasena">
                                Mostrar contraseña
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nombres" value="<?php if ((isset($_smarty_tpl->tpl_vars['nombres']->value))) {
echo $_smarty_tpl->tpl_vars['nombres']->value;
}?>" placeholder="Nombres" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ape_paterno" value="<?php if ((isset($_smarty_tpl->tpl_vars['ape_paterno']->value))) {
echo $_smarty_tpl->tpl_vars['ape_paterno']->value;
}?>" placeholder="Apellido Paterno" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback ape_materno">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ape_materno" value="<?php if ((isset($_smarty_tpl->tpl_vars['ape_materno']->value))) {
echo $_smarty_tpl->tpl_vars['ape_materno']->value;
}?>" placeholder="Apellido Materno" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> D.N.I.:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dni" name="dni" value="<?php if ((isset($_smarty_tpl->tpl_vars['dni']->value))) {
echo $_smarty_tpl->tpl_vars['dni']->value;
}?>" placeholder="D.N.I." />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"> Estado Civil:</label>
                        <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_estado_civil']->value))) {
echo $_smarty_tpl->tpl_vars['id_estado_civil']->value;
}?></div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Correo Electrónico:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="correo" value="<?php if ((isset($_smarty_tpl->tpl_vars['correo']->value))) {
echo $_smarty_tpl->tpl_vars['correo']->value;
}?>" placeholder="Correo Electrónico" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Cargo:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cargo" name="cargo" value="<?php if ((isset($_smarty_tpl->tpl_vars['cargo']->value))) {
echo $_smarty_tpl->tpl_vars['cargo']->value;
}?>" placeholder="Cargo que ocupa" <?php if ($_smarty_tpl->tpl_vars['privilegio_sesion']->value != 7 && $_smarty_tpl->tpl_vars['privilegio_sesion']->value != 1) {?> readonly <?php }?> />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-briefcase"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"> Dirección:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="direccion" value="<?php if ((isset($_smarty_tpl->tpl_vars['direccion']->value))) {
echo $_smarty_tpl->tpl_vars['direccion']->value;
}?>" placeholder="Dirección" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Distrito:</label>
                        <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_distrito']->value))) {
echo $_smarty_tpl->tpl_vars['id_distrito']->value;
}?></div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"> Fecha de Nacimiento:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="fecha_nacimiento" value="<?php if ((isset($_smarty_tpl->tpl_vars['fecha_nacimiento']->value))) {
echo $_smarty_tpl->tpl_vars['fecha_nacimiento']->value;
}?>" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"> Teléfono:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="telefono" value="<?php if ((isset($_smarty_tpl->tpl_vars['telefono']->value))) {
echo $_smarty_tpl->tpl_vars['telefono']->value;
}?>" placeholder="Teléfono" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-phone"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Celular:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="celular" value="<?php if ((isset($_smarty_tpl->tpl_vars['celular']->value))) {
echo $_smarty_tpl->tpl_vars['celular']->value;
}?>" placeholder="Celular" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-mobile-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fas fa-save"></i> Guardar Usuario</button>
                        <input type="hidden" name="id_usuario" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_usuario']->value))) {
echo $_smarty_tpl->tpl_vars['id_usuario']->value;
}?>" />
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
<?php }
}
