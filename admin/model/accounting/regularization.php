<?php
class ModelAccountingRegularization extends Model {
	public function getAccountsToClose($date) {
		$year  = date('Y', strtotime($date));
		$start = $year . '-01-01';

		$query = $this->db->query("SELECT account, SUM(debit) AS total_debit, SUM(credit) AS total_credit
			FROM " . DB_PREFIX . "ctab8
			WHERE (account LIKE '6%' OR account LIKE '7%')
			AND line_date >= '" . $this->db->escape($start) . "'
			AND line_date <= '" . $this->db->escape($date) . "'
			GROUP BY account
			HAVING ROUND(SUM(debit) - SUM(credit), 2) <> 0");

		return $query->rows;
	}
}
