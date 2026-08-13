<?php
class ModelToolPendingInvoices extends Model {
	public function getPendingInvoices($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "purchase_invoice_pending_review ORDER BY date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		return $this->db->query($sql)->rows;
	}

	public function getTotalPendingInvoices() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase_invoice_pending_review");

		return $query->row['total'];
	}

	public function getPendingInvoice($pending_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice_pending_review WHERE pending_id = '" . (int)$pending_id . "'");

		return $query->row;
	}

	public function deletePendingInvoice($pending_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_invoice_pending_review WHERE pending_id = '" . (int)$pending_id . "'");
	}
}
