<?php
class ModelReportTrialBalance extends Model {
	public function getGroupBalances($data, $level) {
		$sql = "SELECT LEFT(e.account, " . (int)$level . ") AS code, SUM(e.debit) AS debit, SUM(e.credit) AS credit
			FROM " . DB_PREFIX . "ctab8 e
			WHERE 1 = 1";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND e.line_date >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND e.line_date <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if (!empty($data['filter_account_start'])) {
			$sql .= " AND e.account >= '" . $this->db->escape($data['filter_account_start']) . "'";
		}

		if (!empty($data['filter_account_end'])) {
			$sql .= " AND e.account <= '" . $this->db->escape($data['filter_account_end']) . "'";
		}

		$sql .= " GROUP BY LEFT(e.account, " . (int)$level . ")";
		$sql .= " ORDER BY code ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getSubaccountBalances($data) {
		$sql = "SELECT s.code, s.title, COALESCE(SUM(e.debit), 0) AS debit, COALESCE(SUM(e.credit), 0) AS credit
			FROM " . DB_PREFIX . "ctab61 s
			LEFT JOIN " . DB_PREFIX . "ctab8 e ON e.account = s.code";

		$date_conditions = array();

		if (!empty($data['filter_date_start'])) {
			$date_conditions[] = "e.line_date >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$date_conditions[] = "e.line_date <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($date_conditions) {
			$sql .= " AND " . implode(' AND ', $date_conditions);
		}

		$sql .= " WHERE 1 = 1";

		if (!empty($data['filter_account_start'])) {
			$sql .= " AND s.code >= '" . $this->db->escape($data['filter_account_start']) . "'";
		}

		if (!empty($data['filter_account_end'])) {
			$sql .= " AND s.code <= '" . $this->db->escape($data['filter_account_end']) . "'";
		}

		$sql .= " GROUP BY s.code, s.title";
		$sql .= " ORDER BY s.code ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAccountTitle($code) {
		$query = $this->db->query("SELECT title FROM " . DB_PREFIX . "ctab61 WHERE code = '" . $this->db->escape($code) . "'");

		return $query->num_rows ? $query->row['title'] : '';
	}
}
