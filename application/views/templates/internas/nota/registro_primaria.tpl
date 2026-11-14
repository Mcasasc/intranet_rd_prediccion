<div class="page-content-wrapper bg-white ">
    <section class="container-fluid pl-0 pr-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        {$nombre_curso} <button type="button" class="btn btn-warning" onClick="window.location.reload();">PROMEDIAR NOTAS </button>
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
                            <input type="hidden" class="registro-primaria-curso" name="id_curso" value="{$id_curso}">
                            <input type="hidden" class="registro-primaria-bimestre" name="id_bimestre" value="{$id_bimestre}">
                            <input type="hidden" class="registro-primaria-anioescolar" name="id_anio_escolar" value="{$id_anio_escolar}">
                            <input type="hidden" class="registro-primaria-grado" name="id_grado" value="{$id_grado}">
                            <input type="hidden" class="registro-primaria-niveleducativo" name="id_nivel_educativo" value="{$id_nivel_educativo}">
                            <input type="hidden" class="registro-primaria-numero-concepto" value="">
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Concepto
                    </div>
                    <div class="card-body p-2 card-body-ips">                        
                        <div class="list-group">
                            {if !empty($lista_alumnos) }
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-primaria" data-concepto="1">Participación y Tareas</button>
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-primaria" data-concepto="2">Asistencia y Prácticas Diarias</button>
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-primaria" data-concepto="3">Revisión de Cuaderno y Conducta</button>
                            <button type="button" class="list-group-item list-group-item-action p-1 concepto-primaria" data-concepto="4">Exámen Mensual, Bimestral y Promedio Final</button>
                            {/if}
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
                        {if !empty($lista_alumnos) }
                            {foreach $lista_alumnos as $la}
                            <tr id="fila_{$la.numero}">
                                <td class="td-numero">{$la.numero}</td>
                                <td class="td-alumno">{$la.nombre_alumno}</td>                            
                            </tr>
                            {/foreach}
                        {else}
                        <tr>
                            <td><h3><small>El grado no tiene alumnos matriculados</small></h3></td>
                        </tr>                            
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
