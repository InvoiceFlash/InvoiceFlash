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
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

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
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

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

		$fields = array('firstname', 'lastname', 'company', 'company_id', 'tax_id', 'email', 'telephone', 'fax', 'web', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id', 'comment');

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
}
?>
