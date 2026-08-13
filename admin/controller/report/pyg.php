<?php
class ControllerReportPyg extends Controller {
	public function index() {
		$this->language->load('report/pyg');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_date_start      = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : date('01-01-Y');
		$filter_date_end        = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : date('d-m-Y');
		$compare                = isset($this->request->get['compare']) ? !empty($this->request->get['compare']) : false;
		$filter_date_start_prev = isset($this->request->get['filter_date_start_prev']) ? $this->request->get['filter_date_start_prev'] : '';
		$filter_date_end_prev   = isset($this->request->get['filter_date_end_prev']) ? $this->request->get['filter_date_end_prev'] : '';

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/pyg', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/pyg');

		$filtered = isset($this->request->get['filtered']);

		if ($filtered) {
			$data = array(
				'filter_date_start' => $this->toSqlDate($filter_date_start),
				'filter_date_end'   => $this->toSqlDate($filter_date_end)
			);

			$data_prev = null;

			if ($compare) {
				$data_prev = array(
					'filter_date_start' => $this->toSqlDate($filter_date_start_prev),
					'filter_date_end'   => $this->toSqlDate($filter_date_end_prev)
				);
			}

			$this->data['rows'] = $this->buildRows($data, $data_prev);
		} else {
			$this->data['rows'] = array();
		}

		$this->data['filtered'] = $filtered;
		$this->data['compare']  = $compare;

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_period']     = $this->language->get('text_period');
		$this->data['text_compare']    = $this->language->get('text_compare');

		$this->data['entry_from'] = $this->language->get('entry_from');
		$this->data['entry_to']   = $this->language->get('entry_to');

		$this->data['column_concept']     = $this->language->get('column_concept');
		$this->data['column_amount']      = $this->language->get('column_amount');
		$this->data['column_amount_prev'] = $this->language->get('column_amount_prev');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_print']  = $this->language->get('button_print');

		$this->data['filter_date_start']      = $filter_date_start;
		$this->data['filter_date_end']        = $filter_date_end;
		$this->data['filter_date_start_prev'] = $filter_date_start_prev;
		$this->data['filter_date_end_prev']   = $filter_date_end_prev;

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'report/pyg_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('report/pyg');

		if (!$this->user->hasPermission('access', 'report/pyg')) {
			die('Permission denied');
		}

		$compare = !empty($this->request->get['compare']);

		$data = array(
			'filter_date_start' => $this->toSqlDate(isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : ''),
			'filter_date_end'   => $this->toSqlDate(isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '')
		);

		$data_prev = null;

		if ($compare) {
			$data_prev = array(
				'filter_date_start' => $this->toSqlDate(isset($this->request->get['filter_date_start_prev']) ? $this->request->get['filter_date_start_prev'] : ''),
				'filter_date_end'   => $this->toSqlDate(isset($this->request->get['filter_date_end_prev']) ? $this->request->get['filter_date_end_prev'] : '')
			);
		}

		$this->load->model('report/pyg');

		$rows = $this->buildRows($data, $data_prev);

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= $compare ? '"Concepto";"Importe";"Periodo Anterior"' . "\n" : '"Concepto";"Importe"' . "\n";

		foreach ($rows as $row) {
			$csv .= '"' . str_replace('"', '""', str_repeat('  ', $row['level']) . $row['name']) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$row['amount']) . '"';
			if ($compare) {
				$csv .= ';"' . str_replace('"', '""', (string)$row['amount_prev']) . '"';
			}
			$csv .= "\n";
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="perdidas_ganancias_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	public function printout() {
		$this->language->load('report/pyg');

		$this->load->model('report/pyg');

		$format = isset($this->request->get['format']) ? $this->request->get['format'] : 'view';

		$compare = !empty($this->request->get['compare']);

		$filter_date_start      = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end        = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';
		$filter_date_start_prev = isset($this->request->get['filter_date_start_prev']) ? $this->request->get['filter_date_start_prev'] : '';
		$filter_date_end_prev   = isset($this->request->get['filter_date_end_prev']) ? $this->request->get['filter_date_end_prev'] : '';

		$data = array(
			'filter_date_start' => $this->toSqlDate($filter_date_start),
			'filter_date_end'   => $this->toSqlDate($filter_date_end)
		);

		$data_prev = null;

