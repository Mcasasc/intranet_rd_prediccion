<?php
/* Smarty version 3.1.39, created on 2023-11-22 19:54:33
  from '/var/www/html/intranet_rd/application/views/templates/internas/mensaje/listar_mensaje.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655e5c79bafa47_30651330',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '31f82719c8fc8b4e389b3a36db9e27ef5e312a9c' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/mensaje/listar_mensaje.tpl',
      1 => 1681053472,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655e5c79bafa47_30651330 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <?php if ($_smarty_tpl->tpl_vars['id_privilegio']->value == 3 && $_smarty_tpl->tpl_vars['id_ultimo_anio_escolar']->value >= 3) {?>
        <div class="row m-3">
            <div class="col-md-6 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        DOCUMENTOS DE MATRÍCULA
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["mostrar_doc_1"] == "S") {?>
                                    <td>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["doc_1"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["nombre_doc_1"];?>
</a>
                                    </td>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["mostrar_doc_2"] == "S") {?>
                                    <td>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["doc_2"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["nombre_doc_2"];?>
</a>
                                    </td>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["mostrar_doc_3"] == "S") {?>
                                    <td>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["doc_3"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["nombre_doc_3"];?>
</a>
                                    </td>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["mostrar_doc_4"] == "S") {?>
                                    <td>++
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["doc_4"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["nombre_doc_4"];?>
</a>
                                    </td>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["mostrar_doc_5"] == "S") {?>
                                    <td>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["doc_5"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento"]["nombre_doc_5"];?>
</a>
                                    </td>
                                <?php }?>
                                

                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento_grado"]["mostrar_doc_1"] == "S") {?>
                                    <td>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento_grado"]["doc_1"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento_grado"]["nombre_doc_1"];?>
</a>
                                    </td>
                                <?php }?>

                                <?php if ($_smarty_tpl->tpl_vars['documentos']->value["matricula_documento_grado"]["mostrar_doc_2"] == "S") {?>
                                    <td>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento_grado"]["doc_2"];?>
" download class="btn btn-primary "><?php echo $_smarty_tpl->tpl_vars['documentos']->value["matricula_documento_grado"]["nombre_doc_2"];?>
</a>
                                    </td>
                                <?php }?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>

        <div class="row m-3">
            <span class="response"></span>
      
            <?php if ($_smarty_tpl->tpl_vars['id_privilegio']->value == 3) {?>
            <div class="dt-buttons btn-group combo-filtro-mensaje-anioeascolar border border-primary rounded">
                <label class="font-weight-bold pl-1 pr-2" for="combo-filtro-cpe">Año Escolar:</label>
                <?php if ((isset($_smarty_tpl->tpl_vars['combo_anio_escolar']->value))) {
echo $_smarty_tpl->tpl_vars['combo_anio_escolar']->value;
}?>
                
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
mensaje/listar" class="btn btn-primary boton-filtro-mensaje-anioeascolar">Mostrar</a>
            </div>
            <?php }?>

            <table class="table table-bordered table-hover" id="lista-mensajeria">
                <thead>
                    <tr style="background-color: #D8D8D8;">
                        <th>N°</th>
                        <th>Asunto </th>
                        <?php if ($_smarty_tpl->tpl_vars['id_privilegio']->value != 3) {?>
                        <th>Destinatarios </th>
                        <?php }?>
                        <th>Fecha de Envío</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista']->value, 'l');
$_smarty_tpl->tpl_vars['l']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['l']->value) {
$_smarty_tpl->tpl_vars['l']->do_else = false;
?>
                        <tr class="resultado <?php if ((isset($_smarty_tpl->tpl_vars['l']->value['leido'])) && $_smarty_tpl->tpl_vars['l']->value['leido'] == 0) {?> font-weight-bold <?php }?>">
                            <td><?php echo $_smarty_tpl->tpl_vars['l']->value['numero'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['l']->value['asunto'];?>
 <?php if (!empty($_smarty_tpl->tpl_vars['l']->value['archivo_adjunto'])) {?> &emsp; <span><i class="fas fa-paperclip text-primary"></i></span> <?php }?> </td>
                            <?php if ($_smarty_tpl->tpl_vars['id_privilegio']->value != 3) {?>
                            <td><?php echo $_smarty_tpl->tpl_vars['l']->value['destinatarios'];?>
</td>
                            <?php }?>
                            <td><?php echo $_smarty_tpl->tpl_vars['l']->value['fecha_envio'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['l']->value['accion'];?>
</td>
                        </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <?php }?>
                </tbody>
            </table>
           
        </div>
    </section>
</div>

<div class="modal fade" id="modal_vistos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Vieron el mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container"> 
                    <div class="row align-items-center">
                        <div class="lista-leidos">                            
                          
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
