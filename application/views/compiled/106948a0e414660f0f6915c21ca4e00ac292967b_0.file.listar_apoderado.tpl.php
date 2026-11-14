<?php
/* Smarty version 3.1.39, created on 2026-08-19 01:48:06
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\internas\apoderado\listar_apoderado.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6a84ef365a5af0_77670004',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '106948a0e414660f0f6915c21ca4e00ac292967b' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\internas\\apoderado\\listar_apoderado.tpl',
      1 => 1787091153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a84ef365a5af0_77670004 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>            
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No hay apoderados registrados</small></h1>
            <?php } else { ?>                
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th style="padding: 0px; width: 10px;">N°</th>
                            <th>Nombres y Apellidos</th>
                            <th>DNI</th>
                            <th>Parentesco</th>                            
                            <th>Celular</th>
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
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['dni'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['parentesco'];?>
</td>                                
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['celular'];?>
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
</div><?php }
}
