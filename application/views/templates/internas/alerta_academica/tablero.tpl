<link rel="stylesheet" href="{$base_url}public/css/alerta_academica.css?14"/>
<div class="page-content-wrapper bg-white">
    <section class="container-fluid pl-0">
        <div class="row m-3">
            <!-- Barra sobre el tablero de Power BI: misma tarjeta que "Mostrar por:" y "Generación
                 institucional" de la pagina de prediccion (card border-primary, encabezado azul) -->
            <div class="w-100 pb-3 pl-0">
                <div class="card border-primary">
                    <div class="card-header font-weight-bold bg-primary text-white">
                        Tablero de Alertas{if $volver_id_curso} - {$volver_nombre_curso|escape}{/if}
                    </div>
                    <div class="card-body p-2 d-flex justify-content-between align-items-center flex-wrap">
                        <span class="my-1 mr-3" id="estado_tablero_alerta" aria-live="polite">Resumen de las alertas académicas generadas, en Power BI.</span>
                        <div class="my-1">
                            {if $boton_actualizar_tablero}
                                <button type="button" class="btn btn-primary boton-actualizar-tablero-alerta" data-base-url="{$base_url}">Actualizar Tablero</button>
                            {/if}
                            {if $volver_id_curso}
                                <a href="{$url_volver}" class="btn btn-secondary ml-1">Volver a la Predicción</a>
                            {else}
                                <a href="{$url_volver}" class="btn btn-secondary ml-1">Ir a Generar Predicción</a>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>

            {if $error_tablero != ''}
                <h1><small>{$error_tablero}</small></h1>
            {elseif $url_tablero == ''}
                <h1><small>El tablero aún no está publicado</small></h1>
            {else}
                <div class="w-100 aa-marco-tablero{if $ocultar_franja_powerbi} aa-sin-franja{/if}">
                    <iframe src="{$url_tablero|escape}" allowfullscreen="true"></iframe>
                </div>
            {/if}
        </div>
    </section>
</div>
{if $boton_actualizar_tablero}
{literal}
<script>
/* Boton "Actualizar tablero": pide a Power BI actualizar el modelo semantico (como "Actualizar ahora"
   en app.powerbi.com) y consulta el estado cada 10 s hasta que termine. Si otro usuario ya inicio una
   actualizacion, solo se espera a esa. Al terminar se recarga el tablero. */
(function () {
    var boton = document.querySelector('.boton-actualizar-tablero-alerta');
    var estado = document.getElementById('estado_tablero_alerta');
    if (!boton || !estado) {
        return;
    }
    var baseUrl = boton.getAttribute('data-base-url');
    var textoInicial = estado.textContent;
    var INTERVALO = 10000;            // 10 s entre consultas
    var MAXIMO_ESPERA = 15 * 60000;   // deja de consultar a los 15 min
    var inicioEspera = 0;
    var pulsado = false;              // el usuario acaba de pulsar el boton
    // Colores de Bootstrap que ya usa la intranet
    var CLASES = {proceso: 'text-primary', ok: 'text-success', error: 'text-danger'};

    function mostrar(texto, tipo) {
        estado.className = 'my-1 mr-3' + (CLASES[tipo] ? ' ' + CLASES[tipo] : '');
        estado.textContent = texto || textoInicial;
        if (tipo === 'proceso') {
            var giro = document.createElement('i');
            giro.className = 'fas fa-spinner fa-spin mr-1';
            estado.insertBefore(giro, estado.firstChild);
        }
    }

    function ocupado(si) {
        boton.disabled = si;
        boton.textContent = si ? 'Actualizando...' : 'Actualizar Tablero';
    }

    function pedir(accion) {
        return fetch(baseUrl + 'alerta_academica/' + accion, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        }).then(function (r) { return r.json(); });
    }

    function recargarTablero() {
        var marco = document.querySelector('.aa-marco-tablero iframe');
        if (marco) {
            marco.src = marco.src;
        }
    }

    /* Solo se muestra un mensaje mientras se actualiza (o si falla una actualizacion que se estaba
       esperando). En reposo queda el texto inicial, sin fecha de la ultima actualizacion. */
    function procesar(datos) {
        if (datos.error) {
            ocupado(false);
            mostrar(pulsado || inicioEspera ? datos.error : '', pulsado || inicioEspera ? 'error' : '');
            pulsado = false;
            inicioEspera = 0;
            return;
        }
        pulsado = false;
        if (datos.estado === 'en_curso') {
            ocupado(true);
            mostrar('Actualizando los datos del tablero… (puede tardar unos minutos)', 'proceso');
            if (!inicioEspera) {
                inicioEspera = Date.now();
            }
            if (Date.now() - inicioEspera > MAXIMO_ESPERA) {
                ocupado(false);
                mostrar('La actualización está tardando más de lo normal. Vuelva a abrir esta página en unos minutos.', 'error');
                return;
            }
            setTimeout(consultar, INTERVALO);
            return;
        }
        ocupado(false);
        var huboEspera = inicioEspera !== 0;
        inicioEspera = 0;
        if (huboEspera && datos.estado === 'fallida') {
            mostrar('La actualización no se completó. Intente nuevamente.', 'error');
            return;
        }
        if (huboEspera && datos.estado === 'completada') {
            recargarTablero();
        }
        mostrar('', '');
    }

    function fallo() {
        var avisar = pulsado || inicioEspera;
        ocupado(false);
        pulsado = false;
        inicioEspera = 0;
        mostrar(avisar ? 'No se pudo consultar el tablero. Intente nuevamente.' : '', avisar ? 'error' : '');
    }

    function consultar() {
        pedir('estado_tablero').then(procesar, fallo);
    }

    boton.addEventListener('click', function () {
        ocupado(true);
        pulsado = true;
        mostrar('Actualizando los datos del tablero… (puede tardar unos minutos)', 'proceso');
        pedir('actualizar_tablero').then(procesar, fallo);
    });

    consultar();
})();
</script>
{/literal}
{/if}
