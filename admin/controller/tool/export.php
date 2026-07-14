<?php
class ControllerToolExport extends Controller {
	public function index() {
		$this->language->load('tool/export');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_contaplus'] = $this->language->get('text_contaplus');
		$this->data['text_sage50'] = $this->language->get('text_sage50');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');

		$this->data['date_start'] = date('d-m-Y', strtotime(date('Y-m-01')));
		$this->data['date_end'] = date('d-m-Y');

		$this->data['action_contaplus'] = $this->url->link('tool/export/contaplus', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['action_sage50'] = $this->url->link('tool/export/sage50', 'token=' . $this->session->data['token'], 'SSL');

		// Setup warnings: without these, the export can't post a balanced entry for every invoice.
		$this->load->model('localisation/tax_rate');

		$warnings = array();

		if (!$this->config->get('config_conta_ventas_account')) {
			$warnings[] = $this->language->get('warning_no_sales_account');
		}

		if (!$this->config->get('config_conta_cliente_account')) {
			$warnings[] = $this->language->get('warning_no_customer_account');
		}

		foreach ($this->model_localisation_tax_rate->getTaxRates() as $tax_rate) {
			if ((float)$tax_rate['rate'] > 0 && empty($tax_rate['account'])) {
				$warnings[] = sprintf($this->language->get('warning_no_tax_account'), $tax_rate['name']);
			}
		}

		$this->data['warnings'] = $warnings;

		$this->template = 'tool/export.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function contaplus() {
		$entries = $this->prepareExport();

		if (!is_array($entries)) {
			return; // prepareExport() already set the error output
		}

		$lines = array();

		foreach ($entries as $entry) {
			$lines[] = $this->buildContaplusLine($entry);
		}

		$content = implode("\r\n", $lines) . "\r\n";
		$converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $content);

		if ($converted !== false) {
			$content = $converted;
		}

		$filename = 'contaplus_' . date('Ymd_His') . '.txt';

		$this->response->addHeader('Content-Type: text/plain; charset=ISO-8859-1');
		$this->response->addHeader('Content-Disposition: attachment; filename="' . $filename . '"');
		$this->response->setOutput($content);
	}

	public function sage50() {
		$entries = $this->prepareExport();

		if (!is_array($entries)) {
			return; // prepareExport() already set the error output
		}

		// UTF-8 BOM so Excel auto-detects the encoding (accented characters, ñ) on double-click.
		$content = "\xEF\xBB\xBF";
		$content .= "Asiento;Fecha;Cuenta;Concepto;Debe;Haber;Documento\r\n";

		foreach ($entries as $entry) {
			$content .= $this->buildSage50Line($entry) . "\r\n";
		}

		$filename = 'sage50_' . date('Ymd_His') . '.csv';

		$this->response->addHeader('Content-Type: text/csv; charset=UTF-8');
		$this->response->addHeader('Content-Disposition: attachment; filename="' . $filename . '"');
		$this->response->setOutput($content);
	}

