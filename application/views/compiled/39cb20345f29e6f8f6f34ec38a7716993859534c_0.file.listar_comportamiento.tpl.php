<?php
/* Smarty version 3.1.39, created on 2023-11-18 17:00:11
  from '/var/www/html/intranet_rd/application/views/templates/internas/comportamiento/listar_comportamiento.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6558ed9bbf18f6_47391326',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '39cb20345f29e6f8f6f34ec38a7716993859534c' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/comportamiento/listar_comportamiento.tpl',
      1 => 1580028382,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6558ed9bbf18f6_47391326 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3"> 
            <span class="response"></span>
            <?php if (empty($_smarty_tpl->tpl_vars['lista_grados']->value)) {?>
            <h1><small>No hay comportamiento aperturado</small></h1>
            <?php } else { ?>
            <table class="table table-bordered table-hover" id="lista">            
                <thead>
                    <tr style="background-color: #D8D8D8;">
                        <th>N°</th>                        
                        <th>Grado y Sección</th>                        
                        <th>Nivel Educativo</th>
                        <th>Año Escolar</th>
                        <th>Acción</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista_grados']->value, 'l');
$_smarty_tpl->tpl_vars['l']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['l']->value) {
$_smarty_tpl->tpl_vars['l']->do_else = false;
?>
                    <tr class="resultado">
                        <td><?php echo $_smarty_tpl->tpl_vars['l']->value['numero'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['l']->value['grado'];?>
 <?php echo $_smarty_tpl->tpl_vars['l']->value['seccion'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nivel_educativo'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['l']->value['anio_escolar'];?>
</td>
                        <td><?php echo $_smarty_tpl->tpl_vars['l']->value['accion'];?>
</td>
                    </tr>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </tbody>
            </table>
            <?php }?>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_comportamiento">
    <div class="modal-dialog">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">Calificar Comportamiento de <span class="nombre-grado"></span></h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
             </button>
          </div>
          <div class="modal-body mx-auto">
             <div class="container">
                <div class="row justify-content-center align-items-center contenido-calificar">
                </div>
             </div>
          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
       </div>
    </div>
 </div>
 <!-- Fin Modal --><?php }
}
