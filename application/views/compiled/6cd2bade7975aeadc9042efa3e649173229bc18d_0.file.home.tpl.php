<?php
/* Smarty version 3.1.39, created on 2026-08-19 01:47:55
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\internas\home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6a84ef2bbf94a8_33585159',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6cd2bade7975aeadc9042efa3e649173229bc18d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\internas\\home.tpl',
      1 => 1787091153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a84ef2bbf94a8_33585159 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper">
    <section class="container-fluid pl-0">
        <!--<div class="row justify-content-center">           
            <img class="center-block" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/imagenes/su_logo_aqui.png">            
        </div> -->
        <div class="row m-3">
            <!-- CUADRO USUARIO -->
            <!-- <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1) {?>
                <div class="card text-white bg-primary m-3" style="max-width: 18rem;">
                    <div class="card-header">Usuario</div>
                    <div class="card-body">
                        <p class="card-text"><a class="text-white" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
usuario/agregar">Agregar Usuario</a></p>
                        <p class="card-text"><a class="text-white" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
usuario/listar">Ver Usuarios</a></p>                    
                    </div>
                </div>
            <?php }?> -->
            <!-- FIN CUADRO USUARIO -->

            <!-- CUADRO MATRICULA -->
            <!-- <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1) {?>
                <div class="card text-white bg-primary m-3" style="max-width: 18rem;">
                    <div class="card-header">Matrícula</div>
                    <div class="card-body">
                        <p class="card-text"><a class="text-white" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
matricula/agregar">Agregar Matrícula</a></p>
                        <p class="card-text"><a class="text-white" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
matricula/listar">Ver Matrículas</a></p>                    
                    </div>
                </div>
            <?php }?> -->
            <!-- FIN CUADRO MATRICULA -->
        </div>
    </section>
</div>           
<?php }
}
