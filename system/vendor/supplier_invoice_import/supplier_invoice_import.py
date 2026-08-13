#!/usr/bin/env python3
"""
Revisa por POP3 el buzón configurado en Ajustes > Mail (config_supplier_invoice_email)
cada vez que lo lanza el cron `supplier_invoice_import` (ver
system/vendor/cron/supplier_invoice_import.php, cycle=720 minutos = 12h), y por
cada mensaje que sigue en el buzón:

  - Si no tiene ningún adjunto PDF/JPG/PNG/XML soportado -> se borra del buzón.
  - Si el adjunto es un PDF con texto embebido (no escaneado) -> se extrae el
    texto directamente (PyMuPDF, gratis/instantáneo/exacto) y se le pide a un
    modelo de texto de Ollama que lo estructure en JSON.
  - Si es un PDF escaneado (sin capa de texto) o una imagen JPG/PNG -> se
    convierte a imagen y se usa el modelo de visión Qwen2.5-VL (Ollama) para
    extraer los datos directamente de la imagen.
  - Si es un XML de Facturae -> se parsea de forma determinista (sin IA).
  - Si se crea la factura correctamente -> se borra del buzón (POP3 DELE).
  - Si falla, se deja en el buzón para reintentar en el siguiente ciclo del
    cron (hasta MAX_ATTEMPTS veces); al agotar los intentos se guarda una
    copia del email en disco (ATTACHMENT_DIR/_errores/) y se borra del buzón.

Se usa POP3 (no IMAP) a propósito: no depende del flag \\Seen del servidor
(cualquiera que abra el buzón por webmail lo marca leído sin que nos enteremos)
ni de mover mensajes entre carpetas — el propio UIDL de POP3 (estable e
independiente del estado de lectura) es la clave de idempotencia, cruzada
contra nuestra tabla `purchase_invoice_import_log`.

Con los datos extraídos: crea el proveedor en BD si no existe (por tax_id) y
crea la factura de proveedor (purchase_invoice + purchase_invoice_product +
purchase_invoice_total), replicando el mismo formato que genera
ModelPurchaseInvoice::addInvoice() en admin/model/purchase/invoice.php.

Variables de entorno esperadas (las pone system/vendor/cron/supplier_invoice_import.php):
  DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_PREFIX
  POP_HOST, POP_PORT, POP_SSL, POP_EMAIL, POP_PASSWORD
  OLLAMA_URL, ATTACHMENT_DIR, STATUS_FILE
"""

import base64
import email
import html
import io
import json
import os
import poplib
import re
import sys
import unicodedata
import urllib.error
import urllib.request
import xml.etree.ElementTree as ET
from datetime import datetime
from email.header import decode_header

import pymysql

try:
    import fitz  # PyMuPDF
except ImportError:
    fitz = None

try:
    from PIL import Image
except ImportError:
    Image = None

OLLAMA_TEXT_MODEL = os.environ.get("OLLAMA_TEXT_MODEL", "qwen3:1.7b")
OLLAMA_VISION_MODEL = os.environ.get("OLLAMA_VISION_MODEL", "qwen2.5vl:3b")
OLLAMA_URL = os.environ.get("OLLAMA_URL", "http://127.0.0.1:11434/api/chat")
OLLAMA_TIMEOUT = 180

SUPPORTED_EXTENSIONS = (".pdf", ".jpg", ".jpeg", ".png", ".xml")
MAX_PDF_PAGES = 3
MAX_IMAGE_DIMENSION = 1600
MAX_ATTEMPTS = 3

STATUS_FILE = os.environ.get("STATUS_FILE", "")


# ---------------------------------------------------------------------------
# Estado (para depuración / consulta manual, no hay UI de progreso para esto)
# ---------------------------------------------------------------------------

class StatusWriter:
    def __init__(self, path):
        self.path = path

    def write(self, **kwargs):
        if not self.path:
            return
        data = kwargs
        tmp = self.path + ".tmp"
        try:
            with open(tmp, "w", encoding="utf-8") as f:
                json.dump(data, f, ensure_ascii=False, indent=2)
            os.replace(tmp, self.path)
        except OSError:
            pass


status = StatusWriter(STATUS_FILE)


def log(msg):
    print("[{}] {}".format(datetime.now().strftime("%Y-%m-%d %H:%M:%S"), msg))


# ---------------------------------------------------------------------------
# Base de datos
# ---------------------------------------------------------------------------

def db_connect():
    return pymysql.connect(
        host=os.environ["DB_HOST"],
        user=os.environ["DB_USER"],
        password=os.environ.get("DB_PASS", ""),
        database=os.environ["DB_NAME"],
        port=int(os.environ.get("DB_PORT", "3306")),
        charset="utf8",
        autocommit=True,
        cursorclass=pymysql.cursors.DictCursor,
    )


def db_prefix():
    return os.environ.get("DB_PREFIX", "")


# ---------------------------------------------------------------------------
# Utilidades de texto / normalización
# ---------------------------------------------------------------------------

def strip_accents(text):
    if not text:
        return ""
    normalized = unicodedata.normalize("NFKD", text)
    return "".join(c for c in normalized if not unicodedata.combining(c))


def normalize_key(text):
    return strip_accents(html.unescape(text or "")).strip().upper()


def clean_tax_id(text):
    if not text:
        return ""
    return re.sub(r"[^A-Za-z0-9]", "", text).upper()


def decode_mime_header(value):
    if not value:
        return ""
    parts = decode_header(value)
    out = []
    for text, charset in parts:
        if isinstance(text, bytes):
            try:
                out.append(text.decode(charset or "utf-8", errors="replace"))
            except (LookupError, UnicodeDecodeError):
                out.append(text.decode("utf-8", errors="replace"))
        else:
            out.append(text)
    return "".join(out)


def strip_json_fences(text):
    text = text.strip()
    match = re.search(r"```(?:json)?\s*(.*?)```", text, re.DOTALL)
    if match:
        return match.group(1).strip()
    return text


def parse_float(value, default=0.0):
    """Admite tanto '1234.56' (JSON/inglés) como '1.234,56' (formato español:
    punto de miles, coma decimal) y '1,234.56' (inglés con miles). El separador
    decimal es el que aparece más a la derecha; el otro se trata como miles."""
    if value is None:
        return default
    if isinstance(value, (int, float)):
        return float(value)

    text = re.sub(r"[^0-9,.\-]", "", str(value))
    if not text or text in ("-", ".", ","):
        return default

    last_comma = text.rfind(",")
    last_dot = text.rfind(".")

    if last_comma != -1 and last_dot != -1:
        if last_comma > last_dot:
            text = text.replace(".", "").replace(",", ".")
        else:
            text = text.replace(",", "")
    elif last_comma != -1:
        # solo coma -> formato español, es el separador decimal
        text = text.replace(".", "").replace(",", ".")
    # solo punto (o ninguno): ya es un separador decimal válido, se deja tal cual

    try:
        return float(text) if text not in ("", "-", ".") else default
    except ValueError:
        return default


