<?php
/* Smarty version 3.1.39, created on 2024-06-01 16:12:14
  from '/var/www/html/intranet_rd/application/views/templates/internas/psicologia/listar_psicologia.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_665b485eba0508_36947239',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a162b23131727538456eee9a8a85a790c974115e' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/psicologia/listar_psicologia.tpl',
      1 => 1717258330,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_665b485eba0508_36947239 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3"> 
            <span class="response"></span>
            <?php if (empty($_smarty_tpl->tpl_vars['lista_grados']->value)) {?>
            <h1><small>No hay data</small></h1>
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
                        <td>
                            <a class='btn btn-primary btn-sm' href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
psicologia/calificar/<?php echo $_smarty_tpl->tpl_vars['l']->value['id_grado'];?>
/<?php echo $_smarty_tpl->tpl_vars['l']->value['id_anio_escolar'];?>
" title='Registrar evaluación psicológica' style= 'margin: 1px;'><i class='fas fa-list-ul'></i></a>
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
