<?php
class ControllerAccountingMod303 extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('accounting/mod303');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/mod303');

		$this->getList();
	}

	public function insert() {
		$this->language->load('accounting/mod303');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/mod303');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_mod303->addAccount($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('accounting/mod303', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('accounting/mod303');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/mod303');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_mod303->editAccount($this->request->get['ctab10_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('accounting/mod303', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('accounting/mod303');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/mod303');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ctab10_id) {
				$this->model_accounting_mod303->deleteAccount($ctab10_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('accounting/mod303', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('accounting/mod303', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('accounting/mod303/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('accounting/mod303/delete', 'token=' . $this->session->data['token'], 'SSL');

		$results = $this->model_accounting_mod303->getAccounts(array());

		$this->data['accounts'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('accounting/mod303/update', 'token=' . $this->session->data['token'] . '&ctab10_id=' . $result['ctab10_id'], 'SSL')
			);

			$this->data['accounts'][] = array(
				'ctab10_id'  => $result['ctab10_id'],
				'code'       => $result['code'],
				'name'       => $result['name'],
				'order_code' => $result['order_code'],
				'level'      => $result['level'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['ctab10_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_code']       = $this->language->get('column_code');
		$this->data['column_name']       = $this->language->get('column_name');
		$this->data['column_order_code'] = $this->language->get('column_order_code');
		$this->data['column_action']     = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_new');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'accounting/mod303_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_code']       = $this->language->get('entry_code');
		$this->data['entry_order_code'] = $this->language->get('entry_order_code');
		$this->data['entry_name']       = $this->language->get('entry_name');
		$this->data['entry_accounts']   = $this->language->get('entry_accounts');
		$this->data['entry_level']      = $this->language->get('entry_level');
		$this->data['entry_list_after'] = $this->language->get('entry_list_after');

		$this->data['help_accounts'] = $this->language->get('help_accounts');
		$this->data['help_level']    = $this->language->get('help_level');

		$this->data['button_save']   = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('accounting/mod303', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['ctab10_id'])) {
			$this->data['action'] = $this->url->link('accounting/mod303/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('accounting/mod303/update', 'token=' . $this->session->data['token'] . '&ctab10_id=' . $this->request->get['ctab10_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('accounting/mod303', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['ctab10_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$account_info = $this->model_accounting_mod303->getAccount($this->request->get['ctab10_id']);
		}

		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (!empty($account_info)) {
			$this->data['code'] = $account_info['code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->request->post['order_code'])) {
			$this->data['order_code'] = $this->request->post['order_code'];
		} elseif (!empty($account_info)) {
			$this->data['order_code'] = $account_info['order_code'];
		} else {
			$this->data['order_code'] = '';
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($account_info)) {
			$this->data['name'] = $account_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['accounts'])) {
			$this->data['accounts_field'] = $this->request->post['accounts'];
		} elseif (!empty($account_info)) {
			$this->data['accounts_field'] = $account_info['accounts'];
		} else {
			$this->data['accounts_field'] = '';
		}

		if (isset($this->request->post['level'])) {
			$this->data['level'] = $this->request->post['level'];
		} elseif (!empty($account_info)) {
			$this->data['level'] = $account_info['level'];
		} else {
			$this->data['level'] = 0;
		}

		if (isset($this->request->post['list_after'])) {
			$this->data['list_after'] = $this->request->post['list_after'];
		} elseif (!empty($account_info)) {
			$this->data['list_after'] = $account_info['list_after'];
		} else {
			$this->data['list_after'] = '';
		}

		$this->template = 'accounting/mod303_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'accounting/mod303')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['code']) < 1) || (utf8_strlen($this->request->post['code']) > 12)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->error) {
			$account_info = $this->model_accounting_mod303->getAccountByCode($this->request->post['code']);

			if ($account_info && (!isset($this->request->get['ctab10_id']) || $account_info['ctab10_id'] != $this->request->get['ctab10_id'])) {
				$this->error['code'] = $this->language->get('error_code_exists');
			}
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'accounting/mod303')) {
			$this->error['warning'] = $this->language->get('error_permission');

			return false;
		}

		return true;
	}
}