def parse_date(value):
    if not value:
        return None
    value = str(value).strip()
    for fmt in ("%Y-%m-%d", "%d-%m-%Y", "%d/%m/%Y", "%d.%m.%Y", "%Y/%m/%d"):
        try:
            return datetime.strptime(value, fmt).strftime("%Y-%m-%d")
        except ValueError:
            continue
    return None


# ---------------------------------------------------------------------------
# POP3
# ---------------------------------------------------------------------------

def pop3_connect():
    host = os.environ.get("POP_HOST", "")
    port = int(os.environ.get("POP_PORT", "995") or "995")
    use_ssl = os.environ.get("POP_SSL", "1") != "0"
    user = os.environ.get("POP_EMAIL", "")
    password = os.environ.get("POP_PASSWORD", "")

    if use_ssl:
        conn = poplib.POP3_SSL(host, port, timeout=60)
    else:
        conn = poplib.POP3(host, port, timeout=60)

    conn.user(user)
    conn.pass_(password)
    return conn


def list_uidls(pop):
    """[(msgnum, uidl), ...] para todos los mensajes que siguen en el buzón."""
    resp, lines, octets = pop.uidl()
    entries = []
    for line in lines:
        parts = line.decode(errors="replace").split(" ", 1)
        if len(parts) == 2:
            entries.append((int(parts[0]), parts[1].strip()))
    return entries


def fetch_message(pop, msgnum):
    resp, lines, octets = pop.retr(msgnum)
    return b"\r\n".join(lines)


def save_failed_email(raw_bytes, uidl):
    """Copia local del mensaje original antes de borrarlo del buzón al agotar
    los reintentos — sin esto, al no haber carpeta 'Errores' en el servidor
    (POP3 no tiene carpetas), se perdería el único rastro del documento que
    nunca llegó a convertirse en adjunto guardado."""
    attach_dir = os.environ.get("ATTACHMENT_DIR", "")
    if not attach_dir:
        return ""
    subdir = os.path.join(attach_dir, "_errores", datetime.now().strftime("%Y-%m"))
    os.makedirs(subdir, exist_ok=True)
    safe_uidl = re.sub(r"[^A-Za-z0-9_.\-]", "_", uidl)[:100] or "mensaje"
    path = os.path.join(subdir, safe_uidl + ".eml")
    with open(path, "wb") as f:
        f.write(raw_bytes)
    return path


def extract_attachments(msg):
    """Devuelve lista de (filename, extension_lower, bytes) para adjuntos soportados."""
    attachments = []
    for part in msg.walk():
        if part.get_content_maintype() == "multipart":
            continue
        disposition = str(part.get("Content-Disposition") or "")
        filename = part.get_filename()
        if filename:
            filename = decode_mime_header(filename)
        if not filename and "attachment" not in disposition.lower():
            continue
        if not filename:
            continue
        ext = os.path.splitext(filename)[1].lower()
        if ext not in SUPPORTED_EXTENSIONS:
            continue
        payload = part.get_payload(decode=True)
        if not payload:
            continue
        attachments.append((filename, ext, payload))
    return attachments


# ---------------------------------------------------------------------------
# Extracción de PDF (texto embebido vs escaneado)
# ---------------------------------------------------------------------------

def analyze_pdf(data):
    """Devuelve {'text': str} si el PDF trae texto embebido, o
    {'images': [png_bytes, ...]} si hay que pasarlo por el modelo de visión."""
    if fitz is None:
        raise RuntimeError("PyMuPDF (fitz) no está instalado")

    doc = fitz.open(stream=data, filetype="pdf")
    text_parts = []
    for page in doc:
        text_parts.append(page.get_text())
    text = "\n".join(text_parts).strip()

    if text:
        doc.close()
        return {"text": text}

    images = []
    for i, page in enumerate(doc):
        if i >= MAX_PDF_PAGES:
            break
        pix = page.get_pixmap(matrix=fitz.Matrix(2, 2))
        images.append(pix.tobytes("png"))
    doc.close()
    return {"images": images}


def prepare_image_bytes(raw_bytes):
    """Redimensiona a un máximo razonable para no mandar imágenes gigantes a Ollama."""
    if Image is None:
        return raw_bytes
    try:
        img = Image.open(io.BytesIO(raw_bytes))
        img.load()
        if img.mode not in ("RGB", "L"):
            img = img.convert("RGB")
        if max(img.size) > MAX_IMAGE_DIMENSION:
            ratio = MAX_IMAGE_DIMENSION / float(max(img.size))
            new_size = (int(img.size[0] * ratio), int(img.size[1] * ratio))
            img = img.resize(new_size)
        buf = io.BytesIO()
        img.save(buf, format="PNG")
        return buf.getvalue()
    except Exception:
        return raw_bytes


# ---------------------------------------------------------------------------
# Ollama
# ---------------------------------------------------------------------------

EXTRACTION_SCHEMA_PROMPT = """Eres un extractor de datos de facturas de proveedores. Analiza el documento (texto o imagen) y devuelve EXCLUSIVAMENTE un objeto JSON válido, sin explicaciones, sin markdown, sin ```json, con exactamente esta forma:

{
  "supplier": {
    "company": "razón social del EMISOR/VENDEDOR de la factura (no la nuestra)",
    "tax_id": "NIF/CIF del emisor",
    "address": "calle y número",
    "city": "ciudad",
    "postcode": "código postal",
    "province": "provincia",
    "country": "país (si no aparece, asume España)",
    "email": "email de contacto si aparece, si no cadena vacía",
    "phone": "teléfono si aparece, si no cadena vacía"
  },
  "invoice": {
    "number": "número de factura tal cual aparece impreso",
    "date": "fecha de emisión en formato YYYY-MM-DD",
    "currency": "código de moneda ISO, por defecto EUR",
    "lines": [
      {
        "description": "descripción del concepto/línea",
        "quantity": "cantidad, TAL CUAL aparece impresa (texto, no la conviertas)",
        "unit_price": "precio unitario, TAL CUAL aparece impreso (texto, no la conviertas)",
        "discount_percent": "porcentaje de descuento de esa línea, TAL CUAL aparece impreso (texto, 0 si no hay)",
        "tax_rate": "porcentaje de IVA de esa línea, TAL CUAL aparece impreso (texto, p.ej. 21)"
      }
    ],
    "subtotal": "base imponible total, TAL CUAL aparece impresa (texto, no la conviertas)",
    "tax_total": "importe total de IVA, TAL CUAL aparece impreso (texto, no lo conviertas)",
    "total": "importe total de la factura, TAL CUAL aparece impreso (texto, no lo conviertas)"
  }
}

Reglas importantes:
- "supplier" es SIEMPRE quien EMITE/VENDE la factura (el proveedor), nunca la empresa que la RECIBE.
- La empresa que emite normalmente aparece en el membrete/cabecera del documento, a menudo junto a su logo. La
  empresa que recibe suele aparecer en una casilla aparte tipo "Cliente", "Facturar a", "Datos del cliente" o
  "Destinatario" — esa NUNCA es "supplier", aunque aparezca primero o más grande en la página.
- Si no encuentras un dato, usa cadena vacía "" (o "0" para números), nunca inventes datos.
- MUY IMPORTANTE sobre los números (quantity, unit_price, discount_percent, tax_rate, subtotal, tax_total,
  total): transcríbelos EXACTAMENTE como aparecen impresos en el documento, como texto, sin reformatearlos ni
  convertirlos tú de ninguna manera — ni cambies comas por puntos, ni quites separadores de miles, ni redondees.
  Otro programa se encarga de interpretarlos correctamente después. Ejemplos de transcripción correcta:
  "5,606" en el documento -> "5,606" en el JSON (NUNCA "5606" ni "5606.00"); "1.234,56" en el documento ->
  "1.234,56" en el JSON (NUNCA "1234.56" ni "1234,56"). Si el documento ya usa punto decimal ("45.00"),
  transcríbelo igual, "45.00".
- Si la factura no desglosa líneas, crea una única línea con toda la base imponible.
- Responde solo con el JSON, nada más."""


