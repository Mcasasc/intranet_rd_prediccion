(function ($) {
    $.ajaxblock = function () {
        $("body").prepend("<div id='ajax-overlay'><div id='ajax-overlay-body' class='center'><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i><span class='sr-only'>Loading...</span></div></div>");
        $("#ajax-overlay").css({
            position: 'absolute',
            color: '#FFFFFF',
            top: '0',
            left: '0',
            width: '100%',
            height: '100%',
            position: 'fixed',
            background: 'rgba(39, 38, 46, 0.67)',
            'text-align': 'center',
            'z-index': '9999'
        });
        $("#ajax-overlay-body").css({
            position: 'absolute',
            top: '40%',
            left: '50%',
            width: '120px',
            height: '48px',
            'margin-top': '-12px',
            'margin-left': '-60px',
            //background: 'rgba(39, 38, 46, 0.1)',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            'border-radius': '10px'
        });
        $("#ajax-overlay").fadeIn(50);
    };
    $.ajaxunblock = function () {
        $("#ajax-overlay").fadeOut(100, function () {
            $("#ajax-overlay").remove();
        });
    };
})(jQuery);


/* Habilita el acordion del Sidebar */
$('ul').tree();

/* Funcionalidad para mostrar u ocultar el Sidebar */
$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});

/* Iniciamos Datetangepicker */
$("#datepicker").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
        format: "YYYY-MM-DD",
        "daysOfWeek": [
            "DO",
            "LU",
            "MA",
            "MI",
            "JU",
            "VI",
            "SA"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Setiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1 //Lunes
    }
});

/* Inicializa Datatable */
$(document).ready(function () {
    $('#lista').DataTable({
        "ordering": false,
        "lengthMenu": [
            [9, 25, 50, -1],
            [9, 25, 50, "All"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sZeroRecords: "No se encontraron resultados",
            sInfoEmpty: "No se encontraron resultados",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            searchPlaceholder: "Buscar...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            //'copyHtml5',
            //'excelHtml5',
            {
                extend: 'excelHtml5',
                text: 'Exportar Excel'
            }
            //'csvHtml5',
            //'pdfHtml5'
        ]
    });

    $('#no-lista').DataTable({
        "ordering": false,
        "lengthMenu": [
            [9, 25, 50, -1],
            [9, 25, 50, "All"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sZeroRecords: "No se encontraron resultados",
            sInfoEmpty: "No se encontraron resultados",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            searchPlaceholder: "Buscar...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            //'copyHtml5',
            //'excelHtml5',
            //'csvHtml5',
            //'pdfHtml5'
        ]
    });

    $('#lista-mensajeria').DataTable({
        "ordering": false,
        "lengthMenu": [
            [9, 25, 50, -1],
            [9, 25, 50, "All"]
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sZeroRecords: "No se encontraron resultados",
            sInfoEmpty: "No se encontraron resultados",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            searchPlaceholder: "Buscar...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            }
        },
        dom: 'Bfrtip',
        buttons: [
            //'copyHtml5',
            //'excelHtml5',
            //'csvHtml5',
            //'pdfHtml5'
        ]
    });
});

$(document).ready(function () {

    /* Inicializamos CKEditor Modo Admin */
    /*DecoupledEditor.create(document.querySelector('#editor'))
        .then(editor => {
            const toolbarContainer = document.querySelector('#toolbar-container');
            toolbarContainer.appendChild(editor.ui.view.toolbar.element);

            //OBTENEMOS EL CONTENIDO
            $(document).on('keyup', '#editor', function () {
                $(".contenido").val(editor.getData());
            });
        }).catch(error => {
            console.error(error);
        });
*/
    DecoupledDocumentEditor.create(document.querySelector('#editor'), {

        toolbar: {
            items: [
                'heading',
                '|',
                'removeFormat',
                'fontSize',
                'fontFamily',
                '|',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                'highlight',
                '|',
                'alignment',
                '|',
                'numberedList',
                'bulletedList',
                '|',
                'indent',
                'outdent',
                '|',
                'todoList',
                'link',
                'blockQuote',
                'imageUpload',
                'insertTable',
                'mediaEmbed',
                '|',
                'undo',
                'redo'
            ]
        },
        language: 'es',
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:side'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells',
                'tableCellProperties',
                'tableProperties'
            ]
        },

        licenseKey: '',

    })
        .then(editor => {
            window.editor = editor;

            // Set a custom container for the toolbar.
            //document.querySelector('.document-editor__toolbar').appendChild(editor.ui.view.toolbar.element);
            //document.querySelector('.ck-toolbar').classList.add('ck-reset_all');
            const toolbarContainer = document.querySelector('#toolbar-container');
            toolbarContainer.appendChild(editor.ui.view.toolbar.element);
            //OBTENEMOS EL CONTENIDO
            $(document).bind('keyup change paste', '#editor', function () {
                $(".contenido").val(window.editor.getData());
            });
        })
        .catch(error => {
            //console.error(error);
        });


    /* Inicializamos CKEditor Modo Visor*/
    DecoupledDocumentEditor.create(document.querySelector('#visor'))
        .then(editor => {
            //const toolbarContainer = document.querySelector('#toolbar-container');
            //toolbarContainer.appendChild(editor.ui.view.toolbar.element);

            editor.isReadOnly = true;

        }).catch(error => {
            //console.error(error);
        });

});
/*
navigator.mediaDevices.getUserMedia({ audio: false, video: true }).then((stream) => {
    console.log(stream)


}).catch((err) => console.log(err))*/