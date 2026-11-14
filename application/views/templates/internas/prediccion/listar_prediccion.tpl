<div class="page-content-wrapper bg-white">    
    <section class="container-fluid pl-0">
        <div class="row m-3">         
            <span class="response"></span>
            {if empty($lista)}
                <h1><small>No hay predicciones registradas</small></h1>
            {else}
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Nombres y Apellidos</th>
                            <th>Prom. Nota Ingresada</th>
                            <th>Prom. Nota Histórica</th>
                            <th>Prom. Nota Final</th>
                            <th>Prom. Psicología</th>
                            <th>Prom. Asistencia</th>
                            <th>Predicc. Nivel</th>
                            <th>Predicc. Recomendaciones</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $lista as $l}
                            <tr class="resultado">
                                <td>{$l.numero}</td>
                                <td>{$l.nombre}</td>
                                <td>{$l.promedio_nota_ingresada}</td>
                                <td>{$l.promedio_nota_historica}</td>
                                <td>{$l.promedio_nota_final}</td>
                                <td>{$l.promedio_psicologia}</td>
                                <td>{$l.promedio_asistencia}</td>
                                <td> <h6><span class="{$l.color_nivel_ld}">{$l.respuesta_nivel_ld}</span> </td></h6>
                                <td>{$l.respuesta_recomendacion_ld}</td>
                                <td>{$l.agregado}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>                
            {/if}    
        </div>
    </section>
</div>
