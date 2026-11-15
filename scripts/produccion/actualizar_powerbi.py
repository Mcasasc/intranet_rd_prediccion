"""
Actualiza el modelo semantico del tablero de Power BI (boton "Actualizar
tablero" de la intranet, pagina alerta_academica/tablero).

Hace lo mismo que el boton "Actualizar ahora" de app.powerbi.com, pero desde
la intranet, usando la API REST de Power BI:
  POST https://api.powerbi.com/v1.0/myorg/datasets/{id}/refreshes
Power BI, a su vez, pide los datos a la puerta de enlace (gateway) instalada
en la maquina que ve la base de datos MySQL.

Uso:
  python actualizar_powerbi.py --login       (UNA sola vez, a mano, en una
                                              terminal: autoriza la conexion
                                              con la cuenta de Power BI)
  python actualizar_powerbi.py --actualizar  (lo llama la intranet con exec())
  python actualizar_powerbi.py --estado      (lo llama la intranet con exec())

Autorizacion: inicio de sesion en el navegador (codigo de autorizacion +
PKCE, redireccion a http://localhost) con una aplicacion registrada en
Microsoft Entra ID (permiso delegado Dataset.ReadWrite.All). El flujo
"device code" se descarto: los valores predeterminados de seguridad del
inquilino lo bloquean (AADSTS530035). Tras el --login se
guarda un token de renovacion en RUTA_TOKEN; cada ejecucion lo renueva sin
pedir contrasena (Microsoft lo invalida si pasa ~90 dias sin usarse; en ese
caso se repite --login). No usa librerias externas (solo urllib), para no
agregar dependencias al servidor.

Configuracion (no secreta) en config_powerbi.json, junto a este script:
  {"tenant_id": "...", "client_id": "...", "dataset_id": "..."}
El token (secreto) va en token_powerbi_NO_COMPARTIR.json, fuera de la
carpeta web de la intranet. Nunca se comparte ni se sube a ningun lado.

Salida: siempre UN JSON por stdout (igual que predecir_curso.py), con
"error" (mensaje para el usuario) y "detalle" (tecnico) si algo falla.
Este script no lee ni escribe la base de datos.
"""
import argparse
import json
import os
import sys
import time
import urllib.error
import urllib.parse
import urllib.request
from datetime import datetime, timezone

sys.stdout.reconfigure(encoding="utf-8")

DIR_SCRIPT = os.path.dirname(os.path.abspath(__file__))
RUTA_CONFIG = os.path.join(DIR_SCRIPT, "config_powerbi.json")
RUTA_TOKEN = os.path.join(DIR_SCRIPT, "token_powerbi_NO_COMPARTIR.json")

ALCANCE = "https://analysis.windows.net/powerbi/api/Dataset.ReadWrite.All offline_access"
API_POWERBI = "https://api.powerbi.com/v1.0/myorg"
TIEMPO_ESPERA = 30  # segundos por llamada HTTP

MSG_NO_CONFIGURADO = "La actualización automática del tablero aún no está configurada."
MSG_REAUTORIZAR = ("La conexión con Power BI necesita volver a autorizarse. "
                   "Avise al administrador del sistema.")
MSG_SIN_CONEXION = "No se pudo conectar con Power BI. Intente nuevamente en unos minutos."


class ErrorTablero(Exception):
    """Error con un mensaje apto para el usuario final y un detalle tecnico."""

    def __init__(self, mensaje, detalle=""):
        super().__init__(mensaje)
        self.mensaje = mensaje
        self.detalle = detalle


# ----------------------------------------------------------------------------
# Utilidades
# ----------------------------------------------------------------------------
def imprimir(datos):
    print(json.dumps(datos, ensure_ascii=False))


def leer_json(ruta):
    with open(ruta, encoding="utf-8") as f:
        return json.load(f)


def guardar_json(ruta, datos):
    # Escritura atomica: si dos clics llegan a la vez, nunca queda un archivo a medias
    temporal = ruta + ".tmp"
    with open(temporal, "w", encoding="utf-8") as f:
        json.dump(datos, f)
    os.replace(temporal, ruta)


def cargar_config():
    if not os.path.exists(RUTA_CONFIG):
        raise ErrorTablero(MSG_NO_CONFIGURADO, f"No existe {RUTA_CONFIG}")
    config = leer_json(RUTA_CONFIG)
    faltan = [c for c in ("tenant_id", "client_id", "dataset_id") if not str(config.get(c, "")).strip()]
    if faltan:
        raise ErrorTablero(MSG_NO_CONFIGURADO, "Faltan en config_powerbi.json: " + ", ".join(faltan))
    return config


