"""
BORME scraper: busca en el Boletin Oficial del Registro Mercantil (BORME) las
sociedades de nueva constitucion de una provincia, en la fecha indicada, y
para cada una intenta averiguar su web y email de contacto usando el API de
Claude (Anthropic) con la herramienta de busqueda web.

Se lanza como proceso independiente desde
admin/controller/tool/borme.php::run() (popen + start /B en Windows), para no
bloquear la peticion HTTP de Apache/PHP mientras dura el proceso.

Uso:
    python borme_scraper.py --province "ZARAGOZA" --max-emails 5 [--date YYYYMMDD] [--max-attempts 60]

Variables de entorno requeridas:
    DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_PREFIX
    CLAUDE_API_KEY
    STATUS_FILE   ruta al fichero JSON de estado que lee el panel para el polling
"""

import argparse
import html
import json
import os
import re
import sys
import time
import unicodedata
import urllib.error
import urllib.parse
import urllib.request
import xml.etree.ElementTree as ET
from datetime import datetime, timedelta

import pymysql

BOE_SUMARIO_URL = "https://www.boe.es/datosabiertos/api/borme/sumario/{fecha}"
ANTHROPIC_URL = "https://api.anthropic.com/v1/messages"
ANTHROPIC_MODEL = "claude-opus-4-8"
ANTHROPIC_VERSION = "2023-06-01"
USER_AGENT = "InvoiceFlash-BormeScraper/1.0"


def log(msg):
    print("[%s] %s" % (datetime.now().strftime("%H:%M:%S"), msg), flush=True)


def normalize(text):
    text = unicodedata.normalize("NFKD", text or "")
    text = "".join(c for c in text if not unicodedata.combining(c))
    return text.strip().upper()


def http_get(url, accept="application/xml", timeout=30):
    req = urllib.request.Request(url, headers={"Accept": accept, "User-Agent": USER_AGENT})
    with urllib.request.urlopen(req, timeout=timeout) as resp:
        return resp.read()


class StatusWriter:
    def __init__(self, path):
        self.path = path
        self.data = {
            "running": True,
            "started_at": datetime.now().isoformat(),
            "finished_at": None,
            "date_requested": None,
            "date_used": None,
            "province": None,
            "scanned": 0,
            "found_emails": 0,
            "target_emails": 0,
            "message": "Iniciando...",
            "error": None,
        }
        self.write()

    def update(self, **kwargs):
        self.data.update(kwargs)
        self.write()

    def write(self):
        tmp = self.path + ".tmp"
        with open(tmp, "w", encoding="utf-8") as f:
            json.dump(self.data, f, ensure_ascii=False)
        os.replace(tmp, self.path)

    def finish(self, message, error=None):
        self.update(running=False, finished_at=datetime.now().isoformat(), message=message, error=error)


def fetch_sumario(date_str):
    """Devuelve el arbol XML del sumario de ese dia, o None si no hubo boletin."""
    raw = http_get(BOE_SUMARIO_URL.format(fecha=date_str))
    root = ET.fromstring(raw)
    code = root.findtext("./status/code")
    if code != "200":
        return None
    return root


def find_province_item(sumario_root, province_wanted):
    wanted = normalize(province_wanted)
    seccion = sumario_root.find(".//seccion[@codigo='A']")
    if seccion is None:
        return None
    for item in seccion.findall("item"):
        titulo = item.findtext("titulo") or ""
        if normalize(titulo) == wanted or wanted in normalize(titulo):
            url_xml = item.findtext("url_xml")
            identificador = item.findtext("identificador")
            if url_xml:
                return {"identificador": identificador, "url_xml": url_xml, "titulo": titulo}
    return None


