import os
import re
import sys
import html
import json
import base64
import argparse
from pathlib import Path
from datetime import datetime, timezone

import fitz
import requests
import mariadb


# ============================================================
# CONFIGURACION (via variables de entorno, pasadas por PHP)
# ============================================================

DB_HOST = os.environ.get("DOCEMB_DB_HOST", "127.0.0.1")
DB_PORT = int(os.environ.get("DOCEMB_DB_PORT", "3306"))
DB_USER = os.environ.get("DOCEMB_DB_USER", "root")
DB_PASSWORD = os.environ.get("DOCEMB_DB_PASSWORD", "")
DB_NAME = os.environ.get("DOCEMB_DB_NAME", "")
DB_PREFIX = os.environ.get("DOCEMB_DB_PREFIX", "")
DEFAULT_LANGUAGE_ID = int(os.environ.get("DOCEMB_LANGUAGE_ID", "1"))

OLLAMA_URL = os.environ.get("DOCEMB_OLLAMA_URL", "http://127.0.0.1:11434")
VISION_MODEL = os.environ.get("DOCEMB_VISION_MODEL", "qwen2.5vl:3b")
EMBED_MODEL = os.environ.get("DOCEMB_EMBED_MODEL", "nomic-embed-text")

DOCS_DIR = Path(os.environ.get("DOCEMB_DOCS_DIR", "."))
STATUS_FILE = Path(os.environ.get("DOCEMB_STATUS_FILE", "status.json"))

DOCUMENT_TYPE = "product_document"
EMBED_DIMENSIONS = 768

CHUNK_SIZE = 700
CHUNK_OVERLAP = 100
MIN_TEXT_LENGTH = 30


def table(name):
	return DB_PREFIX + name


# ============================================================
# ESTADO (para el polling desde el panel)
# ============================================================

def write_status(data):
	tmp = STATUS_FILE.with_suffix(".tmp")
	with open(tmp, "w", encoding="utf-8") as f:
		json.dump(data, f, ensure_ascii=False)
	os.replace(tmp, STATUS_FILE)


def now_iso():
	return datetime.now(timezone.utc).isoformat()


# ============================================================
# OLLAMA
# ============================================================

def ollama_request(endpoint, payload):
	url = f"{OLLAMA_URL}{endpoint}"
	response = requests.post(url, json=payload, timeout=600)
	response.raise_for_status()
	return response.json()


def generate_embedding(text):
	payload = {"model": EMBED_MODEL, "input": text}
	result = ollama_request("/api/embed", payload)
	embeddings = result.get("embeddings")

	if not embeddings:
		raise RuntimeError("Ollama no devolvio ningun embedding.")

	return embeddings[0]


def extract_text_with_vision(image_bytes):
	image_base64 = base64.b64encode(image_bytes).decode("utf-8")

	prompt = (
		"Eres un sistema OCR para documentacion tecnica de productos.\n\n"
		"Extrae TODO el texto visible de esta pagina.\n\n"
		"IMPORTANTE:\n"
		"- No resumas.\n"
		"- No expliques.\n"
		"- No inventes informacion.\n"
		"- Conserva numeros, unidades, codigos de producto y referencias.\n"
		"- Conserva titulos y especificaciones tecnicas.\n"
		"- Conserva el contenido de las tablas, manteniendo su estructura como texto.\n"
		"- No anadas comentarios.\n\n"
		"Devuelve unicamente el texto extraido."
	)

	payload = {
		"model": VISION_MODEL,
		"prompt": prompt,
		"images": [image_base64],
		"stream": False,
		"options": {"temperature": 0},
	}

	result = ollama_request("/api/generate", payload)
	return clean_text(result.get("response", ""))


# ============================================================
# TEXTO
# ============================================================

def strip_html_tags(text):
	if not text:
		return ""

	# Algunos mensajes de `mails` llegan con las etiquetas ya escapadas como
	# entidades (&lt;p&gt;...&lt;/p&gt;) en vez de HTML literal - hay que
	# desescapar ANTES de aplicar el regex, o el regex no las reconoce como
	# etiquetas y el unescape final las revela sin haberlas podido quitar.
	text = html.unescape(text)

	text = re.sub(r"<(br|p|div|tr|li)[^>]*>", "\n", text, flags=re.IGNORECASE)
	text = re.sub(r"<[^>]+>", " ", text)

	return text


