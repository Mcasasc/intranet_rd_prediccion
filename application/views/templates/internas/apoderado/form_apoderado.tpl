<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">                
        <form class="formulario" action="{$base_url}apoderado/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">                    
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Parentesco:</label>
                        <div class="input-group ">{if isset($id_parentesco)}{$id_parentesco}{/if}</div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="nombre"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nombre" name="nombres" value="{if isset($nombres)}{$nombres}{/if}" placeholder="Nombres"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="apellido_paterno"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{if isset($apellido_paterno)}{$apellido_paterno}{/if}" placeholder="Apellido Paterno"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="apellido_materno"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{if isset($apellido_materno)}{$apellido_materno}{/if}" placeholder="Apellido Materno"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-user"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="dni"><i class="fas fa-asterisk text-danger"></i> D.N.I.:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="dni" name="dni" value="{if isset($dni)}{$dni}{/if}" placeholder="D.N.I."/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-id-card"></i></div></div>
                        </div>
                    </div>                          
                    <div class="form-group">
                        <label class="font-weight-bold" for="datepicker"> Fecha de Nacimiento:</label>
                        <div class="input-group">                            
                            <input type="date" class="form-control"  name="fecha_nacimiento" value="{if isset($fecha_nacimiento)}{$fecha_nacimiento}{/if}" />
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-calendar-alt"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="email">Correo Electrónico:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="email" name="email" value="{if isset($email)}{$email}{/if}" placeholder="Correo Electrónico"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-at"></i></div></div>
                        </div>
                    </div>
                </div>            
                <div class="col-md-6">                                                           
                    <div class="form-group">
                        <label class="font-weight-bold" for="direccion"><i class="fas fa-asterisk text-danger"></i> Dirección:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{if isset($direccion)}{$direccion}{/if}" placeholder="Dirección"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="referencia_direccion"> Referencia:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="referencia_direccion" name="referencia_direccion" value="{if isset($referencia_direccion)}{$referencia_direccion}{/if}" placeholder="Referencia de la dirección"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="profesion_ocupacion"> Profesión u Ocupación:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="profesion_ocupacion" name="profesion_ocupacion" value="{if isset($profesion_ocupacion)}{$profesion_ocupacion}{/if}" placeholder="Prefesión u Ocupación"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-briefcase"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="direccion_trabajo"> Dirección de Trabajo:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="direccion_trabajo" name="direccion_trabajo" value="{if isset($direccion_trabajo)}{$direccion_trabajo}{/if}" placeholder="Dirección del Trabajo"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div></div>
                        </div>
                    </div>                    

                    <div class="form-group">
                        <label class="font-weight-bold" for="celular_1"><i class="fas fa-asterisk text-danger"></i> Celular:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="celular_1" name="celular_1" value="{if isset($celular_1)}{$celular_1}{/if}" placeholder="Celular"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-mobile-alt"></i></div></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="celular_2">Celular Opcional:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="celular_2" name="celular_2" value="{if isset($celular_2)}{$celular_2}{/if}" placeholder="Celular Opcional"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-mobile-alt"></i></div></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold" for="telefono"> Teléfono:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="telefono" name="telefono" value="{if isset($telefono)}{$telefono}{/if}" placeholder="Teléfono"/>
                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-phone"></i></div></div>
                        </div>
                    </div>
                    <div class="form-group box-anioescolar">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Año Escolar:</label>
                        <div class="input-group">{if isset($id_anio_escolar)}{$id_anio_escolar}{/if}</div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Alumno:</label>
                        <div class="input-group box-apoderado-matriculados"> {if isset($id_matriculados)}{$id_matriculados}{/if} </div>
                    <div class="form-group">                            
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fa fa-save"></i> Guardar Apoderado</button>
                        <input type="hidden" name="id_apoderado" value="{if isset($id_apoderado)}{$id_apoderado}{/if}" />                            
                    </div>
                </div>
            </div>
        </form>        
    </section>
</div>