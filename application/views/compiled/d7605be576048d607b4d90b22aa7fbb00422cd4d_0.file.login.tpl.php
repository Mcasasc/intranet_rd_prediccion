<?php
/* Smarty version 3.1.39, created on 2026-08-19 00:41:19
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\internas\login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6a84df8f14a2b0_23913596',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd7605be576048d607b4d90b22aa7fbb00422cd4d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\internas\\login.tpl',
      1 => 1787091153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a84df8f14a2b0_23913596 (Smarty_Internal_Template $_smarty_tpl) {
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
