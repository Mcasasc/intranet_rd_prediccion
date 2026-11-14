<?php
/* Smarty version 3.1.39, created on 2026-08-19 00:41:19
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\estructura\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6a84df8f1382d9_44834412',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b4f33e46f89e8a04004aabaad0ff4c1b2cff056a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\estructura\\header.tpl',
      1 => 1787091153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a84df8f1382d9_44834412 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $_smarty_tpl->tpl_vars['titulo_pagina']->value;?>
</title>
        <link rel="icon" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/imagenes/rd-favicon.png" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/imagenes/rd-favicon.png" type="image/x-icon" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 4.2.1 -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/bootstrap/css/bootstrap.min.css"/>
        <!-- ############# BOOTSTRAP SELECT ############# -->
        <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />       
        <!-- BASE URL -->
        <?php echo '<script'; ?>
> var base_url = "<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
";<?php echo '</script'; ?>
>
        <!-- WWW-SCHOOL-->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/www-school/w3.css"/>
        <!-- Font Awesome 5.6.3 -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/font-awesome/font-awesome-all.css">        
        <!-- SWEETALERT2 -->
        <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/sweetalert/sweetalert2.min.css" rel="stylesheet" type="text/css" />        
        <!-- ############# DATERANGEPICKER ############# -->
        <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />        
        <!-- ############# DATATABLES ############# -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/datatables/datatables.min.css"/>        
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css"/>        
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/datatables/Buttons-1.5.4/css/buttons.bootstrap4.min.css"/>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/datatables/Responsive-2.2.2/css/responsive.bootstrap4.min.css"/>
        <!-- ############# BOOTSTRAP TAG INPUT ############# -->
        <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/plugins/bootstrap-tag-input/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>

        <!-- CSS PERSONALIZADO -->
        <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/style.css?v=2" rel="stylesheet" type="text/css" />        
    </head>
<?php }
}
