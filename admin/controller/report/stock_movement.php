<?php

class ControllerReportStockMovement extends Controller {
	public function index() {
		$this->load->language('report/stock_movement');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/stock_movement');

		$filter_product        = isset($this->request->get['filter_product']) ? $this->request->get['filter_product'] : '';
		$filter_movement_type  = isset($this->request->get['filter_movement_type']) ? $this->request->get['filter_movement_type'] : '';
		$filter_document_type  = isset($this->request->get['filter_document_type']) ? $this->request->get['filter_document_type'] : '';
		$filter_party          = isset($this->request->get['filter_party']) ? $this->request->get['filter_party'] : '';
		$filter_date_start     = isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '';
		$filter_date_end       = isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '';

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$url = $this->buildFilterUrl();

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/stock_movement', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$data = array(
			'filter_product'       => $filter_product,
			'filter_movement_type' => $filter_movement_type,
			'filter_document_type' => $filter_document_type,
			'filter_party'         => $filter_party,
			'filter_date_start'    => $filter_date_start,
			'filter_date_end'      => $filter_date_end,
			'start'                => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                => $this->config->get('config_admin_limit')
		);

		$movement_total = $this->model_report_stock_movement->getTotalMovements($data);
		$results = $this->model_report_stock_movement->getMovements($data);

		$this->data['movements'] = array();

		foreach ($results as $result) {
			$this->data['movements'][] = $this->formatMovement($result);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_in'] = $this->language->get('text_in');
		$this->data['text_out'] = $this->language->get('text_out');
		$this->data['text_sale_delivery'] = $this->language->get('text_sale_delivery');
		$this->data['text_purchase_reception'] = $this->language->get('text_purchase_reception');

		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_movement_type'] = $this->language->get('column_movement_type');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_balance_after'] = $this->language->get('column_balance_after');
		$this->data['column_document'] = $this->language->get('column_document');
		$this->data['column_party'] = $this->language->get('column_party');
		$this->data['column_user'] = $this->language->get('column_user');

		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_movement_type'] = $this->language->get('entry_movement_type');
		$this->data['entry_document_type'] = $this->language->get('entry_document_type');
		$this->data['entry_party'] = $this->language->get('entry_party');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');

		$this->data['token'] = $this->session->data['token'];

		$this->data['export'] = $this->url->link('report/stock_movement/export', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $movement_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/stock_movement', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_product'] = $filter_product;
		$this->data['filter_movement_type'] = $filter_movement_type;
		$this->data['filter_document_type'] = $filter_document_type;
		$this->data['filter_party'] = $filter_party;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'report/stock_movement.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function export() {
		$this->load->language('report/stock_movement');

		if (!$this->user->hasPermission('access', 'report/stock_movement')) {
			die('Permission denied');
		}

		$this->load->model('report/stock_movement');

		$data = array(
			'filter_product'       => isset($this->request->get['filter_product']) ? $this->request->get['filter_product'] : '',
			'filter_movement_type' => isset($this->request->get['filter_movement_type']) ? $this->request->get['filter_movement_type'] : '',
			'filter_document_type' => isset($this->request->get['filter_document_type']) ? $this->request->get['filter_document_type'] : '',
			'filter_party'         => isset($this->request->get['filter_party']) ? $this->request->get['filter_party'] : '',
			'filter_date_start'    => isset($this->request->get['filter_date_start']) ? $this->request->get['filter_date_start'] : '',
			'filter_date_end'      => isset($this->request->get['filter_date_end']) ? $this->request->get['filter_date_end'] : '',
		);

		$results = $this->model_report_stock_movement->getMovements($data);

		$date_format = $this->language->get('date_format_short');

		$csv  = "\xEF\xBB\xBF";
		$csv .= '"Fecha";"Producto";"Modelo";"Tipo";"Cantidad";"Stock resultante";"Documento";"Cliente/Proveedor";"Usuario"' . "\n";

		foreach ($results as $result) {
			$formatted = $this->formatMovement($result);

			$csv .= '"' . date($date_format . ' H:i', strtotime($result['date_added'])) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['product_name']) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['model']) . '";';
			$csv .= '"' . ($result['movement_type'] == 'in' ? $this->language->get('text_in') : $this->language->get('text_out')) . '";';
			$csv .= '"' . (int)$result['quantity'] . '";';
			$csv .= '"' . (int)$result['balance_after'] . '";';
			$csv .= '"' . str_replace('"', '""', $formatted['document_label']) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['party_name']) . '";';
			$csv .= '"' . str_replace('"', '""', (string)$result['username']) . '"' . "\n";
		}

		ob_start();
		ob_end_clean();
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="kardex_' . date('Y-m-d') . '.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo $csv;
		exit;
	}

	private function formatMovement($result) {
		if ($result['document_type'] == 'sale_delivery') {
			$document_label = $this->language->get('text_sale_delivery') . ' Nº ' . $result['document_id'];
			$document_href  = $this->url->link('sale/delivery/info', 'token=' . $this->session->data['token'] . '&delivery_id=' . $result['document_id'], 'SSL');
		} elseif ($result['document_type'] == 'purchase_reception') {
			$document_label = $this->language->get('text_purchase_reception') . ' Nº ' . $result['document_id'];
			$document_href  = $this->url->link('purchase/reception/info', 'token=' . $this->session->data['token'] . '&reception_id=' . $result['document_id'], 'SSL');
		} else {
			$document_label = $result['document_type'] . ' Nº ' . $result['document_id'];
			$document_href  = '';
		}

		return array(
			'product_name'    => $result['product_name'],
			'model'           => $result['model'],
			'movement_type'   => $result['movement_type'],
			'movement_label'  => ($result['movement_type'] == 'in') ? $this->language->get('text_in') : $this->language->get('text_out'),
			'quantity'        => (int)$result['quantity'],
			'balance_after'   => (int)$result['balance_after'],
			'document_type'   => $result['document_type'],
			'document_label'  => $document_label,
			'document_href'   => $document_href,
			'party_name'      => $result['party_name'],
			'username'        => $result['username'],
			'date_added'      => date($this->language->get('date_format_short') . ' H:i', strtotime($result['date_added'])),
			'product_href'    => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'),
		);
	}

	private function buildFilterUrl() {
		$url = '';

		foreach (array('filter_product', 'filter_movement_type', 'filter_document_type', 'filter_party', 'filter_date_start', 'filter_date_end', 'page') as $key) {
			if (isset($this->request->get[$key]) && $this->request->get[$key] !== '') {
				$url .= '&' . $key . '=' . urlencode(html_entity_decode($this->request->get[$key], ENT_QUOTES, 'UTF-8'));
			}
		}

		return $url;
	}
}
?>
