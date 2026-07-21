<?php

class ControllerSaleSalesStatus extends Controller {
	public function index() {
		$this->language->load('sale/sales_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/sales_status');
		$this->load->model('localisation/currency');

		$filter_customer      = isset($this->request->get['filter_customer'])      ? $this->request->get['filter_customer']      : '';
		$filter_reference     = isset($this->request->get['filter_reference'])     ? $this->request->get['filter_reference']     : '';
		$filter_currency_code = isset($this->request->get['filter_currency_code']) ? $this->request->get['filter_currency_code'] : '';

		$period_mode = isset($this->request->get['period_mode']) ? $this->request->get['period_mode'] : 'all';

		$filter_quarter      = isset($this->request->get['filter_quarter'])      ? (int)$this->request->get['filter_quarter']      : (int)ceil(date('n') / 3);
		$filter_quarter_year = isset($this->request->get['filter_quarter_year']) ? (int)$this->request->get['filter_quarter_year'] : (int)date('Y');
		$filter_month        = isset($this->request->get['filter_month'])        ? (int)$this->request->get['filter_month']        : (int)date('n');
		$filter_month_year   = isset($this->request->get['filter_month_year'])   ? (int)$this->request->get['filter_month_year']   : (int)date('Y');
		$filter_date_start   = isset($this->request->get['filter_date_start'])   ? $this->request->get['filter_date_start']        : '';
		$filter_date_end     = isset($this->request->get['filter_date_end'])     ? $this->request->get['filter_date_end']          : '';

		// Sin filtro aplicado todavia (primera carga), Pendientes/Cobradas empiezan marcadas
		if (isset($this->request->get['filter_applied'])) {
			$filter_pending = isset($this->request->get['filter_pending']) ? 1 : 0;
			$filter_paid    = isset($this->request->get['filter_paid'])    ? 1 : 0;
		} else {
			$filter_pending = 1;
			$filter_paid    = 1;
		}

		$date_start = '';
		$date_end   = '';

		if ($period_mode == 'quarter' && $filter_quarter >= 1 && $filter_quarter <= 4) {
			$start_month = (($filter_quarter - 1) * 3) + 1;
			$date_start  = sprintf('%04d-%02d-01', $filter_quarter_year, $start_month);
			$date_end    = date('Y-m-t', strtotime($date_start . ' +2 months'));
		} elseif ($period_mode == 'month' && $filter_month >= 1 && $filter_month <= 12) {
			$date_start = sprintf('%04d-%02d-01', $filter_month_year, $filter_month);
			$date_end   = date('Y-m-t', strtotime($date_start));
		} elseif ($period_mode == 'dates') {
			$date_start = $filter_date_start;
			$date_end   = $filter_date_end;
		}

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
			'href'      => $this->url->link('sale/sales_status', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$data = array(
			'filter_customer'      => $filter_customer,
			'filter_reference'     => $filter_reference,
			'filter_currency_code' => $filter_currency_code,
			'filter_date_start'    => $date_start,
			'filter_date_end'      => $date_end,
			'filter_pending'       => $filter_pending,
			'filter_paid'          => $filter_paid,
			'start'                => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                => $this->config->get('config_admin_limit')
		);

		$total = $this->model_sale_sales_status->getTotalSalesStatus($data);

		$results = $this->model_sale_sales_status->getSalesStatus($data);

		$date_format = $this->language->get('date_format_short');

		$this->data['rows'] = array();

		foreach ($results as $result) {
			$this->data['rows'][] = array(
				'customer'        => $result['customer'],
				'quote_id'        => $result['quote_id'],
				'quote_date'      => $result['quote_date'] ? date($date_format, strtotime($result['quote_date'])) : '',
				'quote_status'    => $result['quote_status'],
				'quote_href'      => $result['quote_id'] ? $this->url->link('sale/quote/update', 'token=' . $this->session->data['token'] . '&quote_id=' . $result['quote_id'], 'SSL') : '',
				'order_id'        => $result['order_id'],
				'order_date'      => $result['order_date'] ? date($date_format, strtotime($result['order_date'])) : '',
				'order_status'    => $result['order_status'],
				'order_href'      => $result['order_id'] ? $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL') : '',
				'delivery_id'     => $result['delivery_id'],
				'delivery_date'   => $result['delivery_date'] ? date($date_format, strtotime($result['delivery_date'])) : '',
				'delivery_status' => $result['delivery_status'],
				'delivery_href'   => $result['delivery_id'] ? $this->url->link('sale/delivery/update', 'token=' . $this->session->data['token'] . '&delivery_id=' . $result['delivery_id'], 'SSL') : '',
				'invoice_id'      => $result['invoice_id'],
				'invoice_date'    => $result['invoice_date'] ? date($date_format, strtotime($result['invoice_date'])) : '',
				'invoice_status'  => $result['invoice_status'],
				'invoice_paid'    => $result['invoice_status_id'] == 2,
				'invoice_href'    => $result['invoice_id'] ? $this->url->link('sale/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'], 'SSL') : ''
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_customer']  = $this->language->get('column_customer');
		$this->data['column_quote']     = $this->language->get('column_quote');
		$this->data['column_order']     = $this->language->get('column_order');
		$this->data['column_delivery']  = $this->language->get('column_delivery');
		$this->data['column_invoice']   = $this->language->get('column_invoice');
		$this->data['column_date']      = $this->language->get('column_date');
		$this->data['column_status']    = $this->language->get('column_status');

		$this->data['entry_customer']  = $this->language->get('entry_customer');
		$this->data['entry_currency']  = $this->language->get('entry_currency');
		$this->data['entry_reference'] = $this->language->get('entry_reference');
		$this->data['entry_period']    = $this->language->get('entry_period');
		$this->data['entry_invoices']  = $this->language->get('entry_invoices');
		$this->data['entry_pending']   = $this->language->get('entry_pending');
		$this->data['entry_paid']      = $this->language->get('entry_paid');

		$this->data['text_all']     = $this->language->get('text_all');
		$this->data['text_quarter'] = $this->language->get('text_quarter');
		$this->data['text_month']   = $this->language->get('text_month');
		$this->data['text_dates']   = $this->language->get('text_dates');

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['months'] = array();
		for ($m = 1; $m <= 12; $m++) {
			$this->data['months'][] = array(
				'value' => $m,
				'text'  => $this->language->get('text_month_' . $m)
			);
		}

		$current_year = (int)date('Y');
		$this->data['years'] = range($current_year - 5, $current_year + 1);

		$this->data['currencies'] = $this->model_sale_sales_status->getCurrencyCodes();

		$this->data['filter_customer']      = $filter_customer;
		$this->data['filter_reference']     = $filter_reference;
		$this->data['filter_currency_code'] = $filter_currency_code;
		$this->data['period_mode']          = $period_mode;
		$this->data['filter_quarter']       = $filter_quarter;
		$this->data['filter_quarter_year']  = $filter_quarter_year;
		$this->data['filter_month']         = $filter_month;
		$this->data['filter_month_year']    = $filter_month_year;
		$this->data['filter_date_start']    = $filter_date_start;
		$this->data['filter_date_end']      = $filter_date_end;
		$this->data['filter_pending']       = $filter_pending;
		$this->data['filter_paid']          = $filter_paid;

		$this->data['token'] = $this->session->data['token'];

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/sales_status', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/sales_status_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function buildUrl() {
		$url = '';

		foreach (array('filter_customer', 'filter_reference', 'filter_currency_code', 'period_mode', 'filter_quarter', 'filter_quarter_year', 'filter_month', 'filter_month_year', 'filter_date_start', 'filter_date_end', 'filter_pending', 'filter_paid', 'filter_applied') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}
}
