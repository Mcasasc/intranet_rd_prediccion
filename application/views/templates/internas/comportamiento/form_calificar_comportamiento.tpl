<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0 pr-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        {$nombre_grado}
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th>Bimestre</th>
                                <td>{$nombre_bimestre}</td>
                            </tr>
                            <tr> 
                                <th>Grado y Sección</th>
                                <td>{$nombre_grado} - {$seccion_grado}</td>
                            </tr>
                            <tr>
                                <th>Nivel Educativo</th>
                                <td>{$nombre_nivel_educativo}</td>
                            </tr>                            
                            <tr>
                                <th>Año Escolar</th>
                                <td>{$nombre_anio_escolar}</td>
                            </tr>
                            <input type="hidden" class="registro-inicial-anioescolar" name="id_anio_escolar" value="{$id_anio_escolar}">
                            <input type="hidden" class="registro-inicial-grado" name="id_grado" value="{$id_grado}">
                        </table>
                    </div>
                </div>
            </div>                        
        </div>
        <div class="row m-3">            
                <table class="table table-bordered table-striped table-sm mb-0">
                    <thead class="thead-nota">
                        <tr class="tr-nota">
                            <th class="th-numero">N°</th>
                            <th class="th-alumno">Apellidos y Nombres</th>
                            <th class="th-nota list-group-item-info">Nota</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-nota">
                        {foreach $lista_alumnos as $la}
                        <tr id="fila_{$la.numero}">
                            <td class="td-numero">{$la.numero}</td>
                            <td class="td-alumno">{$la.nombre_alumno}</td>                            
                            <td class='td-nota celda-nota'>
                                <input type='number' name="nota" class='form-control m-0 comportamiento' value="{if isset($la.nota)}{$la.nota}{/if}">
                                <input type="hidden" name="id_comportamiento" class="id_comportamiento" value="{if isset($la.id_comportamiento)}{$la.id_comportamiento}{/if}">
                            </td>
                        </tr>
                        {/foreach}            
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
