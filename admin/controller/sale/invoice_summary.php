<?php
/**
 * Ventas > Resumen Facturacion: pantalla con cuatro pestanas sobre las facturas de venta
 * (lista, resumen de importes por anyo y mes, detalle de articulos y resumen de unidades),
 * con filtros comunes y grafico de lineas por anyo (con acumulados). Solo lectura.
 */
class ControllerSaleInvoiceSummary extends Controller {
	public function index() {
		$this->data = array_merge($this->data, $this->language->load('sale/invoice_summary'));

		$this->document->setTitle($this->data['heading_title']);

		$this->load->model('sale/invoice_summary');
		$this->load->model('localisation/invoice_status');

		$get = $this->request->get;

		$filter_customer   = isset($get['filter_customer']) ? trim($get['filter_customer']) : '';
		$filter_date_start = isset($get['filter_date_start']) ? $get['filter_date_start'] : '';
		$filter_date_end   = isset($get['filter_date_end']) ? $get['filter_date_end'] : '';
		$filter_status     = isset($get['filter_status']) ? (int)$get['filter_status'] : 0;
		$amount            = (isset($get['amount']) && $get['amount'] == 'base') ? 'base' : 'total';
		$tab               = (isset($get['tab']) && in_array((int)$get['tab'], array(1, 2, 3, 4))) ? (int)$get['tab'] : 1;

		$page_invoices = max(1, isset($get['page_invoices']) ? (int)$get['page_invoices'] : 1);
		$page_lines    = max(1, isset($get['page_lines']) ? (int)$get['page_lines'] : 1);

		$limit = (int)$this->config->get('config_admin_limit');

		if ($limit < 1) {
			$limit = 20;
		}

		$data = array(
			'filter_customer'   => $filter_customer,
			'filter_date_start' => $this->toSqlDate($filter_date_start),
			'filter_date_end'   => $this->toSqlDate($filter_date_end),
			'filter_status'     => $filter_status,
			'amount'            => $amount
		);

		$token       = $this->session->data['token'];
		$date_format = $this->language->get('date_format_short');
		$is_es       = ($this->language->get('code') == 'es');

		// ---- Pestana 1: lista de facturas
		$total_invoices = $this->model_sale_invoice_summary->getTotalInvoices($data);

		$this->data['invoices'] = array();

		foreach ($this->model_sale_invoice_summary->getInvoices(array_merge($data, array('start' => ($page_invoices - 1) * $limit, 'limit' => $limit))) as $row) {
			// El descuento global se guarda como importe negativo; el % se deduce de la base antes de descontar.
			$discount = abs((float)$row['discount']);
			$gross    = (float)$row['sub_total'] + $discount;

			$this->data['invoices'][] = array(
				'number'       => $row['invoice_prefix'] . $row['invoice_no'],
				'date'         => date($date_format, strtotime($row['date_added'])),
				'nif'          => $row['nif'],
				'code'         => $this->customerCode($row),
				'customer'     => $row['customer'],
				'account'      => ((float)$row['contable_account'] > 0) ? sprintf('%.0f', $row['contable_account']) : '',
				'status'       => $row['status'],
				'paid'         => ($row['invoice_status_id'] == 2),
				'total'        => $this->money($row['total'], $is_es),
				'discount_pct' => $this->money($gross > 0 ? $discount * 100 / $gross : 0, $is_es),
				'discount'     => $this->money($discount, $is_es),
				'sub_total'    => $this->money($row['sub_total'], $is_es),
				'tax'          => $this->money($row['tax'], $is_es),
				'negative'     => ((float)$row['total'] < 0),
				'href'         => $this->url->link('sale/invoice/info', 'token=' . $token . '&invoice_id=' . $row['invoice_id'], 'SSL')
			);
		}

		$totals = $this->model_sale_invoice_summary->getInvoiceTotals($data);

		$this->data['sum_total']     = $this->money($totals['total'], $is_es);
		$this->data['sum_discount']  = $this->money(abs((float)$totals['discount']), $is_es);
		$this->data['sum_sub_total'] = $this->money($totals['sub_total'], $is_es);
		$this->data['sum_tax']       = $this->money($totals['tax'], $is_es);

		// ---- Pestana 3: detalle de articulos
		$total_lines = $this->model_sale_invoice_summary->getTotalLines($data);

		$this->data['lines'] = array();

		foreach ($this->model_sale_invoice_summary->getLines(array_merge($data, array('start' => ($page_lines - 1) * $limit, 'limit' => $limit))) as $row) {
			$deliveries = array();
			$orders     = array();

			foreach (array_filter(explode(',', (string)$row['deliveries'])) as $id) {
				$deliveries[] = array('id' => (int)$id, 'href' => $this->url->link('sale/delivery/update', 'token=' . $token . '&delivery_id=' . (int)$id, 'SSL'));
			}

			foreach (array_filter(explode(',', (string)$row['orders'])) as $id) {
				$orders[] = array('id' => (int)$id, 'href' => $this->url->link('sale/order/update', 'token=' . $token . '&order_id=' . (int)$id, 'SSL'));
			}

			$this->data['lines'][] = array(
				'date'       => date($date_format, strtotime($row['date_added'])),
				'number'     => $row['invoice_prefix'] . $row['invoice_no'],
				'code'       => $this->customerCode($row),
				'customer'   => $row['customer'],
				'name'       => $row['name'],
				'model'      => $row['model'],
				'quantity'   => (int)$row['quantity'],
				'price'      => $this->money($row['price'], $is_es),
				'discount'   => $this->money($row['discount'], $is_es),
				'total'      => $this->money($row['total'], $is_es),
				'deliveries' => $deliveries,
				'orders'     => $orders,
				'negative'   => ((float)$row['total'] < 0),
				'href'       => $this->url->link('sale/invoice/info', 'token=' . $token . '&invoice_id=' . $row['invoice_id'], 'SSL')
			);
		}

		$line_totals = $this->model_sale_invoice_summary->getLinesTotals($data);

		$this->data['sum_quantity']    = $this->money($line_totals['quantity'], $is_es, 0);
		$this->data['sum_lines_total'] = $this->money($line_totals['total'], $is_es);

		// ---- Pestanas 2 y 4: resumenes por anyo y mes
		$amounts = $this->model_sale_invoice_summary->getMonthlyAmounts($data);
		$units   = $this->model_sale_invoice_summary->getMonthlyUnits($data);

		$years = array_unique(array_merge(array_keys($amounts), array_keys($units)));

		rsort($years);

		$this->data['amount_table'] = $this->buildMatrix($amounts, $years, $is_es, 2);
		$this->data['units_table']  = $this->buildMatrix($units, $years, $is_es, 0);

		$this->data['amount_total'] = $this->money($this->data['amount_table']['grand'], $is_es, 0);
		$this->data['units_total']  = $this->money($this->data['units_table']['grand'], $is_es, 0);

		// Datos para el grafico (JSON): anyo => 12 valores.
		$chart = array('years' => array_values($years), 'amount' => array(), 'units' => array(), 'now_year' => (int)date('Y'), 'now_month' => (int)date('n'));

		foreach ($years as $y) {
			$chart['amount'][$y] = $this->twelve($amounts, $y);
			$chart['units'][$y]  = $this->twelve($units, $y);
		}

		$this->data['chart_json'] = json_encode($chart);

		$months = array();
		$short  = array();

		for ($m = 1; $m <= 12; $m++) {
			$months[] = html_entity_decode($this->data['text_month_' . $m], ENT_QUOTES, 'UTF-8');
			$short[]  = html_entity_decode($this->data['text_short_' . $m], ENT_QUOTES, 'UTF-8');
		}

		$this->data['months_json']  = json_encode($months);
		$this->data['short_json']   = json_encode($short);
		$this->data['locale']       = $is_es ? 'es-ES' : 'en-GB';
		$this->data['chart_amount_title'] = json_encode(html_entity_decode($this->data['text_chart_amounts'], ENT_QUOTES, 'UTF-8'));
		$this->data['chart_units_title']  = json_encode(html_entity_decode($this->data['text_chart_units'], ENT_QUOTES, 'UTF-8'));
		$this->data['text_total_js']      = json_encode(html_entity_decode($this->data['text_total'], ENT_QUOTES, 'UTF-8'));

		// ---- Contadores de pestanas y paginacion
		$this->data['count_invoices'] = $total_invoices;
		$this->data['count_amounts']  = count($years);
		$this->data['count_lines']    = $total_lines;
		$this->data['count_units']    = count($years);

		$filters = '&filter_customer=' . urlencode($filter_customer) . '&filter_date_start=' . urlencode($filter_date_start) . '&filter_date_end=' . urlencode($filter_date_end) . '&filter_status=' . $filter_status . '&amount=' . $amount;

		$pagination        = new Pagination();
		$pagination->total = $total_invoices;
		$pagination->page  = $page_invoices;
		$pagination->limit = $limit;
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('sale/invoice_summary', 'token=' . $token . $filters . '&tab=1&page_lines=' . $page_lines . '&page_invoices={page}', 'SSL');

		$this->data['pagination_invoices'] = $pagination->render();

		$pagination        = new Pagination();
		$pagination->total = $total_lines;
		$pagination->page  = $page_lines;
		$pagination->limit = $limit;
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('sale/invoice_summary', 'token=' . $token . $filters . '&tab=3&page_invoices=' . $page_invoices . '&page_lines={page}', 'SSL');

		$this->data['pagination_lines'] = $pagination->render();

		// ---- Filtros
		$this->data['filter_customer']   = $filter_customer;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end']   = $filter_date_end;
		$this->data['filter_status']     = $filter_status;
		$this->data['amount']            = $amount;
		$this->data['tab']               = $tab;
		$this->data['statuses']          = $this->model_localisation_invoice_status->getInvoiceStatuses();
		$this->data['token']             = $token;

		$this->data['breadcrumbs'] = array(
			array('text' => $this->data['text_home'], 'href' => $this->url->link('common/home', 'token=' . $token, 'SSL'), 'separator' => false),
			array('text' => $this->data['heading_title'], 'href' => $this->url->link('sale/invoice_summary', 'token=' . $token, 'SSL'), 'separator' => ' :: ')
		);

		$this->data['success']       = '';
		$this->data['error_warning'] = '';

		$this->template = 'sale/invoice_summary.tpl';
		$this->children = array('common/header', 'common/footer');

		$this->response->setOutput($this->render());
	}

