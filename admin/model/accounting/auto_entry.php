<?php

// Genera/regenera automaticamente el asiento contable (tabla ctab8) de una
// factura de venta o de compra cuando Ajustes > Contabilidad > "Activar
// contabilidad" esta en Si. Un asiento por factura, enlazado via la columna
// invoice.entry_id / purchase_invoice.entry_id (no existe FK real, mismo
// criterio que el resto de la app). Al editar una factura ya contabilizada
// se borra el asiento anterior y se crea uno nuevo (regeneracion completa,
// nunca un ajuste parcial) para que asiento y factura nunca diverjan.
class ModelAccountingAutoEntry extends Model {
	public function postSaleInvoice($invoice_id, $user) {
		if (!$this->config->get('config_conta_enabled')) {
			return;
		}

		$this->load->model('sale/invoice');
		$this->load->model('accounting/entry');

		$invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

		if (!$invoice_info) {
			return;
		}

		$this->removeEntry('invoice', $invoice_id);

		$digits = (int)$this->config->get('config_conta_digits') ?: 10;

		$customer_account = '';

		if (!empty($invoice_info['customer_id'])) {
			$this->load->model('sale/customer');

			$customer_info = $this->model_sale_customer->getCustomer($invoice_info['customer_id']);

			if ($customer_info && !empty($customer_info['contable_account'])) {
				$customer_account = $this->formatAccount($customer_info['contable_account'], $digits);
			}
		}

		if (!$customer_account) {
			$customer_account = $this->formatAccount($this->config->get('config_conta_cliente_account'), $digits);
		}

		$sales_account = $this->formatAccount($this->config->get('config_conta_ventas_account'), $digits);
		$iva_account   = $this->formatAccount($this->config->get('config_conta_iva_repercutido_account'), $digits);

		// Sin las cuentas minimas configuradas no se puede generar el asiento
		// (mejor no contabilizar nada a dejar un asiento con cuentas vacias).
		if (!$customer_account || !$sales_account) {
			return;
		}

		$totals = $this->model_sale_invoice->getInvoiceTotals($invoice_id);

		$concept = 'Fra. Venta ' . $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];

		if (!empty($invoice_info['company'])) {
			$concept .= ' - ' . $invoice_info['company'];
		}

