<?php

class ControllerPurchasePurchaseOrder extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purchase/purchase_order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase_order');

		$this->getList();
	}

	public function insert() {
		$this->load->language('purchase/purchase_order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase_order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$purchase_order_id = $this->model_purchase_purchase_order->addPurchaseOrder($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/purchase_order')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function update() {
		$this->load->language('purchase/purchase_order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase_order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_purchase_order->editPurchaseOrder($this->request->get['purchase_order_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/purchase_order')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function delete() {
		$this->load->language('purchase/purchase_order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/purchase_order');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $purchase_order_id) {
				$this->model_purchase_purchase_order->deletePurchaseOrder($purchase_order_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$filter_purchase_order_id = isset($this->request->get['filter_purchase_order_id']) ? $this->request->get['filter_purchase_order_id'] : null;
		$filter_supplier = isset($this->request->get['filter_supplier']) ? $this->request->get['filter_supplier'] : null;
		$filter_purchase_order_status_id = isset($this->request->get['filter_purchase_order_status_id']) ? $this->request->get['filter_purchase_order_status_id'] : null;
		$filter_total = isset($this->request->get['filter_total']) ? $this->request->get['filter_total'] : null;
		$filter_date_added = isset($this->request->get['filter_date_added']) ? $this->request->get['filter_date_added'] : null;

		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'po.purchase_order_id';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'DESC';
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
			'href'      => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('purchase/purchase_order/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('purchase/purchase_order/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['purchase_orders'] = array();

		$data = array(
			'filter_purchase_order_id'        => $filter_purchase_order_id,
			'filter_supplier'                 => $filter_supplier,
			'filter_purchase_order_status_id' => $filter_purchase_order_status_id,
			'filter_total'                    => $filter_total,
			'filter_date_added'               => $filter_date_added,
			'sort'                            => $sort,
			'order'                           => $order,
			'start'                           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                           => $this->config->get('config_admin_limit')
		);

		$purchase_order_total = $this->model_purchase_purchase_order->getTotalPurchaseOrders($data);

		$results = $this->model_purchase_purchase_order->getPurchaseOrders($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'href'  => $this->url->link('purchase/purchase_order/info', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . $result['purchase_order_id'] . $url, 'SSL'),
				'icon'  => 'far fa-eye',
				'color' => 'info'
			);

			$action[] = array(
				'href'  => $this->url->link('purchase/purchase_order/update', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . $result['purchase_order_id'] . $url, 'SSL'),
				'icon'  => 'fas fa-edit',
				'color' => 'default'
			);

			$this->data['purchase_orders'][] = array(
				'purchase_order_id' => $result['purchase_order_id'],
				'po_number'         => $result['po_number'],
				'supplier'          => $result['supplier_company'],
				'status'            => $result['status'],
				'total'             => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified'     => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'          => isset($this->request->post['selected']) && in_array($result['purchase_order_id'], $this->request->post['selected']),
				'action'            => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['column_purchase_order_id'] = $this->language->get('column_purchase_order_id');
		$this->data['column_supplier'] = $this->language->get('column_supplier');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['sort_purchase_order_id'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . '&sort=po.purchase_order_id' . $url, 'SSL');
		$this->data['sort_supplier'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . '&sort=supplier_company' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . '&sort=po.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . '&sort=po.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . '&sort=po.date_modified' . $url, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $purchase_order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_purchase_order_id'] = $filter_purchase_order_id;
		$this->data['filter_supplier'] = $filter_supplier;
		$this->data['filter_purchase_order_status_id'] = $filter_purchase_order_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;

		$this->data['purchase_order_statuses'] = $this->model_purchase_purchase_order->getPurchaseOrderStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'purchase/purchase_order_list.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_product'] = $this->language->get('text_product');

		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_supplier'] = $this->language->get('entry_supplier');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_purchase_order_status'] = $this->language->get('entry_purchase_order_status');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_supplier'] = $this->language->get('tab_supplier');
		$this->data['tab_product'] = $this->language->get('tab_product');

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_supplier'] = isset($this->error['supplier_id']) ? $this->error['supplier_id'] : '';

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['purchase_order_id'])) {
			$this->data['action'] = $this->url->link('purchase/purchase_order/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/purchase_order/update', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . $this->request->get['purchase_order_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['purchase_order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$purchase_order_info = $this->model_purchase_purchase_order->getPurchaseOrder($this->request->get['purchase_order_id']);
		} else {
			$purchase_order_info = array();
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['purchase_order_id'] = isset($this->request->get['purchase_order_id']) ? $this->request->get['purchase_order_id'] : 0;

		if (isset($this->request->post['po_number'])) {
			$this->data['po_number'] = $this->request->post['po_number'];
		} elseif (!empty($purchase_order_info)) {
			$this->data['po_number'] = $purchase_order_info['po_number'];
		} else {
			$this->data['po_number'] = 'PO-' . date('Ymd') . '-' . str_pad((string)(mt_rand(1, 999)), 3, '0', STR_PAD_LEFT);
		}

		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($purchase_order_info)) {
			$this->data['store_id'] = $purchase_order_info['store_id'];
		} else {
			$this->data['store_id'] = 0;
		}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['supplier_id'])) {
			$this->data['supplier_id'] = $this->request->post['supplier_id'];
		} elseif (!empty($purchase_order_info)) {
			$this->data['supplier_id'] = $purchase_order_info['supplier_id'];
		} else {
			$this->data['supplier_id'] = 0;
		}

		if (isset($this->request->post['supplier'])) {
			$this->data['supplier'] = $this->request->post['supplier'];
		} elseif (!empty($purchase_order_info)) {
			$this->data['supplier'] = $purchase_order_info['supplier_company'];
		} else {
			$this->data['supplier'] = '';
		}

		foreach (array('shipping_method', 'shipping_code', 'payment_method', 'payment_code') as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($purchase_order_info)) {
				$this->data[$field] = $purchase_order_info[$field];
			} else {
				$this->data[$field] = '';
			}
		}

		$this->load->model('localisation/shipping');
		$this->load->model('localisation/payment');

		$this->data['shippings'] = $this->model_localisation_shipping->getShippings();
		$this->data['payments'] = $this->model_localisation_payment->getPayments();

		if (isset($this->request->post['purchase_order_status_id'])) {
			$this->data['purchase_order_status_id'] = $this->request->post['purchase_order_status_id'];
		} elseif (!empty($purchase_order_info)) {
			$this->data['purchase_order_status_id'] = $purchase_order_info['purchase_order_status_id'];
		} else {
			$this->data['purchase_order_status_id'] = 1;
		}

		$this->data['purchase_order_statuses'] = $this->model_purchase_purchase_order->getPurchaseOrderStatuses();

		if (isset($this->request->post['purchase_order_product'])) {
			$purchase_order_products = $this->request->post['purchase_order_product'];
		} elseif (isset($this->request->get['purchase_order_id'])) {
			$purchase_order_products = $this->model_purchase_purchase_order->getPurchaseOrderProducts($this->request->get['purchase_order_id']);
		} else {
			$purchase_order_products = array();
		}

		$this->load->model('catalog/product');

		$this->data['purchase_order_products'] = array();

		foreach ($purchase_order_products as $purchase_order_product) {
			$this->data['purchase_order_products'][] = array(
				'purchase_order_product_id' => isset($purchase_order_product['purchase_order_product_id']) ? $purchase_order_product['purchase_order_product_id'] : 0,
				'product_id'                => $purchase_order_product['product_id'],
				'name'                      => $purchase_order_product['name'],
				'model'                     => $purchase_order_product['model'],
				'quantity'                  => $purchase_order_product['quantity'],
				'price'                     => $this->currency->format($purchase_order_product['price']),
				'total'                     => $this->currency->format($purchase_order_product['total']),
				'tax'                       => $purchase_order_product['tax']
			);
		}

		if (isset($this->request->post['purchase_order_total'])) {
			$this->data['purchase_order_totals'] = $this->request->post['purchase_order_total'];
		} elseif (isset($this->request->get['purchase_order_id'])) {
			$this->data['purchase_order_totals'] = $this->model_purchase_purchase_order->getPurchaseOrderTotals($this->request->get['purchase_order_id']);
		} else {
			$this->data['purchase_order_totals'] = array();
		}

		$this->template = 'purchase/purchase_order_form.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'purchase/purchase_order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['supplier_id'])) {
			$this->error['supplier_id'] = $this->language->get('error_supplier');
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'purchase/purchase_order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function info() {
		$this->load->model('purchase/purchase_order');

		$purchase_order_id = isset($this->request->get['purchase_order_id']) ? $this->request->get['purchase_order_id'] : 0;

		$purchase_order_info = $this->model_purchase_purchase_order->getPurchaseOrder($purchase_order_id);

		if (!$purchase_order_info) {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_not_found'] = $this->language->get('text_not_found');

			$this->data['breadcrumbs'] = array(
				array(
					'text'      => $this->language->get('text_home'),
					'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
					'separator' => false
				),
				array(
					'text'      => $this->language->get('heading_title'),
					'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
					'separator' => ' :: '
				)
			);

			$this->template = 'error/not_found.tpl';
			$this->children = array('common/header', 'common/footer');

			$this->response->setOutput($this->render());

			return;
		}

		$this->load->language('purchase/purchase_order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		foreach (array('text_purchase_order_id', 'text_store', 'text_supplier', 'text_email', 'text_telephone', 'text_shipping_method', 'text_payment_method', 'text_total', 'text_purchase_order_status', 'text_date_added', 'text_date_modified') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		foreach (array('column_product', 'column_model', 'column_quantity', 'column_price', 'column_total') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['entry_purchase_order_status'] = $this->language->get('entry_purchase_order_status');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		$this->data['entry_comment'] = $this->language->get('entry_comment');

		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_history'] = $this->language->get('button_add_history');

		$this->data['tab_supplier'] = $this->language->get('tab_supplier');
		$this->data['tab_product'] = $this->language->get('tab_product');
		$this->data['tab_history'] = $this->language->get('tab_history');

		$this->data['token'] = $this->session->data['token'];

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['cancel'] = $this->url->link('purchase/purchase_order', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['purchase_order_id'] = $purchase_order_info['purchase_order_id'];
		$this->data['po_number'] = $purchase_order_info['po_number'];
		$this->data['store_name'] = $purchase_order_info['store_name'];
		$this->data['supplier_company'] = $purchase_order_info['supplier_company'];
		$this->data['supplier_name'] = trim($purchase_order_info['supplier_firstname'] . ' ' . $purchase_order_info['supplier_lastname']);
		$this->data['supplier_email'] = $purchase_order_info['supplier_email'];
		$this->data['supplier_telephone'] = $purchase_order_info['supplier_telephone'];
		$this->data['supplier'] = $purchase_order_info['supplier_id'] ? $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $purchase_order_info['supplier_id'], 'SSL') : '';
		$this->data['shipping_method'] = $purchase_order_info['shipping_method'];
		$this->data['payment_method'] = $purchase_order_info['payment_method'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($purchase_order_info['date_added']));
		$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($purchase_order_info['date_modified']));
		$this->data['total'] = $this->currency->format($purchase_order_info['total'], $purchase_order_info['currency_code'], $purchase_order_info['currency_value']);
		$this->data['purchase_order_status'] = $purchase_order_info['status'];
		$this->data['purchase_order_status_id'] = $purchase_order_info['purchase_order_status_id'];
		$this->data['purchase_order_statuses'] = $this->model_purchase_purchase_order->getPurchaseOrderStatuses();

		$this->data['products'] = array();

		$products = $this->model_purchase_purchase_order->getPurchaseOrderProducts($purchase_order_info['purchase_order_id']);

		foreach ($products as $product) {
			$this->data['products'][] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'quantity'   => $product['quantity'],
				'price'      => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $purchase_order_info['currency_code'], $purchase_order_info['currency_value']),
				'total'      => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $purchase_order_info['currency_code'], $purchase_order_info['currency_value']),
				'href'       => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
			);
		}

		$this->data['totals'] = $this->model_purchase_purchase_order->getPurchaseOrderTotals($purchase_order_info['purchase_order_id']);

		$this->template = 'purchase/purchase_order_info.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function history() {
		$this->load->language('purchase/purchase_order');

		$this->data['error'] = '';
		$this->data['success'] = '';

		$this->load->model('purchase/purchase_order');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'purchase/purchase_order')) {
				$this->data['error'] = $this->language->get('error_permission');
			}

			if (!$this->data['error']) {
				$this->model_purchase_purchase_order->addPurchaseOrderHistory($this->request->get['purchase_order_id'], $this->request->post);

				$this->data['success'] = $this->language->get('text_success');
			}
		}

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_notify'] = $this->language->get('column_notify');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$this->data['histories'] = array();

		$results = $this->model_purchase_purchase_order->getPurchaseOrderHistories($this->request->get['purchase_order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_purchase_purchase_order->getTotalPurchaseOrderHistories($this->request->get['purchase_order_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('purchase/purchase_order/history', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . $this->request->get['purchase_order_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'purchase/purchase_order_history.tpl';

		$this->response->setOutput($this->render());
	}

	public function checkOrder() {
		$this->load->language('purchase/purchase_order');

		$json = array();

		if ($this->user->hasPermission('modify', 'purchase/purchase_order')) {
			unset($this->session->data['cart']);

			$this->load->model('catalog/product');

			$this->session->data['cart'] = array();

			if (isset($this->request->post['purchase_order_product'])) {
				foreach ($this->request->post['purchase_order_product'] as $purchase_order_product) {
					$product_info = $this->model_catalog_product->getProduct($purchase_order_product['product_id']);

					if ($product_info) {
						$this->session->data['cart'][] = array(
							'product_id'    => $product_info['product_id'],
							'name'          => $product_info['name'],
							'model'         => $product_info['model'],
							'quantity'      => $purchase_order_product['quantity'],
							'price'         => $product_info['price'],
							'tax_class_id'  => $product_info['tax_class_id'],
							'total'         => ($product_info['price'] * $purchase_order_product['quantity'])
						);
					}
				}
			}

			if (!empty($this->request->post['product_id'])) {
				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					$quantity = isset($this->request->post['quantity']) ? $this->request->post['quantity'] : 1;

					$this->session->data['cart'][] = array(
						'product_id'   => $this->request->post['product_id'],
						'name'         => $product_info['name'],
						'model'        => $product_info['model'],
						'quantity'     => $quantity,
						'price'        => $product_info['price'],
						'tax_class_id' => $product_info['tax_class_id'],
						'total'        => ($product_info['price'] * $quantity)
					);
				} else {
					$json['error']['product']['not_found'] = $this->language->get('error_action');
				}
			}

			$json['purchase_order_product'] = array();

			$products = $this->session->data['cart'];

			foreach ($products as $product) {
				$json['purchase_order_product'][] = array(
					'product_id'   => $product['product_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'quantity'     => $product['quantity'],
					'price'        => $this->currency->format($product['price']),
					'tax_class_id' => $product['tax_class_id'],
					'total'        => $this->currency->format($product['total'])
				);
			}

			$json['purchase_order_total'] = array();

			$total = 0;
			$taxes = $this->getTaxes($products);

			$this->load->model('total/sub_total');
			$this->load->model('total/tax');
			$this->load->model('total/total');

			$this->model_total_sub_total->getTotal($json['purchase_order_total'], $total, $taxes);
			$this->model_total_tax->getTotal($json['purchase_order_total'], $total, $taxes);
			$this->model_total_total->getTotal($json['purchase_order_total'], $total, $taxes);

			if (!isset($json['error'])) {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getTaxes($data) {
		$this->load->model('catalog/product');

		$tax_data = array();

		foreach ($data as $product) {
			if ($product['tax_class_id'] != 0) {
				$tax_rates = $this->model_catalog_product->getProductRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	private function buildFilterUrl() {
		$url = '';

		foreach (array('filter_purchase_order_id', 'filter_supplier', 'filter_purchase_order_status_id', 'filter_total', 'filter_date_added', 'sort', 'order', 'page') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . (($key == 'filter_supplier') ? urlencode(html_entity_decode($this->request->get[$key], ENT_QUOTES, 'UTF-8')) : $this->request->get[$key]);
			}
		}

		return $url;
	}
}
?>
