<?php
/* Smarty version 3.1.39, created on 2023-11-18 16:00:30
  from '/var/www/html/intranet_rd/application/views/templates/internas/matricula/listar_matricula.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6558df9eb99152_52552061',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a91de6d2b8915f599c0d18ae14d76432a681c7dc' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/matricula/listar_matricula.tpl',
      1 => 1615136814,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6558df9eb99152_52552061 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 pb-3 pl-0">
                <form class="formulario" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
matricula/matricula_anio_escolar" method="post">
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
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <?php if (empty($_smarty_tpl->tpl_vars['lista']->value)) {?>
                <h1><small>No hay matrículas registradas</small></h1>
            <?php } else { ?>
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Nombres y Apellidos</th>
                            <th>DNI</th>
                            <th>Grado y Sección</th>
                            <th>Nivel Educativo</th>
                            <th>Año Escolar</th>
                            <th>Usuario</th>
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
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['grado'];?>
 <?php echo $_smarty_tpl->tpl_vars['l']->value['seccion'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['nivel_educativo'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['anio_escolar'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['l']->value['usuario'];?>
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


<div class="modal fade" id="modal_aperturar_cuenta">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Aperturar Cuenta de <span class="nombre_alumno"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body mx-auto">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-4">
                            <label class="control-label">Monto de Pensión:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control monto-matricula" placeholder="Ingrese Monto"
                                   value="">
                            <input type="hidden" class="form-control id-matricula" value="">
                        </div>
                        <br><br><br>
                        <div class="col-md-4">
                            <label class="control-label">Monto de Matrícula:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control monto-base" placeholder="Ingrese Monto" value="">
                        </div>
                        <div class="badge badge-danger text-wrap mt-3 mensaje" style="font-size: 1.2rem;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success modal-aperturar-cuenta boton-aperturar"
                        data-dismiss="modal">Aperturar Cuenta</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php }
}
