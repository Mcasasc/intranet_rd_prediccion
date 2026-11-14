<body>    
    <header>        
        <nav class="navbar navbar-dark bg-primary">
            <div class="logo-sidebar text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
                <a class="navbar-brand" href="#">INTRANET</a>
            </div>     
            <div class="d-flex justify-content-start align-items-start mr-auto">
                <button class="navbar-toggler mr-3 toggler" type="button" data-toggle="offcanvas" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation" id="menu-toggle">
                    <span class="navbar-toggler-icon text-white"></span>
                </button>
                <span class="navbar-text text-white">{$titulo_modulo}</span>
            </div>
            <div class="navbar-menu-wrapper d-flex">                
                <ul class="nav nav-pills">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$usuario_nombres}</a>
                        <div class="dropdown-menu">
                            {if $usuario_privilegio != 3}
                                <a class="dropdown-item" href="{$base_url}usuario/editar/{$usuario_id}">Mi Perfil</a>
                            {/if}
                            <a class="dropdown-item" href="{$base_url}login/salir">Salir</a>                        
                        </div>
                    </li>               
                </ul>
            </div>
        </nav>
    </header> 

    <div id="wrapper" class="toggled">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">                
                <li>
                    <a href="{$base_url}home">
                        <i class="fa fa-home"></i> <span>Inicio</span>
                    </a>
                </li>
                <!-- MODULO DE USUARIOS -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
                    <li class="treeview {if isset($usuario_activo)}{$usuario_activo}{/if}">
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span> Usuario</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{$base_url}usuario/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <li><a href="{$base_url}usuario/listar"><i class="fa fa-list"></i> Listar</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE USUARIOS -->

                <!-- MODULO DE APODERADO -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
                    <li class="treeview {if isset($apoderado_activo)}{$apoderado_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-user-tie"></i>
                            <span> Apoderado</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li><a href="{$base_url}apoderado/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <li><a href="{$base_url}apoderado/listar"><i class="fa fa-list"></i> Listar</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE APODERADO -->

                <!-- MODULO DE MATRICULA -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
                    <li class="treeview {if isset($ingeso_activo)}{$ingeso_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-child"></i>
                            <span> Matrícula</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>

                        <ul class="treeview-menu">                        
                            <li><a href="{$base_url}matricula/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <li><a href="{$base_url}matricula/listar"><i class="fa fa-list"></i> Listar</a></li>
                            <li><a href="{$base_url}alumno/listar"><i class="fas fa-user-graduate"></i> Alumno</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE MATRICULA -->           

                <!-- MODULO DE REGISTRO AUXILIAR -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 2 || $usuario_privilegio eq 5}
                    <li class="treeview {if isset($registro_auxiliar_activo)}{$registro_auxiliar_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-pencil-alt"></i>
                            <span> Registro Auxiliar</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
                                <li><a href="{$base_url}registro_auxiliar/asignar_docente"><i class="fas fa-chalkboard-teacher"></i> Asignar Docente</a></li>
                                <li><a href="{$base_url}registro_auxiliar/docentes"><i class="fas fa-user-graduate"></i> Docentes</a></li>
                                <!-- <li><a href="{$base_url}registro_auxiliar/grados"><i class="fas fa-calculator"></i> Grados</a></li> -->
                            {/if}

                            <!-- OPCION PARA DOCENTEE -->
                            {if $usuario_privilegio eq 2 || $usuario_privilegio eq 5}  
                                <li><a href="{$base_url}registro_auxiliar/listar_cursos_docente/{$usuario_id}"><i class="fas fa-user-graduate"></i> Mis Cursos</a></li>
                            {/if}
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE REGISTRO AUXILIAR -->

                <!-- MODULO DE ALERTA ACADEMICA -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 2 || $usuario_privilegio eq 5}
                    <li class="treeview {if isset($alerta_academica_activo)}{$alerta_academica_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-chart-line"></i>
                            <span> Alerta Académica</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{$base_url}alerta_academica/listar"><i class="fas fa-list"></i> Generar Predicción</a></li>
                            <li><a href="{$base_url}alerta_academica/tablero"><i class="fas fa-chart-bar"></i> Tablero de alertas</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE ALERTA ACADEMICA -->

                <!-- MODULO DE COMPORTAMIENTO -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 5}
                    <li class="treeview {if isset($comportamiento_activo)}{$comportamiento_activo}{/if}">
                        <a href="#">
                                <i class="fas fa-handshake"></i>
                            <span> Comportamiento</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{$base_url}comportamiento/listar"><i class="fas fa-list"></i> Listar Grados</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE COMPORTAMIENTO -->

                <!-- MODULO DE ASISTENCIA -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 5}
                    <li class="treeview {if isset($asistencia_activo)}{$asistencia_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-calendar-alt"></i>
                            <span> Asistencia</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{$base_url}asistencia/listar"><i class="fas fa-list"></i> Listar Grados</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE ASISTENCIA -->

                <!-- MODULO DE MENSAJES -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 3 || $usuario_privilegio eq 9}
               <li class="treeview {if isset($mensaje_activo)}{$mensaje_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-envelope"></i>
                            <span> Mensaje</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            {if $usuario_privilegio eq 3}
                                <li><a href="{$base_url}mensaje/listar"><i class="fas fa-list"></i> Mis Mensajes</a></li>
                            {else}
                            {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
                                <li><a href="{$base_url}mensaje/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            {/if}
                                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 9}
                                <li><a href="{$base_url}mensaje/listar"><i class="fas fa-list"></i> Listar</a></li>
                                {/if}
                            {/if}
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE MENSAJES -->

                <!-- MODULO DE REPORTES -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7 || $usuario_privilegio eq 3}
                    <li class="treeview {if isset($reportes_activo)}{$reportes_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-folder-open"></i>
                            <span> Reportes</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            {if $usuario_privilegio eq 3}
                            <li><a href="{$base_url}reporte/ver"><i class="fas fa-file-pdf"></i> Mis Reportes</a></li>
                            {else}
                            <li><a href="{$base_url}reporte/ver_admin"><i class="fas fa-file-pdf"></i> Ver</a></li>
                            {/if}
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE REPORTES -->

            <!-- MODULO DE PSICOLOGIA -->
            {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
            <li class="treeview {if isset($psicologia_activo)}{$psicologia_activo}{/if}">
                <a href="#">
                    <i class="fa fa-user-md"></i>
                    <span> Psicología</span>
                    <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{$base_url}psicologia/listar"><i class="fas fa-list"></i> Listado</a></li>
                </ul>
            </li>
        {/if}
        <!-- FIN MODULO DE PSICOLOGIA -->

                <!-- MODULO DE CONFIGURACION -->
                {if $usuario_privilegio eq 1 || $usuario_privilegio eq 7}
                    <li class="treeview {if isset($configuracion_activo)}{$configuracion_activo}{/if}">
                        <a href="#">
                            <i class="fas fa-cogs"></i>
                            <span> Configuración</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{$base_url}configuracion/listar"><i class="fas fa-list"></i> Ver Años Escolares</a></li>
                            <li><a href="{$base_url}configuracion/anio_escolar"><i class="fas fa-plus"></i> Aperturar Año Escolar</a></li>
                        </ul>
                    </li>
                {/if}
                <!-- FIN MODULO DE CONFIGURACION -->
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->