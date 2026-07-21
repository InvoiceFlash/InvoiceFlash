<?php
class ControllerAccountingSubaccount extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('accounting/subaccount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/subaccount');

		$this->getList();
	}

	public function insert() {
		$this->language->load('accounting/subaccount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/subaccount');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_subaccount->addSubaccount($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('accounting/subaccount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/subaccount');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_accounting_subaccount->editSubaccount($this->request->get['ctab61_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('accounting/subaccount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('accounting/subaccount');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $ctab61_id) {
				$this->model_accounting_subaccount->deleteSubaccount($ctab61_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_code'])) {
			$filter_code = $this->request->get['filter_code'];
		} else {
			$filter_code = null;
		}

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href'      => $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('accounting/subaccount/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('accounting/subaccount/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['subaccounts'] = array();

		$data = array(
			'filter_code'  => $filter_code,
			'filter_title' => $filter_title,
			'sort'         => $sort,
			'order'        => $order,
			'start'        => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'        => $this->config->get('config_admin_limit')
		);

		$subaccount_total = $this->model_accounting_subaccount->getTotalSubaccounts($data);

		$results = $this->model_accounting_subaccount->getSubaccounts($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('accounting/subaccount/update', 'token=' . $this->session->data['token'] . '&ctab61_id=' . $result['ctab61_id'] . $url, 'SSL')
			);

			$this->data['subaccounts'][] = array(
				'ctab61_id' => $result['ctab61_id'],
				'code'      => $result['code'],
				'title'     => $result['title'],
				'debit'     => $this->currency->format($result['debit'], $this->config->get('config_currency'), '', true, true),
				'credit'    => $this->currency->format($result['credit'], $this->config->get('config_currency'), '', true, true),
				'balance'   => $this->currency->format($result['balance'], $this->config->get('config_currency'), '', true, true),
				'selected'  => isset($this->request->post['selected']) && in_array($result['ctab61_id'], $this->request->post['selected']),
				'action'    => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_debit'] = $this->language->get('column_debit');
		$this->data['column_credit'] = $this->language->get('column_credit');
		$this->data['column_balance'] = $this->language->get('column_balance');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

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

		$url = '';

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_code'] = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . '&sort=code' . $url, 'SSL');
		$this->data['sort_title'] = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . '&sort=title' . $url, 'SSL');
		$this->data['sort_debit'] = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . '&sort=debit' . $url, 'SSL');
		$this->data['sort_credit'] = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . '&sort=credit' . $url, 'SSL');
		$this->data['sort_balance'] = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . '&sort=balance' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $subaccount_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_code'] = $filter_code;
		$this->data['filter_title'] = $filter_title;

		$this->data['token'] = $this->session->data['token'];

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'accounting/subaccount_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_vat_regime'] = $this->language->get('entry_vat_regime');

		$this->data['button_save'] = $this->language->get('button_save');
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

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href'      => $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['ctab61_id'])) {
			$this->data['action'] = $this->url->link('accounting/subaccount/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('accounting/subaccount/update', 'token=' . $this->session->data['token'] . '&ctab61_id=' . $this->request->get['ctab61_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('accounting/subaccount', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['ctab61_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$subaccount_info = $this->model_accounting_subaccount->getSubaccount($this->request->get['ctab61_id']);
		}

		if (isset($this->request->post['code'])) {
			$this->data['code'] = $this->request->post['code'];
		} elseif (!empty($subaccount_info)) {
			$this->data['code'] = $subaccount_info['code'];
		} else {
			$this->data['code'] = '';
		}

		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (!empty($subaccount_info)) {
			$this->data['title'] = $subaccount_info['title'];
		} else {
			$this->data['title'] = '';
		}

		if (isset($this->request->post['vat_regime'])) {
			$this->data['vat_regime'] = $this->request->post['vat_regime'];
		} elseif (!empty($subaccount_info)) {
			$this->data['vat_regime'] = $subaccount_info['vat_regime'];
		} else {
			$this->data['vat_regime'] = '';
		}

		$this->template = 'accounting/subaccount_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'accounting/subaccount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['code']) < 1) || (utf8_strlen($this->request->post['code']) > 12)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if (!$this->error) {
			$subaccount_info = $this->model_accounting_subaccount->getSubaccountByCode($this->request->post['code']);

			if ($subaccount_info && (!isset($this->request->get['ctab61_id']) || $subaccount_info['ctab61_id'] != $this->request->get['ctab61_id'])) {
				$this->error['code'] = $this->language->get('error_code_exists');
			}
		}

		if ((utf8_strlen($this->request->post['title']) < 1) || (utf8_strlen($this->request->post['title']) > 255)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'accounting/subaccount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
