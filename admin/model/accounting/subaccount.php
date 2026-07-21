<?php
class ModelAccountingSubaccount extends Model {
	public function addSubaccount($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ctab61 SET code = '" . $this->db->escape($data['code']) . "', title = '" . $this->db->escape($data['title']) . "', vat_regime = '" . $this->db->escape(isset($data['vat_regime']) ? $data['vat_regime'] : '') . "'");

		return $this->db->getLastId();
	}

	public function editSubaccount($ctab61_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ctab61 SET code = '" . $this->db->escape($data['code']) . "', title = '" . $this->db->escape($data['title']) . "', vat_regime = '" . $this->db->escape(isset($data['vat_regime']) ? $data['vat_regime'] : '') . "' WHERE ctab61_id = '" . (int)$ctab61_id . "'");
	}

	public function deleteSubaccount($ctab61_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ctab61 WHERE ctab61_id = '" . (int)$ctab61_id . "'");
	}

	public function getSubaccount($ctab61_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ctab61 WHERE ctab61_id = '" . (int)$ctab61_id . "'");

		return $query->row;
	}

	public function getSubaccountByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ctab61 WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getSubaccounts($data = array()) {
		$sql = "SELECT *, (debit - credit) AS balance FROM " . DB_PREFIX . "ctab61";

		if (!empty($data['filter_code'])) {
			$sql .= " WHERE code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
		}

		$sort_data = array(
			'code',
			'title',
			'debit',
			'credit',
			'balance'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY code";
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

	public function getTotalSubaccounts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ctab61";

		if (!empty($data['filter_code'])) {
			$sql .= " WHERE code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>
