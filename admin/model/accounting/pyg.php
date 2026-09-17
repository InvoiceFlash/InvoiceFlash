<?php
class ModelAccountingPyg extends Model {
	public function addAccount($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ctab9 SET
			code = '" . $this->db->escape($data['code']) . "',
			name = '" . $this->db->escape($data['name']) . "',
			order_code = '" . $this->db->escape($data['order_code']) . "',
			accounts = '" . $this->db->escape($data['accounts']) . "',
			`level` = '" . (int)$data['level'] . "',
			list_after = '" . $this->db->escape($data['list_after']) . "'");

		return $this->db->getLastId();
	}

	public function editAccount($ctab9_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ctab9 SET
			code = '" . $this->db->escape($data['code']) . "',
			name = '" . $this->db->escape($data['name']) . "',
			order_code = '" . $this->db->escape($data['order_code']) . "',
			accounts = '" . $this->db->escape($data['accounts']) . "',
			`level` = '" . (int)$data['level'] . "',
			list_after = '" . $this->db->escape($data['list_after']) . "'
			WHERE ctab9_id = '" . (int)$ctab9_id . "'");
	}

	public function deleteAccount($ctab9_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "ctab9 WHERE ctab9_id = '" . (int)$ctab9_id . "'");
	}

	public function getAccount($ctab9_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ctab9 WHERE ctab9_id = '" . (int)$ctab9_id . "'");

		return $query->row;
	}

	public function getAccountByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ctab9 WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getAccounts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "ctab9";

		if (!empty($data['filter_code'])) {
			$sql .= " WHERE code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY order_code ASC, code ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
