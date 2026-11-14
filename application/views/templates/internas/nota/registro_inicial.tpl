<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0 pr-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        {$nombre_curso}
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
                                <th>Docente</th>
                                <td>{$nombre_docente}</td>
                            </tr>
                            <tr>
                                <th>Año Escolar</th>
                                <td>{$nombre_anio_escolar}</td>
                            </tr>
                            <input type="hidden" class="registro-inicial-curso" name="id_curso" value="{$id_curso}">
                            <input type="hidden" class="registro-inicial-bimestre" name="id_bimestre" value="{$id_bimestre}">
                            <input type="hidden" class="registro-inicial-anioescolar" name="id_anio_escolar" value="{$id_anio_escolar}">
                            <input type="hidden" class="registro-inicial-grado" name="id_grado" value="{$id_grado}">
                            <input type="hidden" class="registro-inicial-niveleducativo" name="id_nivel_educativo"
                                value="{$id_nivel_educativo}">
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Competencias
                    </div>
                    <div class="card-body p-2 card-body-ips">
                        <div class="list-group">
                            {if isset($nombre_competencia_1)}<button type="button" class="list-group-item list-group-item-action p-1 competencia" data-competencia="1">{$nombre_competencia_1}</button>{/if}
                            {if isset($nombre_competencia_2)}<button type="button" class="list-group-item list-group-item-action p-1 competencia" data-competencia="2">{$nombre_competencia_2}</button>{/if}
                            {if isset($nombre_competencia_3)}<button type="button" class="list-group-item list-group-item-action p-1 competencia" data-competencia="3">{$nombre_competencia_3}</button>{/if}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Indicadores
                    </div>
                    <div class="card-body p-2 card-body-ips">
                        <div class="d-flex justify-content-center hide-spinner" id="spinner-carga">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <table class="table table-borderless table-sm mb-0 tabla-indicadores">

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
                            <th class="th-nota">1</th>
                            <th class="th-nota">2</th>
                            <th class="th-nota">3</th>
                            <th class="th-nota">4</th>
                            <th class="th-nota">5</th>
                            <th class="th-nota">6</th>
                            <th class="th-nota">7</th>
                            <th class="th-nota">8</th>
                            <th class="th-nota">Prom</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-nota">
                        {foreach $lista_alumnos as $la}
                        <tr id="fila_{$la.numero}">
                            <td class="td-numero">{$la.numero}</td>
                            <td class="td-alumno">{$la.nombre_alumno}</td>                            
                            <!-- <td class='td-nota celda-nota'>
                            <input type='number' class='form-control m-0'>
                            </td> -->
                        </tr>
                        {/foreach}            
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
