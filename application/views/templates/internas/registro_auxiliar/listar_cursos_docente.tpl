<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="pb-3 pl-0">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Mostrar por:
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th>Año Escolar</th>
                                    <td>{$combo_anio_escolar}</td>
                                    <input type="hidden" value="{$id_docente}" id="id_docente_cursos">
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {if empty($lista)}
                <h1><small>No tiene cursos asignados</small></h1>
            {else}

                {if $usuario_privilegio eq 2}
                    <table class="table table-bordered table-hover" id="no-lista">
                    {else}
                        <table class="table table-bordered table-hover" id="lista">
                        {/if}
                        <thead>
                            <tr style="background-color: #D8D8D8;">
                                <th>N°</th>
                                <th>Curso</th>
                                <th>Nivel Educativo</th>
                                <th>Grado</th>
                                <th>Sección</th>
                                <th>Año Escolar</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $lista as $l}
                                <tr class="resultado">
                                    <td>{$l.numero}</td>
                                    <td>{$l.nombre_curso}</td>
                                    <td>{$l.nombre_nivel_educativo}</td>
                                    <td>{$l.nombre_grado}</td>
                                    <td>{$l.seccion_grado}</td>
                                    <td>{$l.nombre_anio_escolar}</td>
                                    <td>
                                        {$l.accion}
                                        <a class="btn btn-warning btn-sm boton-calificar" data-toggle="modal" title="Calificar" data-target="#modal_calificar_curso" style="margin: 1px;" data-idcurso="{$l.id_curso}" data-idanioescolar="{$l.id_anio_escolar}"><i class="fas fa-tasks"></i></a>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                {/if}
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_calificar_curso">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Calificar Curso de <span class="nombre-curso"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body mx-auto">
                <div class="container">
                    <div class="row justify-content-center align-items-center contenido-calificar">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->