def build_extraction_prompt(own_company_name, own_tax_id):
    if not own_company_name:
        return EXTRACTION_SCHEMA_PROMPT

    own_tax_suffix = " (NIF/CIF {})".format(own_tax_id) if own_tax_id else ""
    warning = (
        "\n\nATENCIÓN: la empresa que RECIBE esta factura (nuestra propia empresa, la clienta — NUNCA el "
        "proveedor) es \"{}\"{}. Si ese nombre o ese NIF aparece en el documento, es el CLIENTE, no el "
        "proveedor. El campo \"supplier\" NUNCA puede ser \"{}\" — si el único emisor que identificas coincide "
        "con este nombre, vuelve a mirar el documento: el proveedor real es la OTRA empresa que aparece en él."
    ).format(own_company_name, own_tax_suffix, own_company_name)

    return EXTRACTION_SCHEMA_PROMPT + warning


def ollama_chat(payload):
    data = json.dumps(payload).encode("utf-8")
    req = urllib.request.Request(
        OLLAMA_URL,
        data=data,
        headers={"Content-Type": "application/json"},
        method="POST",
    )
    with urllib.request.urlopen(req, timeout=OLLAMA_TIMEOUT) as resp:
        body = json.loads(resp.read().decode("utf-8"))
    return body.get("message", {}).get("content", "")


def extract_with_text_model(text, own_company_name="", own_tax_id=""):
    payload = {
        "model": OLLAMA_TEXT_MODEL,
        "stream": False,
        "think": True,
        "messages": [
            {"role": "system", "content": build_extraction_prompt(own_company_name, own_tax_id)},
            {"role": "user", "content": text[:12000]},
        ],
    }
    content = ollama_chat(payload)
    return json.loads(strip_json_fences(content))


def extract_with_vision_model(image_list, own_company_name="", own_tax_id=""):
    images_b64 = [base64.b64encode(prepare_image_bytes(img)).decode("ascii") for img in image_list]
    payload = {
        "model": OLLAMA_VISION_MODEL,
        "stream": False,
        "messages": [
            {
                "role": "user",
                "content": build_extraction_prompt(own_company_name, own_tax_id),
                "images": images_b64,
            }
        ],
    }
    content = ollama_chat(payload)
    return json.loads(strip_json_fences(content))


# ---------------------------------------------------------------------------
# Facturae XML (sin IA, es un formato estructurado)
# ---------------------------------------------------------------------------

def local_name(tag):
    return tag.split("}")[-1] if "}" in tag else tag


def find_first(elem, path_names):
    """Busca el primer descendiente cuyo nombre local (sin namespace) coincida
    con cada elemento de path_names, en orden (recorrido tolerante a la versión
    de namespace de Facturae, que cambia entre 3.2/3.2.1/3.2.2)."""
    current = [elem]
    for name in path_names:
        found = None
        for node in current:
            for child in node:
                if local_name(child.tag) == name:
                    found = child
                    break
            if found is not None:
                break
        if found is None:
            return None
        current = [found]
    return current[0] if current else None


def text_of(elem, path_names, default=""):
    node = find_first(elem, path_names)
    return node.text.strip() if node is not None and node.text else default


