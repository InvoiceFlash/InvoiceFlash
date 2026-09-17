<?php
class ModelAccountingEntry extends Model {
	public function getNextEntryId() {
		$query = $this->db->query("SELECT COALESCE(MAX(entry_id), 0) + 1 AS next_id FROM " . DB_PREFIX . "ctab8");

		return (int)$query->row['next_id'];
	}

	public function addEntry($entry_id, $line_date, $lines, $user) {
		foreach ($lines as $line) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "ctab8 SET
				entry_id = '" . (int)$entry_id . "',
				line_date = '" . $this->db->escape($line_date) . "',
				account = '" . $this->db->escape($line['account']) . "',
				concept = '" . $this->db->escape($line['concept']) . "',
				debit = '" . (float)$line['debit'] . "',
				credit = '" . (float)$line['credit'] . "',
				user_id = '" . (int)$user['user_id'] . "',
				username = '" . $this->db->escape($user['username']) . "',
				date_added = NOW(),
				date_modified = NOW()");
		}
	}
}
