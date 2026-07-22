<?php
class ModelReportPyg extends Model {
	public function getStructure() {
		$query = $this->db->query("SELECT code, name, accounts, level
			FROM " . DB_PREFIX . "ctab9
			ORDER BY order_code ASC");

		return $query->rows;
	}

	public function getAccountBalance($prefixes, $data) {
		if (!$prefixes) {
			return 0.0;
		}

		$conditions = array();

		foreach ($prefixes as $prefix) {
			$conditions[] = "e.account LIKE '" . $this->db->escape($prefix) . "%'";
		}

		$sql = "SELECT COALESCE(SUM(e.credit), 0) - COALESCE(SUM(e.debit), 0) AS balance
			FROM " . DB_PREFIX . "ctab8 e
			WHERE (" . implode(' OR ', $conditions) . ")";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND e.line_date >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND e.line_date <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return (float)$query->row['balance'];
	}
}
