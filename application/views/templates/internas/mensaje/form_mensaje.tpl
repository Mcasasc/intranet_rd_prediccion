<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formularioo" action="{$base_url}mensaje/accion" method="post" id="formulario">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-12">
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Para:</label>
                                </div>
                                <div class="col-10 col-md-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control mensaje-destinatario" name="para" value="" {if isset($id_mensaje)} readonly {/if} >
                                        <input type="hidden" value="" class="tipo" name="tipo">
                                        <input type="hidden" value="" class="nombres-destinatarios" name="nombres_destinatarios">
                                        <input type="hidden" value="" class="id-anio-escolar" name="id_anio_escolar">
                                    </div>
                                </div>
                                {if !isset($id_mensaje)} 
                                    <div class="col-1 col-md-1 agregar-destino" data-toggle="modal" data-target="#modal-agregar-destino">
                                        <span class="btn btn-secondary"><i class="fas fa-plus"></i></span>
                                    </div>
                                {/if}
                            </div>

                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Asunto:</label>
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="asunto" value="{if isset($asunto)}{$asunto}{/if}">
                                    </div>
                                </div>
                                {if isset($id_mensaje) && $tiene_adjunto == si}
                                <div class="col-1 col-md-1">
                                    <div class="form-group">
                                        <span class="btn btn-social btn-secondary btn-flat ver-adjunto" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto"><i class="fas fa-paperclip"></i></span>
                                    </div>
                                </div>
                                {/if}
                            </div>

                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Adjunto:</label>
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="input-group">
                                        <!-- <input type="file" name="archivo_adjunto"> -->
                                        <div class="custom-file">
                                            <input type="file" name="adjunto" class="custom-file-input adjunto" id="adjunto" value="">
                                            <label  class="custom-file-label label-adjunto" for="adjunto">Seleccionar Archivo</label>
                                          </div>
                                    </div>
                                </div>
                                <div class="col-1 col-md-1">
                                    <div class="form-group">
                                        <!-- <button class="btn btn-social btn-primary btn-flat">Enviar</button> -->
                                        <input type="submit" id="uploadSubmit" value="Enviar" class="btn btn-social btn-primary btn-flat" />
                                        <input type="hidden" name="id_mensaje" value="{if isset($id_mensaje)}{$id_mensaje}{/if}" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-4 col-md-1"></div>
                                <div class="col-6 col-md-6">
                                    <div class="progress d-none" style="height: 18px; font-size: 14px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated font-weight-bold" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card border-primary">
                        <div id="toolbar-container"></div>
                        <div id="editor"> {if isset($contenido)}{$contenido}{/if} </div>
                        <textarea name="contenido" class="d-none contenido"> {if isset($contenido)}{$contenido}{/if} </textarea>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<div class="modal fade" id="modal-agregar-destino">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">¿A quién enviará el mensaje?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body mx-auto">
                <div class="container"> 
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-4">
                            <label class="control-label font-weight-bold">Enviar por:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="alumno" name="radio_enviar_por" value="alumno" class="custom-control-input radio-tipo">
                                <label class="custom-control-label" for="alumno">Alumno</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="grado" name="radio_enviar_por" value="grado" class="custom-control-input radio-tipo">
                                <label class="custom-control-label" for="grado">Grado</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="nivel-educativo" name="radio_enviar_por" value="nivel_educativo" class="custom-control-input radio-tipo">
                                <label class="custom-control-label" for="nivel-educativo">Nivel Educativo</label>
                              </div>
                        </div>
                        <br><br>
                        <div class="col-md-4 combo-anio-escolar">
                            <label class="control-label font-weight-bold">Año Escolar:</label>
                        </div>
                        <div class="col-md-8 combo-anio-escolar">
                            <div class="input-group">{if isset($combo_anio_escolar)}{$combo_anio_escolar}{/if}</div>
                        </div>
                        <br><br><br>
                        <div class="col-md-4">
                            <label class="control-label font-weight-bold">Seleccione:</label>
                        </div>
                        <div class="col-md-8 combo-destinatarios">
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-success boton-agregar" data-dismiss="modal">Agregar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ver-adjunto">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Archivo Adjunto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container"> 
                    <div class="row justify-content-center align-items-center">
                        <div class="archivo-adjunto">                            
                            <!-- <object data="{if isset($archivo_adjunto)} {$archivo_adjunto} {/if}" type="application/pdf" width="600" height="600"></object> -->
                            <!-- <embed src="{if isset($archivo_adjunto)} {$archivo_adjunto} {/if}" type="application/pdf" width="600" height="600" /> -->
                            <object data="{if isset($archivo_adjunto)}{$archivo_adjunto}{/if}" width="600" height="600">
                                <label class="font-weight-bold h5">El archivo se ha descargado automaticamente</label>
                                <embed src="{if isset($archivo_adjunto)}{$archivo_adjunto}{/if}" type="application/pdf" width="600" height="600">
                            </object>
                        </div>                                               
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>