def extract_constitutions(doc_root, doc_identificador):
    """Recorre <texto> emparejando cada <p class="articulo"> con el <p class="parrafo">
    que le sigue, y se queda solo con los actos de Constitucion."""
    texto = doc_root.find("texto")
    if texto is None:
        return []

    companies = []
    children = list(texto)
    index = 0
    for i, el in enumerate(children):
        if el.get("class") != "articulo":
            continue
        parrafo_el = children[i + 1] if i + 1 < len(children) else None
        if parrafo_el is None or parrafo_el.get("class") != "parrafo":
            continue

        parrafo_text = "".join(parrafo_el.itertext())
        if not parrafo_text.strip().startswith("Constitución"):
            continue

        articulo_text = "".join(el.itertext())
        m = re.match(r"^\s*\d+\s*-\s*(.+?)\.?\s*$", articulo_text.strip())
        company_name = m.group(1).strip() if m else articulo_text.strip().rstrip(".")

        city_m = re.search(r"Domicilio:.*?\(([^)]+)\)", parrafo_text)
        capital_m = re.search(r"Capital:\s*([\d.,]+)\s*Euros", parrafo_text)

        index += 1
        companies.append({
            "identifier": "%s#%d" % (doc_identificador, index),
            "company_name": company_name,
            "city": city_m.group(1).strip() if city_m else None,
            "capital": capital_m.group(1).strip() if capital_m else None,
        })

    return companies


def ask_claude_for_contact(api_key, company_name, city):
    where = (" con domicilio en %s" % city) if city else ""
    system_prompt = (
        "Eres un asistente que localiza informacion publica de contacto de empresas espanolas "
        "recien constituidas, usando la herramienta de busqueda web. Busca la pagina web oficial "
        "de la empresa y, si la encuentras, un email de contacto publico (de la propia web, footer, "
        "pagina de contacto, o aviso legal). No inventes datos: si no encuentras algo con certeza "
        "razonable, devuelve null en ese campo. IMPORTANTE: evalua 'website' y 'email' de forma "
        "COMPLETAMENTE INDEPENDIENTE uno del otro - encontrar la web pero no el email es un resultado "
        "valido y frecuente, en ese caso devuelve el 'website' que hayas encontrado con normalidad y "
        "SOLO 'email' en null; nunca pongas 'website' en null solo porque no encontraste el email. "
        "Responde UNICAMENTE con un objeto JSON, sin texto adicional ni bloques de codigo, con esta "
        "forma exacta: "
        '{"website": "https://... o null", "email": "correo@dominio.com o null"}'
    )
    user_prompt = "Empresa: %s%s. Es una sociedad de nueva constitucion segun el BORME." % (company_name, where)

    payload = {
        "model": ANTHROPIC_MODEL,
        "max_tokens": 1024,
        "system": system_prompt,
        "tools": [{"type": "web_search_20250305", "name": "web_search", "max_uses": 3}],
        "messages": [{"role": "user", "content": user_prompt}],
    }

    req = urllib.request.Request(
        ANTHROPIC_URL,
        data=json.dumps(payload).encode("utf-8"),
        headers={
            "Content-Type": "application/json",
            "x-api-key": api_key,
            "anthropic-version": ANTHROPIC_VERSION,
        },
        method="POST",
    )

    with urllib.request.urlopen(req, timeout=120) as resp:
        body = json.loads(resp.read().decode("utf-8"))

    text_out = ""
    for block in body.get("content", []):
        if block.get("type") == "text":
            text_out = block.get("text", "")

    cleaned = re.sub(r"^```(json)?|```$", "", text_out.strip(), flags=re.MULTILINE).strip()

    try:
        parsed = json.loads(cleaned)
    except (ValueError, TypeError):
        return None, None

    website = parsed.get("website") or None
    email = parsed.get("email") or None
    if website in ("null", ""):
        website = None
    if email in ("null", ""):
        email = None
    return website, email


EMAIL_RE = re.compile(r"[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}")
MAILTO_RE = re.compile(r"mailto:([^\"'\s?>]+)", re.IGNORECASE)
CF_EMAIL_RE = re.compile(r'data-cfemail="([0-9a-fA-F]+)"')

# dominios que aparecen en botones de compartir, trackers, plantillas, iconos de fuente, etc.
# y que casi nunca son el email de contacto real de la empresa
EMAIL_DOMAIN_BLOCKLIST = (
    "sentry.io", "wixpress.com", "example.com", "yourdomain.com", "domain.com",
    "godaddy.com", "gstatic.com", "google.com", "googlemail.com", "schema.org",
    "w3.org", "cloudflare.com", "wordpress.org", "wordpress.com", "gravatar.com",
    "fontawesome.com", "sentry-next.wixpress.com",
)


