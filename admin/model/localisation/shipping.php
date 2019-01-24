<?php
class ModelLocalisationShipping extends Model {
	public function addShipping($data) {
		$query = $this->db->query("SELECT * FROM shipping_methods order by shipping_id desc limit 1");

		$shipping_id = isset($query->row['shipping_id']) ? $query->row['shipping_id']+1 : 1;
		// Por cada lenguaje insertamos una línea en la tabla
		foreach ($data['ship_name'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "shipping_methods SET shipping_id = " . (int)$shipping_id . ", name = '" . $this->db->escape($value['name']) . "', language_id = " . (int)$language_id);
		}

	}
	
	public function editShipping($shipping_id, $data) {

		// Actualizamos todos los idiomas
		foreach ($data['ship_name'] as $language_id => $value) {
			$this->db->query("UPDATE " . DB_PREFIX . "shipping_methods SET name = '" . $this->db->escape($value['name']) . "' WHERE language_id = " . (int)$language_id . " AND shipping_id = " . (int)$shipping_id);
		}
	}
	
	public function deleteShipping($shipping_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "shipping_methods WHERE shipping_id = " . (int)$shipping_id);
	}
	
	public function getShipping($shipping_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "shipping_methods WHERE shipping_id = " . $shipping_id . " AND language_id = " . (int)$this->config->get('config_language_id'));

		return $query->row;
	}
	
	public function getShippings($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "shipping_methods WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'name'
		);	

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
	
	public function getShippingName($shipping_id) {
		$shipping_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "shipping_methods WHERE shipping_id = '" . (int)$shipping_id . "'");
				
		foreach ($query->rows as $result) {
			$shipping_data[$result['language_id']] = array(
				'name'        => $result['name']
			);
		}
		
		return $shipping_data;
	}
		
	public function getTotalShippings($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "shipping_methods";

		$sort_data = array(
			'name'
		);	

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

		return $query->row['total'];
	}
}
?>