def clean_text(text):
	if not text:
		return ""

	text = text.replace("\r\n", "\n").replace("\r", "\n")
	text = re.sub(r"[ \t]+", " ", text)
	text = re.sub(r"\n\s*\n+", "\n\n", text)
	text = re.sub(r" *\n *", "\n", text)

	return text.strip()


def create_chunks(text, chunk_size=CHUNK_SIZE, overlap=CHUNK_OVERLAP):
	words = text.split()

	if not words:
		return []

	chunks = []
	start = 0

	while start < len(words):
		end = min(start + chunk_size, len(words))
		chunks.append(" ".join(words[start:end]))

		if end >= len(words):
			break

		start = end - overlap

	return chunks


def pdf_page_to_image(page):
	matrix = fitz.Matrix(2, 2)
	pix = page.get_pixmap(matrix=matrix, alpha=False)
	return pix.tobytes("jpeg")


# ============================================================
# BASE DE DATOS
# ============================================================

def get_connection():
	return mariadb.connect(
		host=DB_HOST,
		port=DB_PORT,
		user=DB_USER,
		password=DB_PASSWORD,
		database=DB_NAME,
	)


def find_product(conn, stem):
	cur = conn.cursor()

	cur.execute(
		f"SELECT product_id FROM {table('product')} "
		"WHERE sku = ? OR model = ? LIMIT 1",
		(stem, stem),
	)
	row = cur.fetchone()
	cur.close()

	if not row:
		return None, None

	product_id = row[0]
	product_name = get_product_name(conn, product_id)

	return product_id, product_name


def get_product_name(conn, product_id):
	cur = conn.cursor()
	cur.execute(
		f"SELECT name FROM {table('product_description')} "
		"WHERE product_id = ? AND language_id = ? LIMIT 1",
		(product_id, DEFAULT_LANGUAGE_ID),
	)
	row = cur.fetchone()

	if not row:
		cur = conn.cursor()
		cur.execute(
			f"SELECT name FROM {table('product_description')} "
			"WHERE product_id = ? LIMIT 1",
			(product_id,),
		)
		row = cur.fetchone()

	cur.close()

	return row[0] if row else None


def get_customer_name(conn, customer_id):
	cur = conn.cursor()
	cur.execute(
		f"SELECT company FROM {table('customer')} WHERE customer_id = ? LIMIT 1",
		(customer_id,),
	)
	row = cur.fetchone()
	cur.close()

	return row[0] if row else None


def get_supplier_name(conn, supplier_id):
	cur = conn.cursor()
	cur.execute(
		f"SELECT company FROM {table('supplier')} WHERE supplier_id = ? LIMIT 1",
		(supplier_id,),
	)
	row = cur.fetchone()
	cur.close()

	return row[0] if row else None


def upsert_log(conn, document_id, fields):
	cur = conn.cursor()

	columns = ", ".join(fields.keys())
	placeholders = ", ".join(["?"] * len(fields))
	updates = ", ".join(f"{k} = VALUES({k})" for k in fields.keys())

	sql = (
		f"INSERT INTO {table('document_embedding_log')} "
		f"(document_id, {columns}) VALUES (?, {placeholders}) "
		f"ON DUPLICATE KEY UPDATE {updates}"
	)

	cur.execute(sql, (document_id, *fields.values()))
	conn.commit()
	cur.close()


def delete_existing_chunks(conn, document_id):
	cur = conn.cursor()
	cur.execute(
		f"DELETE FROM {table('document_chunks')} WHERE document_id = ?",
		(document_id,),
	)
	conn.commit()
	cur.close()


def insert_chunk(conn, document_id, filename, product_id, product_name, customer_id, customer_name,
				  page, chunk_number, text, embedding, document_type, supplier_id=None, supplier_name=None):
	vector_text = "[" + ",".join(str(float(x)) for x in embedding) + "]"

	cur = conn.cursor()
	cur.execute(
		f"""
		INSERT INTO {table('document_chunks')}
		(document_id, document, product_id, product_name, customer_id, customer_name,
		 supplier_id, supplier_name, page, chunk_number, chunk_text, document_type, embedding)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, VEC_FromText(?))
		""",
		(
			document_id,
			filename,
			product_id,
			product_name,
			customer_id,
			customer_name,
			supplier_id,
			supplier_name,
			page,
			chunk_number,
			text,
			document_type,
			vector_text,
		),
	)
	conn.commit()
	cur.close()