def decode_cf_email(hexstr):
    """Decodea el email ofuscado que usa el 'Email Protection' de Cloudflare
    (data-cfemail="...") - XOR de cada byte contra el primer byte, en hex."""
    try:
        raw = bytes.fromhex(hexstr)
    except ValueError:
        return None
    if len(raw) < 2:
        return None
    key = raw[0]
    decoded = bytes(b ^ key for b in raw[1:])
    try:
        return decoded.decode("utf-8")
    except UnicodeDecodeError:
        return None


def extract_email_from_html(html_text):
    """Busca un email de contacto en el HTML real de una pagina, en orden de fiabilidad:
    1) emails ofuscados por Cloudflare (data-cfemail), 2) enlaces mailto:, 3) texto plano."""
    for m in CF_EMAIL_RE.findall(html_text):
        decoded = decode_cf_email(m)
        if decoded and EMAIL_RE.fullmatch(decoded):
            return decoded

    for m in MAILTO_RE.findall(html_text):
        candidate = urllib.parse.unquote(m).split("?")[0].strip()
        if EMAIL_RE.fullmatch(candidate) and not candidate.lower().endswith(EMAIL_DOMAIN_BLOCKLIST):
            return candidate

    unescaped = html.unescape(html_text)
    for candidate in EMAIL_RE.findall(unescaped):
        if not candidate.lower().endswith(EMAIL_DOMAIN_BLOCKLIST) and not candidate.lower().endswith((".png", ".jpg", ".gif", ".svg")):
            return candidate

    return None


def find_email_on_website(website):
    """Visita la home y unas pocas paginas tipicas (contacto/aviso legal) del propio
    dominio y busca un email real en el HTML, sin depender de lo que haya indexado
    un buscador. Devuelve (email, url_donde_se_encontro) o (None, None)."""
    parsed = urllib.parse.urlparse(website if "://" in website else "https://" + website)
    base = "%s://%s" % (parsed.scheme or "https", parsed.netloc)

    candidate_paths = [
        "", "/contacto", "/contact", "/contact-us", "/aviso-legal", "/legal",
        "/politica-de-privacidad", "/privacidad", "/sobre-nosotros", "/quienes-somos", "/about",
    ]

    for path in candidate_paths:
        url = base + path
        try:
            raw = http_get(url, accept="text/html", timeout=8)
        except (urllib.error.URLError, TimeoutError, OSError):
            continue

        try:
            html_text = raw.decode("utf-8", errors="ignore")
        except Exception:
            continue

        email = extract_email_from_html(html_text)
        if email:
            return email, url

    return None, None


def db_connect():
    return pymysql.connect(
        host=os.environ["DB_HOST"],
        user=os.environ["DB_USER"],
        password=os.environ.get("DB_PASS", ""),
        database=os.environ["DB_NAME"],
        port=int(os.environ.get("DB_PORT", "3306")),
        charset="utf8mb4",
        autocommit=True,
    )


def already_processed(conn, prefix, identifier):
    with conn.cursor() as cur:
        cur.execute("SELECT status FROM `%sborme` WHERE identifier = %%s" % prefix, (identifier,))
        row = cur.fetchone()
        return row[0] if row else None


