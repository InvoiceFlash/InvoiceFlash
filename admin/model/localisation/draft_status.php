<?php
class ModelLocalisationDraftStatus extends Model {
	public function addDraftStatus($data) {
		foreach ($data['draft_status'] as $language_id => $value) {
			if (isset($draft_status_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "draft_status SET draft_status_id = '" . (int)$draft_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "draft_status SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");

				$draft_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('draft_status');
	}

	public function editDraftStatus($draft_status_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_status WHERE draft_status_id = '" . (int)$draft_status_id . "'");

		foreach ($data['draft_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "draft_status SET draft_status_id = '" . (int)$draft_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('draft_status');
	}

	public function deleteDraftStatus($draft_status_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_status WHERE draft_status_id = '" . (int)$draft_status_id . "'");

		$this->cache->delete('draft_status');
	}

	public function getDraftStatus($draft_status_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "draft_status WHERE draft_status_id = '" . (int)$draft_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getDraftStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "draft_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

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
			$draft_status_data = $this->cache->get('draft_status.' . (int)$this->config->get('config_language_id'));

			if (!$draft_status_data) {
				$query = $this->db->query("SELECT draft_status_id, name FROM " . DB_PREFIX . "draft_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

				$draft_status_data = $query->rows;

				$this->cache->set('draft_status.' . (int)$this->config->get('config_language_id'), $draft_status_data);
			}

			return $draft_status_data;
		}
	}

	public function getDraftStatusDescriptions($draft_status_id) {
		$draft_status_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "draft_status WHERE draft_status_id = '" . (int)$draft_status_id . "'");

		foreach ($query->rows as $result) {
			$draft_status_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $draft_status_data;
	}

	public function getTotalDraftStatuses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "draft_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
}
?>
