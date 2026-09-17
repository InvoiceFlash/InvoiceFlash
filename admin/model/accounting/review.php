<?php
class ModelAccountingReview extends Model {
	public function getEntries($data = array()) {
		$sql = "SELECT e.ctab8_id, e.entry_id, e.line_date, e.account, e.concept, e.debit, e.credit, s.title AS description
			FROM " . DB_PREFIX . "ctab8 e
			LEFT JOIN " . DB_PREFIX . "ctab61 s ON s.code = e.account";

		$sql .= $this->buildFilterSql($data);

		$sql .= " ORDER BY e.entry_id ASC, e.ctab8_id ASC";

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

	public function getTotalEntries($data = array()) {
		$sql = "SELECT COUNT(*) AS total
			FROM " . DB_PREFIX . "ctab8 e
			LEFT JOIN " . DB_PREFIX . "ctab61 s ON s.code = e.account";

		$sql .= $this->buildFilterSql($data);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	private function buildFilterSql($data) {
		$mode = !empty($data['filter_mode']) ? $data['filter_mode'] : 'all';

		$sql = '';

		switch ($mode) {
			case 'number':
				if (isset($data['filter_number_start']) && $data['filter_number_start'] !== '') {
					$sql .= " AND e.entry_id >= '" . (int)$data['filter_number_start'] . "'";
				}

				if (isset($data['filter_number_end']) && $data['filter_number_end'] !== '') {
					$sql .= " AND e.entry_id <= '" . (int)$data['filter_number_end'] . "'";
				}
				break;

			case 'date':
				if (!empty($data['filter_date_start'])) {
					$sql .= " AND e.line_date >= '" . $this->db->escape($data['filter_date_start']) . "'";
				}

				if (!empty($data['filter_date_end'])) {
					$sql .= " AND e.line_date <= '" . $this->db->escape($data['filter_date_end']) . "'";
				}
				break;

			case 'amount':
				if (isset($data['filter_amount']) && $data['filter_amount'] !== '') {
					$amount = (float)str_replace(',', '.', $data['filter_amount']);
					$sql .= " AND (e.debit = '" . $amount . "' OR e.credit = '" . $amount . "')";
				}
				break;

			case 'concept':
				if (!empty($data['filter_concept'])) {
					$sql .= " AND e.concept LIKE '%" . $this->db->escape($data['filter_concept']) . "%'";
				}
				break;

			case 'account':
				if (!empty($data['filter_account'])) {
					$sql .= " AND e.account LIKE '" . $this->db->escape($data['filter_account']) . "%'";
				}
				break;

			case 'unbalanced':
				$sql .= " AND e.entry_id IN (SELECT entry_id FROM " . DB_PREFIX . "ctab8 GROUP BY entry_id HAVING SUM(debit) <> SUM(credit))";
				break;
		}

		if ($sql) {
			$sql = " WHERE 1 = 1" . $sql;
		}

		return $sql;
	}
}
