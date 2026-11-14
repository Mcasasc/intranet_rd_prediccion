<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="{$base_url}matricula/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="dni"><i class="fas fa-asterisk text-danger"></i> DNI:</label>
                        <div class=input-group>
                            <input type="text" class="form-control dni" id="dni" name="dni" value="{if isset($dni)}{$dni}{/if}" {if $tipo == "editar"}readonly{/if} />
                            <!-- <div class="input-group-prepend {if $tipo == 'agregar'}buscar-alumno{/if}"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div> -->
                            <div class="input-group-append {if $tipo == 'agregar'}buscar-alumno{/if}" title="Buscar Alumno"><button class="btn btn-secondary"><i class="fas fa-search"></i></button></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="nombres"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                        <div class="input-group">
                            <input type="text" class="form-control nombres" id="nombres" name="nombres" value="{if isset($nombres)}{$nombres}{/if}" {if $tipo == "editar"}readonly{/if} />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-child"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="ape_paterno"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control ape_paterno" id="ape_paterno" name="ape_paterno" value="{if isset($ape_paterno)}{$ape_paterno}{/if}" {if $tipo == "editar"}readonly{/if} />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-child"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="ape_materno"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                        <div class=input-group>
                            <input type="text" class="form-control ape_materno" id="ape_materno" name="ape_materno" value="{if isset($ape_materno)}{$ape_materno}{/if}" {if $tipo == "editar"}readonly{/if} />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-child"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="usuario"><i class="fas fa-asterisk text-danger"></i> Usuario:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="usuario" id="usuario" value="{if isset($usuario)}{$usuario}{/if}" placeholder="Usuario" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="contrasena"><i class="fas fa-asterisk text-danger"></i> Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" autocomplete="new-password" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Fecha de Nacimiento:</label>
                        <div class=input-group>
                            <input type="date" class="form-control fecha_nacimiento" name="fecha_nacimiento" value="{if isset($fecha_nacimiento)}{$fecha_nacimiento}{/if}" {if $tipo == "editar"}readonly{/if} />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="direccion"><i class="fas fa-asterisk text-danger"></i> Dirección:</label>
                        <div class=input-group>
                            <input type="text" class="form-control direccion" id="direccion" name="direccion" value="{if isset($direccion)}{$direccion}{/if}" {if $tipo == "editar"}readonly{/if} />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Distrito:</label>
                        <div class="input-group">{if isset($id_distrito)}{$id_distrito}{/if}</div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class="input-group">{if isset($id_anio_escolar)}{$id_anio_escolar}{/if}</div>
                    </div>
                    <div class="form-group radio-nivel-educativo">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Nivel Educativo:</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="inicial" value="1" class="custom-control-input" {if isset($id_nivel_educativo) and $id_nivel_educativo eq 1}checked{/if}>
                            <label class="custom-control-label" for="inicial">Inicial</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="primaria" value="2" class="custom-control-input" {if isset($id_nivel_educativo) and $id_nivel_educativo eq 2}checked{/if}>
                            <label class="custom-control-label" for="primaria">Primaria</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="secundaria" value="3" class="custom-control-input" {if isset($id_nivel_educativo) and $id_nivel_educativo eq 3}checked{/if}>
                            <label class="custom-control-label" for="secundaria">Secundaria</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Grado:</label>
                        <div class="input-group ">{if isset($id_grado)}{$id_grado}{/if}</div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fa fa-save"></i> Registrar Matrícula</button>
                        <input class="id_matricula" type="hidden" name="id_matricula" value="{if isset($id_matricula)}{$id_matricula}{/if}" />
                    </div>
                </div>
            </div>
</div>
</form>
</section>
</div>


<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
    <div id="toast-encontrado" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" style="font-size: 1.5rem;">
        <div class="toast-header">
            <i class="far fa-thumbs-up"></i>
            <strong class="mr-auto text-success">¡Bien!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body text-success">
            Alumno identificado
        </div>
    </div>
</div>

<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0; font-size: 1.5rem;">
    <div id="toast-noencontrado" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000" style="font-size: 1.5rem;">
        <div class="toast-header">
            <i class="far fa-thumbs-down"></i>
            <strong class="mr-auto text-danger">¡Lo sentimos!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body text-danger">
            Alumno no identificado
        </div>
    </div>
</div>
