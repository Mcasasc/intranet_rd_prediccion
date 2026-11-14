<?php
/* Smarty version 3.1.39, created on 2023-11-27 17:18:41
  from '/var/www/html/intranet_rd/application/views/templates/internas/mensaje/ver_mensaje.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6564cf717c7ce4_97390555',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6770d8d01e249642d0c73025cf140c1d9ec1a16c' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/mensaje/ver_mensaje.tpl',
      1 => 1585841900,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6564cf717c7ce4_97390555 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
mensaje/accion" method="post" enctype="multipart/form-data">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-12">
                    <div class="card border-primary"> 
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Asunto:</label>
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="asunto" value="<?php if ((isset($_smarty_tpl->tpl_vars['asunto']->value))) {
echo $_smarty_tpl->tpl_vars['asunto']->value;
}?>" readonly>
                                    </div>
                                </div>                                
                            </div>

                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Adjunto:</label>
                                </div>
                                <?php if ((isset($_smarty_tpl->tpl_vars['id_mensaje']->value)) && $_smarty_tpl->tpl_vars['tiene_adjunto']->value == 'si') {?>
                                <div class="col-1 col-md-1">
                                    <div class="form-group">
                                        <span class="btn btn-social btn-secondary btn-flat ver-adjunto" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto"><i class="fas fa-paperclip"></i></span>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <label>Este mensaje no contiene archivo adjunto</label>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card border-primary">
                        <div id="toolbar-container"></div>
                        <div id="visor"> <?php if ((isset($_smarty_tpl->tpl_vars['contenido']->value))) {
echo $_smarty_tpl->tpl_vars['contenido']->value;
}?> </div>
                        <textarea name="contenido" class="d-none contenido"> <?php if ((isset($_smarty_tpl->tpl_vars['contenido']->value))) {
echo $_smarty_tpl->tpl_vars['contenido']->value;
}?> </textarea>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<div class="modal fade" id="modal-ver-adjunto">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Archivo Adjunto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container"> 
                    <div class="row justify-content-center align-items-center">
                        <div class="archivo-adjunto">                            
                            <!-- <object data="<?php if ((isset($_smarty_tpl->tpl_vars['archivo_adjunto']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['archivo_adjunto']->value;?>
 <?php }?>" width="600" height="600"></object> -->
                            <!-- <embed src="<?php if ((isset($_smarty_tpl->tpl_vars['archivo_adjunto']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['archivo_adjunto']->value;?>
 <?php }?>" type="application/pdf" width="600" height="600" /> -->
                            <object data="<?php if ((isset($_smarty_tpl->tpl_vars['archivo_adjunto']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['archivo_adjunto']->value;?>
 <?php }?>" width="600" height="600">
                                <label class="font-weight-bold h5">El archivo se ha descargado automaticamente</label>
                                <embed src="<?php if ((isset($_smarty_tpl->tpl_vars['archivo_adjunto']->value))) {?> <?php echo $_smarty_tpl->tpl_vars['archivo_adjunto']->value;?>
 <?php }?>" type="application/pdf" width="600" height="600" />
                            </object>
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
