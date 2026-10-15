<?php
/**
 * Ventas > Resumen Facturacion: consultas de solo lectura sobre invoice / invoice_product / invoice_total.
 * Las facturas rectificativas (en negativo) entran con su signo, asi que restan en los resumenes.
 */
class ModelSaleInvoiceSummary extends Model {
	// Importe de cada factura: total con IVA o base imponible (sub_total, ya neto de descuentos).
	private function amountExpr($data) {
		if (!empty($data['amount']) && $data['amount'] == 'base') {
			return "COALESCE((SELECT SUM(it.value) FROM " . DB_PREFIX . "invoice_total it WHERE it.invoice_id = i.invoice_id AND it.code = 'sub_total'), i.total)";
		}

		return "i.total";
	}

	// Valor de una fila de invoice_total (sub_total, discount, tax, shipping) de la factura i.
	private function totalExpr($code) {
		return "COALESCE((SELECT SUM(it.value) FROM " . DB_PREFIX . "invoice_total it WHERE it.invoice_id = i.invoice_id AND it.code = '" . $code . "'), 0)";
	}

	private function buildWhere($data) {
		$where = array('1');

		if (!empty($data['filter_date_start'])) {
			$where[] = "i.date_added >= '" . $this->db->escape($data['filter_date_start']) . " 00:00:00'";
		}

		if (!empty($data['filter_date_end'])) {
			$where[] = "i.date_added <= '" . $this->db->escape($data['filter_date_end']) . " 23:59:59'";
		}

		if (!empty($data['filter_customer'])) {
			$c       = $this->db->escape($data['filter_customer']);
			$where[] = "(i.payment_company LIKE '%" . $c . "%' OR CONCAT(i.firstname, ' ', i.lastname) LIKE '%" . $c . "%')";
		}

		if (!empty($data['filter_status'])) {
			$where[] = "i.invoice_status_id = '" . (int)$data['filter_status'] . "'";
		}

		return implode(' AND ', $where);
	}

	public function getTotalInvoices($data) {
		return (int)$this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice i WHERE " . $this->buildWhere($data))->row['total'];
	}

	public function getInvoices($data) {
		$sql = "SELECT i.invoice_id, i.invoice_prefix, i.invoice_no, i.date_added, i.total, i.currency_code, i.invoice_status_id, i.customer_id,
				CASE WHEN i.payment_company <> '' THEN i.payment_company ELSE CONCAT(i.firstname, ' ', i.lastname) END AS customer,
				COALESCE(NULLIF(i.payment_tax_id, ''), c.nif, '') AS nif,
				c.cod_flash, c.contable_account,
				" . $this->totalExpr('sub_total') . " AS sub_total,
				" . $this->totalExpr('discount') . " AS discount,
				" . $this->totalExpr('tax') . " AS tax,
				(SELECT s.name FROM " . DB_PREFIX . "invoice_status s WHERE s.invoice_status_id = i.invoice_status_id AND s.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status
			FROM " . DB_PREFIX . "invoice i
			LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = i.customer_id
			WHERE " . $this->buildWhere($data) . "
			ORDER BY i.date_added DESC, i.invoice_id DESC";

		return $this->db->query($sql . " LIMIT " . (int)$data['start'] . ", " . (int)$data['limit'])->rows;
	}

	// Totales de TODAS las facturas filtradas (no solo de la pagina): van entre parentesis en las cabeceras.
	public function getInvoiceTotals($data) {
		$sql = "SELECT COALESCE(SUM(i.total), 0) AS total,
				COALESCE(SUM(" . $this->totalExpr('sub_total') . "), 0) AS sub_total,
				COALESCE(SUM(" . $this->totalExpr('discount') . "), 0) AS discount,
				COALESCE(SUM(" . $this->totalExpr('tax') . "), 0) AS tax
			FROM " . DB_PREFIX . "invoice i
			WHERE " . $this->buildWhere($data);

		return $this->db->query($sql)->row;
	}

	// Importe facturado por anyo y mes: array(anyo => array(mes => importe)).
	public function getMonthlyAmounts($data) {
		$sql = "SELECT YEAR(i.date_added) AS y, MONTH(i.date_added) AS m, SUM(" . $this->amountExpr($data) . ") AS v
			FROM " . DB_PREFIX . "invoice i
			WHERE " . $this->buildWhere($data) . "
			GROUP BY y, m";

		return $this->pivot($this->db->query($sql)->rows);
	}

	public function getTotalLines($data) {
		return (int)$this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice_product p INNER JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = p.invoice_id WHERE " . $this->buildWhere($data))->row['total'];
	}

	public function getLines($data) {
		// Albaran y pedido vinculados: delivery.invoice_no guarda el id de la factura que genero, y
		// order.invoice_no el id del albaran (ver "Cadena de documentos"). Puede haber varios: GROUP_CONCAT.
		$sql = "SELECT i.invoice_id, i.invoice_prefix, i.invoice_no, i.date_added, i.customer_id, c.cod_flash,
				CASE WHEN i.payment_company <> '' THEN i.payment_company ELSE CONCAT(i.firstname, ' ', i.lastname) END AS customer,
				p.name, p.model, p.quantity, p.price, p.discount, p.total,
				(SELECT GROUP_CONCAT(d.delivery_id ORDER BY d.delivery_id) FROM " . DB_PREFIX . "delivery d WHERE d.invoice_no = i.invoice_id) AS deliveries,
				(SELECT GROUP_CONCAT(o.order_id ORDER BY o.order_id) FROM `" . DB_PREFIX . "order` o WHERE o.invoice_no > 0 AND o.invoice_no IN (SELECT d2.delivery_id FROM " . DB_PREFIX . "delivery d2 WHERE d2.invoice_no = i.invoice_id)) AS orders
			FROM " . DB_PREFIX . "invoice_product p
			INNER JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = p.invoice_id
			LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = i.customer_id
			WHERE " . $this->buildWhere($data) . "
			ORDER BY i.date_added DESC, i.invoice_id DESC, p.invoice_product_id ASC";

		return $this->db->query($sql . " LIMIT " . (int)$data['start'] . ", " . (int)$data['limit'])->rows;
	}

	// Cantidad e importe de TODAS las lineas filtradas.
	public function getLinesTotals($data) {
		$sql = "SELECT COALESCE(SUM(p.quantity), 0) AS quantity, COALESCE(SUM(p.total), 0) AS total
			FROM " . DB_PREFIX . "invoice_product p
			INNER JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = p.invoice_id
			WHERE " . $this->buildWhere($data);

		return $this->db->query($sql)->row;
	}

	// Unidades vendidas por anyo y mes.
	public function getMonthlyUnits($data) {
		$sql = "SELECT YEAR(i.date_added) AS y, MONTH(i.date_added) AS m, SUM(p.quantity) AS v
			FROM " . DB_PREFIX . "invoice_product p
			INNER JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = p.invoice_id
			WHERE " . $this->buildWhere($data) . "
			GROUP BY y, m";

		return $this->pivot($this->db->query($sql)->rows);
	}

	private function pivot($rows) {
		$result = array();

		foreach ($rows as $row) {
			$result[(int)$row['y']][(int)$row['m']] = (float)$row['v'];
		}

		return $result;
	}
}
