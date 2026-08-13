<?php
class ControllerReportPurchaseInvoice extends Controller {
	public function index() {
		$this->load->language('report/purchase_invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_supplier_id'])) {
			$filter_supplier_id = (int)$this->request->get['filter_supplier_id'];
		} else {
			$filter_supplier_id = 0;
		}

		if (isset($this->request->get['filter_invoice_status_id'])) {
			$filter_invoice_status_id = $this->request->get['filter_invoice_status_id'];
		} else {
			$filter_invoice_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_supplier_id'])) {
			$url .= '&filter_supplier_id=' . (int)$this->request->get['filter_supplier_id'];
		}

		if (isset($this->request->get['filter_invoice_status_id'])) {
			$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/purchase_invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->load->model('report/purchaselist');

		$this->data['invoices'] = array();

		$data = array(
			'filter_date_start'        => $filter_date_start,
			'filter_date_end'          => $filter_date_end,
			'filter_supplier_id'       => $filter_supplier_id,
			'filter_invoice_status_id' => $filter_invoice_status_id,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$invoice_total = $this->model_report_purchaselist->getTotalInvoices($data);
		$results = $this->model_report_purchaselist->getInvoices($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' 				=> $this->language->get('text_view'),
				'href' 				=> $this->url->link('purchase/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'], 'SSL'),
				'icon'				=> '<i class="far fa-eye"></i>'
			);

			$this->data['invoices'][] = array(
				'invoice_id'  => $result['invoice_id'],
				'customer'    => $result['customer'],
				'tax_id'      => $result['tax_id'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'email'       => $result['email'],
				'telephone'   => $result['telephone'],
				'tax'         => $this->currency->format($result['tax'], $result['currency_code'], $result['currency_value']),
				'total'       => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'status'      => $result['status'],
				'action'      => $action
			);
		}

 		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');

		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_invoiceid'] = $this->language->get('column_invoiceid');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_phone'] = $this->language->get('column_phone');
		$this->data['column_tax_id'] = $this->language->get('column_tax_id');
		$this->data['column_tax']    = $this->language->get('column_tax');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['entry_date_start']  = $this->language->get('entry_date_start');
		$this->data['entry_date_end']    = $this->language->get('entry_date_end');
		$this->data['entry_status']      = $this->language->get('entry_status');
		$this->data['entry_supplier']    = $this->language->get('entry_supplier');
		$this->data['text_all_suppliers'] = $this->language->get('text_all_suppliers');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/invoice_status');

		$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();

		$this->load->model('purchase/supplier');
		$this->data['suppliers'] = $this->model_purchase_supplier->getSuppliers(array());

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_supplier_id'])) {
			$url .= '&filter_supplier_id=' . (int)$this->request->get['filter_supplier_id'];
		}

		if (isset($this->request->get['filter_invoice_status_id'])) {
			$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $invoice_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/purchase_invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start']        = $filter_date_start;
		$this->data['filter_date_end']          = $filter_date_end;
		$this->data['filter_supplier_id']       = $filter_supplier_id;
		$this->data['filter_invoice_status_id'] = $filter_invoice_status_id;

		$this->template = 'report/purchase_invoice.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->load->language('report/purchase_invoice');

		if (!$this->user->hasPermission('access', 'report/purchase_invoice')) {
			die('Permission denied');
		}

		$filter_date_start        = isset($this->request->get['filter_date_start'])        ? $this->request->get['filter_date_start']             : '';
		$filter_date_end          = isset($this->request->get['filter_date_end'])          ? $this->request->get['filter_date_end']               : '';
		$filter_supplier_id       = isset($this->request->get['filter_supplier_id'])       ? (int)$this->request->get['filter_supplier_id']       : 0;
		$filter_invoice_status_id = isset($this->request->get['filter_invoice_status_id']) ? (int)$this->request->get['filter_invoice_status_id'] : 0;

		$this->load->model('report/purchaselist');

		$data = array(
			'filter_date_start'        => $filter_date_start,
			'filter_date_end'          => $filter_date_end,
			'filter_supplier_id'       => $filter_supplier_id,
			'filter_invoice_status_id' => $filter_invoice_status_id,
		);

		$results = $this->model_report_purchaselist->getInvoices($data);

		$date_format = $this->language->get('date_format_short');

		$csv  = "\xEF\xBB\xBF"; // UTF-8 BOM para Excel
		$csv .= '"Nº Factura";"Proveedor";"NIF/CIF";"Email";"Teléfono";"Fecha";"IVA";"Total";"Estado"' . "\n";

		foreach ($results as $result) {
			$csv .= '"' . (int)$result['invoice_id'] . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['customer'])        . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['tax_id'])          . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['email'])           . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['telephone'])       . '";';
			$csv .= '"' . date($date_format, strtotime($result['date_added']))        . '";';
			$csv .= '"' . number_format((float)$result['tax'], 2, $this->config->get('config_decimal_point') ?: ',', $this->config->get('config_thousand_point') ?: '.')          . '";';
			$csv .= '"' . number_format((float)$result['total'], 2, $this->config->get('config_decimal_point') ?: ',', $this->config->get('config_thousand_point') ?: '.')        . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['status'])          . '"' . "\n";
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="facturas_proveedores_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}
}
?>
