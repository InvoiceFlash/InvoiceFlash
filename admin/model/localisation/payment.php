<?php
class ModelLocalisationPayment extends Model {
	public function addPayment($data) {
		$query = $this->db->query("SELECT * FROM payment_methods order by payment_id desc limit 1");

		$payment_id = isset($query->row['payment_id']) ? $query->row['payment_id']+1 : 1;
		// Por cada lenguaje insertamos una línea en la tabla
		foreach ($data['pay_name'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "payment_methods SET payment_id = " . (int)$payment_id . ", name = '" . $this->db->escape($value['name']) . "', language_id = " . (int)$language_id);
		}

	}
	
	public function editPayment($payment_id, $data) {

		// Actualizamos todos los idiomas
		foreach ($data['pay_name'] as $language_id => $value) {
			$this->db->query("UPDATE " . DB_PREFIX . "payment_methods SET name = '" . $this->db->escape($value['name']) . "' WHERE language_id = " . (int)$language_id . " AND payment_id = " . (int)$payment_id);
		}
	}
	
	public function deletePayment($payment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "payment_methods WHERE payment_id = " . (int)$payment_id);
	}
	
	public function getPayment($payment_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "payment_methods WHERE payment_id = " . $payment_id . " AND language_id = " . (int)$this->config->get('config_language_id'));

		return $query->row;
	}
	
	public function getPayments($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payment_methods WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
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
	
	public function getPaymentName($payment_id) {
		$payment_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "payment_methods WHERE payment_id = '" . (int)$payment_id . "'");
				
		foreach ($query->rows as $result) {
			$payment_data[$result['language_id']] = array(
				'name'        => $result['name']
			);
		}
		
		return $payment_data;
	}
		
	public function getTotalPayments($data) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payment_methods";

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