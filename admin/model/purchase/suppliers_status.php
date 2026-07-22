<?php

/**
 * A diferencia de presupuesto->pedido->albarán->factura en Ventas, en Compras el Pedido de
 * Compra y la Factura de Compra no están encadenados por ningún campo (no existe un
 * `invoice_no` equivalente): son documentos independientes ligados solo al proveedor. Por eso
 * cada uno aparece en su propia fila, sin cruzar Pedido y Factura entre sí.
 */
class ModelPurchaseSuppliersStatus extends Model {

	public function getTotalSuppliersStatus($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (" . $this->buildUnionSql($data) . ") AS t WHERE 1 = 1" . $this->buildFilterSql($data);

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSuppliersStatus($data = array()) {
		$sql = "SELECT t.*, s.company AS supplier FROM (" . $this->buildUnionSql($data) . ") AS t
			LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = t.supplier_id
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

		if (!empty($data['filter_supplier'])) {
			$sql .= " AND s.company LIKE '" . $this->db->escape($data['filter_supplier']) . "%'";
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

		// El filtro Pendientes/Pagadas solo afecta a las facturas; los pedidos de compra
		// (que no tienen concepto de pago) se muestran siempre.
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
				CONCAT('po', po.purchase_order_id) AS row_id,
				po.purchase_order_id AS purchase_order_id, po.date_added AS po_date, pos.name AS po_status,
				NULL AS invoice_id, NULL AS invoice_date, NULL AS invoice_status_id, NULL AS invoice_status,
				po.supplier_id AS supplier_id,
				po.po_number AS comment,
				po.date_added AS sort_date
			FROM " . DB_PREFIX . "purchase_order po
			LEFT JOIN " . DB_PREFIX . "purchase_order_status pos ON pos.purchase_order_status_id = po.purchase_order_status_id AND pos.language_id = " . $language_id . "

			UNION ALL

			SELECT
				CONCAT('pi', i.invoice_id) AS row_id,
				NULL, NULL, NULL,
				i.invoice_id AS invoice_id, i.date_added AS invoice_date, i.invoice_status_id AS invoice_status_id, ist.name AS invoice_status,
				i.supplier_id AS supplier_id,
				i.comment AS comment,
				i.date_added AS sort_date
			FROM " . DB_PREFIX . "purchase_invoice i
			LEFT JOIN " . DB_PREFIX . "invoice_status ist ON ist.invoice_status_id = i.invoice_status_id AND ist.language_id = " . $language_id . "
		";
	}
}
