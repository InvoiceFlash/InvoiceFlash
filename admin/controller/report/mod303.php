<?php
class ControllerReportMod303 extends Controller {
	public function index() {
		$this->language->load('report/mod303');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_date_start = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : date('01-01-Y');
		$filter_date_end   = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : date('d-m-Y');

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/mod303', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/mod303');

		$filtered = isset($this->request->get['filtered']);

		if ($filtered) {
			$data = array(
				'filter_date_start' => $this->toSqlDate($filter_date_start),
				'filter_date_end'   => $this->toSqlDate($filter_date_end)
			);

			$this->data['rows'] = $this->buildRows($data);
		} else {
			$this->data['rows'] = array();
		}

		$this->data['filtered'] = $filtered;

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_period']     = $this->language->get('text_period');

		$this->data['entry_from'] = $this->language->get('entry_from');
		$this->data['entry_to']   = $this->language->get('entry_to');

		$this->data['column_code']   = $this->language->get('column_code');
		$this->data['column_name']   = $this->language->get('column_name');
		$this->data['column_amount'] = $this->language->get('column_amount');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_print']  = $this->language->get('button_print');

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end']   = $filter_date_end;

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'report/mod303_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('report/mod303');

		if (!$this->user->hasPermission('access', 'report/mod303')) {
			die('Permission denied');
		}

		$data = array(
			'filter_date_start' => $this->toSqlDate(isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : ''),
			'filter_date_end'   => $this->toSqlDate(isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '')
		);

		$this->load->model('report/mod303');

		$rows = $this->buildRows($data);

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Casilla";"Concepto";"Importe"' . "\n";

		foreach ($rows as $row) {
			$csv .= '"' . str_replace('"', '""', $row['code']) . '";';
			$csv .= '"' . str_replace('"', '""', $row['name']) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$row['amount']) . '"' . "\n";
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="modelo_303_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	public function printout() {
		$this->language->load('report/mod303');

		$this->load->model('report/mod303');

		$format = isset($this->request->get['format']) ? $this->request->get['format'] : 'view';

		$filter_date_start = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end   = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';

		$data = array(
			'filter_date_start' => $this->toSqlDate($filter_date_start),
			'filter_date_end'   => $this->toSqlDate($filter_date_end)
		);

		$this->data['rows'] = $this->buildRows($data);

		$date_format = $this->language->get('date_format_short');

		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_company']    = $this->language->get('text_company');
		$this->data['text_period']     = $this->language->get('text_period');
		$this->data['text_print_date'] = $this->language->get('text_print_date');

		$this->data['column_code']   = $this->language->get('column_code');
		$this->data['column_name']   = $this->language->get('column_name');
		$this->data['column_amount'] = $this->language->get('column_amount');

		$this->data['company_name'] = $this->config->get('config_name');

		$this->data['period'] = ($data['filter_date_start'] ? date($date_format, strtotime($data['filter_date_start'])) : '') . ' - ' . ($data['filter_date_end'] ? date($date_format, strtotime($data['filter_date_end'])) : '');

		$this->data['print_date'] = date($date_format);

		if ($format == 'pdf') {
			$this->renderPDF('report/mod303_print.tpl', 'pdf', 'modelo_303', date('YmdHis'));
		} else {
			$this->template = 'report/mod303_print.tpl';
			$this->response->setOutput($this->render());
		}
	}

	private function buildRows($data) {
		$structure = $this->model_report_mod303->getStructure();

		$values = $this->computeValues($structure, $data);

		$rows = array();

		foreach ($structure as $row) {
			$rows[] = array(
				'code'   => $row['code'],
				'name'   => $row['name'],
				'bold'   => ((int)$row['level'] == 1),
				'amount' => $this->formatAmount($values[$row['code']])
			);
		}

		return $rows;
	}

	private function computeValues($structure, $data) {
		$by_code = array();

		foreach ($structure as $i => $row) {
			$by_code[$row['code']] = $i;
		}

		$values = array();

		foreach ($structure as $row) {
			$this->resolveValue($row['code'], $structure, $by_code, $data, $values);
		}

		return $values;
	}

	private function resolveValue($code, $structure, $by_code, $data, &$values) {
		if (isset($values[$code])) {
			return $values[$code];
		}

		if (!isset($by_code[$code])) {
			return 0.0;
		}

		$row      = $structure[$by_code[$code]];
		$accounts = trim($row['accounts']);

		if ($accounts === '') {
			$value = 0.0;
		} elseif (preg_match('/^-?[0-9\s]+$/', $accounts)) {
			// Un "-" inicial invierte el signo (Debe - Haber en vez de Haber - Debe),
			// necesario para cuentas de IVA soportado (472), que son de naturaleza deudora.
			$negate = false;

			if ($accounts[0] === '-') {
				$negate   = true;
				$accounts = ltrim(substr($accounts, 1));
			}

			$prefixes = preg_split('/\s+/', $accounts, -1, PREG_SPLIT_NO_EMPTY);
			$value    = $this->model_report_mod303->getAccountBalance($prefixes, $data);

			if ($negate) {
				$value = -$value;
			}
		} else {
			preg_match_all('/([+-]?)C(\d+)(?:\/([0-9.]+))?/', $accounts, $matches, PREG_SET_ORDER);
			$value = 0.0;

			foreach ($matches as $match) {
				$sign     = ($match[1] === '-') ? -1 : 1;
				$referred = $this->resolveValue($match[2], $structure, $by_code, $data, $values);

				if (isset($match[3]) && $match[3] !== '' && (float)$match[3] != 0.0) {
					$referred = $referred / (float)$match[3];
				}

				$value += $sign * $referred;
			}
		}

		$values[$code] = $value;

		return $value;
	}

	private function formatAmount($value) {
		return number_format((float)$value, 2, ',', '.');
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

		foreach (array('filter_date_start', 'filter_date_end', 'filtered') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}
