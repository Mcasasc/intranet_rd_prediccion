<?php
/* Smarty version 3.1.39, created on 2023-11-19 03:45:04
  from '/var/www/html/intranet_rd/application/views/templates/internas/nota/registro_secundaria.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_655984c07e03b0_02517605',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5a48301a8747b47d1e59f0ee7785d0ec92988a18' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/nota/registro_secundaria.tpl',
      1 => 1700365370,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_655984c07e03b0_02517605 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0 pr-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        <?php echo $_smarty_tpl->tpl_vars['nombre_curso']->value;?>
 <button type="button" class="btn btn-warning" onClick="window.location.reload();">PROMEDIAR NOTAS </button>
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th>Bimestre</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_bimestre']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Grado y Sección</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_grado']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Nivel Educativo</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_nivel_educativo']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Docente</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_docente']->value;?>
</td>
                            </tr>
                            <tr>
                                <th>Año Escolar</th>
                                <td><?php echo $_smarty_tpl->tpl_vars['nombre_anio_escolar']->value;?>
</td>
                            </tr>
                            <input type="hidden" class="registro-secundaria-curso" name="id_curso" value="<?php echo $_smarty_tpl->tpl_vars['id_curso']->value;?>
">
                            <input type="hidden" class="registro-secundaria-bimestre" name="id_bimestre" value="<?php echo $_smarty_tpl->tpl_vars['id_bimestre']->value;?>
">
                            <input type="hidden" class="registro-secundaria-anioescolar" name="id_anio_escolar" value="<?php echo $_smarty_tpl->tpl_vars['id_anio_escolar']->value;?>
">
                            <input type="hidden" class="registro-secundaria-grado" name="id_grado" value="<?php echo $_smarty_tpl->tpl_vars['id_grado']->value;?>
">
                            <input type="hidden" class="registro-secundaria-niveleducativo" name="id_nivel_educativo" value="<?php echo $_smarty_tpl->tpl_vars['id_nivel_educativo']->value;?>
">
                            <input type="hidden" class="registro-secundaria-numero-concepto" value="">
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Conceptos
                    </div>
                    <div class="card-body p-2 card-body-ips">                        
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-secundaria" data-concepto="1">Participación y Tareas</button>
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-secundaria" data-concepto="2">Asistencia y Prácticas Diarias</button>
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-secundaria" data-concepto="3">Revisión de Cuaderno y Conducta</button>
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-secundaria" data-concepto="4">Exámen Mensual, Bimestral y Promedio Final</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Leyenda
                    </div>
                    <div class="d-flex justify-content-center hide-spinner" id="spinner-carga">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="card-body p-2 card-body-ips">                        
                        <div class="list-group contenido-leyenda">
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        <div class="row m-3">            
                <table class="table table-bordered table-striped table-sm mb-0">
                    <thead class="thead-nota">
                        <tr class="tr-nota" id="encabezado-notas">
                            <th class="th-numero">N°</th>
                            <th class="th-alumno">Apellidos y Nombres</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-nota">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista_alumnos']->value, 'la');
$_smarty_tpl->tpl_vars['la']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['la']->value) {
$_smarty_tpl->tpl_vars['la']->do_else = false;
?>
                        <tr id="fila_<?php echo $_smarty_tpl->tpl_vars['la']->value['numero'];?>
">
                            <td class="td-numero"><?php echo $_smarty_tpl->tpl_vars['la']->value['numero'];?>
</td>
                            <td class="td-alumno"><?php echo $_smarty_tpl->tpl_vars['la']->value['nombre_alumno'];?>
</td>                      
                        </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>            
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?php }
}
