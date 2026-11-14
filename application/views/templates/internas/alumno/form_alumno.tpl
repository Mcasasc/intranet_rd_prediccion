<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="{$base_url}alumno/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label class="font-weight-bold" for="dni"><i class="fas fa-asterisk text-danger"></i> DNI:</label>
                            <div class=input-group>
                                <input type="text" class="form-control dni" id="dni" name="dni" value="{if isset($dni)}{$dni}{/if}"/>
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div>
                            </div>
                        </div>                                                                                                     
                        <div class="form-group">
                            <label class="font-weight-bold" for="nombres"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                            <div class="input-group">
                                <input type="text" class="form-control nombres" id="nombres" name="nombres" value="{if isset($nombres)}{$nombres}{/if}" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-child"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">                            
                            <label class="font-weight-bold" for="ape_paterno"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                            <div class="input-group">
                                <input type="text" class="form-control ape_paterno" id="ape_paterno" name="ape_paterno" value="{if isset($ape_paterno)}{$ape_paterno}{/if}" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-child"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="ape_materno"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                            <div class=input-group>
                                <input type="text" class="form-control ape_materno" id="ape_materno" name="ape_materno" value="{if isset($ape_materno)}{$ape_materno}{/if}" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-child"></i></div></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold" for="usuario"><i class="fas fa-asterisk text-danger"></i> Usuario:</label>
                            <div class="input-group">                            
                                <input type="text" class="form-control" name="usuario" id="usuario" value="{if isset($usuario)}{$usuario}{/if}" placeholder="Usuario" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                            </div>                                                
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="contrasena"><i class="fas fa-asterisk text-danger"></i> Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="contrasena" placeholder="Contraseña"/>
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-key"></i></div></div>
                            </div>
                        </div>

                                                                                          
                    </div>
                    <div class="col-md-6">                                                                        
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Fecha de Nacimiento:</label>
                            <div class=input-group>
                                <input type="date" class="form-control fecha_nacimiento" name="fecha_nacimiento" value="{if isset($fecha_nacimiento)}{$fecha_nacimiento}{/if}" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold" for="direccion"><i class="fas fa-asterisk text-danger"></i> Dirección:</label>
                            <div class=input-group>
                                <input type="text" class="form-control direccion" id="direccion" name="direccion" value="{if isset($direccion)}{$direccion}{/if}" />
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Distrito:</label>
                            <div class="input-group">{if isset($id_distrito)}{$id_distrito}{/if}</div>
                        </div>                                                 
                         <div class="form-group">                            
                            <button class="btn btn-social btn-block btn-primary btn-flat">
                                <i class="fa fa-save"></i> Editar Alumno</button>
                            <input class="id_alumno" type="hidden" name="id_alumno" value="{if isset($id_alumno)}{$id_alumno}{/if}" />
                            <input class="id_usuario" type="hidden" name="id_usuario" value="{if isset($id_usuario)}{$id_usuario}{/if}" />
                        </div>     
                    </div>                                                                                                  
                </div>
            </div>        
    </div>
    </form>
</section>
</div>