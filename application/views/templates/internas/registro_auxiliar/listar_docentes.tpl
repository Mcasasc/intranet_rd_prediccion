<div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>            
            {if empty($lista)}
                <h1><small>No hay docentes registrados</small></h1>
            {else}                
                <table class="table table-bordered table-hover" id="no-lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Docente</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $lista as $l}
                            <tr class="resultado">
                                <td>{$l.numero}</td>
                                <td>{$l.nombre_docente}</td>
                                <td>{$l.accion}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>                    
            {/if}
        </div>
    </section>
</div>