<?php
/* Smarty version 3.1.39, created on 2023-11-21 03:51:16
  from '/var/www/html/intranet_rd/application/views/templates/internas/configuracion/listar_anio_aperturado.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655c29347f4ee4_55570823',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '520911accdad583b7347042368ed192a8bb914f4' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/configuracion/listar_anio_aperturado.tpl',
      1 => 1680900838,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655c29347f4ee4_55570823 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">         
            <span class="response"></span>
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No hay años aperturados</small></h1>
            <?php } else { ?>
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Año</th>
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
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre'];?>
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
<?php }
}