		$this->postInvoiceTotals($invoice_id, 'invoice', $invoice_info['date_added'], $concept, $totals, $sales_account, $iva_account, $customer_account, $user);
	}

	public function postPurchaseInvoice($invoice_id, $user) {
		if (!$this->config->get('config_conta_enabled')) {
			return;
		}

		$this->load->model('purchase/invoice');
		$this->load->model('accounting/entry');

		$invoice_info = $this->model_purchase_invoice->getInvoice($invoice_id);

		if (!$invoice_info) {
			return;
		}

		$this->removeEntry('purchase_invoice', $invoice_id);

		$digits = (int)$this->config->get('config_conta_digits') ?: 10;

		$supplier_account = '';

		if (!empty($invoice_info['supplier_id'])) {
			$this->load->model('purchase/supplier');

			$supplier_info = $this->model_purchase_supplier->getSupplier($invoice_info['supplier_id']);

			if ($supplier_info && !empty($supplier_info['contable_account'])) {
				$supplier_account = $this->formatAccount($supplier_info['contable_account'], $digits);
			}
		}

		if (!$supplier_account) {
			$supplier_account = $this->formatAccount($this->config->get('config_conta_proveedor_account'), $digits);
		}

		$purchase_account = $this->formatAccount($this->config->get('config_conta_compras_account'), $digits);
		$iva_account      = $this->formatAccount($this->config->get('config_conta_iva_soportado_account'), $digits);

		if (!$supplier_account || !$purchase_account) {
			return;
		}

		$totals = $this->model_purchase_invoice->getInvoiceTotals($invoice_id);

		$concept = 'Fra. Compra ' . $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];

		if (!empty($invoice_info['payment_company'])) {
			$concept .= ' - ' . $invoice_info['payment_company'];
		}

		// En compra el neto/IVA van al Debe (gasto/IVA soportado) y el
		// proveedor al Haber - justo al reves que en venta.
		$this->postInvoiceTotals($invoice_id, 'purchase_invoice', $invoice_info['date_added'], $concept, $totals, $purchase_account, $iva_account, $supplier_account, $user, true);
	}

	// $reverse_sides = true para compras: base/IVA al debe, tercero al haber.
	// $reverse_sides = false (venta): base/IVA al haber, tercero al debe.
	// El signo de cada importe (positivo/negativo, para las facturas
	// rectificativas en negativo) decide el lado real via addBalancedLine().
	private function postInvoiceTotals($invoice_id, $table, $date_added, $concept, $totals, $net_account, $iva_account, $party_account, $user, $reverse_sides = false) {
		$net_side   = $reverse_sides ? 'debit' : 'credit';
		$party_side = $reverse_sides ? 'credit' : 'debit';

		$lines = array();
		$net = 0;
		$party_total = 0;

		foreach ($totals as $total) {
			if ($total['code'] == 'sub_total') {
				$net += (float)$total['value'];
			} elseif ($total['code'] == 'tax') {
				$this->addBalancedLine($lines, $iva_account, substr($concept . ' - ' . $total['title'], 0, 100), (float)$total['value'], $net_side);
			} elseif ($total['code'] == 'total') {
				$party_total += (float)$total['value'];
			}
		}

		if (!$party_total) {
			$party_total = $net; // fallback si por lo que sea no hay fila 'total'
		}

		if (!$net && !$party_total) {
			return; // nada que contabilizar
		}

		$concept_short = substr($concept, 0, 100);

		$net_lines = array();
		$this->addBalancedLine($net_lines, $net_account, $concept_short, $net, $net_side);

		$lines = array_merge($net_lines, $lines);

		$this->addBalancedLine($lines, $party_account, $concept_short, $party_total, $party_side);

		$entry_id = $this->model_accounting_entry->getNextEntryId();

		$this->model_accounting_entry->addEntry($entry_id, date('Y-m-d', strtotime($date_added)), $lines, $user);

		$this->db->query("UPDATE " . DB_PREFIX . $table . " SET entry_id = '" . (int)$entry_id . "' WHERE invoice_id = '" . (int)$invoice_id . "'");
	}

	// Anota $amount en $lines en el lado $normal_side ('debit'/'credit') si es
	// positivo, o en el lado contrario (en valor absoluto) si es negativo -
	// asi una factura rectificativa en negativo genera el asiento inverso
	// correcto sin duplicar logica.
	private function addBalancedLine(&$lines, $account, $concept, $amount, $normal_side) {
		if (!$account || (abs($amount) < 0.005)) {
			return;
		}

		$debit = 0;
		$credit = 0;

		if ($normal_side == 'debit') {
			if ($amount >= 0) {
				$debit = $amount;
			} else {
				$credit = -$amount;
			}
		} else {
			if ($amount >= 0) {
				$credit = $amount;
			} else {
				$debit = -$amount;
			}
		}

		$lines[] = array(
			'account' => $account,
			'concept' => $concept,
			'debit'   => round($debit, 2),
			'credit'  => round($credit, 2)
		);
	}

	private function formatAccount($code, $digits) {
		$code = preg_replace('/[^0-9]/', '', (string)$code);

		if ($code === '') {
			return '';
		}

		return str_pad($code, $digits, '0', STR_PAD_RIGHT);
	}

	// Borra el asiento (todas las lineas ctab8 de su entry_id) enlazado a una
	// factura ya contabilizada, si lo tiene. Se usa antes de regenerar el
	// asiento en una edicion, y desde purchase/invoice al borrar de verdad
	// una factura de compra (captura el entry_id ANTES del DELETE real).
	public function removeEntry($table, $invoice_id) {
		$query = $this->db->query("SELECT entry_id FROM " . DB_PREFIX . $table . " WHERE invoice_id = '" . (int)$invoice_id . "'");

		if ($query->num_rows && $query->row['entry_id']) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "ctab8 WHERE entry_id = '" . (int)$query->row['entry_id'] . "'");
			$this->db->query("UPDATE " . DB_PREFIX . $table . " SET entry_id = '0' WHERE invoice_id = '" . (int)$invoice_id . "'");
		}
	}

	public function getEntryId($table, $invoice_id) {
		$query = $this->db->query("SELECT entry_id FROM " . DB_PREFIX . $table . " WHERE invoice_id = '" . (int)$invoice_id . "'");

		return $query->num_rows ? (int)$query->row['entry_id'] : 0;
	}
}
?>
