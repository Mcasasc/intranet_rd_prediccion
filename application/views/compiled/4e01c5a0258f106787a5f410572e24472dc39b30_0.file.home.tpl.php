<?php
/* Smarty version 3.1.39, created on 2023-11-17 02:29:35
  from '/var/www/html/intranet_rd/application/views/templates/internas/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6556d00fb79bb4_69660084',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4e01c5a0258f106787a5f410572e24472dc39b30' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/home.tpl',
      1 => 1562673794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6556d00fb79bb4_69660084 (Smarty_Internal_Template $_smarty_tpl) {
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
