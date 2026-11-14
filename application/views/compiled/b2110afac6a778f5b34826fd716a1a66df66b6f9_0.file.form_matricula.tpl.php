<?php
/* Smarty version 3.1.39, created on 2023-11-18 15:58:18
  from '/var/www/html/intranet_rd/application/views/templates/internas/matricula/form_matricula.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6558df1ab1fed6_83003706',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b2110afac6a778f5b34826fd716a1a66df66b6f9' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/matricula/form_matricula.tpl',
      1 => 1615139116,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6558df1ab1fed6_83003706 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
matricula/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="dni"><i class="fas fa-asterisk text-danger"></i> DNI:</label>
                        <div class=input-group>
                            <input type="text" class="form-control dni" id="dni" name="dni" value="<?php if ((isset($_smarty_tpl->tpl_vars['dni']->value))) {
echo $_smarty_tpl->tpl_vars['dni']->value;
}?>" <?php if ($_smarty_tpl->tpl_vars['tipo']->value == "editar") {?>readonly<?php }?> />
                            <!-- <div class="input-group-prepend <?php if ($_smarty_tpl->tpl_vars['tipo']->value == 'agregar') {?>buscar-alumno<?php }?>"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div> -->
                            <div class="input-group-append <?php if ($_smarty_tpl->tpl_vars['tipo']->value == 'agregar') {?>buscar-alumno<?php }?>" title="Buscar Alumno"><button class="btn btn-secondary"><i class="fas fa-search"></i></button></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="nombres"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                        <div class="input-group">
                            <input type="text" class="form-control nombres" id="nombres" name="nombres" value="<?php if ((isset($_smarty_tpl->tpl_vars['nombres']->value))) {
echo $_smarty_tpl->tpl_vars['nombres']->value;
}?>" <?php if ($_smarty_tpl->tpl_vars['tipo']->value == "editar") {?>readonly<?php }?> />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-child"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="ape_paterno"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control ape_paterno" id="ape_paterno" name="ape_paterno" value="<?php if ((isset($_smarty_tpl->tpl_vars['ape_paterno']->value))) {
echo $_smarty_tpl->tpl_vars['ape_paterno']->value;
}?>" <?php if ($_smarty_tpl->tpl_vars['tipo']->value == "editar") {?>readonly<?php }?> />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-child"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="ape_materno"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                        <div class=input-group>
                            <input type="text" class="form-control ape_materno" id="ape_materno" name="ape_materno" value="<?php if ((isset($_smarty_tpl->tpl_vars['ape_materno']->value))) {
echo $_smarty_tpl->tpl_vars['ape_materno']->value;
}?>" <?php if ($_smarty_tpl->tpl_vars['tipo']->value == "editar") {?>readonly<?php }?> />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-child"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="usuario"><i class="fas fa-asterisk text-danger"></i> Usuario:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="usuario" id="usuario" value="<?php if ((isset($_smarty_tpl->tpl_vars['usuario']->value))) {
echo $_smarty_tpl->tpl_vars['usuario']->value;
}?>" placeholder="Usuario" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="contrasena"><i class="fas fa-asterisk text-danger"></i> Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" autocomplete="new-password" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Fecha de Nacimiento:</label>
                        <div class=input-group>
                            <input type="date" class="form-control fecha_nacimiento" name="fecha_nacimiento" value="<?php if ((isset($_smarty_tpl->tpl_vars['fecha_nacimiento']->value))) {
echo $_smarty_tpl->tpl_vars['fecha_nacimiento']->value;
}?>" <?php if ($_smarty_tpl->tpl_vars['tipo']->value == "editar") {?>readonly<?php }?> />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="direccion"><i class="fas fa-asterisk text-danger"></i> Dirección:</label>
                        <div class=input-group>
                            <input type="text" class="form-control direccion" id="direccion" name="direccion" value="<?php if ((isset($_smarty_tpl->tpl_vars['direccion']->value))) {
echo $_smarty_tpl->tpl_vars['direccion']->value;
}?>" <?php if ($_smarty_tpl->tpl_vars['tipo']->value == "editar") {?>readonly<?php }?> />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Distrito:</label>
                        <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_distrito']->value))) {
echo $_smarty_tpl->tpl_vars['id_distrito']->value;
}?></div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value))) {
echo $_smarty_tpl->tpl_vars['id_anio_escolar']->value;
}?></div>
                    </div>
                    <div class="form-group radio-nivel-educativo">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Nivel Educativo:</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="inicial" value="1" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['id_nivel_educativo']->value)) && $_smarty_tpl->tpl_vars['id_nivel_educativo']->value == 1) {?>checked<?php }?>>
                            <label class="custom-control-label" for="inicial">Inicial</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="primaria" value="2" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['id_nivel_educativo']->value)) && $_smarty_tpl->tpl_vars['id_nivel_educativo']->value == 2) {?>checked<?php }?>>
                            <label class="custom-control-label" for="primaria">Primaria</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="secundaria" value="3" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['id_nivel_educativo']->value)) && $_smarty_tpl->tpl_vars['id_nivel_educativo']->value == 3) {?>checked<?php }?>>
                            <label class="custom-control-label" for="secundaria">Secundaria</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Grado:</label>
                        <div class="input-group "><?php if ((isset($_smarty_tpl->tpl_vars['id_grado']->value))) {
echo $_smarty_tpl->tpl_vars['id_grado']->value;
}?></div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fa fa-save"></i> Registrar Matrícula</button>
                        <input class="id_matricula" type="hidden" name="id_matricula" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_matricula']->value))) {
echo $_smarty_tpl->tpl_vars['id_matricula']->value;
}?>" />
                    </div>
                </div>
            </div>
</div>
</form>
</section>
</div>


<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
    <div id="toast-encontrado" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" style="font-size: 1.5rem;">
        <div class="toast-header">
            <i class="far fa-thumbs-up"></i>
            <strong class="mr-auto text-success">¡Bien!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body text-success">
            Alumno identificado
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0; font-size: 1.5rem;">
    <div id="toast-noencontrado" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" style="font-size: 1.5rem;">
        <div class="toast-header">
            <i class="far fa-thumbs-down"></i>
            <strong class="mr-auto text-danger">¡Lo sentimos!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body text-danger">
            Alumno no identificado
        </div>
    </div>
</div>
<?php }
}
