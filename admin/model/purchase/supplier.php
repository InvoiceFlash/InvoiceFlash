<?php

class ModelPurchaseSupplier extends Model {
	public function addSupplier($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "supplier SET
			firstname = '" . $this->db->escape($data['firstname']) . "',
			lastname = '" . $this->db->escape($data['lastname']) . "',
			company = '" . $this->db->escape($data['company']) . "',
			company_id = '" . $this->db->escape($data['company_id']) . "',
			tax_id = '" . $this->db->escape($data['tax_id']) . "',
			email = '" . $this->db->escape($data['email']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			fax = '" . $this->db->escape($data['fax']) . "',
			web = '" . $this->db->escape($data['web']) . "',
			address_1 = '" . $this->db->escape($data['address_1']) . "',
			address_2 = '" . $this->db->escape($data['address_2']) . "',
			city = '" . $this->db->escape($data['city']) . "',
			postcode = '" . $this->db->escape($data['postcode']) . "',
			country_id = '" . (int)$data['country_id'] . "',
			zone_id = '" . (int)$data['zone_id'] . "',
			comment = '" . $this->db->escape($data['comment']) . "',
			status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "',
			date_added = NOW(),
			date_modified = NOW()");

		return $this->db->getLastId();
	}

	public function editSupplier($supplier_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "supplier SET
			firstname = '" . $this->db->escape($data['firstname']) . "',
			lastname = '" . $this->db->escape($data['lastname']) . "',
			company = '" . $this->db->escape($data['company']) . "',
			company_id = '" . $this->db->escape($data['company_id']) . "',
			tax_id = '" . $this->db->escape($data['tax_id']) . "',
			email = '" . $this->db->escape($data['email']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			fax = '" . $this->db->escape($data['fax']) . "',
			web = '" . $this->db->escape($data['web']) . "',
			address_1 = '" . $this->db->escape($data['address_1']) . "',
			address_2 = '" . $this->db->escape($data['address_2']) . "',
			city = '" . $this->db->escape($data['city']) . "',
			postcode = '" . $this->db->escape($data['postcode']) . "',
			country_id = '" . (int)$data['country_id'] . "',
			zone_id = '" . (int)$data['zone_id'] . "',
			comment = '" . $this->db->escape($data['comment']) . "',
			status = '" . (isset($data['status']) ? (int)$data['status'] : 0) . "',
			date_modified = NOW()
			WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function deleteSupplier($supplier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$supplier_id . "'");
	}

	public function getSupplier($supplier_id) {
		$query = $this->db->query("SELECT s.*, c.name AS country, z.name AS zone, z.code AS zone_code FROM " . DB_PREFIX . "supplier s LEFT JOIN " . DB_PREFIX . "country c ON s.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON s.zone_id = z.zone_id WHERE s.supplier_id = '" . (int)$supplier_id . "'");

		return $query->row;
	}

	public function getSuppliers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "supplier";

		$where = array();

		if (!empty($data['filter_company'])) {
			$where[] = "company LIKE '" . $this->db->escape($data['filter_company']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$where[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$where[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($where) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}

		$sort_data = array(
			'company',
			'name',
			'email',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY company";
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

	public function getTotalSuppliers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supplier";

		$where = array();

		if (!empty($data['filter_company'])) {
			$where[] = "company LIKE '" . $this->db->escape($data['filter_company']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$where[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$where[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if ($where) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function autocomplete($filter_name) {
		$query = $this->db->query("SELECT supplier_id, company, firstname, lastname, email, telephone, fax FROM " . DB_PREFIX . "supplier WHERE company LIKE '" . $this->db->escape($filter_name) . "%' AND status = '1' ORDER BY company ASC LIMIT 0,5");

		return $query->rows;
	}
}
?>
