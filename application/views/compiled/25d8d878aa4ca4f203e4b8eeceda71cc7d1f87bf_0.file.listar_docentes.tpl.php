<?php
/* Smarty version 3.1.39, created on 2026-08-20 01:19:05
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\internas\registro_auxiliar\listar_docentes.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6a8639e9adf1d5_11267086',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '25d8d878aa4ca4f203e4b8eeceda71cc7d1f87bf' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\internas\\registro_auxiliar\\listar_docentes.tpl',
      1 => 1787091153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a8639e9adf1d5_11267086 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>            
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No hay docentes registrados</small></h1>
            <?php } else { ?>                
                <table class="table table-bordered table-hover" id="no-lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Docente</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista']->value, 'l');
$_smarty_tpl->tpl_vars['l']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['l']->value) {
$_smarty_tpl->tpl_vars['l']->do_else = false;
?>
                            <tr class="resultado">
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['numero'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre_docente'];?>
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
</div><?php }
}