		if ($compare) {
			$data_prev = array(
				'filter_date_start' => $this->toSqlDate($filter_date_start_prev),
				'filter_date_end'   => $this->toSqlDate($filter_date_end_prev)
			);
		}

		$this->data['rows']    = $this->buildRows($data, $data_prev);
		$this->data['compare'] = $compare;

		$date_format = $this->language->get('date_format_short');

		$this->data['heading_title']   = $this->language->get('heading_title');
		$this->data['text_company']    = $this->language->get('text_company');
		$this->data['text_period']     = $this->language->get('text_period');
		$this->data['text_print_date'] = $this->language->get('text_print_date');

		$this->data['column_concept']     = $this->language->get('column_concept');
		$this->data['column_amount']      = $this->language->get('column_amount');
		$this->data['column_amount_prev'] = $this->language->get('column_amount_prev');

		$this->data['company_name'] = $this->config->get('config_name');

		$this->data['period'] = ($data['filter_date_start'] ? date($date_format, strtotime($data['filter_date_start'])) : '') . ' - ' . ($data['filter_date_end'] ? date($date_format, strtotime($data['filter_date_end'])) : '');

		if ($data_prev) {
			$this->data['period_prev'] = ($data_prev['filter_date_start'] ? date($date_format, strtotime($data_prev['filter_date_start'])) : '') . ' - ' . ($data_prev['filter_date_end'] ? date($date_format, strtotime($data_prev['filter_date_end'])) : '');
		}

		$this->data['print_date'] = date($date_format);

		if ($format == 'pdf') {
			$this->renderPDF('report/pyg_print.tpl', 'pdf', 'perdidas_ganancias', date('YmdHis'));
		} else {
			$this->template = 'report/pyg_print.tpl';
			$this->response->setOutput($this->render());
		}
	}

	private function buildRows($data, $data_prev = null) {
		$structure = $this->model_report_pyg->getStructure();

		$values      = $this->computeValues($structure, $data);
		$values_prev = $data_prev ? $this->computeValues($structure, $data_prev) : array();

		$rows = array();

		foreach ($structure as $row) {
			$entry = array(
				'code'   => $row['code'],
				'name'   => $row['name'],
				'level'  => (int)$row['level'],
				'bold'   => !$this->isLeaf($row['accounts']),
				'amount' => $this->formatAmount($values[$row['code']])
			);

			if ($data_prev) {
				$entry['amount_prev'] = $this->formatAmount($values_prev[$row['code']]);
			}

			$rows[] = $entry;
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

		$i    = $by_code[$code];
		$row  = $structure[$i];
		$accounts = trim($row['accounts']);

		if ($accounts === '') {
			$level = (int)$row['level'];
			$value = 0.0;
			$total = count($structure);

			for ($j = $i + 1; $j < $total; $j++) {
				$child_level = (int)$structure[$j]['level'];

				if ($child_level <= $level) {
					break;
				}

				if ($child_level == $level + 1) {
					$value += $this->resolveValue($structure[$j]['code'], $structure, $by_code, $data, $values);
				}
			}
		} elseif (preg_match('/^[0-9\s]+$/', $accounts)) {
			$prefixes = preg_split('/\s+/', $accounts, -1, PREG_SPLIT_NO_EMPTY);
			$value    = $this->model_report_pyg->getAccountBalance($prefixes, $data);
		} else {
			preg_match_all('/([+-]?)([A-Za-z][A-Za-z0-9]*)/', $accounts, $matches, PREG_SET_ORDER);
			$value = 0.0;

			foreach ($matches as $match) {
				$sign  = ($match[1] === '-') ? -1 : 1;
				$value += $sign * $this->resolveValue($match[2], $structure, $by_code, $data, $values);
			}
		}

		$values[$code] = $value;

		return $value;
	}

	private function isLeaf($accounts) {
		$accounts = trim($accounts);

		return ($accounts !== '' && preg_match('/^[0-9\s]+$/', $accounts));
	}

	private function formatAmount($value) {
		return number_format((float)$value, 2, $this->config->get('config_decimal_point') ?: ',', $this->config->get('config_thousand_point') ?: '.');
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

		foreach (array('filter_date_start', 'filter_date_end', 'compare', 'filter_date_start_prev', 'filter_date_end_prev', 'filtered') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}