def http(metodo, url, datos_form=None, token=None, cuerpo_json=None):
    """Devuelve (codigo_http, cuerpo_dict). No lanza excepcion por codigos 4xx/5xx."""
    cabeceras = {}
    cuerpo = None
    if datos_form is not None:
        cuerpo = urllib.parse.urlencode(datos_form).encode()
        cabeceras["Content-Type"] = "application/x-www-form-urlencoded"
    elif cuerpo_json is not None:
        cuerpo = json.dumps(cuerpo_json).encode()
        cabeceras["Content-Type"] = "application/json"
    if token:
        cabeceras["Authorization"] = "Bearer " + token
    peticion = urllib.request.Request(url, data=cuerpo, headers=cabeceras, method=metodo)
    try:
        with urllib.request.urlopen(peticion, timeout=TIEMPO_ESPERA) as r:
            texto = r.read().decode("utf-8") or "{}"
            return r.status, json.loads(texto)
    except urllib.error.HTTPError as e:
        texto = e.read().decode("utf-8", errors="replace")
        try:
            return e.code, json.loads(texto) if texto else {}
        except ValueError:
            return e.code, {"texto": texto[:500]}
    except (urllib.error.URLError, TimeoutError, OSError) as e:
        raise ErrorTablero(MSG_SIN_CONEXION, str(e))


def url_token(config):
    return f"https://login.microsoftonline.com/{config['tenant_id']}/oauth2/v2.0/token"


def guardar_token(respuesta):
    guardar_json(RUTA_TOKEN, {
        "refresh_token": respuesta["refresh_token"],
        "access_token": respuesta["access_token"],
        "expira": time.time() + int(respuesta.get("expires_in", 3600)) - 120,
    })


def obtener_token(config):
    """Token de acceso vigente; si vencio, lo renueva con el token de renovacion."""
    if not os.path.exists(RUTA_TOKEN):
        raise ErrorTablero(MSG_NO_CONFIGURADO, "Falta autorizar: ejecute actualizar_powerbi.py --login")
    guardado = leer_json(RUTA_TOKEN)
    if guardado.get("access_token") and guardado.get("expira", 0) > time.time():
        return guardado["access_token"]

    codigo, r = http("POST", url_token(config), datos_form={
        "client_id": config["client_id"],
        "grant_type": "refresh_token",
        "refresh_token": guardado.get("refresh_token", ""),
        "scope": ALCANCE,
    })
    if codigo != 200 or "access_token" not in r:
        raise ErrorTablero(MSG_REAUTORIZAR, f"Renovacion de token {codigo}: {r.get('error_description', r)}")
    guardar_token(r)
    return r["access_token"]


def hora_local(iso):
    """'2026-11-11T20:15:03.12Z' -> '11/11/2026 15:15' (hora local del servidor)."""
    if not iso:
        return ""
    try:
        fecha = datetime.fromisoformat(iso.replace("Z", "+00:00"))
        if fecha.tzinfo is None:
            fecha = fecha.replace(tzinfo=timezone.utc)
        return fecha.astimezone().strftime("%d/%m/%Y %H:%M")
    except ValueError:
        return ""


def ultima_actualizacion(config, token):
    url = f"{API_POWERBI}/datasets/{config['dataset_id']}/refreshes?$top=1"
    codigo, r = http("GET", url, token=token)
    if codigo == 401:
        raise ErrorTablero(MSG_REAUTORIZAR, f"API {codigo}: {r}")
    if codigo != 200:
        raise ErrorTablero("No se pudo consultar el estado del tablero.", f"API {codigo}: {r}")
    valores = r.get("value", [])
    return valores[0] if valores else None


def describir(registro):
    """Traduce el registro de Power BI a lo que necesita la intranet."""
    if registro is None:
        return {"estado": "sin_historial", "fin": ""}
    estado_pbi = registro.get("status", "")
    if estado_pbi == "Unknown":  # asi informa Power BI una actualizacion en curso
        return {"estado": "en_curso", "inicio": hora_local(registro.get("startTime")), "fin": ""}
    if estado_pbi == "Completed":
        return {"estado": "completada", "fin": hora_local(registro.get("endTime"))}
    return {
        "estado": "fallida",
        "fin": hora_local(registro.get("endTime")),
        "detalle": registro.get("serviceExceptionJson", estado_pbi),
    }


# ----------------------------------------------------------------------------
# Acciones
# ----------------------------------------------------------------------------
def accion_estado(config):
    token = obtener_token(config)
    return describir(ultima_actualizacion(config, token))


def accion_actualizar(config):
    token = obtener_token(config)
    # Si ya hay una en curso (otro usuario la inicio), no se pide otra: solo se informa
    actual = describir(ultima_actualizacion(config, token))
    if actual["estado"] == "en_curso":
        actual["ya_estaba_en_curso"] = True
        return actual

    url = f"{API_POWERBI}/datasets/{config['dataset_id']}/refreshes"
    codigo, r = http("POST", url, token=token, cuerpo_json={"notifyOption": "NoNotification"})
    if codigo == 202:
        return {"estado": "en_curso", "inicio": datetime.now().strftime("%d/%m/%Y %H:%M"), "fin": ""}
    texto = json.dumps(r, ensure_ascii=False)
    if codigo == 429 or "limit" in texto.lower():
        raise ErrorTablero("Se alcanzó el límite de actualizaciones del tablero por hoy. "
                           "Se actualizará automáticamente en el próximo horario programado.", texto)
    if codigo == 401:
        raise ErrorTablero(MSG_REAUTORIZAR, texto)
    raise ErrorTablero("No se pudo iniciar la actualización del tablero.", f"API {codigo}: {texto}")


