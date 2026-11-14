<div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">         
            <span class="response"></span>
            {if empty($lista)}
                <h1><small>No hay alumnos registrados</small></h1>
            {else}
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Apellidos y Nombres</th>
                            <th>DNI</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Usuario</th>
                            <th>Fecha de Registro</th>                            
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $lista as $l}
                            <tr class="resultado">
                                <td>{$l.numero}</td>
                                <td>{$l.nombre_alumno}</td>
                                <td>{$l.dni}</td>
                                <td>{$l.fecha_nacimiento}</td>
                                <td>{$l.usuario}</td>
                                <td>{$l.fecha_registro}</td>                                
                                <td>{$l.accion}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>                
            {/if}    
        </div>
    </section>
</div>
