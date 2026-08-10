<?php
class ModelToolBorme extends Model {
	public function getBormes($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "borme` WHERE 1=1";

		if (!empty($data['filter_status'])) {
			$sql .= " AND status = '" . $this->db->escape($data['filter_status']) . "'";
		}

		$sql .= " ORDER BY borme_date DESC, borme_id DESC";

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

	public function getTotalBormes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "borme` WHERE 1=1";

		if (!empty($data['filter_status'])) {
			$sql .= " AND status = '" . $this->db->escape($data['filter_status']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function updateContact($borme_id, $email, $website) {
		$this->db->query("UPDATE `" . DB_PREFIX . "borme` SET `email` = '" . $this->db->escape($email) . "', `website` = '" . $this->db->escape($website) . "', `status` = 'found' WHERE `borme_id` = '" . (int)$borme_id . "'");
	}
}