# ============================================================
# PROCESAR UN PDF (nucleo compartido por los dos modos de invocacion)
# ============================================================

def embed_pdf(conn, document_id, display_name, pdf_path, product_id, product_name,
			  customer_id=None, customer_name=None, supplier_id=None, supplier_name=None,
			  document_type=DOCUMENT_TYPE, progress=None):
	if not pdf_path.exists():
		raise FileNotFoundError(f"No existe el archivo: {pdf_path}")

	delete_existing_chunks(conn, document_id)

	doc = fitz.open(pdf_path)
	total_pages = len(doc)
	progress["total_pages"] = total_pages
	write_status(progress)

	total_chunks = 0

	for page_number, page in enumerate(doc, start=1):
		progress["current_page"] = page_number
		write_status(progress)

		text = clean_text(page.get_text("text"))

		if len(text) < MIN_TEXT_LENGTH:
			image_bytes = pdf_page_to_image(page)
			text = extract_text_with_vision(image_bytes)

		if not text:
			continue

		chunks = create_chunks(text)

		for chunk_number, chunk_text in enumerate(chunks, start=1):
			embedding = generate_embedding(chunk_text)

			if len(embedding) != EMBED_DIMENSIONS:
				raise RuntimeError(
					f"El modelo de embeddings devolvio {len(embedding)} dimensiones, "
					f"se esperaban {EMBED_DIMENSIONS}."
				)

			insert_chunk(
				conn, document_id, display_name, product_id, product_name, customer_id, customer_name,
				page_number, chunk_number, chunk_text, embedding, document_type,
				supplier_id=supplier_id, supplier_name=supplier_name,
			)
			total_chunks += 1

	doc.close()

	return total_pages, total_chunks


def embed_text(conn, document_id, display_name, text, product_id, product_name,
				customer_id, customer_name, document_type, progress,
				supplier_id=None, supplier_name=None):
	"""Igual que embed_pdf() pero para texto plano ya disponible (notas de cliente/
	proveedor), sin PDF/OCR de por medio - todo el texto se trata como una unica
	'pagina' 1."""
	delete_existing_chunks(conn, document_id)

	progress["total_pages"] = 1
	progress["current_page"] = 1
	write_status(progress)

	text = clean_text(text)
	total_chunks = 0

	if text:
		chunks = create_chunks(text)

		for chunk_number, chunk_text in enumerate(chunks, start=1):
			embedding = generate_embedding(chunk_text)

			if len(embedding) != EMBED_DIMENSIONS:
				raise RuntimeError(
					f"El modelo de embeddings devolvio {len(embedding)} dimensiones, "
					f"se esperaban {EMBED_DIMENSIONS}."
				)

			insert_chunk(
				conn, document_id, display_name, product_id, product_name, customer_id, customer_name,
				1, chunk_number, chunk_text, embedding, document_type,
				supplier_id=supplier_id, supplier_name=supplier_name,
			)
			total_chunks += 1

	return 1, total_chunks


