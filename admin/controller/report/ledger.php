<?php
class ControllerReportLedger extends Controller {
	public function index() {
		$this->language->load('report/ledger');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_date_start    = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : date('01-01-Y');
		$filter_date_end      = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : date('d-m-Y');
		$filter_account_start = isset($this->request->get['filter_account_start']) ? $this->request->get['filter_account_start'] : '';
		$filter_account_end   = isset($this->request->get['filter_account_end']) ? $this->request->get['filter_account_end'] : '';
		$show_opening_balance = !empty($this->request->get['show_opening_balance']);
		$new_page_per_account = isset($this->request->get['new_page_per_account']) ? !empty($this->request->get['new_page_per_account']) : true;

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/ledger', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/ledger');

		$data = $this->buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end);

		$this->data['accounts'] = $this->buildAccounts($data, $show_opening_balance);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results']           = $this->language->get('text_no_results');
		$this->data['text_period']               = $this->language->get('text_period');
		$this->data['text_accounts']              = $this->language->get('text_accounts');
		$this->data['text_opening_balance']      = $this->language->get('text_opening_balance');
		$this->data['text_show_opening_balance'] = $this->language->get('text_show_opening_balance');
		$this->data['text_new_page_per_account'] = $this->language->get('text_new_page_per_account');
		$this->data['text_total']                = $this->language->get('text_total');

		$this->data['entry_from'] = $this->language->get('entry_from');
		$this->data['entry_to']   = $this->language->get('entry_to');

		$this->data['column_entry']   = $this->language->get('column_entry');
		$this->data['column_concept'] = $this->language->get('column_concept');
		$this->data['column_debit']   = $this->language->get('column_debit');
		$this->data['column_credit']  = $this->language->get('column_credit');
		$this->data['column_balance'] = $this->language->get('column_balance');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_print']  = $this->language->get('button_print');

		$this->data['filter_date_start']    = $filter_date_start;
		$this->data['filter_date_end']      = $filter_date_end;
		$this->data['filter_account_start'] = $filter_account_start;
		$this->data['filter_account_end']   = $filter_account_end;
		$this->data['show_opening_balance'] = $show_opening_balance;
		$this->data['new_page_per_account'] = $new_page_per_account;

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'report/ledger_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('report/ledger');

		if (!$this->user->hasPermission('access', 'report/ledger')) {
			die('Permission denied');
		}

		$filter_date_start    = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end      = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';
		$filter_account_start = isset($this->request->get['filter_account_start']) ? $this->request->get['filter_account_start'] : '';
		$filter_account_end   = isset($this->request->get['filter_account_end']) ? $this->request->get['filter_account_end'] : '';
		$show_opening_balance = !empty($this->request->get['show_opening_balance']);

		$this->load->model('report/ledger');

		$data = $this->buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end);

		$accounts = $this->buildAccounts($data, $show_opening_balance);

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Cuenta";"Titulo";"Asiento";"Fecha";"Concepto";"Debe";"Haber";"Saldo"' . "\n";

		foreach ($accounts as $account) {
			foreach ($account['rows'] as $row) {
				$csv .= '"' . str_replace('"', '""', (string)$account['code'])  . '";';
				$csv .= '"' . str_replace('"', '""', (string)$account['title']) . '";';
				$csv .= '"' . (($row['entry_id'] !== '') ? (int)$row['entry_id'] : '') . '";';
				$csv .= '"' . str_replace('"', '""', (string)$row['line_date']) . '";';
				$csv .= '"' . str_replace('"', '""', (string)$row['concept'])   . '";';
				$csv .= '"' . str_replace('"', '""', (string)$row['debit'])     . '";';
				$csv .= '"' . str_replace('"', '""', (string)$row['credit'])    . '";';
				$csv .= '"' . str_replace('"', '""', (string)$row['balance'])   . '"' . "\n";
			}
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="libro_mayor_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	public function printout() {
		$this->language->load('report/ledger');

		$this->load->model('report/ledger');

		$format = isset($this->request->get['format']) ? $this->request->get['format'] : 'view';

		$filter_date_start    = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end      = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';
		$filter_account_start = isset($this->request->get['filter_account_start']) ? $this->request->get['filter_account_start'] : '';
		$filter_account_end   = isset($this->request->get['filter_account_end']) ? $this->request->get['filter_account_end'] : '';
		$show_opening_balance = !empty($this->request->get['show_opening_balance']);
		$new_page_per_account = isset($this->request->get['new_page_per_account']) ? !empty($this->request->get['new_page_per_account']) : true;

		$data = $this->buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end);

		$this->data['accounts'] = $this->buildAccounts($data, $show_opening_balance);
		$this->data['new_page_per_account'] = $new_page_per_account;

		$date_format = $this->language->get('date_format_short');

		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_company']    = $this->language->get('text_company');
		$this->data['text_period']     = $this->language->get('text_period');
		$this->data['text_print_date'] = $this->language->get('text_print_date');
		$this->data['text_total']      = $this->language->get('text_total');

		$this->data['column_entry']   = $this->language->get('column_entry');
		$this->data['column_concept'] = $this->language->get('column_concept');
		$this->data['column_debit']   = $this->language->get('column_debit');
		$this->data['column_credit']  = $this->language->get('column_credit');
		$this->data['column_balance'] = $this->language->get('column_balance');

		$this->data['company_name'] = $this->config->get('config_name');

		$this->data['period'] = ($data['filter_date_start'] ? date($date_format, strtotime($data['filter_date_start'])) : '') . ' - ' . ($data['filter_date_end'] ? date($date_format, strtotime($data['filter_date_end'])) : '');

		$this->data['print_date'] = date($date_format);

		if ($format == 'pdf') {
			$this->renderPDF('report/ledger_print.tpl', 'pdf', 'ledger', date('YmdHis'));
		} else {
			$this->template = 'report/ledger_print.tpl';
			$this->response->setOutput($this->render());
		}
	}

	private function buildAccounts($data, $show_opening_balance) {
		$date_format = $this->language->get('date_format_short');

		$accounts = array();

		foreach ($this->model_report_ledger->getAccounts($data) as $account) {
			$rows = array();
			$balance = 0;

			if ($show_opening_balance) {
				$balance = $this->model_report_ledger->getOpeningBalance($account['code'], $data);

				$rows[] = array(
					'entry_id'  => '',
					'line_date' => '',
					'concept'   => $this->language->get('text_opening_balance'),
					'debit'     => '',
					'credit'    => '',
					'balance'   => $this->formatAmount($balance)
				);
			}

			$total_debit  = 0;
			$total_credit = 0;

			foreach ($this->model_report_ledger->getEntries($account['code'], $data) as $entry) {
				$balance      += (float)$entry['debit'] - (float)$entry['credit'];
				$total_debit  += (float)$entry['debit'];
				$total_credit += (float)$entry['credit'];

				$rows[] = array(
					'entry_id'  => $entry['entry_id'],
					'line_date' => $entry['line_date'] ? date($date_format, strtotime($entry['line_date'])) : '',
					'concept'   => $entry['concept'],
					'debit'     => $entry['debit'] > 0 ? $this->formatAmount($entry['debit']) : '',
					'credit'    => $entry['credit'] > 0 ? $this->formatAmount($entry['credit']) : '',
					'balance'   => $this->formatAmount($balance)
				);
			}

			$accounts[] = array(
				'code'         => $account['code'],
				'title'        => $account['title'],
				'rows'         => $rows,
				'total_debit'  => $this->formatAmount($total_debit),
				'total_credit' => $this->formatAmount($total_credit),
				'balance'      => $this->formatAmount($balance)
			);
		}

		return $accounts;
	}

	private function formatAmount($value) {
		return number_format((float)$value, 2, ',', '.');
	}

	private function buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end) {
		return array(
			'filter_account_start' => $filter_account_start,
			'filter_account_end'   => $filter_account_end,
			'filter_date_start'    => $this->toSqlDate($filter_date_start),
			'filter_date_end'      => $this->toSqlDate($filter_date_end)
		);
	}

	// El datepicker (clase .date de common.js) envia DD-MM-YYYY; el modelo compara en formato YYYY-MM-DD.
	private function toSqlDate($date) {
		if (empty($date) || !preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $match)) {
			return '';
		}

		return $match[3] . '-' . $match[2] . '-' . $match[1];
	}

	private function buildUrl() {
		$url = '';

		foreach (array('filter_date_start', 'filter_date_end', 'filter_account_start', 'filter_account_end', 'show_opening_balance', 'new_page_per_account') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}
