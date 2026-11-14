<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3"> 
            <span class="response"></span>
            {if empty($lista_grados)}
            <h1><small>No hay comportamiento aperturado</small></h1>
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
                        <td>{$l.accion}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {/if}
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_comportamiento">
    <div class="modal-dialog">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">Calificar Comportamiento de <span class="nombre-grado"></span></h5>
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