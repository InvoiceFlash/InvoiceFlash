<?php
class ModelLocalisationDeliveryStatus extends Model {
	public function getDeliveryStatus($delivery_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_status WHERE delivery_status_id = '" . (int)$delivery_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getDeliveryStatuses() {
		$query = $this->db->query("SELECT delivery_status_id, name, color FROM " . DB_PREFIX . "delivery_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY delivery_status_id");

		return $query->rows;
	}
}
?>
