/* Script que se ejecuta ante cualquier submit de formularios */
$('.formulario').on('submit', function (e) {
    e.preventDefault(); //Para evitar doble proceso
    var formulario = $(this); //Obtenemos el formulario completo
    var url = formulario.attr('action'); //obtenemos la url de envio
    var metodo = formulario.attr('method'); //Obtenemos el metodo de envio (POST o GET)        
    var data = formulario.serialize(); //Convertimos los datos en un arraylist
    //alert(data);
    $.ajax({
        url: url,
        type: metodo,
        data: data,
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('.fa-spinner').css('display', 'inline').delay(2000);
        }
    })
        .done(function (data) {
            console.log(data);
            formulario.find('.response').html(data).hide().slideDown();
        })
        .fail(function (data) { //Cuando hay error crítico
            console.log(data);
            formulario.find('.response').html(data).hide().slideDown();
        })
        .always(function () { //Siempre se ejecuta asi sea true o false                
            $('.fa-spinner').hide();

        });
    return false;
});


/* Lanza la alerta para bloquear */
$(document).on('click', '.bloquear', function (e) {
    e.preventDefault();
    var url = $(this).attr("data-url"); // url a la cual enviara el id para eliminar

    swal({
        title: '¿Seguro que desea bloquear?',
        text: "Recuerde que puede desbloquear en cualquier momento",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, bloquear!',
        cancelButtonText: 'Cancelar',

        preConfirm: function () {
            return new Promise(function (response) {
                $.post(url, {}, function (response) { //Tres Parametros (url para hacer la accion, data vacia, funcion de respuesta)//                    
                    $('.response').html(response);
                });

            });
        }
    });
});

/* Lanza la alerta para desbloquear */
$(document).on('click', '.desbloquear', function (e) {
    e.preventDefault();
    var url = $(this).attr("data-url"); // url a la cual enviara el id para eliminar

    swal({
        title: '¿Seguro que desea desbloquear?',
        text: "Recuerde que puede bloquear en cualquier momento",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, desbloquear!',
        cancelButtonText: 'Cancelar',

        preConfirm: function () {
            return new Promise(function (response) {
                $.post(url, {}, function (response) { //Tres Parametros (url para hacer la accion, data vacia, funcion de respuesta)//                    
                    $('.response').html(response);
                });

            });
        }
    });
});

/* Lanza la alerta para eliminar */
$(document).on('click', '.eliminar', function (e) {
    e.preventDefault();
    var url = $(this).attr("data-url"); // url a la cual enviara el id para eliminar

    swal({
        title: '¿Seguro que desea eliminar?',
        text: "Recuerde que esta acción no se puede revertir",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, eliminar!',
        cancelButtonText: 'Cancelar',

        preConfirm: function () {
            return new Promise(function (response) {
                $.post(url, {}, function (response) { //Tres Parametros (url para hacer la accion, data vacia, funcion de respuesta)//                    
                    $('.response').html(response);
                });

            });
        }
    });
});

/* -------------------- MODULO DE USUARIO-------------------- */
/* ------------------------------------------------------------- */
$(document).on('click', '#mostrar-contrasena', function (e) {
    var x = document.getElementById("contrasena-usuario");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
});

/* -------------------- FIN MODULO USUARIO -------------------- */



/* -------------------- MODULO DE MATRICULA -------------------- */
/* ------------------------------------------------------------- */

/* Script para generar el codigo el alumno */
$(".dni").keyup(function (e) {
    dni_alumno = $("#dni").val();
    if (e.keyCode == 46 || e.keyCode == 8) { //tecla suprimir o borrar(backspace)
        $('.formulario').trigger("reset");
    }
    $(".dni").val(dni_alumno);
});

