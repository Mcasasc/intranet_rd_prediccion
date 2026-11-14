<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="{$base_url}registro_auxiliar/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class="input-group">{if isset($combo_anio_escolar)}{$combo_anio_escolar}{/if}</div>
                    </div>
                    <div class="form-group radio-nivel-educativo">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Nivel Educativo:</label>
                        <div class="custom-control custom-radio custom-control-inline">                                
                            <input type="radio" name="id_nivel_educativo" id="inicial" value="1" class="custom-control-input" {if isset($id_nivel_educativo) and $id_nivel_educativo eq 1}checked{/if}>
                            <label class="custom-control-label" for="inicial">Inicial</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="primaria" value="2" class="custom-control-input" {if isset($id_nivel_educativo) and $id_nivel_educativo eq 2}checked{/if}>
                            <label class="custom-control-label" for="primaria">Primaria</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" name="id_nivel_educativo" id="secundaria" value="3" class="custom-control-input" {if isset($id_nivel_educativo) and $id_nivel_educativo eq 3}checked{/if}>
                            <label class="custom-control-label" for="secundaria">Secundaria</label>
                        </div>
                    </div>
                    <div class="form-group box_grado">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Grado:</label>
                        <div class="input-group ">{if isset($combo_grado)}{$combo_grado}{/if}</div>
                    </div>
                    
                    <div class="form-group" >
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Curso:</label>
                        <div class="input-group ">{if isset($combo_curso)}{$combo_curso}{/if}</div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Docente:</label>
                        <div class="input-group ">{if isset($combo_docente)}{$combo_docente}{/if}</div>
                    </div>
                                                       
                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                        <i class="fa fa-save"></i> Asignar Docente</button>
                        <input type="hidden" name="id_curso_docente" value="{if isset($id_curso_docente)}{$id_curso_docente}{/if}" />
                    </div>                            
                </div>
            </div>
        </form>        
    </section>
</div>