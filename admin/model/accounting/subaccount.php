<?php
class ModelAccountingSubaccount extends Model {
	public function addSubaccount($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ctab61 SET " . $this->buildFieldsSql($data));

		return $this->db->getLastId();
	}

	public function editSubaccount($ctab61_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ctab61 SET " . $this->buildFieldsSql($data) . " WHERE ctab61_id = '" . (int)$ctab61_id . "'");
	}

	private function buildFieldsSql($data) {
		$fields = array(
			'code'                => isset($data['code']) ? $data['code'] : '',
			'title'               => isset($data['title']) ? $data['title'] : '',
			'vat_regime'          => isset($data['vat_regime']) ? $data['vat_regime'] : '',
			'cif'                 => isset($data['cif']) ? $data['cif'] : '',
			'phone'               => isset($data['phone']) ? $data['phone'] : '',
			'fax'                 => isset($data['fax']) ? $data['fax'] : '',
			'email'               => isset($data['email']) ? $data['email'] : '',
			'street_type'         => isset($data['street_type']) ? $data['street_type'] : '',
			'street'              => isset($data['street']) ? $data['street'] : '',
			'number'              => isset($data['number']) ? $data['number'] : '',
			'postcode'            => isset($data['postcode']) ? $data['postcode'] : '',
			'city'                => isset($data['city']) ? $data['city'] : '',
			'province'            => isset($data['province']) ? $data['province'] : '',
			'country'             => isset($data['country']) ? $data['country'] : '',
			'country_fiscal_code' => isset($data['country_fiscal_code']) ? $data['country_fiscal_code'] : '',
			'eu_vat_code'         => isset($data['eu_vat_code']) ? $data['eu_vat_code'] : ''
		);

		$sql = array();

		foreach ($fields as $column => $value) {
			$sql[] = "`" . $column . "` = '" . $this->db->escape($value) . "'";
		}

		return implode(', ', $sql);
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
		$sql = "SELECT s.ctab61_id, s.code, s.title, COALESCE(SUM(e.debit), 0) AS debit, COALESCE(SUM(e.credit), 0) AS credit, COALESCE(SUM(e.debit), 0) - COALESCE(SUM(e.credit), 0) AS balance
			FROM " . DB_PREFIX . "ctab61 s
			LEFT JOIN " . DB_PREFIX . "ctab8 e ON e.account = s.code";

		if (!empty($data['filter_code'])) {
			$sql .= " WHERE s.code LIKE '" . $this->db->escape($data['filter_code']) . "%'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " s.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
		}

		$sql .= " GROUP BY s.ctab61_id, s.code, s.title";

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
