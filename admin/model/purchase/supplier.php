<?php

class ModelPurchaseSupplier extends Model {
	public function addSupplier($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "supplier SET
			firstname = '" . $this->db->escape($data['firstname']) . "',
			lastname = '" . $this->db->escape($data['lastname']) . "',
			company = '" . $this->db->escape($data['company']) . "',
			company_id = '" . $this->db->escape($data['company_id']) . "',
			tax_id = '" . $this->db->escape($data['tax_id']) . "',
			email = '" . $this->db->escape($data['email']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			fax = '" . $this->db->escape($data['fax']) . "',
			web = '" . $this->db->escape($data['web']) . "',
			address_1 = '" . $this->db->escape($data['address_1']) . "',
			address_2 = '" . $this->db->escape($data['address_2']) . "',
			city = '" . $this->db->escape($data['city']) . "',
			postcode = '" . $this->db->escape($data['postcode']) . "',
			country_id = '" . (int)$data['country_id'] . "',
			zone_id = '" . (int)$data['zone_id'] . "',
			status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "',
			date_added = NOW(),
			date_modified = NOW()");

		return $this->db->getLastId();
	}

	public function editSupplier($supplier_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "supplier SET
			firstname = '" . $this->db->escape($data['firstname']) . "',
			lastname = '" . $this->db->escape($data['lastname']) . "',
			company = '" . $this->db->escape($data['company']) . "',
			company_id = '" . $this->db->escape($data['company_id']) . "',
			tax_id = '" . $this->db->escape($data['tax_id']) . "',
			email = '" . $this->db->escape($data['email']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			fax = '" . $this->db->escape($data['fax']) . "',
			web = '" . $this->db->escape($data['web']) . "',
			address_1 = '" . $this->db->escape($data['address_1']) . "',
			address_2 = '" . $this->db->escape($data['address_2']) . "',
			city = '" . $this->db->escape($data['city']) . "',
			postcode = '" . $this->db->escape($data['postcode']) . "',
			country_id = '" . (int)$data['country_id'] . "',
			zone_id = '" . (int)$data['zone_id'] . "',
			status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "',
			date_modified = NOW()
			WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function deleteSupplier($supplier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT s.*, c.name AS country, z.name AS zone, z.code AS zone_code FROM " . DB_PREFIX . "supplier s LEFT JOIN " . DB_PREFIX . "country c ON s.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON s.zone_id = z.zone_id WHERE s.supplier_id = '" . (int)$supplier_id . "'");

		return $query->row;
	}

	// Pestaña "Pedidos" (mismo patron que ModelSaleCustomer::getordersCustomer()).
	public function getPurchaseOrdersSupplier($supplier_id) {
		$query = $this->db->query("SELECT po.purchase_order_id, po.po_number, po.total, po.currency_code, po.currency_value, po.date_added, pos.name AS status
			FROM " . DB_PREFIX . "purchase_order po
			LEFT JOIN " . DB_PREFIX . "purchase_order_status pos ON po.purchase_order_status_id = pos.purchase_order_status_id AND pos.language_id = '" . (int)$this->config->get('config_language_id') . "'
			WHERE po.supplier_id = '" . (int)$supplier_id . "'
			ORDER BY po.date_added DESC");

		return $query->rows;
	}

	// Pestaña "Recepciones": mismos Pedidos de Compra pero solo los que ya tienen
	// mercancia recibida (total o parcialmente) - no existe un documento de "albaran de
	// compra" propio en este proyecto, se reutiliza purchase_order filtrando por estado.
	// Se resuelven los status_id dinamicamente por nombre (idioma 1, canonico) en vez de
	// asumir que "Received"/"Partially Received" son siempre los ids 3 y 4.
	public function getReceivedPurchaseOrdersSupplier($supplier_id) {
		$query = $this->db->query("SELECT po.purchase_order_id, po.po_number, po.total, po.currency_code, po.currency_value, po.date_added, pos.name AS status
			FROM " . DB_PREFIX . "purchase_order po
			LEFT JOIN " . DB_PREFIX . "purchase_order_status pos ON po.purchase_order_status_id = pos.purchase_order_status_id AND pos.language_id = '" . (int)$this->config->get('config_language_id') . "'
			WHERE po.supplier_id = '" . (int)$supplier_id . "'
			AND po.purchase_order_status_id IN (
				SELECT purchase_order_status_id FROM " . DB_PREFIX . "purchase_order_status WHERE language_id = 1 AND name IN ('Received', 'Partially Received')
			)
			ORDER BY po.date_added DESC");

		return $query->rows;
	}

	public function getSupplierByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "supplier WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getSupplierContactByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_contacts WHERE LCASE(cemail) = '" . $this->db->escape(strtolower($email)) . "'");

		return $query->row;
	}

	// Mismo patron que ModelSaleCustomer::getEmailsByCustomerId() - if_mails ya tiene
	// columna supplier_id (compartida con customer_id/potential_id).
	public function getEmailsBySupplierId($supplier_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "mails` WHERE bleido != 2 AND supplier_id=" . (int)$supplier_id . " ORDER BY date_added DESC");

		return $query->rows;
	}

