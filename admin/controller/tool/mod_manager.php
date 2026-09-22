<?php
class ControllerToolModManager extends Controller {

	public function index() {
		$this->load->language('tool/mod_manager');
		$this->load->model('tool/mod_manager');

		$this->document->setTitle($this->language->get('heading_title'));

		if (!$this->user->hasPermission('access', 'tool/mod_manager')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_no_mods'] = $this->language->get('text_no_mods');
		$this->data['text_confirm_clear_cache'] = $this->language->get('text_confirm_clear_cache');

		$this->data['column_id'] = $this->language->get('column_id');
		$this->data['column_file'] = $this->language->get('column_file');
		$this->data['column_version'] = $this->language->get('column_version');
		$this->data['column_author'] = $this->language->get('column_author');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['button_enable'] = $this->language->get('button_enable');
		$this->data['button_disable'] = $this->language->get('button_disable');
		$this->data['button_clear_cache'] = $this->language->get('button_clear_cache');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/mod_manager', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['mods'] = $this->model_tool_mod_manager->getMods();

		$this->data['toggle_url'] = $this->url->link('tool/mod_manager/toggle', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clear_cache_url'] = $this->url->link('tool/mod_manager/clearCache', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'tool/mod_manager_list.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function toggle() {
		$this->load->language('tool/mod_manager');
		$this->load->model('tool/mod_manager');

		if (!$this->user->hasPermission('modify', 'tool/mod_manager')) {
			$this->session->data['success'] = $this->language->get('error_permission');
			$this->redirect($this->url->link('tool/mod_manager', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$filename = isset($this->request->get['file']) ? $this->request->get['file'] : '';

		if ($filename && preg_match('/^[A-Za-z0-9_\-\.]+$/', $filename)) {
			if ($this->model_tool_mod_manager->toggleMod($filename)) {
				$this->session->data['success'] = $this->language->get('text_success_toggle');
			}
		}

		$this->redirect($this->url->link('tool/mod_manager', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function clearCache() {
		$this->load->language('tool/mod_manager');
		$this->load->model('tool/mod_manager');

		if (!$this->user->hasPermission('modify', 'tool/mod_manager')) {
			$this->session->data['success'] = $this->language->get('error_permission');
			$this->redirect($this->url->link('tool/mod_manager', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$this->model_tool_mod_manager->clearCache();

		$this->session->data['success'] = $this->language->get('text_success_clear_cache');

		$this->redirect($this->url->link('tool/mod_manager', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