def parse_facturae_xml(data):
    try:
        root = ET.fromstring(data)
    except ET.ParseError:
        return None

    if "facturae" not in local_name(root.tag).lower() and not any(
        local_name(c.tag).lower() == "invoices" for c in root
    ):
        return None

    parties = None
    invoices_node = None
    for child in root:
        ln = local_name(child.tag)
        if ln == "Parties":
            parties = child
        elif ln == "Invoices":
            invoices_node = child

    if parties is None or invoices_node is None:
        return None

    seller = find_first(parties, ["SellerParty"])
    if seller is None:
        return None

    tax_id = text_of(seller, ["TaxIdentification", "TaxIdentificationNumber"])

    legal_entity = find_first(seller, ["LegalEntity"])
    if legal_entity is not None:
        company = text_of(legal_entity, ["CorporateName"])
        address_block = legal_entity
    else:
        individual = find_first(seller, ["Individual"])
        address_block = individual
        if individual is not None:
            name = text_of(individual, ["Name"])
            surname1 = text_of(individual, ["FirstSurname"])
            surname2 = text_of(individual, ["SecondSurname"])
            company = " ".join(p for p in (name, surname1, surname2) if p)
        else:
            company = ""

    address = ""
    city = ""
    postcode = ""
    province = ""
    country = "España"
    if address_block is not None:
        addr_in_spain = find_first(address_block, ["AddressInSpain"]) or address_block
        address = text_of(addr_in_spain, ["Address"])
        postcode = text_of(addr_in_spain, ["PostCode"])
        city = text_of(addr_in_spain, ["Town"])
        province = text_of(addr_in_spain, ["Province"])

    contact = find_first(seller, ["ContactDetails"])
    email = text_of(contact, ["ElectronicMail"]) if contact is not None else ""
    phone = text_of(contact, ["Telephone"]) if contact is not None else ""

    invoice_node = find_first(invoices_node, ["Invoice"])
    if invoice_node is None:
        return None

    header = find_first(invoice_node, ["InvoiceHeader"])
    number = text_of(header, ["InvoiceNumber"]) if header is not None else ""
    series = text_of(header, ["InvoiceSeriesCode"]) if header is not None else ""
    full_number = (series + "-" + number) if series and number else (number or series)

    issue_data = find_first(invoice_node, ["InvoiceIssueData"])
    issue_date = text_of(issue_data, ["IssueDate"]) if issue_data is not None else ""

    lines = []
    items_node = find_first(invoice_node, ["Items"])
    if items_node is not None:
        for line in items_node:
            if local_name(line.tag) != "InvoiceLine":
                continue
            description = text_of(line, ["ItemDescription"])
            quantity = parse_float(text_of(line, ["Quantity"]), 1)
            unit_price = parse_float(text_of(line, ["UnitPriceWithoutTax"]))
            tax_rate = 0.0
            taxes_outputs = find_first(line, ["TaxesOutputs"])
            if taxes_outputs is not None:
                tax = find_first(taxes_outputs, ["Tax"])
                if tax is not None:
                    tax_rate = parse_float(text_of(tax, ["TaxRate"]))
            discount_percent = 0.0
            discounts = find_first(line, ["DiscountsAndRebates"])
            if discounts is not None:
                discount = find_first(discounts, ["Discount"])
                if discount is not None:
                    discount_percent = parse_float(text_of(discount, ["DiscountRate"]))
            lines.append({
                "description": description or "Concepto",
                "quantity": quantity or 1,
                "unit_price": unit_price,
                "discount_percent": discount_percent,
                "tax_rate": tax_rate,
            })

    totals_node = find_first(invoice_node, ["InvoiceTotals"])
    subtotal = parse_float(text_of(totals_node, ["TotalGrossAmountBeforeTaxes"])) if totals_node is not None else 0.0
    tax_total = parse_float(text_of(totals_node, ["TotalTaxOutputs"])) if totals_node is not None else 0.0
    total = parse_float(text_of(totals_node, ["InvoiceTotal"])) if totals_node is not None else 0.0

    return {
        "supplier": {
            "company": company,
            "tax_id": tax_id,
            "address": address,
            "city": city,
            "postcode": postcode,
            "province": province,
            "country": country,
            "email": email,
            "phone": phone,
        },
        "invoice": {
            "number": full_number,
            "date": parse_date(issue_date) or issue_date,
            "currency": "EUR",
            "lines": lines,
            "subtotal": subtotal,
            "tax_total": tax_total,
            "total": total,
        },
    }


# ---------------------------------------------------------------------------
# Reconciliación de totales (nunca confiar ciegamente en la aritmética del
# modelo de IA; recalculamos en Python a partir de las líneas)
# ---------------------------------------------------------------------------

def reconcile_invoice(data):
    lines = data.get("invoice", {}).get("lines") or []
    normalized_lines = []
    for line in lines:
        qty = parse_float(line.get("quantity"), 1) or 1
        price = parse_float(line.get("unit_price"))
        discount = max(0.0, min(100.0, parse_float(line.get("discount_percent"))))
        tax_rate = max(0.0, parse_float(line.get("tax_rate")))
        line_total = round(qty * price * (1 - discount / 100.0), 4)
        normalized_lines.append({
            "description": (line.get("description") or "Concepto")[:255],
            "quantity": qty,
            "price": price,
            "discount": discount,
            "tax_rate": tax_rate,
            "total": line_total,
        })

    sub_total = round(sum(l["total"] for l in normalized_lines), 2)

    tax_groups = {}
    for l in normalized_lines:
        rate = round(l["tax_rate"], 2)
        amount = round(l["total"] * rate / 100.0, 2)
        tax_groups[rate] = tax_groups.get(rate, 0.0) + amount
        l["tax_amount"] = amount

    # El desglose por línea (cantidad/precio/tax_rate, cada uno un único número
    # transcrito literalmente del documento) es más fiable que los totales que
    # el propio modelo intenta calcular (subtotal/tax_total/total son aritmética
    # que el modelo hace sobre varias líneas, y ahí es donde más se equivoca) —
    # así que manda siempre que aporte algo. El tax_total extraído solo se usa
    # como último recurso cuando ninguna línea trae un tax_rate real (p. ej. el
    # modelo no localizó el IVA por línea pero sí el total impreso).
    computed_tax_total = round(sum(tax_groups.values()), 2)

    if tax_groups and computed_tax_total != 0:
        tax_rows = [{"rate": rate, "amount": round(amount, 2)} for rate, amount in sorted(tax_groups.items()) if amount != 0]
        tax_total = computed_tax_total
    else:
        extracted_tax_total = parse_float(data.get("invoice", {}).get("tax_total"))
        if extracted_tax_total > 0:
            tax_rows = [{"rate": None, "amount": round(extracted_tax_total, 2)}]
            tax_total = round(extracted_tax_total, 2)
        else:
            tax_rows = []
            tax_total = 0.0

    total = round(sub_total + tax_total, 2)
    extracted_total = parse_float(data.get("invoice", {}).get("total"))

    return {
        "lines": normalized_lines,
        "sub_total": sub_total,
        "tax_rows": tax_rows,
        "tax_total": tax_total,
        "total": total,
        "extracted_total": extracted_total,
    }


CONSISTENCY_MIN_TOLERANCE = 0.05  # € — margen fijo para facturas muy pequeñas
CONSISTENCY_RELATIVE_TOLERANCE = 0.01  # 1% — margen para el resto


def check_total_consistency(reconciled):
    """Compara el total impreso en el documento (transcrito literal por el
    modelo, sin que él lo calcule) contra el total que recalculamos nosotros
    desde las líneas. Si no coinciden, lo más probable es que el modelo se
    haya equivocado leyendo una cifra (línea o total) — mejor no crear la
    factura automáticamente que crearla con un importe incorrecto.
    Si el documento no trae un total legible/parseable, no hay nada con que
    comparar y se deja pasar (no es un fallo, es falta de dato)."""
    extracted_total = reconciled.get("extracted_total", 0.0)
    if not extracted_total:
        return

    computed_total = reconciled["total"]
    tolerance = max(CONSISTENCY_MIN_TOLERANCE, abs(computed_total) * CONSISTENCY_RELATIVE_TOLERANCE)

    if abs(computed_total - extracted_total) > tolerance:
        raise ValueError(
            "El total calculado a partir de las líneas ({:.2f}) no coincide con el total impreso "
            "en el documento ({:.2f}) — posible error de lectura, no se crea la factura.".format(
                computed_total, extracted_total
            )
        )


# ---------------------------------------------------------------------------
# Proveedor: buscar o crear
# ---------------------------------------------------------------------------

