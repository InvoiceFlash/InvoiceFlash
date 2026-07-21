<?php
class ModelAccountingChart extends Model {
	public function addAccount($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ctab6 SET code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "', `level` = '" . (int)$data['level'] . "'");

		return $this->db->getLastId();
	}

	public function editAccount($ctab6_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ctab6 SET code = '" . $this->db->escape($data['code']) . "', name = '" . $this->db->escape($data['name']) . "', `level` = '" . (int)$data['level'] . "' WHERE ctab6_id = '" . (int)$ctab6_id . "'");
	}

	public function deleteAccount($ctab6_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ctab6 WHERE ctab6_id = '" . (int)$ctab6_id . "'");
	}

	public function getAccount($ctab6_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ctab6 WHERE ctab6_id = '" . (int)$ctab6_id . "'");

		return $query->row;
	}

	public function getAccountByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ctab6 WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getAccounts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ctab6";

		if (!empty($data['filter_code'])) {
			$sql .= " WHERE code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'code',
			'name',
			'level'
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

	public function getTotalAccounts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ctab6";

		if (!empty($data['filter_code'])) {
			$sql .= " WHERE code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>
