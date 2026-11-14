<?php
/* Smarty version 3.1.39, created on 2023-11-19 01:11:28
  from '/var/www/html/intranet_rd/application/views/templates/internas/alumno/listar_alumno.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655960c08ef449_87661919',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a8e5bcb7743012cbf94cef6e83ffbe7bcf294907' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/alumno/listar_alumno.tpl',
      1 => 1584613284,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655960c08ef449_87661919 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">         
            <span class="response"></span>
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No hay alumnos registrados</small></h1>
            <?php } else { ?>
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Apellidos y Nombres</th>
                            <th>DNI</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Usuario</th>
                            <th>Fecha de Registro</th>                            
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
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre_alumno'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['dni'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['fecha_nacimiento'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['usuario'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['fecha_registro'];?>
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