def find_or_create_supplier(conn, supplier_data):
    prefix = db_prefix()
    tax_id = clean_tax_id(supplier_data.get("tax_id"))
    company = (supplier_data.get("company") or "").strip()[:92]

    with conn.cursor() as cur:
        if tax_id:
            cur.execute(
                "SELECT supplier_id FROM `{}supplier` WHERE UPPER(REPLACE(REPLACE(tax_id,'-',''),' ','')) = %s LIMIT 1".format(prefix),
                (tax_id,),
            )
            row = cur.fetchone()
            if row:
                return row["supplier_id"], False
        elif company:
            cur.execute(
                "SELECT supplier_id FROM `{}supplier` WHERE company = %s LIMIT 1".format(prefix),
                (company,),
            )
            row = cur.fetchone()
            if row:
                return row["supplier_id"], False

        country_id, zone_id = resolve_country_zone(conn, supplier_data.get("country"), supplier_data.get("province"))

        cur.execute(
            """INSERT INTO `{}supplier`
                (firstname, lastname, company, company_id, tax_id, email, telephone, fax, web,
                 address_1, address_2, city, postcode, country_id, zone_id, comment, status,
                 date_added, date_modified)
               VALUES ('', '', %s, '', %s, %s, %s, '', '', %s, '', %s, %s, %s, %s, %s, 1, NOW(), NOW())""".format(prefix),
            (
                company or "(Proveedor sin nombre)",
                supplier_data.get("tax_id", "")[:32],
                (supplier_data.get("email") or "")[:96],
                (supplier_data.get("phone") or "")[:32],
                (supplier_data.get("address") or "")[:128],
                (supplier_data.get("city") or "")[:128],
                (supplier_data.get("postcode") or "")[:10],
                country_id,
                zone_id,
                "Proveedor creado automáticamente por importación de factura por email el " + datetime.now().strftime("%Y-%m-%d %H:%M"),
            ),
        )
        return cur.lastrowid, True


def resolve_country_zone(conn, country_name, province_name):
    prefix = db_prefix()
    country_id = 195  # España, por defecto (mayoría de proveedores nacionales)
    with conn.cursor() as cur:
        if country_name and normalize_key(country_name) not in ("ESPANA", "SPAIN", ""):
            target_country = normalize_key(country_name)
            target_iso2 = country_name.strip().upper()[:2]
            cur.execute("SELECT country_id, name, iso_code_2 FROM `{}country`".format(prefix))
            for row in cur.fetchall():
                if normalize_key(row["name"]) == target_country or row["iso_code_2"] == target_iso2:
                    country_id = row["country_id"]
                    break

        zone_id = 0
        if province_name:
            target = normalize_key(province_name)
            cur.execute("SELECT zone_id, name FROM `{}zone` WHERE country_id = %s".format(prefix), (country_id,))
            for row in cur.fetchall():
                if normalize_key(row["name"]) == target:
                    zone_id = row["zone_id"]
                    break

    return country_id, zone_id


# ---------------------------------------------------------------------------
# Crear la factura de proveedor (replica ModelPurchaseInvoice::addInvoice())
# ---------------------------------------------------------------------------

def get_setting(conn, key, group="config", default=None):
    prefix = db_prefix()
    with conn.cursor() as cur:
        cur.execute(
            "SELECT value FROM `{}setting` WHERE `key` = %s AND `group` = %s AND store_id = 0 LIMIT 1".format(prefix),
            (key, group),
        )
        row = cur.fetchone()
        return row["value"] if row else default


def create_purchase_invoice(conn, supplier_id, supplier_data, reconciled, invoice_data, source_note):
    prefix = db_prefix()

    invoice_prefix = get_setting(conn, "purchase_invoice_prefix", "setting") \
        or get_setting(conn, "config_purchase_invoice_prefix", "config") \
        or ("FRA-" + str(datetime.now().year) + "-00")

    store_name = get_setting(conn, "config_name", "config", "") or ""
    currency_code = (invoice_data.get("currency") or "").strip().upper()
    with conn.cursor() as cur:
        currency_row = None
        if currency_code:
            cur.execute("SELECT currency_id, code, value FROM `{}currency` WHERE code = %s AND status = 1 LIMIT 1".format(prefix), (currency_code,))
            currency_row = cur.fetchone()
        if not currency_row:
            default_code = get_setting(conn, "config_currency", "config", "EUR")
            cur.execute("SELECT currency_id, code, value FROM `{}currency` WHERE code = %s LIMIT 1".format(prefix), (default_code,))
            currency_row = cur.fetchone()

    currency_id = currency_row["currency_id"] if currency_row else 0
    currency_code_final = currency_row["code"] if currency_row else "EUR"
    currency_value = float(currency_row["value"]) if currency_row else 1.0

    language_id = 1
    admin_language_code = get_setting(conn, "config_admin_language", "config", "en")
    with conn.cursor() as cur:
        cur.execute("SELECT language_id FROM `{}language` WHERE code = %s LIMIT 1".format(prefix), (admin_language_code,))
        row = cur.fetchone()
        if row:
            language_id = row["language_id"]

    country_id, zone_id = resolve_country_zone(conn, supplier_data.get("country"), supplier_data.get("province"))

    with conn.cursor() as cur:
        country_name = ""
        zone_name = ""
        if country_id:
            cur.execute("SELECT name FROM `{}country` WHERE country_id = %s".format(prefix), (country_id,))
            row = cur.fetchone()
            country_name = row["name"] if row else ""
        if zone_id:
            cur.execute("SELECT name FROM `{}zone` WHERE zone_id = %s".format(prefix), (zone_id,))
            row = cur.fetchone()
            zone_name = row["name"] if row else ""

        cur.execute(
            """INSERT INTO `{}purchase_invoice`
                (invoice_prefix, supplier_invoice_no, store_id, store_name, store_url, supplier_id,
                 email, telephone, fax,
                 payment_company, payment_company_id, payment_tax_id,
                 payment_address_1, payment_address_2, payment_city, payment_postcode,
                 payment_country, payment_country_id, payment_zone, payment_zone_id,
                 payment_address_format, payment_method, payment_code,
                 shipping_company, shipping_address_1, shipping_address_2, shipping_city,
                 shipping_postcode, shipping_country, shipping_country_id, shipping_zone,
                 shipping_zone_id, shipping_address_format, shipping_method, shipping_code,
                 comment, invoice_status_id, language_id, currency_id, currency_code, currency_value,
                 date_added, date_modified)
               VALUES (%s, %s, 0, %s, '', %s,
                 %s, %s, '',
                 %s, '', %s,
                 %s, '', %s, %s,
                 %s, %s, %s, %s,
                 '', '', '',
                 '', '', '', '',
                 '', '', 0, '',
                 0, '', '', '',
                 %s, 1, %s, %s, %s, %s,
                 NOW(), NOW())""".format(prefix),
            (
                invoice_prefix,
                (invoice_data.get("number") or "")[:64],
                store_name,
                supplier_id,
                (supplier_data.get("email") or "")[:96],
                (supplier_data.get("phone") or "")[:32],
                (supplier_data.get("company") or "")[:255],
                (supplier_data.get("tax_id") or "")[:32],
                (supplier_data.get("address") or "")[:128],
                (supplier_data.get("city") or "")[:128],
                (supplier_data.get("postcode") or "")[:10],
                country_name,
                country_id,
                zone_name,
                zone_id,
                source_note[:65535],
                language_id,
                currency_id,
                currency_code_final,
                currency_value,
            ),
        )
        invoice_id = cur.lastrowid

        for line in reconciled["lines"]:
            cur.execute(
                """INSERT INTO `{}purchase_invoice_product`
                    (invoice_product_id, invoice_id, product_id, name, model, quantity, price,
                     discount, total, tax)
                   VALUES (0, %s, 0, %s, '', %s, %s, %s, %s, %s)""".format(prefix),
                (
                    invoice_id,
                    line["description"],
                    int(line["quantity"]),
                    line["price"],
                    line["discount"],
                    line["total"],
                    line.get("tax_amount", 0),
                ),
            )

        sort_order_sub_total = int(get_setting(conn, "sub_total_sort_order", "config", 2))
        sort_order_tax = int(get_setting(conn, "tax_sort_order", "config", 5))
        sort_order_total = int(get_setting(conn, "total_sort_order", "config", 9))

        def add_total(code, title, value, sort_order):
            cur.execute(
                """INSERT INTO `{}purchase_invoice_total` (invoice_id, code, title, text, value, sort_order)
                   VALUES (%s, %s, %s, %s, %s, %s)""".format(prefix),
                (invoice_id, code, title, format_currency(value, currency_code_final), value, sort_order),
            )

        add_total("sub_total", "Sub-Total", reconciled["sub_total"], sort_order_sub_total)

        for i, tax_row in enumerate(reconciled["tax_rows"]):
            title = "IVA ({:.0f}%)".format(tax_row["rate"]) if tax_row["rate"] is not None else "IVA"
            add_total("tax", title, tax_row["amount"], sort_order_tax)

        add_total("total", "Total", reconciled["total"], sort_order_total)

        cur.execute(
            "UPDATE `{}purchase_invoice` SET total = %s WHERE invoice_id = %s".format(prefix),
            (reconciled["total"], invoice_id),
        )

        cur.execute(
            """INSERT INTO `{}purchase_invoice_history` (invoice_id, invoice_status_id, notify, comment, date_added)
               VALUES (%s, 1, 0, %s, NOW())""".format(prefix),
            (invoice_id, "Factura importada automáticamente desde email."),
        )

    return invoice_id


