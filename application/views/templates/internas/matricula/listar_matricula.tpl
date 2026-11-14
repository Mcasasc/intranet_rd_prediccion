<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-3 pb-3 pl-0">
                <form class="formulario" action="{$base_url}matricula/matricula_anio_escolar" method="post">
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
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            {if empty($lista)}
                <h1><small>No hay matrículas registradas</small></h1>
            {else}
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Nombres y Apellidos</th>
                            <th>DNI</th>
                            <th>Grado y Sección</th>
                            <th>Nivel Educativo</th>
                            <th>Año Escolar</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $lista as $l}
                            <tr class="resultado">
                                <td>{$l.numero}</td>
                                <td>{$l.nombre}</td>
                                <td>{$l.dni}</td>
                                <td>{$l.grado} {$l.seccion}</td>
                                <td>{$l.nivel_educativo}</td>
                                <td>{$l.anio_escolar}</td>
                                <td>{$l.usuario}</td>
                                <td>{$l.accion}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    </section>
</div>


<div class="modal fade" id="modal_aperturar_cuenta">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="response"></span>
            <div class="modal-header">
                <h5 class="modal-title">Aperturar Cuenta de <span class="nombre_alumno"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body mx-auto">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-4">
                            <label class="control-label">Monto de Pensión:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control monto-matricula" placeholder="Ingrese Monto"
                                   value="">
                            <input type="hidden" class="form-control id-matricula" value="">
                        </div>
                        <br><br><br>
                        <div class="col-md-4">
                            <label class="control-label">Monto de Matrícula:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control monto-base" placeholder="Ingrese Monto" value="">
                        </div>
                        <div class="badge badge-danger text-wrap mt-3 mensaje" style="font-size: 1.2rem;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success modal-aperturar-cuenta boton-aperturar"
                        data-dismiss="modal">Aperturar Cuenta</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
