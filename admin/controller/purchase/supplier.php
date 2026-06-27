<?php

class ControllerPurchaseSupplier extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		$this->getList();
	}

	public function insert() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_supplier->addSupplier($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function update() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_purchase_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function delete() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_purchase_supplier->deleteSupplier($supplier_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function export() {
		$this->load->language('purchase/supplier');

		if (!$this->user->hasPermission('access', 'purchase/supplier') || empty($this->request->post['selected'])) {
			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('purchase/supplier');

		$suppliers = $this->model_purchase_supplier->getSuppliersByIds($this->request->post['selected']);

		require_once(DIR_SYSTEM . 'library/xlsx.php');

		$xlsx = new Xlsx();

		$xlsx->setHeaders(array(
			$this->language->get('column_company'),
			$this->language->get('column_name'),
			$this->language->get('column_email'),
			$this->language->get('column_telephone'),
			$this->language->get('column_status'),
			$this->language->get('column_date_added')
		));

		foreach ($suppliers as $supplier) {
			$xlsx->addRow(array(
				$supplier['company'],
				trim($supplier['firstname'] . ' ' . $supplier['lastname']),
				$supplier['email'],
				$supplier['telephone'],
				$supplier['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				date($this->language->get('date_format_short'), strtotime($supplier['date_added']))
			));
		}

		$this->response->addHeader('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->response->addHeader('Content-Disposition: attachment; filename="suppliers_' . date('Y-m-d') . '.xlsx"');
		$this->response->setOutput($xlsx->build($this->language->get('heading_title')));
	}

	public function autocomplete() {
		$this->load->model('purchase/supplier');

		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '';

		$json = array();

		if ($filter_name) {
			$results = $this->model_purchase_supplier->autocomplete($filter_name);

			foreach ($results as $result) {
				$json[] = array(
					'supplier_id' => $result['supplier_id'],
					'name'        => $result['company'],
					'email'       => $result['email'],
					'telephone'   => $result['telephone'],
					'fax'         => $result['fax']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	protected function getList() {
		$filter_company = isset($this->request->get['filter_company']) ? $this->request->get['filter_company'] : null;
		$filter_email = isset($this->request->get['filter_email']) ? $this->request->get['filter_email'] : null;
		$filter_status = isset($this->request->get['filter_status']) ? $this->request->get['filter_status'] : null;

		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'company';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$url = '';

		foreach (array('filter_company', 'filter_email', 'filter_status', 'sort', 'order', 'page') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . (($key == 'filter_company' || $key == 'filter_email') ? urlencode(html_entity_decode($this->request->get[$key], ENT_QUOTES, 'UTF-8')) : $this->request->get[$key]);
			}
		}

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['insert'] = $this->url->link('purchase/supplier/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('purchase/supplier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['export'] = $this->url->link('purchase/supplier/export', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data = array(
			'filter_company' => $filter_company,
			'filter_email'   => $filter_email,
			'filter_status'  => $filter_status,
			'sort'           => $sort,
			'order'          => $order,
			'start'          => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'          => $this->config->get('config_admin_limit')
		);

		$supplier_total = $this->model_purchase_supplier->getTotalSuppliers($data);

		$results = $this->model_purchase_supplier->getSuppliers($data);

		$this->data['suppliers'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'href'  => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL'),
				'icon'  => 'fas fa-edit',
				'color' => 'default'
			);

			$this->data['suppliers'][] = array(
				'supplier_id' => $result['supplier_id'],
				'company'     => $result['company'],
				'name'        => trim($result['firstname'] . ' ' . $result['lastname']),
				'email'       => $result['email'],
				'telephone'   => $result['telephone'],
				'status'      => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['supplier_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['column_company'] = $this->language->get('column_company');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');

		$this->data['token'] = $this->session->data['token'];

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['sort_company'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=company' . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=email' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $supplier_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_company'] = $filter_company;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'purchase/supplier_list.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_web'] = $this->language->get('entry_web');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_notes'] = $this->language->get('tab_notes');
		$this->data['tab_contacts'] = $this->language->get('tab_contacts');
		$this->data['tab_contracts'] = $this->language->get('tab_contracts');

		$this->data['column_contact_name'] = $this->language->get('column_contact_name');
		$this->data['column_contact_email'] = $this->language->get('column_contact_email');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_article'] = $this->language->get('column_article');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_end_support'] = $this->language->get('column_end_support');
		$this->data['column_comment'] = $this->language->get('column_comment');
		$this->data['column_user'] = $this->language->get('column_user');
		$this->data['column_date'] = $this->language->get('column_date');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_contact'] = $this->language->get('button_add_contact');
		$this->data['button_add_contract'] = $this->language->get('button_add_contract');
		$this->data['button_add_note'] = $this->language->get('button_add_note');

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_company'] = isset($this->error['company']) ? $this->error['company'] : '';
		$this->data['error_email'] = isset($this->error['email']) ? $this->error['email'] : '';

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			)
		);

		if (!isset($this->request->get['supplier_id'])) {
			$this->data['action'] = $this->url->link('purchase/supplier/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$supplier_info = $this->model_purchase_supplier->getSupplier($this->request->get['supplier_id']);
		} else {
			$supplier_info = array();
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['supplier_id'] = isset($this->request->get['supplier_id']) ? $this->request->get['supplier_id'] : 0;

		$fields = array('firstname', 'lastname', 'company', 'company_id', 'tax_id', 'email', 'telephone', 'fax', 'web', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id');

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($supplier_info)) {
				$this->data[$field] = $supplier_info[$field];
			} else {
				$this->data[$field] = '';
			}
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($supplier_info)) {
			$this->data['status'] = $supplier_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->data['contacts'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getSupplierContacts($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$link = $this->url->link('purchase/supplier/updateContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['supplier_contacts_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-default" href="' . $link . '"><i class="fa fa-edit"></i><span class="hidden-xs"> ' . $this->language->get('text_edit') . '</span></a>'
				);

				$link = $this->url->link('purchase/supplier/deleteContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['supplier_contacts_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-danger" href="' . $link . '"><i class="fa fa-trash"></i><span class="hidden-xs"> ' . $this->language->get('text_delete') . '</span></a>'
				);

				$this->data['contacts'][] = array(
					'contact_id' => $result['supplier_contacts_id'],
					'name'       => $result['cname'],
					'email'      => $result['cemail'],
					'telephone'  => $result['ctelef1'],
					'action'     => $action
				);
			}
		}

		$this->data['add_contact'] = $this->url->link('purchase/supplier/insertContact', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		$this->data['notes'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getSupplierNotes($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$note_info = $this->model_purchase_supplier->getSupplierNote($result['supplier_history_id']);

				$this->data['notes'][] = array(
					'note_id' => $result['supplier_history_id'],
					'date'    => date($this->language->get('date_format_short'), strtotime($note_info['date_added'])),
					'user'    => $note_info['user'],
					'comment' => nl2br($note_info['comment']),
					'delete'  => $this->url->link('purchase/supplier/deleteNote', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'] . '&note_id=' . $result['supplier_history_id'], 'SSL')
				);
			}
		}

		$this->data['add_note'] = $this->url->link('purchase/supplier/insertNote', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		$this->data['contracts'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getSupplierContracts($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$link = $this->url->link('purchase/supplier/updateContract', 'token=' . $this->session->data['token'] . '&contracts_id=' . $result['contracts_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-default" href="' . $link . '"><i class="fa fa-edit"></i> <span class="hidden-xs">' . $this->language->get('text_edit') . '</span></a>'
				);

				$link = $this->url->link('purchase/supplier/deleteContract', 'token=' . $this->session->data['token'] . '&contracts_id=' . $result['contracts_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-danger" href="' . $link . '"><i class="fa fa-trash"></i> <span class="hidden-xs">' . $this->language->get('text_delete') . '</span></a>'
				);

				$this->load->model('catalog/product');

				if ($result['narticulo'] > 0) {
					$product = $this->model_catalog_product->getProduct($result['narticulo']);
					$product_name = $product['name'];
				} else {
					$product_name = '';
				}

				$this->data['contracts'][] = array(
					'contracts_id' => $result['contracts_id'],
					'product'      => $product_name,
					'quantity'     => $result['quantity'],
					'end_support'  => date($this->language->get('date_format_short'), strtotime($result['dfinsoport'])),
					'action'       => $action
				);
			}
		}

		$this->data['add_contract'] = $this->url->link('purchase/supplier/insertContract', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		$this->template = 'purchase/supplier_form.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['company']) < 1) || (utf8_strlen($this->request->post['company']) > 92)) {
			$this->error['company'] = $this->language->get('error_company');
		}

		if ($this->request->post['email'] && !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function insertContact() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateContactForm()) {
			$this->model_purchase_supplier->addSupplierContact($this->request->post, $this->request->get['supplier_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getContactForm();
	}

	public function updateContact() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateContactForm()) {
			$this->model_purchase_supplier->editSupplierContact($this->request->post, $this->request->get['contact_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getContactForm();
	}

	public function deleteContact() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->get['contact_id']) && $this->validateDelete()) {
			$this->model_purchase_supplier->deleteSupplierContact($this->request->get['contact_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getForm();
	}

	private function validateContactForm() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (utf8_strlen($this->request->post['name']) < 3 || utf8_strlen($this->request->post['name']) > 50) {
			$this->error['name'] = $this->language->get('error_contact_name');
		}

		return !$this->error;
	}

	protected function getContactForm() {
		$this->data['heading_title'] = $this->language->get('heading_contact');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_telephone2'] = $this->language->get('entry_telephone2');
		$this->data['entry_puesto'] = $this->language->get('entry_puesto');
		$this->data['entry_notas'] = $this->language->get('entry_notas');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'),
				'separator' => ' :: '
			)
		);

		if (isset($this->request->get['contact_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$contact_info = $this->model_purchase_supplier->getSupplierContact($this->request->get['contact_id']);
		}

		if (isset($this->request->get['contact_id'])) {
			$this->data['contact_id'] = $this->request->get['contact_id'];
		} else {
			$this->data['contact_id'] = 0;
		}

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($contact_info)) {
			$this->data['name'] = $contact_info['cname'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (isset($contact_info)) {
			$this->data['email'] = $contact_info['cemail'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['telef1'])) {
			$this->data['telef1'] = $this->request->post['telef1'];
		} elseif (isset($contact_info)) {
			$this->data['telef1'] = $contact_info['ctelef1'];
		} else {
			$this->data['telef1'] = '';
		}

		if (isset($this->request->post['telef2'])) {
			$this->data['telef2'] = $this->request->post['telef2'];
		} elseif (isset($contact_info)) {
			$this->data['telef2'] = $contact_info['ctelef2'];
		} else {
			$this->data['telef2'] = '';
		}

		if (isset($this->request->post['puesto'])) {
			$this->data['puesto'] = $this->request->post['puesto'];
		} elseif (isset($contact_info)) {
			$this->data['puesto'] = $contact_info['cpuesto'];
		} else {
			$this->data['puesto'] = '';
		}

		if (isset($this->request->post['notas'])) {
			$this->data['notas'] = $this->request->post['notas'];
		} elseif (isset($contact_info)) {
			$this->data['notas'] = $contact_info['mnotas'];
		} else {
			$this->data['notas'] = '';
		}

		if ($this->data['contact_id'] == 0) {
			$this->data['action'] = $this->url->link('purchase/supplier/insertContact', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/supplier/updateContact', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . '&contact_id=' . $this->data['contact_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');

		$this->template = 'purchase/supplier_contacts.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insertContract() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_purchase_supplier->addSupplierContract($this->request->post, $this->request->get['supplier_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getContractForm();
	}

	public function updateContract() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_purchase_supplier->editSupplierContract($this->request->post, $this->request->get['contracts_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . '&contracts_id=' . $this->request->get['contracts_id'], 'SSL'));
		}

		$this->getContractForm();
	}

	public function deleteContract() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->get['contracts_id']) && $this->validateDelete()) {
			$this->model_purchase_supplier->deleteSupplierContract($this->request->get['contracts_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getContractForm() {
		$this->load->model('purchase/supplier');

		$this->data['heading_title'] = $this->language->get('heading_title_contract');

		$this->data['entry_article'] = $this->language->get('entry_article');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_end_support'] = $this->language->get('entry_end_support');
		$this->data['entry_notes'] = $this->language->get('entry_notes');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'),
				'separator' => ' :: '
			)
		);

		if (isset($this->request->get['contracts_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$contract_info = $this->model_purchase_supplier->getSupplierContract($this->request->get['contracts_id']);
		}

		if (isset($this->request->get['contracts_id'])) {
			$this->data['contracts_id'] = $this->request->get['contracts_id'];
		} else {
			$this->data['contracts_id'] = 0;
		}

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$this->load->model('catalog/product');
		$this->data['products'] = $this->model_catalog_product->getProducts();

		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (isset($contract_info)) {
			$this->data['product_id'] = $contract_info['narticulo'];
		} else {
			$this->data['product_id'] = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$this->data['quantity'] = $this->request->post['quantity'];
		} elseif (isset($contract_info)) {
			$this->data['quantity'] = $contract_info['quantity'];
		} else {
			$this->data['quantity'] = 1;
		}

		if (isset($this->request->post['date_purchased'])) {
			$this->data['date_purchased'] = $this->request->post['date_purchased'];
		} elseif (isset($contract_info)) {
			$this->data['date_purchased'] = $contract_info['dcompra'];
		} else {
			$this->data['date_purchased'] = '';
		}

		if (isset($this->request->post['end_support'])) {
			$this->data['end_support'] = $this->request->post['end_support'];
		} elseif (isset($contract_info)) {
			$this->data['end_support'] = $contract_info['dfinsoport'];
		} else {
			$this->data['end_support'] = '';
		}

		if (isset($this->request->post['notes'])) {
			$this->data['notes'] = $this->request->post['notes'];
		} elseif (isset($contract_info)) {
			$this->data['notes'] = $contract_info['mnotas'];
		} else {
			$this->data['notes'] = '';
		}

		$this->data['contract_statuses'] = $this->model_purchase_supplier->getSupplierContractStatus();

		if (isset($this->request->post['contract_status_id'])) {
			$this->data['contract_status_id'] = $this->request->post['contract_status_id'];
		} elseif (isset($contract_info)) {
			$this->data['contract_status_id'] = $contract_info['contract_status'];
		} else {
			$this->data['contract_status_id'] = 0;
		}

		if ($this->data['contracts_id'] == 0) {
			$this->data['action'] = $this->url->link('purchase/supplier/insertContract', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/supplier/updateContract', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . '&contracts_id=' . $this->data['contracts_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');

		$this->template = 'purchase/supplier_contract.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insertNote() {
		$this->load->language('purchase/supplier');

		$this->load->model('purchase/supplier');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_purchase_supplier->addSupplierNote($this->request->post, $this->request->get['supplier_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getNoteForm();
	}

	public function deleteNote() {
		$this->load->language('purchase/supplier');

		$this->load->model('purchase/supplier');

		if (isset($this->request->get['note_id']) && $this->validateDelete()) {
			$this->model_purchase_supplier->deleteSupplierNote($this->request->get['note_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getNoteForm() {
		$this->load->model('purchase/supplier');

		$this->data['heading_title'] = $this->language->get('heading_title_note');

		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_date_note'] = $this->language->get('entry_date_note');
		$this->data['entry_user'] = $this->language->get('entry_user');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} else {
			$this->data['date_added'] = date('Y-m-d');
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} else {
			$this->data['user_id'] = $this->user->getId();
		}

		if (isset($this->request->post['user_name'])) {
			$this->data['user_name'] = $this->request->post['user_name'];
		} else {
			$this->data['user_name'] = $this->user->getUserName();
		}

		$this->data['action'] = $this->url->link('purchase/supplier/insertNote', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		$this->data['cancel'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');

		$this->template = 'purchase/supplier_note_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