def format_currency(value, code):
    symbol = "€" if code == "EUR" else code
    return "{:.2f} {}".format(value, symbol)


# ---------------------------------------------------------------------------
# Comprobación opcional contra Pedidos de Compra (config_supplier_invoice_match_order)
# ---------------------------------------------------------------------------

PO_TOTAL_TOLERANCE = 0.01  # "coincide 100%" — solo margen para redondeo de céntimo


def find_matching_purchase_order(conn, supplier_id, total):
    """Busca un pedido de compra de ese proveedor, sin facturar aún
    (purchase_order.invoice_no = 0), con el mismo total exacto. Devuelve
    (purchase_order_id, None) si hay exactamente uno, o (None, motivo) si no
    hay ninguno o hay varios candidatos ambiguos (no se adivina, se manda a
    revisión manual)."""
    prefix = db_prefix()
    with conn.cursor() as cur:
        cur.execute(
            "SELECT purchase_order_id, total FROM `{}purchase_order` WHERE supplier_id = %s AND invoice_no = 0".format(prefix),
            (supplier_id,),
        )
        candidates = [row for row in cur.fetchall() if abs(float(row["total"]) - total) <= PO_TOTAL_TOLERANCE]

    if len(candidates) == 1:
        return candidates[0]["purchase_order_id"], None
    if len(candidates) == 0:
        return None, "Sin ningún pedido de compra sin facturar de ese proveedor que coincida 100% en el total."
    return None, "Varios pedidos de compra de ese proveedor coinciden con el mismo total — revisión manual."


def save_pending_review(conn, mailbox, message_uid, subject, from_email, date_received, reason,
                         supplier_id, supplier_data, invoice_data, filename, data, extraction_method):
    """Guarda la extracción ya hecha (proveedor ya resuelto/creado, factura ya
    reconciliada) para que un humano decida desde tool/pending_invoices si la
    incorpora tal cual o la descarta — no se crea la purchase_invoice."""
    prefix = db_prefix()
    supplier_payload = dict(supplier_data)
    supplier_payload["supplier_id"] = supplier_id

    with conn.cursor() as cur:
        cur.execute(
            """INSERT INTO `{}purchase_invoice_pending_review`
                (mailbox, message_uid, subject, from_email, date_received, reason,
                 supplier_data, invoice_data, attachment_filename, extraction_method, date_added)
               VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW())""".format(prefix),
            (
                mailbox, message_uid, subject[:255], from_email[:255], date_received, reason[:255],
                json.dumps(supplier_payload, ensure_ascii=False), json.dumps(invoice_data, ensure_ascii=False),
                filename[:255], extraction_method,
            ),
        )
        pending_id = cur.lastrowid

    attach_dir = os.environ.get("ATTACHMENT_DIR", "")
    attachment_path = ""
    if attach_dir:
        subdir = os.path.join(attach_dir, "_pendientes", datetime.now().strftime("%Y-%m"), str(pending_id))
        os.makedirs(subdir, exist_ok=True)
        safe_name = re.sub(r"[^A-Za-z0-9_.\-]", "_", filename)[:150] or "adjunto"
        attachment_path = os.path.join(subdir, safe_name)
        with open(attachment_path, "wb") as f:
            f.write(data)

        with conn.cursor() as cur:
            cur.execute(
                "UPDATE `{}purchase_invoice_pending_review` SET attachment_path = %s WHERE pending_id = %s".format(prefix),
                (attachment_path, pending_id),
            )

    return pending_id


# ---------------------------------------------------------------------------
# Log de importación (idempotencia entre ejecuciones)
# ---------------------------------------------------------------------------

def invoice_exists(conn, invoice_id):
    if not invoice_id:
        return False
    prefix = db_prefix()
    with conn.cursor() as cur:
        cur.execute("SELECT 1 FROM `{}purchase_invoice` WHERE invoice_id = %s LIMIT 1".format(prefix), (invoice_id,))
        return cur.fetchone() is not None


def get_log_row(conn, mailbox, message_uid):
    prefix = db_prefix()
    with conn.cursor() as cur:
        cur.execute(
            "SELECT * FROM `{}purchase_invoice_import_log` WHERE mailbox = %s AND message_uid = %s LIMIT 1".format(prefix),
            (mailbox, message_uid),
        )
        return cur.fetchone()


