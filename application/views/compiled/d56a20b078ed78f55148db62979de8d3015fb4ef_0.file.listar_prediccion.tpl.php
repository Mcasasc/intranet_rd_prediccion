<?php
/* Smarty version 3.1.39, created on 2024-05-26 01:21:01
  from '/var/www/html/intranet_rd/application/views/templates/internas/prediccion/listar_prediccion.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_66528e7dd1c772_82075561',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd56a20b078ed78f55148db62979de8d3015fb4ef' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/prediccion/listar_prediccion.tpl',
      1 => 1716686456,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66528e7dd1c772_82075561 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">         
            <span class="response"></span>
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No hay predicciones registradas</small></h1>
            <?php } else { ?>
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Nombres y Apellidos</th>
                            <th>Prom. Nota Ingresada</th>
                            <th>Prom. Nota Histórica</th>
                            <th>Prom. Nota Final</th>
                            <th>Prom. Psicología</th>
                            <th>Prom. Asistencia</th>
                            <th>Predicc. Nivel</th>
                            <th>Predicc. Recomendaciones</th>
                            <th>Fecha de Registro</th>
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
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['promedio_nota_ingresada'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['promedio_nota_historica'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['promedio_nota_final'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['promedio_psicologia'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['promedio_asistencia'];?>
</td>
                                <td> <h6><span class="<?php echo $_smarty_tpl->tpl_vars['l']->value['color_nivel_ld'];?>
"><?php echo $_smarty_tpl->tpl_vars['l']->value['respuesta_nivel_ld'];?>
</span> </td></h6>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['respuesta_recomendacion_ld'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['agregado'];?>
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
