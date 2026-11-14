<?php
/* Smarty version 3.1.39, created on 2024-05-27 22:22:31
  from '/var/www/html/intranet_rd/application/views/templates/internas/prediccion/form_prediccion.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_665507a7b294f8_16594019',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '49e050b3ad1cfed15fc8f61bbac28933f65392c3' => 
    array (
      0 => '/var/www/html/intranet_rd/application/views/templates/internas/prediccion/form_prediccion.tpl',
      1 => 1716848541,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_665507a7b294f8_16594019 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formularioPrediccion" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
prediccion/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>

                <div class="col-md-12">
                    <div class="alert alert-warning font-weight-bolder" role="alert">
                        Predicción de Rendimiento Académico, el proceso considera los datos ingresados en este formulario y los procesa junto con toda la data histórica del alumno
                        para obtener una mejor predicción del rendimiento académico.
                    </div>
                </div>

                <div class="col-md-6">
                  
                    <div class="form-group">
                        <label class="font-weight-bold" for="examen_mensual"><i class="fas fa-asterisk text-danger"></i>Examen Mensual:</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control" name="examen_mensual" id="examen_mensual" placeholder="Ingrese su nota de examen mensual" autofocus />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-book"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="examen_bimestral"><i class="fas fa-asterisk text-danger"></i>Examen Bimestral:</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control" name="examen_bimestral" id="examen_bimestral" placeholder="Ingrese su nota de examen bimestral" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-book"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="revision_cuaderno"><i class="fas fa-asterisk text-danger"></i>Revisión de Cuadernos:</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control" name="revision_cuaderno" id="revision_cuaderno" placeholder="Ingrese su nota de revisión de cuaderno" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-book"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="participacion_tarea"><i class="fas fa-asterisk text-danger"></i>Participación y Tareas:</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control" name="participacion_tarea" id="participacion_tarea" placeholder="Ingrese su nota de participación y tareas" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-book"></i></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Promedio Psicología:</label>
                        <label class="font-italic text-muted">Generado con toda la data histórica</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="promedio_psicologia" value="<?php if ((isset($_smarty_tpl->tpl_vars['promedio_psicologia']->value))) {
echo $_smarty_tpl->tpl_vars['promedio_psicologia']->value;
}?>" readonly/>
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-users"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Promedio Asistencia a clases:</label>
                        <label class="font-italic text-muted">Generado con toda la data histórica</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="promedio_asistencia" value="<?php if ((isset($_smarty_tpl->tpl_vars['promedio_asistencia']->value))) {
echo $_smarty_tpl->tpl_vars['promedio_asistencia']->value;
}?>" readonly/>
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fas fa-save"></i> Generar Predicción</button>
                        <input type="hidden" name="id_alumno" value="<?php if ((isset($_smarty_tpl->tpl_vars['id_alumno']->value))) {
echo $_smarty_tpl->tpl_vars['id_alumno']->value;
}?>" />
                    </div>
                    
                </div>

                <div class="col-md-6 d-none" id="seccionResultadoPrediccion">
                    <div class="card bg-light mb-3">
                        <div class="card-header bg-primary text-white"><h5>Se analizaron las calificaciones ingresadas y toda la data histórica existente.</h5></div>
                        <div class="card-body">

                            <div id="mensajeEvaluacion"></div>
                            
                            <div id="nivelRendimientoAcademico"></div>

                            <h6 class="font-weight-bolder">Recomendaciones:</h6>
                            <div id="recomendaciones"></div>
                            
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </section>
</div><?php }
}