def upsert_log(conn, mailbox, message_uid, **fields):
    prefix = db_prefix()
    existing = get_log_row(conn, mailbox, message_uid)
    with conn.cursor() as cur:
        if existing:
            attempts = existing["attempts"] + (1 if fields.get("increment_attempt") else 0)
            fields.pop("increment_attempt", None)
            set_parts = ["attempts = %s"]
            values = [attempts]
            for k, v in fields.items():
                set_parts.append("`{}` = %s".format(k))
                values.append(v)
            values.extend([mailbox, message_uid])
            cur.execute(
                "UPDATE `{}purchase_invoice_import_log` SET ".format(prefix) + ", ".join(set_parts) +
                " WHERE mailbox = %s AND message_uid = %s",
                values,
            )
        else:
            attempts = 1 if fields.get("increment_attempt") else 0
            fields.pop("increment_attempt", None)
            fields.setdefault("subject", "")
            fields.setdefault("from_email", "")
            fields.setdefault("status", "pending")
            columns = ["mailbox", "message_uid", "attempts", "date_added"] + list(fields.keys())
            placeholders = ["%s", "%s", "%s", "NOW()"] + ["%s"] * len(fields)
            values = [mailbox, message_uid, attempts] + list(fields.values())
            cur.execute(
                "INSERT INTO `{}purchase_invoice_import_log` (".format(prefix) + ", ".join("`{}`".format(c) for c in columns) +
                ") VALUES (" + ", ".join(placeholders) + ")",
                values,
            )


# ---------------------------------------------------------------------------
# Guardado del adjunto original
# ---------------------------------------------------------------------------

def save_attachment(filename, data, invoice_id):
    """<ATTACHMENT_DIR>/<YYYY-MM>/<invoice_id>/<archivo original>. ATTACHMENT_DIR
    lo fija system/vendor/cron/supplier_invoice_import.php (docs/suppliers/invoices/
    en la raíz del proyecto, no download/) — no asumir la ruta aquí."""
    attach_dir = os.environ.get("ATTACHMENT_DIR", "")
    if not attach_dir:
        return ""
    subdir = os.path.join(attach_dir, datetime.now().strftime("%Y-%m"), str(invoice_id))
    os.makedirs(subdir, exist_ok=True)
    safe_name = re.sub(r"[^A-Za-z0-9_.\-]", "_", filename)[:150] or "adjunto"
    path = os.path.join(subdir, safe_name)
    with open(path, "wb") as f:
        f.write(data)
    return path


# ---------------------------------------------------------------------------
# Procesar un único mensaje
# ---------------------------------------------------------------------------

def process_message(conn, pop, mailbox, msgnum, uidl, raw_bytes, counters, own_company_name="", own_tax_id="", match_orders=False):
    msg = email.message_from_bytes(raw_bytes)
    message_key = uidl
    subject = decode_mime_header(msg.get("Subject", ""))
    from_email = decode_mime_header(msg.get("From", ""))
    date_received = None
    try:
        date_received = email.utils.parsedate_to_datetime(msg.get("Date")).strftime("%Y-%m-%d %H:%M:%S")
    except Exception:
        pass

    existing = get_log_row(conn, mailbox, message_key)
    if existing and existing["status"] == "imported":
        if invoice_exists(conn, existing["invoice_id"]):
            pop.dele(msgnum)
            return
        # La factura se borró después (limpieza manual, pruebas...) — el log decía
        # "imported" pero ya no hay nada real que la respalde, así que se reprocesa
        # como si fuera la primera vez en vez de darla por hecha para siempre.
        log("Aviso: invoice_id {} ya no existe (factura borrada), reprocesando: {}".format(existing["invoice_id"], subject))
    if existing and existing["status"] == "error" and existing["attempts"] >= MAX_ATTEMPTS:
        # Ya se había dado por perdido en una ejecución anterior; si sigue aquí es
        # porque el DELE de entonces no llegó a confirmarse (p.ej. caída de red
        # antes del QUIT). Se finaliza otra vez, sin gastar una nueva llamada a IA.
        saved_path = save_failed_email(raw_bytes, uidl)
        log("Máximo de intentos ya alcanzado, guardado en {} y borrado del buzón: {}".format(saved_path, subject))
        pop.dele(msgnum)
        return

    attachments = extract_attachments(msg)

    if not attachments:
        pop.dele(msgnum)
        upsert_log(
            conn, mailbox, message_key,
            status="deleted_no_attachment", subject=subject[:255], from_email=from_email[:255],
            date_received=date_received, date_processed=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            increment_attempt=True,
        )
        counters["deleted_no_attachment"] += 1
        log("Sin adjuntos, borrado: {}".format(subject))
        return

    filename, ext, data = attachments[0]

    try:
        extracted, method = extract_invoice_data(ext, data, own_company_name, own_tax_id)

        if not extracted or not extracted.get("invoice", {}).get("lines"):
            raise ValueError("La extracción no devolvió líneas de factura")

        if is_own_company(extracted["supplier"], own_company_name, own_tax_id):
            raise ValueError(
                "El proveedor extraído coincide con nuestra propia empresa ({}) — el modelo ha confundido "
                "emisor y cliente, no se crea la factura.".format(own_company_name)
            )

        reconciled = reconcile_invoice(extracted)
        check_total_consistency(reconciled)

        supplier_id, created = find_or_create_supplier(conn, extracted["supplier"])

        matched_po_id = None
        if match_orders:
            matched_po_id, no_match_reason = find_matching_purchase_order(conn, supplier_id, reconciled["total"])

            if matched_po_id is None:
                invoice_data_for_review = dict(reconciled)
                invoice_data_for_review["number"] = extracted["invoice"].get("number", "")
                invoice_data_for_review["date"] = extracted["invoice"].get("date", "")
                invoice_data_for_review["currency"] = extracted["invoice"].get("currency", "EUR")

                pending_id = save_pending_review(
                    conn, mailbox, message_key, subject, from_email, date_received, no_match_reason,
                    supplier_id, extracted["supplier"], invoice_data_for_review, filename, data, method,
                )

                upsert_log(
                    conn, mailbox, message_key,
                    status="pending_review", subject=subject[:255], from_email=from_email[:255],
                    date_received=date_received, date_processed=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
                    supplier_id=supplier_id, extraction_method=method, error_message=no_match_reason,
                    increment_attempt=True,
                )

                pop.dele(msgnum)
                counters["pending_review"] += 1
                log("A revisión manual (pending_id {}): {} — {}".format(pending_id, subject, no_match_reason))
                return

        source_note = "Factura importada automáticamente desde email (asunto: {}, adjunto: {}, método: {}).".format(
            subject, filename, method
        )

        invoice_id = create_purchase_invoice(conn, supplier_id, extracted["supplier"], reconciled, extracted["invoice"], source_note)

        if matched_po_id:
            with conn.cursor() as cur:
                cur.execute(
                    "UPDATE `{}purchase_order` SET invoice_no = %s WHERE purchase_order_id = %s".format(db_prefix()),
                    (invoice_id, matched_po_id),
                )

        attachment_path = save_attachment(filename, data, invoice_id)

        if attachment_path:
            with conn.cursor() as cur:
                cur.execute(
                    "UPDATE `{}purchase_invoice` SET attachment_path = %s WHERE invoice_id = %s".format(db_prefix()),
                    (attachment_path, invoice_id),
                )

        upsert_log(
            conn, mailbox, message_key,
            status="imported", subject=subject[:255], from_email=from_email[:255],
            date_received=date_received, date_processed=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            supplier_id=supplier_id, invoice_id=invoice_id, attachment_path=attachment_path,
            extraction_method=method, error_message="",
            increment_attempt=True,
        )

        pop.dele(msgnum)

        counters["imported"] += 1
        log("Factura #{} creada (proveedor {}{}) desde: {}".format(
            invoice_id, supplier_id, " nuevo" if created else "", subject))

    except Exception as exc:
        error_text = "{}: {}".format(type(exc).__name__, exc)
        upsert_log(
            conn, mailbox, message_key,
            status="error", subject=subject[:255], from_email=from_email[:255],
            date_received=date_received, date_processed=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            error_message=error_text[:2000],
            increment_attempt=True,
        )
        counters["errors"] += 1
        log("ERROR procesando '{}': {}".format(subject, error_text))

        updated = get_log_row(conn, mailbox, message_key)
        if updated and updated["attempts"] >= MAX_ATTEMPTS:
            saved_path = save_failed_email(raw_bytes, uidl)
            log("Máximo de intentos alcanzado ({}), guardado en {} y borrado del buzón: {}".format(MAX_ATTEMPTS, saved_path, subject))
            pop.dele(msgnum)
        # si no ha llegado al máximo de intentos, se deja en el buzón (no se
        # borra) para que el próximo ciclo del cron lo reintente.


