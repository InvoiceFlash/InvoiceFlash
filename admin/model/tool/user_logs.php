<?php
class ModelToolUserLogs extends Model {

	public function addLog($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_activity_log` SET
			user_id       = '" . (int)$data['user_id'] . "',
			username      = '" . $this->db->escape($data['username']) . "',
			action        = '" . $this->db->escape($data['action']) . "',
			document_type = '" . $this->db->escape($data['document_type']) . "',
			document_id   = '" . (int)$data['document_id'] . "',
			ip            = '" . $this->db->escape($data['ip']) . "',
			date_added    = NOW()");
	}

	public function getLogs($data = array()) {
		$sql = "SELECT l.*,
			CASE l.document_type
				WHEN 'sale_invoice'     THEN CONCAT(si.invoice_prefix, LPAD(si.invoice_no, 5, '0'))
				WHEN 'purchase_invoice' THEN CONCAT(pi.invoice_prefix, LPAD(pi.invoice_no, 5, '0'))
				ELSE ''
			END AS document_ref,
			CASE l.document_type
				WHEN 'sale_invoice'     THEN si.invoice_id
				WHEN 'purchase_invoice' THEN pi.invoice_id
				ELSE 0
			END AS doc_id_check
			FROM `" . DB_PREFIX . "user_activity_log` l
			LEFT JOIN `" . DB_PREFIX . "invoice` si
				ON l.document_type = 'sale_invoice' AND l.document_id = si.invoice_id
			LEFT JOIN `" . DB_PREFIX . "purchase_invoice` pi
				ON l.document_type = 'purchase_invoice' AND l.document_id = pi.invoice_id
			WHERE 1";

		if (!empty($data['filter_username'])) {
			$sql .= " AND l.username LIKE '%" . $this->db->escape($data['filter_username']) . "%'";
		}

		if (!empty($data['filter_action'])) {
			$sql .= " AND l.action = '" . $this->db->escape($data['filter_action']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(l.date_added) >= '" . $this->db->escape($data['filter_date_from']) . "'";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(l.date_added) <= '" . $this->db->escape($data['filter_date_to']) . "'";
		}

		$sql .= " ORDER BY l.date_added DESC";

		if (isset($data['start']) && isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		return $this->db->query($sql)->rows;
	}

	public function getTotalLogs($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user_activity_log` l WHERE 1";

		if (!empty($data['filter_username'])) {
			$sql .= " AND l.username LIKE '%" . $this->db->escape($data['filter_username']) . "%'";
		}

		if (!empty($data['filter_action'])) {
			$sql .= " AND l.action = '" . $this->db->escape($data['filter_action']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(l.date_added) >= '" . $this->db->escape($data['filter_date_from']) . "'";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(l.date_added) <= '" . $this->db->escape($data['filter_date_to']) . "'";
		}

		return (int)$this->db->query($sql)->row['total'];
	}

	public function getUsers() {
		return $this->db->query("SELECT DISTINCT username FROM `" . DB_PREFIX . "user_activity_log` ORDER BY username ASC")->rows;
	}
}
?>
