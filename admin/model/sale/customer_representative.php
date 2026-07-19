<?php

class ModelSaleCustomerRepresentative extends Model {
	public function addCustomerRepresentative($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_representative` SET `name` = '" . $this->db->escape($data['name']) . "'");
	}

	public function editCustomerRepresentative($customer_representative_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_representative` SET `name` = '" . $this->db->escape($data['name']) . "' WHERE `customer_representative_id` = '" . (int)$customer_representative_id . "'");
	}

	public function deleteCustomerRepresentative($customer_representative_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_representative` WHERE `customer_representative_id` = '" . (int)$customer_representative_id . "'");
	}

	public function getTotalCustomerRepresentatives($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_representative`";

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

		return $query->row['total'];
	}

	public function getCustomerRepresentatives($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_representative`";

		$sort_data = array('customer_representative_id', 'name');

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getCustomerRepresentative($customer_representative_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_representative` WHERE `customer_representative_id` = '" . (int)$customer_representative_id . "'");

		return $query->row;
	}
}
