<?php
class ModelReportMod111 extends Model {
	public function getRetentionSummary($account, $date_start, $date_end) {
		$query = $this->db->query("SELECT COUNT(DISTINCT entry_id) AS entries, COALESCE(SUM(credit), 0) AS credit, COALESCE(SUM(debit), 0) AS debit
			FROM " . DB_PREFIX . "ctab8
			WHERE account = '" . $this->db->escape($account) . "'
			AND line_date >= '" . $this->db->escape($date_start) . "'
			AND line_date <= '" . $this->db->escape($date_end) . "'");

		return $query->row;
	}
}
