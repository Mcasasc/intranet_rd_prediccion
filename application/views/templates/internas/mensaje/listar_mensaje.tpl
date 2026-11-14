<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        {if $id_privilegio == 3 and $id_ultimo_anio_escolar >= 3}
        <div class="row m-3">
            <div class="col-md-6 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        DOCUMENTOS DE MATRÍCULA
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                {if $documentos["matricula_documento"]["mostrar_doc_1"] == "S"}
                                    <td>
                                        <a href="{$documentos["matricula_documento"]["doc_1"]}" download class="btn btn-primary ">{$documentos["matricula_documento"]["nombre_doc_1"]}</a>
                                    </td>
                                {/if}
                                {if $documentos["matricula_documento"]["mostrar_doc_2"] == "S"}
                                    <td>
                                        <a href="{$documentos["matricula_documento"]["doc_2"]}" download class="btn btn-primary ">{$documentos["matricula_documento"]["nombre_doc_2"]}</a>
                                    </td>
                                {/if}
                                {if $documentos["matricula_documento"]["mostrar_doc_3"] == "S"}
                                    <td>
                                        <a href="{$documentos["matricula_documento"]["doc_3"]}" download class="btn btn-primary ">{$documentos["matricula_documento"]["nombre_doc_3"]}</a>
                                    </td>
                                {/if}
                                {if $documentos["matricula_documento"]["mostrar_doc_4"] == "S"}
                                    <td>++
                                        <a href="{$documentos["matricula_documento"]["doc_4"]}" download class="btn btn-primary ">{$documentos["matricula_documento"]["nombre_doc_4"]}</a>
                                    </td>
                                {/if}
                                {if $documentos["matricula_documento"]["mostrar_doc_5"] == "S"}
                                    <td>
                                        <a href="{$documentos["matricula_documento"]["doc_5"]}" download class="btn btn-primary ">{$documentos["matricula_documento"]["nombre_doc_5"]}</a>
                                    </td>
                                {/if}
                                

                                {if $documentos["matricula_documento_grado"]["mostrar_doc_1"] == "S"}
                                    <td>
                                        <a href="{$documentos["matricula_documento_grado"]["doc_1"]}" download class="btn btn-primary ">{$documentos["matricula_documento_grado"]["nombre_doc_1"]}</a>
                                    </td>
                                {/if}

                                {if $documentos["matricula_documento_grado"]["mostrar_doc_2"] == "S"}
                                    <td>
                                        <a href="{$documentos["matricula_documento_grado"]["doc_2"]}" download class="btn btn-primary ">{$documentos["matricula_documento_grado"]["nombre_doc_2"]}</a>
                                    </td>
                                {/if}
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {/if}

        <div class="row m-3">
            <span class="response"></span>
      
            {if $id_privilegio == 3}
            <div class="dt-buttons btn-group combo-filtro-mensaje-anioeascolar border border-primary rounded">
                <label class="font-weight-bold pl-1 pr-2" for="combo-filtro-cpe">Año Escolar:</label>
                {if isset($combo_anio_escolar)}{$combo_anio_escolar}{/if}
                
                <a href="{$base_url}mensaje/listar" class="btn btn-primary boton-filtro-mensaje-anioeascolar">Mostrar</a>
            </div>
            {/if}

            <table class="table table-bordered table-hover" id="lista-mensajeria">
                <thead>
                    <tr style="background-color: #D8D8D8;">
                        <th>N°</th>
                        <th>Asunto </th>
                        {if $id_privilegio != 3}
                        <th>Destinatarios </th>
                        {/if}
                        <th>Fecha de Envío</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    {if !empty($lista)}
                        {foreach $lista as $l}
                        <tr class="resultado {if isset($l.leido) and $l.leido eq 0} font-weight-bold {/if}">
                            <td>{$l.numero}</td>
                            <td>{$l.asunto} {if !empty($l.archivo_adjunto)} &emsp; <span><i class="fas fa-paperclip text-primary"></i></span> {/if} </td>
                            {if $id_privilegio != 3}
                            <td>{$l.destinatarios}</td>
                            {/if}
                            <td>{$l.fecha_envio}</td>
                            <td>{$l.accion}</td>
                        </tr>
                        {/foreach}
                    {/if}
                </tbody>
            </table>
           
        </div>
    </section>
</div>

<div class="modal fade" id="modal_vistos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Vieron el mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container"> 
                    <div class="row align-items-center">
                        <div class="lista-leidos">                            
                          
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