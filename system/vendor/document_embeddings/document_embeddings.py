import os
import re
import sys
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


def insert_chunk(conn, document_id, filename, product_id, product_name, page, chunk_number, text, embedding):
	vector_text = "[" + ",".join(str(float(x)) for x in embedding) + "]"

	cur = conn.cursor()
	cur.execute(
		f"""
		INSERT INTO {table('document_chunks')}
		(document_id, document, product_id, product_name, page, chunk_number, chunk_text, document_type, embedding)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, VEC_FromText(?))
		""",
		(
			document_id,
			filename,
			product_id,
			product_name,
			page,
			chunk_number,
			text,
			DOCUMENT_TYPE,
			vector_text,
		),
	)
	conn.commit()
	cur.close()


# ============================================================
# PROCESAR UN PDF (nucleo compartido por los dos modos de invocacion)
# ============================================================

def embed_pdf(conn, document_id, display_name, pdf_path, product_id, product_name, progress):
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
				conn, document_id, display_name, product_id, product_name,
				page_number, chunk_number, chunk_text, embedding,
			)
			total_chunks += 1

	doc.close()

	return total_pages, total_chunks


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
			conn, document_id, filename, pdf_path, product_id, product_name, progress
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
			conn, document_id, display_name, pdf_path, product_id, product_name, progress
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


# ============================================================
# MAIN
# ============================================================

def main():
	parser = argparse.ArgumentParser()
	parser.add_argument("--files", help="Nombres de archivo separados por coma, relativos a DOCEMB_DOCS_DIR (modo lote)")
	parser.add_argument("--product-id", type=int, help="ID del producto (modo adjunto directo)")
	parser.add_argument("--product-document-id", type=int, help="ID de if_product_document (modo adjunto directo)")
	parser.add_argument("--file", help="Ruta absoluta del PDF (modo adjunto directo)")
	parser.add_argument("--original-name", help="Nombre original del archivo, para mostrar (modo adjunto directo)")
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

	if not args.files:
		parser.error("Debes indicar --files, o bien --product-id/--product-document-id/--file")

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
