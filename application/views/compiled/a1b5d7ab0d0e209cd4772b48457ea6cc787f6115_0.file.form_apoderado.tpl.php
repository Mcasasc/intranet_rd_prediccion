<?php
/* Smarty version 3.1.39, created on 2024-01-05 17:18:28
  from '/var/www/html/intranet_rd/application/views/templates/internas/apoderado/form_apoderado.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_659839e400e2b3_89571421',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a1b5d7ab0d0e209cd4772b48457ea6cc787f6115' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/apoderado/form_apoderado.tpl',
      1 => 1583138594,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_659839e400e2b3_89571421 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">                
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
apoderado/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">                    
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Parentesco:</label>
                        <div class="input-group "><?php if ((isset($_smarty_tpl->tpl_vars['id_parentesco']->value))) {
echo $_smarty_tpl->tpl_vars['id_parentesco']->value;
}?></div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="nombre"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nombre" name="nombres" value="<?php if ((isset($_smarty_tpl->tpl_vars['nombres']->value))) {
echo $_smarty_tpl->tpl_vars['nombres']->value;
}?>" placeholder="Nombres"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="apellido_paterno"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php if ((isset($_smarty_tpl->tpl_vars['apellido_paterno']->value))) {
echo $_smarty_tpl->tpl_vars['apellido_paterno']->value;
}?>" placeholder="Apellido Paterno"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="apellido_materno"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php if ((isset($_smarty_tpl->tpl_vars['apellido_materno']->value))) {
echo $_smarty_tpl->tpl_vars['apellido_materno']->value;
}?>" placeholder="Apellido Materno"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="dni"><i class="fas fa-asterisk text-danger"></i> D.N.I.:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="dni" name="dni" value="<?php if ((isset($_smarty_tpl->tpl_vars['dni']->value))) {
echo $_smarty_tpl->tpl_vars['dni']->value;
}?>" placeholder="D.N.I."/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div>
                        </div>
                    </div>                          
                    <div class="form-group">
                        <label class="font-weight-bold" for="datepicker"> Fecha de Nacimiento:</label>
                        <div class="input-group">                            
                            <input type="date" class="form-control"  name="fecha_nacimiento" value="<?php if ((isset($_smarty_tpl->tpl_vars['fecha_nacimiento']->value))) {
echo $_smarty_tpl->tpl_vars['fecha_nacimiento']->value;
}?>" />
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="email">Correo Electrónico:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="email" name="email" value="<?php if ((isset($_smarty_tpl->tpl_vars['email']->value))) {
echo $_smarty_tpl->tpl_vars['email']->value;
}?>" placeholder="Correo Electrónico"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-at"></i></div></div>
                        </div>
                    </div>
                </div>            
                <div class="col-md-6">                                                           
                    <div class="form-group">
                        <label class="font-weight-bold" for="direccion"><i class="fas fa-asterisk text-danger"></i> Dirección:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php if ((isset($_smarty_tpl->tpl_vars['direccion']->value))) {
echo $_smarty_tpl->tpl_vars['direccion']->value;
}?>" placeholder="Dirección"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="referencia_direccion"> Referencia:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="referencia_direccion" name="referencia_direccion" value="<?php if ((isset($_smarty_tpl->tpl_vars['referencia_direccion']->value))) {
echo $_smarty_tpl->tpl_vars['referencia_direccion']->value;
}?>" placeholder="Referencia de la dirección"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="profesion_ocupacion"> Profesión u Ocupación:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="profesion_ocupacion" name="profesion_ocupacion" value="<?php if ((isset($_smarty_tpl->tpl_vars['profesion_ocupacion']->value))) {
echo $_smarty_tpl->tpl_vars['profesion_ocupacion']->value;
}?>" placeholder="Prefesión u Ocupación"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-briefcase"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="direccion_trabajo"> Dirección de Trabajo:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="direccion_trabajo" name="direccion_trabajo" value="<?php if ((isset($_smarty_tpl->tpl_vars['direccion_trabajo']->value))) {
echo $_smarty_tpl->tpl_vars['direccion_trabajo']->value;
}?>" placeholder="Dirección del Trabajo"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        </div>
                    </div>                    

                    <div class="form-group">
                        <label class="font-weight-bold" for="celular_1"><i class="fas fa-asterisk text-danger"></i> Celular:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="celular_1" name="celular_1" value="<?php if ((isset($_smarty_tpl->tpl_vars['celular_1']->value))) {
echo $_smarty_tpl->tpl_vars['celular_1']->value;
}?>" placeholder="Celular"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-mobile-alt"></i></div></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="celular_2">Celular Opcional:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="celular_2" name="celular_2" value="<?php if ((isset($_smarty_tpl->tpl_vars['celular_2']->value))) {
echo $_smarty_tpl->tpl_vars['celular_2']->value;
}?>" placeholder="Celular Opcional"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-mobile-alt"></i></div></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="telefono"> Teléfono:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php if ((isset($_smarty_tpl->tpl_vars['telefono']->value))) {
echo $_smarty_tpl->tpl_vars['telefono']->value;
}?>" placeholder="Teléfono"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group box-anioescolar">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value))) {
echo $_smarty_tpl->tpl_vars['id_anio_escolar']->value;
}?></div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Alumno:</label>
                        <div class="input-group box-apoderado-matriculados"> <?php if ((isset($_smarty_tpl->tpl_vars['id_matriculados']->value))) {
echo $_smarty_tpl->tpl_vars['id_matriculados']->value;
}?> </div>
                    <div class="form-group">                            
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fa fa-save"></i> Guardar Apoderado</button>
                        <input type="hidden" name="id_apoderado" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_apoderado']->value))) {
echo $_smarty_tpl->tpl_vars['id_apoderado']->value;
}?>" />                            
                    </div>
                </div>
            </div>
        </form>        
    </section>
</div><?php }
}
