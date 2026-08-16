<?php

class ControllerPurchaseReception extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purchase/reception');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/reception');

		$this->getList();
	}

	public function insert() {
		$this->load->language('purchase/reception');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/reception');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$reception_id = $this->model_purchase_reception->addReception($this->request->post);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'create',
				'document_type' => 'purchase_reception',
				'document_id'   => (int)$reception_id,
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/reception')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function update() {
		$this->load->language('purchase/reception');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/reception');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$reception_before = $this->model_purchase_reception->getReception($this->request->get['reception_id']);
			$products_before  = $this->model_purchase_reception->getReceptionProducts($this->request->get['reception_id']);

			$this->model_purchase_reception->editReception($this->request->get['reception_id'], $this->request->post);

			$diff         = $this->diffFields($reception_before, $this->request->post);
			$product_diff = $this->diffReceptionProducts($products_before, isset($this->request->post['reception_product']) ? $this->request->post['reception_product'] : array());

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'edit',
				'document_type' => 'purchase_reception',
				'document_id'   => (int)$this->request->get['reception_id'],
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				'original'      => array_merge($diff['original'], $product_diff['original']),
				'cambiado'      => array_merge($diff['changed'], $product_diff['changed']),
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/reception')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function delete() {
		$this->load->language('purchase/reception');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/reception');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->load->model('tool/user_logs');

			foreach ($this->request->post['selected'] as $reception_id) {
				$this->model_purchase_reception->deleteReception($reception_id);

				$this->model_tool_user_logs->addLog(array(
					'user_id'       => $this->user->getId(),
					'username'      => $this->user->getUserName(),
					'action'        => 'delete',
					'document_type' => 'purchase_reception',
					'document_id'   => (int)$reception_id,
					'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				));
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function convertToInvoice() {
		$this->load->language('purchase/reception');

		$this->load->model('purchase/reception');
		$this->load->model('purchase/invoice');
		$this->load->model('purchase/supplier');
		$this->load->model('tool/user_logs');

		if (isset($this->request->post['selected']) && $this->validateConvert()) {
			foreach ($this->request->post['selected'] as $reception_id) {
				$reception_info = $this->model_purchase_reception->getReception($reception_id);

				if ($reception_info && !$reception_info['invoice_no']) {
					$supplier_info = $this->model_purchase_supplier->getSupplier($reception_info['supplier_id']);

					$invoice_products = array();

					foreach ($this->model_purchase_reception->getReceptionProducts($reception_id) as $reception_product) {
						$invoice_products[] = array(
							'invoice_product_id' => 0,
							'product_id'         => $reception_product['product_id'],
							'name'               => $reception_product['name'],
							'model'              => $reception_product['model'],
							'quantity'           => $reception_product['quantity'],
							'price'              => $reception_product['price'],
							'discount'           => $reception_product['discount'],
							'total'              => $reception_product['total'],
							'tax'                => $reception_product['tax']
						);
					}

					$data = array(
						'store_id'            => $reception_info['store_id'],
						'supplier_id'         => $reception_info['supplier_id'],
						'supplier_invoice_no' => $reception_info['supplier_delivery_no'],
						'email'               => $supplier_info ? $supplier_info['email'] : '',
						'telephone'           => $supplier_info ? $supplier_info['telephone'] : '',
						'fax'                 => $supplier_info ? $supplier_info['fax'] : '',
						'payment_company'     => $supplier_info ? $supplier_info['company'] : '',
						'payment_company_id'  => $supplier_info ? $supplier_info['company_id'] : '',
						'payment_tax_id'      => $supplier_info ? $supplier_info['tax_id'] : '',
						'payment_address_1'   => $supplier_info ? $supplier_info['address_1'] : '',
						'payment_address_2'   => $supplier_info ? $supplier_info['address_2'] : '',
						'payment_city'        => $supplier_info ? $supplier_info['city'] : '',
						'payment_postcode'    => $supplier_info ? $supplier_info['postcode'] : '',
						'payment_country_id'  => $supplier_info ? $supplier_info['country_id'] : 0,
						'payment_zone_id'     => $supplier_info ? $supplier_info['zone_id'] : 0,
						'payment_method'      => $reception_info['payment_method'],
						'payment_code'        => $reception_info['payment_code'],
						'shipping_company'    => $supplier_info ? $supplier_info['company'] : '',
						'shipping_address_1'  => $supplier_info ? $supplier_info['address_1'] : '',
						'shipping_address_2'  => $supplier_info ? $supplier_info['address_2'] : '',
						'shipping_city'       => $supplier_info ? $supplier_info['city'] : '',
						'shipping_postcode'   => $supplier_info ? $supplier_info['postcode'] : '',
						'shipping_country_id' => $supplier_info ? $supplier_info['country_id'] : 0,
						'shipping_zone_id'    => $supplier_info ? $supplier_info['zone_id'] : 0,
						'shipping_method'     => $reception_info['shipping_method'],
						'shipping_code'       => $reception_info['shipping_code'],
						'comment'             => $reception_info['comment'],
						'invoice_status_id'   => 1,
						'invoice_product'     => $invoice_products,
						'invoice_total'       => $this->model_purchase_reception->getReceptionTotals($reception_id)
					);

					$new_invoice_id = $this->model_purchase_invoice->addInvoice($data);

					// Cierra la recepción y marca el pedido de compra de origen (si lo hay)
					// como facturado, igual que hace purchase_order/convertToInvoice() —
					// la importación automática de facturas por email consulta invoice_no.
					$this->db->query("UPDATE `" . DB_PREFIX . "reception` SET invoice_no = '" . (int)$new_invoice_id . "' WHERE reception_id = '" . (int)$reception_id . "'");

					if (!empty($reception_info['purchase_order_id'])) {
						$this->db->query("UPDATE `" . DB_PREFIX . "purchase_order` SET invoice_no = '" . (int)$new_invoice_id . "' WHERE purchase_order_id = '" . (int)$reception_info['purchase_order_id'] . "' AND invoice_no = '0'");
					}

					$this->model_tool_user_logs->addLog(array(
						'user_id'       => $this->user->getId(),
						'username'      => $this->user->getUserName(),
						'action'        => 'create',
						'document_type' => 'purchase_invoice',
						'document_id'   => (int)$new_invoice_id,
						'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
					));
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_convert');

			$this->redirect($this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . $this->buildFilterUrl(), 'SSL'));
		}

		$this->getList();
	}

	private function validateConvert() {
		if (!$this->user->hasPermission('modify', 'purchase/reception')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function getList() {
		$filter_reception_id = isset($this->request->get['filter_reception_id']) ? $this->request->get['filter_reception_id'] : null;
		$filter_supplier = isset($this->request->get['filter_supplier']) ? $this->request->get['filter_supplier'] : null;
		$filter_reception_status_id = isset($this->request->get['filter_reception_status_id']) ? $this->request->get['filter_reception_status_id'] : null;
		$filter_total = isset($this->request->get['filter_total']) ? $this->request->get['filter_total'] : null;
		$filter_date_added = isset($this->request->get['filter_date_added']) ? $this->request->get['filter_date_added'] : null;

		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'r.reception_id';
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
			'href'      => $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('purchase/reception/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('purchase/reception/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['convert'] = $this->url->link('purchase/reception/convertToInvoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['invoice'] = $this->url->link('purchase/reception/document', 'token=' . $this->session->data['token'] . '&format=view', 'SSL');
		$this->data['printPDF'] = $this->url->link('purchase/reception/document', 'token=' . $this->session->data['token'] . '&format=pdf', 'SSL');

		$this->data['receptions'] = array();

		$data = array(
			'filter_reception_id'        => $filter_reception_id,
			'filter_supplier'            => $filter_supplier,
			'filter_reception_status_id' => $filter_reception_status_id,
			'filter_total'               => $filter_total,
			'filter_date_added'          => $filter_date_added,
			'sort'                       => $sort,
			'order'                      => $order,
			'start'                      => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                      => $this->config->get('config_admin_limit')
		);

		$reception_total = $this->model_purchase_reception->getTotalReceptions($data);

		$results = $this->model_purchase_reception->getReceptions($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'href'  => $this->url->link('purchase/reception/info', 'token=' . $this->session->data['token'] . '&reception_id=' . $result['reception_id'] . $url, 'SSL'),
				'icon'  => 'far fa-eye',
				'color' => 'info'
			);

			if (!$this->model_purchase_reception->checkInvoice($result['reception_id'])) {
				$action[] = array(
					'href'  => $this->url->link('purchase/reception/update', 'token=' . $this->session->data['token'] . '&reception_id=' . $result['reception_id'] . $url, 'SSL'),
					'icon'  => 'fas fa-edit',
					'color' => 'default'
				);
			}

			$this->data['receptions'][] = array(
				'reception_id'         => $result['reception_id'],
				'supplier_delivery_no' => $result['supplier_delivery_no'],
				'supplier'             => $result['supplier_company'],
				'status'               => $result['status'],
				'total'                => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'           => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified'        => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'             => isset($this->request->post['selected']) && in_array($result['reception_id'], $this->request->post['selected']),
				'action'               => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');
		$this->data['error_no_selection'] = $this->language->get('error_no_selection');

		$this->data['column_reception_id'] = $this->language->get('column_reception_id');
		$this->data['column_supplier_delivery_no'] = $this->language->get('column_supplier_delivery_no');
		$this->data['column_supplier'] = $this->language->get('column_supplier');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_convert_invoice'] = $this->language->get('button_convert_invoice');

		$this->data['token'] = $this->session->data['token'];

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['sort_reception_id'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . '&sort=r.reception_id' . $url, 'SSL');
		$this->data['sort_supplier'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . '&sort=supplier_company' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . '&sort=r.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . '&sort=r.date_modified' . $url, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $reception_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_reception_id'] = $filter_reception_id;
		$this->data['filter_supplier'] = $filter_supplier;
		$this->data['filter_reception_status_id'] = $filter_reception_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;

		$this->data['reception_statuses'] = $this->model_purchase_reception->getReceptionStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'purchase/reception_list.tpl';

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
		$this->data['text_supplier_details'] = $this->language->get('text_supplier_details');

		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_supplier'] = $this->language->get('entry_supplier');
		$this->data['entry_supplier_delivery_no'] = $this->language->get('entry_supplier_delivery_no');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_reception_status'] = $this->language->get('entry_reception_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_global_discount'] = $this->language->get('entry_global_discount');
		$this->data['entry_email'] = $this->language->get('text_email');
		$this->data['entry_telephone'] = $this->language->get('text_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_reception'] = $this->language->get('tab_reception');
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
			'href'      => $this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['reception_id'])) {
			$this->data['action'] = $this->url->link('purchase/reception/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/reception/update', 'token=' . $this->session->data['token'] . '&reception_id=' . $this->request->get['reception_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['reception_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$reception_info = $this->model_purchase_reception->getReception($this->request->get['reception_id']);
		} else {
			$reception_info = array();
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['reception_id'] = isset($this->request->get['reception_id']) ? $this->request->get['reception_id'] : 0;

		if (isset($this->request->post['purchase_order_id'])) {
			$this->data['purchase_order_id'] = $this->request->post['purchase_order_id'];
		} elseif (!empty($reception_info)) {
			$this->data['purchase_order_id'] = $reception_info['purchase_order_id'];
		} else {
			$this->data['purchase_order_id'] = 0;
		}

		if (isset($this->request->post['supplier_delivery_no'])) {
			$this->data['supplier_delivery_no'] = $this->request->post['supplier_delivery_no'];
		} elseif (!empty($reception_info)) {
			$this->data['supplier_delivery_no'] = $reception_info['supplier_delivery_no'];
		} else {
			$this->data['supplier_delivery_no'] = '';
		}

		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} elseif (!empty($reception_info)) {
			$this->data['comment'] = $reception_info['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($reception_info)) {
			$this->data['store_id'] = $reception_info['store_id'];
		} else {
			$this->data['store_id'] = 0;
		}

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['supplier_id'])) {
			$this->data['supplier_id'] = $this->request->post['supplier_id'];
		} elseif (!empty($reception_info)) {
			$this->data['supplier_id'] = $reception_info['supplier_id'];
		} else {
			$this->data['supplier_id'] = 0;
		}

		if (isset($this->request->post['supplier'])) {
			$this->data['supplier'] = $this->request->post['supplier'];
		} elseif (!empty($reception_info)) {
			$this->data['supplier'] = $reception_info['supplier_company'];
		} else {
			$this->data['supplier'] = '';
		}

		foreach (array('shipping_method', 'shipping_code', 'payment_method', 'payment_code') as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($reception_info)) {
				$this->data[$field] = $reception_info[$field];
			} else {
				$this->data[$field] = '';
			}
		}

		$this->load->model('localisation/shipping');
		$this->load->model('localisation/payment');

		$this->data['shippings'] = $this->model_localisation_shipping->getShippings();
		$this->data['payments'] = $this->model_localisation_payment->getPayments();

		if (isset($this->request->post['reception_status_id'])) {
			$this->data['reception_status_id'] = $this->request->post['reception_status_id'];
		} elseif (!empty($reception_info)) {
			$this->data['reception_status_id'] = $reception_info['reception_status_id'];
		} else {
			$this->data['reception_status_id'] = 1;
		}

		$this->data['reception_statuses'] = $this->model_purchase_reception->getReceptionStatuses();

		if (isset($this->request->post['reception_product'])) {
			$reception_products = $this->request->post['reception_product'];
		} elseif (isset($this->request->get['reception_id'])) {
			$reception_products = $this->model_purchase_reception->getReceptionProducts($this->request->get['reception_id']);
		} else {
			$reception_products = array();
		}

		$this->load->model('catalog/product');

		$this->data['reception_products'] = array();

		foreach ($reception_products as $reception_product) {
			$product_info = $this->model_catalog_product->getProduct($reception_product['product_id']);

			$this->data['reception_products'][] = array(
				'reception_product_id' => isset($reception_product['reception_product_id']) ? $reception_product['reception_product_id'] : 0,
				'product_id'           => $reception_product['product_id'],
				'name'                 => $reception_product['name'],
				'model'                => $reception_product['model'],
				'quantity'             => $reception_product['quantity'],
				'price'                => $this->currency->format($reception_product['price']),
				'price_raw'            => number_format((float)$reception_product['price'], 2, '.', ''),
				'catalog_price_raw'    => $product_info ? number_format((float)$product_info['price'], 2, '.', '') : number_format((float)$reception_product['price'], 2, '.', ''),
				'discount_raw'         => (!empty($reception_product['discount'])) ? number_format((float)preg_replace('/[^0-9\.]/', '', $reception_product['discount']), 2, '.', '') : '',
				'total'                => $this->currency->format($reception_product['total']),
				'tax'                  => $reception_product['tax']
			);
		}

		if (isset($this->request->post['reception_total'])) {
			$this->data['reception_totals'] = $this->request->post['reception_total'];
		} elseif (isset($this->request->get['reception_id'])) {
			$this->data['reception_totals'] = $this->model_purchase_reception->getReceptionTotals($this->request->get['reception_id']);
		} else {
			$this->data['reception_totals'] = array();
		}

		if (isset($this->request->post['global_discount'])) {
			$this->data['global_discount'] = $this->request->post['global_discount'];
		} else {
			$this->data['global_discount'] = '';

			foreach ($this->data['reception_totals'] as $reception_total) {
				if ($reception_total['code'] == 'discount') {
					$this->data['global_discount'] = number_format(abs((float)$reception_total['value']), 2, '.', '');
					break;
				}
			}
		}

		$this->template = 'purchase/reception_form.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'purchase/reception')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['supplier_id'])) {
			$this->error['supplier_id'] = $this->language->get('error_supplier');
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'purchase/reception')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	// Igual que ControllerPurchasePurchaseOrder::diffPurchaseOrderProducts() (mismo
	// criterio de emparejar por posicion + product_id, no por reception_product_id:
	// el recalculo AJAX de precio/cantidad reconstruye la tabla de lineas desde el
	// carrito de sesion y lo deja vacio en cada edicion).
	private function diffReceptionProducts($old_products, $new_products) {
		$original = array();
		$changed  = array();

		$old_products = array_values($old_products);
		$new_products = array_values($new_products);

		foreach ($new_products as $i => $product) {
			if (!isset($old_products[$i]) || (int)$old_products[$i]['product_id'] !== (int)$product['product_id']) {
				continue;
			}

			$old   = $old_products[$i];
			$label = 'Producto: ' . $old['name'];

			$old_price = number_format((float)preg_replace('/[^-0-9\.]/', '', $old['price']), 2, '.', '');
			$new_price = number_format((float)preg_replace('/[^-0-9\.]/', '', $product['price']), 2, '.', '');

			if ($old_price !== $new_price) {
				$original[$label . ' > Precio'] = $old_price;
				$changed[$label . ' > Precio']  = $new_price;
			}

			$old_qty = (string)(int)$old['quantity'];
			$new_qty = (string)(int)$product['quantity'];

			if ($old_qty !== $new_qty) {
				$original[$label . ' > Cantidad'] = $old_qty;
				$changed[$label . ' > Cantidad']  = $new_qty;
			}
		}

		return array('original' => $original, 'changed' => $changed);
	}

	public function info() {
		$this->load->model('purchase/reception');

		$reception_id = isset($this->request->get['reception_id']) ? $this->request->get['reception_id'] : 0;

		$reception_info = $this->model_purchase_reception->getReception($reception_id);

		if (!$reception_info) {
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

		$this->load->language('purchase/reception');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		foreach (array('text_reception_id', 'text_store', 'text_supplier', 'text_supplier_delivery_no', 'text_email', 'text_telephone', 'text_shipping_method', 'text_payment_method', 'text_total', 'text_reception_status', 'text_comment', 'text_date_added', 'text_date_modified') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		foreach (array('column_product', 'column_model', 'column_quantity', 'column_price', 'column_total') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['entry_reception_status'] = $this->language->get('entry_reception_status');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		$this->data['entry_comment'] = $this->language->get('entry_comment');

		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_history'] = $this->language->get('button_add_history');
		$this->data['button_generate'] = $this->language->get('button_generate');

		$this->data['tab_reception'] = $this->language->get('tab_reception');
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
				'href'      => $this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['cancel'] = $this->url->link('purchase/reception', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['invoice'] = $this->url->link('purchase/reception/document', 'token=' . $this->session->data['token'] . '&reception_id=' . $reception_info['reception_id'] . '&format=view', 'SSL');
		$this->data['printPDF'] = $this->url->link('purchase/reception/document', 'token=' . $this->session->data['token'] . '&reception_id=' . $reception_info['reception_id'] . '&format=pdf', 'SSL');

		if ($reception_info['invoice_no']) {
			$this->data['generate'] = $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $reception_info['invoice_no'], 'SSL');
		} else {
			$this->data['generate'] = $this->url->link('purchase/reception/convertToInvoice', 'token=' . $this->session->data['token'] . '&selected[]=' . $reception_info['reception_id'], 'SSL');
		}

		$this->data['invoice_no'] = $reception_info['invoice_no'];

		$this->data['reception_id'] = $reception_info['reception_id'];
		$this->data['supplier_delivery_no'] = $reception_info['supplier_delivery_no'];
		$this->data['store_name'] = $reception_info['store_name'];
		$this->data['supplier_company'] = $reception_info['supplier_company'];
		$this->data['supplier_name'] = trim($reception_info['supplier_firstname'] . ' ' . $reception_info['supplier_lastname']);
		$this->data['supplier_email'] = $reception_info['supplier_email'];
		$this->data['supplier_telephone'] = $reception_info['supplier_telephone'];
		$this->data['supplier'] = $reception_info['supplier_id'] ? $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $reception_info['supplier_id'], 'SSL') : '';
		$this->data['shipping_method'] = $reception_info['shipping_method'];
		$this->data['payment_method'] = $reception_info['payment_method'];
		$this->data['comment'] = nl2br($reception_info['comment']);
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($reception_info['date_added']));
		$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($reception_info['date_modified']));
		$this->data['total'] = $this->currency->format($reception_info['total'], $reception_info['currency_code'], $reception_info['currency_value'], true, true);
		$this->data['reception_status'] = $reception_info['status'];
		$this->data['reception_status_id'] = $reception_info['reception_status_id'];
		$this->data['reception_statuses'] = $this->model_purchase_reception->getReceptionStatuses();

		$this->data['products'] = array();

		$products = $this->model_purchase_reception->getReceptionProducts($reception_info['reception_id']);

		foreach ($products as $product) {
			$this->data['products'][] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'quantity'   => $product['quantity'],
				'price'      => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $reception_info['currency_code'], $reception_info['currency_value']),
				'total'      => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $reception_info['currency_code'], $reception_info['currency_value']),
				'href'       => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
			);
		}

		$this->data['totals'] = $this->model_purchase_reception->getReceptionTotals($reception_info['reception_id']);

		$this->template = 'purchase/reception_info.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function history() {
		$this->load->language('purchase/reception');

		$this->data['error'] = '';
		$this->data['success'] = '';

		$this->load->model('purchase/reception');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'purchase/reception')) {
				$this->data['error'] = $this->language->get('error_permission');
			}

			if (!$this->data['error']) {
				$this->model_purchase_reception->addReceptionHistory($this->request->get['reception_id'], $this->request->post);

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

		$results = $this->model_purchase_reception->getReceptionHistories($this->request->get['reception_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_purchase_reception->getTotalReceptionHistories($this->request->get['reception_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('purchase/reception/history', 'token=' . $this->session->data['token'] . '&reception_id=' . $this->request->get['reception_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'purchase/reception_history.tpl';

		$this->response->setOutput($this->render());
	}

	public function checkReception() {
		$this->load->language('purchase/reception');

		$json = array();

		if ($this->user->hasPermission('modify', 'purchase/reception')) {
			unset($this->session->data['cart']);

			$this->load->model('catalog/product');

			$this->session->data['cart'] = array();

			if (isset($this->request->post['reception_product'])) {
				foreach ($this->request->post['reception_product'] as $reception_product) {
					$product_info = $this->model_catalog_product->getProduct($reception_product['product_id']);

					if ($product_info) {
						$use_price = (isset($reception_product['price']) && $reception_product['price'] !== '')
							? (float)preg_replace('/[^-0-9\.]/', '', $reception_product['price'])
							: (float)$product_info['price'];

						$discount_percent = isset($reception_product['discount']) ? (float)preg_replace('/[^0-9\.]/', '', $reception_product['discount']) : 0;
						$discount_amount = ($use_price * $reception_product['quantity']) * ($discount_percent / 100);

						$this->session->data['cart'][] = array(
							'product_id'    => $product_info['product_id'],
							'name'          => $product_info['name'],
							'model'         => $product_info['model'],
							'quantity'      => $reception_product['quantity'],
							'price'         => $use_price,
							'catalog_price' => $product_info['price'],
							'tax_class_id'  => $product_info['tax_class_id'],
							'total'         => ($use_price * $reception_product['quantity']) - $discount_amount,
							'discount'      => $discount_percent
						);
					}
				}
			}

			if (!empty($this->request->post['product_id'])) {
				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					$quantity = isset($this->request->post['quantity']) ? $this->request->post['quantity'] : 1;

					$discount_percent = isset($this->request->post['discount']) ? (float)preg_replace('/[^0-9\.]/', '', $this->request->post['discount']) : 0;
					$discount_amount = ($product_info['price'] * $quantity) * ($discount_percent / 100);

					$this->session->data['cart'][] = array(
						'product_id'   => $this->request->post['product_id'],
						'name'         => $product_info['name'],
						'model'        => $product_info['model'],
						'quantity'     => $quantity,
						'price'        => $product_info['price'],
						'tax_class_id' => $product_info['tax_class_id'],
						'total'        => ($product_info['price'] * $quantity) - $discount_amount,
						'discount'     => $discount_percent
					);
				} else {
					$json['error']['product']['not_found'] = $this->language->get('error_action');
				}
			}

			$json['reception_product'] = array();

			$products = $this->session->data['cart'];

			foreach ($products as $product) {
				$json['reception_product'][] = array(
					'product_id'        => $product['product_id'],
					'name'              => $product['name'],
					'model'             => $product['model'],
					'quantity'          => $product['quantity'],
					'price'             => $this->currency->format($product['price']),
					'price_raw'         => number_format((float)$product['price'], 2, '.', ''),
					'catalog_price_raw' => number_format((float)(isset($product['catalog_price']) ? $product['catalog_price'] : $product['price']), 2, '.', ''),
					'tax_class_id'      => $product['tax_class_id'],
					'total'             => $this->currency->format($product['total']),
					'discount'          => (!empty($product['discount'])) ? number_format((float)$product['discount'], 2, '.', '') : ''
				);
			}

			$json['reception_total'] = array();

			$total = 0;
			$taxes = $this->getTaxes($products);

			$this->load->model('total/discount');
			$this->load->model('total/sub_total');
			$this->load->model('total/tax');
			$this->load->model('total/total');

			// discount corre antes que sub_total para que este último salga neto
			// (mismo orden que purchase_order, ver model/total/discount.php)
			$this->model_total_discount->getTotal($json['reception_total'], $total, $taxes);
			$this->model_total_sub_total->getTotal($json['reception_total'], $total, $taxes);
			$this->model_total_tax->getTotal($json['reception_total'], $total, $taxes);
			$this->model_total_total->getTotal($json['reception_total'], $total, $taxes);

			if (!isset($json['error'])) {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getTaxes($data) {
		$this->load->model('catalog/product');

		$tax_data = array();

		// IVA sobre el sub-total (neto de descuento de línea + descuento global),
		// no sobre el precio unitario de catálogo.
		$global_discount_percent = isset($this->request->post['global_discount']) ? (float)preg_replace('/[^0-9.]/', '', $this->request->post['global_discount']) : 0;

		foreach ($data as $product) {
			if ($product['tax_class_id'] != 0) {
				$unit_price_after_discount = $product['quantity'] ? ($product['total'] / $product['quantity']) : $product['price'];
				$unit_price_for_tax = $unit_price_after_discount * (1 - ($global_discount_percent / 100));

				$tax_rates = $this->model_catalog_product->getProductRates($unit_price_for_tax, $product['tax_class_id']);

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

	// Documento imprimible de la recepción (View HTML / PDF), mismo patrón que
	// ControllerPurchasePurchaseOrder::document() — la recepción no guarda su
	// propia copia de la dirección del proveedor, así que el bloque "To" se
	// resuelve en vivo contra la ficha actual del proveedor.
	public function document() {
		$lcFormat = isset($this->request->get['format']) ? $this->request->get['format'] : '';

		$this->load->language('purchase/reception');

		$this->data['title'] = $this->language->get('text_reception');

		if (isset($this->request->server['HTTPS']) && in_array($this->request->server['HTTPS'], array('on', '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_reception'] = $this->language->get('text_reception');
		$this->data['text_reception_id'] = $this->language->get('text_reception_id');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_tax_id'] = $this->language->get('text_tax_id');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->load->model('purchase/reception');
		$this->load->model('purchase/supplier');
		$this->load->model('setting/setting');

		$reception_ids = array();
		$reception_id = 0;

		if (isset($this->request->post['selected'])) {
			$reception_ids = $this->request->post['selected'];
		} elseif (isset($this->request->get['reception_id'])) {
			$reception_ids[] = $this->request->get['reception_id'];
		}

		$reception_ids = array_unique($reception_ids);

		$this->data['receptions'] = array();

		foreach ($reception_ids as $reception_id) {
			$reception_info = $this->model_purchase_reception->getReception($reception_id);

			if (!$reception_info) {
				continue;
			}

			$store_info = $this->model_setting_setting->getSetting('config', $reception_info['store_id']);

			if ($store_info) {
				$store_address = $store_info['config_address'];
				$store_email = $store_info['config_email'];
				$store_telephone = $store_info['config_telephone'];
				$store_fax = isset($store_info['config_fax']) ? $store_info['config_fax'] : '';
			} else {
				$store_address = $this->config->get('config_address');
				$store_email = $this->config->get('config_email');
				$store_telephone = $this->config->get('config_telephone');
				$store_fax = (string)$this->config->get('config_fax');
			}

			$store_nif = $this->config->get('config_vat_id');

			if (!$store_nif) {
				$store_nif = $this->config->get('config_nif');
			}

			$supplier_info = $this->model_purchase_supplier->getSupplier($reception_info['supplier_id']);

			$supplier_address = '';

			if ($supplier_info) {
				$supplier_address_lines = array_filter(array(
					$supplier_info['address_1'],
					$supplier_info['address_2'],
					trim($supplier_info['postcode'] . ' ' . $supplier_info['city']),
					$supplier_info['zone'],
					$supplier_info['country']
				));

				$supplier_address = implode('<br/>', array_map('htmlspecialchars', $supplier_address_lines));
			}

			$product_data = array();

			foreach ($this->model_purchase_reception->getReceptionProducts($reception_id) as $product) {
				$product_data[] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $reception_info['currency_code'], $reception_info['currency_value'], true, true),
					'discount' => (!empty($product['discount'])) ? number_format((float)$product['discount'], 2, '.', '') . '%' : '',
					'total'    => $this->currency->format($product['total'], $reception_info['currency_code'], $reception_info['currency_value'], true, true)
				);
			}

			$total_data = array();

			foreach ($this->model_purchase_reception->getReceptionTotals($reception_id) as $total) {
				$total_data[] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $reception_info['currency_code'], $reception_info['currency_value'], true, true)
				);
			}

			$this->data['receptions'][] = array(
				'reception_id'         => $reception_id,
				'supplier_delivery_no' => $reception_info['supplier_delivery_no'],
				'date_added'           => date($this->language->get('date_format_short'), strtotime($reception_info['date_added'])),
				'store_name'           => $reception_info['store_name'],
				'store_address'        => nl2br(trim($store_address)),
				'store_email'          => $store_email,
				'store_telephone'      => $store_telephone,
				'store_fax'            => $store_fax,
				'store_nif'            => $store_nif,
				'supplier_company'     => $supplier_info ? $supplier_info['company'] : $reception_info['supplier_company'],
				'supplier_tax_id'      => $supplier_info ? $supplier_info['tax_id'] : '',
				'supplier_address'     => $supplier_address,
				'supplier_email'       => $supplier_info ? $supplier_info['email'] : $reception_info['supplier_email'],
				'supplier_telephone'   => $supplier_info ? $supplier_info['telephone'] : $reception_info['supplier_telephone'],
				'payment_method'       => $reception_info['payment_method'],
				'shipping_method'      => $reception_info['shipping_method'],
				'product'              => $product_data,
				'total'                => $total_data
			);
		}

		$this->data['logo'] = $this->config->get('config_logo');

		if ($lcFormat == 'pdf') {
			$this->renderPDF('purchase/reception_printPDF.tpl', 'pdf', 'reception', $reception_id);
		} else {
			$this->data['auto_print'] = ($lcFormat != 'view');

			$this->template = 'purchase/reception_invoice.tpl';
			$this->response->setOutput($this->render());
		}
	}

	public function searchPurchaseOrders() {
		ob_start();
		$json = array();

		if ($this->user->hasPermission('access', 'purchase/reception')) {
			$this->load->model('purchase/purchase_order');
			$this->load->language('purchase/reception');

			$data = array(
				'filter_purchase_order_status_id' => null,
				'sort'  => 'po.purchase_order_id',
				'order' => 'DESC',
				'start' => 0,
				'limit' => 200,
			);

			if (!empty($this->request->post['filter_purchase_order_id'])) {
				$data['filter_purchase_order_id'] = (int)$this->request->post['filter_purchase_order_id'];
			}

			if (!empty($this->request->post['filter_supplier'])) {
				$data['filter_supplier'] = $this->request->post['filter_supplier'];
			}

			$results = $this->model_purchase_purchase_order->getPurchaseOrders($data);

			foreach ($results as $result) {
				// Solo interesan a este modal los pedidos que aún no se hayan facturado
				// directamente (invoice_no = 0) — un pedido ya cerrado no debería volver
				// a usarse como origen de una recepción nueva.
				if (!empty($result['invoice_no'])) {
					continue;
				}

				$json[] = array(
					'purchase_order_id' => $result['purchase_order_id'],
					'po_number'         => $result['po_number'],
					'supplier'          => $result['supplier_company'],
					'date_added'        => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'total'             => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'status'            => $result['status'],
				);
			}
		}

		ob_end_clean();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getPurchaseOrderData() {
		ob_start();
		$json = array();

		if ($this->user->hasPermission('access', 'purchase/reception')) {
			$this->load->model('purchase/purchase_order');
			$this->load->model('catalog/product');

			$purchase_order_id = isset($this->request->post['purchase_order_id']) ? (int)$this->request->post['purchase_order_id'] : 0;

			if ($purchase_order_id) {
				$purchase_order_info = $this->model_purchase_purchase_order->getPurchaseOrder($purchase_order_id);

				if ($purchase_order_info) {
					$json['purchase_order_id'] = $purchase_order_info['purchase_order_id'];
					$json['supplier_id']       = $purchase_order_info['supplier_id'];
					$json['supplier']          = $purchase_order_info['supplier_company'];
					$json['shipping_code']     = $purchase_order_info['shipping_code'];
					$json['payment_code']      = $purchase_order_info['payment_code'];

					$order_products = $this->model_purchase_purchase_order->getPurchaseOrderProducts($purchase_order_id);
					$json['reception_product'] = array();

					foreach ($order_products as $product) {
						$product_info = $this->model_catalog_product->getProduct($product['product_id']);

						$json['reception_product'][] = array(
							'product_id'        => $product['product_id'],
							'name'              => $product['name'],
							'model'             => $product['model'],
							'quantity'          => $product['quantity'],
							'price'             => $this->currency->format($product['price']),
							'price_raw'         => number_format((float)$product['price'], 2, '.', ''),
							'catalog_price_raw' => $product_info ? number_format((float)$product_info['price'], 2, '.', '') : number_format((float)$product['price'], 2, '.', ''),
							'discount'          => (!empty($product['discount'])) ? number_format((float)$product['discount'], 2, '.', '') : '',
							'total'             => $this->currency->format($product['total'], '', '', true, true),
							'tax'               => isset($product['tax']) ? $product['tax'] : 0,
						);
					}

					$order_totals = $this->model_purchase_purchase_order->getPurchaseOrderTotals($purchase_order_id);
					$json['reception_total'] = array();

					foreach ($order_totals as $total) {
						$json['reception_total'][] = array(
							'code'       => $total['code'],
							'title'      => $total['title'],
							'text'       => $total['text'],
							'value'      => $total['value'],
							'sort_order' => $total['sort_order'],
						);
					}
				} else {
					$json['error'] = $this->language->get('error_purchase_order_not_found');
				}
			} else {
				$json['error'] = $this->language->get('error_purchase_order_not_found');
			}
		}

		ob_end_clean();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function buildFilterUrl() {
		$url = '';

		foreach (array('filter_reception_id', 'filter_supplier', 'filter_reception_status_id', 'filter_total', 'filter_date_added', 'sort', 'order', 'page') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . (($key == 'filter_supplier') ? urlencode(html_entity_decode($this->request->get[$key], ENT_QUOTES, 'UTF-8')) : $this->request->get[$key]);
			}
		}

		return $url;
	}
}
?>