def accion_login(config):
    """Inicio de sesion en el navegador (codigo de autorizacion + PKCE), UNA vez, a mano.

    Se usa en vez del flujo "device code" porque los valores predeterminados de
    seguridad de Entra ID lo bloquean (error AADSTS530035). Requiere en el
    registro de la app la plataforma "Aplicaciones moviles y de escritorio"
    con el URI de redireccion http://localhost (Entra acepta cualquier puerto).
    """
    import base64
    import hashlib
    import secrets
    from http.server import BaseHTTPRequestHandler, HTTPServer  # no "import http": taparia la funcion http()
    import webbrowser

    base = f"https://login.microsoftonline.com/{config['tenant_id']}/oauth2/v2.0"
    verificador = secrets.token_urlsafe(64)
    desafio = base64.urlsafe_b64encode(hashlib.sha256(verificador.encode()).digest()).rstrip(b"=").decode()
    estado_oauth = secrets.token_urlsafe(16)
    recibido = {}

    class Receptor(BaseHTTPRequestHandler):
        def do_GET(self):
            consulta = urllib.parse.parse_qs(urllib.parse.urlparse(self.path).query)
            if "code" in consulta or "error" in consulta:
                recibido.update({k: v[0] for k, v in consulta.items()})
                texto = "Listo. Ya puede cerrar esta ventana y volver a la terminal."
            else:
                texto = "Esperando la respuesta de Microsoft..."
            self.send_response(200)
            self.send_header("Content-Type", "text/html; charset=utf-8")
            self.end_headers()
            self.wfile.write(f"<p style='font-family:sans-serif'>{texto}</p>".encode("utf-8"))

        def log_message(self, *args):
            pass

    servidor = HTTPServer(("127.0.0.1", 0), Receptor)
    servidor.timeout = 5
    redireccion = f"http://localhost:{servidor.server_address[1]}"
    url = base + "/authorize?" + urllib.parse.urlencode({
        "client_id": config["client_id"], "response_type": "code", "redirect_uri": redireccion,
        "scope": ALCANCE, "state": estado_oauth, "code_challenge": desafio,
        "code_challenge_method": "S256", "prompt": "select_account",
    })
    # Unico caso en que se imprime texto para una persona (no lo llama la intranet)
    print("Se abrira el navegador para iniciar sesion con la cuenta de Power BI.\n"
          "Si no se abre, copie esta direccion en el navegador:\n" + url, file=sys.stderr)
    webbrowser.open(url)

    limite = time.time() + 600
    while not recibido and time.time() < limite:
        servidor.handle_request()
    servidor.server_close()
    if not recibido:
        raise ErrorTablero("Se agotó el tiempo para autorizar. Ejecute --login de nuevo.")
    if "error" in recibido:
        raise ErrorTablero("La autorización fue rechazada o falló.",
                           f"{recibido['error']}: {recibido.get('error_description', '')}")
    if recibido.get("state") != estado_oauth:
        raise ErrorTablero("La autorización fue rechazada o falló.", "state no coincide")

    codigo, t = http("POST", base + "/token", datos_form={
        "client_id": config["client_id"], "grant_type": "authorization_code", "code": recibido["code"],
        "redirect_uri": redireccion, "code_verifier": verificador, "scope": ALCANCE,
    })
    if codigo == 200 and "access_token" in t and "refresh_token" in t:
        guardar_token(t)
        return {"ok": True, "mensaje": "Autorizado. Token guardado en " + RUTA_TOKEN}
    raise ErrorTablero("La autorización fue rechazada o falló.",
                       f"{codigo}: {t.get('error', '')}: {t.get('error_description', t)}")


def main():
    parser = argparse.ArgumentParser(description="Actualiza el tablero de Power BI de alertas academicas.")
    grupo = parser.add_mutually_exclusive_group(required=True)
    grupo.add_argument("--login", action="store_true", help="Autorizar la conexion (una sola vez, a mano).")
    grupo.add_argument("--actualizar", action="store_true", help="Iniciar la actualizacion del modelo.")
    grupo.add_argument("--estado", action="store_true", help="Consultar la ultima actualizacion.")
    args = parser.parse_args()

    try:
        config = cargar_config()
        if args.login:
            imprimir(accion_login(config))
        elif args.actualizar:
            imprimir(accion_actualizar(config))
        else:
            imprimir(accion_estado(config))
    except ErrorTablero as e:
        imprimir({"error": e.mensaje, "detalle": e.detalle})
    except Exception as e:  # nunca devolver una traza sin JSON a la intranet
        imprimir({"error": "No se pudo actualizar el tablero.", "detalle": f"{type(e).__name__}: {e}"})


if __name__ == "__main__":
    main()
