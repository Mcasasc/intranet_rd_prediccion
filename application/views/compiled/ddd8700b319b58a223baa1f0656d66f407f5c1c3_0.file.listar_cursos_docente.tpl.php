<?php
/* Smarty version 3.1.39, created on 2023-11-17 02:47:20
  from '/var/www/html/intranet_rd/application/views/templates/internas/registro_auxiliar/listar_cursos_docente.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6556d438149c05_70069994',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ddd8700b319b58a223baa1f0656d66f407f5c1c3' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/registro_auxiliar/listar_cursos_docente.tpl',
      1 => 1615535876,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6556d438149c05_70069994 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="pb-3 pl-0">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Mostrar por:
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th>Año Escolar</th>
                                    <td><?php echo $_smarty_tpl->tpl_vars['combo_anio_escolar']->value;?>
</td>
                                    <input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['id_docente']->value;?>
" id="id_docente_cursos">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No tiene cursos asignados</small></h1>
            <?php } else { ?>

                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 2) {?>
                    <table class="table table-bordered table-hover" id="no-lista">
                    <?php } else { ?>
                        <table class="table table-bordered table-hover" id="lista">
                        <?php }?>
                        <thead>
                            <tr style="background-color: #D8D8D8;">
                                <th>N°</th>
                                <th>Curso</th>
                                <th>Nivel Educativo</th>
                                <th>Grado</th>
                                <th>Sección</th>
                                <th>Año Escolar</th>
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
                                    <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre_curso'];?>
</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre_nivel_educativo'];?>
</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre_grado'];?>
</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['l']->value['seccion_grado'];?>
</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nombre_anio_escolar'];?>
</td>
                                    <td>
                                        <?php echo $_smarty_tpl->tpl_vars['l']->value['accion'];?>

                                        <a class="btn btn-warning btn-sm boton-calificar" data-toggle="modal" title="Calificar" data-target="#modal_calificar_curso" style="margin: 1px;" data-idcurso="<?php echo $_smarty_tpl->tpl_vars['l']->value['id_curso'];?>
" data-idanioescolar="<?php echo $_smarty_tpl->tpl_vars['l']->value['id_anio_escolar'];?>
"><i class="fas fa-tasks"></i></a>
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

<!-- Modal -->
<div class="modal fade" id="modal_calificar_curso">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calificar Curso de <span class="nombre-curso"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body mx-auto">
                <div class="container">
                    <div class="row justify-content-center align-items-center contenido-calificar">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->
<?php }
}
