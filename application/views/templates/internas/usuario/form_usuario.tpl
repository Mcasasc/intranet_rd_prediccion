<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <form class="formulario" action="{$base_url}usuario/accion" method="post">
            <div class="row m-3">
                <span class="response"></span>
                <div class="col-md-6">
                    {if $privilegio_sesion eq 7 || $privilegio_sesion eq 1 }
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Privilegio:</label>
                            <div class="input-group">{if isset($id_privilegio)}{$id_privilegio}{/if}</div>
                        </div>
                    {else}
                        <input type="hidden" name="id_privilegio" value="{if isset($id_privilegio)}{$id_privilegio}{/if}" />
                    {/if}
                    <div class="form-group">
                        <label class="font-weight-bold" for="usuario"><i class="fas fa-asterisk text-danger"></i> Usuario:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="usuario" id="usuario" value="{if isset($usuario)}{$usuario}{/if}" placeholder="Usuario" autofocus {if $privilegio_sesion != 7 && $privilegio_sesion != 1 } readonly {/if} autocomplete="new-password" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="contrasena"><i class="fas fa-asterisk text-danger"></i> Contraseña:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="contrasena-usuario" name="contrasena" placeholder="Contraseña" autocomplete="new-password" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-key"></i></div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="mostrar-contrasena">
                            <label class="form-check-label" for="mostrar-contrasena">
                                Mostrar contraseña
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Nombres:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nombres" value="{if isset($nombres)}{$nombres}{/if}" placeholder="Nombres" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Apellido Paterno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ape_paterno" value="{if isset($ape_paterno)}{$ape_paterno}{/if}" placeholder="Apellido Paterno" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback ape_materno">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Apellido Materno:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="ape_materno" value="{if isset($ape_materno)}{$ape_materno}{/if}" placeholder="Apellido Materno" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> D.N.I.:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dni" name="dni" value="{if isset($dni)}{$dni}{/if}" placeholder="D.N.I." />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"> Estado Civil:</label>
                        <div class="input-group">{if isset($id_estado_civil)}{$id_estado_civil}{/if}</div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Correo Electrónico:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="correo" value="{if isset($correo)}{$correo}{/if}" placeholder="Correo Electrónico" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Cargo:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="cargo" name="cargo" value="{if isset($cargo)}{$cargo}{/if}" placeholder="Cargo que ocupa" {if $privilegio_sesion != 7 && $privilegio_sesion != 1} readonly {/if} />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-briefcase"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"> Dirección:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="direccion" value="{if isset($direccion)}{$direccion}{/if}" placeholder="Dirección" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-map-marker-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Distrito:</label>
                        <div class="input-group">{if isset($id_distrito)}{$id_distrito}{/if}</div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"> Fecha de Nacimiento:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="fecha_nacimiento" value="{if isset($fecha_nacimiento)}{$fecha_nacimiento}{/if}" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"> Teléfono:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="telefono" value="{if isset($telefono)}{$telefono}{/if}" placeholder="Teléfono" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-phone"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i> Celular:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="celular" value="{if isset($celular)}{$celular}{/if}" placeholder="Celular" />
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-mobile-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-social btn-block btn-primary btn-flat">
                            <i class="fas fa-save"></i> Guardar Usuario</button>
                        <input type="hidden" name="id_usuario" value="{if isset($id_usuario)}{$id_usuario}{/if}" />
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
