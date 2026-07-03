<?php
class ModelReportPurchaselist extends Model {
	public function getInvoices($data = array()) {

		$sql = "SELECT o.invoice_id, o.email, o.telephone,
		o.payment_tax_id AS tax_id,
		COALESCE(NULLIF(s.company,''), NULLIF(o.payment_company,''), o.shipping_company) AS customer,
		o.date_added AS date_added,
		o.total, o.currency_code, o.currency_value,
		(SELECT SUM(it.value) FROM `" . DB_PREFIX . "purchase_invoice_total` it WHERE it.invoice_id = o.invoice_id AND it.code = 'tax') AS tax,
		(SELECT os.name FROM `" . DB_PREFIX . "invoice_status` os WHERE os.invoice_status_id = o.invoice_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status
		FROM `" . DB_PREFIX . "purchase_invoice` o
		LEFT JOIN `" . DB_PREFIX . "supplier` s ON o.supplier_id = s.supplier_id
		";

		if (!is_null($data['filter_invoice_status_id']) && $data['filter_invoice_status_id'] <> 0) {
			$sql .= " where o.invoice_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " where o.invoice_status_id > '0'";
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$sql .= " ORDER BY o.invoice_id DESC";

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

	public function getTotalInvoices($data = array()) {

		$sql = "select count(invoice_id) as total from `" . DB_PREFIX . "purchase_invoice` o  ";

		if (!is_null($data['filter_invoice_status_id']) && $data['filter_invoice_status_id'] <> 0) {
			$sql .= " where o.invoice_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " where o.invoice_status_id > '0'";
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>
