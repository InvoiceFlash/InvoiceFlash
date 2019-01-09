<?php 
class ModelLocalisationInvoiceStatus extends Model {
	public function addInvoiceStatus($data) {
		foreach ($data['invoice_status'] as $language_id => $value) {
			if (isset($invoice_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_status SET invoice_status_id = '" . (int)$invoice_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', color = '" . $this->db->escape($data['color']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				$invoice_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('invoice_status');
	}

	public function editInvoiceStatus($invoice_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_status WHERE invoice_status_id = '" . (int)$invoice_status_id . "'");

		foreach ($data['invoice_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_status SET invoice_status_id = '" . (int)$invoice_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', color = '" . $this->db->escape($data['color']) . "'");
		}

		$this->cache->delete('invoice_status');
	}

	public function deleteInvoiceStatus($invoice_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_status WHERE invoice_status_id = '" . (int)$invoice_status_id . "'");

		$this->cache->delete('invoice_status');
	}

	public function getInvoiceStatus($invoice_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_status WHERE invoice_status_id = '" . (int)$invoice_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getInvoiceStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "invoice_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY name";	

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
		} else {
			$invoice_status_data = $this->cache->get('invoice_status.' . (int)$this->config->get('config_language_id'));

			if (!$invoice_status_data) {
				$query = $this->db->query("SELECT invoice_status_id, name FROM " . DB_PREFIX . "invoice_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$invoice_status_data = $query->rows;

				$this->cache->set('invoice_status.' . (int)$this->config->get('config_language_id'), $invoice_status_data);
			}	

			return $invoice_status_data;				
		}
	}

	public function getInvoiceStatusDescriptions($invoice_status_id) {
		$invoice_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_status WHERE invoice_status_id = '" . (int)$invoice_status_id . "'");

		foreach ($query->rows as $result) {
			$invoice_status_data[$result['language_id']] = array('name' => $result['name']);
			$invoice_status_data['color'] = $result['color'];
		}

		$log=new Log('invoice_status.log');$log->write($invoice_status_data);
		return $invoice_status_data;
	}

	public function getTotalInvoiceStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}	

}
?>