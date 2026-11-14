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
                                <th>Mes Escolar</th>
                                <td>{$nombre_mes_escolar}</td>
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
                            <tr>
                                <th>Día</th>
                                <td>{$nombre_dia}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Resumen de Asistencias Mensual
                    </div>
                    <div class="card-body p-2 card-body-ips">
                        <div class="list-group">
                           <button type="button" class="list-group-item list-group-item-action p-1">1 - Total de Asistencias</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">2 - Total de Faltas</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">3 - Total de Tardanzas</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">4 - Total de Faltas Justificadas</button>
                           <button type="button" class="list-group-item list-group-item-action p-1">5 - Total de Tardanzas Justificadas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-3">
            <table class="table table-bordered table-striped table-sm mb-0" id="tabla-asistencia">
                <thead class="thead-nota">
                    <tr class="tr-nota">
                        <th class="th-numero">N°</th>
                        <th class="th-alumno">Apellidos y Nombres</th>
                        <th class="th-nota"><i class="fas fa-arrow-circle-down"></i>
                            <!--
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input check_dialibre" id="checkbox_dia_libre"
                                    name="checkbox_dia_libre">
                                <label class="custom-control-label" for="checkbox_dia_libre"></label>
                            </div>
                            -->
                        </th>
                        <th class="th-nota">1</th>
                        <th class="th-nota">2</th>
                        <th class="th-nota">3</th>
                        <th class="th-nota">4</th>
                        <th class="th-nota">5</th>
                    </tr>
                </thead>
                <tbody class="tbody-nota">
                    {foreach $lista_alumnos as $la}
                    <tr id="fila_{$la.numero} fila">
                        <td class="td-numero">{$la.numero}</td>
                        <td class="td-alumno">{$la.nombre_alumno}</td>
                        <td class='td-nota celda-nota'>
                            <select class="box_asistencia_alumno selectpicker" data-width="fit" name="{$la.numero_dia}">
                                <option value="A" {if $la.dia=='A'}selected{/if}>A </option>
                                <option value="F" {if $la.dia=='F'}selected{/if}>F </option>
                                <option value="T" {if $la.dia=='T'} selected{/if}>T </option>
                                <option value="F.J." {if $la.dia=='F.J.'}selected{/if}>F.J. </option>
                                <option value="T.J." {if $la.dia=='T.J.'}selected{/if}>T.J. </option>
                                <option value="NA" {if $la.dia=='NA'}selected{/if}>LIB </option>
                            </select>
                            <input type="hidden"name="id_asistencia_alumno" class="id_asistencia_alumno" value="{if isset($la.id_asistencia_alumno)}{$la.id_asistencia_alumno}{/if}">
                        </td>
                        <td class="td-nota celda-nota total_asistencias text-center">{if isset($la.total_asistencias)}{$la.total_asistencias}{/if}</td>
                        <td class="td-nota celda-nota total_faltas text-center">{if isset($la.total_asistencias)}{$la.total_faltas}{/if}</td>
                        <td class="td-nota celda-nota total_tardanzas text-center">{if isset($la.total_asistencias)}{$la.total_tardanzas}{/if}</td>
                        <td class="td-nota celda-nota total_faltas_justificadas text-center">{if isset($la.total_asistencias)}{$la.total_faltas_justificadas}{/if}</td>
                        <td class="td-nota celda-nota total_tardanzas_justificadas text-center">{if isset($la.total_asistencias)}{$la.total_tardanzas_justificadas}{/if}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
</div>
</section>
</div>