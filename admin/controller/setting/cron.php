<?php
class ControllerSettingCron extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('setting/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		$this->getList();
	}

	public function insert() {
		$this->load->language('setting/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_cron->addCron($this->request->post);

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

			$this->response->redirect($this->url->link('setting/cron', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('setting/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_cron->editCron($this->request->post);

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

			$this->response->redirect($this->url->link('setting/cron', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('setting/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $cron_id) {
				$this->model_setting_cron->deleteCron($cron_id);
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

			$this->response->redirect($this->url->link('setting/cron', 'token=' . $this->session->data['token'] . $url));
		}

		$this->getList();
	}

	public function getList() {
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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'])
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . $url)
		);
		
		$this->data['insert'] = $this->url->link('setting/cron/insert', 'token=' . $this->session->data['token'] . $url);
		$this->data['delete'] = $this->url->link('setting/cron/delete', 'token=' . $this->session->data['token'] . $url);
		$this->data['cron'] = $this->url->link('setting/cron');

		$this->data['crons'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$cron_total = $this->model_setting_cron->getTotalCrons();

		$results = $this->model_setting_cron->getCrons($filter_data);

		foreach ($results as $result) {
			$this->data['crons'][] = array(
				'cron_id'       => $result['cron_id'],
				'code'          => $result['code'],
				'cycle'         => $this->language->get('text_' . $result['cycle']),
				'action'        => $result['action'],
				'status'        => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'enabled'       => $result['status']
			);
		}

		$this->data['token'] = $this->session->data['token'];

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

		if (isset($this->request->post['selected'])) {
			$this->data['selected'] = (array)$this->request->post['selected'];
		} else {
			$this->data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_code'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . '&sort=code' . $url);
		$this->data['sort_cycle'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . '&sort=cycle' . $url);
		$this->data['sort_action'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . '&sort=action' . $url);
		$this->data['sort_status'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . '&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url);
		$this->data['sort_date_modified'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . '&sort=date_modified' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_disable'] = $this->language->get('button_disable');
		$this->data['button_enable'] = $this->language->get('button_enable');
		$this->data['button_run'] = $this->language->get('button_run');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_cycle'] = $this->language->get('column_cycle');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['entry_cron'] = $this->language->get('entry_cron');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_cron_1'] = $this->language->get('text_cron_1');
		$this->data['text_cron_2'] = $this->language->get('text_cron_2');
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->template = 'setting/cron.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_action'] = $this->language->get('entry_action');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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
			'href'      => $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['cron_id'])) {
			$this->data['action'] = $this->url->link('setting/cron/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('setting/cron/update', 'token=' . $this->session->data['token'] . '&cron_id=' . $this->request->get['cron_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('setting/cron', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['cron_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cron_info = $this->model_setting_cron->getCron($this->request->get['cron_id']);
		}

		if (isset($this->request->post[''])) {
			$this->data['cron_action'] = $this->request->post['cron_action'];
		} elseif (!empty($cron_info)) {
			$this->data['cron_action'] = $cron_info['action'];
		} else {
			$this->data['cron_action'] = '';
		}

		if (isset($this->request->post['cron_status'])) {
			$this->data['cron_status'] = $this->request->post['cron_status'];
		} elseif (!empty($cron_info)) {
			$this->data['cron_status'] = $cron_info['status'];
		} else {
			$this->data['cron_status'] = '';
		}

		$this->template = 'setting/cron_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/cron')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function run() {
		$this->load->language('setting/cron');

		$json = array();

		if (isset($this->request->get['cron_id'])) {
			$cron_id = $this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'setting/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$cron_info = $this->model_setting_cron->getCron($cron_id);

			if ($cron_info) {
				$this->load->controller($cron_info['action'], $cron_id, $cron_info['code'], $cron_info['cycle'], $cron_info['date_added'], $cron_info['date_modified']);

				$this->model_setting_cron->editCron($cron_info['cron_id']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function enable() {
		$this->load->language('setting/cron');

		$json = array();

		if (isset($this->request->get['cron_id'])) {
			$cron_id = $this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'setting/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$this->model_setting_cron->editStatus($cron_id, 1);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable() {
		$this->load->language('setting/cron');

		$json = array();

		if (isset($this->request->get['cron_id'])) {
			$cron_id = $this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'setting/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$this->model_setting_cron->editStatus($cron_id, 0);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->model('setting/setting');

		$settings['module_cron_status'] = 1;

		$this->model_setting_setting->editSetting('module_cron', $settings);

		$this->load->model('setting/cron');

		$this->model_setting_cron->install();
	}

	public function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/cron')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}