def extract_invoice_data(ext, data, own_company_name="", own_tax_id=""):
    if ext == ".xml":
        parsed = parse_facturae_xml(data)
        if parsed is None:
            raise ValueError("El XML adjunto no tiene formato Facturae reconocible")
        return parsed, "facturae_xml"

    if ext == ".pdf":
        analysis = analyze_pdf(data)
        if "text" in analysis:
            return extract_with_text_model(analysis["text"], own_company_name, own_tax_id), "pdf_text"
        return extract_with_vision_model(analysis["images"], own_company_name, own_tax_id), "pdf_vision"

    if ext in (".jpg", ".jpeg", ".png"):
        return extract_with_vision_model([data], own_company_name, own_tax_id), "image_vision"

    raise ValueError("Extensión no soportada: " + ext)


def is_own_company(supplier_data, own_company_name, own_tax_id):
    """Salvaguarda determinista: si el 'proveedor' extraído coincide con nuestra
    propia empresa (nombre o NIF), es casi seguro que el modelo se ha confundido
    de parte (emisor vs cliente) y NO hay que crear ese proveedor/factura."""
    tax_id = clean_tax_id(supplier_data.get("tax_id"))
    own_tax_id_clean = clean_tax_id(own_tax_id)
    if own_tax_id_clean and tax_id and tax_id == own_tax_id_clean:
        return True

    company = normalize_key(supplier_data.get("company"))
    own_company_key = normalize_key(own_company_name)
    if own_company_key and company and company == own_company_key:
        return True

    return False


# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

def main():
    started_at = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    status.write(running=True, started_at=started_at, message="Comprobando configuración")

    email_addr = os.environ.get("POP_EMAIL", "").strip()
    password = os.environ.get("POP_PASSWORD", "").strip()
    host = os.environ.get("POP_HOST", "").strip()

    if not email_addr or not password:
        msg = "Faltan el email o la contraseña del buzón de facturas de proveedores (Ajustes > Mail)."
        log(msg)
        status.write(running=False, started_at=started_at, finished_at=datetime.now().strftime("%Y-%m-%d %H:%M:%S"), error=msg)
        return 1

    if not host:
        msg = "Falta configurar el servidor POP3 (host) en Ajustes > Mail."
        log(msg)
        status.write(running=False, started_at=started_at, finished_at=datetime.now().strftime("%Y-%m-%d %H:%M:%S"), error=msg)
        return 1

    counters = {"scanned": 0, "imported": 0, "deleted_no_attachment": 0, "pending_review": 0, "errors": 0}

    conn = None
    pop = None
    try:
        conn = db_connect()

        own_company_name = get_setting(conn, "config_name", "config", "") or ""
        own_tax_id = get_setting(conn, "config_vat_id", "config", "") or get_setting(conn, "config_nif", "config", "") or ""
        match_orders = get_setting(conn, "config_supplier_invoice_match_order", "config", "0") == "1"

        status.write(running=True, started_at=started_at, message="Conectando al buzón POP3")
        pop = pop3_connect()

        entries = list_uidls(pop)
        log("Mensajes en el buzón: {}".format(len(entries)))

        for msgnum, uidl in entries:
            counters["scanned"] += 1
            status.write(running=True, started_at=started_at, message="Procesando mensaje {}/{}".format(counters["scanned"], len(entries)))

            try:
                raw_bytes = fetch_message(pop, msgnum)
            except Exception as exc:
                log("ERROR descargando mensaje {} (uidl={}): {}".format(msgnum, uidl, exc))
                counters["errors"] += 1
                continue

            try:
                process_message(conn, pop, email_addr, msgnum, uidl, raw_bytes, counters, own_company_name, own_tax_id, match_orders)
            except Exception as exc:
                log("ERROR inesperado procesando mensaje {} (uidl={}): {}".format(msgnum, uidl, exc))
                counters["errors"] += 1

        # El QUIT es imprescindible: en POP3 los DELE marcados durante la sesión
        # no se confirman de verdad en el servidor hasta que se cierra la sesión
        # correctamente con QUIT (si se corta antes, el servidor los descarta).
        pop.quit()
        pop = None

    except Exception as exc:
        log("ERROR fatal: {}".format(exc))
        status.write(
            running=False, started_at=started_at, finished_at=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            error=str(exc), counters=counters,
        )
        return 1
    finally:
        if pop is not None:
            try:
                pop.quit()
            except Exception:
                try:
                    pop.close()
                except Exception:
                    pass
        if conn:
            conn.close()

    finished_at = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    status.write(running=False, started_at=started_at, finished_at=finished_at, counters=counters, message="Completado")
    log("Completado: {}".format(counters))
    return 0


if __name__ == "__main__":
    sys.exit(main())
