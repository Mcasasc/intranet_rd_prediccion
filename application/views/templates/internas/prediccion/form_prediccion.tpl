<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formularioPrediccion" action="{$base_url}prediccion/accion" method="post">
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
                            <input type="text" class="form-control" name="promedio_psicologia" value="{if isset($promedio_psicologia)}{$promedio_psicologia}{/if}" readonly/>
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-users"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Promedio Asistencia a clases:</label>
                        <label class="font-italic text-muted">Generado con toda la data histórica</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="promedio_asistencia" value="{if isset($promedio_asistencia)}{$promedio_asistencia}{/if}" readonly/>
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fas fa-save"></i> Generar Predicción</button>
                        <input type="hidden" name="id_alumno" value="{if isset($id_alumno)}{$id_alumno}{/if}" />
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
</div>