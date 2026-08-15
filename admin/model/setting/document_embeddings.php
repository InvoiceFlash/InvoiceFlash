<?php
class ModelSettingDocumentEmbeddings extends Model {
	// Ruta base usada solo para resolver, en el proceso Python, la ruta absoluta de
	// los PDF de la categoria "product_document" (docs/products/<product_id>/<filename>)
	// - las otras categorias (customer_document, supplier_document) ya guardan la
	// ruta absoluta completa en su propia fila, no hace falta este directorio.
	public function getDocsDir() {
		$project_root = rtrim(str_replace('\\', '/', dirname(DIR_APPLICATION)), '/');

		return $project_root . '/docs/products/';
	}

	// Tipos validos del combo de la pantalla "Generador Representacion vectorial",
	// en el mismo orden en que deben aparecer las opciones.
	public function getTypes() {
		return array(
			'product_document',
			'mail_in',
			'mail_out',
			'customer_note',
			'customer_document',
			'supplier_note',
			'supplier_document',
		);
	}

	// Todos los elementos de un tipo dado que TODAVIA no tienen un embedding
	// 'done' en if_document_embedding_log - es decir, la cola pendiente de indexar
	// manualmente desde este panel. Cada fila devuelta trae 'id' (el id real de la
	// fila en su tabla de origen, lo que se le pasa a Python via --batch-ids),
	// 'title', 'related' (cliente/proveedor/producto/destinatario) y 'date'.
	public function getUnindexedItems($type) {
		switch ($type) {
			case 'product_document':
				return $this->getUnindexedProductDocuments();
			case 'mail_in':
				return $this->getUnindexedMails('R');
			case 'mail_out':
				return $this->getUnindexedMails('E');
			case 'customer_note':
				return $this->getUnindexedCustomerNotes();
			case 'customer_document':
				return $this->getUnindexedCustomerDocuments();
			case 'supplier_note':
				return $this->getUnindexedSupplierNotes();
			case 'supplier_document':
				return $this->getUnindexedSupplierDocuments();
			default:
				return array();
		}
	}

	private function getUnindexedProductDocuments() {
		$sql = "SELECT pd.product_document_id AS id, pd.name AS title, pd.date_added AS date,
				p.name AS related
			FROM `" . DB_PREFIX . "product_document` pd
			LEFT JOIN (
				SELECT pdesc.product_id, pdesc.name
				FROM `" . DB_PREFIX . "product_description` pdesc
				WHERE pdesc.language_id = '" . (int)$this->config->get('config_language_id') . "'
			) p ON p.product_id = pd.product_id
			LEFT JOIN `" . DB_PREFIX . "document_embedding_log` log
				ON log.document_id = CONCAT('product_document_', pd.product_document_id) AND log.status = 'done'
			WHERE log.document_embedding_log_id IS NULL AND LOWER(pd.filename) LIKE '%.pdf'
			ORDER BY pd.date_added DESC";

		return $this->db->query($sql)->rows;
	}

	private function getUnindexedMails($type) {
		$sql = "SELECT m.mail_id AS id, m.title AS title, m.date_added AS date,
				COALESCE(c.company, m.client) AS related
			FROM `" . DB_PREFIX . "mails` m
			LEFT JOIN `" . DB_PREFIX . "customer` c ON c.customer_id = m.customer_id
			LEFT JOIN `" . DB_PREFIX . "document_embedding_log` log
				ON log.document_id = CONCAT('mail_', m.mail_id) AND log.status = 'done'
			WHERE m.type = '" . $this->db->escape($type) . "' AND m.bleido <> 2 AND log.document_embedding_log_id IS NULL
			ORDER BY m.date_added DESC";

		return $this->db->query($sql)->rows;
	}

	private function getUnindexedCustomerNotes() {
		$sql = "SELECT ch.customer_history_id AS id, ch.comment AS title, ch.date_added AS date,
				c.company AS related
			FROM `" . DB_PREFIX . "customer_history` ch
			LEFT JOIN `" . DB_PREFIX . "customer` c ON c.customer_id = ch.customer_id
			LEFT JOIN `" . DB_PREFIX . "document_embedding_log` log
				ON log.document_id = CONCAT('customer_note_', ch.customer_history_id) AND log.status = 'done'
			WHERE log.document_embedding_log_id IS NULL
			ORDER BY ch.date_added DESC";

		return $this->db->query($sql)->rows;
	}

	private function getUnindexedCustomerDocuments() {
		$sql = "SELECT cd.document_id AS id, cd.filename AS title, cd.date_added AS date,
				c.company AS related
			FROM `" . DB_PREFIX . "customer_document` cd
			LEFT JOIN `" . DB_PREFIX . "customer` c ON c.customer_id = cd.customer_id
			LEFT JOIN `" . DB_PREFIX . "document_embedding_log` log
				ON log.document_id = CONCAT('customer_document_', cd.document_id) AND log.status = 'done'
			WHERE log.document_embedding_log_id IS NULL AND LOWER(cd.filename) LIKE '%.pdf'
			ORDER BY cd.date_added DESC";

		return $this->db->query($sql)->rows;
	}

	private function getUnindexedSupplierNotes() {
		$sql = "SELECT sh.supplier_history_id AS id, sh.comment AS title, sh.date_added AS date,
				s.company AS related
			FROM `" . DB_PREFIX . "supplier_history` sh
			LEFT JOIN `" . DB_PREFIX . "supplier` s ON s.supplier_id = sh.supplier_id
			LEFT JOIN `" . DB_PREFIX . "document_embedding_log` log
				ON log.document_id = CONCAT('supplier_note_', sh.supplier_history_id) AND log.status = 'done'
			WHERE log.document_embedding_log_id IS NULL
			ORDER BY sh.date_added DESC";

		return $this->db->query($sql)->rows;
	}

	private function getUnindexedSupplierDocuments() {
		$sql = "SELECT sd.document_id AS id, sd.filename AS title, sd.date_added AS date,
				s.company AS related
			FROM `" . DB_PREFIX . "supplier_document` sd
			LEFT JOIN `" . DB_PREFIX . "supplier` s ON s.supplier_id = sd.supplier_id
			LEFT JOIN `" . DB_PREFIX . "document_embedding_log` log
				ON log.document_id = CONCAT('supplier_document_', sd.document_id) AND log.status = 'done'
			WHERE log.document_embedding_log_id IS NULL AND LOWER(sd.filename) LIKE '%.pdf'
			ORDER BY sd.date_added DESC";

		return $this->db->query($sql)->rows;
	}
}
