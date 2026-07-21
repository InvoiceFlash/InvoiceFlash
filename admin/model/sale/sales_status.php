<?php

/**
 * El encadenado presupuesto -> pedido -> albarán -> factura no se guarda en una tabla de
 * relaciones: cuando se convierte un documento al siguiente, el campo `invoice_no` del
 * documento de origen se sobreescribe con el id del documento generado (ver
 * ControllerSaleQuote/Order/Delivery, acción "convertir"). Por eso cada tramo se une con
 * `siguiente.id = actual.invoice_no` en lugar de una FK dedicada.
 */
class ModelSaleSalesStatus extends Model {

	public function getTotalSalesStatus($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (" . $this->buildUnionSql($data) . ") AS t WHERE 1 = 1" . $this->buildFilterSql($data);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSalesStatus($data = array()) {
		$sql = "SELECT t.*, c.company AS customer FROM (" . $this->buildUnionSql($data) . ") AS t
			LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = t.customer_id
			WHERE 1 = 1" . $this->buildFilterSql($data) . "
			ORDER BY t.sort_date DESC, t.row_id DESC";

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

	private function buildFilterSql($data) {
		$sql = '';

		if (!empty($data['filter_customer'])) {
			$sql .= " AND c.company LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_reference'])) {
			$sql .= " AND t.comment LIKE '%" . $this->db->escape($data['filter_reference']) . "%'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND t.sort_date >= '" . $this->db->escape($data['filter_date_start']) . " 00:00:00'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND t.sort_date <= '" . $this->db->escape($data['filter_date_end']) . " 23:59:59'";
		}

		// El filtro Pendientes/Cobradas solo afecta a las cadenas que ya tienen factura;
		// las que aún no han llegado a factura se muestran siempre.
		if (empty($data['filter_pending']) && !empty($data['filter_paid'])) {
			$sql .= " AND (t.invoice_id IS NULL OR t.invoice_status_id = 2)";
		} elseif (!empty($data['filter_pending']) && empty($data['filter_paid'])) {
			$sql .= " AND (t.invoice_id IS NULL OR t.invoice_status_id != 2)";
		} elseif (empty($data['filter_pending']) && empty($data['filter_paid'])) {
			$sql .= " AND t.invoice_id IS NULL";
		}

		return $sql;
	}

	private function buildUnionSql($data) {
		$language_id = (int)$this->config->get('config_language_id');

		return "
			SELECT
				CONCAT('q', q.quote_id) AS row_id,
				q.quote_id AS quote_id, q.date_added AS quote_date, qs.name AS quote_status,
				o.order_id AS order_id, o.date_added AS order_date, os.name AS order_status,
				d.delivery_id AS delivery_id, d.date_added AS delivery_date, ds.name AS delivery_status,
				i.invoice_id AS invoice_id, i.date_added AS invoice_date, i.invoice_status_id AS invoice_status_id, ist.name AS invoice_status,
				COALESCE(q.customer_id, o.customer_id, d.customer_id, i.customer_id) AS customer_id,
				COALESCE(q.comment, o.comment, d.comment, i.comment) AS comment,
				COALESCE(i.date_added, d.date_added, o.date_added, q.date_added) AS sort_date
			FROM " . DB_PREFIX . "quote q
			LEFT JOIN `" . DB_PREFIX . "order` o ON o.order_id = q.invoice_no
			LEFT JOIN " . DB_PREFIX . "delivery d ON d.delivery_id = o.invoice_no
			LEFT JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = d.invoice_no
			LEFT JOIN " . DB_PREFIX . "quote_status qs ON qs.quote_status_id = q.quote_status_id AND qs.language_id = " . $language_id . "
			LEFT JOIN " . DB_PREFIX . "order_status os ON os.order_status_id = o.order_status_id AND os.language_id = " . $language_id . "
			LEFT JOIN " . DB_PREFIX . "delivery_status ds ON ds.delivery_status_id = d.delivery_status_id AND ds.language_id = " . $language_id . "
			LEFT JOIN " . DB_PREFIX . "invoice_status ist ON ist.invoice_status_id = i.invoice_status_id AND ist.language_id = " . $language_id . "

			UNION ALL

			SELECT
				CONCAT('o', o.order_id) AS row_id,
				NULL, NULL, NULL,
				o.order_id AS order_id, o.date_added AS order_date, os.name AS order_status,
				d.delivery_id AS delivery_id, d.date_added AS delivery_date, ds.name AS delivery_status,
				i.invoice_id AS invoice_id, i.date_added AS invoice_date, i.invoice_status_id AS invoice_status_id, ist.name AS invoice_status,
				COALESCE(o.customer_id, d.customer_id, i.customer_id) AS customer_id,
				COALESCE(o.comment, d.comment, i.comment) AS comment,
				COALESCE(i.date_added, d.date_added, o.date_added) AS sort_date
			FROM `" . DB_PREFIX . "order` o
			LEFT JOIN " . DB_PREFIX . "delivery d ON d.delivery_id = o.invoice_no
			LEFT JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = d.invoice_no
			LEFT JOIN " . DB_PREFIX . "order_status os ON os.order_status_id = o.order_status_id AND os.language_id = " . $language_id . "
			LEFT JOIN " . DB_PREFIX . "delivery_status ds ON ds.delivery_status_id = d.delivery_status_id AND ds.language_id = " . $language_id . "
			LEFT JOIN " . DB_PREFIX . "invoice_status ist ON ist.invoice_status_id = i.invoice_status_id AND ist.language_id = " . $language_id . "
			WHERE o.order_id NOT IN (SELECT invoice_no FROM " . DB_PREFIX . "quote WHERE invoice_no > 0)

			UNION ALL

			SELECT
				CONCAT('d', d.delivery_id) AS row_id,
				NULL, NULL, NULL,
				NULL, NULL, NULL,
				d.delivery_id AS delivery_id, d.date_added AS delivery_date, ds.name AS delivery_status,
				i.invoice_id AS invoice_id, i.date_added AS invoice_date, i.invoice_status_id AS invoice_status_id, ist.name AS invoice_status,
				COALESCE(d.customer_id, i.customer_id) AS customer_id,
				COALESCE(d.comment, i.comment) AS comment,
				COALESCE(i.date_added, d.date_added) AS sort_date
			FROM " . DB_PREFIX . "delivery d
			LEFT JOIN " . DB_PREFIX . "invoice i ON i.invoice_id = d.invoice_no
			LEFT JOIN " . DB_PREFIX . "delivery_status ds ON ds.delivery_status_id = d.delivery_status_id AND ds.language_id = " . $language_id . "
			LEFT JOIN " . DB_PREFIX . "invoice_status ist ON ist.invoice_status_id = i.invoice_status_id AND ist.language_id = " . $language_id . "
			WHERE d.delivery_id NOT IN (SELECT invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_no > 0)

			UNION ALL

			SELECT
				CONCAT('i', i.invoice_id) AS row_id,
				NULL, NULL, NULL,
				NULL, NULL, NULL,
				NULL, NULL, NULL,
				i.invoice_id AS invoice_id, i.date_added AS invoice_date, i.invoice_status_id AS invoice_status_id, ist.name AS invoice_status,
				i.customer_id AS customer_id,
				i.comment AS comment,
				i.date_added AS sort_date
			FROM " . DB_PREFIX . "invoice i
			LEFT JOIN " . DB_PREFIX . "invoice_status ist ON ist.invoice_status_id = i.invoice_status_id AND ist.language_id = " . $language_id . "
			WHERE i.invoice_id NOT IN (SELECT invoice_no FROM " . DB_PREFIX . "delivery WHERE invoice_no > 0)
		";
	}
}
