<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="{$base_url}mensaje/accion" method="post" enctype="multipart/form-data">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-12">
                    <div class="card border-primary"> 
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Asunto:</label>
                                </div>
                                <div class="col-9 col-md-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="asunto" value="{if isset($asunto)}{$asunto}{/if}" readonly>
                                    </div>
                                </div>                                
                            </div>

                            <div class="form-group row">
                                <div class="col-4 col-md-1">
                                    <label class="font-weight-bold">Adjunto:</label>
                                </div>
                                {if isset($id_mensaje) && $tiene_adjunto == si}
                                <div class="col-1 col-md-1">
                                    <div class="form-group">
                                        <span class="btn btn-social btn-secondary btn-flat ver-adjunto" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto"><i class="fas fa-paperclip"></i></span>
                                    </div>
                                </div>
                                {else}
                                <label>Este mensaje no contiene archivo adjunto</label>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card border-primary">
                        <div id="toolbar-container"></div>
                        <div id="visor"> {if isset($contenido)}{$contenido}{/if} </div>
                        <textarea name="contenido" class="d-none contenido"> {if isset($contenido)}{$contenido}{/if} </textarea>
                    </div>
                </div>
            </div>
        </form>
    </section>
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
                            <!-- <object data="{if isset($archivo_adjunto)} {$archivo_adjunto} {/if}" width="600" height="600"></object> -->
                            <!-- <embed src="{if isset($archivo_adjunto)} {$archivo_adjunto} {/if}" type="application/pdf" width="600" height="600" /> -->
                            <object data="{if isset($archivo_adjunto)} {$archivo_adjunto} {/if}" width="600" height="600">
                                <label class="font-weight-bold h5">El archivo se ha descargado automaticamente</label>
                                <embed src="{if isset($archivo_adjunto)} {$archivo_adjunto} {/if}" type="application/pdf" width="600" height="600" />
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