def process_file(conn, filename, progress):
	"""Modo 'lote' (pantalla Tools > Importar Documentos): el archivo vive suelto en
	DOCS_DIR y el producto se resuelve por SKU/Modelo == nombre de archivo sin extension."""
	pdf_path = DOCS_DIR / filename
	document_id = Path(filename).stem

	progress["current_file"] = filename
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": filename,
		"product_id": None,
		"product_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	product_id = None
	product_name = None

	try:
		product_id, product_name = find_product(conn, document_id)

		if not product_id:
			raise ValueError(
				f"No se ha encontrado ningun producto cuyo SKU o Modelo sea '{document_id}'."
			)

		total_pages, total_chunks = embed_pdf(
			conn, document_id, filename, pdf_path, product_id, product_name, progress=progress
		)

		upsert_log(conn, document_id, {
			"filename": filename,
			"product_id": product_id,
			"product_name": product_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": filename,
			"product_id": product_id,
			"product_name": product_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": filename, "error": str(e)})
		write_status(progress)


def process_product_document(conn, product_id, product_document_id, file_path, display_name, progress):
	"""Modo 'adjunto directo' (subida de un documento desde la ficha de producto,
	catalog/product/uploadDocument): el product_id ya se conoce con certeza, sin
	necesidad de emparejar por SKU/Modelo."""
	document_id = f"product_document_{product_document_id}"
	pdf_path = Path(file_path)

	progress["current_file"] = display_name
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": display_name,
		"product_id": product_id,
		"product_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	product_name = None

	try:
		product_name = get_product_name(conn, product_id)

		total_pages, total_chunks = embed_pdf(
			conn, document_id, display_name, pdf_path, product_id, product_name, progress=progress
		)

		upsert_log(conn, document_id, {
			"filename": display_name,
			"product_id": product_id,
			"product_name": product_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": display_name,
			"product_id": product_id,
			"product_name": product_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": display_name, "error": str(e)})
		write_status(progress)


def process_customer_document(conn, customer_id, customer_document_id, file_path, display_name, progress):
	"""Modo 'adjunto directo de cliente' (sale/customer/insertContract): mismo patron
	que process_product_document() pero indexado por customer_id, sin producto de por medio."""
	document_id = f"customer_document_{customer_document_id}"
	pdf_path = Path(file_path)

	progress["current_file"] = display_name
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": display_name,
		"customer_id": customer_id,
		"customer_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	customer_name = None

	try:
		customer_name = get_customer_name(conn, customer_id)

		total_pages, total_chunks = embed_pdf(
			conn, document_id, display_name, pdf_path, None, None,
			customer_id=customer_id, customer_name=customer_name,
			document_type="customer_document", progress=progress,
		)

		upsert_log(conn, document_id, {
			"filename": display_name,
			"customer_id": customer_id,
			"customer_name": customer_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": display_name,
			"customer_id": customer_id,
			"customer_name": customer_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": display_name, "error": str(e)})
		write_status(progress)


def process_supplier_document(conn, supplier_id, supplier_document_id, file_path, display_name, progress):
	"""Modo 'adjunto directo de proveedor' (purchase/supplier/insertContract): mismo
	patron que process_customer_document() pero indexado por supplier_id."""
	document_id = f"supplier_document_{supplier_document_id}"
	pdf_path = Path(file_path)

	progress["current_file"] = display_name
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": display_name,
		"supplier_id": supplier_id,
		"supplier_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	supplier_name = None

	try:
		supplier_name = get_supplier_name(conn, supplier_id)

		total_pages, total_chunks = embed_pdf(
			conn, document_id, display_name, pdf_path, None, None,
			supplier_id=supplier_id, supplier_name=supplier_name,
			document_type="supplier_document", progress=progress,
		)

		upsert_log(conn, document_id, {
			"filename": display_name,
			"supplier_id": supplier_id,
			"supplier_name": supplier_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": display_name,
			"supplier_id": supplier_id,
			"supplier_name": supplier_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": display_name, "error": str(e)})
		write_status(progress)


def process_customer_note(conn, customer_id, customer_note_id, progress):
	"""Modo 'nota de cliente' (sale/customer/insertNote): no hay fichero, el texto ya
	esta en la BD (customer_history.comment) - se embebe directamente, sin PDF/OCR."""
	document_id = f"customer_note_{customer_note_id}"
	display_name = f"Nota #{customer_note_id}"

	progress["current_file"] = display_name
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": display_name,
		"customer_id": customer_id,
		"customer_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	customer_name = None

	try:
		cur = conn.cursor()
		cur.execute(
			f"SELECT comment FROM {table('customer_history')} WHERE customer_history_id = ? LIMIT 1",
			(customer_note_id,),
		)
		row = cur.fetchone()
		cur.close()

		if not row:
			raise ValueError(f"No existe la nota customer_history_id={customer_note_id}.")

		customer_name = get_customer_name(conn, customer_id)

		total_pages, total_chunks = embed_text(
			conn, document_id, display_name, row[0], None, None,
			customer_id, customer_name, "customer_note", progress,
		)

		upsert_log(conn, document_id, {
			"filename": display_name,
			"customer_id": customer_id,
			"customer_name": customer_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": display_name,
			"customer_id": customer_id,
			"customer_name": customer_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": display_name, "error": str(e)})
		write_status(progress)


def process_supplier_note(conn, supplier_id, supplier_note_id, progress):
	"""Modo 'nota de proveedor' (purchase/supplier/insertNote): mismo patron que
	process_customer_note() pero indexado por supplier_id, sin fichero de por medio."""
	document_id = f"supplier_note_{supplier_note_id}"
	display_name = f"Nota #{supplier_note_id}"

	progress["current_file"] = display_name
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": display_name,
		"supplier_id": supplier_id,
		"supplier_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	supplier_name = None

	try:
		cur = conn.cursor()
		cur.execute(
			f"SELECT comment FROM {table('supplier_history')} WHERE supplier_history_id = ? LIMIT 1",
			(supplier_note_id,),
		)
		row = cur.fetchone()
		cur.close()

		if not row:
			raise ValueError(f"No existe la nota supplier_history_id={supplier_note_id}.")

		supplier_name = get_supplier_name(conn, supplier_id)

		total_pages, total_chunks = embed_text(
			conn, document_id, display_name, row[0], None, None,
			None, None, "supplier_note", progress,
			supplier_id=supplier_id, supplier_name=supplier_name,
		)

		upsert_log(conn, document_id, {
			"filename": display_name,
			"supplier_id": supplier_id,
			"supplier_name": supplier_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": display_name,
			"supplier_id": supplier_id,
			"supplier_name": supplier_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": display_name, "error": str(e)})
		write_status(progress)


def process_mail(conn, mail_id, progress):
	"""Modo 'email de cliente' (sale/customer::new_email() al enviar, o
	ModelCatalogMail::getmails() al recibir por IMAP): el email ya esta en la tabla
	`mails` (type='E' enviado / 'R' recibido) - se embebe titulo+cuerpo, sin fichero."""
	document_id = f"mail_{mail_id}"

	cur = conn.cursor()
	cur.execute(
		f"SELECT type, title, message, customer_id FROM {table('mails')} WHERE mail_id = ? LIMIT 1",
		(mail_id,),
	)
	row = cur.fetchone()
	cur.close()

	if not row:
		raise ValueError(f"No existe el email mail_id={mail_id}.")

	mail_type, title, message, customer_id = row
	document_type = "email_sent" if mail_type == "E" else "email_received"
	display_name = title or f"Email #{mail_id}"

	progress["current_file"] = display_name
	progress["current_page"] = 0
	progress["total_pages"] = 0
	write_status(progress)

	upsert_log(conn, document_id, {
		"filename": display_name,
		"customer_id": customer_id,
		"customer_name": None,
		"status": "processing",
		"pages": 0,
		"chunks": 0,
		"error_message": None,
		"date_started": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		"date_finished": None,
	})

	customer_name = None

	try:
		customer_name = get_customer_name(conn, customer_id)

		text = strip_html_tags(title) + "\n\n" + strip_html_tags(message)

		total_pages, total_chunks = embed_text(
			conn, document_id, display_name, text, None, None,
			customer_id, customer_name, document_type, progress,
		)

		upsert_log(conn, document_id, {
			"filename": display_name,
			"customer_id": customer_id,
			"customer_name": customer_name,
			"status": "done",
			"pages": total_pages,
			"chunks": total_chunks,
			"error_message": None,
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

	except Exception as e:
		upsert_log(conn, document_id, {
			"filename": display_name,
			"customer_id": customer_id,
			"customer_name": customer_name,
			"status": "error",
			"pages": 0,
			"chunks": 0,
			"error_message": str(e)[:1000],
			"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
		})

		progress.setdefault("errors", []).append({"file": display_name, "error": str(e)})
		write_status(progress)


# ============================================================
# MODO LOTE GENERICO (pantalla setting/document_embeddings: combo de tipo +
# checkboxes de "no indexados todavia" + boton Indexar). A diferencia de los modos
# anteriores (invocados por PHP con todos los datos ya resueltos, p.ej. product_id +
# ruta absoluta), aqui PHP solo conoce el id de la fila (product_document_id,
# document_id de customer_document/supplier_document, etc.) - estos resolvers
# recuperan el resto (ruta de fichero, product_id/customer_id/supplier_id) de la
# propia BD antes de reutilizar las funciones process_* de siempre.
# ============================================================

def resolve_product_document(conn, product_document_id):
	cur = conn.cursor()
	cur.execute(
		f"SELECT product_id, filename, name FROM {table('product_document')} WHERE product_document_id = ? LIMIT 1",
		(product_document_id,),
	)
	row = cur.fetchone()
	cur.close()

	if not row:
		raise ValueError(f"No existe product_document_id={product_document_id}.")

	product_id, stored_filename, display_name = row
	file_path = str(DOCS_DIR / str(product_id) / stored_filename)

	return product_id, file_path, display_name


def resolve_customer_document(conn, customer_document_id):
	cur = conn.cursor()
	cur.execute(
		f"SELECT customer_id, filename, stored_filename FROM {table('customer_document')} WHERE document_id = ? LIMIT 1",
		(customer_document_id,),
	)
	row = cur.fetchone()
	cur.close()

	if not row:
		raise ValueError(f"No existe customer_document con document_id={customer_document_id}.")

	customer_id, display_name, file_path = row

	return customer_id, file_path, display_name


def resolve_supplier_document(conn, supplier_document_id):
	cur = conn.cursor()
	cur.execute(
		f"SELECT supplier_id, filename, stored_filename FROM {table('supplier_document')} WHERE document_id = ? LIMIT 1",
		(supplier_document_id,),
	)
	row = cur.fetchone()
	cur.close()

	if not row:
		raise ValueError(f"No existe supplier_document con document_id={supplier_document_id}.")

	supplier_id, display_name, file_path = row

	return supplier_id, file_path, display_name


def process_batch_item(conn, item_type, item_id, progress):
	if item_type == "product_document":
		product_id, file_path, display_name = resolve_product_document(conn, item_id)
		process_product_document(conn, product_id, item_id, file_path, display_name, progress)

	elif item_type == "customer_document":
		customer_id, file_path, display_name = resolve_customer_document(conn, item_id)
		process_customer_document(conn, customer_id, item_id, file_path, display_name, progress)

	elif item_type == "customer_note":
		cur = conn.cursor()
		cur.execute(
			f"SELECT customer_id FROM {table('customer_history')} WHERE customer_history_id = ? LIMIT 1",
			(item_id,),
		)
		row = cur.fetchone()
		cur.close()

		if not row:
			raise ValueError(f"No existe la nota customer_history_id={item_id}.")

		process_customer_note(conn, row[0], item_id, progress)

	elif item_type == "supplier_document":
		supplier_id, file_path, display_name = resolve_supplier_document(conn, item_id)
		process_supplier_document(conn, supplier_id, item_id, file_path, display_name, progress)

	elif item_type == "supplier_note":
		cur = conn.cursor()
		cur.execute(
			f"SELECT supplier_id FROM {table('supplier_history')} WHERE supplier_history_id = ? LIMIT 1",
			(item_id,),
		)
		row = cur.fetchone()
		cur.close()

		if not row:
			raise ValueError(f"No existe la nota supplier_history_id={item_id}.")

		process_supplier_note(conn, row[0], item_id, progress)

	elif item_type == "mail":
		process_mail(conn, item_id, progress)

	else:
		raise ValueError(f"Tipo de lote desconocido: {item_type}")


# ============================================================
# MAIN
# ============================================================

def main():
	parser = argparse.ArgumentParser()
	parser.add_argument("--files", help="Nombres de archivo separados por coma, relativos a DOCEMB_DOCS_DIR (modo lote)")
	parser.add_argument("--product-id", type=int, help="ID del producto (modo adjunto directo de producto)")
	parser.add_argument("--product-document-id", type=int, help="ID de if_product_document (modo adjunto directo de producto)")
	parser.add_argument("--customer-id", type=int, help="ID del cliente (modo documento/nota de cliente)")
	parser.add_argument("--customer-document-id", type=int, help="ID de if_customer_document (modo documento de cliente)")
	parser.add_argument("--customer-note-id", type=int, help="ID de if_customer_history (modo nota de cliente)")
	parser.add_argument("--supplier-id", type=int, help="ID del proveedor (modo documento/nota de proveedor)")
	parser.add_argument("--supplier-document-id", type=int, help="ID de if_supplier_document (modo adjunto directo de proveedor)")
	parser.add_argument("--supplier-note-id", type=int, help="ID de if_supplier_history (modo nota de proveedor)")
	parser.add_argument("--mail-id", type=int, help="ID de if_mails (modo email de cliente, enviado o recibido)")
	parser.add_argument("--file", help="Ruta absoluta del PDF (modo adjunto directo)")
	parser.add_argument("--original-name", help="Nombre original del archivo, para mostrar (modo adjunto directo)")
	parser.add_argument("--batch-type", choices=[
		"product_document", "customer_document", "customer_note",
		"supplier_document", "supplier_note", "mail",
	], help="Tipo de elemento para el modo lote de setting/document_embeddings (Generador Representacion vectorial)")
	parser.add_argument("--batch-ids", help="IDs de fila separados por coma para --batch-type")
	args = parser.parse_args()

	if args.product_id and args.product_document_id and args.file:
		display_name = args.original_name or Path(args.file).name

		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": 1,
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			process_product_document(conn, args.product_id, args.product_document_id, args.file, display_name, progress)
			progress["processed_files"] = 1
			write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if args.customer_id and args.customer_document_id and args.file:
		display_name = args.original_name or Path(args.file).name

		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": 1,
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			process_customer_document(conn, args.customer_id, args.customer_document_id, args.file, display_name, progress)
			progress["processed_files"] = 1
			write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if args.supplier_id and args.supplier_document_id and args.file:
		display_name = args.original_name or Path(args.file).name

		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": 1,
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			process_supplier_document(conn, args.supplier_id, args.supplier_document_id, args.file, display_name, progress)
			progress["processed_files"] = 1
			write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if args.customer_id and args.customer_note_id:
		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": 1,
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			process_customer_note(conn, args.customer_id, args.customer_note_id, progress)
			progress["processed_files"] = 1
			write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if args.supplier_id and args.supplier_note_id:
		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": 1,
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			process_supplier_note(conn, args.supplier_id, args.supplier_note_id, progress)
			progress["processed_files"] = 1
			write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if args.mail_id:
		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": 1,
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			process_mail(conn, args.mail_id, progress)
			progress["processed_files"] = 1
			write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if args.batch_type and args.batch_ids:
		ids = [int(x.strip()) for x in args.batch_ids.split(",") if x.strip()]

		progress = {
			"running": True,
			"started_at": now_iso(),
			"finished_at": None,
			"total_files": len(ids),
			"processed_files": 0,
			"current_file": None,
			"current_page": 0,
			"total_pages": 0,
			"errors": [],
		}

		write_status(progress)

		conn = get_connection()

		try:
			for item_id in ids:
				try:
					process_batch_item(conn, args.batch_type, item_id, progress)
				except Exception as e:
					document_id = f"{args.batch_type}_{item_id}"
					upsert_log(conn, document_id, {
						"filename": document_id,
						"status": "error",
						"pages": 0,
						"chunks": 0,
						"error_message": str(e)[:1000],
						"date_finished": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
					})
					progress.setdefault("errors", []).append({"file": document_id, "error": str(e)})
					write_status(progress)

				progress["processed_files"] += 1
				write_status(progress)
		finally:
			conn.close()

		progress["running"] = False
		progress["current_file"] = None
		progress["finished_at"] = now_iso()
		write_status(progress)
		return

	if not args.files:
		parser.error(
			"Debes indicar --files, o bien --product-id/--product-document-id/--file, "
			"o bien --customer-id + (--customer-document-id/--file | --customer-note-id), "
			"o bien --supplier-id/--supplier-document-id/--file, "
			"o bien --mail-id, "
			"o bien --batch-type/--batch-ids"
		)

	files = [f.strip() for f in args.files.split(",") if f.strip()]

	progress = {
		"running": True,
		"started_at": now_iso(),
		"finished_at": None,
		"total_files": len(files),
		"processed_files": 0,
		"current_file": None,
		"current_page": 0,
		"total_pages": 0,
		"errors": [],
	}

	write_status(progress)

	conn = get_connection()

	try:
		for filename in files:
			process_file(conn, filename, progress)
			progress["processed_files"] += 1
			write_status(progress)
	finally:
		conn.close()

	progress["running"] = False
	progress["current_file"] = None
	progress["finished_at"] = now_iso()
	write_status(progress)


if __name__ == "__main__":
	main()
