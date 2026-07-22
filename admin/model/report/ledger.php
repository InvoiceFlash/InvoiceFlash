<?php
class ModelReportLedger extends Model {
	public function getAccounts($data = array()) {
		$sql = "SELECT s.code, s.title
			FROM " . DB_PREFIX . "ctab61 s
			WHERE 1 = 1";

		if (!empty($data['filter_account_start'])) {
			$sql .= " AND s.code >= '" . $this->db->escape($data['filter_account_start']) . "'";
		}

		if (!empty($data['filter_account_end'])) {
			$sql .= " AND s.code <= '" . $this->db->escape($data['filter_account_end']) . "'";
		}

		$sql .= " AND EXISTS (SELECT 1 FROM " . DB_PREFIX . "ctab8 e WHERE e.account = s.code" . $this->buildDateSql($data, 'e') . ")";

		$sql .= " ORDER BY s.code ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOpeningBalance($account, $data) {
		if (empty($data['filter_date_start'])) {
			return 0;
		}

		$query = $this->db->query("SELECT SUM(debit) AS debit, SUM(credit) AS credit
			FROM " . DB_PREFIX . "ctab8
			WHERE account = '" . $this->db->escape($account) . "'
			AND line_date < '" . $this->db->escape($data['filter_date_start']) . "'");

		return (float)$query->row['debit'] - (float)$query->row['credit'];
	}

	public function getEntries($account, $data = array()) {
		$sql = "SELECT ctab8_id, entry_id, line_date, concept, debit, credit
			FROM " . DB_PREFIX . "ctab8
			WHERE account = '" . $this->db->escape($account) . "'";

		$sql .= $this->buildDateSql($data);

		$sql .= " ORDER BY line_date ASC, entry_id ASC, ctab8_id ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	private function buildDateSql($data, $alias = '') {
		$prefix = $alias ? $alias . '.' : '';
		$sql = '';

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND " . $prefix . "line_date >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND " . $prefix . "line_date <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		return $sql;
	}
}
