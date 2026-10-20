<?php
class ModelSaleRemittances extends Model {
	public function deleteRemittance($remittance_id) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "receipt SET remittance_id = 0 WHERE remittance_id = '" . (int)$remittance_id . "'" );

		$this->db->query("DELETE FROM `" . DB_PREFIX . "remittances` WHERE remittance_id = '" . (int)$remittance_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "remittances_lines WHERE remittance_id = '" . (int)$remittance_id . "'");
	}

	public function getRemittancesLines($remittance_id) {
		$sql = "SELECT rl.receipt_id, c.customer_id, c.company, rl.amount, rl.date_vto, c.bank_cc, CONCAT(i.invoice_prefix, i.invoice_id) AS invoice_no
			FROM " . DB_PREFIX . "remittances_lines rl
			LEFT JOIN " . DB_PREFIX . "remittances r ON r.remittance_id = rl.remittance_id
			LEFT JOIN `" . DB_PREFIX . "receipt` re ON rl.receipt_id = re.receipt_id
			LEFT JOIN `" . DB_PREFIX . "invoice` i ON i.invoice_id = re.invoice_id
			LEFT JOIN `" . DB_PREFIX . "customer` c ON c.customer_id = i.customer_id WHERE rl.remittance_id = $remittance_id";
		
		$query = $this->db->query($sql);

		return $query->rows;
		
	}

	public function getRemittanceLinesTotal($remittance_id) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "remittances_lines` rl WHERE remittance_id = $remittance_id";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalRemittances($data = array()) {
		$sql = "SELECT COUNT(*) AS total
		FROM `" . DB_PREFIX . "remittances` r
		LEFT JOIN `" . DB_PREFIX . "invoice` i ON i.invoice_id = (SELECT invoice_id FROM `" . DB_PREFIX . "receipt` WHERE remittance_id = r.remittance_id LIMIT 1)";

		if (isset($data['filter_remittance_id'])) {
			$sql .= " AND r.remittance_id = '" . (int)$data['filter_remittance_id'] . "'";
		}

		if (isset($data['filter_total'])) {
			$sql .= " AND r.amount = '" . (float)$data['filter_total'] . "'";
		}

		if (isset($data['filter_date_added'])) {
			$sql .= " AND r.date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getRemittances($data = array()) {
		$sql = "SELECT r.remittance_id, r.amount AS total, r.date_added
		FROM `" . DB_PREFIX . "remittances` r
		LEFT JOIN `" . DB_PREFIX . "invoice` i ON i.invoice_id = (SELECT invoice_id FROM `" . DB_PREFIX . "receipt` WHERE remittance_id = r.remittance_id LIMIT 1) WHERE 1 = 1";

		if (isset($data['filter_remittance_id'])) {
			$sql .= " AND r.remittance_id = '" . (int)$data['filter_remittance_id'] . "'";
		}

		if (isset($data['filter_total'])) {
			$sql .= " AND r.amount = '" . (float)$data['filter_total'] . "'";
		}

		if (isset($data['filter_date_added'])) {
			$sql .= " AND r.date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
		}
				 
		$sort_data = array(
			'r.remittance_id',
			'r.amount',
			'r.date_added'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.remittance_id";	
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

	private $sepa_ready = false;

	// Tabla de la secuencia SEPA (FRST/RCUR) de cada cliente; tambien esta en install/invoiceflash.sql.
	private function ensureSepaTable() {
		if ($this->sepa_ready) {
			return;
		}

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sepa_mandate` (
			`customer_id` int(11) NOT NULL,
			`remittance_id` int(11) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`customer_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->sepa_ready = true;
	}

	public function getSepaPayments($remittance_ids) {
		$ids = implode(',', array_map('intval', $remittance_ids));

		$query = $this->db->query("SELECT rl.remittance_id, rl.receipt_id, rl.amount, rl.date_vto, re.currency_code, c.customer_id, c.company, c.bank_cc, c.bic, c.date_added AS customer_date_added, CONCAT(i.invoice_prefix, i.invoice_id) AS invoice_no
			FROM `" . DB_PREFIX . "remittances_lines` rl
			LEFT JOIN `" . DB_PREFIX . "receipt` re ON re.receipt_id = rl.receipt_id
			LEFT JOIN `" . DB_PREFIX . "invoice` i ON i.invoice_id = re.invoice_id
			LEFT JOIN `" . DB_PREFIX . "customer` c ON c.customer_id = i.customer_id
			WHERE rl.remittance_id IN (" . $ids . ")
			ORDER BY rl.remittance_id, rl.remittance_lines_id");

		return $query->rows;
	}

	public function countRemittancesByIds($remittance_ids) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "remittances` WHERE remittance_id IN (" . implode(',', array_map('intval', $remittance_ids)) . ")");

		return (int)$query->row['total'];
	}

	// FRST la primera vez que se cobra a un cliente (y al volver a descargar esa misma remesa); RCUR despues.
	public function getSepaSequence($customer_id, $remittance_id) {
		$this->ensureSepaTable();

		$query = $this->db->query("SELECT remittance_id FROM `" . DB_PREFIX . "sepa_mandate` WHERE customer_id = '" . (int)$customer_id . "'");

		if (!$query->num_rows || (int)$query->row['remittance_id'] === (int)$remittance_id) {
			return 'FRST';
		}

		return 'RCUR';
	}

	public function markSepaFirst($customer_id, $remittance_id) {
		$this->ensureSepaTable();

		$this->db->query("INSERT IGNORE INTO `" . DB_PREFIX . "sepa_mandate` SET customer_id = '" . (int)$customer_id . "', remittance_id = '" . (int)$remittance_id . "', date_added = NOW()");
	}

	public function getRemittance($remittance_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "remittances` WHERE remittance_id = $remittance_id");

		return $query->row;
	}
		
	
}
?>