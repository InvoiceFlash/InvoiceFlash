<?php

class ModelReportStockMovement extends Model {
	private function buildSql($data) {
		$sql = "SELECT sm.*, p.model AS current_model FROM " . DB_PREFIX . "stock_movement sm LEFT JOIN " . DB_PREFIX . "product p ON sm.product_id = p.product_id WHERE 1 = 1";

		if (!empty($data['filter_product'])) {
			$sql .= " AND (sm.product_name LIKE '%" . $this->db->escape($data['filter_product']) . "%' OR sm.model LIKE '%" . $this->db->escape($data['filter_product']) . "%')";
		}

		if (!empty($data['filter_product_id'])) {
			$sql .= " AND sm.product_id = '" . (int)$data['filter_product_id'] . "'";
		}

		if (!empty($data['filter_movement_type'])) {
			$sql .= " AND sm.movement_type = '" . $this->db->escape($data['filter_movement_type']) . "'";
		}

		if (!empty($data['filter_document_type'])) {
			$sql .= " AND sm.document_type = '" . $this->db->escape($data['filter_document_type']) . "'";
		}

		if (!empty($data['filter_party'])) {
			$sql .= " AND sm.party_name LIKE '%" . $this->db->escape($data['filter_party']) . "%'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(sm.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(sm.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
		}

		return $sql;
	}

	public function getMovements($data = array()) {
		$sql = $this->buildSql($data);

		$sql .= " ORDER BY sm.date_added DESC, sm.stock_movement_id DESC";

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

	public function getTotalMovements($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (" . $this->buildSql($data) . ") AS t";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>
