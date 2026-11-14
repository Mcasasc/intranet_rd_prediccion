<?php
/* Smarty version 3.1.39, created on 2023-11-17 02:29:34
  from '/var/www/html/intranet_rd/application/views/templates/internas/login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6556d00e198946_49062327',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '397c31007e66e078c298cc98b5d2065fa51bf68e' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/login.tpl',
      1 => 1621364482,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6556d00e198946_49062327 (Smarty_Internal_Template $_smarty_tpl) {
?><body
    style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/imagenes/fondo_login.jpg'); background-size: 100% 100%; background-repeat: no-repeat;height: 100vh;display: block;">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="card">
                <img class="row h-100 justify-content-center align-items-center logo-login" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/imagenes/insignia-rd.png">
                <div class="card-body">
                    <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
login/ingresar" class="bform formulario" method="post">
                        <h4 class="card-title"></h4>
                        <span class="response"></span>
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_usuario" autofocus placeholder="Usuario">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="txt_contrasena" placeholder="Contraseña"
                                data-eye>
                        </div>
                        <div class="form-group no-margin">
                            <button class="save btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body><?php }
}
