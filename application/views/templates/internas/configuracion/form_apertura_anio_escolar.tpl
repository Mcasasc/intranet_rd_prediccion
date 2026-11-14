<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="{$base_url}configuracion/accion_apertura_anio_escolar" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold" for="anio_escolar"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class=input-group>
                            <input type="text" class="form-control" id="anio_escolar" name="anio_escolar" value="{if isset($nombre_anio_escolar)}{$nombre_anio_escolar}{/if}" autofocus />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header m-0">Documentos Generales</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#doc_uno">Documento 1</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_dos">Documento 2</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_tres">Documento 3</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_cuatro">Documento 4</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#doc_cinco">Documento 5</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="doc_uno" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 1:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_1" value="{if isset($gen_nombre_doc_1)}{$gen_nombre_doc_1}{/if}">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_1" value="" id="gen_doc_1">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_1">Seleccionar Archivo</label>
                                                </div>
                                                {if isset($id_anio_escolar) && $gen_tiene_adjunto_1 == "si"}
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gen_doc_1}" data-nombre="{$gen_nombre_doc_1}">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_1" id="h_gen_mostrar_doc_1" value="S" class="custom-control-input" {if isset($gen_mostrar_doc_1) and $gen_mostrar_doc_1 == "S"}checked{/if}>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_1">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_1" id="d_gen_mostrar_doc_1" value="N" class="custom-control-input radio-deshabilitado" {if isset($gen_mostrar_doc_1) and $gen_mostrar_doc_1 == "N"}checked{/if}>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_1">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_dos" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 2:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_2" value="{if isset($gen_nombre_doc_2)}{$gen_nombre_doc_2}{/if}">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_2" value="" id="gen_doc_2">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_2">Seleccionar Archivo</label>
                                                </div>
                                                {if isset($id_anio_escolar) && $gen_tiene_adjunto_2 == "si"}
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gen_doc_2}" data-nombre="{$gen_nombre_doc_2}">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_2" id="h_gen_mostrar_doc_2" value="S" class="custom-control-input" {if isset($gen_mostrar_doc_2) and $gen_mostrar_doc_2 == "S"}checked{/if}>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_2">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_2" id="d_gen_mostrar_doc_2" value="N" class="custom-control-input radio-deshabilitado" {if isset($gen_mostrar_doc_2) and $gen_mostrar_doc_2 == "N"}checked{/if}>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_2">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_tres" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 3:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_3" value="{if isset($gen_nombre_doc_3)}{$gen_nombre_doc_3}{/if}">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_3" value="" id="gen_doc_3">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_3">Seleccionar Archivo</label>
                                                </div>
                                                {if isset($id_anio_escolar) && $gen_tiene_adjunto_3 == "si"}
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gen_doc_3}" data-nombre="{$gen_nombre_doc_3}">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_3" id="h_gen_mostrar_doc_3" value="S" class="custom-control-input" {if isset($gen_mostrar_doc_3) and $gen_mostrar_doc_3 == "S"}checked{/if}>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_3">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_3" id="d_gen_mostrar_doc_3" value="N" class="custom-control-input radio-deshabilitado" {if isset($gen_mostrar_doc_3) and $gen_mostrar_doc_3 == "N"}checked{/if}>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_3">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_cuatro" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 4:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_4" value="{if isset($gen_nombre_doc_4)}{$gen_nombre_doc_4}{/if}">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_4" value="" id="gen_doc_4">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_4">Seleccionar Archivo</label>
                                                </div>
                                                {if isset($id_anio_escolar) && $gen_tiene_adjunto_4 == "si"}
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gen_doc_4}" data-nombre="{$gen_nombre_doc_4}">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_4" id="h_gen_mostrar_doc_4" value="S" class="custom-control-input" {if isset($gen_mostrar_doc_4) and $gen_mostrar_doc_4 == "S"}checked{/if}>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_4">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_4" id="d_gen_mostrar_doc_4" value="N" class="custom-control-input radio-deshabilitado" {if isset($gen_mostrar_doc_4) and $gen_mostrar_doc_4 == "N"}checked{/if}>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_4">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="doc_cinco" role="tabpanel">
                                    <div class="form-group row mt-5">
                                        <div class="col-4 col-md-1">
                                            <label class="font-weight-bold">Adjunto 5:</label>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="gen_nombre_doc_5" value="{if isset($gen_nombre_doc_5)}{$gen_nombre_doc_5}{/if}">
                                            </div>
                                        </div>
                                        <div class="col-9 col-md-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input adjunto" name="gen_doc_5" value="" id="gen_doc_5">
                                                    <label  class="custom-file-label label-adjunto" for="gen_doc_5">Seleccionar Archivo</label>
                                                </div>
                                                {if isset($id_anio_escolar) && $gen_tiene_adjunto_5 == "si"}
                                                    <div class="col-1 col-md-1">
                                                        <div class="form-group">
                                                            <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gen_doc_5}" data-nombre="{$gen_nombre_doc_5}">
                                                                <i class="fas fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-1 col-md-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_5" id="h_gen_mostrar_doc_5" value="S" class="custom-control-input" {if isset($gen_mostrar_doc_5) and $gen_mostrar_doc_5 == "S"}checked{/if}>
                                                    <label class="custom-control-label" for="h_gen_mostrar_doc_5">Habilitado</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="gen_mostrar_doc_5" id="d_gen_mostrar_doc_5" value="N" class="custom-control-input radio-deshabilitado" {if isset($gen_mostrar_doc_5) and $gen_mostrar_doc_5 == "N"}checked{/if}>
                                                    <label class="custom-control-label" for="d_gen_mostrar_doc_5">Deshabilitado</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            
            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header m-0">Documentos Primaria</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab" role="tablist">
                                {foreach $grados_primaria as $clave => $gp}
                                    <li class="nav-item">
                                        <a class="nav-link {if $clave == 0}active{/if}" data-toggle="tab" href="#{$gp.codigo}">{$gp.nombre}</a>
                                    </li>
                                {/foreach}
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                {foreach $grados_primaria as $clave => $gp}
                                    <div class="tab-pane fade {if $clave == 0}show active{/if}" id="{$gp.codigo}" role="tabpanel">
                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 1:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[{$gp.codigo}][nombre_doc_1]" value="{if isset($gp.nombre_doc_1)}{$gp.nombre_doc_1}{/if}">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="{$gp.codigo}[doc_1]" value="" id="doc_1_{$gp.codigo}">
                                                        <label  class="custom-file-label label-adjunto" for="doc_1_{$gp.codigo}">Seleccionar Archivo</label>
                                                    </div>
                                                    {if isset($id_anio_escolar) && $gp.tiene_adjunto_1 == "si"}
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gp.doc_1}" data-nombre="{$gp.nombre_doc_1}">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gp.codigo}][mostrar_doc_1]" id="h_doc_1_{$gp.codigo}" value="S" class="custom-control-input" {if isset($gp.mostrar_doc_1) and $gp.mostrar_doc_1 == "S"}checked{/if}>
                                                        <label class="custom-control-label" for="h_doc_1_{$gp.codigo}">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gp.codigo}][mostrar_doc_1]" id="d_doc_1_{$gp.codigo}" value="N" class="custom-control-input radio-deshabilitado" {if isset($gp.mostrar_doc_1) and $gp.mostrar_doc_1 == "N"}checked{/if}>
                                                        <label class="custom-control-label" for="d_doc_1_{$gp.codigo}">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 2:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[{$gp.codigo}][nombre_doc_2]" value="{if isset($gp.nombre_doc_2)}{$gp.nombre_doc_2}{/if}">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="{$gp.codigo}[doc_2]" value="" id="doc_2_{$gp.codigo}">
                                                        <label  class="custom-file-label label-adjunto" for="doc_2_{$gp.codigo}">Seleccionar Archivo</label>
                                                    </div>
                                                    {if isset($id_anio_escolar) && $gp.tiene_adjunto_2 == "si"}
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gp.doc_2}" data-nombre="{$gp.nombre_doc_2}">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gp.codigo}][mostrar_doc_2]" id="h_doc_2_{$gp.codigo}" value="S" class="custom-control-input" {if isset($gp.mostrar_doc_2) and $gp.mostrar_doc_2 == "S"}checked{/if}>
                                                        <label class="custom-control-label" for="h_doc_2_{$gp.codigo}">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gp.codigo}][mostrar_doc_2]" id="d_doc_2_{$gp.codigo}" value="N" class="custom-control-input radio-deshabilitado" {if isset($gp.mostrar_doc_2) and $gp.mostrar_doc_2 == "N"}checked{/if}>
                                                        <label class="custom-control-label" for="d_doc_2_{$gp.codigo}">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header m-0">Documentos Secundaria</h5>
                        <div class="card-body">
                            <ul class="nav nav-pills" id="myTab2" role="tablist">
                                {foreach $grados_secundaria as $clave_s => $gs}
                                    <li class="nav-item">
                                        <a class="nav-link {if $clave_s == 0}active{/if}" data-toggle="tab" href="#{$gs.codigo}">{$gs.nombre}</a>
                                    </li>
                                {/foreach}
                            </ul>

                            <div class="tab-content" id="myTabContent2">
                                {foreach $grados_secundaria as $clave_s => $gs}
                                    <div class="tab-pane fade {if $clave_s == 0}show active{/if}" id="{$gs.codigo}" role="tabpanel">
                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 1:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[{$gs.codigo}][nombre_doc_1]" value="{if isset($gs.nombre_doc_1)}{$gs.nombre_doc_1}{/if}">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="{$gs.codigo}[doc_1]" value="" id="doc_1_{$gs.codigo}">
                                                        <label  class="custom-file-label label-adjunto" for="doc_1_{$gs.codigo}">Seleccionar Archivo</label>
                                                    </div>
                                                    {if isset($id_anio_escolar) && $gs.tiene_adjunto_1 == "si"}
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gs.doc_1}" data-nombre="{$gs.nombre_doc_1}">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gs.codigo}][mostrar_doc_1]" id="h_doc_1_{$gs.codigo}" value="S" class="custom-control-input" {if isset($gs.mostrar_doc_1) and $gs.mostrar_doc_1 == "S"}checked{/if}>
                                                        <label class="custom-control-label" for="h_doc_1_{$gs.codigo}">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gs.codigo}][mostrar_doc_1]" id="d_doc_1_{$gs.codigo}" value="N" class="custom-control-input radio-deshabilitado" {if isset($gs.mostrar_doc_1) and $gs.mostrar_doc_1 == "N"}checked{/if}>
                                                        <label class="custom-control-label" for="d_doc_1_{$gs.codigo}">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-5">
                                            <div class="col-4 col-md-1">
                                                <label class="font-weight-bold">Adjunto 2:</label>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="data[{$gs.codigo}][nombre_doc_2]" value="{if isset($gs.nombre_doc_2)}{$gs.nombre_doc_2}{/if}">
                                                </div>
                                            </div>
                                            <div class="col-9 col-md-5">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input adjunto" name="{$gs.codigo}[doc_2]" value="" id="doc_2_{$gs.codigo}">
                                                        <label  class="custom-file-label label-adjunto" for="doc_2_{$gs.codigo}">Seleccionar Archivo</label>
                                                    </div>
                                                    {if isset($id_anio_escolar) && $gs.tiene_adjunto_2 == "si"}
                                                        <div class="col-1 col-md-1">
                                                            <div class="form-group">
                                                                <span class="btn btn-social btn-secondary btn-flat ver-adjunto-conf" title="Ver Adjunto" data-toggle="modal" data-target="#modal-ver-adjunto-conf" data-doc="{$gs.doc_2}" data-nombre="{$gs.nombre_doc_2}">
                                                                    <i class="fas fa-paperclip"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>
                                            </div>
                                            <div class="col-1 col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gs.codigo}][mostrar_doc_2]" id="h_doc_2_{$gs.codigo}" value="S" class="custom-control-input" {if isset($gs.mostrar_doc_2) and $gs.mostrar_doc_2 == "S"}checked{/if}>
                                                        <label class="custom-control-label" for="h_doc_2_{$gs.codigo}">Habilitado</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" name="data[{$gs.codigo}][mostrar_doc_2]" id="d_doc_2_{$gs.codigo}" value="N" class="custom-control-input radio-deshabilitado" {if isset($gs.mostrar_doc_2) and $gs.mostrar_doc_2 == "N"}checked{/if}>
                                                        <label class="custom-control-label" for="d_doc_2_{$gs.codigo}">Deshabilitado</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-social btn-primary">
                    <i class="fa fa-save"></i> Registrar Año Escolar
                </button>
                <input type="hidden" value="{if isset($tipo)}{$tipo}{/if}" class="tipo" name="tipo">
                <input type="hidden" value="{if isset($id_anio_escolar)}{$id_anio_escolar}{/if}" name="id_anio_escolar">
            </div>
        </form>
    </section>
</div>

<!-- MODAL VER ADJUNTO -->
<div class="modal fade" id="modal-ver-adjunto-conf">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Archivo Adjunto <span class="titulo-adjunto"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container"> 
                    <div class="row justify-content-center align-items-center">
                        <div class="archivo-adjunto-conf">
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