	/**
	 * Builds the shared list of accounting entries (apuntes) for the invoices in the
	 * posted date range, common to every export format. Each entry is one side of a
	 * double-entry line: array(entry_no, date [DateTime], account, concept, document,
	 * debit, credit) with exactly one of debit/credit non-zero.
	 *
	 * On any blocking error this sets the response output itself and returns false,
	 * so callers just need to check `is_array($result)` before proceeding.
	 */
	private function prepareExport() {
		if (!$this->user->hasPermission('access', 'tool/export')) {
			$this->language->load('tool/export');
			$this->response->setOutput($this->language->get('error_permission'));
			return false;
		}

		$this->language->load('tool/export');

		$this->load->model('sale/invoice');
		$this->load->model('sale/customer');
		$this->load->model('localisation/tax_rate');

		$date_start = $this->parseDate(isset($this->request->post['date_start']) ? $this->request->post['date_start'] : '', date('Y-m-01'));
		$date_end   = $this->parseDate(isset($this->request->post['date_end']) ? $this->request->post['date_end'] : '', date('Y-m-d'));

		$sales_account = trim((string)$this->config->get('config_conta_ventas_account'));
		$default_customer_account = trim((string)$this->config->get('config_conta_cliente_account'));

		if (!$sales_account) {
			$this->response->setOutput($this->language->get('error_no_sales_account'));
			return false;
		}

		$tax_accounts = array();

		foreach ($this->model_localisation_tax_rate->getTaxRates() as $tax_rate) {
			$tax_accounts[$tax_rate['name']] = trim((string)$tax_rate['account']);
		}

		$invoices = $this->model_sale_invoice->getInvoices(array(
			'filter_date_from' => $date_start,
			'filter_date_to'   => $date_end,
			'sort'             => 'o.invoice_id',
			'order'            => 'ASC'
		));

		if (!$invoices) {
			$this->response->setOutput($this->language->get('error_no_invoices'));
			return false;
		}

		$entries = array();
		$entry_no = 0;
		$missing_tax_accounts = array();

		foreach ($invoices as $row) {
			$invoice_info = $this->model_sale_invoice->getInvoice($row['invoice_id']);

			if (!$invoice_info) {
				continue;
			}

			$total = (float)$invoice_info['total'];

			if (!$total) {
				continue;
			}

			$entry_no++;

			// Cancellations/credit notes are stored as invoices with a negative total:
			// keep the same accounts but book customer/sales/VAT on the opposite side.
			$flip = ($total < 0);

			$date = new DateTime($invoice_info['date_added']);
			$invoice_no = $invoice_info['invoice_no'] ? $invoice_info['invoice_no'] : $invoice_info['invoice_id'];
			$customer_name = $invoice_info['company'] ? $invoice_info['company'] : $invoice_info['payment_company'];
			$concept = 'FRA ' . $invoice_info['invoice_prefix'] . $invoice_no . ' ' . $customer_name;

			// Customer account: the customer's own accounting subaccount if set on
			// their fiscal record, otherwise the generic default from Settings.
			$customer_account = $default_customer_account;

			if ($invoice_info['customer_id']) {
				$customer_info = $this->model_sale_customer->getCustomer($invoice_info['customer_id']);

				if ($customer_info && $customer_info['contable_account']) {
					$customer_account = trim((string)$customer_info['contable_account']);
				}
			}

			if (!$customer_account) {
				continue;
			}

			// Line 1: customer, for the whole invoice total.
			$entries[] = $this->makeEntry($entry_no, $date, $customer_account, $concept, $invoice_no, abs($total), !$flip);

			// Lines 2..N: VAT per rate present on the invoice.
			$totals = $this->model_sale_invoice->getInvoiceTotals($row['invoice_id']);
			$credited = 0;

			foreach ($totals as $total_row) {
				if ($total_row['code'] == 'tax' && (float)$total_row['value'] != 0) {
					$tax_amount = (float)$total_row['value'];
					$account = isset($tax_accounts[$total_row['title']]) ? $tax_accounts[$total_row['title']] : '';

					if (!$account) {
						$missing_tax_accounts[$total_row['title']] = true;
						continue;
					}

					$entries[] = $this->makeEntry($entry_no, $date, $account, $concept, $invoice_no, abs($tax_amount), $flip);
					$credited += abs($tax_amount);
				}
			}

			// Last line: sales account, for whatever the VAT lines above didn't cover
			// (the taxable base, plus any other total line) - this guarantees the
			// entry always balances (Debe == Haber) regardless of the tax breakdown.
			$remainder = round(abs($total) - $credited, 2);

			if ($remainder != 0) {
				$entries[] = $this->makeEntry($entry_no, $date, $sales_account, $concept, $invoice_no, $remainder, $flip);
			}
		}

		if ($missing_tax_accounts) {
			$this->response->setOutput($this->language->get('error_no_tax_account') . ' ' . implode(', ', array_keys($missing_tax_accounts)));
			return false;
		}

		if (!$entries) {
			$this->response->setOutput($this->language->get('error_no_invoices'));
			return false;
		}

		return $entries;
	}

	private function makeEntry($entry_no, DateTime $date, $account, $concept, $document, $amount, $debe) {
		return array(
			'entry_no' => $entry_no,
			'date'     => $date,
			'account'  => $account,
			'concept'  => $concept,
			'document' => $document,
			'debit'    => $debe ? $amount : 0,
			'credit'   => $debe ? 0 : $amount,
		);
	}