	// Filas = anyos (mas reciente primero) x 12 meses, con total por anyo, total por mes y total general.
	private function buildMatrix($pivot, $years, $is_es, $decimals) {
		$rows         = array();
		$month_totals = array_fill(1, 12, 0);
		$grand        = 0;

		foreach ($years as $y) {
			$cells = array();
			$sum   = 0;

			for ($m = 1; $m <= 12; $m++) {
				$v      = isset($pivot[$y][$m]) ? $pivot[$y][$m] : 0;
				$sum   += $v;

				$month_totals[$m] += $v;

				$cells[] = array('text' => $this->money($v, $is_es, $decimals), 'negative' => ($v < 0), 'zero' => ($v == 0));
			}

			$grand += $sum;

			$rows[] = array('year' => $y, 'total' => $this->money($sum, $is_es, $decimals), 'negative' => ($sum < 0), 'cells' => $cells);
		}

		$totals = array();

		foreach ($month_totals as $v) {
			$totals[] = array('text' => $this->money($v, $is_es, $decimals), 'negative' => ($v < 0));
		}

		return array('rows' => $rows, 'totals' => $totals, 'grand' => $grand, 'grand_text' => $this->money($grand, $is_es, $decimals));
	}

	// Codigo de cliente: el de FLASH (cod_flash) si lo tiene, si no el id; siempre a 5 cifras.
	private function customerCode($row) {
		$code = !empty($row['cod_flash']) ? (int)$row['cod_flash'] : (int)$row['customer_id'];

		return $code ? sprintf('%05d', $code) : '';
	}

	private function twelve($pivot, $year) {
		$out = array();

		for ($m = 1; $m <= 12; $m++) {
			$out[] = isset($pivot[$year][$m]) ? round($pivot[$year][$m], 2) : 0;
		}

		return $out;
	}

	private function money($value, $is_es, $decimals = 2) {
		return $is_es ? number_format((float)$value, $decimals, ',', '.') : number_format((float)$value, $decimals, '.', ',');
	}

	// El datepicker (clase .date de common.js) envia DD-MM-YYYY; el modelo compara en YYYY-MM-DD.
	private function toSqlDate($date) {
		if (empty($date) || !preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $match)) {
			return '';
		}

		return $match[3] . '-' . $match[2] . '-' . $match[1];
	}
}
