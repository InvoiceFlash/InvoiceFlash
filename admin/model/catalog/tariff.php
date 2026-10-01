<?php
class ModelCatalogTariff extends Model {
	private function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tariff` (
			`tariff_id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(64) NOT NULL,
			`percent` decimal(7,2) NOT NULL DEFAULT '0.00',
			`date_end` date DEFAULT NULL,
			`is_default` tinyint(1) NOT NULL DEFAULT '0',
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`tariff_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	private function dateEnd($data) {
		return (isset($data['date_end']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_end'])) ? "'" . $this->db->escape($data['date_end']) . "'" : 'NULL';
	}

	public function addTariff($data) {
		$this->install();

		$this->db->query("INSERT INTO `" . DB_PREFIX . "tariff` SET name = '" . $this->db->escape($data['name']) . "', percent = '" . (float)$data['percent'] . "', date_end = " . $this->dateEnd($data) . ", is_default = '" . (empty($data['is_default']) ? 0 : 1) . "', date_added = NOW()");

		$tariff_id = $this->db->getLastId();

		if (!empty($data['is_default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "tariff` SET is_default = '0' WHERE tariff_id <> '" . (int)$tariff_id . "'");
		}

		return $tariff_id;
	}

	public function editTariff($tariff_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "tariff` SET name = '" . $this->db->escape($data['name']) . "', percent = '" . (float)$data['percent'] . "', date_end = " . $this->dateEnd($data) . ", is_default = '" . (empty($data['is_default']) ? 0 : 1) . "' WHERE tariff_id = '" . (int)$tariff_id . "'");

		if (!empty($data['is_default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "tariff` SET is_default = '0' WHERE tariff_id <> '" . (int)$tariff_id . "'");
		}
	}

	public function deleteTariff($tariff_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tariff` WHERE tariff_id = '" . (int)$tariff_id . "'");
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET tariff_id = '0' WHERE tariff_id = '" . (int)$tariff_id . "'");
	}

	public function getTariff($tariff_id) {
		$this->install();

		return $this->db->query("SELECT * FROM `" . DB_PREFIX . "tariff` WHERE tariff_id = '" . (int)$tariff_id . "'")->row;
	}

	public function getTariffs($data = array()) {
		$this->install();

		$sort_data = array('name', 'percent', 'date_end');
		$sort = (isset($data['sort']) && in_array($data['sort'], $sort_data)) ? $data['sort'] : 'name';
		$sql = "SELECT * FROM `" . DB_PREFIX . "tariff` ORDER BY " . $sort . ((isset($data['order']) && $data['order'] == 'DESC') ? ' DESC' : ' ASC');

		if (isset($data['start']) || isset($data['limit'])) {
			$start = max(0, isset($data['start']) ? (int)$data['start'] : 0);
			$limit = (isset($data['limit']) && (int)$data['limit'] > 0) ? (int)$data['limit'] : 20;

			$sql .= " LIMIT " . $start . "," . $limit;
		}

		return $this->db->query($sql)->rows;
	}

	public function getTotalTariffs() {
		$this->install();

		return (int)$this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "tariff`")->row['total'];
	}

	// Porcentaje de descuento de la tarifa del cliente; sin tarifa propia (o caducada) se usa la tarifa por defecto.
	public function getCustomerPercent($customer_id) {
		$this->install();

		$valid = "(date_end IS NULL OR date_end >= CURDATE())";
		$customer_id = (int)$customer_id;

		if ($customer_id) {
			$query = $this->db->query("SELECT t.percent FROM `" . DB_PREFIX . "tariff` t INNER JOIN `" . DB_PREFIX . "customer` c ON c.tariff_id = t.tariff_id WHERE c.customer_id = '" . $customer_id . "' AND (t.date_end IS NULL OR t.date_end >= CURDATE())");

			if ($query->num_rows) {
				return (float)$query->row['percent'];
			}
		}

		$query = $this->db->query("SELECT percent FROM `" . DB_PREFIX . "tariff` WHERE is_default = '1' AND " . $valid . " LIMIT 1");

		return $query->num_rows ? (float)$query->row['percent'] : 0;
	}
}
?>