def insert_result(conn, prefix, borme_date, province, company):
    with conn.cursor() as cur:
        cur.execute(
            "INSERT INTO `%sborme` "
            "(borme_date, province, identifier, company_name, city, capital, website, email, status, date_added) "
            "VALUES (%%s,%%s,%%s,%%s,%%s,%%s,%%s,%%s,%%s, NOW()) "
            "ON DUPLICATE KEY UPDATE website=VALUES(website), email=VALUES(email), status=VALUES(status)" % prefix,
            (
                borme_date, province, company["identifier"], company["company_name"],
                company.get("city"), company.get("capital"), company.get("website"),
                company.get("email"), company["status"],
            ),
        )


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--province", required=True)
    parser.add_argument("--max-emails", type=int, default=5)
    parser.add_argument("--max-attempts", type=int, default=60)
    parser.add_argument("--date", default=None, help="YYYYMMDD, por defecto hoy-7dias")
    args = parser.parse_args()

    status_path = os.environ["STATUS_FILE"]
    status = StatusWriter(status_path)
    status.update(province=args.province, target_emails=args.max_emails)

    db_prefix = os.environ.get("DB_PREFIX", "")
    api_key = os.environ.get("CLAUDE_API_KEY", "")

    if not api_key:
        status.finish("Error: no hay API key de Claude configurada (Ajustes > IA).", error="missing_api_key")
        return

    if args.date:
        target_date = datetime.strptime(args.date, "%Y%m%d")
    else:
        target_date = datetime.now() - timedelta(days=7)

    status.update(date_requested=target_date.strftime("%Y-%m-%d"))

    sumario_root = None
    used_date = target_date
    for attempt in range(7):
        date_str = used_date.strftime("%Y%m%d")
        status.update(message="Consultando BORME del %s..." % used_date.strftime("%d-%m-%Y"))
        try:
            sumario_root = fetch_sumario(date_str)
        except (urllib.error.URLError, ET.ParseError) as e:
            status.finish("Error consultando BOE: %s" % e, error="boe_fetch_error")
            return
        if sumario_root is not None:
            break
        used_date -= timedelta(days=1)

    if sumario_root is None:
        status.finish("No se encontro ningun BORME publicado en los 7 dias anteriores a la fecha objetivo.", error="no_bulletin")
        return

    status.update(date_used=used_date.strftime("%Y-%m-%d"))

    item = find_province_item(sumario_root, args.province)
    if item is None:
        status.finish(
            "El BORME del %s no trae Seccion I para '%s' (puede que ese dia no hubiera actos inscritos en esa provincia)."
            % (used_date.strftime("%d-%m-%Y"), args.province),
            error=None,
        )
        return

    status.update(message="Descargando boletin de %s..." % item["titulo"])
    try:
        doc_raw = http_get(item["url_xml"])
        doc_root = ET.fromstring(doc_raw)
    except (urllib.error.URLError, ET.ParseError) as e:
        status.finish("Error descargando el boletin provincial: %s" % e, error="boe_fetch_error")
        return

    companies = extract_constitutions(doc_root, item["identificador"])
    status.update(message="%d constituciones encontradas en %s. Buscando webs/emails..." % (len(companies), item["titulo"]))

    conn = db_connect()
    borme_date_sql = used_date.strftime("%Y-%m-%d")
    scanned = 0
    found_emails = 0

    for company in companies:
        if scanned >= args.max_attempts or found_emails >= args.max_emails:
            break

        existing_status = already_processed(conn, db_prefix, company["identifier"])
        if existing_status is not None:
            continue

        scanned += 1
        status.update(scanned=scanned, message="Buscando contacto de: %s" % company["company_name"])

        try:
            website, claude_email = ask_claude_for_contact(api_key, company["company_name"], company.get("city"))
        except Exception as e:
            log("Error consultando Claude para %s: %s" % (company["company_name"], e))
            website, claude_email = None, None

        email = None
        if website:
            try:
                status.update(message="Rastreando web de: %s" % company["company_name"])
                crawled_email, found_at = find_email_on_website(website)
                if crawled_email:
                    log("Email encontrado en %s: %s" % (found_at, crawled_email))
                    email = crawled_email
            except Exception as e:
                log("Error rastreando %s: %s" % (website, e))

        if not email:
            email = claude_email

        company["website"] = website
        company["email"] = email
        company["status"] = "found" if email else "not_found"

        insert_result(conn, db_prefix, borme_date_sql, item["titulo"], company)

        if email:
            found_emails += 1
            status.update(found_emails=found_emails)

        time.sleep(1)

    conn.close()
    status.finish(
        "Terminado. %d empresas revisadas, %d emails encontrados (boletin del %s)."
        % (scanned, found_emails, used_date.strftime("%d-%m-%Y"))
    )


if __name__ == "__main__":
    try:
        main()
    except Exception as e:
        try:
            status_path = os.environ.get("STATUS_FILE")
            if status_path:
                with open(status_path, "w", encoding="utf-8") as f:
                    json.dump({"running": False, "error": "fatal: %s" % e, "message": "Fallo inesperado: %s" % e}, f)
        finally:
            raise
