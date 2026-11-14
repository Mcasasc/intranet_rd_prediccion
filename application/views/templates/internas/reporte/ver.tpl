<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="col-md-6 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Reporte de Asistencia
                    </div>
                    <div class="card-body p-2">
                        <form action="{$base_url}reporte/reporte_asistencia" method="post" target="_blank">
                            <div class="form-group row">
                                <label class="col-md-2 font-weight-bold"> Año Escolar</label>
                                <div class="col-md-6">{if isset($combo_anios_escolares)} {$combo_anios_escolares} {/if}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 font-weight-bold">Mes</label>
                                <div class="col-md-6"> {if isset($combo_meses_escolares)} {$combo_meses_escolares} {/if}
                                </div>
                            </div>
                            <div class="form-group row float-right">
                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-social btn-primary btn-flat"
                                        value="Ver Reporte">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-1">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Reporte de Notas
                    </div>
                    <div class="card-body p-2">
                        <form action="{$base_url}reporte/reporte_notas" method="post" target="_blank">
                            <div class="form-group row">
                                <label class="col-md-2 font-weight-bold"> Año Escolar</label>
                                <div class="col-md-6">{if isset($combo_anios_escolares)} {$combo_anios_escolares} {/if}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">Bimestre</label>
                                <div class="col-md-6"> {if isset($combo_bimestres)} {$combo_bimestres} {/if}
                                </div>
                            </div>
                            {if isset($estado_usuario) && $estado_usuario eq 0} 
                                <div class="form-group row float-right">
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-social btn-primary btn-flat" value="Ver Reporte">
                                    </div>
                                </div>
                            {/if}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>