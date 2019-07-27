<?php
class ModelSettingCron extends Model {
	public function addCron($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cron` SET `code` = '" . $this->db->escape($data['cron_code']) . "', `action` = '" . $this->db->escape($data['cron_action']) . "', `status` = '" . (int)$data['cron_status'] . "', `date_added` = now(), `date_modified` = now()");

		return $this->db->getLastId();
	}

	public function editCron($data, $cron_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `code` = '" . $this->db->escape($data['cron_code']) . "', `action` = '" . $this->db->escape($data['cron_action']) . "', `status` = '" . (int)$data['cron_status'] . "', `date_modified` = now() WHERE cron_id = '" . (int)$cron_id . "'");
	}

	public function deleteCron($cron_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");
	}
	
	public function deleteCronByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function editStatus($cron_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `status` = '" . (int)$status . "' WHERE cron_id = '" . (int)$cron_id . "'");
	}

	public function getCron($cron_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");
		
		return $query->row;
	}

	public function getCronByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}
		
	public function getCrons($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "cron`";

		$sort_data = array(
			'code',
			'action',
			'status',
			'date_added',
			'date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `date_added`";
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

	public function getTotalCrons() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "cron`");

		return $query->row['total'];
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cron` (
			`cron_id` int(11) NOT NULL AUTO_INCREMENT,
			`code` varchar(64) NOT NULL,
			`action` text NOT NULL,
			`status` tinyint(1) NOT NULL,
			`date_added` datetime NOT NULL,
			`date_modified` datetime NOT NULL,
			PRIMARY KEY (`cron_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
	}
}