/* Script para buscar alumno por dni */
$(".buscar-alumno").click(function () {
    //Obtenemos dni
    var dni_alumno = $(".dni").val();

    $.ajax({
        url: base_url + 'matricula/buscar_alumno',
        type: "post",
        data: {
            dni_alumno: dni_alumno
        },
        beforeSend: function () {
            $.ajaxblock(); // Animacion Loader
        }
    })
        .done(function (data) {
            if (data) {
                var dato = JSON.parse(data);
                //Seteamos valores                                        
                $(".codigo_alumno").val(dato.codigo_alumno);
                $(".nombres").val(dato.nombre);
                $(".ape_paterno").val(dato.apellido_paterno);
                $(".ape_materno").val(dato.apellido_materno);
                $(".dni").val(dato.dni);
                $(".fecha_nacimiento").val(dato.fecha_nacimiento);
                $(".imagen").val(dato.imagen);
                $(".direccion").val(dato.direccion);
                $("#id_distrito option[value='" + dato.id_distrito + "']").attr("selected", true);
                $('.selectpicker').selectpicker('refresh'); //Refrescamos combo
                $.ajaxunblock();

                if (dato.encontrado) {
                    $('#toast-encontrado').toast('show');
                }
                else {
                    $('#toast-noencontrado').toast('show');
                }

            }
        })
        .fail(function (data) {
            console.log(data);
            $.ajaxunblock();
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;
});

/* Script para filtrar combo de grados */
$('.radio-nivel-educativo').change(function () {
    $.post(base_url + 'matricula/grados_por_nivel', {
        id_nivel_educativo: $('input:radio[name=id_nivel_educativo]:checked').val()
    },
        function (response) {
            if (response[0].nombre_grado == 'vacio') {
                $('#id_grado').html('Sin grados registrados');
                $('#id_grado').selectpicker('refresh');
            } else {
                $('#id_grado').html('<option value="">Seleccione una opción</option>');
                var total = Object.keys(response).length;
                for (var i = 0; i < total; i++) {
                    $('#id_grado').append('<option value="' + response[i].id_grado + '">' + response[i].nombre_grado + '</option>');
                }
                $('#id_grado').selectpicker('refresh');
            }
        }, 'json');
});

/* Script para mostrar las matriculas segun año escolar seleccionado */
$(document).on('change', "#id_anio_escolar_matricula", function (e) {
    let id_anio_escolar = $(this).val(); //Año escolar seleccionado del combo
    window.location.href = base_url + `matricula/listar/${id_anio_escolar}`
});
/* -------------------- FIN MODULO DE MATRICULA -------------------- */

/* -------------------- MODULO DE REGISTRO AUXILIAR -------------------- */

//Script para resetear todos los campos al cambiar de año escolar (Asignar Docente)
$(document).on('change', "#id_anio_escolar", function (e) {
    $("#id_grado").prop('selectedIndex', 0);
    $("#id_curso").prop('selectedIndex', 0);
    $('.selectpicker').selectpicker('refresh');
    $('input[name="id_nivel_educativo"]').prop('checked', false);

});

//Script para filtrar los grados segun el nivel educativo
$('.radio_nivel_educativo').change(function () {
    var id_nivel_educativo = $('input:radio[name=id_nivel_educativo]:checked').val();
    $.post(
        base_url + 'registro_auxiliar/grados_por_nivel', {
        id_nivel_ducativo: id_nivel_educativo
    },
        function (response) {
            if (response[0].nombre_grado == 'vacio') {
                $('#id_grado').html('Sin grados registrados');
                $('#id_grado').selectpicker('refresh');
            } else {
                $('#id_grado').html('<option value="">Seleccione una opción</option>');
                var total = Object.keys(response).length;
                for (var i = 0; i < total; i++) {
                    $('#id_grado').append('<option value="' + response[i].id_grado + '">' + response[i].nombre_grado + '</option>');
                }
                $('#id_grado').selectpicker('refresh');
            }
        }, 'json');

});

//Script para filtrar los cursos segun el grado
$('.box_grado').change(function () {
    var id_grado = $('#id_grado').val();
    var id_anio_escolar = $('#id_anio_escolar').val();
    $.post(
        base_url + 'registro_auxiliar/cursos_por_grado', {
        id_grado: id_grado, id_anio_escolar: id_anio_escolar
    },
        function (response) {
            if (response[0].nombre_curso == 'vacio') {
                $('#id_curso').html('Sin cursos registrados');
                $('#id_curso').selectpicker('refresh');
            } else {
                $('#id_curso').html('<option value="">Seleccione una opción</option>');
                var total = Object.keys(response).length;
                for (var i = 0; i < total; i++) {
                    $('#id_curso').append('<option value="' + response[i].id_curso + '">' + response[i].nombre_curso + '</option>');
                }
                $('#id_curso').selectpicker('refresh');
            }
        }, 'json');

});

// Script para enviar la ruta del formulario del modal para calificar curso
$(document).on('click', '.boton-calificar', function (e) {
    e.preventDefault();
    //Obtenemos data
    var id_curso = $(this).attr("data-idcurso");
    var id_anio_escolar = $(this).attr("data-idanioescolar");

    $.ajax({
        url: base_url + 'registro_auxiliar/calificar_curso',
        type: "post",
        data: {
            id_curso: id_curso,
            id_anio_escolar: id_anio_escolar
        }
    })
        .done(function (data) {
            //Seteamos valores
            $(".nombre-curso").html(data.nombre_curso);
            var html = "";
            switch (data.id_nivel_educativo) {
                case "1": //INICIAL                
                    /*
                    html += "<a href='" + data.ruta_bimestre_1 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_1 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_2 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_2 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_3 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_3 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_4 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_4 + "</a>";
                    */
                    break;
                case "2": //PRIMARIA
                    html += "<a href='" + data.ruta_bimestre_1 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_1 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_2 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_2 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_3 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_3 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_4 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_4 + "</a>";
                    break;
                case "3": //SECUNDARIA
                    html += "<a href='" + data.ruta_bimestre_1 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_1 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_2 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_2 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_3 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_3 + "</a>";
                    html += "<a href='" + data.ruta_bimestre_4 + "' class='btn btn-outline-primary m-1' role='button'>" + data.nombre_bimestre_4 + "</a>";
                    break;
            }
            $(".contenido-calificar").html(html);
        })

        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;


});

/* Script para mostrar los cursos del docente segun año escolar seleccionado */
$(document).on('change', "#id_anio_escolar_docente", function (e) {
    let id_anio_escolar = $(this).val(); //Año escolar seleccionado del combo
    let id_docente = $("#id_docente_cursos").val(); //ID del docente
    window.location.href = base_url + `registro_auxiliar/listar_cursos_docente/${id_docente}/${id_anio_escolar}`
});

/* -------------------- FIN MODULO DE REGISTRO AUXILIAR -------------------- */

/* -------------------- MODULO DE NOTAS - INICIAL -------------------- */

/* Script para mostrar los indicadores pertenecientes a la competencia seleccionada y para mostrar las notas pertenecientes a los indicadores */
$(".competencia").click(function () {
    //Obtenemos datos
    var id_curso = $(".registro-inicial-curso").val();
    var id_bimestre = $(".registro-inicial-bimestre").val();
    var id_anio_escolar = $(".registro-inicial-anioescolar").val();
    var id_grado = $(".registro-inicial-grado").val();
    var id_nivel_educativo = $(".registro-inicial-niveleducativo").val();
    var numero_competencia = $(this).attr("data-competencia");
    $(".competencia").removeClass("active"); //Quitamos la clase active a todas las competencias
    $(this).addClass("active"); // Añadimos la clase active a la competencia seleccionada
    $.ajax({
        url: base_url + 'inicial/obtener_notas_indicadores',
        type: "post",
        data: {
            id_curso: id_curso,
            id_bimestre: id_bimestre,
            id_anio_escolar: id_anio_escolar,
            id_grado: id_grado,
            id_nivel_educativo: id_nivel_educativo,
            numero_competencia: numero_competencia,
            beforeSend: function () {
                $(".tabla-indicadores").html(""); //Limpiamos tabla de indicadores
                $(".celda-nota").remove(); //Limpiamos tabla de indicadores
                $('#spinner-carga').removeClass("hide-spinner"); //Quitamos clase que oculta el spinner
                $('#spinner-carga').addClass("show-spinner").delay(2000); //Añadimos clase que muestra el spinner
            }
        }
    })
        .done(function (data) {
            //Recibimos data
            var html_indicadores = "";
            //var html_notas = "";
            var arreglo_indicadores = data["nombre_indicadores"];
            var arreglo_notas = data["notas_alumnos"];
            var inicio_rango = data["inicio_rango"];
            var nota_1 = "nota_" + inicio_rango++;
            var nota_2 = "nota_" + inicio_rango++;
            var nota_3 = "nota_" + inicio_rango++;
            var nota_4 = "nota_" + inicio_rango++;
            var nota_5 = "nota_" + inicio_rango++;
            var nota_6 = "nota_" + inicio_rango++;
            var nota_7 = "nota_" + inicio_rango++;
            var nota_8 = "nota_" + inicio_rango++;
            var promedio_competencia = "promedio_competencia_" + numero_competencia;
            var numero = 1;
            var i = 1;
            //Recorremos el arrego de indicadores y armamos las filas a mostrar
            html_indicadores += "<input type='hidden' name='id_indicador_competencia_curso_inicial' class='id_indicador_competencia_curso_inicial' value='" + data["id_indicador_competencia_curso_inicial"] + "'>";
            Object.keys(arreglo_indicadores).forEach(function (key) {
                html_indicadores += "<tr>";
                html_indicadores += "<th class='num-indicador'>" + numero + "</th>";
                html_indicadores += "<td>";
                html_indicadores += "<textarea class='form-control indicador' rows='2' maxlength='120' name='" + key + "'>" + arreglo_indicadores[key] + "</textarea>";
                html_indicadores += "</td>";
                html_indicadores += "</tr>";
                numero++;
            });
            //Mostramos los indicadores
            $(".tabla-indicadores").html(html_indicadores);

            //Recorremos el arreglo de notas y promedio de competencia y armamos las filas a mostrar            
            Object.keys(arreglo_notas).forEach(function (key) {
                html_notas = "";
                //console.log("name: " + nota_1 + " --- " + "value: " + arreglo_notas[key][nota_1]);
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_1 + "' value='" + arreglo_notas[key][nota_1] + "'>";
                html_notas += "<input type='hidden' name='id_nota_alumno_inicial' class='id_nota_alumno_inicial' value='" + arreglo_notas[key]["id_nota_alumno_inicial"] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_2 + "' value='" + arreglo_notas[key][nota_2] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_3 + "' value='" + arreglo_notas[key][nota_3] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_4 + "' value='" + arreglo_notas[key][nota_4] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_5 + "' value='" + arreglo_notas[key][nota_5] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_6 + "' value='" + arreglo_notas[key][nota_6] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_7 + "' value='" + arreglo_notas[key][nota_7] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0 nota' name='" + nota_8 + "' value='" + arreglo_notas[key][nota_8] + "'>";
                html_notas += "</td>";
                html_notas += "<td class='td-nota celda-nota'>";
                html_notas += "<input type='number' class='form-control m-0' name='" + promedio_competencia + "' value='" + arreglo_notas[key][promedio_competencia] + "' readonly>";
                html_notas += "</td>";
                $("#fila_" + i).append(html_notas);
                i++;
            });
        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        })
        .always(function () { //Siempre se ejecuta asi sea true o false                
            $('#spinner-carga').removeClass("show-spinner"); //Quitamos clase que muestra el spinner
            $('#spinner-carga').addClass("hide-spinner"); //Añadimos clase que oculta el spinner
        });
    return false;

});

/* Script para guardar las notas al cambiar de foco al input de notas */
//$(document).on('blur', '.nota', function () {
$(document).on('keyup', '.nota', function () {
    var celda = $(this).parents("tr").find("td"); //Obtenemos celda dentro de la fila seleccionada

    var name_nota_1 = celda.eq(2).find("input").attr("name");;
    var nota_1 = celda.eq(2).find("input").val();
    var n1 = [name_nota_1, nota_1];

    var name_nota_2 = celda.eq(3).find("input").attr("name");
    var nota_2 = celda.eq(3).find("input").val();
    var n2 = [name_nota_2, nota_2];

    var name_nota_3 = celda.eq(4).find("input").attr("name");
    var nota_3 = celda.eq(4).find("input").val();
    var n3 = [name_nota_3, nota_3];

    var name_nota_4 = celda.eq(5).find("input").attr("name");
    var nota_4 = celda.eq(5).find("input").val();
    var n4 = [name_nota_4, nota_4];

    var name_nota_5 = celda.eq(6).find("input").attr("name");
    var nota_5 = celda.eq(6).find("input").val();
    var n5 = [name_nota_5, nota_5];

    var name_nota_6 = celda.eq(7).find("input").attr("name");
    var nota_6 = celda.eq(7).find("input").val();
    var n6 = [name_nota_6, nota_6];

    var name_nota_7 = celda.eq(8).find("input").attr("name");
    var nota_7 = celda.eq(8).find("input").val();
    var n7 = [name_nota_7, nota_7];

    var name_nota_8 = celda.eq(9).find("input").attr("name");
    var nota_8 = celda.eq(9).find("input").val();
    var n8 = [name_nota_8, nota_8];

    var name_promedio_competencia = celda.eq(10).find("input").attr("name");

    var id_nota_alumno_inicial = celda.find(".id_nota_alumno_inicial").val();

    $.ajax({
        url: base_url + 'inicial/guardar_notas',
        type: "post",
        data: {
            id_nota_alumno_inicial: id_nota_alumno_inicial,
            nota_1: n1,
            nota_2: n2,
            nota_3: n3,
            nota_4: n4,
            nota_5: n5,
            nota_6: n6,
            nota_7: n7,
            nota_8: n8,
            name_promedio_competencia: name_promedio_competencia
        }
    })
        .done(function (data) {
            celda.eq(10).find("input").val(data); //Seteamos el promedio
        })

        .fail(function (data) {
            console.log(data);
            //$(".response").html("Ha ocurrido un error interno");
        });
    return false;


});

/* Script para guardar indicador al cambiar de foco del textarea */
$(document).on('blur', '.indicador', function () {
    var indicador = $(this).val(); //Contenido del indicador ingresado por el docente
    var name_indicador = $(this).attr("name"); //Name del textarea
    var id_indicador_competencia_curso_inicial = $(".id_indicador_competencia_curso_inicial").val();
    $.ajax({
        url: base_url + 'inicial/guardar_indicador',
        type: "post",
        data: {
            id_indicador_competencia_curso_inicial: id_indicador_competencia_curso_inicial,
            indicador: indicador,
            name_indicador: name_indicador
        }
    })
        .fail(function (data) {
            console.log(data);
            //$(".response").html("Ha ocurrido un error interno");
        });
    return false;


});

/* -------------------- FIN MODULO DE NOTAS - INICIAL -------------------- */


/* -------------------- MODULO DE NOTAS - PRIMARIA -------------------- */

/* Script para mostrar las notas pertenecientes al concepto seleccionado */
$(".concepto-primaria").click(function () {
    //Obtenemos datos
    var id_curso = $(".registro-primaria-curso").val();
    var id_bimestre = $(".registro-primaria-bimestre").val();
    var id_anio_escolar = $(".registro-primaria-anioescolar").val();
    var id_grado = $(".registro-primaria-grado").val();
    var id_nivel_educativo = $(".registro-primaria-niveleducativo").val();
    var numero_concepto = $(this).attr("data-concepto");

    $(".concepto-primaria").removeClass("active"); //Quitamos la clase active a todas las competencias
    $(this).addClass("active"); // Añadimos la clase active a la competencia seleccionada
    $(".registro-primaria-numero-concepto").val(numero_concepto); //Se usa al momento de registrar las notas
    $.ajax({
        url: base_url + 'primaria/obtener_notas_concepto',
        type: "post",
        data: {
            id_curso: id_curso,
            id_bimestre: id_bimestre,
            id_anio_escolar: id_anio_escolar,
            id_grado: id_grado,
            id_nivel_educativo: id_nivel_educativo,
            numero_concepto: numero_concepto,
            beforeSend: function () {
                //$(".tabla-indicadores").html(""); //Limpiamos tabla de indicadores
                $(".celda-nota").remove(); //Limpiamos tabla de notas
                $(".celda-encabezado").remove(); //Limpiamos tabla de notas
                $(".contenido-leyenda").html(""); //Limpiamos leyenda
                $('#spinner-carga').removeClass("hide-spinner"); //Quitamos clase que oculta el spinner
                $('#spinner-carga').addClass("show-spinner").delay(2000); //Añadimos clase que muestra el spinner                
            }
        }
    })
        .done(function (data) {
            //Recibimos data                                    
            var arreglo_notas = data["notas_alumnos"];
            var encabezado = data["encabezado"];
            var leyenda = data["leyenda"];
            var i = 1;
            html_encabezado = "";
            var html_leyenda = "";

            switch (numero_concepto) {
                case "1":
                case "2":
                    //Mostramos Leyenda
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l1"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l2"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l3"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l4"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l5"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l6"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l7"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l8"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["prom"] + "</button>";

                    //Data para el encabezado
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n1"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n2"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n3"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n4"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n5"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n6"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n7"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n8"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["promedio"] + "</th>";

                    //Armamos campos para las notas
                    var nota_1 = "n" + numero_concepto + "_s1";
                    var nota_2 = "n" + numero_concepto + "_s2";
                    var nota_3 = "n" + numero_concepto + "_s3";
                    var nota_4 = "n" + numero_concepto + "_s4";
                    var nota_5 = "n" + numero_concepto + "_s5";
                    var nota_6 = "n" + numero_concepto + "_s6";
                    var nota_7 = "n" + numero_concepto + "_s7";
                    var nota_8 = "n" + numero_concepto + "_s8";
                    var promedio_concepto = "n" + numero_concepto + "_promedio";
                    break;
                case "3":
                    //Mostramos Leyenda                                                            
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l1"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l2"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l3"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l4"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["prom"] + "</button>";

                    //Data para el encabezado
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n1"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n2"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n3"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n4"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["promedio"] + "</th>";

                    //Armamos campos para las notas
                    var nota_1 = "n" + numero_concepto + "_rc1";
                    var nota_2 = "n" + numero_concepto + "_co1";
                    var nota_3 = "n" + numero_concepto + "_rc2";
                    var nota_4 = "n" + numero_concepto + "_co2";
                    var promedio_concepto = "n" + numero_concepto + "_promedio";
                    break;
                case "4":
                    //Mostramos Leyenda                    
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l1"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l2"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["prom"] + "</button>";
                    //Data para el encabezado
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n1"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n2"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n3"] + "</th>";
                    break;
            }
            //Seteamos cabecera
            $("#encabezado-notas").append(html_encabezado);
            $(".contenido-leyenda").append(html_leyenda);


            //Recorremos el arreglo de notas y promedio de conceptos y armamos las filas a mostrar
            Object.keys(arreglo_notas).forEach(function (key) {
                switch (numero_concepto) {
                    case "1":
                    case "2":
                        //Armamos notas
                        html_notas = "";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_1 + "' value='" + arreglo_notas[key][nota_1] + "'>";
                        html_notas += "<input type='hidden' name='id_nota_alumno_primaria' class='id_nota_alumno_primaria' value='" + arreglo_notas[key]["id_nota_alumno_primaria"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_2 + "' value='" + arreglo_notas[key][nota_2] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_3 + "' value='" + arreglo_notas[key][nota_3] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_4 + "' value='" + arreglo_notas[key][nota_4] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_5 + "' value='" + arreglo_notas[key][nota_5] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_6 + "' value='" + arreglo_notas[key][nota_6] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_7 + "' value='" + arreglo_notas[key][nota_7] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_8 + "' value='" + arreglo_notas[key][nota_8] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0' name='" + promedio_concepto + "' value='" + arreglo_notas[key][promedio_concepto] + "' readonly>";
                        html_notas += "</td>";
                        $("#fila_" + i).append(html_notas);
                        i++;
                        break;

                    case "3":
                        //Armamos notas
                        html_notas = "";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_1 + "' value='" + arreglo_notas[key][nota_1] + "'>";
                        html_notas += "<input type='hidden' name='id_nota_alumno_primaria' class='id_nota_alumno_primaria' value='" + arreglo_notas[key]["id_nota_alumno_primaria"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_2 + "' value='" + arreglo_notas[key][nota_2] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_3 + "' value='" + arreglo_notas[key][nota_3] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='" + nota_4 + "' value='" + arreglo_notas[key][nota_4] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0' name='" + promedio_concepto + "' value='" + arreglo_notas[key][promedio_concepto] + "' readonly>";
                        html_notas += "</td>";
                        $("#fila_" + i).append(html_notas);
                        i++;
                        break;
                    case "4":
                        html_notas = "";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='examen_mensual' value='" + arreglo_notas[key]["examen_mensual"] + "'>";
                        html_notas += "<input type='hidden' name='id_nota_alumno_primaria' class='id_nota_alumno_primaria' value='" + arreglo_notas[key]["id_nota_alumno_primaria"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='examen_bimestral' value='" + arreglo_notas[key]["examen_bimestral"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-primaria' name='promedio_final' value='" + arreglo_notas[key]["promedio_final"] + "' readonly>";
                        html_notas += "</td>";
                        $("#fila_" + i).append(html_notas);
                        i++;
                        break;
                }
            });

        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        })
        .always(function () { //Siempre se ejecuta asi sea true o false                
            $('#spinner-carga').removeClass("show-spinner"); //Quitamos clase que muestra el spinner
            $('#spinner-carga').addClass("hide-spinner"); //Añadimos clase que oculta el spinner
        });
    return false;

});

/* Script para guardar las notas al cambiar de foco al input de notas */
//$(document).on('blur', '.nota-primaria', function () {
$(document).on('keyup', '.nota-primaria', function () {    
    var celda = $(this).parents("tr").find("td"); //Obtenemos celda dentro de la fila seleccionada

    var name_nota_1 = celda.eq(2).find("input").attr("name");;
    var nota_1 = celda.eq(2).find("input").val();
    var n1 = [name_nota_1, nota_1];

    var name_nota_2 = celda.eq(3).find("input").attr("name");
    var nota_2 = celda.eq(3).find("input").val();
    var n2 = [name_nota_2, nota_2];

    var name_nota_3 = celda.eq(4).find("input").attr("name");
    var nota_3 = celda.eq(4).find("input").val();
    var n3 = [name_nota_3, nota_3];

    var name_nota_4 = celda.eq(5).find("input").attr("name");
    var nota_4 = celda.eq(5).find("input").val();
    var n4 = [name_nota_4, nota_4];

    var name_nota_5 = celda.eq(6).find("input").attr("name");
    var nota_5 = celda.eq(6).find("input").val();
    var n5 = [name_nota_5, nota_5];

    var name_nota_6 = celda.eq(7).find("input").attr("name");
    var nota_6 = celda.eq(7).find("input").val();
    var n6 = [name_nota_6, nota_6];

    var name_nota_7 = celda.eq(8).find("input").attr("name");
    var nota_7 = celda.eq(8).find("input").val();
    var n7 = [name_nota_7, nota_7];

    var name_nota_8 = celda.eq(9).find("input").attr("name");
    var nota_8 = celda.eq(9).find("input").val();
    var n8 = [name_nota_8, nota_8];

    var name_promedio_concepto = celda.eq(10).find("input").attr("name");

    var numero_concepto = $(".registro-primaria-numero-concepto").val();

    var id_nota_alumno_primaria = celda.find(".id_nota_alumno_primaria").val();

    $.ajax({
        url: base_url + 'primaria/guardar_notas',
        type: "post",
        data: {
            id_nota_alumno_primaria: id_nota_alumno_primaria,
            nota_1: n1,
            nota_2: n2,
            nota_3: n3,
            nota_4: n4,
            nota_5: n5,
            nota_6: n6,
            nota_7: n7,
            nota_8: n8,
            name_promedio_concepto: name_promedio_concepto,
            numero_concepto: numero_concepto
        }
    })
        .done(function (data) {
            switch (numero_concepto) {
                case "1":
                case "2":
                    celda.eq(10).find("input").val(data); //Seteamos el promedio
                    break;
                case "3":
                    celda.eq(6).find("input").val(data); //Seteamos el promedio
                    break;
                case "4":
                    celda.eq(4).find("input").val(data); //Seteamos el promedio
                    break;
            }
        })

        .fail(function (data) {
            console.log(data);
            //$(".response").html("Ha ocurrido un error interno");
        });
    return false;


});

/* -------------------- FIN MODULO DE NOTAS - PRIMARIA -------------------- */

/* -------------------- MODULO DE NOTAS - SECUNDARIA  -------------------- */

/* Script para mostrar las notas pertenecientes al concepto seleccionado */
$(".concepto-secundaria").click(function () {
    //Obtenemos datos
    var id_curso = $(".registro-secundaria-curso").val();
    var id_bimestre = $(".registro-secundaria-bimestre").val();
    var id_anio_escolar = $(".registro-secundaria-anioescolar").val();
    var id_grado = $(".registro-secundaria-grado").val();
    var id_nivel_educativo = $(".registro-secundaria-niveleducativo").val();
    var numero_concepto = $(this).attr("data-concepto");

    $(".concepto-secundaria").removeClass("active"); //Quitamos la clase active a todas las competencias
    $(this).addClass("active"); // Añadimos la clase active a la competencia seleccionada
    $(".registro-secundaria-numero-concepto").val(numero_concepto); //Se usa al momento de registrar las notas
    $.ajax({
        url: base_url + 'secundaria/obtener_notas_concepto',
        type: "post",
        data: {
            id_curso: id_curso,
            id_bimestre: id_bimestre,
            id_anio_escolar: id_anio_escolar,
            id_grado: id_grado,
            id_nivel_educativo: id_nivel_educativo,
            numero_concepto: numero_concepto,
            beforeSend: function () {
                $(".celda-nota").remove(); //Limpiamos tabla de notas
                $(".celda-encabezado").remove(); //Limpiamos tabla de notas                
                $(".contenido-leyenda").html(""); //Limpiamos leyenda
                $('#spinner-carga').removeClass("hide-spinner"); //Quitamos clase que oculta el spinner
                $('#spinner-carga').addClass("show-spinner").delay(2000); //Añadimos clase que muestra el spinner
            }
        }
    })
        .done(function (data) {
            //Recibimos data
            var arreglo_notas = data["notas_alumnos"];
            var encabezado = data["encabezado"];
            var leyenda = data["leyenda"];
            var i = 1;
            var html_encabezado = "";
            var html_leyenda = "";

            switch (numero_concepto) {
                case "1":
                case "2":
                    //Mostramos Leyenda                    
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l1"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l2"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l3"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l4"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l5"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l6"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l7"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l8"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["prom"] + "</button>";

                    //Data para el encabezado
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n1"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n2"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n3"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n4"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n5"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n6"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n7"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n8"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["promedio"] + "</th>";

                    //Armamos campos para las notas
                    var nota_1 = "n" + numero_concepto + "_s1";
                    var nota_2 = "n" + numero_concepto + "_s2";
                    var nota_3 = "n" + numero_concepto + "_s3";
                    var nota_4 = "n" + numero_concepto + "_s4";
                    var nota_5 = "n" + numero_concepto + "_s5";
                    var nota_6 = "n" + numero_concepto + "_s6";
                    var nota_7 = "n" + numero_concepto + "_s7";
                    var nota_8 = "n" + numero_concepto + "_s8";
                    var promedio_concepto = "n" + numero_concepto + "_promedio";
                    break;
                case "3":
                    //Mostramos Leyenda                                                            
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l1"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l2"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l3"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l4"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["prom"] + "</button>";

                    //Data para el encabezado
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n1"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n2"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n3"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n4"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["promedio"] + "</th>";

                    //Armamos campos para las notas
                    var nota_1 = "n" + numero_concepto + "_rc1";
                    var nota_2 = "n" + numero_concepto + "_co1";
                    var nota_3 = "n" + numero_concepto + "_rc2";
                    var nota_4 = "n" + numero_concepto + "_co2";
                    var promedio_concepto = "n" + numero_concepto + "_promedio";
                    break;
                case "4":
                    //Mostramos Leyenda                    
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l1"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["l2"] + "</button>";
                    html_leyenda += "<button type='button' class='list-group-item list-group-item-action p-1'>" + leyenda["prom"] + "</button>";
                    //Data para el encabezado
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n1"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n2"] + "</th>";
                    html_encabezado += "<th class='th-nota celda-encabezado'>" + encabezado["n3"] + "</th>";
                    break;
            }
            //Seteamos cabecera
            $("#encabezado-notas").append(html_encabezado);
            $(".contenido-leyenda").append(html_leyenda);


            //Recorremos el arreglo de notas y promedio de conceptos y armamos las filas a mostrar
            Object.keys(arreglo_notas).forEach(function (key) {
                switch (numero_concepto) {
                    case "1":
                    case "2":
                        //Armamos notas
                        html_notas = "";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_1 + "' value='" + arreglo_notas[key][nota_1] + "'>";
                        html_notas += "<input type='hidden' name='id_nota_alumno_secundaria' class='id_nota_alumno_secundaria' value='" + arreglo_notas[key]["id_nota_alumno_secundaria"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_2 + "' value='" + arreglo_notas[key][nota_2] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_3 + "' value='" + arreglo_notas[key][nota_3] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_4 + "' value='" + arreglo_notas[key][nota_4] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_5 + "' value='" + arreglo_notas[key][nota_5] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_6 + "' value='" + arreglo_notas[key][nota_6] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_7 + "' value='" + arreglo_notas[key][nota_7] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_8 + "' value='" + arreglo_notas[key][nota_8] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0' name='" + promedio_concepto + "' value='" + arreglo_notas[key][promedio_concepto] + "' readonly>";
                        html_notas += "</td>";
                        $("#fila_" + i).append(html_notas);
                        i++;
                        break;

                    case "3":
                        //Armamos notas
                        html_notas = "";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_1 + "' value='" + arreglo_notas[key][nota_1] + "'>";
                        html_notas += "<input type='hidden' name='id_nota_alumno_secundaria' class='id_nota_alumno_secundaria' value='" + arreglo_notas[key]["id_nota_alumno_secundaria"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_2 + "' value='" + arreglo_notas[key][nota_2] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_3 + "' value='" + arreglo_notas[key][nota_3] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='" + nota_4 + "' value='" + arreglo_notas[key][nota_4] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0' name='" + promedio_concepto + "' value='" + arreglo_notas[key][promedio_concepto] + "' readonly>";
                        html_notas += "</td>";
                        $("#fila_" + i).append(html_notas);
                        i++;
                        break;
                    case "4":
                        html_notas = "";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='examen_mensual' value='" + arreglo_notas[key]["examen_mensual"] + "'>";
                        html_notas += "<input type='hidden' name='id_nota_alumno_secundaria' class='id_nota_alumno_secundaria' value='" + arreglo_notas[key]["id_nota_alumno_secundaria"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='examen_bimestral' value='" + arreglo_notas[key]["examen_bimestral"] + "'>";
                        html_notas += "</td>";
                        html_notas += "<td class='td-nota celda-nota'>";
                        html_notas += "<input type='text' class='form-control m-0 nota-secundaria' name='promedio_final' value='" + arreglo_notas[key]["promedio_final"] + "' readonly>";
                        html_notas += "</td>";
                        $("#fila_" + i).append(html_notas);
                        i++;
                        break;
                }
            });


        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        })
        .always(function () { //Siempre se ejecuta asi sea true o false                
            $('#spinner-carga').removeClass("show-spinner"); //Quitamos clase que muestra el spinner
            $('#spinner-carga').addClass("hide-spinner"); //Añadimos clase que oculta el spinner
        });
    return false;

});

/* Script para guardar las notas al cambiar de foco al input de notas */
//$(document).on('blur', '.nota-secundaria', function () {
$(document).on('keyup', '.nota-secundaria', function () {
    var celda = $(this).parents("tr").find("td"); //Obtenemos celda dentro de la fila seleccionada

    var name_nota_1 = celda.eq(2).find("input").attr("name");;
    var nota_1 = celda.eq(2).find("input").val();
    var n1 = [name_nota_1, nota_1];

    var name_nota_2 = celda.eq(3).find("input").attr("name");
    var nota_2 = celda.eq(3).find("input").val();
    var n2 = [name_nota_2, nota_2];

    var name_nota_3 = celda.eq(4).find("input").attr("name");
    var nota_3 = celda.eq(4).find("input").val();
    var n3 = [name_nota_3, nota_3];

    var name_nota_4 = celda.eq(5).find("input").attr("name");
    var nota_4 = celda.eq(5).find("input").val();
    var n4 = [name_nota_4, nota_4];

    var name_nota_5 = celda.eq(6).find("input").attr("name");
    var nota_5 = celda.eq(6).find("input").val();
    var n5 = [name_nota_5, nota_5];

    var name_nota_6 = celda.eq(7).find("input").attr("name");
    var nota_6 = celda.eq(7).find("input").val();
    var n6 = [name_nota_6, nota_6];

    var name_nota_7 = celda.eq(8).find("input").attr("name");
    var nota_7 = celda.eq(8).find("input").val();
    var n7 = [name_nota_7, nota_7];

    var name_nota_8 = celda.eq(9).find("input").attr("name");
    var nota_8 = celda.eq(9).find("input").val();
    var n8 = [name_nota_8, nota_8];

    var name_promedio_concepto = celda.eq(10).find("input").attr("name");

    var numero_concepto = $(".registro-secundaria-numero-concepto").val();

    var id_nota_alumno_secundaria = celda.find(".id_nota_alumno_secundaria").val();

    $.ajax({
        url: base_url + 'secundaria/guardar_notas',
        type: "post",
        data: {
            id_nota_alumno_secundaria: id_nota_alumno_secundaria,
            nota_1: n1,
            nota_2: n2,
            nota_3: n3,
            nota_4: n4,
            nota_5: n5,
            nota_6: n6,
            nota_7: n7,
            nota_8: n8,
            name_promedio_concepto: name_promedio_concepto,
            numero_concepto: numero_concepto
        }
    })
        .done(function (data) {
            switch (numero_concepto) {
                case "1":
                case "2":
                    celda.eq(10).find("input").val(data); //Seteamos el promedio
                    break;
                case "3":
                    celda.eq(6).find("input").val(data); //Seteamos el promedio
                    break;
                case "4":
                    celda.eq(4).find("input").val(data); //Seteamos el promedio
                    break;
            }
        })
        .fail(function (data) {
            console.log(data);
            //$(".response").html("Ha ocurrido un error interno");
        });
    return false;


});

/* -------------------- FIN MODULO DE NOTAS - SECUNDARIA  -------------------- */


/* -------------------- MODULO DE COMPORTAMIENTO -------------------- */

/* Script para armar los enlaces del comportamiento (10 meses), setearlos en el modal y poder ingresar a cada registro y calificar */
$(document).on('click', '.boton-comportamiento', function (e) {
    e.preventDefault();
    //Obtenemos data
    var id_grado = $(this).attr("data-idgrado");
    var id_anio_escolar = $(this).attr("data-idanioescolar");
    $.ajax({
        url: base_url + 'comportamiento/obtener_bimestres_escolares',
        type: "post",
        data: {
            id_grado: id_grado,
        }
    })
        .done(function (data) {
            var html = "";
            data.bimestres.forEach(element => {
                //Armamos html para setear en el modal
                html += "<a class='btn btn-outline-primary m-1' role='button' href='" + base_url + "comportamiento/calificar/" + id_grado + "/" + element.id_bimestre + "/" + id_anio_escolar + "'>" + element.nombre + "</a>";
            });
            //Seteamos html en el modal
            $(".contenido-calificar").html(html);
            //Seteamos nombre del grado en el modal
            $(".nombre-grado").html(data.nombre_grado + " " + data.seccion_grado);
        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;

});

/* Script para guardar las notas al cambiar de foco al input de notas */
$(document).on('blur', '.comportamiento', function () {
    var celda = $(this).parents("tr").find("td"); //Obtenemos celda dentro de la fila seleccionada

    var nota = celda.eq(2).find("input").val();
    var id_comportamiento = celda.find(".id_comportamiento").val();

    $.ajax({
        url: base_url + 'comportamiento/guardar_notas',
        type: "post",
        data: {
            id_comportamiento: id_comportamiento, nota: nota,
        }
    })
        .done(function (data) {
            //Guardado correctamente
        })
        .fail(function (data) {
            console.log(data);
        });
    return false;


});

/* -------------------- FIN MODULO DE ACTITUDINAL -------------------- */

/* -------------------- MODULO DE ASISTENCIA -------------------- */

/* Script para armar los enlaces del actitudinal (10 meses), setearlos en el modal y poder ingresar a cada registro y calificar */
$(document).on('click', '.boton-asistencia', function (e) {
    e.preventDefault();
    //Obtenemos data
    var id_grado = $(this).attr("data-idgrado");
    var id_anio_escolar = $(this).attr("data-idanioescolar");
    $.ajax({
        url: base_url + 'asistencia/obtener_meses_escolares',
        type: "post",
        data: {
            id_grado: id_grado,
        }
    })
        .done(function (data) {
            var html = "";
            data.meses_escolares.forEach(element => {
                //Armamos html para setear en el modal
                //html +="<a class='btn btn-outline-primary m-1' role='button' href='" + base_url + "asistencia/pasar_asistencia/" + id_grado + "/" + element.id_mes_escolar + "/" + id_anio_escolar + "'>" + element.nombre + "</a>";
                html += "<button class='btn btn-outline-primary m-1 asistencia-mes' data-idgrado='" + id_grado + "' data-idmesescolar='" + element.id_mes_escolar + "' data-idanioescolar='" + id_anio_escolar + "' role='button'>" + element.nombre + "</button>";
            });
            //Seteamos html en el modal
            $(".contenido-calificar").html(html);
            //Seteamos nombre del grado en el modal
            $(".nombre-grado").html(data.nombre_grado + " " + data.seccion_grado);
        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;

});

/* Script para armar los dias del mes enviado y setearlos en el modal */
$(document).on('click', '.asistencia-mes', function (e) {
    e.preventDefault();
    //Obtenemos data
    var id_grado = $(this).attr("data-idgrado");
    var id_mes_escolar = $(this).attr("data-idmesescolar");
    var id_anio_escolar = $(this).attr("data-idanioescolar");

    $.ajax({
        url: base_url + 'asistencia/obtener_dias_mes',
        type: "post",
        data: {
            id_mes_escolar: id_mes_escolar,
            id_anio_escolar: id_anio_escolar,
            id_grado: id_grado
        }
    })
        .done(function (data) {
            //Mostramos modal
            $('#modal_asistencia_mes').modal('show');
            //Seteamos valores
            $(".nombre-mes").html(data["mes"]);
            var html = "";
            html += "<div class='col-md-4'>";
            html += "<label class='font-weight-bold control-label'> Seleccione Día:</label>";
            html += "</div>";
            html += "<div class='col-md-8'>";
            html += "<input name='id_grado' type='hidden' class='id_grado' value='" + data["id_grado"] + "'>";
            html += "<input name='id_mes_escolar' type='hidden' class='id_mes_escolar' value='" + data["id_mes_escolar"] + "'>";
            html += "<input name='id_anio_escolar' type='hidden' class='id_anio_escolar' value='" + data["id_anio_escolar"] + "'>";
            html += data["combo_dias"];
            html += "</div>";
            $(".dias-mes").html(html);
            $('#numero_dia').selectpicker('refresh');
        })
        .fail(function (data) {
            console.log(data);
        });
    return false;
});

/* Script para cargar la asistencia del dia seleccionado */
$(document).on('click', '.boton-pasar-asistencia', function (e) {
    e.preventDefault();
    //Obtenemos data
    var id_grado = $(".id_grado").val();
    var id_mes_escolar = $(".id_mes_escolar").val();
    var id_anio_escolar = $(".id_anio_escolar").val();
    var numero_dia = $("#numero_dia").val();
    //Cargamos URL
    $(location).attr("href", base_url + 'asistencia/pasar_asistencia/' + id_grado + "/" + id_mes_escolar + "/" + id_anio_escolar + "/" + numero_dia);

});

/* Script para guardar asistencia */
$('.box_asistencia_alumno').change(function (e) {
    e.preventDefault();
    //Obtenemos datos
    var celda = $(this).parents("tr").find("td"); //Obtenemos celda dentro de la fila seleccionada
    var asistencia = $(this).val(); //Valor del select seleccionado
    var numero_dia = $(this).attr("name"); //Valor del select seleccionado
    var id_asistencia_alumno = celda.eq(2).find("input").val();//Valor del input(hidden)

    $.ajax({
        url: base_url + 'asistencia/guardar_asistencia',
        type: "post",
        data: {
            id_asistencia_alumno: id_asistencia_alumno,
            asistencia: asistencia,
            numero_dia: numero_dia
        }
    })
        .done(function (data) {
            console.log(data);
            //Seteamos valores
            celda.eq(3).html(data["total_asistencias"]);
            celda.eq(4).html(data["total_faltas"]);
            celda.eq(5).html(data["total_tardanzas"]);
            celda.eq(6).html(data["total_asisttotal_faltas_justificadasencias"]);
            celda.eq(7).html(data["total_tardanzas_justificadas"]);
        })
        .fail(function (data) {
            console.log(data);
        });
    return false;

});

/* -------------------- FIN MODULO DE ASISTENCIA -------------------- */

/* -------------------- MODULO DE APODERADO -------------------- */

//Script para filtrar los cursos segun el grado
$('.box-anioescolar').change(function () {
    var id_anio_escolar = $('#id_anio_escolar').val();
    $.post(
        base_url + 'apoderado/matriculados_anio_escolar', {
        id_anio_escolar: id_anio_escolar
    },
        function (response) {
            console.log(response);
            if (response.combo_matriculados == "") {
                $('.box-apoderado-matriculados').html('Sin alumnos matriculados');
                //$('#id_curso').selectpicker('refresh');
            } else {
                $('.box-apoderado-matriculados').html(response.combo_matriculados);
                $('#id_matricula').selectpicker('refresh');
            }
        }, 'json');

});

/* -------------------- FIN MODULO DE APODERADO -------------------- */


/* -------------------- MODULO DE MENSAJE -------------------- */

/* Script para ocultar comno de año escolar */
/*$(document).on('click', '.radio-tipo', function (e) {
    var tipo = $('input:radio[name=radio_enviar_por]:checked').val()
    if (tipo == "alumno") {
        //Mostramos combo de año escolar
        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh'); //Refrescamos combo
        $(".combo-anio-escolar").show();
    }

});*/

/* Script para enviar el tipo solo cuando es alumno, se envia el tipo y año escolar */
/*$(document).on('change', '.combo-anio-escolar', function (e) {
    e.preventDefault();
    //Obtenemos datos
    var tipo = $('input:radio[name=radio_enviar_por]:checked').val()
    var id_anio_escolar = $("#id_anio_escolar").val()
    $.ajax({
        url: base_url + 'mensaje/obtener_listas',
        type: "post",
        data: { tipo: tipo, id_anio_escolar: id_anio_escolar }
    })
        .done(function (data) {
            //Seteamos valores
            $(".combo-destinatarios").html(data);
            $('.selectpicker').selectpicker('refresh'); //Refrescamos combo
        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;

});*/

$(document).ready(function () {
    $('#formulario').submit(function (event) {
        var progreso = "";
        event.preventDefault();
        $(this).ajaxSubmit({
            uploadProgress: function (event, position, total, percentageComplete) {
                $('.progress').removeClass("d-none"); //Quitamos clase que oculta el spinner
                progreso = percentageComplete + '%';
                $(".progress-bar").css("width", progreso);
                $('.progress-bar').html(progreso, {
                    duration: 1000
                });
            },
            complete: function (data) {
                console.log("complete");
                console.log(data);
                $(".response").html(data.responseText);
            }
        });
        return false;
    });
});


/* Script para setear el combo en el valor vacio*/
$(document).on('change', '.radio-tipo', function () {
    //Refrescamos combo
    $('.selectpicker').prop('selectedIndex', 0);
    $('.selectpicker').selectpicker('refresh');

});

/* Script para enviar el tipo sea grado o nivel educativo */
$(document).on('change', '.combo-anio-escolar', function (e) {
    e.preventDefault();
    //Obtenemos datos
    var tipo = $('input:radio[name=radio_enviar_por]:checked').val()
    var id_anio_escolar = $("#id_anio_escolar").val()

    $.ajax({
        url: base_url + 'mensaje/obtener_listas',
        type: "post",
        data: { tipo: tipo, id_anio_escolar: id_anio_escolar }
    })
        .done(function (data) {
            $(".combo-destinatarios").html(data);
            $('.selectpicker').selectpicker('refresh'); //Refrescamos combo
        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;

});

/* Script para agregar todo lo seleccionado del modal al campo "para" */
$(document).on('click', '.boton-agregar', function (e) {
    //Recibimos datos
    var tipo = $('input:radio[name=radio_enviar_por]:checked').val()
    var id_anio_escolar = $("#id_anio_escolar").val()
    var destinatarios = "";
    switch (tipo) {
        case "alumno":
            destinatarios = $("#id_matricula").val()
            break;
        case "grado":
            destinatarios = $("#id_grado").val()
            break;
        case "nivel_educativo":
            destinatarios = $("#id_nivel_educativo").val()
            break;
    }


    $.ajax({
        url: base_url + 'mensaje/obtener_destinatarios',
        type: "post",
        data: { tipo: tipo, destinatarios: destinatarios }
    })
        .done(function (data) {
            var elt = $('.mensaje-destinatario');
            elt.tagsinput({
                allowDuplicates: false,
                tagClass: "badge badge-primary h6",
                itemValue: 'id',
                itemText: 'text',
                /*itemValue: function (item) {
                    return item.id;
                }*/
            });
            if (data == null) { //Si la data de retorno es vacio
                elt.tagsinput('removeAll');
            }
            else {
                var array_nombres = [];
                elt.tagsinput('removeAll');
                data.forEach(element => {
                    array_nombres.push(element.nombre.toString());
                    elt.tagsinput('add', { "id": element.id.toString(), "text": element.nombre.toString() });
                });
                //Seteamos valores en input hidden            
                $(".tipo").val(tipo);
                $(".id-anio-escolar").val(id_anio_escolar);
                console.log(array_nombres);
                $(".nombres-destinatarios").val(array_nombres);
            }
        })
        .fail(function (data) {
            console.log(data);
            $(".response").html("Ha ocurrido un error interno");
        });
    return false;
});

/* Script para actualizar la ruta del archivo adjunto - Solo visualizacion */
$('.adjunto').on('change', function (e) {
    //Obtenemos el nombre del archivo    
    var nombre_adjunto = e.target.files[0].name;
    //Reemplazamos el nombre en el label
    $(this).next('.label-adjunto').html(nombre_adjunto);
})

/* Seteamos los radio de Configuracion en NO */
$(document).ready(function () {
   let tipo = $(".tipo").val();
   if(tipo != "editar") {
    $(".radio-deshabilitado").prop("checked", true);
   }

});

$('.ver-adjunto-conf').on('click', function (e) {
    let documento_adjunto = $(this).attr("data-doc"); // URL del documento adjunto
    let nombre_adjunto = $(this).attr("data-nombre"); // Nombre de la etiqueta documento adjunto
    console.log(documento_adjunto, nombre_adjunto);

    $(".titulo-adjunto").text(nombre_adjunto);

    let html = `
               <object class="adjunto-object" data="${documento_adjunto}" width="600" height="600">
                  <label class="font-weight-bold h5">El archivo se ha descargado automaticamente</label>
                  <embed class="adjunto-embed" src="${documento_adjunto}" type="application/pdf" width="600" height="600">
               </object>
            `
    $(".archivo-adjunto-conf").html(html);
    
})

/* Script para actualizar url del combo año escolar y mostrar los mensajes segun el año escolar seteado */
$(document).on('change', '#combo_mensaje_anio_escolar', function (e) {

    var id_anio_escolar = $(this).val();

    if (id_anio_escolar == "") {
        var enlace = base_url + "mensaje/listar";
    }
    else {
        var enlace = base_url + "mensaje/filtro/" + id_anio_escolar;
    }
    $(".boton-filtro-mensaje-anioeascolar").attr("href", enlace);
});

/* Modulo para mostrar la lista de leidos */
$(document).on('click', '.boton-vistos', function (e) {

    var id_mensaje = $(this).attr("data-idmensaje"); // url a la cual enviara el id para eliminar

    $.ajax({
        url: base_url + 'mensaje/mostrar_leidos',
        type: "post",
        data: {
            id_mensaje: id_mensaje
        }
    })
        .done(function (data) {
            var html = "";
            html += "<ul class='nav nav-pills' role='tablist'>";
            html += "<li class='nav-item'>";
            html += "<a class='nav-link active' data-toggle='pill' href='#nav-leidos'>Leidos</a>";
            html += "</li>";
            html += "<li class='nav-item'>";
            html += "<a class='nav-link' data-toggle='pill' href='#nav-noleidos'>No Leidos</a>";
            html += "</li>";
            html += "</ul>";

            html += "<div class='tab-content'>";
            //TAB-LEIDOS
            html += "<div class='container tab-pane active' id='nav-leidos'>";
            html += "<table class='table table-striped table-hover'>";
            html += "<thead>";
            html += "<tr>";
            html += "<th>Alumno<th>";
            html += "<th>Fecha y Hora<th>";
            html += "</tr>";
            html += "</thead>";
            data.leidos.forEach(element => {
                html += "<tr>";
                html += "<td>" + element.alumno + "<td>";
                html += "<td>" + element.fecha_leido + "<td>";
                html += "</tr>";
            });
            html += "</table>";
            html += "</div>"; //tab-leidos

            //TAB-NO-LEIDOS
            html += "<div class='container tab-pane fade' id='nav-noleidos'>";
            html += "<table class='table table-striped table-hover'>";
            html += "<thead>";
            html += "<tr>";
            html += "<th>Alumno<th>";
            html += "</tr>";
            html += "</thead>";
            data.no_leidos.forEach(element2 => {
                html += "<tr>";
                html += "<td>" + element2.alumno + "<td>";
                html += "</tr>";
            });
            html += "</table>";
            html += "</div>"; //tab-no-leidos

            html += "</div>"; //tab-content
            $(".lista-leidos").html(html);

        })

});
/* -------------------- FIN MODULO DE MENSAJE -------------------- */

/* -------------------- MODULO DE REPORTE -------------------- */
/* Script para mostrar opciones para exportar segun alumno, grado o nivel educativo */
$('.radio-exportar').change(function () {
    var exportar_por = $('input:radio[name=radio_exportar_por]:checked').val();
    var id_anio_escolar = $('#id_anio_escolar').val();

    $.post(base_url + 'reporte/filtro', {
        exportar_por: exportar_por,
        id_anio_escolar: id_anio_escolar
    },
        function (data) {
            $(".combo-filtro").html(data);
            $('.selectpicker').selectpicker('refresh'); //Refrescamos combo
        }, 'json');

});

/* Script para mostrar opciones para exportar segun alumno, grado o nivel educativo - Reporte de notas */

$(document).on('change', '.radio-exportar-nota', function () {
    var exportar_por = $('input:radio[name=radio_notas_exportar_por]:checked').val();
    var id_anio_escolar = $('#id_anio_escolar_nota').val();

    $.post(base_url + 'reporte/filtro_reporte_notas', {
        exportar_por: exportar_por,
        id_anio_escolar: id_anio_escolar,
    },
        function (data) {
            $(".combo-filtro-alumno-notas").html(data);
            $('.selectpicker').selectpicker('refresh'); //Refrescamos combo
        }, 'json');
});

/* SCRIPT PARA LIMPIAR EL CHECKBOX */
$(document).on('change', '#id_anio_escolar_nota', function () {
    $(".radio-exportar-nota").prop('checked', false);
    $(".combo-filtro-alumno-notas").html("");
});

/* -------------------- FIN MODULO DE REPORTE -------------------- */


/* VALIDAMOS QUE NO SE INGRESEN NOTAS INCORRECTAS */
$(document).on('keyup blur', '.nota-primaria, .nota-secundaria', function () {
    var nota = $(this).val();

    if ((nota < 0 || nota > 20 || !$.isNumeric(nota)) && nota != "") {
        alert("La nota ingresada es incorrecta.");
        $(this).val("");
        return false;
    }

});


/* ------------------------------------------ */
/* PREDICCION                                 */
/* ------------------------------------------ */

/* Script que se ejecuta al enviar formulario de Prediccion */
$('.formularioPrediccion').on('submit', function (e) {
    e.preventDefault(); //Para evitar doble proceso

    $("#seccionResultadoPrediccion").addClass("d-none"); //Ocultamos seccion de resultados

    var formulario = $(this); //Obtenemos el formulario completo
    var url = formulario.attr('action'); //obtenemos la url de envio
    var metodo = formulario.attr('method'); //Obtenemos el metodo de envio (POST o GET)        
    var data = formulario.serialize(); //Convertimos los datos en un arraylist
    let recomendacionesHtml = "";
    let colorNivel = "badge-secondary";
    //alert(data);
    $.ajax({
        url: url,
        type: metodo,
        data: data,
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('.fa-spinner').css('display', 'inline').delay(2000);
        }
    })
        .done(function (data) {
            formulario.find('.response').html(data).hide().slideDown();

            switch (data.respuesta.nivel) {
                case "bajo":
                    colorNivel = "badge badge-pill badge-warning";
                    break;

                    case "medio":
                    colorNivel = "badge badge-pill badge-info";
                    break;

                    case "alto":
                    colorNivel = "badge badge-pill badge-success";
                    break;
            
                default:
                    break;
            }
            
            let nivel = data.respuesta.nivel.toUpperCase();
            let nivelHtml = `<h5>La tendencia del rendimiento académico es: <span class="${colorNivel}">${nivel}</span></h5>`;

            let promedioNotasIngresadas = data.promedios.promedio_notas_ingresadas;
            let promedioNotasHistoricas = data.promedios.promedio_notas_historicas;
            let promedioPsicologia = data.promedios.promedio_psicologia;
            let promedioAsistencia = data.promedios.promedio_asistencia;

            let htmlNotas = `<div class="alert alert-warning font-weight-bolder" role="alert">
                                <h4 class="alert-heading">Se han evaluado los siguientes conceptos:</h4>
                                <hr>
                                <p class= "mb-1"> Promedio de Notas Ingresadas: ${promedioNotasIngresadas} </p>
                                <p class= "mb-1"> Promedio de Notas Históricas: ${promedioNotasHistoricas} </p>
                                <p class= "mb-1"> Promedio de Psicología: ${promedioPsicologia} </p>
                                <p class= "mb-1"> Promedio de Asistencia Histórica: ${promedioAsistencia} </p>
                            </div>`;

            let recomendaciones = data.respuesta.recomendaciones;
            let i = 1;
            recomendaciones.forEach(function(recomendacion) {
                recomendacionesHtml += `<h5>
                                            <i class="fa fa-check-square text-primary" aria-hidden="true"></i>
                                            Recomendación ${i}: <span class="${colorNivel}"> ${recomendacion} </span>
                                        </h5>`
                i++;
            });

            $("#mensajeEvaluacion").html(htmlNotas);
            
            $("#nivelRendimientoAcademico").html(nivelHtml);
            
            $("#recomendaciones").html(recomendacionesHtml);

            $("#seccionResultadoPrediccion").removeClass("d-none");

            
        })
        .fail(function (data) { //Cuando hay error crítico
            formulario.find('.response').html(data.responseText).hide().slideDown();
        })
        .always(function () { //Siempre se ejecuta asi sea true o false                
            $('.fa-spinner').hide();
        });
    return false;
});

/* Script para guardar las notas al cambiar de foco al input de notas - PSICOLOGIA */
$(document).on('blur', '.psicologia', function () {
    var celda = $(this).parents("tr").find("td"); //Obtenemos celda dentro de la fila seleccionada

    var nota = celda.eq(2).find("input").val();
    var id_psicologia = celda.find(".id_psicologia").val();

    $.ajax({
        url: base_url + 'psicologia/guardar_evaluacion',
        type: "post",
        data: {
            id_psicologia: id_psicologia, nota: nota,
        }
    })
        .done(function (data) {
            //Guardado correctamente
        })
        .fail(function (data) {
            console.log(data);
        });
    return false;


});

/* -------------------- MODULO DE ALERTA ACADEMICA -------------------- */

/* Recarga el listado de cursos al cambiar el año escolar */
$(document).on('change', "#id_anio_escolar_alerta", function (e) {
    let id_anio_escolar = $(this).val();
    window.location.href = base_url + `alerta_academica/listar/${id_anio_escolar}`;
});

/* Pinta la lista de factores explicables (data.resultados[i].factores_detalle, ya armada por
   predecir_curso.py con el filtro de doble condicion (SHAP relevante + valor real objetivamente
   malo/bueno frente a la mediana historica), la validacion de coherencia pedagogica (una nota
   A/AD nunca se muestra como factor desfavorable) y el filtro de relevancia de presentacion:
   cada elemento trae icono y frase (ej. "Tuvo 5 faltas en el bimestre anterior, más que la
   mayoría de sus compañeros."), ya ordenados de mayor a menor relevancia -- SIN porcentajes
   SHAP ni etiquetas de posicion, que se usan solo internamente para ordenar y filtrar.
   mensajeVacio: texto ya calculado en Python (data.resultados[i].factores_explicables),
   varia segun el modo (riesgo o protector) -- no se hardcodea aqui para no duplicar esa logica. */
function renderizarFactoresAlertaAcademica(detalle, mensajeVacio) {
    if (!detalle || detalle.length === 0) {
        return '<div class="card-body py-2">' + mensajeVacio + '</div>';
    }

    var html = '<ul class="list-group list-group-flush">';
    $.each(detalle, function (i, f) {
        html += '<li class="list-group-item d-flex align-items-start">';
        html += '<i class="fas ' + f.icono + ' fa-fw text-primary mr-2 aa-factor-icono"></i>';
        html += '<span>' + f.frase + '</span>';
        html += '</li>';
    });
    html += '</ul>';
    return html;
}

/* "Bimestre a predecir" (2026-11-11): solo se puede predecir el bimestre en curso del colegio (el
   siguiente al ultimo con la mayoria de notas registradas; lo calcula listar() en PHP y generar()
   rechaza cualquier otro). Las ventanas de generacion lo muestran con los demas deshabilitados. */
function bimestreAlertaAcademica() {
    var b = parseInt($('#bimestre_en_curso_alerta').attr('data-bimestre'), 10);
    return b >= 2 && b <= 4 ? b : '';
}

/* Aviso cuando un curso no tiene a ningun alumno con notas del bimestre anterior (no se genera nada) */
function motivoCursoSinPrediccionAlertaAcademica(data) {
    var total = data.total_alumnos_excluidos || 0;
    var anterior = data.bimestre_solicitado ? textoBimestreAlertaAcademica(data.bimestre_solicitado - 1) : 'bimestre anterior';
    if (total === 0) {
        return 'No hay alumnos con notas registradas en este curso.';
    }
    return 'Faltan registrar o promediar las notas del ' + anterior + ' de este curso (ninguno de los ' + total +
        ' alumnos tiene nota de ese bimestre).';
}

function textoBimestreAlertaAcademica(b) {
    return {2: '2.º bimestre', 3: '3.er bimestre', 4: '4.º bimestre', 1: '1.er bimestre'}[parseInt(b, 10)] || '';
}

/* Clave de sessionStorage con el ultimo resultado de curso mostrado en el modal: permite volver
   desde el tablero de Power BI al aula ya generada sin ejecutar otra vez el modelo */
var CLAVE_ULTIMO_RESULTADO_ALERTA = 'alerta_academica_ultimo_resultado';

function escaparHtmlAlertaAcademica(texto) {
    return $('<div>').text(texto === null || texto === undefined ? '' : texto).html();
}

/* Texto para comparar en "Buscar alumno": minusculas y sin tildes ("Pérez" se encuentra con "perez") */
function textoBusquedaAlertaAcademica(texto) {
    var t = String(texto || '').toLowerCase();
    return t.normalize ? t.normalize('NFD').replace(/[\u0300-\u036f]/g, '') : t;
}

/* Marca (data-cumple) las filas de alumno que cumplen el filtro y las renumera; las filas de
   factores abiertas se cierran. Que filas se VEN lo decide luego la paginacion. Devuelve cuantas
   filas cumplen el filtro. El Excel exporta todas las que cumplen (no solo la pagina a la vista). */
function filtrarFilasAlertaAcademica(cuerpo, cumple) {
    var visibles = 0;
    cuerpo.children('tr').each(function () {
        var fila = $(this);
        if (fila.hasClass('fila-factores')) {
            fila.hide();
            return;
        }
        var mostrar = cumple(fila);
        fila.attr('data-cumple', mostrar ? '1' : '0');
        fila.find('.boton-ver-factores').html('<i class="fas fa-chevron-down mr-1"></i>Ver factores');
        if (mostrar) {
            visibles++;
            fila.children('td').first().text(visibles);
        }
    });
    return visibles;
}

/* ---------- Paginacion de las tablas de resultados (2026-11-11) ----------
   Maximo FILAS_POR_PAGINA_ALERTA filas por pagina, con la paginacion de Bootstrap que usan las
   tablas DataTables de la intranet (Anterior / numeros / Siguiente). Devuelve {desde, hasta}. */
var FILAS_POR_PAGINA_ALERTA = 15;

function paginarAlertaAcademica(cuerpo, contenedor, pagina) {
    var filas = cuerpo.children('tr').not('.fila-factores');
    var cumplen = filas.filter('[data-cumple="1"]');
    var total = cumplen.length;
    var paginas = Math.max(1, Math.ceil(total / FILAS_POR_PAGINA_ALERTA));
    pagina = Math.min(Math.max(1, pagina || 1), paginas);
    cuerpo.data('pagina', pagina);

    var desde = (pagina - 1) * FILAS_POR_PAGINA_ALERTA;
    var hasta = Math.min(desde + FILAS_POR_PAGINA_ALERTA, total);
    filas.hide();
    cuerpo.children('tr.fila-factores').hide();
    cuerpo.find('.boton-ver-factores').html('<i class="fas fa-chevron-down mr-1"></i>Ver factores');
    cumplen.slice(desde, hasta).show();

    var html = '';
    if (paginas > 1) {
        function item(texto, destino, activo, deshabilitado) {
            return '<li class="page-item' + (activo ? ' active' : '') + (deshabilitado ? ' disabled' : '') + '">' +
                '<a class="page-link pagina-alerta-academica" href="#" data-pagina="' + destino + '">' + texto + '</a></li>';
        }
        html += '<nav><ul class="pagination pagination-sm justify-content-end mb-2">';
        html += item('Anterior', pagina - 1, false, pagina === 1);
        var ultimo_mostrado = 0;
        for (var p = 1; p <= paginas; p++) {
            if (p === 1 || p === paginas || Math.abs(p - pagina) <= 2) {
                if (ultimo_mostrado && p - ultimo_mostrado > 1) {
                    html += '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
                html += item(p, p, p === pagina, false);
                ultimo_mostrado = p;
            }
        }
        html += item('Siguiente', pagina + 1, false, pagina === paginas);
        html += '</ul></nav>';
    }
    contenedor.html(html).attr('data-cuerpo', cuerpo.hasClass('cuerpo-lote-alerta-academica') ? 'lote' : 'curso');
    return {desde: total === 0 ? 0 : desde + 1, hasta: hasta, total: total};
}

$(document).on('click', '.pagina-alerta-academica', function (e) {
    e.preventDefault();
    if ($(this).closest('.page-item').hasClass('disabled')) {
        return;
    }
    var contenedor = $(this).closest('[data-cuerpo]');
    var pagina = parseInt($(this).attr('data-pagina'), 10);
    if (contenedor.attr('data-cuerpo') === 'lote') {
        actualizarConteoLoteAlertaAcademica(paginarAlertaAcademica($('.cuerpo-lote-alerta-academica'), contenedor, pagina));
    } else {
        actualizarConteoCursoAlertaAcademica(paginarAlertaAcademica($('.cuerpo-resultado-alerta-academica'), contenedor, pagina));
    }
});

/* Arma la fila de un alumno y su fila oculta de factores ("Ver factores"), igual en el modal de
   un curso y en la lista institucional. opciones.celdasExtra: celdas despues del nombre (grado y
   curso en la lista institucional); opciones.colspan: columnas de la fila de factores;
   opciones.botonesExtra: boton de Power BI; opciones.atributosFila: indice para los filtros */
function filasAlumnoAlertaAcademica(r, numero, opciones) {
    var ctx = r.contexto_bimestre_anterior;
    var promedio = ctx.promedio_final;
    var probabilidadPct = Math.round(r.probabilidad_riesgo * 100);
    //Asistencia en dias ("23 de 35 días"), no en porcentaje: mas facil de leer para el docente
    var asistenciaDias = (ctx.asistencia_dias !== null && ctx.asistencia_dias_lectivos !== null)
        ? Math.round(ctx.asistencia_dias) + ' de ' + Math.round(ctx.asistencia_dias_lectivos) + ' días'
        : '-';

    var filas = '<tr' + opciones.atributosFila + ' data-nombre="' + escaparHtmlAlertaAcademica(textoBusquedaAlertaAcademica(r.nombre_alumno)) +
        '" data-nivel="' + escaparHtmlAlertaAcademica(r.clasificacion) + '">';
    filas += '<td>' + numero + '</td>';

    filas += '<td class="aa-alumno">' + r.nombre_alumno + '</td>';
    filas += opciones.celdasExtra;
    //Tono de la barra de probabilidad segun el nivel de alerta (badge-danger -> aa-barra-danger)
    var tono = String(r.clase_alerta).replace('badge badge-', '');
    filas += '<td><span class="' + r.clase_alerta + '">' + r.etiqueta_alerta + '</span> <span class="aa-prob">' + probabilidadPct + '%</span>';
    filas += '<div class="aa-barra"><span class="aa-barra-' + tono + '" style="width:' + probabilidadPct + '%;"></span></div></td>';
    filas += '<td>' + (promedio !== null ? promedio + '/20' : '-') + '</td>';
    filas += '<td>' + asistenciaDias + '</td>';
    filas += '<td class="aa-acciones"><button type="button" class="btn btn-primary btn-sm boton-ver-factores"><i class="fas fa-chevron-down mr-1"></i>Ver factores</button>' + opciones.botonesExtra + '</td>';
    filas += '</tr>';
    filas += '<tr class="fila-factores"' + opciones.atributosFila + ' style="display:none;">';
    filas += '<td></td>';
    filas += '<td colspan="' + opciones.colspan + '">';
    filas += '<div class="card aa-factores">';
    filas += '<div class="card-header py-2 d-flex justify-content-between align-items-center flex-wrap">';
    filas += '<strong>' + r.titulo_factores + '</strong>';
    filas += '</div>';
    filas += renderizarFactoresAlertaAcademica(r.factores_detalle, r.factores_explicables);
    filas += '</div>';
    filas += '</td>';
    filas += '</tr>';
    return filas;
}

/* Tarjetas de conteo al inicio del resultado (un curso y lote): mismas tarjetas de la intranet
   (card + card-header de color en negrita, como "Mostrar por:"), cada una con el color de su nivel
   de alerta (rojo / amarillo / verde de Bootstrap). Solo el numero de alertas de cada nivel, sin
   porcentaje ni total (decision de la autora 2026-11-11). De mayor a menor alerta.
   Para que se entienda de que es la alerta (decision de la autora 2026-11-12, sin mencionar las
   letras B/C): una frase corta dentro de cada tarjeta y debajo una "Nota:" que explica que son
   alertas de rendimiento por debajo de lo esperado (sin titulo arriba: la autora lo descarto).
   varios_cursos = resultado de un lote. */
function tarjetasResumenAlertaAcademica(alta, moderada, sin_alerta, bimestre, varios_cursos) {
    function tarjeta(color, texto_encabezado, etiqueta, numero, color_numero, descripcion) {
        return '<div class="col-md-4 mb-2"><div class="card border-' + color + ' h-100">' +
            '<div class="card-header font-weight-bold bg-' + color + ' ' + texto_encabezado + ' py-1">' + etiqueta + '</div>' +
            '<div class="card-body p-2 text-center">' +
            '<span class="h3 font-weight-bold mb-0 ' + color_numero + '">' + numero + '</span>' +
            '<div class="small">' + descripcion + '</div>' +
            '</div></div></div>';
    }
    var texto_bimestre = textoBimestreAlertaAcademica(bimestre);
    return '<div class="row mb-1">' +
        tarjeta('danger', 'text-white', 'Alerta alta', alta, 'text-danger', 'Alta probabilidad de nota por debajo de lo esperado') +
        tarjeta('warning', 'text-dark', 'Alerta moderada', moderada, 'text-dark', 'Probabilidad media') +
        tarjeta('success', 'text-white', 'Sin alerta', sin_alerta, 'text-success', 'Baja probabilidad') +
        '</div>' +
        '<p class="mb-2"><strong>Nota:</strong> las alertas son alertas de rendimiento por debajo de lo esperado en el ' +
        (texto_bimestre || 'próximo bimestre') + (varios_cursos ? ' de cada curso.' : ' de este curso.') + '</p>';
}

/* "Ver detalle": boton pequeno con borde azul (btn-outline-primary, el mismo de los botones de
   bimestre de la intranet) que despliega (collapse de Bootstrap) el contenido dado */
var contadorDetalleAlertaAcademica = 0;
function detallePlegableAlertaAcademica(contenido_html) {
    var id = 'detalle_alerta_academica_' + (++contadorDetalleAlertaAcademica);
    return ' <button type="button" class="btn btn-outline-primary btn-sm py-0 ml-1 boton-detalle-alerta" data-toggle="collapse" data-target="#' + id +
        '" aria-expanded="false">Ver detalle</button>' +
        '<div class="collapse mt-2" id="' + id + '">' + contenido_html + '</div>';
}

$(document).on('show.bs.collapse hide.bs.collapse', '[id^="detalle_alerta_academica_"]', function (e) {
    $('.boton-detalle-alerta[data-target="#' + this.id + '"]').text(e.type === 'show' ? 'Ocultar detalle' : 'Ver detalle');
});

/* Pinta en el modal el resultado de un curso (tarjetas de conteo, tabla de alumnos, excluidos) */
function pintarResultadoAlertaAcademica(data) {
    var id_curso = parseInt(data.id_curso, 10);

    $('.resumen-curso-alerta-academica').html(tarjetasResumenAlertaAcademica(
        data.resumen_alertas.alerta_alta, data.resumen_alertas.alerta_moderada, data.resumen_alertas.sin_alerta,
        data.bimestre_solicitado || (data.resultados.length ? data.resultados[0].bimestre_objetivo : 0), false));

    var filas = '';
    $.each(data.resultados, function (i, r) {
        filas += filasAlumnoAlertaAcademica(r, i + 1, {
            celdasExtra: '',
            colspan: 5,
            atributosFila: ' data-indice-curso="' + i + '"',
            botonesExtra: ''
        });
    });
    $('.cuerpo-resultado-alerta-academica').html(filas);
    resultadoCursoAlertaAcademica = data;
    $('.boton-excel-curso-alerta').show();
    $('.filtro-curso-nivel-alerta').val('');
    $('.buscar-curso-alerta').val('');
    aplicarFiltrosCursoAlertaAcademica();

    var html_excluidos = '';
    if (data.alumnos_excluidos && data.alumnos_excluidos.length > 0) {
        if (data.bimestre_solicitado) {
            var nombres = $.map(data.alumnos_excluidos, function (ex) {
                return ex.nombre_alumno ? escaparHtmlAlertaAcademica(ex.nombre_alumno) : null;
            });
            html_excluidos = '<div class="alert alert-secondary mt-3">' + data.alumnos_excluidos.length + ' alumno(s) de este curso no tienen predicción del ' +
                textoBimestreAlertaAcademica(data.bimestre_solicitado) + ' porque no tienen notas registradas del ' +
                textoBimestreAlertaAcademica(data.bimestre_solicitado - 1) + '.' +
                (nombres.length > 0 ? detallePlegableAlertaAcademica('<small>' + nombres.join('<br>') + '</small>') : '') + '</div>';
        } else {
            html_excluidos = '<div class="alert alert-secondary mt-3">' + data.alumnos_excluidos.length + ' alumno(s) de este curso no tienen predicción para este bimestre (no cuentan con el bimestre anterior cerrado, o el año escolar ya finalizó para ellos).</div>';
        }
    }
    $('.alumnos-excluidos-alerta-academica').html(html_excluidos);

    $('.boton-tablero-curso-alerta').attr('data-idcurso', id_curso).show();
    $('.cargando-resultado-alerta-academica').hide();
    $('.contenido-resultado-alerta-academica').show();
}

function aplicarFiltrosCursoAlertaAcademica() {
    var nivel = $('.filtro-curso-nivel-alerta').val();
    var texto = textoBusquedaAlertaAcademica($.trim($('.buscar-curso-alerta').val()));
    var cuerpo = $('.cuerpo-resultado-alerta-academica');
    filtrarFilasAlertaAcademica(cuerpo, function (fila) {
        return (!nivel || fila.attr('data-nivel') === nivel)
            && (!texto || fila.attr('data-nombre').indexOf(texto) >= 0);
    });
    actualizarConteoCursoAlertaAcademica(paginarAlertaAcademica(cuerpo, $('.paginacion-curso-alerta-academica'), 1));
}

/* "Mostrando 1 a 15 de 25 alumnos" (como DataTables); si hay filtro, aclara de cuantos */
function actualizarConteoCursoAlertaAcademica(rango) {
    var total = $('.cuerpo-resultado-alerta-academica').children('tr:not(.fila-factores)').length;
    $('.conteo-curso-alerta-academica').text('Mostrando ' + rango.desde + ' a ' + rango.hasta + ' de ' + rango.total +
        (rango.total === 1 ? ' alumno' : ' alumnos') + (rango.total < total ? ' (filtrados de ' + total + ')' : ''));
}

$(document).on('change', '.filtro-curso-nivel-alerta', aplicarFiltrosCursoAlertaAcademica);
$(document).on('input', '.buscar-curso-alerta', aplicarFiltrosCursoAlertaAcademica);

/* ---------- Exportar Excel (mismo boton "Exportar Excel" de DataTables que las demas tablas) ----------
   Se exportan los alumnos que estan a la vista (respeta los filtros y la busqueda), con los factores
   en texto. La tabla temporal se arma con los datos (no con la pantalla) y se quita al terminar. */
var resultadoCursoAlertaAcademica = null;

function textoFactoresExcelAlertaAcademica(r) {
    if (r.factores_detalle && r.factores_detalle.length > 0) {
        return $.map(r.factores_detalle, function (f) {
            return f.frase;
        }).join(' ');
    }
    return r.factores_explicables || '';
}

function exportarExcelAlertaAcademica(resultados, conGradoCurso, titulo) {
    if (resultados.length === 0) {
        return;
    }
    var encabezados = ['N°', 'Alumno'].concat(conGradoCurso ? ['Grado', 'Curso'] : [])
        .concat(['Alerta de rendimiento por debajo de lo esperado', 'Probabilidad', 'Promedio bimestre anterior', 'Asistencia bimestre anterior', 'Principales factores']);
    var tabla = $('<table id="tabla_excel_alerta_academica">');
    var encabezado = $('<tr>');
    $.each(encabezados, function (i, e) {
        encabezado.append($('<th>').text(e));
    });
    tabla.append($('<thead>').append(encabezado));
    var cuerpo = $('<tbody>');
    $.each(resultados, function (i, r) {
        var ctx = r.contexto_bimestre_anterior || {};
        var celdas = [i + 1, r.nombre_alumno];
        if (conGradoCurso) {
            celdas.push(r.nombre_grado_lote, r.nombre_curso_lote);
        }
        celdas.push(
            r.etiqueta_alerta,
            Math.round(r.probabilidad_riesgo * 100) + '%',
            ctx.promedio_final !== null && ctx.promedio_final !== undefined ? ctx.promedio_final + '/20' : '-',
            (ctx.asistencia_dias !== null && ctx.asistencia_dias !== undefined && ctx.asistencia_dias_lectivos !== null && ctx.asistencia_dias_lectivos !== undefined)
                ? Math.round(ctx.asistencia_dias) + ' de ' + Math.round(ctx.asistencia_dias_lectivos) + ' días' : '-',
            textoFactoresExcelAlertaAcademica(r)
        );
        var fila = $('<tr>');
        $.each(celdas, function (j, c) {
            fila.append($('<td>').text(c === null || c === undefined ? '' : c));
        });
        cuerpo.append(fila);
    });
    tabla.append(cuerpo);

    var contenedor = $('<div style="display:none;">').append(tabla).appendTo('body');
    var dt = tabla.DataTable({paging: false, ordering: false, searching: false, info: false});
    new $.fn.dataTable.Buttons(dt, {
        buttons: [{extend: 'excelHtml5', title: titulo, filename: titulo}]
    });
    dt.button(0).trigger();
    dt.destroy();
    contenedor.remove();
}

/* Alumnos a la vista en una tabla de resultados, en el orden en que se muestran */
function resultadosVisiblesAlertaAcademica(cuerpo, atributo, lista) {
    var visibles = [];
    cuerpo.children('tr[' + atributo + ']').not('.fila-factores').each(function () {
        //Todas las que cumplen el filtro, esten o no en la pagina a la vista
        if ($(this).attr('data-cumple') !== '0') {
            visibles.push(lista[parseInt($(this).attr(atributo), 10)]);
        }
    });
    return visibles;
}

$(document).on('click', '.boton-excel-curso-alerta', function () {
    if (!resultadoCursoAlertaAcademica) {
        return;
    }
    var titulo = 'Alerta académica - ' + $('.nombre-curso-resultado').text() + ' - ' + $('.grado-curso-resultado').text();
    exportarExcelAlertaAcademica(
        resultadosVisiblesAlertaAcademica($('.cuerpo-resultado-alerta-academica'), 'data-indice-curso', resultadoCursoAlertaAcademica.resultados),
        false, titulo);
});

$(document).on('click', '.boton-excel-lote-alerta', function () {
    var titulo = 'Alerta académica - ' + $('.titulo-lote-alerta-academica').text().replace(/^Predicción de /, '');
    exportarExcelAlertaAcademica(
        resultadosVisiblesAlertaAcademica($('.cuerpo-lote-alerta-academica'), 'data-indice-lote', loteAlertaAcademica.resultados),
        true, titulo);
});

/* sessionStorage puede no estar disponible (navegacion privada, bloqueado): nunca rompe la pagina */
function guardarUltimoResultadoAlertaAcademica(id_curso, nombre_curso, data) {
    try {
        sessionStorage.setItem(CLAVE_ULTIMO_RESULTADO_ALERTA, JSON.stringify({
            id_curso: parseInt(id_curso, 10),
            nombre_curso: nombre_curso,
            data: data
        }));
    } catch (e) {
    }
}

function leerUltimoResultadoAlertaAcademica(id_curso) {
    try {
        var guardado = JSON.parse(sessionStorage.getItem(CLAVE_ULTIMO_RESULTADO_ALERTA));
        if (guardado && guardado.id_curso === parseInt(id_curso, 10) && guardado.data) {
            return guardado;
        }
    } catch (e) {
    }
    return null;
}

/* Titulo del modal: "ALGEBRA - 3er Año" -> curso en tipo oracion ("Álgebra") y el grado aparte,
   como etiqueta. Los nombres de curso vienen en mayusculas desde la base. */
function ponerTituloResultadoAlertaAcademica(nombre, bimestre) {
    var texto_bimestre = textoBimestreAlertaAcademica(bimestre);
    $('.bimestre-curso-resultado').text(texto_bimestre).toggle(texto_bimestre !== '');
    var texto = String(nombre || '');
    var corte = texto.lastIndexOf(' - ');
    var curso = corte >= 0 ? texto.substring(0, corte) : texto;
    var grado = corte >= 0 ? texto.substring(corte + 3) : '';
    var minusculas = ['de', 'del', 'la', 'las', 'el', 'los', 'y', 'e', 'en'];
    curso = curso.toLowerCase().replace(/[^\s\/]+/g, function (palabra, posicion) {
        if (posicion > 0 && $.inArray(palabra, minusculas) >= 0) {
            return palabra;
        }
        return palabra.charAt(0).toUpperCase() + palabra.slice(1);
    });
    $('.nombre-curso-resultado').text(curso);
    $('.grado-curso-resultado').text(grado).toggle(grado !== '');
}

/* Ejecuta el modelo para un curso y muestra el resultado en el modal (boton puede ser null) */
function generarAlertaAcademicaCurso(id_curso, nombre_curso, boton, bimestre_elegido) {
    var texto_original = boton ? boton.html() : '';
    var bimestre = bimestre_elegido || bimestreAlertaAcademica();

    //Abrimos el modal de inmediato mostrando el indicador de carga, mientras corre el script
    ponerTituloResultadoAlertaAcademica(nombre_curso, bimestre);
    $('.contenido-resultado-alerta-academica').hide();
    $('.cargando-resultado-alerta-academica').show();
    $('#modal_resultado_alerta_academica').modal('show');

    if (boton) {
        boton.prop('disabled', true);
    }

    $.ajax({
        url: base_url + 'alerta_academica/generar',
        type: 'post',
        data: {
            id_curso: id_curso,
            bimestre: bimestre
        }
    })
        .done(function (data) {
            if (data.error) {
                $('.cargando-resultado-alerta-academica').html('<div class="alert alert-danger mb-0">' + data.error + '</div>');
                return;
            }
            //Ningun alumno con notas del bimestre anterior: se explica el motivo en vez de una tabla vacia
            if (!data.resultados || data.resultados.length === 0) {
                $('.cargando-resultado-alerta-academica').html('<div class="alert alert-warning mb-0 text-left"><strong>No se pudo generar la predicción del ' +
                    escaparHtmlAlertaAcademica(textoBimestreAlertaAcademica(bimestre)) + ' para este curso.</strong><br>' +
                    escaparHtmlAlertaAcademica(motivoCursoSinPrediccionAlertaAcademica(data)) + '</div>');
                return;
            }
            pintarResultadoAlertaAcademica(data);
            guardarUltimoResultadoAlertaAcademica(id_curso, nombre_curso, data);
        })
        .fail(function (data) {
            console.log(data);
            $('.cargando-resultado-alerta-academica').html('<div class="alert alert-danger mb-0">Ha ocurrido un error interno.</div>');
        })
        .always(function () {
            if (boton) {
                boton.prop('disabled', false).html(texto_original);
            }
        });
}

/* Boton "Generar Predicción" de un curso: primero una ventana con el curso y el bimestre a predecir
   (solo el bimestre en curso habilitado); al confirmar, se genera y se muestra en el modal de resultado */
var cursoPendienteAlertaAcademica = null;

$(document).on('click', '.boton-generar-alerta', function (e) {
    e.preventDefault();
    var boton = $(this);
    cursoPendienteAlertaAcademica = {
        id_curso: boton.attr('data-idcurso'),
        nombre_curso: boton.attr('data-nombrecurso'),
        boton: boton
    };
    $('.nombre-curso-generar-alerta').val(boton.attr('data-nombrecurso'));
    $('.select-bimestre-curso-alerta').val(bimestreAlertaAcademica());
    $('#modal_generar_curso_alerta_academica').modal('show');
    return false;
});

$(document).on('click', '.boton-confirmar-curso-alerta', function () {
    var pendiente = cursoPendienteAlertaAcademica;
    var bimestre = parseInt($('.select-bimestre-curso-alerta').val(), 10);
    if (!pendiente) {
        return;
    }
    cursoPendienteAlertaAcademica = null;
    //El modal de resultado se abre cuando este termino de cerrarse (dos modales a la vez dejan el fondo pegado)
    $('#modal_generar_curso_alerta_academica').one('hidden.bs.modal', function () {
        generarAlertaAcademicaCurso(pendiente.id_curso, pendiente.nombre_curso, pendiente.boton, bimestre);
    }).modal('hide');
});

/* Al volver del tablero de Power BI (?volver_curso=N), reabre el resultado de ese curso: desde
   sessionStorage si esta guardado; si no, lo genera otra vez (generar() valida los permisos) */
$(function () {
    var volver = $('#volver_alerta_academica');
    var id_curso = parseInt(volver.attr('data-idcurso'), 10);
    if (volver.length === 0 || !(id_curso > 0)) {
        return;
    }
    //Quitamos ?volver_curso de la URL para que recargar la pagina no vuelva a abrir el modal
    if (window.history && window.history.replaceState) {
        window.history.replaceState(null, '', window.location.pathname);
    }

    var guardado = leerUltimoResultadoAlertaAcademica(id_curso);
    if (guardado) {
        ponerTituloResultadoAlertaAcademica(guardado.nombre_curso, guardado.data.bimestre_solicitado);
        pintarResultadoAlertaAcademica(guardado.data);
        $('#modal_resultado_alerta_academica').modal('show');
    } else {
        generarAlertaAcademicaCurso(id_curso, volver.attr('data-nombrecurso'), null);
    }
});

/* Al cerrar el modal, restauramos el indicador de carga para la proxima vez que se abra */
$(document).on('hidden.bs.modal', '#modal_resultado_alerta_academica', function () {
    $('.contenido-resultado-alerta-academica').hide();
    $('.cargando-resultado-alerta-academica').show().html('<i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-3 mb-0">Generando predicción, esto puede tardar unos segundos...</p>');
    $('.aviso-tablero-alerta-academica').hide().html('');
    $('.boton-tablero-curso-alerta').hide();
    $('.boton-excel-curso-alerta').hide();
});

/* Boton "Ver en Power BI" (modal de un curso y modal del lote): abre el tablero directo
   (sin aviso previo, decision de la autora 2026-11-10). Si aun no esta publicado, se avisa en el
   modal. En la URL solo van numeros (id_curso, id_alumno); el tablero se abre en la misma pestana
   para que "Volver a la prediccion" encuentre el resultado guardado en sessionStorage */
$(document).on('click', '.boton-tablero-alerta', function () {
    var id_curso = parseInt($(this).attr('data-idcurso'), 10);
    var desde_lote = $(this).hasClass('boton-tablero-lote-alerta');
    if (!(id_curso > 0) && !desde_lote) {
        return;
    }

    if ($('#modal_resultado_alerta_academica').attr('data-powerbi-publicado') !== '1') {
        var html = '<div class="alert alert-warning aa-aviso">';
        html += '<span class="aa-aviso-texto">El tablero aún no está publicado.</span>';
        html += '<button type="button" class="btn btn-secondary btn-sm boton-cancelar-tablero-alerta">Cerrar</button>';
        html += '</div>';
        if (desde_lote) {
            swal('Tablero de alertas', 'El tablero aún no está publicado.', 'info');
            return;
        }
        $('.aviso-tablero-alerta-academica').html(html).show();
        $('#modal_resultado_alerta_academica').scrollTop(0);
        return;
    }
    //Ambos botones abren la pagina "Detalle por Grado y Curso"; desde un curso se agrega id_curso
    //para la barra "Volver a la prediccion de [curso]"
    window.location.href = base_url + 'alerta_academica/tablero?vista=curso' + (id_curso > 0 ? '&id_curso=' + id_curso : '');
});

$(document).on('click', '.boton-cancelar-tablero-alerta', function () {
    $('.aviso-tablero-alerta-academica').hide().html('');
});

/* ---------- Generacion institucional (solo Super Admin): generar() curso por curso ---------- */

var loteAlertaAcademica = {corriendo: false, cancelar: false, resultados: []};

/* Cursos de secundaria del anio elegido, ya sin repetir id_curso (los arma listar() en PHP) */
function cursosLoteAlertaAcademica() {
    try {
        return JSON.parse($('#datos_lote_alerta_academica').text()) || [];
    } catch (e) {
        return [];
    }
}

function actualizarBarraLoteAlertaAcademica(hechos, total) {
    var pct = total > 0 ? Math.round(hechos / total * 100) : 0;
    $('.barra-progreso-lote-alerta').css('width', pct + '%').text(pct + '%');
    $('.porcentaje-lote-alerta').text(pct + '%');
}

/* Una peticion por curso, en secuencia: un error en un curso no detiene el lote, y "Cancelar
   lote" se respeta al terminar el curso en proceso */
function ejecutarLoteAlertaAcademica(cursos, titulo, alumnos, bimestre_elegido) {
    if (loteAlertaAcademica.corriendo || cursos.length === 0) {
        return;
    }
    //Mismo bimestre para todo el lote (el bimestre en curso)
    var bimestre = bimestre_elegido || bimestreAlertaAcademica();
    var no_generados = [];
    if (bimestre) {
        titulo += ' - ' + textoBimestreAlertaAcademica(bimestre);
    }
    //El lote se ve en su propio modal (no empuja el listado de cursos); mientras procesa solo se
    //puede salir con "Cancelar lote"
    $('.titulo-lote-alerta-academica').text(titulo);
    $('.cerrar-lote-alerta').hide();
    $('#modal_lote_alerta_academica').modal('show');
    //alumnos: ids de alumno elegidos en "Alumno (opcional)"; vacio = todos los alumnos
    var filtroAlumnos = alumnos && alumnos.length > 0 ? alumnos : null;
    loteAlertaAcademica = {corriendo: true, cancelar: false, resultados: []};
    var total = cursos.length;
    var indice = 0;
    var procesados = 0;
    var guardadas = 0;
    var excluidosPorAlumno = {};
    var errores = [];

    $('.boton-lote-colegio-alerta, .boton-lote-grado-alerta, .boton-generar-alerta').prop('disabled', true);
    $(window).on('beforeunload.loteAlertaAcademica', function () {
        return 'Hay un lote de predicciones en proceso.';
    });
    $('.resultado-lote-alerta-academica').hide();
    $('.cuerpo-lote-alerta-academica').html('');
    $('.resumen-lote-alerta-academica').hide().html('');
    $('.nota-lote-alerta-academica').html('');
    $('.paginacion-lote-alerta-academica').html('');
    $('.aviso-pestana-lote-alerta').show();
    $('.progreso-lote-alerta-academica').show();
    $('.boton-cancelar-lote-alerta').prop('disabled', false).text('Cancelar Lote').show();
    $('.barra-progreso-lote-alerta').addClass('progress-bar-animated');
    actualizarBarraLoteAlertaAcademica(0, total);

    function siguiente() {
        if (indice >= total || loteAlertaAcademica.cancelar) {
            terminar();
            return;
        }
        var c = cursos[indice];
        $('.texto-progreso-lote-alerta').text('Procesando curso ' + (indice + 1) + ' de ' + total + ': ' + c.nombre_curso + ' - ' + c.nombre_grado);

        $.ajax({
            url: base_url + 'alerta_academica/generar',
            type: 'post',
            data: {
                id_curso: c.id_curso,
                bimestre: bimestre
            }
        })
            .done(function (data) {
                if (!data || data.error || !data.resultados) {
                    errores.push({curso: c, mensaje: (data && data.error) ? data.error : 'Respuesta inválida.'});
                    return;
                }
                procesados++;
                guardadas += data.resultados.length;
                //Curso sin ningun alumno con notas del bimestre anterior: no se genero, se informa al final
                if (data.resultados.length === 0) {
                    no_generados.push({curso: c, motivo: motivoCursoSinPrediccionAlertaAcademica(data)});
                    return;
                }
                //Alumnos sin prediccion en este curso: se agrupan por alumno para el "Ver detalle"
                $.each(data.alumnos_excluidos || [], function (i, ex) {
                    if (filtroAlumnos && $.inArray(parseInt(ex.id_alumno, 10), filtroAlumnos) < 0) {
                        return;
                    }
                    if (!excluidosPorAlumno[ex.id_alumno]) {
                        excluidosPorAlumno[ex.id_alumno] = {nombre: ex.nombre_alumno || '', grado: c.nombre_grado, cursos: []};
                    }
                    excluidosPorAlumno[ex.id_alumno].cursos.push(c.nombre_curso);
                });
                $.each(data.resultados, function (i, r) {
                    if (filtroAlumnos && $.inArray(parseInt(r.id_alumno, 10), filtroAlumnos) < 0) {
                        return;
                    }
                    r.nombre_curso_lote = c.nombre_curso;
                    r.nombre_grado_lote = c.nombre_grado;
                    loteAlertaAcademica.resultados.push(r);
                });
            })
            .fail(function () {
                errores.push({curso: c, mensaje: 'Ha ocurrido un error interno.'});
            })
            .always(function () {
                indice++;
                actualizarBarraLoteAlertaAcademica(indice, total);
                siguiente();
            });
    }

    function terminar() {
        var sin_procesar = total - indice;
        loteAlertaAcademica.corriendo = false;
        $(window).off('beforeunload.loteAlertaAcademica');
        $('.boton-lote-colegio-alerta, .boton-lote-grado-alerta, .boton-generar-alerta').prop('disabled', false);
        $('.aviso-pestana-lote-alerta').hide();
        $('.boton-cancelar-lote-alerta').hide();
        $('.cerrar-lote-alerta').show();
        $('.progreso-lote-alerta-academica').hide();

        //Resumen final: tabla como las del resto de la intranet (table-bordered, encabezado gris
        //#D8D8D8) con el conteo por nivel de alerta y, debajo, los datos del lote en una linea pequena
        var conteo = {sin_riesgo: 0, riesgo_moderado: 0, riesgo_alto: 0};
        $.each(loteAlertaAcademica.resultados, function (i, r) {
            if (conteo[r.clasificacion] !== undefined) {
                conteo[r.clasificacion]++;
            }
        });
        var html = '';
        if (sin_procesar > 0) {
            html += '<div class="alert alert-warning py-2">Lote cancelado. Los cursos ya procesados quedaron guardados; los ' +
                sin_procesar + ' restantes no se generaron.</div>';
        }
        if (errores.length > 0) {
            html += '<div class="alert alert-danger py-2"><strong>Cursos con error:</strong><ul class="mb-0 pl-3">';
            $.each(errores, function (i, e) {
                html += '<li>' + escaparHtmlAlertaAcademica(e.curso.nombre_curso + ' - ' + e.curso.nombre_grado) + ': ' + escaparHtmlAlertaAcademica(e.mensaje) + '</li>';
            });
            html += '</ul></div>';
        }
        html += tarjetasResumenAlertaAcademica(conteo.riesgo_alto, conteo.riesgo_moderado, conteo.sin_riesgo, bimestre, true);

        //Resumen para el docente: en cuantos cursos se genero y, si aplica, cuantos alumnos (distintos)
        //quedaron sin prediccion en algun curso y que cursos no se generaron. El detalle va plegado.
        var texto_bimestre = bimestre ? textoBimestreAlertaAcademica(bimestre) : 'próximo bimestre';
        var texto_anterior = bimestre ? textoBimestreAlertaAcademica(bimestre - 1) : 'bimestre anterior';
        var generados = procesados - no_generados.length;
        var ids_excluidos = Object.keys(excluidosPorAlumno);
        var resumen = 'Se generó la predicción del ' + texto_bimestre + ' en ' + generados +
            (generados === procesados ? '' : ' de ' + procesados) + (procesados === 1 ? ' curso.' : ' cursos.');
        if (no_generados.length > 0) {
            resumen += ' ' + no_generados.length + (no_generados.length === 1 ? ' curso no se generó' : ' cursos no se generaron') +
                ' por falta de notas del ' + texto_anterior + '.';
        }
        if (ids_excluidos.length > 0) {
            resumen += ' ' + ids_excluidos.length + (ids_excluidos.length === 1 ? ' alumno no tiene' : ' alumnos no tienen') +
                ' predicción en uno o más cursos por no tener notas del ' + texto_anterior + '.';
        }
        var detalle = '';
        if (ids_excluidos.length > 0) {
            detalle += '<table class="table table-bordered table-sm mb-2"><thead><tr style="background-color: #D8D8D8;">' +
                '<th>Alumno</th><th>Grado</th><th>Cursos sin notas del ' + texto_anterior + '</th></tr></thead><tbody>';
            ids_excluidos.sort(function (a, b) {
                return excluidosPorAlumno[a].nombre.localeCompare(excluidosPorAlumno[b].nombre);
            });
            $.each(ids_excluidos, function (i, id) {
                var ex = excluidosPorAlumno[id];
                detalle += '<tr><td>' + escaparHtmlAlertaAcademica(ex.nombre) + '</td><td>' + escaparHtmlAlertaAcademica(ex.grado) +
                    '</td><td>' + escaparHtmlAlertaAcademica(ex.cursos.join(', ')) + '</td></tr>';
            });
            detalle += '</tbody></table>';
        }
        if (no_generados.length > 0) {
            detalle += '<table class="table table-bordered table-sm mb-2"><thead><tr style="background-color: #D8D8D8;">' +
                '<th>Curso sin predicción</th><th>Grado</th><th>Motivo</th></tr></thead><tbody>';
            $.each(no_generados, function (i, n) {
                detalle += '<tr><td>' + escaparHtmlAlertaAcademica(n.curso.nombre_curso) + '</td><td>' +
                    escaparHtmlAlertaAcademica(n.curso.nombre_grado) + '</td><td>' + escaparHtmlAlertaAcademica(n.motivo) + '</td></tr>';
            });
            detalle += '</tbody></table>';
        }
        //La nota va al final de la ventana, debajo de la lista (igual que el aviso de excluidos de un curso)
        $('.nota-lote-alerta-academica').html('<div class="text-muted small mt-2">' + resumen +
            (detalle ? detallePlegableAlertaAcademica(detalle) : '') + '</div>');
        $('.resumen-lote-alerta-academica').html(html).show();

        pintarListaLoteAlertaAcademica();
    }

    siguiente();
}

function llenarSelectLoteAlertaAcademica(select, textoTodos, valores) {
    var actual = select.val();
    select.empty().append($('<option>').val('').text(textoTodos));
    $.each(valores, function (i, v) {
        select.append($('<option>').val(v).text(v));
    });
    select.val($.inArray(actual, valores) >= 0 ? actual : '');
}

/* Cursos presentes en la lista (del grado elegido, si hay uno), en orden alfabetico */
function cursosFiltroLoteAlertaAcademica(grado) {
    var cursos = [];
    $.each(loteAlertaAcademica.resultados, function (i, r) {
        if ((!grado || r.nombre_grado_lote === grado) && $.inArray(r.nombre_curso_lote, cursos) < 0) {
            cursos.push(r.nombre_curso_lote);
        }
    });
    return cursos.sort();
}

/* Lista de alumnos del lote, de mayor a menor probabilidad, con "Ver factores" igual que el modal */
function pintarListaLoteAlertaAcademica() {
    var resultados = loteAlertaAcademica.resultados;
    if (resultados.length === 0) {
        $('.resultado-lote-alerta-academica').hide();
        return;
    }
    resultados.sort(function (a, b) {
        return b.probabilidad_riesgo - a.probabilidad_riesgo;
    });

    var filas = '';
    $.each(resultados, function (k, r) {
        filas += filasAlumnoAlertaAcademica(r, k + 1, {
            celdasExtra: '<td>' + escaparHtmlAlertaAcademica(r.nombre_grado_lote) + '</td><td>' + escaparHtmlAlertaAcademica(r.nombre_curso_lote) + '</td>',
            colspan: 7,
            atributosFila: ' data-indice-lote="' + k + '"',
            botonesExtra: ''
        });
    });
    $('.cuerpo-lote-alerta-academica').html(filas);

    //Grados en el mismo orden que el listado de cursos
    var grados = [];
    $.each(cursosLoteAlertaAcademica(), function (i, c) {
        if ($.inArray(c.nombre_grado, grados) < 0) {
            $.each(resultados, function (j, r) {
                if (r.nombre_grado_lote === c.nombre_grado) {
                    grados.push(c.nombre_grado);
                    return false;
                }
            });
        }
    });
    llenarSelectLoteAlertaAcademica($('.filtro-lote-grado-alerta').val(''), 'Todos los grados', grados);
    llenarSelectLoteAlertaAcademica($('.filtro-lote-curso-alerta').val(''), 'Todos los cursos', cursosFiltroLoteAlertaAcademica(''));
    $('.filtro-lote-nivel-alerta').val('');
    $('.buscar-lote-alerta').val('');

    aplicarFiltrosLoteAlertaAcademica();
    $('.resultado-lote-alerta-academica').show();
}

function aplicarFiltrosLoteAlertaAcademica() {
    var grado = $('.filtro-lote-grado-alerta').val();
    var curso = $('.filtro-lote-curso-alerta').val();
    var nivel = $('.filtro-lote-nivel-alerta').val();
    var texto = textoBusquedaAlertaAcademica($.trim($('.buscar-lote-alerta').val()));

    filtrarFilasAlertaAcademica($('.cuerpo-lote-alerta-academica'), function (fila) {
        var r = loteAlertaAcademica.resultados[parseInt(fila.attr('data-indice-lote'), 10)];
        return (!grado || r.nombre_grado_lote === grado)
            && (!curso || r.nombre_curso_lote === curso)
            && (!nivel || r.clasificacion === nivel)
            && (!texto || fila.attr('data-nombre').indexOf(texto) >= 0);
    });
    actualizarConteoLoteAlertaAcademica(paginarAlertaAcademica($('.cuerpo-lote-alerta-academica'), $('.paginacion-lote-alerta-academica'), 1));
}

/* Una fila por alumno y curso: "Mostrando 1 a 15 de 272 resultados (13 alumnos en 21 cursos)" */
function actualizarConteoLoteAlertaAcademica(rango) {
    var total_res = loteAlertaAcademica.resultados.length;
    var alumnos_dist = {};
    var cursos_dist = {};
    $.each(loteAlertaAcademica.resultados, function (i, r) {
        alumnos_dist[r.id_matricula] = true;
        cursos_dist[r.nombre_curso_lote + '|' + r.nombre_grado_lote] = true;
    });
    var n_alumnos = Object.keys(alumnos_dist).length;
    var n_cursos = Object.keys(cursos_dist).length;
    $('.conteo-lote-alerta-academica').text('Mostrando ' + rango.desde + ' a ' + rango.hasta + ' de ' + rango.total +
        (rango.total === 1 ? ' resultado' : ' resultados') + (rango.total < total_res ? ' (filtrados de ' + total_res + ')' : '') +
        ' · ' + n_alumnos + (n_alumnos === 1 ? ' alumno' : ' alumnos') + ' en ' + n_cursos + (n_cursos === 1 ? ' curso' : ' cursos'));
}

$(document).on('change', '.filtro-lote-grado-alerta', function () {
    llenarSelectLoteAlertaAcademica($('.filtro-lote-curso-alerta'), 'Todos los cursos', cursosFiltroLoteAlertaAcademica($(this).val()));
    aplicarFiltrosLoteAlertaAcademica();
});

$(document).on('change', '.filtro-lote-curso-alerta, .filtro-lote-nivel-alerta', function () {
    aplicarFiltrosLoteAlertaAcademica();
});

$(document).on('input', '.buscar-lote-alerta', function () {
    aplicarFiltrosLoteAlertaAcademica();
});

$(document).on('click', '.boton-lote-colegio-alerta', function (e) {
    e.preventDefault();
    var cursos = cursosLoteAlertaAcademica();
    if (loteAlertaAcademica.corriendo || cursos.length === 0) {
        return;
    }
    //Confirmacion en un modal de la intranet (antes era un aviso SweetAlert), con el año escolar elegido
    $('.anio-colegio-alerta').text($.trim($('#id_anio_escolar_alerta option:selected').text()));
    $('.bimestre-colegio-alerta').text(textoBimestreAlertaAcademica(bimestreAlertaAcademica()));
    $('#modal_confirmar_colegio_alerta_academica').modal('show');
});

$(document).on('click', '.boton-confirmar-colegio-alerta', function () {
    var cursos = cursosLoteAlertaAcademica();
    if (loteAlertaAcademica.corriendo || cursos.length === 0) {
        return;
    }
    //Se abre el modal del lote recien cuando este termino de cerrarse (dos modales a la vez dejan
    //el fondo oscuro trabado en Bootstrap 4)
    $('#modal_confirmar_colegio_alerta_academica').one('hidden.bs.modal', function () {
        ejecutarLoteAlertaAcademica(cursos, 'Predicción de todo el colegio');
    }).modal('hide');
});

$(document).on('click', '.boton-lote-grado-alerta', function (e) {
    e.preventDefault();
    if (loteAlertaAcademica.corriendo) {
        return;
    }
    $('.select-lote-grado-alerta').val('');
    $('.select-lote-curso-alerta').html('<option value="">Todos los cursos del grado</option>').prop('disabled', true);
    llenarAlumnosLoteAlertaAcademica(0);
    $('.error-lote-grado-alerta').hide();
    $('#modal_lote_grado_alerta_academica').modal('show');
});

/* Alumnos matriculados en los grados de la generacion institucional (los arma listar() en PHP) */
function alumnosLoteAlertaAcademica() {
    try {
        return JSON.parse($('#alumnos_lote_alerta_academica').text()) || [];
    } catch (e) {
        return [];
    }
}

/* "Alumno (opcional)": solo los alumnos del grado elegido; sin grado queda deshabilitado */
function llenarAlumnosLoteAlertaAcademica(id_grado) {
    var select = $('select.select-lote-alumno-alerta');
    //bootstrap-select 1.13.2 falla en 'refresh' (liHeight: "reading 'className'") cuando un campo que
    //ya tuvo opciones queda vacio dentro de un modal oculto, y corta el resto del codigo: el boton
    //"Generar por Grado / Aula" dejaba de abrir el modal (2026-11-11). Se desmonta y se vuelve a crear.
    if (select.data('selectpicker')) {
        select.selectpicker('destroy');
    }
    select.empty();
    $.each(alumnosLoteAlertaAcademica(), function (i, a) {
        if (a.id_grado === id_grado) {
            select.append($('<option>').val(a.id_alumno).text(a.nombre));
        }
    });
    select.prop('disabled', !(id_grado > 0));
    select.selectpicker();
}

/* Al elegir un grado, el selector de curso (opcional) muestra solo los cursos de ese grado */
$(document).on('change', '.select-lote-grado-alerta', function () {
    var id_grado = parseInt($(this).val(), 10);
    var select = $('.select-lote-curso-alerta');
    select.html('<option value="">Todos los cursos del grado</option>');
    llenarAlumnosLoteAlertaAcademica(id_grado > 0 ? id_grado : 0);
    if (!(id_grado > 0)) {
        select.prop('disabled', true);
        return;
    }
    $.each(cursosLoteAlertaAcademica(), function (i, c) {
        if (c.id_grado === id_grado) {
            select.append($('<option>').val(c.id_curso).text(c.nombre_curso));
        }
    });
    select.prop('disabled', false);
    $('.error-lote-grado-alerta').hide();
});

$(document).on('click', '.boton-confirmar-lote-grado-alerta', function () {
    var id_grado = parseInt($('.select-lote-grado-alerta').val(), 10);
    var id_curso = parseInt($('.select-lote-curso-alerta').val(), 10);
    if (!(id_grado > 0)) {
        $('.error-lote-grado-alerta').show();
        return;
    }
    var cursos = $.grep(cursosLoteAlertaAcademica(), function (c) {
        return id_curso > 0 ? c.id_curso === id_curso : c.id_grado === id_grado;
    });
    if (cursos.length === 0) {
        return;
    }
    var alumnos = $.map($('select.select-lote-alumno-alerta').val() || [], function (v) {
        var id = parseInt(v, 10);
        return id > 0 ? id : null;
    });
    var bimestre = parseInt($('.select-bimestre-grado-alerta').val(), 10) || bimestreAlertaAcademica();
    //Se abre el siguiente modal recien cuando este termino de cerrarse (dos modales a la vez dejan
    //el fondo oscuro pegado). Un solo curso se muestra igual que el boton amarillo de la lista.
    $('#modal_lote_grado_alerta_academica').one('hidden.bs.modal', function () {
        if (alumnos.length > 0) {
            //Con alumnos elegidos se usa siempre el modal del lote, que filtra la lista a esos alumnos
            ejecutarLoteAlertaAcademica(cursos, 'Predicción de ' + cursos[0].nombre_grado + ' (' + alumnos.length +
                (alumnos.length === 1 ? ' alumno)' : ' alumnos)'), alumnos, bimestre);
        } else if (id_curso > 0) {
            generarAlertaAcademicaCurso(cursos[0].id_curso, cursos[0].nombre_curso + ' - ' + cursos[0].nombre_grado, null, bimestre);
        } else {
            ejecutarLoteAlertaAcademica(cursos, 'Predicción de ' + cursos[0].nombre_grado, null, bimestre);
        }
    }).modal('hide');
});

$(document).on('click', '.boton-cancelar-lote-alerta', function () {
    if (!loteAlertaAcademica.corriendo) {
        return;
    }
    loteAlertaAcademica.cancelar = true;
    $(this).prop('disabled', true).text('Cancelando… (termina el curso en proceso)');
});

/* Muestra/oculta los factores explicables (IA explicable) de un alumno */
$(document).on('click', '.boton-ver-factores', function () {
    var boton = $(this);
    var fila = boton.closest('tr').next('.fila-factores');
    fila.toggle();
    if (fila.is(':visible')) {
        boton.html('<i class="fas fa-chevron-up mr-1"></i>Ocultar factores');
    } else {
        boton.html('<i class="fas fa-chevron-down mr-1"></i>Ver factores');
    }
});

/* -------------------- FIN MODULO DE ALERTA ACADEMICA -------------------- */