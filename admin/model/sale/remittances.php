<?php
class ModelSaleRemittances extends Model {
	public function deleteRemittance($remittance_id) {
		
		$this->db->query("UPDATE " . DB_PREFIX . "receipt SET remittance_id = 0 WHERE remittance_id = '" . (int)$remittance_id . "'" );

		$this->db->query("DELETE FROM `" . DB_PREFIX . "remittances` WHERE remittance_id = '" . (int)$remittance_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "remittances_lines WHERE remittance_id = '" . (int)$remittance_id . "'");
	}

	public function getRemittancesLines($remittance_id) {
		$sql = "SELECT rl.receipt_id, c.customer_id, c.company, rl.amount, rl.date_vto, fc.bank_cc, CONCAT(i.invoice_prefix, i.invoice_id) AS invoice_no
			FROM " . DB_PREFIX . "remittances_lines rl 
			LEFT JOIN " . DB_PREFIX . "remittances r ON r.remittance_id = rl.remittance_id
			LEFT JOIN `" . DB_PREFIX . "receipt` re ON rl.receipt_id = re.receipt_id
			LEFT JOIN `" . DB_PREFIX . "invoice` i ON i.invoice_id = re.invoice_id
			LEFT JOIN `" . DB_PREFIX . "customer` c ON c.customer_id = i.customer_id 
			LEFT JOIN `" . DB_PREFIX . "fl_customers` fc ON fc.customer_id = c.customer_id WHERE rl.remittance_id = $remittance_id";
		
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

	public function getRemittance($remittance_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "remittances` WHERE remittance_id = $remittance_id");

		return $query->row;
	}
		
	
}
?>