	public function getSuppliersByIds($supplier_ids) {
		if (!$supplier_ids) {
			return array();
		}

		$supplier_ids = array_map('intval', $supplier_ids);

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier WHERE supplier_id IN (" . implode(',', $supplier_ids) . ")");

		return $query->rows;
	}

	public function getSuppliers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "supplier";

		$where = array();

		if (!empty($data['filter_company'])) {
			$where[] = "company LIKE '" . $this->db->escape($data['filter_company']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$where[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$where[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($where) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}

		$sort_data = array(
			'company',
			'name',
			'email',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY company";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalSuppliers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier";

		$where = array();

		if (!empty($data['filter_company'])) {
			$where[] = "company LIKE '" . $this->db->escape($data['filter_company']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$where[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$where[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($where) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function autocomplete($filter_name) {
		$query = $this->db->query("SELECT supplier_id, company, firstname, lastname, email, telephone, fax FROM " . DB_PREFIX . "supplier WHERE company LIKE '" . $this->db->escape($filter_name) . "%' AND status = '1' ORDER BY company ASC LIMIT 0,5");

		return $query->rows;
	}

	public function getSupplierContacts($supplier_id) {
		$query = $this->db->query("SELECT supplier_contacts_id, cname, date_added, ctelef1, cpuesto, cemail FROM " . DB_PREFIX . "supplier_contacts WHERE supplier_id = " . (int)$supplier_id);

		return $query->rows;
	}

	public function getSupplierContactsTotal($supplier_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier_contacts WHERE supplier_id = " . (int)$supplier_id);

		return $query->row['total'];
	}

	public function getSupplierContact($supplier_contacts_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_contacts WHERE supplier_contacts_id = " . (int)$supplier_contacts_id);

		return $query->row;
	}

	public function addSupplierContact($data, $supplier_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "supplier_contacts SET
			supplier_id = " . (int)$supplier_id . ",
			cname = '" . $this->db->escape($data['name']) . "',
			cpuesto = '" . $this->db->escape($data['puesto']) . "',
			cemail = '" . $this->db->escape($data['email']) . "',
			ctelef1 = '" . $this->db->escape($data['telef1']) . "',
			ctelef2 = '" . $this->db->escape($data['telef2']) . "',
			mnotas = '" . $this->db->escape($data['notas']) . "',
			nusualta = " . (int)$this->user->getID() . ",
			caplalta = 'web',
			tultmod = now(),
			nusuultmod = " . (int)$this->user->getID() . ",
			caplultmod = 'web',
			date_added = now()");
	}

	public function editSupplierContact($data, $supplier_contacts_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "supplier_contacts SET
			cname = '" . $this->db->escape($data['name']) . "',
			cpuesto = '" . $this->db->escape($data['puesto']) . "',
			cemail = '" . $this->db->escape($data['email']) . "',
			ctelef1 = '" . $this->db->escape($data['telef1']) . "',
			ctelef2 = '" . $this->db->escape($data['telef2']) . "',
			mnotas = '" . $this->db->escape($data['notas']) . "',
			tultmod = now(),
			nusuultmod = " . (int)$this->user->getID() . ",
			caplultmod = 'web' WHERE supplier_contacts_id = " . (int)$supplier_contacts_id);
	}

	public function deleteSupplierContact($supplier_contacts_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier_contacts WHERE supplier_contacts_id = " . (int)$supplier_contacts_id);
	}

	public function getSupplierDocuments($supplier_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_document WHERE supplier_id = '" . (int)$supplier_id . "' ORDER BY date_added DESC");

		return $query->rows;
	}

	public function getSupplierDocument($document_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier_document WHERE document_id = '" . (int)$document_id . "'");

		return $query->row;
	}

	public function addSupplierDocument($supplier_id, $filename, $stored_filename) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "supplier_document SET
			supplier_id = '" . (int)$supplier_id . "',
			filename = '" . $this->db->escape($filename) . "',
			stored_filename = '" . $this->db->escape($stored_filename) . "',
			date_added = NOW(),
			user_id = '" . (int)$this->user->getId() . "'");

		return $this->db->getLastId();
	}

	public function deleteSupplierDocument($document_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier_document WHERE document_id = '" . (int)$document_id . "'");
	}

	// Limpia los fragmentos/embeddings RAG generados para este documento de proveedor
	// (ver document_embeddings.py, document_id = "supplier_document_<id>") - se llama
	// antes de borrar la fila de supplier_document. Mismo patron que
	// ModelSaleCustomer::deleteCustomerDocumentEmbeddings().
	public function deleteSupplierDocumentEmbeddings($document_id) {
		$doc_id = 'supplier_document_' . (int)$document_id;

		$this->db->query("DELETE FROM " . DB_PREFIX . "document_chunks WHERE document_id = '" . $this->db->escape($doc_id) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "document_embedding_log WHERE document_id = '" . $this->db->escape($doc_id) . "'");
	}

	public function addSupplierNote($data, $supplier_id) {
		$this->installNotes();

		$this->db->query("INSERT INTO `" . DB_PREFIX . "supplier_history` SET
			`supplier_id` = '" . (int)$supplier_id . "',
			`comment` = '" . $this->db->escape($data['comment']) . "',
			`date_added` = NOW(),
			`user_id` = '" . (int)$this->user->getId() . "'");

		return $this->db->getLastId();
	}

	public function deleteSupplierNote($note_id) {
		$this->installNotes();

		$this->db->query("DELETE FROM `" . DB_PREFIX . "supplier_history` WHERE supplier_history_id = '" . (int)$note_id . "'");
	}

	// Limpia los fragmentos/embeddings RAG generados para esta nota concreta (ver
	// document_embeddings.py::process_supplier_note(), document_id =
	// "supplier_note_<id>") - se llama antes de borrar la fila de supplier_history.
	// Mismo patron que deleteSupplierDocumentEmbeddings().
	public function deleteSupplierNoteEmbeddings($note_id) {
		$doc_id = 'supplier_note_' . (int)$note_id;

		$this->db->query("DELETE FROM " . DB_PREFIX . "document_chunks WHERE document_id = '" . $this->db->escape($doc_id) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "document_embedding_log WHERE document_id = '" . $this->db->escape($doc_id) . "'");
	}

	public function getSupplierNotes($supplier_id) {
		$this->installNotes();

		$query = $this->db->query("SELECT supplier_history_id, comment, date_added FROM `" . DB_PREFIX . "supplier_history` WHERE supplier_id = '" . (int)$supplier_id . "' ORDER BY date_added DESC");

		return $query->rows;
	}

	public function getSupplierNote($note_id) {
		$this->installNotes();

		$query = $this->db->query("SELECT sh.comment, sh.date_added, u.username AS user FROM " . DB_PREFIX . "supplier_history sh LEFT JOIN `" . DB_PREFIX . "user` u ON sh.user_id = u.user_id WHERE sh.supplier_history_id = " . (int)$note_id);

		return $query->row;
	}

	public function getProductsSupplier($supplier_id) {
		$query = $this->db->query("
			SELECT pip.product_id, pip.name, pip.quantity, pip.total,
			       pi.invoice_id, pi.date_added
			FROM `" . DB_PREFIX . "purchase_invoice_product` pip
			JOIN `" . DB_PREFIX . "purchase_invoice` pi ON pip.invoice_id = pi.invoice_id
			WHERE pi.supplier_id = '" . (int)$supplier_id . "'
			ORDER BY pi.date_added DESC
		");
		return $query->rows;
	}

	public function getInvoicesSupplierTotal($supplier_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "purchase_invoice` WHERE supplier_id = '" . (int)$supplier_id . "'");

		return $query->row['total'];
	}

	public function getInvoicesSupplier($supplier_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "purchase_invoice` WHERE supplier_id = '" . (int)$supplier_id . "' ORDER BY date_added DESC");

		return $query->rows;
	}

	private function installNotes() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "supplier_history` (
			`supplier_history_id` int(11) NOT NULL AUTO_INCREMENT,
			`supplier_id` int(11) NOT NULL,
			`user_id` int(11) NOT NULL,
			`comment` text NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`supplier_history_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	}
}
?>
