<?php
/* Smarty version 3.1.39, created on 2026-10-29 02:44:37
  from 'C:\xampp\htdocs\Intranet_RD\application\views\templates\estructura\inter_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_6ae2a5057260c6_34045825',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b80799ae85bffedf1e91c2646b35145897576343' => 
    array (
      0 => 'C:\\xampp\\htdocs\\Intranet_RD\\application\\views\\templates\\estructura\\inter_header.tpl',
      1 => 1793236076,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6ae2a5057260c6_34045825 (Smarty_Internal_Template $_smarty_tpl) {
?><body>    
    <header>        
        <nav class="navbar navbar-dark bg-primary">
            <div class="logo-sidebar text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
                <a class="navbar-brand" href="#">INTRANET</a>
            </div>     
            <div class="d-flex justify-content-start align-items-start mr-auto">
                <button class="navbar-toggler mr-3 toggler" type="button" data-toggle="offcanvas" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation" id="menu-toggle">
                    <span class="navbar-toggler-icon text-white"></span>
                </button>
                <span class="navbar-text text-white"><?php echo $_smarty_tpl->tpl_vars['titulo_modulo']->value;?>
</span>
            </div>
            <div class="navbar-menu-wrapper d-flex">                
                <ul class="nav nav-pills">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_smarty_tpl->tpl_vars['usuario_nombres']->value;?>
</a>
                        <div class="dropdown-menu">
                            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value != 3) {?>
                                <a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
usuario/editar/<?php echo $_smarty_tpl->tpl_vars['usuario_id']->value;?>
">Mi Perfil</a>
                            <?php }?>
                            <a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
login/salir">Salir</a>                        
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
                    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
home">
                        <i class="fa fa-home"></i> <span>Inicio</span>
                    </a>
                </li>
                <!-- MODULO DE USUARIOS -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['usuario_activo']->value))) {
echo $_smarty_tpl->tpl_vars['usuario_activo']->value;
}?>">
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span> Usuario</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
usuario/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
usuario/listar"><i class="fa fa-list"></i> Listar</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE USUARIOS -->

                <!-- MODULO DE APODERADO -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['apoderado_activo']->value))) {
echo $_smarty_tpl->tpl_vars['apoderado_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-user-tie"></i>
                            <span> Apoderado</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>

                        <ul class="treeview-menu">
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
apoderado/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
apoderado/listar"><i class="fa fa-list"></i> Listar</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE APODERADO -->

                <!-- MODULO DE MATRICULA -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['ingeso_activo']->value))) {
echo $_smarty_tpl->tpl_vars['ingeso_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-child"></i>
                            <span> Matrícula</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>

                        <ul class="treeview-menu">                        
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
matricula/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
matricula/listar"><i class="fa fa-list"></i> Listar</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
alumno/listar"><i class="fas fa-user-graduate"></i> Alumno</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE MATRICULA -->           

                <!-- MODULO DE REGISTRO AUXILIAR -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 2 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 5) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['registro_auxiliar_activo']->value))) {
echo $_smarty_tpl->tpl_vars['registro_auxiliar_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-pencil-alt"></i>
                            <span> Registro Auxiliar</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
registro_auxiliar/asignar_docente"><i class="fas fa-chalkboard-teacher"></i> Asignar Docente</a></li>
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
registro_auxiliar/docentes"><i class="fas fa-user-graduate"></i> Docentes</a></li>
                                <!-- <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
registro_auxiliar/grados"><i class="fas fa-calculator"></i> Grados</a></li> -->
                            <?php }?>

                            <!-- OPCION PARA DOCENTEE -->
                            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 2 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 5) {?>  
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
registro_auxiliar/listar_cursos_docente/<?php echo $_smarty_tpl->tpl_vars['usuario_id']->value;?>
"><i class="fas fa-user-graduate"></i> Mis Cursos</a></li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE REGISTRO AUXILIAR -->

                <!-- MODULO DE ALERTA ACADEMICA -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 2 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 5) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['alerta_academica_activo']->value))) {
echo $_smarty_tpl->tpl_vars['alerta_academica_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-chart-line"></i>
                            <span> Alerta Académica</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
alerta_academica/listar"><i class="fas fa-list"></i> Generar Predicción</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
alerta_academica/tablero"><i class="fas fa-chart-bar"></i> Tablero de alertas</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE ALERTA ACADEMICA -->

                <!-- MODULO DE COMPORTAMIENTO -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 5) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['comportamiento_activo']->value))) {
echo $_smarty_tpl->tpl_vars['comportamiento_activo']->value;
}?>">
                        <a href="#">
                                <i class="fas fa-handshake"></i>
                            <span> Comportamiento</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
comportamiento/listar"><i class="fas fa-list"></i> Listar Grados</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE COMPORTAMIENTO -->

                <!-- MODULO DE ASISTENCIA -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 5) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['asistencia_activo']->value))) {
echo $_smarty_tpl->tpl_vars['asistencia_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-calendar-alt"></i>
                            <span> Asistencia</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
asistencia/listar"><i class="fas fa-list"></i> Listar Grados</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE ASISTENCIA -->

                <!-- MODULO DE MENSAJES -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 3 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 9) {?>
               <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['mensaje_activo']->value))) {
echo $_smarty_tpl->tpl_vars['mensaje_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-envelope"></i>
                            <span> Mensaje</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 3) {?>
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
mensaje/listar"><i class="fas fa-list"></i> Mis Mensajes</a></li>
                            <?php } else { ?>
                            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
mensaje/agregar"><i class="fa fa-plus"></i> Agregar</a></li>
                            <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 9) {?>
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
mensaje/listar"><i class="fas fa-list"></i> Listar</a></li>
                                <?php }?>
                            <?php }?>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE MENSAJES -->

                <!-- MODULO DE REPORTES -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 3) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['reportes_activo']->value))) {
echo $_smarty_tpl->tpl_vars['reportes_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-folder-open"></i>
                            <span> Reportes</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 3) {?>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/ver"><i class="fas fa-file-pdf"></i> Mis Reportes</a></li>
                            <?php } else { ?>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
reporte/ver_admin"><i class="fas fa-file-pdf"></i> Ver</a></li>
                            <?php }?>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE REPORTES -->

            <!-- MODULO DE PSICOLOGIA -->
            <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
            <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['psicologia_activo']->value))) {
echo $_smarty_tpl->tpl_vars['psicologia_activo']->value;
}?>">
                <a href="#">
                    <i class="fa fa-user-md"></i>
                    <span> Psicología</span>
                    <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
psicologia/listar"><i class="fas fa-list"></i> Listado</a></li>
                </ul>
            </li>
        <?php }?>
        <!-- FIN MODULO DE PSICOLOGIA -->

                <!-- MODULO DE CONFIGURACION -->
                <?php if ($_smarty_tpl->tpl_vars['usuario_privilegio']->value == 1 || $_smarty_tpl->tpl_vars['usuario_privilegio']->value == 7) {?>
                    <li class="treeview <?php if ((isset($_smarty_tpl->tpl_vars['configuracion_activo']->value))) {
echo $_smarty_tpl->tpl_vars['configuracion_activo']->value;
}?>">
                        <a href="#">
                            <i class="fas fa-cogs"></i>
                            <span> Configuración</span>
                            <i class="fa fa-angle-left float-right pr-3 sidebar-angle"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
configuracion/listar"><i class="fas fa-list"></i> Ver Años Escolares</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
configuracion/anio_escolar"><i class="fas fa-plus"></i> Aperturar Año Escolar</a></li>
                        </ul>
                    </li>
                <?php }?>
                <!-- FIN MODULO DE CONFIGURACION -->
            </ul>
        </div>
        <!-- /#sidebar-wrapper --><?php }
}
