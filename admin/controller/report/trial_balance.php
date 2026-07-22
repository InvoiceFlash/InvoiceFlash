<?php
class ControllerReportTrialBalance extends Controller {
	public function index() {
		$this->language->load('report/trial_balance');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_date_start    = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : date('01-01-Y');
		$filter_date_end      = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : date('d-m-Y');
		$filter_account_start = isset($this->request->get['filter_account_start']) ? $this->request->get['filter_account_start'] : '0000000000';
		$filter_account_end   = isset($this->request->get['filter_account_end']) ? $this->request->get['filter_account_end'] : '9999999999';
		$include_zero_balance = isset($this->request->get['include_zero_balance']) ? !empty($this->request->get['include_zero_balance']) : true;
		$filter_levels        = isset($this->request->get['filter_levels']) && $this->request->get['filter_levels'] !== '' ? explode(',', $this->request->get['filter_levels']) : array('sub');
		$balance_columns      = (isset($this->request->get['balance_columns']) && $this->request->get['balance_columns'] == 'one') ? 'one' : 'two';

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/trial_balance', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/trial_balance');

		$filtered = isset($this->request->get['filtered']);

		if ($filtered) {
			$data = $this->buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end);

			$this->data['result'] = $this->buildRows($data, $filter_levels, $include_zero_balance, $balance_columns);
		} else {
			$this->data['result'] = array('rows' => array());
		}

		$this->data['filtered'] = $filtered;

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results']           = $this->language->get('text_no_results');
		$this->data['text_company']              = $this->language->get('text_company');
		$this->data['text_period']               = $this->language->get('text_period');
		$this->data['text_accounts']             = $this->language->get('text_accounts');
		$this->data['text_print_date']           = $this->language->get('text_print_date');
		$this->data['text_include_zero_balance'] = $this->language->get('text_include_zero_balance');
		$this->data['text_breakdown_levels']     = $this->language->get('text_breakdown_levels');
		$this->data['text_level_1']              = $this->language->get('text_level_1');
		$this->data['text_level_2']              = $this->language->get('text_level_2');
		$this->data['text_level_3']              = $this->language->get('text_level_3');
		$this->data['text_level_4']              = $this->language->get('text_level_4');
		$this->data['text_subaccounts_level']    = $this->language->get('text_subaccounts_level');
		$this->data['text_balance_columns']      = $this->language->get('text_balance_columns');
		$this->data['text_one_column']           = $this->language->get('text_one_column');
		$this->data['text_two_columns']          = $this->language->get('text_two_columns');
		$this->data['text_total']                = $this->language->get('text_total');

		$this->data['entry_from'] = $this->language->get('entry_from');
		$this->data['entry_to']   = $this->language->get('entry_to');

		$this->data['column_account']        = $this->language->get('column_account');
		$this->data['column_title']          = $this->language->get('column_title');
		$this->data['column_debit']          = $this->language->get('column_debit');
		$this->data['column_credit']         = $this->language->get('column_credit');
		$this->data['column_balance']        = $this->language->get('column_balance');
		$this->data['column_debit_balance']  = $this->language->get('column_debit_balance');
		$this->data['column_credit_balance'] = $this->language->get('column_credit_balance');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_print']  = $this->language->get('button_print');

		$this->data['filter_date_start']    = $filter_date_start;
		$this->data['filter_date_end']      = $filter_date_end;
		$this->data['filter_account_start'] = $filter_account_start;
		$this->data['filter_account_end']   = $filter_account_end;
		$this->data['include_zero_balance'] = $include_zero_balance;
		$this->data['filter_levels']        = $filter_levels;
		$this->data['balance_columns']      = $balance_columns;

		$this->data['company_name']  = $this->config->get('config_name');
		$this->data['period']        = $filter_date_start . ' &mdash; ' . $filter_date_end;
		$this->data['account_range'] = $filter_account_start . ' &mdash; ' . $filter_account_end;
		$this->data['print_date']    = date($this->language->get('date_format_short'));

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'report/trial_balance_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('report/trial_balance');

		if (!$this->user->hasPermission('access', 'report/trial_balance')) {
			die('Permission denied');
		}

		$filter_date_start    = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end      = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';
		$filter_account_start = isset($this->request->get['filter_account_start']) ? $this->request->get['filter_account_start'] : '';
		$filter_account_end   = isset($this->request->get['filter_account_end']) ? $this->request->get['filter_account_end'] : '';
		$include_zero_balance = !empty($this->request->get['include_zero_balance']);
		$filter_levels        = isset($this->request->get['filter_levels']) && $this->request->get['filter_levels'] !== '' ? explode(',', $this->request->get['filter_levels']) : array('sub');
		$balance_columns      = (isset($this->request->get['balance_columns']) && $this->request->get['balance_columns'] == 'one') ? 'one' : 'two';

		$this->load->model('report/trial_balance');

		$data = $this->buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end);

		$result = $this->buildRows($data, $filter_levels, $include_zero_balance, $balance_columns);

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Cuenta";"Titulo";"Debe";"Haber";"Saldo Deudor";"Saldo Acreedor"' . "\n";

		foreach ($result['rows'] as $row) {
			$csv .= '"' . str_replace('"', '""', (string)$row['code'])          . '";';
			$csv .= '"' . str_replace('"', '""', (string)$row['title'])         . '";';
			$csv .= '"' . str_replace('"', '""', (string)$row['debit'])         . '";';
			$csv .= '"' . str_replace('"', '""', (string)$row['credit'])        . '";';
			$csv .= '"' . str_replace('"', '""', (string)(isset($row['debit_balance']) ? $row['debit_balance'] : '')) . '";';
			$csv .= '"' . str_replace('"', '""', (string)(isset($row['credit_balance']) ? $row['credit_balance'] : '')) . '"' . "\n";
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="balance_sumas_saldos_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	public function printout() {
		$this->language->load('report/trial_balance');

		$this->load->model('report/trial_balance');

		$format = isset($this->request->get['format']) ? $this->request->get['format'] : 'view';

		$filter_date_start    = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end      = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';
		$filter_account_start = isset($this->request->get['filter_account_start']) ? $this->request->get['filter_account_start'] : '';
		$filter_account_end   = isset($this->request->get['filter_account_end']) ? $this->request->get['filter_account_end'] : '';
		$include_zero_balance = !empty($this->request->get['include_zero_balance']);
		$filter_levels        = isset($this->request->get['filter_levels']) && $this->request->get['filter_levels'] !== '' ? explode(',', $this->request->get['filter_levels']) : array('sub');
		$balance_columns      = (isset($this->request->get['balance_columns']) && $this->request->get['balance_columns'] == 'one') ? 'one' : 'two';

		$data = $this->buildFilterData($filter_account_start, $filter_account_end, $filter_date_start, $filter_date_end);

		$this->data['result']          = $this->buildRows($data, $filter_levels, $include_zero_balance, $balance_columns);
		$this->data['balance_columns'] = $balance_columns;

		$date_format = $this->language->get('date_format_short');

		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_company']    = $this->language->get('text_company');
		$this->data['text_period']     = $this->language->get('text_period');
		$this->data['text_print_date'] = $this->language->get('text_print_date');
		$this->data['text_total']      = $this->language->get('text_total');

		$this->data['column_account']        = $this->language->get('column_account');
		$this->data['column_title']          = $this->language->get('column_title');
		$this->data['column_debit']          = $this->language->get('column_debit');
		$this->data['column_credit']         = $this->language->get('column_credit');
		$this->data['column_balance']        = $this->language->get('column_balance');
		$this->data['column_debit_balance']  = $this->language->get('column_debit_balance');
		$this->data['column_credit_balance'] = $this->language->get('column_credit_balance');

		$this->data['company_name'] = $this->config->get('config_name');

		$this->data['period'] = ($data['filter_date_start'] ? date($date_format, strtotime($data['filter_date_start'])) : '') . ' - ' . ($data['filter_date_end'] ? date($date_format, strtotime($data['filter_date_end'])) : '');

		$this->data['print_date'] = date($date_format);

		if ($format == 'pdf') {
			$this->renderPDF('report/trial_balance_print.tpl', 'pdf', 'trial_balance', date('YmdHis'));
		} else {
			$this->template = 'report/trial_balance_print.tpl';
			$this->response->setOutput($this->render());
		}
	}

	private function buildRows($data, $filter_levels, $include_zero_balance, $balance_columns) {
		$entries = array();

		foreach (array('1', '2', '3', '4') as $level) {
			if (!in_array($level, $filter_levels)) {
				continue;
			}

			foreach ($this->model_report_trial_balance->getGroupBalances($data, (int)$level) as $group) {
				$entries[] = array(
					'code'   => $group['code'],
					'title'  => $this->model_report_trial_balance->getAccountTitle($group['code']),
					'debit'  => (float)$group['debit'],
					'credit' => (float)$group['credit']
				);
			}
		}

		if (in_array('sub', $filter_levels)) {
			foreach ($this->model_report_trial_balance->getSubaccountBalances($data) as $account) {
				$entries[] = array(
					'code'   => $account['code'],
					'title'  => $account['title'],
					'debit'  => (float)$account['debit'],
					'credit' => (float)$account['credit']
				);
			}
		}

		usort($entries, function ($a, $b) {
			return strcmp($a['code'], $b['code']);
		});

		$rows = array();
		$total_debit  = 0;
		$total_credit = 0;

		foreach ($entries as $entry) {
			$net = $entry['debit'] - $entry['credit'];

			$total_debit  += $entry['debit'];
			$total_credit += $entry['credit'];

			if (!$include_zero_balance && $net == 0.0) {
				continue;
			}

			$rows[] = $this->formatRow($entry['code'], $entry['title'], $entry['debit'], $entry['credit'], $net, $balance_columns);
		}

		return $this->formatTotals($rows, $total_debit, $total_credit, $balance_columns);
	}

	private function formatRow($code, $title, $debit, $credit, $net, $balance_columns) {
		$row = array(
			'code'  => $code,
			'title' => $title,
			'debit' => $debit > 0 ? $this->formatAmount($debit) : '',
			'credit' => $credit > 0 ? $this->formatAmount($credit) : ''
		);

		if ($balance_columns == 'one') {
			$row['balance'] = $this->formatAmount($net);
		} else {
			$row['debit_balance']  = $net > 0 ? $this->formatAmount($net) : '';
			$row['credit_balance'] = $net < 0 ? $this->formatAmount(-$net) : '';
		}

		return $row;
	}

	private function formatTotals($rows, $total_debit, $total_credit, $balance_columns) {
		$total_net = $total_debit - $total_credit;

		$result = array(
			'rows'         => $rows,
			'total_debit'  => $this->formatAmount($total_debit),
			'total_credit' => $this->formatAmount($total_credit)
		);

		if ($balance_columns == 'one') {
			$result['total_balance'] = $this->formatAmount($total_net);
		} else {
			$result['total_debit_balance']  = $total_net > 0 ? $this->formatAmount($total_net) : $this->formatAmount(0);
			$result['total_credit_balance'] = $total_net < 0 ? $this->formatAmount(-$total_net) : $this->formatAmount(0);
		}

		return $result;
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

		foreach (array('filter_date_start', 'filter_date_end', 'filter_account_start', 'filter_account_end', 'include_zero_balance', 'filter_levels', 'balance_columns', 'filtered') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}
