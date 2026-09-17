<?php
class ModelReportBalanceSheet extends Model {
	public function getBalances($date_end) {
		$sql = "SELECT s.code, s.title, COALESCE(SUM(e.debit), 0) AS debit, COALESCE(SUM(e.credit), 0) AS credit
			FROM " . DB_PREFIX . "ctab61 s
			LEFT JOIN " . DB_PREFIX . "ctab8 e ON e.account = s.code";

		if (!empty($date_end)) {
			$sql .= " AND e.line_date <= '" . $this->db->escape($date_end) . "'";
		}

		// Los grupos 6 y 7 (gastos/ingresos) no forman parte del balance: su resultado
		// acumulado se recoge en la cuenta 129 mediante el asiento de regularizacion.
		$sql .= " WHERE s.code < '6000000000' OR s.code >= '8000000000'";

		$sql .= " GROUP BY s.code, s.title
			HAVING debit <> 0 OR credit <> 0
			ORDER BY s.code ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
