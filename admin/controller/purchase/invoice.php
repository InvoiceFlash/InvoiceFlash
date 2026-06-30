<?php
class ControllerPurchaseInvoice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purchase/invoice');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('purchase/invoice');
		$this->getList();
	}

	public function insert() {
		$this->load->language('purchase/invoice');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('purchase/invoice');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$new_invoice_id = $this->model_purchase_invoice->addInvoice($this->request->post);
			$this->load->model('tool/user_logs');
			$inv = $this->model_purchase_invoice->getInvoice((int)$new_invoice_id);
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'create',
				'document_type' => 'purchase_invoice',
				'document_id'   => (int)$new_invoice_id,
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->buildFilterUrl();
			$this->redirect($this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function update() {
		$this->load->language('purchase/invoice');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('purchase/invoice');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_invoice->editInvoice($this->request->get['invoice_id'], $this->request->post);
			$this->load->model('tool/user_logs');
			$inv = $this->model_purchase_invoice->getInvoice((int)$this->request->get['invoice_id']);
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'edit',
				'document_type' => 'purchase_invoice',
				'document_id'   => (int)$this->request->get['invoice_id'],
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->buildFilterUrl();
			$this->redirect($this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function delete() {
		$this->load->language('purchase/invoice');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('purchase/invoice');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $invoice_id) {
				$this->model_purchase_invoice->createNegativeInvoice($invoice_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = $this->buildFilterUrl();
			$this->redirect($this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function buildFilterUrl() {
		$url = '';
		$filters = array('filter_invoice_id', 'filter_company', 'filter_invoice_status_id', 'filter_total', 'filter_date_added', 'filter_date_modified', 'sort', 'order', 'page');
		foreach ($filters as $filter) {
			if (isset($this->request->get[$filter])) {
				if ($filter == 'filter_company') {
					$url .= '&' . $filter . '=' . urlencode(html_entity_decode($this->request->get[$filter], ENT_QUOTES, 'UTF-8'));
				} else {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}
		}
		return $url;
	}

	private function getList() {
		$filter_invoice_id        = isset($this->request->get['filter_invoice_id']) ? $this->request->get['filter_invoice_id'] : null;
		$filter_company           = isset($this->request->get['filter_company']) ? $this->request->get['filter_company'] : null;
		$filter_invoice_status_id = isset($this->request->get['filter_invoice_status_id']) ? $this->request->get['filter_invoice_status_id'] : null;
		$filter_total             = isset($this->request->get['filter_total']) ? $this->request->get['filter_total'] : null;
		$filter_date_added        = isset($this->request->get['filter_date_added']) ? $this->request->get['filter_date_added'] : null;
		$filter_date_modified     = isset($this->request->get['filter_date_modified']) ? $this->request->get['filter_date_modified'] : null;
		$sort                     = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'o.date_added';
		$order                    = isset($this->request->get['order']) ? $this->request->get['order'] : 'DESC';
		$page                     = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$url = $this->buildFilterUrl();

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['invoice'] = $this->url->link('purchase/invoice/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['print']   = $this->url->link('purchase/invoice/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert']  = $this->url->link('purchase/invoice/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete']  = $this->url->link('purchase/invoice/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['reports'] = array(
			array('name' => 'Invoice', 'report' => 'purchase_invoice_printPDF.tpl')
		);

		$data = array(
			'filter_invoice_id'        => $filter_invoice_id,
			'filter_company'           => $filter_company,
			'filter_invoice_status_id' => $filter_invoice_status_id,
			'filter_total'             => $filter_total,
			'filter_date_added'        => $filter_date_added,
			'filter_date_modified'     => $filter_date_modified,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$invoice_total = $this->model_purchase_invoice->getTotalInvoices($data);
		$results       = $this->model_purchase_invoice->getInvoices($data);

		$this->data['invoices'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'href'  => $this->url->link('purchase/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL'),
				'icon'  => 'far fa-eye',
				'color' => 'info'
			);

			$action[] = array(
				'href'  => $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL'),
				'icon'  => 'fas fa-edit',
				'color' => 'default'
			);

			$this->data['invoices'][] = array(
				'invoice_id'    => $result['invoice_id'],
				'company'       => $result['company'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['invoice_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['heading_title']    = $this->language->get('heading_title');
		$this->data['text_no_results']  = $this->language->get('text_no_results');
		$this->data['text_missing']     = $this->language->get('text_missing');

		$this->data['column_invoice_id']    = $this->language->get('column_invoice_id');
		$this->data['column_supplier']      = $this->language->get('column_supplier');
		$this->data['column_status']        = $this->language->get('column_status');
		$this->data['column_total']         = $this->language->get('column_total');
		$this->data['column_date_added']    = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action']        = $this->language->get('column_action');

		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_insert']  = $this->language->get('button_insert');
		$this->data['button_delete']  = $this->language->get('button_delete');
		$this->data['button_filter']  = $this->language->get('button_filter');

		$this->data['error_no_selection'] = $this->language->get('error_no_selection');
		$this->data['token']              = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		// Sort URLs
		$sort_url = $this->buildFilterUrl();
		if ($order == 'ASC') {
			$sort_url_order = str_replace('&order=ASC', '', $sort_url) . '&order=DESC';
		} else {
			$sort_url_order = str_replace('&order=DESC', '', $sort_url) . '&order=ASC';
		}

		$this->data['sort_invoice']       = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . '&sort=o.invoice_id' . $sort_url_order, 'SSL');
		$this->data['sort_company']       = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . '&sort=company' . $sort_url_order, 'SSL');
		$this->data['sort_status']        = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . '&sort=status' . $sort_url_order, 'SSL');
		$this->data['sort_total']         = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . '&sort=o.total' . $sort_url_order, 'SSL');
		$this->data['sort_date_added']    = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $sort_url_order, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $sort_url_order, 'SSL');

		$pagination         = new Pagination();
		$pagination->total  = $invoice_total;
		$pagination->page   = $page;
		$pagination->limit  = $this->config->get('config_admin_limit');
		$pagination->text   = $this->language->get('text_pagination');
		$pagination->url    = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $sort_url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_invoice_id']        = $filter_invoice_id;
		$this->data['filter_company']           = $filter_company;
		$this->data['filter_invoice_status_id'] = $filter_invoice_status_id;
		$this->data['filter_total']             = $filter_total;
		$this->data['filter_date_added']        = $filter_date_added;
		$this->data['filter_date_modified']     = $filter_date_modified;

		$this->load->model('localisation/invoice_status');
		$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();

		$this->data['sort']  = $sort;
		$this->data['order'] = $order;

		$this->template = 'purchase/purchase_invoice_list.tpl';
		$this->children  = array('common/header', 'common/footer');
		$this->response->setOutput($this->render());
	}

	public function getForm() {
		$this->load->model('purchase/supplier');

		$this->data['heading_title']         = $this->language->get('heading_title');
		$this->data['text_no_results']       = $this->language->get('text_no_results');
		$this->data['text_default']          = $this->language->get('text_default');
		$this->data['text_select']           = $this->language->get('text_select');
		$this->data['text_none']             = $this->language->get('text_none');
		$this->data['text_wait']             = $this->language->get('text_wait');
		$this->data['text_product']          = $this->language->get('text_product');
		$this->data['text_sub_total']        = $this->language->get('text_sub_total');
		$this->data['text_invoice']          = $this->language->get('text_invoice');
		$this->data['text_invoice_details']  = $this->language->get('text_invoice_details');

		$this->data['entry_store']           = $this->language->get('entry_store');
		$this->data['entry_supplier']            = $this->language->get('entry_supplier');
		$this->data['entry_supplier_invoice_no'] = $this->language->get('entry_supplier_invoice_no');
		$this->data['entry_vat']             = $this->language->get('entry_vat');
		$this->data['entry_email']           = $this->language->get('entry_email');
		$this->data['entry_telephone']       = $this->language->get('entry_telephone');
		$this->data['entry_fax']             = $this->language->get('entry_fax');
		$this->data['entry_invoice_status']  = $this->language->get('entry_invoice_status');
		$this->data['entry_comment']         = $this->language->get('entry_comment');
		$this->data['entry_address']         = $this->language->get('entry_address');
		$this->data['entry_company']         = $this->language->get('entry_company');
		$this->data['entry_company_id']      = $this->language->get('entry_company_id');
		$this->data['entry_tax_id']          = $this->language->get('entry_tax_id');
		$this->data['entry_address_1']       = $this->language->get('entry_address_1');
		$this->data['entry_address_2']       = $this->language->get('entry_address_2');
		$this->data['entry_city']            = $this->language->get('entry_city');
		$this->data['entry_postcode']        = $this->language->get('entry_postcode');
		$this->data['entry_zone']            = $this->language->get('entry_zone');
		$this->data['entry_zone_code']       = $this->language->get('entry_zone_code');
		$this->data['entry_country']         = $this->language->get('entry_country');
		$this->data['entry_product']         = $this->language->get('entry_product');
		$this->data['entry_option']          = $this->language->get('entry_option');
		$this->data['entry_quantity']        = $this->language->get('entry_quantity');
		$this->data['entry_name_ext']        = $this->language->get('entry_name_ext');
		$this->data['entry_price']           = $this->language->get('entry_price');
		$this->data['entry_to_name']         = $this->language->get('entry_to_name');
		$this->data['entry_to_email']        = $this->language->get('entry_to_email');
		$this->data['entry_from_name']       = $this->language->get('entry_from_name');
		$this->data['entry_from_email']      = $this->language->get('entry_from_email');
		$this->data['entry_theme']           = $this->language->get('entry_theme');
		$this->data['entry_message']         = $this->language->get('entry_message');
		$this->data['entry_amount']          = $this->language->get('entry_amount');
		$this->data['entry_shipping']        = $this->language->get('entry_shipping');
		$this->data['entry_payment']         = $this->language->get('entry_payment');
		$this->data['entry_coupon']          = $this->language->get('entry_coupon');

		$this->data['column_product']        = $this->language->get('column_product');
		$this->data['column_model']          = $this->language->get('column_model');
		$this->data['column_quantity']       = $this->language->get('column_quantity');
		$this->data['column_price']          = $this->language->get('column_price');
		$this->data['column_total']          = $this->language->get('column_total');

		$this->data['button_save']           = $this->language->get('button_save');
		$this->data['button_cancel']         = $this->language->get('button_cancel');
		$this->data['button_add_product']    = $this->language->get('button_add_product');
		$this->data['button_update_total']   = $this->language->get('button_update_total');
		$this->data['button_remove']         = $this->language->get('button_remove');
		$this->data['button_upload']         = $this->language->get('button_upload');

		$this->data['tab_invoice']           = $this->language->get('tab_invoice');
		$this->data['tab_supplier']          = $this->language->get('tab_supplier');
		$this->data['tab_payment']           = $this->language->get('tab_payment');
		$this->data['tab_shipping']          = $this->language->get('tab_shipping');
		$this->data['tab_product']           = $this->language->get('tab_product');
		$this->data['tab_total']             = $this->language->get('tab_total');

		// Errors
		foreach (array('warning','company','email','telephone','payment_address_1','payment_city','payment_postcode','payment_tax_id','payment_country','payment_zone','payment_method','shipping_address_1','shipping_city','shipping_postcode','shipping_country','shipping_zone','shipping_method') as $key) {
			$err_key = ($key == 'company') ? 'customer' : $key;
			$this->data['error_' . $err_key] = isset($this->error[$key]) ? $this->error[$key] : '';
		}

		$url = $this->buildFilterUrl();

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['invoice_id'])) {
			$this->data['action'] = $this->url->link('purchase/invoice/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['invoice_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$invoice_info = $this->model_purchase_invoice->getInvoice($this->request->get['invoice_id']);
		}

		$this->data['token'] = $this->session->data['token'];

		$this->data['invoice_id'] = isset($this->request->get['invoice_id']) ? $this->request->get['invoice_id'] : 0;

		// Store
		$this->data['store_id'] = isset($this->request->post['store_id']) ? $this->request->post['store_id'] : (isset($invoice_info) && !empty($invoice_info) ? $invoice_info['store_id'] : '');
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		$this->data['store_url'] = (isset($this->request->server['HTTPS']) && in_array($this->request->server['HTTPS'], array('on', '1'))) ? HTTPS_CATALOG : HTTP_CATALOG;

		// Supplier + address fields
		$fields = array('company', 'supplier_id', 'supplier_invoice_no', 'email', 'telephone', 'fax',
			'invoice_status_id', 'comment',
			'payment_company', 'payment_company_id', 'payment_address_1', 'payment_address_2',
			'payment_city', 'payment_postcode', 'payment_country_id', 'payment_zone_id',
			'payment_method', 'payment_code',
			'shipping_company', 'shipping_address_1', 'shipping_address_2',
			'shipping_city', 'shipping_postcode', 'shipping_country_id', 'shipping_zone_id',
			'shipping_method', 'shipping_code');

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($invoice_info)) {
				$this->data[$field] = isset($invoice_info[$field]) ? $invoice_info[$field] : '';
			} else {
				$this->data[$field] = '';
			}
		}

		// Check if supplier doc already uploaded
		$this->data['doc_exists'] = false;
		$invoice_id_check = isset($this->request->get['invoice_id']) ? (int)$this->request->get['invoice_id'] : 0;
		if ($invoice_id_check && !empty($invoice_info)) {
			$doc_dir = rtrim(str_replace('\\', '/', realpath(dirname(DIR_APPLICATION))), '/') . '/docs/suppliers/' . date('Y') . '/';
			$sid  = (int)$invoice_info['supplier_id'];
			$sino = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $invoice_info['supplier_invoice_no']);
			$matches = glob($doc_dir . $sid . '_' . $sino . '.*');
			$this->data['doc_exists'] = !empty($matches);
		}
		$this->data['upload_doc_url'] = $this->url->link('purchase/invoice/uploadDoc', 'token=' . $this->session->data['token'] . '&invoice_id=' . $invoice_id_check, 'SSL');

		$this->load->model('localisation/invoice_status');
		$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();

		$this->load->model('setting/extension');
		$this->data['shipping_option_codes'] = $this->model_purchase_invoice->getInvoiceShippingCodes();

		// Products
		if (isset($this->request->post['invoice_product'])) {
			$invoice_products = $this->request->post['invoice_product'];
		} elseif (isset($this->request->get['invoice_id'])) {
			$invoice_products = $this->model_purchase_invoice->getInvoiceProducts($this->request->get['invoice_id']);
		} else {
			$invoice_products = array();
		}

		$this->data['invoice_products'] = array();

		foreach ($invoice_products as $invoice_product) {
			if (isset($invoice_product['invoice_option'])) {
				$invoice_option = $invoice_product['invoice_option'];
			} elseif (isset($this->request->get['invoice_id'])) {
				$invoice_option = $this->model_purchase_invoice->getInvoiceOptions($this->request->get['invoice_id'], $invoice_product['invoice_product_id']);
			} else {
				$invoice_option = array();
			}

			$this->data['invoice_products'][] = array(
				'invoice_product_id' => $invoice_product['invoice_product_id'],
				'product_id'         => $invoice_product['product_id'],
				'name'               => $invoice_product['name'],
				'model'              => $invoice_product['model'],
				'option'             => $invoice_option,
				'quantity'           => $invoice_product['quantity'],
				'price'              => isset($invoice_info) ? $this->currency->format($invoice_product['price'], $invoice_info['currency_code'], $invoice_info['currency_value']) : $invoice_product['price'],
				'total'              => isset($invoice_info) ? $this->currency->format($invoice_product['total'], $invoice_info['currency_code'], $invoice_info['currency_value']) : $invoice_product['total'],
				'tax'                => $invoice_product['tax']
			);
		}

		// Totals
		if (isset($this->request->post['invoice_total'])) {
			$this->data['invoice_totals'] = $this->request->post['invoice_total'];
		} elseif (isset($this->request->get['invoice_id'])) {
			$this->data['invoice_totals'] = $this->model_purchase_invoice->getInvoiceTotals($this->request->get['invoice_id']);
		} else {
			$this->data['invoice_totals'] = array();
		}

		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/payment');
		$this->load->model('localisation/shipping');
		$this->data['payments']  = $this->model_localisation_payment->getPayments();
		$this->data['shippings'] = $this->model_localisation_shipping->getShippings();

		$this->template = 'purchase/purchase_invoice_form.tpl';
		$this->children  = array('common/header', 'common/footer');
		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'purchase/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'purchase/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function info() {
		$this->load->model('purchase/invoice');

		$invoice_id = isset($this->request->get['invoice_id']) ? $this->request->get['invoice_id'] : 0;

		$invoice_info = $this->model_purchase_invoice->getInvoice($invoice_id);

		if ($invoice_info) {
			$this->load->language('purchase/invoice');
			$this->document->setTitle($this->language->get('heading_title'));

			// Texts
			foreach (array('heading_title','text_invoice_id','text_invoice_no','text_invoice_date','text_store_name','text_store_url','text_supplier','text_supplier_invoice_no','text_email','text_telephone','text_fax','text_total','text_invoice_status','text_comment','text_date_added','text_date_modified','text_company','text_company_id','text_tax_id','text_address_1','text_address_2','text_city','text_postcode','text_zone','text_zone_code','text_country','text_shipping_method','text_payment_method','text_download','text_wait','text_generate') as $key) {
				$this->data[$key] = $this->language->get($key);
			}

			foreach (array('entry_invoice_id','entry_invoice_status','entry_notify','entry_comment') as $key) {
				$this->data[$key] = $this->language->get($key);
			}

			foreach (array('column_product','column_model','column_quantity','column_price','column_total','column_download','column_filename','column_remaining') as $key) {
				$this->data[$key] = $this->language->get($key);
			}

			foreach (array('button_invoice','button_cancel','button_add_history') as $key) {
				$this->data[$key] = $this->language->get($key);
			}

			foreach (array('tab_invoice','tab_payment','tab_shipping','tab_product','tab_history') as $key) {
				$this->data[$key] = $this->language->get($key);
			}

			$this->data['token'] = $this->session->data['token'];

			$url = $this->buildFilterUrl();

			$this->data['breadcrumbs'] = array();
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			);

			$this->data['printPDF'] = $this->url->link('purchase/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$invoice_id . '&format=pdf', 'SSL');
			$this->data['invoice']  = $this->url->link('purchase/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$invoice_id . '&format=view', 'SSL');
			$this->data['sendEmail'] = $this->url->link('purchase/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$invoice_id . '&format=email', 'SSL');
			$this->data['cancel']   = $this->url->link('purchase/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['print']    = $this->url->link('purchase/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$invoice_id, 'SSL');

			$this->data['reports'] = array(
				array('name' => 'Invoice', 'report' => 'purchase_invoice_printPDF.tpl')
			);

			$this->data['invoice_id'] = $invoice_id;

			if ($invoice_info['invoice_no']) {
				$this->data['invoice_no'] = $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];
			} else {
				$this->data['invoice_no'] = '';
			}

			$this->data['store_name']         = $invoice_info['store_name'];
			$this->data['store_url']          = $invoice_info['store_url'];
			$this->data['supplier_invoice_no'] = $invoice_info['supplier_invoice_no'];

			if ($invoice_info['supplier_id']) {
				$this->data['supplier'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $invoice_info['supplier_id'], 'SSL');
			} else {
				$this->data['supplier'] = '';
			}

			$this->data['email']           = $invoice_info['email'];
			$this->data['telephone']       = $invoice_info['telephone'];
			$this->data['fax']             = $invoice_info['fax'];
			$this->data['company']         = $invoice_info['company'];
			$this->data['date_added']      = date($this->language->get('date_format_short'), strtotime($invoice_info['date_added']));
			$this->data['date_modified']   = date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified']));
			$this->data['comment']         = nl2br($invoice_info['comment']);
			$this->data['shipping_method'] = $invoice_info['shipping_method'];
			$this->data['payment_method']  = $invoice_info['payment_method'];
			$this->data['total']           = $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']);
			$this->data['credit']          = ($invoice_info['total'] < 0) ? $invoice_info['total'] : 0;

			$this->load->model('localisation/invoice_status');
			$invoice_status_info = $this->model_localisation_invoice_status->getInvoiceStatus($invoice_info['invoice_status_id']);
			$this->data['invoice_status'] = $invoice_status_info ? $invoice_status_info['name'] : '';

			$this->data['payment_company']    = $invoice_info['payment_company'];
			$this->data['payment_company_id'] = $invoice_info['payment_company_id'];
			$this->data['payment_tax_id']     = $invoice_info['payment_tax_id'];
			$this->data['payment_address_1']  = $invoice_info['payment_address_1'];
			$this->data['payment_address_2']  = $invoice_info['payment_address_2'];
			$this->data['payment_city']       = $invoice_info['payment_city'];
			$this->data['payment_postcode']   = $invoice_info['payment_postcode'];
			$this->data['payment_zone']       = $invoice_info['payment_zone'];
			$this->data['payment_zone_code']  = $invoice_info['payment_zone_code'];
			$this->data['payment_country']    = $invoice_info['payment_country'];
			$this->data['shipping_company']   = $invoice_info['shipping_company'];
			$this->data['shipping_address_1'] = $invoice_info['shipping_address_1'];
			$this->data['shipping_address_2'] = $invoice_info['shipping_address_2'];
			$this->data['shipping_city']      = $invoice_info['shipping_city'];
			$this->data['shipping_postcode']  = $invoice_info['shipping_postcode'];
			$this->data['shipping_zone']      = $invoice_info['shipping_zone'];
			$this->data['shipping_zone_code'] = $invoice_info['shipping_zone_code'];
			$this->data['shipping_country']   = $invoice_info['shipping_country'];

			$this->data['products'] = array();
			$products = $this->model_purchase_invoice->getInvoiceProducts($invoice_id);
			foreach ($products as $product) {
				$option_data = array();
				$options     = $this->model_purchase_invoice->getInvoiceOptions($invoice_id, $product['invoice_product_id']);
				foreach ($options as $option) {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
						'type'  => $option['type']
					);
				}
				$this->data['products'][] = array(
					'invoice_product_id' => $product['invoice_product_id'],
					'product_id'         => $product['product_id'],
					'name'               => $product['name'],
					'model'              => $product['model'],
					'option'             => $option_data,
					'quantity'           => $product['quantity'],
					'price'              => $this->currency->format($product['price']),
					'total'              => $this->currency->format($product['total']),
					'href'               => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$this->data['totals']           = $this->model_purchase_invoice->getInvoiceTotals($invoice_id);
			$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();
			$this->data['invoice_status_id'] = $invoice_info['invoice_status_id'];
			$this->data['email']            = $invoice_info['email'];
			$this->data['credit_total']     = 0;

			$this->template = 'purchase/purchase_invoice_info.tpl';
			$this->children  = array('common/header', 'common/footer');
			$this->response->setOutput($this->render());
		} else {
			$this->load->language('error/not_found');
			$this->document->setTitle($this->language->get('heading_title'));
			$this->data['heading_title']  = $this->language->get('heading_title');
			$this->data['text_not_found'] = $this->language->get('text_not_found');
			$this->data['breadcrumbs']    = array();
			$this->data['breadcrumbs'][]  = array('text' => $this->language->get('text_home'), 'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'), 'separator' => false);
			$this->template = 'error/not_found.tpl';
			$this->children  = array('common/header', 'common/footer');
			$this->response->setOutput($this->render());
		}
	}

	public function history() {
		$this->language->load('purchase/invoice');
		$this->data['error']   = '';
		$this->data['success'] = '';
		$this->load->model('purchase/invoice');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'purchase/invoice')) {
				$this->data['error'] = $this->language->get('error_permission');
			}
			if (!$this->data['error']) {
				$this->model_purchase_invoice->addInvoiceHistory($this->request->get['invoice_id'], $this->request->post);
				$this->data['success'] = $this->language->get('text_success');
			}
		}

		$this->data['text_no_results']    = $this->language->get('text_no_results');
		$this->data['column_date_added']  = $this->language->get('column_date_added');
		$this->data['column_status']      = $this->language->get('column_status');
		$this->data['column_notify']      = $this->language->get('column_notify');
		$this->data['column_comment']     = $this->language->get('column_comment');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$this->data['histories'] = array();
		$results = $this->model_purchase_invoice->getInvoiceHistories($this->request->get['invoice_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total     = $this->model_purchase_invoice->getTotalInvoiceHistories($this->request->get['invoice_id']);
		$pagination        = new Pagination();
		$pagination->total = $history_total;
		$pagination->page  = $page;
		$pagination->limit = 10;
		$pagination->text  = $this->language->get('text_pagination');
		$pagination->url   = $this->url->link('purchase/invoice/history', 'token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'purchase/purchase_invoice_history.tpl';
		$this->response->setOutput($this->render());
	}

	public function invoice() {
		$lcFormat = isset($this->request->get['format']) ? $this->request->get['format'] : '';

		$this->data['lang']  = $this->config->get('config_language');
		$this->load->language('purchase/invoice');

		$this->data['title']     = $this->language->get('heading_title');
		$this->data['base']      = (isset($this->request->server['HTTPS']) && in_array($this->request->server['HTTPS'], array('on', '1'))) ? HTTPS_SERVER : HTTP_SERVER;
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language']  = $this->language->get('code');

		foreach (array('text_invoice','text_invoice_id','text_invoice_no','text_invoice_date','text_date_added','text_telephone','text_fax','text_email','text_nif','text_to','text_company_id','text_tax_id','text_ship_to','text_payment_method','text_shipping_method','text_invoice_details') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		foreach (array('column_product','column_image','column_model','column_quantity','column_price','column_total','column_comment') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->load->model('purchase/invoice');
		$this->load->model('setting/setting');

		$invoice_design = $this->model_setting_setting->getSetting('invoice_design');
		$this->data['header_html'] = isset($invoice_design['header_html']) ? $invoice_design['header_html'] : '';
		$this->data['footer_html'] = isset($invoice_design['footer_html']) ? $invoice_design['footer_html'] : '';

		$this->data['invoices'] = array();

		$invoices = array();
		if (isset($this->request->post['selected'])) {
			$invoices = $this->request->post['selected'];
		} elseif (isset($this->request->get['invoice_id'])) {
			$invoices[] = $this->request->get['invoice_id'];
		}
		$invoices = array_unique($invoices);

		$lcReport = isset($this->request->post['report']) ? $this->request->post['report'] : (isset($this->request->get['report']) ? $this->request->get['report'] : '');

		foreach ($invoices as $invoice_id) {
			$invoice_info = $this->model_purchase_invoice->getInvoice($invoice_id);

			if ($invoice_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $invoice_info['store_id']);

				if ($store_info) {
					$store_address   = $store_info['config_address'];
					$store_email     = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax       = isset($store_info['config_fax']) ? $store_info['config_fax'] : '';
				} else {
					$store_address   = $this->config->get('config_address');
					$store_email     = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax       = (string)$this->config->get('config_fax');
				}

				$store_nif = $this->config->get('config_vat_id');
				if (!$store_nif) $store_nif = $this->config->get('config_nif');

				if ($invoice_info['invoice_no']) {
					$invoice_no = $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				// Shipping address format
				$format  = $invoice_info['shipping_address_format'] ?: '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				$find    = array('{company}','{address_1}','{address_2}','{city}','{postcode}','{zone}','{zone_code}','{country}');
				$replace = array($invoice_info['shipping_company'],$invoice_info['shipping_address_1'],$invoice_info['shipping_address_2'],$invoice_info['shipping_city'],$invoice_info['shipping_postcode'],$invoice_info['shipping_zone'],$invoice_info['shipping_zone_code'],$invoice_info['shipping_country']);
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				// Payment address format
				$format  = $invoice_info['payment_address_format'] ?: '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				$replace = array($invoice_info['payment_company'],$invoice_info['payment_address_1'],$invoice_info['payment_address_2'],$invoice_info['payment_city'],$invoice_info['payment_postcode'],$invoice_info['payment_zone'],$invoice_info['payment_zone_code'],$invoice_info['payment_country']);
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$payment_tax_id = $invoice_info['payment_tax_id'];

				if ((!$payment_address || !$payment_tax_id) && $invoice_info['customer_id']) {
					$this->load->model('sale/customer');
					$customer_general = $this->model_sale_customer->getCustomer($invoice_info['customer_id']);
					if (!empty($customer_general)) {
						if (!$payment_address && isset($customer_general['address']) && $customer_general['address']) {
							$customer_postcode = $customer_general['postcode'];
							if (preg_match('/^(\d{2})(\d{3})$/', $customer_postcode, $postcode_match)) {
								$customer_postcode = $postcode_match[1] . '.' . $postcode_match[2];
							}
							$payment_address = trim($customer_general['address']) . '<br />' . trim($customer_postcode . ' ' . $customer_general['city']);
						}
						if (!$payment_tax_id && isset($customer_general['nif'])) {
							$payment_tax_id = $customer_general['nif'];
						}
					}
				}

				$product_data = array();
				$products     = $this->model_purchase_invoice->getInvoiceProducts($invoice_id);
				foreach ($products as $product) {
					$option_data = array();
					$options     = $this->model_purchase_invoice->getInvoiceOptions($invoice_id, $product['invoice_product_id']);
					foreach ($options as $option) {
						$value         = ($option['type'] != 'file') ? $option['value'] : utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						$option_data[] = array('name' => $option['name'], 'value' => $value);
					}
					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'image'    => ($product['image'] == '' ? 'no_image.jpg' : $product['image']),
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price']),
						'total'    => $this->currency->format($product['total'])
					);
				}

				$total_data = $this->model_purchase_invoice->getInvoiceTotals($invoice_id);

				$this->data['invoices'][] = array(
					'invoice_id'         => $invoice_id,
					'invoice_no'         => $invoice_no,
					'invoice_prefix'     => $invoice_info['invoice_prefix'],
					'date_added'         => date($this->language->get('date_format_short'), strtotime($invoice_info['date_added'])),
					'store_name'         => $invoice_info['store_name'],
					'store_url'          => rtrim($invoice_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'store_nif'          => $store_nif,
					'email'              => $invoice_info['email'],
					'telephone'          => $invoice_info['telephone'],
					'shipping_address'   => $shipping_address,
					'payment_address'    => $payment_address,
					'payment_company'    => $invoice_info['payment_company'] ? $invoice_info['payment_company'] : $invoice_info['company'],
					'payment_company_id' => $invoice_info['payment_company_id'],
					'payment_tax_id'     => $payment_tax_id,
					'payment_method'     => $invoice_info['payment_method'],
					'shipping_method'    => $invoice_info['shipping_method'],
					'product'            => $product_data,
					'total'              => $total_data,
					'comment'            => nl2br($invoice_info['comment'])
				);
			}
		}

		$this->data['logo'] = $this->config->get('config_logo');

		if ($lcFormat == 'pdf') {
			$this->renderPDF('purchase/purchase_invoice_printPDF.tpl', 'pdf', 'purchase_invoice', $invoice_id);
		} elseif ($lcFormat == 'email') {
			$this->renderPDF('purchase/purchase_invoice_printPDF.tpl', 'email', 'purchase_invoice', $invoice_id);

			$json = array();

			if ($this->request->post['to'] == '' || filter_var($this->request->post['to'], FILTER_VALIDATE_EMAIL) == false) {
				$json['error']['to'] = $this->language->get('error_to');
			}
			if ($this->request->post['subject'] == '') {
				$json['error']['subject'] = $this->language->get('error_subject');
			}
			if ($this->request->post['message'] == '') {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (empty($json['error'])) {
				$data['to']      = $this->request->post['to'];
				$data['subject'] = $this->request->post['subject'];
				$data['text']    = $this->request->post['message'];
				$data['file']    = DIR_DOWNLOAD . 'purchase_invoice_' . $invoice_id . '.pdf';
				$this->sendnewmail($data['to'], $data['subject'], $data['text'], $data['file']);
				$json['success'] = $this->language->get('text_success_email');
			}

			$this->response->setOutput(json_encode($json));
		} else {
			$this->template = 'purchase/purchase_invoice_printPDF.tpl';
			$this->response->setOutput($this->render());
		}
	}

	public function uploadDoc() {
		ob_start();

		$this->load->language('purchase/invoice');

		$json = array();

		if (!$this->user->hasPermission('modify', 'purchase/invoice')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (empty($this->request->get['invoice_id'])) {
			$json['error'] = 'Missing invoice_id';
		} elseif (!isset($_FILES['doc']) || empty($_FILES['doc']['tmp_name'])) {
			$json['error'] = 'No file received (check PHP upload_max_filesize / post_max_size)';
		} elseif (!is_uploaded_file($_FILES['doc']['tmp_name'])) {
			$json['error'] = 'Invalid upload';
		} else {
			$this->load->model('purchase/invoice');
			$invoice_info = $this->model_purchase_invoice->getInvoice((int)$this->request->get['invoice_id']);

			if (!$invoice_info) {
				$json['error'] = 'Invoice not found';
			} else {
				$sid  = (int)$invoice_info['supplier_id'];
				$sino = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $invoice_info['supplier_invoice_no']);
				$ext  = strtolower(pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION));

				$base = rtrim(str_replace('\\', '/', realpath(dirname(DIR_APPLICATION))), '/');
				$dir  = $base . '/docs/suppliers/' . date('Y') . '/';

				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}

				foreach ((array)glob($dir . $sid . '_' . $sino . '.*') as $old) {
					@unlink($old);
				}

				$filename = $sid . '_' . $sino . '.' . $ext;

				if (move_uploaded_file($_FILES['doc']['tmp_name'], $dir . $filename)) {
					$json['success'] = true;
					$json['filename'] = $filename;
				} else {
					$json['error'] = 'move_uploaded_file failed. Dir: ' . $dir . ' File: ' . $filename;
				}
			}
		}

		ob_end_clean();

		$this->response->addheader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
