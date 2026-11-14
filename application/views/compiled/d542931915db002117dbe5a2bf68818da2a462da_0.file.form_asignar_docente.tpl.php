<?php
/* Smarty version 3.1.39, created on 2026-08-20 01:22:26
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\internas\registro_auxiliar\form_asignar_docente.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6a863ab2093a07_81351840',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd542931915db002117dbe5a2bf68818da2a462da' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\internas\\registro_auxiliar\\form_asignar_docente.tpl',
      1 => 1787091153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a863ab2093a07_81351840 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
registro_auxiliar/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class="input-group"><?php if ((isset($_smarty_tpl->tpl_vars['combo_anio_escolar']->value))) {
echo $_smarty_tpl->tpl_vars['combo_anio_escolar']->value;
}?></div>
                    </div>
                    <div class="form-group radio-nivel-educativo">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Nivel Educativo:</label>
                        <div class="custom-control custom-radio custom-control-inline">                                
                            <input type="radio" name="id_nivel_educativo" id="inicial" value="1" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['id_nivel_educativo']->value)) && $_smarty_tpl->tpl_vars['id_nivel_educativo']->value == 1) {?>checked<?php }?>>
                            <label class="custom-control-label" for="inicial">Inicial</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="primaria" value="2" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['id_nivel_educativo']->value)) && $_smarty_tpl->tpl_vars['id_nivel_educativo']->value == 2) {?>checked<?php }?>>
                            <label class="custom-control-label" for="primaria">Primaria</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="secundaria" value="3" class="custom-control-input" <?php if ((isset($_smarty_tpl->tpl_vars['id_nivel_educativo']->value)) && $_smarty_tpl->tpl_vars['id_nivel_educativo']->value == 3) {?>checked<?php }?>>
                            <label class="custom-control-label" for="secundaria">Secundaria</label>
                        </div>
                    </div>
                    <div class="form-group box_grado">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Grado:</label>
                        <div class="input-group "><?php if ((isset($_smarty_tpl->tpl_vars['combo_grado']->value))) {
echo $_smarty_tpl->tpl_vars['combo_grado']->value;
}?></div>
                    </div>
                    
                    <div class="form-group" >
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Curso:</label>
                        <div class="input-group "><?php if ((isset($_smarty_tpl->tpl_vars['combo_curso']->value))) {
echo $_smarty_tpl->tpl_vars['combo_curso']->value;
}?></div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Docente:</label>
                        <div class="input-group "><?php if ((isset($_smarty_tpl->tpl_vars['combo_docente']->value))) {
echo $_smarty_tpl->tpl_vars['combo_docente']->value;
}?></div>
                    </div>
                                                       
                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                        <i class="fa fa-save"></i> Asignar Docente</button>
                        <input type="hidden" name="id_curso_docente" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_curso_docente']->value))) {
echo $_smarty_tpl->tpl_vars['id_curso_docente']->value;
}?>" />
                    </div>                            
                </div>
            </div>
        </form>        
    </section>
</div><?php }
}
