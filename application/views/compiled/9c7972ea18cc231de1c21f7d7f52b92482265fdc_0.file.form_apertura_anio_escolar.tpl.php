<?php
/* Smarty version 3.1.39, created on 2023-11-21 03:51:14
  from '/var/www/html/intranet_rd/application/views/templates/internas/configuracion/form_apertura_anio_escolar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655c2932bd16e2_69569531',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9c7972ea18cc231de1c21f7d7f52b92482265fdc' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/configuracion/form_apertura_anio_escolar.tpl',
      1 => 1680994574,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655c2932bd16e2_69569531 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
configuracion/accion_apertura_anio_escolar" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="anio_escolar"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class=input-group>
                            <input type="text" class="form-control" id="anio_escolar" name="anio_escolar" value="<?php if ((isset($_smarty_tpl->tpl_vars['nombre_anio_escolar']->value))) {
echo $_smarty_tpl->tpl_vars['nombre_anio_escolar']->value;
}?>" autofocus />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header m-0">Documentos Generales</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#doc_uno">Documento 1</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_dos">Documento 2</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_tres">Documento 3</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_cuatro">Documento 4</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_cinco">Documento 5</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="doc_uno" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 1:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_1" value="<?php if ((isset($_smarty_tpl->tpl_vars['gen_nombre_doc_1']->value))) {
echo $_smarty_tpl->tpl_vars['gen_nombre_doc_1']->value;
}?>">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_1" value="" id="gen_doc_1">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_1">Seleccionar Archivo</label>
                                                </div>
                                                <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gen_tiene_adjunto_1']->value == "si") {?>
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gen_doc_1']->value;?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gen_nombre_doc_1']->value;?>
">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_1" id="h_gen_mostrar_doc_1" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_1']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_1']->value == "S") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_1">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_1" id="d_gen_mostrar_doc_1" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_1']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_1']->value == "N") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_1">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_dos" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 2:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_2" value="<?php if ((isset($_smarty_tpl->tpl_vars['gen_nombre_doc_2']->value))) {
echo $_smarty_tpl->tpl_vars['gen_nombre_doc_2']->value;
}?>">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_2" value="" id="gen_doc_2">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_2">Seleccionar Archivo</label>
                                                </div>
                                                <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gen_tiene_adjunto_2']->value == "si") {?>
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gen_doc_2']->value;?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gen_nombre_doc_2']->value;?>
">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_2" id="h_gen_mostrar_doc_2" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_2']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_2']->value == "S") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_2">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_2" id="d_gen_mostrar_doc_2" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_2']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_2']->value == "N") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_2">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_tres" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 3:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_3" value="<?php if ((isset($_smarty_tpl->tpl_vars['gen_nombre_doc_3']->value))) {
echo $_smarty_tpl->tpl_vars['gen_nombre_doc_3']->value;
}?>">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_3" value="" id="gen_doc_3">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_3">Seleccionar Archivo</label>
                                                </div>
                                                <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gen_tiene_adjunto_3']->value == "si") {?>
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gen_doc_3']->value;?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gen_nombre_doc_3']->value;?>
">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_3" id="h_gen_mostrar_doc_3" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_3']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_3']->value == "S") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_3">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_3" id="d_gen_mostrar_doc_3" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_3']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_3']->value == "N") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_3">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_cuatro" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 4:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_4" value="<?php if ((isset($_smarty_tpl->tpl_vars['gen_nombre_doc_4']->value))) {
echo $_smarty_tpl->tpl_vars['gen_nombre_doc_4']->value;
}?>">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_4" value="" id="gen_doc_4">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_4">Seleccionar Archivo</label>
                                                </div>
                                                <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gen_tiene_adjunto_4']->value == "si") {?>
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gen_doc_4']->value;?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gen_nombre_doc_4']->value;?>
">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_4" id="h_gen_mostrar_doc_4" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_4']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_4']->value == "S") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_4">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_4" id="d_gen_mostrar_doc_4" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_4']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_4']->value == "N") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_4">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_cinco" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 5:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_5" value="<?php if ((isset($_smarty_tpl->tpl_vars['gen_nombre_doc_5']->value))) {
echo $_smarty_tpl->tpl_vars['gen_nombre_doc_5']->value;
}?>">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_5" value="" id="gen_doc_5">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_5">Seleccionar Archivo</label>
                                                </div>
                                                <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gen_tiene_adjunto_5']->value == "si") {?>
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gen_doc_5']->value;?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gen_nombre_doc_5']->value;?>
">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_5" id="h_gen_mostrar_doc_5" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_5']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_5']->value == "S") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_5">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_5" id="d_gen_mostrar_doc_5" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gen_mostrar_doc_5']->value)) && $_smarty_tpl->tpl_vars['gen_mostrar_doc_5']->value == "N") {?>checked<?php }?>>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_5">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header m-0">Documentos Primaria</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab" role="tablist">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grados_primaria']->value, 'gp', false, 'clave');
$_smarty_tpl->tpl_vars['gp']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['clave']->value => $_smarty_tpl->tpl_vars['gp']->value) {
$_smarty_tpl->tpl_vars['gp']->do_else = false;
?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['clave']->value == 0) {?>active<?php }?>" data-toggle="tab" href="#<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
"><?php echo $_smarty_tpl->tpl_vars['gp']->value['nombre'];?>
</a>
                                    </li>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grados_primaria']->value, 'gp', false, 'clave');
$_smarty_tpl->tpl_vars['gp']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['clave']->value => $_smarty_tpl->tpl_vars['gp']->value) {
$_smarty_tpl->tpl_vars['gp']->do_else = false;
?>
                                    <div class="tab-pane fade <?php if ($_smarty_tpl->tpl_vars['clave']->value == 0) {?>show active<?php }?>" id="<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
" role="tabpanel">
                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 1:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
][nombre_doc_1]" value="<?php if ((isset($_smarty_tpl->tpl_vars['gp']->value['nombre_doc_1']))) {
echo $_smarty_tpl->tpl_vars['gp']->value['nombre_doc_1'];
}?>">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
[doc_1]" value="" id="doc_1_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">
                                                        <label  class="custom-file-label label-adjunto" for="doc_1_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">Seleccionar Archivo</label>
                                                    </div>
                                                    <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gp']->value['tiene_adjunto_1'] == "si") {?>
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gp']->value['doc_1'];?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gp']->value['nombre_doc_1'];?>
">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
][mostrar_doc_1]" id="h_doc_1_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_1'])) && $_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_1'] == "S") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="h_doc_1_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
][mostrar_doc_1]" id="d_doc_1_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_1'])) && $_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_1'] == "N") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="d_doc_1_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 2:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
][nombre_doc_2]" value="<?php if ((isset($_smarty_tpl->tpl_vars['gp']->value['nombre_doc_2']))) {
echo $_smarty_tpl->tpl_vars['gp']->value['nombre_doc_2'];
}?>">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
[doc_2]" value="" id="doc_2_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">
                                                        <label  class="custom-file-label label-adjunto" for="doc_2_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">Seleccionar Archivo</label>
                                                    </div>
                                                    <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gp']->value['tiene_adjunto_2'] == "si") {?>
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gp']->value['doc_2'];?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gp']->value['nombre_doc_2'];?>
">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
][mostrar_doc_2]" id="h_doc_2_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_2'])) && $_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_2'] == "S") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="h_doc_2_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
][mostrar_doc_2]" id="d_doc_2_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_2'])) && $_smarty_tpl->tpl_vars['gp']->value['mostrar_doc_2'] == "N") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="d_doc_2_<?php echo $_smarty_tpl->tpl_vars['gp']->value['codigo'];?>
">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header m-0">Documentos Secundaria</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab2" role="tablist">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grados_secundaria']->value, 'gs', false, 'clave_s');
$_smarty_tpl->tpl_vars['gs']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['clave_s']->value => $_smarty_tpl->tpl_vars['gs']->value) {
$_smarty_tpl->tpl_vars['gs']->do_else = false;
?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['clave_s']->value == 0) {?>active<?php }?>" data-toggle="tab" href="#<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
"><?php echo $_smarty_tpl->tpl_vars['gs']->value['nombre'];?>
</a>
                                    </li>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grados_secundaria']->value, 'gs', false, 'clave_s');
$_smarty_tpl->tpl_vars['gs']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['clave_s']->value => $_smarty_tpl->tpl_vars['gs']->value) {
$_smarty_tpl->tpl_vars['gs']->do_else = false;
?>
                                    <div class="tab-pane fade <?php if ($_smarty_tpl->tpl_vars['clave_s']->value == 0) {?>show active<?php }?>" id="<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
" role="tabpanel">
                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 1:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
][nombre_doc_1]" value="<?php if ((isset($_smarty_tpl->tpl_vars['gs']->value['nombre_doc_1']))) {
echo $_smarty_tpl->tpl_vars['gs']->value['nombre_doc_1'];
}?>">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
[doc_1]" value="" id="doc_1_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">
                                                        <label  class="custom-file-label label-adjunto" for="doc_1_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">Seleccionar Archivo</label>
                                                    </div>
                                                    <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gs']->value['tiene_adjunto_1'] == "si") {?>
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gs']->value['doc_1'];?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gs']->value['nombre_doc_1'];?>
">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
][mostrar_doc_1]" id="h_doc_1_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_1'])) && $_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_1'] == "S") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="h_doc_1_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
][mostrar_doc_1]" id="d_doc_1_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_1'])) && $_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_1'] == "N") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="d_doc_1_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 2:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
][nombre_doc_2]" value="<?php if ((isset($_smarty_tpl->tpl_vars['gs']->value['nombre_doc_2']))) {
echo $_smarty_tpl->tpl_vars['gs']->value['nombre_doc_2'];
}?>">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
[doc_2]" value="" id="doc_2_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">
                                                        <label  class="custom-file-label label-adjunto" for="doc_2_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">Seleccionar Archivo</label>
                                                    </div>
                                                    <?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value)) && $_smarty_tpl->tpl_vars['gs']->value['tiene_adjunto_2'] == "si") {?>
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="<?php echo $_smarty_tpl->tpl_vars['gs']->value['doc_2'];?>
" data-nombre="<?php echo $_smarty_tpl->tpl_vars['gs']->value['nombre_doc_2'];?>
">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
][mostrar_doc_2]" id="h_doc_2_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
" value="S" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_2'])) && $_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_2'] == "S") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="h_doc_2_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
][mostrar_doc_2]" id="d_doc_2_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
" value="N" class="custom-control-input radio-deshabilitado" <?php if ((isset($_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_2'])) && $_smarty_tpl->tpl_vars['gs']->value['mostrar_doc_2'] == "N") {?>checked<?php }?>>
                                                        <label class="custom-control-label" for="d_doc_2_<?php echo $_smarty_tpl->tpl_vars['gs']->value['codigo'];?>
">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-social btn-primary">
                    <i class="fa fa-save"></i> Registrar Año Escolar
                </button>
                <input type="hidden" value="<?php if ((isset($_smarty_tpl->tpl_vars['tipo']->value))) {
echo $_smarty_tpl->tpl_vars['tipo']->value;
}?>" class="tipo" name="tipo">
                <input type="hidden" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_anio_escolar']->value))) {
echo $_smarty_tpl->tpl_vars['id_anio_escolar']->value;
}?>" name="id_anio_escolar">
            </div>
        </form>
    </section>
</div>

<!-- MODAL VER ADJUNTO -->
<div class="modal fade" id="modal-ver-adjunto-conf">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Archivo Adjunto <span class="titulo-adjunto"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container"> 
                    <div class="row justify-content-center align-items-center">
                        <div class="archivo-adjunto-conf">
                        </div>                                               
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div><?php }
}
