<?php
/* Smarty version 3.1.39, created on 2026-11-14 18:50:49
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\internas\alerta_academica\listar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6af89f79b42283_75480244',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1ddec438035c705ce2a188ccbaec1b20d7c01073' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\internas\\alerta_academica\\listar.tpl',
      1 => 1794678632,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6af89f79b42283_75480244 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/alerta_academica.css?14"/>
<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <span class="response"></span>
            <div class="pb-3 pl-0">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Mostrar por:
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th>Año Escolar</th>
                                    <td><?php echo $_smarty_tpl->tpl_vars['combo_anio_escolar']->value;?>
</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bimestre en curso del colegio (siguiente al ultimo con la mayoria de notas registradas): es el
                 unico que se puede predecir; process.js lo usa en todas las ventanas de generacion -->
            <span id="bimestre_en_curso_alerta" data-bimestre="<?php echo $_smarty_tpl->tpl_vars['bimestre_sugerido']->value;?>
" style="display:none;"></span>

            <!-- Regreso desde el tablero de Power BI: process.js vuelve a abrir el resultado de este curso -->
            <span id="volver_alerta_academica" data-idcurso="<?php echo $_smarty_tpl->tpl_vars['volver_id_curso']->value;?>
" data-nombrecurso="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['volver_nombre_curso']->value, ENT_QUOTES, 'UTF-8', true);?>
" style="display:none;"></span>

            <?php if ($_smarty_tpl->tpl_vars['es_administrador']->value && !empty($_smarty_tpl->tpl_vars['lista_cursos']->value)) {?>
                <!-- Generacion institucional (solo Super Admin): llama a generar() curso por curso -->
                <div class="w-100 pb-3 pl-0">
                    <!-- Misma tarjeta que "Mostrar por:" de esta pagina -->
                    <div class="card border-primary">
                        <div class="card-header font-weight-bold bg-primary text-white">
                            Generación institucional
                        </div>
                        <div class="card-body p-2 d-flex justify-content-between align-items-center flex-wrap">
                            <span class="my-1 mr-3">Genera las alertas de todo el colegio o de un grado; puedes elegir un curso o algunos alumnos.</span>
                            <div class="my-1">
                                <button type="button" class="btn btn-primary boton-lote-colegio-alerta">Generar todo el colegio</button>
                                <button type="button" class="btn btn-secondary ml-1 boton-lote-grado-alerta">Generar por Grado / Aula</button>
                            </div>
                        </div>
                    </div>

                    <?php echo '<script'; ?>
 type="application/json" id="datos_lote_alerta_academica"><?php echo $_smarty_tpl->tpl_vars['cursos_lote_json']->value;
echo '</script'; ?>
>
                    <?php echo '<script'; ?>
 type="application/json" id="alumnos_lote_alerta_academica"><?php echo $_smarty_tpl->tpl_vars['alumnos_lote_json']->value;
echo '</script'; ?>
>
                </div>
            <?php }?>

            <?php if (empty($_smarty_tpl->tpl_vars['lista_cursos']->value)) {?>
                <h1><small>No hay cursos de secundaria para mostrar</small></h1>
            <?php } else { ?>
                <table class="table table-bordered table-hover" id="lista">
                    <thead>
                        <tr style="background-color: #D8D8D8;">
                            <th>N°</th>
                            <th>Curso</th>
                            <th>Grado</th>
                            <?php if ($_smarty_tpl->tpl_vars['es_administrador']->value) {?><th>Docente</th><?php }?>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lista_cursos']->value, 'c');
$_smarty_tpl->tpl_vars['c']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['c']->do_else = false;
?>
                            <tr>
                                <td><?php echo $_smarty_tpl->tpl_vars['c']->value['numero'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['c']->value['nombre_curso'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['c']->value['nombre_grado'];?>
</td>
                                <?php if ($_smarty_tpl->tpl_vars['es_administrador']->value) {?><td><?php echo $_smarty_tpl->tpl_vars['c']->value['nombre_docente'];?>
</td><?php }?>
                                <td>
                                    <!-- Mismo boton que "Calificar" de Registro Auxiliar > Mis Cursos -->
                                    <button type="button" class="btn btn-warning btn-sm boton-generar-alerta" title="Generar predicción de aula" style="margin: 1px;" data-idcurso="<?php echo $_smarty_tpl->tpl_vars['c']->value['id_curso'];?>
" data-nombrecurso="<?php echo $_smarty_tpl->tpl_vars['c']->value['nombre_curso'];?>
 - <?php echo $_smarty_tpl->tpl_vars['c']->value['nombre_grado'];?>
">Generar Predicción</button>
                                </td>
                            </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </tbody>
                </table>
            <?php }?>

        </div>
    </section>
</div>

<?php if ($_smarty_tpl->tpl_vars['es_administrador']->value && !empty($_smarty_tpl->tpl_vars['lista_cursos']->value)) {?>
<!-- Modal del lote (todo el colegio o un grado completo, solo Super Admin): progreso y lista de
     alumnos. No se cierra mientras procesa (solo con "Cancelar lote"); al terminar, con "Cerrar". -->
<div class="modal fade aa-modal-colegio" id="modal_lote_alerta_academica" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><span class="titulo-lote-alerta-academica"></span></h5>
                <button type="button" class="close cerrar-lote-alerta" data-dismiss="modal" aria-label="Cerrar" style="display:none;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Mientras procesa: avance del lote (se oculta al terminar) -->
                <div class="progreso-lote-alerta-academica aa-progreso-lote">
                    <p class="mb-2 texto-progreso-lote-alerta"></p>
                    <div class="progress">
                        <div class="progress-bar barra-progreso-lote-alerta" role="progressbar" style="width: 0%;">0%</div>
                    </div>
                    <small class="text-muted d-block mt-2 aviso-pestana-lote-alerta">Mantén esta pestaña abierta hasta que termine.</small>
                </div>

                <!-- Al terminar: resumen del lote (lo llena process.js) -->
                <div class="resumen-lote-alerta-academica" style="display:none;"></div>

                <div class="resultado-lote-alerta-academica" style="display:none;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                        <h6 class="font-weight-bold mb-1">Alumnos, de mayor a menor alerta</h6>
                        <span class="text-muted mb-1 conteo-lote-alerta-academica"></span>
                    </div>
                    <div>
                            <div class="form-row aa-filtros">
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1">Grado</label>
                                    <select class="form-control form-control-sm filtro-lote-grado-alerta">
                                        <option value="">Todos los grados</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1">Curso</label>
                                    <select class="form-control form-control-sm filtro-lote-curso-alerta">
                                        <option value="">Todos los cursos</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1">Nivel de alerta</label>
                                    <select class="form-control form-control-sm filtro-lote-nivel-alerta">
                                        <option value="">Todos los niveles de alerta</option>
                                        <option value="riesgo_alto">Alerta alta</option>
                                        <option value="riesgo_moderado">Alerta moderada</option>
                                        <option value="sin_riesgo">Sin alerta</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-2">
                                    <label class="mb-1">Buscar alumno</label>
                                    <input type="search" class="form-control form-control-sm buscar-lote-alerta" placeholder="Buscar...">
                                </div>
                            </div>
                            <div class="table-responsive aa-tabla-envoltura">
                                <table class="table table-bordered table-hover aa-tabla">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Alumno</th>
                                            <th>Grado</th>
                                            <th>Curso</th>
                                            <th class="aa-th-alerta"><span>Alerta de rendimiento por debajo de lo esperado<br>/ Probabilidad</span></th>
                                            <th>Promedio bimestre anterior</th>
                                            <th>Asistencia bimestre anterior</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="cuerpo-lote-alerta-academica">
                                    </tbody>
                                </table>
                            </div>
                            <!-- Paginacion de la lista (la arma process.js) -->
                            <div class="paginacion-lote-alerta-academica"></div>
                    </div>
                    <!-- Nota final del lote (cursos generados, alumnos sin prediccion y "Ver detalle") -->
                    <div class="nota-lote-alerta-academica"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger boton-cancelar-lote-alerta">Cancelar Lote</button>
                <button type="button" class="btn btn-primary mr-auto cerrar-lote-alerta boton-tablero-alerta boton-tablero-lote-alerta" style="display:none;">Ver en Power BI</button>
                <button type="button" class="btn btn-success cerrar-lote-alerta boton-excel-lote-alerta" style="display:none;">Exportar Excel</button>
                <button type="button" class="btn btn-secondary cerrar-lote-alerta" data-dismiss="modal" style="display:none;">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->
<?php }?>

<!-- Modal previo a generar un curso (boton "Generar Predicción" de la lista, docente y Super Admin):
     muestra el curso y el bimestre a predecir (solo el bimestre en curso habilitado) -->
<div class="modal fade aa-modal-colegio" id="modal_generar_curso_alerta_academica" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Generar Predicción de Aula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Curso</label>
                    <input type="text" class="form-control nombre-curso-generar-alerta" readonly>
                </div>
                <div class="form-group mb-0">
                    <label>Bimestre a predecir</label>
                    <select class="form-control select-bimestre-curso-alerta">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, array(2=>'2.º bimestre',3=>'3.er bimestre',4=>'4.º bimestre'), 'texto', false, 'num');
$_smarty_tpl->tpl_vars['texto']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['num']->value => $_smarty_tpl->tpl_vars['texto']->value) {
$_smarty_tpl->tpl_vars['texto']->do_else = false;
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['num']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['num']->value == $_smarty_tpl->tpl_vars['bimestre_sugerido']->value) {?> selected<?php } else { ?> disabled<?php }?>><?php echo $_smarty_tpl->tpl_vars['texto']->value;
if ($_smarty_tpl->tpl_vars['num']->value < $_smarty_tpl->tpl_vars['bimestre_sugerido']->value) {?> (cerrado)<?php } elseif ($_smarty_tpl->tpl_vars['num']->value > $_smarty_tpl->tpl_vars['bimestre_sugerido']->value) {?> (aún no disponible)<?php }?></option>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </select>
                    <small class="form-text text-muted">Solo se puede predecir el bimestre en curso, el siguiente al último con notas registradas.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary boton-confirmar-curso-alerta">Generar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->

<!-- Modal de resultado -->
<div class="modal fade aa-modal-colegio" id="modal_resultado_alerta_academica" tabindex="-1" role="dialog" data-powerbi-publicado="<?php if ($_smarty_tpl->tpl_vars['powerbi_publicado']->value) {?>1<?php } else { ?>0<?php }?>">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title aa-titulo-curso">Predicción de <span class="nombre-curso-resultado"></span><span class="aa-grado-titulo grado-curso-resultado"></span><span class="aa-grado-titulo bimestre-curso-resultado" style="display:none;"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Aviso antes de abrir el tablero de Power BI (lo llena process.js) -->
                <div class="aviso-tablero-alerta-academica" style="display:none;"></div>

                <div class="cargando-resultado-alerta-academica text-center p-5">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-3 mb-0">Generando predicción, esto puede tardar unos segundos...</p>
                </div>

                <div class="contenido-resultado-alerta-academica" style="display:none;">
                    <!-- Mismo orden que el modal del lote: tarjetas (las arma process.js con
                         tarjetasResumenAlertaAcademica), titulo de la lista, filtros y tabla -->
                    <div class="resumen-curso-alerta-academica"></div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                        <h6 class="font-weight-bold mb-1">Alumnos, de mayor a menor alerta</h6>
                        <span class="text-muted mb-1 conteo-curso-alerta-academica"></span>
                    </div>
                    <div class="form-row aa-filtros">
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Nivel de alerta</label>
                            <select class="form-control form-control-sm filtro-curso-nivel-alerta">
                                <option value="">Todos los niveles de alerta</option>
                                <option value="riesgo_alto">Alerta alta</option>
                                <option value="riesgo_moderado">Alerta moderada</option>
                                <option value="sin_riesgo">Sin alerta</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="mb-1">Buscar alumno</label>
                            <input type="search" class="form-control form-control-sm buscar-curso-alerta" placeholder="Buscar...">
                        </div>
                    </div>
                    <div class="table-responsive aa-tabla-envoltura">
                        <table class="table table-bordered table-hover aa-tabla">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Alumno</th>
                                    <th class="aa-th-alerta"><span>Alerta de rendimiento por debajo de lo esperado<br>/ Probabilidad</span></th>
                                    <th>Promedio bimestre anterior</th>
                                    <th>Asistencia bimestre anterior</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="cuerpo-resultado-alerta-academica">
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginacion de la lista (la arma process.js) -->
                    <div class="paginacion-curso-alerta-academica"></div>

                    <div class="alumnos-excluidos-alerta-academica"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mr-auto boton-tablero-alerta boton-tablero-curso-alerta" style="display:none;">Ver en Power BI</button>
                <button type="button" class="btn btn-success boton-excel-curso-alerta" style="display:none;">Exportar Excel</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->

<?php if ($_smarty_tpl->tpl_vars['es_administrador']->value) {?>
<!-- Modal de confirmacion de "Generar Todo el Colegio" (solo Super Admin). Encabezado azul de las
     tarjetas de la intranet y tabla con la columna gris #D8D8D8 de sus listados. Los numeros los
     llena process.js. -->
<div class="modal fade aa-modal-colegio" id="modal_confirmar_colegio_alerta_academica" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Generar todo el colegio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-2">¿Desea generar la predicción de todo el colegio, año escolar <strong class="anio-colegio-alerta"></strong>?</p>
                <p class="mb-2">Se generará la predicción del <strong class="bimestre-colegio-alerta"></strong> (bimestre en curso). Los cursos o alumnos que no tengan notas del bimestre anterior se indicarán al final.</p>
                <small class="text-muted">Puede tardar varios minutos. No cierre esta pestaña hasta que termine.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary boton-confirmar-colegio-alerta">Generar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->

<!-- Modal de generacion por grado / aula (solo Super Admin) -->
<div class="modal fade aa-modal-colegio" id="modal_lote_grado_alerta_academica" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Generar por Grado / Aula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Grado / aula</label>
                    <select class="form-control select-lote-grado-alerta">
                        <option value="">Seleccione un grado</option>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grados_lote']->value, 'nombre_grado', false, 'id_grado');
$_smarty_tpl->tpl_vars['nombre_grado']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['id_grado']->value => $_smarty_tpl->tpl_vars['nombre_grado']->value) {
$_smarty_tpl->tpl_vars['nombre_grado']->do_else = false;
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['id_grado']->value;?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['nombre_grado']->value, ENT_QUOTES, 'UTF-8', true);?>
</option>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Curso (opcional)</label>
                    <select class="form-control select-lote-curso-alerta" disabled>
                        <option value="">Todos los cursos del grado</option>
                    </select>
                    <small class="form-text text-muted">Si no eliges un curso, se generan todos los cursos del grado.</small>
                </div>
                <div class="form-group mb-0">
                    <label>Alumno (opcional)</label>
                    <select class="form-control select-lote-alumno-alerta" multiple disabled
                            data-live-search="true" data-width="100%" data-selected-text-format="count > 2"
                            data-count-selected-text="{0} alumnos seleccionados"
                            data-none-selected-text="Todos los alumnos del grado" data-none-results-text="Ningún alumno coincide con {0}"
                            data-live-search-placeholder="Buscar alumno..."></select>
                    <small class="form-text text-muted">Si no eliges un alumno, se generan todos los alumnos del grado.</small>
                </div>
                <!-- Bimestre a predecir: solo el bimestre en curso esta habilitado -->
                <div class="form-group mb-0 mt-3">
                    <label>Bimestre a predecir</label>
                    <select class="form-control select-bimestre-grado-alerta">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, array(2=>'2.º bimestre',3=>'3.er bimestre',4=>'4.º bimestre'), 'texto', false, 'num');
$_smarty_tpl->tpl_vars['texto']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['num']->value => $_smarty_tpl->tpl_vars['texto']->value) {
$_smarty_tpl->tpl_vars['texto']->do_else = false;
?>
                            <option value="<?php echo $_smarty_tpl->tpl_vars['num']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['num']->value == $_smarty_tpl->tpl_vars['bimestre_sugerido']->value) {?> selected<?php } else { ?> disabled<?php }?>><?php echo $_smarty_tpl->tpl_vars['texto']->value;
if ($_smarty_tpl->tpl_vars['num']->value < $_smarty_tpl->tpl_vars['bimestre_sugerido']->value) {?> (cerrado)<?php } elseif ($_smarty_tpl->tpl_vars['num']->value > $_smarty_tpl->tpl_vars['bimestre_sugerido']->value) {?> (aún no disponible)<?php }?></option>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </select>
                    <small class="form-text text-muted">Solo se puede predecir el bimestre en curso, el siguiente al último con notas registradas.</small>
                </div>
                <div class="text-danger small mt-2 error-lote-grado-alerta" style="display:none;">Seleccione un grado.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary boton-confirmar-lote-grado-alerta">Generar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal -->
<?php }
}
}
