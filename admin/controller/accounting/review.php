<?php
class ControllerAccountingReview extends Controller {
	public function index() {
		$this->language->load('accounting/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$filter_mode         = isset($this->request->get['filter_mode']) ? $this->request->get['filter_mode'] : 'all';
		$filter_number_start = isset($this->request->get['filter_number_start']) ? $this->request->get['filter_number_start'] : '';
		$filter_number_end   = isset($this->request->get['filter_number_end']) ? $this->request->get['filter_number_end'] : '';
		$filter_date_start   = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end     = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';
		$filter_amount       = isset($this->request->get['filter_amount']) ? $this->request->get['filter_amount'] : '';
		$filter_concept      = isset($this->request->get['filter_concept']) ? $this->request->get['filter_concept'] : '';
		$filter_account      = isset($this->request->get['filter_account']) ? $this->request->get['filter_account'] : '';

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = $this->buildUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('accounting/review', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('accounting/review');

		$data = array(
			'filter_mode'         => $filter_mode,
			'filter_number_start' => $filter_number_start,
			'filter_number_end'   => $filter_number_end,
			'filter_date_start'   => $this->toSqlDate($filter_date_start),
			'filter_date_end'     => $this->toSqlDate($filter_date_end),
			'filter_amount'       => $filter_amount,
			'filter_concept'      => $filter_concept,
			'filter_account'      => $filter_account,
			'start'               => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'               => $this->config->get('config_admin_limit')
		);

		$entry_total = $this->model_accounting_review->getTotalEntries($data);
		$results     = $this->model_accounting_review->getEntries($data);

		$date_format = $this->language->get('date_format_short');

		$this->data['rows'] = array();

		foreach ($results as $result) {
			$this->data['rows'][] = array(
				'entry_id'    => $result['entry_id'],
				'line_date'   => $result['line_date'] ? date($date_format, strtotime($result['line_date'])) : '',
				'account'     => $result['account'],
				'description' => $result['description'],
				'concept'     => $result['concept'],
				'debit'       => $result['debit'] > 0 ? number_format($result['debit'], 2, ',', '.') : '',
				'credit'      => $result['credit'] > 0 ? number_format($result['credit'], 2, ',', '.') : ''
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all']        = $this->language->get('text_all');
		$this->data['text_by_number']  = $this->language->get('text_by_number');
		$this->data['text_by_date']    = $this->language->get('text_by_date');
		$this->data['text_by_amount']  = $this->language->get('text_by_amount');
		$this->data['text_by_concept'] = $this->language->get('text_by_concept');
		$this->data['text_by_account'] = $this->language->get('text_by_account');
		$this->data['text_unbalanced'] = $this->language->get('text_unbalanced');

		$this->data['column_entry']       = $this->language->get('column_entry');
		$this->data['column_account']     = $this->language->get('column_account');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_concept']     = $this->language->get('column_concept');
		$this->data['column_debit']       = $this->language->get('column_debit');
		$this->data['column_credit']      = $this->language->get('column_credit');

		$this->data['entry_from']   = $this->language->get('entry_from');
		$this->data['entry_to']     = $this->language->get('entry_to');
		$this->data['entry_amount'] = $this->language->get('entry_amount');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');

		$this->data['filter_mode']         = $filter_mode;
		$this->data['filter_number_start'] = $filter_number_start;
		$this->data['filter_number_end']   = $filter_number_end;
		$this->data['filter_date_start']   = $filter_date_start;
		$this->data['filter_date_end']     = $filter_date_end;
		$this->data['filter_amount']       = $filter_amount;
		$this->data['filter_concept']      = $filter_concept;
		$this->data['filter_account']      = $filter_account;

		$this->data['token'] = $this->session->data['token'];

		$pagination = new Pagination();
		$pagination->total = $entry_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('accounting/review', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'accounting/review_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->language->load('accounting/review');

		if (!$this->user->hasPermission('access', 'accounting/review')) {
			die('Permission denied');
		}

		$data = array(
			'filter_mode'         => isset($this->request->get['filter_mode']) ? $this->request->get['filter_mode'] : 'all',
			'filter_number_start' => isset($this->request->get['filter_number_start']) ? $this->request->get['filter_number_start'] : '',
			'filter_number_end'   => isset($this->request->get['filter_number_end']) ? $this->request->get['filter_number_end'] : '',
			'filter_date_start'   => $this->toSqlDate(isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : ''),
			'filter_date_end'     => $this->toSqlDate(isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : ''),
			'filter_amount'       => isset($this->request->get['filter_amount']) ? $this->request->get['filter_amount'] : '',
			'filter_concept'      => isset($this->request->get['filter_concept']) ? $this->request->get['filter_concept'] : '',
			'filter_account'      => isset($this->request->get['filter_account']) ? $this->request->get['filter_account'] : ''
		);

		$this->load->model('accounting/review');

		$results = $this->model_accounting_review->getEntries($data);

		$date_format = $this->language->get('date_format_short');

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Asiento";"Fecha";"Subcuenta";"Descripción";"Concepto";"Debe";"Haber"' . "\n";

		foreach ($results as $result) {
			$csv .= '"' . (int)$result['entry_id'] . '";';
			$csv .= '"' . ($result['line_date'] ? date($date_format, strtotime($result['line_date'])) : '') . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['account'])     . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['description']) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['concept'])     . '";';
			$csv .= '"' . number_format((float)$result['debit'], 2, ',', '.')    . '";';
			$csv .= '"' . number_format((float)$result['credit'], 2, ',', '.')   . '"' . "\n";
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="asientos_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
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

		foreach (array('filter_mode', 'filter_number_start', 'filter_number_end', 'filter_date_start', 'filter_date_end', 'filter_amount', 'filter_concept', 'filter_account', 'page') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}