	// The date fields use the same datepicker (DD-MM-YYYY) as the rest of the app.
	private function parseDate($value, $default) {
		$parsed = DateTime::createFromFormat('d-m-Y', (string)$value);

		return $parsed ? $parsed->format('Y-m-d') : $default;
	}

	/**
	 * ContaPlus fixed-width ASCII "formato de traspaso" record - one line per apunte
	 * (each asiento has 2+ lines sharing the same Nº Asiento: one Debe, one or more Haber,
	 * or the reverse for a cancellation). Column layout (1-based, no separators):
	 *
	 *   1-6    Nº Asiento     6 chars,  numeric, zero-padded left
	 *   7-14   Fecha          8 chars,  DDMMYYYY
	 *   15-24  Cuenta         10 chars, digits only, zero-padded RIGHT (trailing zeros
	 *                          extend the account group, e.g. "477000000" -> "4770000000")
	 *   25-64  Concepto       40 chars, text, space-padded right, truncated if longer
	 *   65-76  Importe Debe   12 chars, cents (no decimal point), zero-padded left,
	 *                          "000000000000" on a Haber line
	 *   77-88  Importe Haber  12 chars, same, "000000000000" on a Debe line
	 *   89-98  Documento      10 chars, text (invoice number), space-padded right
	 *
	 * IMPORTANT: verify these exact column widths against the actual ContaPlus 2014
	 * import wizard (Empresa > Traspasos > Importar asientos > Formato ASCII) before
	 * relying on this for real bookkeeping - this is the classic Sage/ContaPlus
	 * transfer format, but different editions/versions have shown minor variants.
	 */
	private function buildContaplusLine($entry) {
		$debe_cents  = (int)round($entry['debit'] * 100);
		$haber_cents = (int)round($entry['credit'] * 100);

		$account_digits = preg_replace('/[^0-9]/', '', (string)$entry['account']);

		$line  = str_pad((string)$entry['entry_no'], 6, '0', STR_PAD_LEFT);
		$line .= $entry['date']->format('dmY');
		$line .= str_pad(substr($account_digits, 0, 10), 10, '0', STR_PAD_RIGHT);
		$line .= str_pad(substr($entry['concept'], 0, 40), 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad((string)$debe_cents, 12, '0', STR_PAD_LEFT);
		$line .= str_pad((string)$haber_cents, 12, '0', STR_PAD_LEFT);
		$line .= str_pad(substr((string)$entry['document'], 0, 10), 10, ' ', STR_PAD_RIGHT);

		return $line;
	}

	/**
	 * Sage 50 (Contabilidad) accounting-entries import: a semicolon-separated CSV with
	 * a header row - Asiento;Fecha;Cuenta;Concepto;Debe;Haber;Documento - one line per
	 * apunte, Spanish locale numbers (comma decimal separator, no thousands separator)
	 * and DD/MM/YYYY dates.
	 *
	 * IMPORTANT: this is the common/modern Sage 50 CSV template; verify the exact
	 * column names/order your Sage 50 install expects (Contabilidad > Asientos >
	 * Importar) before relying on this for real bookkeeping - Sage 50 lets each
	 * installation remap columns, but the header names must still match what you use.
	 */
	private function buildSage50Line($entry) {
		$columns = array(
			$entry['entry_no'],
			$entry['date']->format('d/m/Y'),
			preg_replace('/[^0-9]/', '', (string)$entry['account']),
			$this->csvEscape($entry['concept']),
			$this->formatSage50Amount($entry['debit']),
			$this->formatSage50Amount($entry['credit']),
			$this->csvEscape((string)$entry['document']),
		);

		return implode(';', $columns);
	}

	private function formatSage50Amount($amount) {
		return number_format((float)$amount, 2, ',', '');
	}

	private function csvEscape($value) {
		$value = (string)$value;

		if (strpos($value, ';') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
			return '"' . str_replace('"', '""', $value) . '"';
		}

		return $value;
	}
}
