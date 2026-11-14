<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3"> 
            <span class="response"></span>
            {if empty($lista_grados)}
            <h1><small>No hay data</small></h1>
            {else}
            <table class="table table-bordered table-hover" id="lista">            
                <thead>
                    <tr style="background-color: #D8D8D8;">
                        <th>N°</th>                        
                        <th>Grado y Sección</th>                        
                        <th>Nivel Educativo</th>
                        <th>Año Escolar</th>
                        <th>Acción</th>
                    </tr>
                </thead> 
                <tbody>
                    {foreach $lista_grados as $l}
                    <tr class="resultado">
                        <td>{$l.numero}</td>
                        <td>{$l.grado} {$l.seccion}</td>
                        <td>{$l.nivel_educativo}</td>
                        <td>{$l.anio_escolar}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href="{$base_url}psicologia/calificar/{$l.id_grado}/{$l.id_anio_escolar}" title='Registrar evaluación psicológica' style= 'margin: 1px;'><i class='fas fa-list-ul'></i></a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {/if}
        </div>
    </section>
</div>