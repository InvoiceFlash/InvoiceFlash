<?php
class ControllerCatalogAgent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/agent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/agent');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/agent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/agent');

		// Sin API KEY de la IA no se pueden crear agentes.
		if (!$this->model_catalog_agent->hasApiKey()) {
			$this->error['warning'] = $this->language->get('error_no_api_key');

			$this->getList();

			return;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_agent->addAgent($this->request->post);
			$this->model_catalog_agent->ensureCron();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/agent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/agent');

		if (!$this->model_catalog_agent->hasApiKey()) {
			$this->error['warning'] = $this->language->get('error_no_api_key');

			$this->getList();

			return;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_agent->editAgent($this->request->get['agent_id'], $this->request->post);
			$this->model_catalog_agent->ensureCron();

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/agent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/agent');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $agent_id) {
				$this->model_catalog_agent->deleteAgent($agent_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	// Ejecuta un agente ahora mismo (AJAX). Tarda lo que tarde la IA.
	public function run() {
		set_time_limit(0);

		$this->load->language('catalog/agent');
		$this->load->model('catalog/agent');

		$json = array();

		$agent_id = isset($this->request->get['agent_id']) ? (int)$this->request->get['agent_id'] : 0;

		if (!$this->user->hasPermission('modify', 'catalog/agent')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (!$this->model_catalog_agent->hasApiKey()) {
			$json['error'] = $this->language->get('error_no_api_key');
		} elseif (!$this->model_catalog_agent->getAgent($agent_id)) {
			$json['error'] = $this->language->get('error_not_found');
		} elseif ($this->model_catalog_agent->isRunning($agent_id)) {
			$json['error'] = $this->language->get('error_running');
		} else {
			$result = $this->model_catalog_agent->runAgent($agent_id, 'manual');

			if ($result['status'] == 'ok') {
				$json['success'] = $this->language->get('text_run_ok');
			} else {
				$json['error'] = $this->language->get('text_run_error') . ' ' . htmlspecialchars($result['error'], ENT_QUOTES, 'UTF-8');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Borra entradas del log de un agente: las marcadas o, con clear=1, todas.
	public function deleteRuns() {
		$this->load->language('catalog/agent');
		$this->load->model('catalog/agent');

		$agent_id = isset($this->request->get['agent_id']) ? (int)$this->request->get['agent_id'] : 0;

		if (!$this->model_catalog_agent->getAgent($agent_id)) {
			$this->redirect($this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'catalog/agent')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['clear'])) {
			$this->model_catalog_agent->deleteRuns($agent_id);

			$this->session->data['success'] = $this->language->get('text_success_runs');
		} elseif (!empty($this->request->post['selected_runs']) && is_array($this->request->post['selected_runs'])) {
			$this->model_catalog_agent->deleteRuns($agent_id, $this->request->post['selected_runs']);

			$this->session->data['success'] = $this->language->get('text_success_runs');
		} else {
			$this->session->data['error'] = $this->language->get('error_no_runs_selected');
		}

		$this->redirect($this->url->link('catalog/agent/runs', 'agent_id=' . $agent_id . '&token=' . $this->session->data['token'], 'SSL'));
	}

	// Historial de ejecuciones de un agente.
	public function runs() {
		$this->load->language('catalog/agent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/agent');

		$agent_id = isset($this->request->get['agent_id']) ? (int)$this->request->get['agent_id'] : 0;
		$agent    = $this->model_catalog_agent->getAgent($agent_id);

		if (!$agent) {
			$this->redirect($this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title') . ': ' . $agent['name'];
		$this->data['cancel']        = $this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete_runs']   = $this->url->link('catalog/agent/deleteRuns', 'agent_id=' . $agent_id . '&token=' . $this->session->data['token'], 'SSL');
		$this->data['clear_runs']    = $this->url->link('catalog/agent/deleteRuns', 'agent_id=' . $agent_id . '&clear=1&token=' . $this->session->data['token'], 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}

		foreach (array('button_cancel', 'button_delete', 'text_email_sent', 'button_clear_log', 'text_confirm_delete_runs', 'text_confirm_clear_runs', 'text_runs_title', 'text_no_runs', 'column_started', 'column_finished', 'column_run_status', 'column_trigger', 'column_summary', 'column_changes', 'text_run_status_ok', 'text_run_status_error', 'text_run_status_running', 'text_trigger_cron', 'text_trigger_manual', 'text_no_changes') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['runs'] = array();

		foreach ($this->model_catalog_agent->getRuns($agent_id, 30) as $run) {
			$changes = json_decode($run['actions'], true);

			$this->data['runs'][] = array(
				'run_id'        => $run['run_id'],
				'status'        => $run['status'],
				'trigger'       => $run['trigger_by'],
				'date_started'  => date($this->language->get('datetime_format'), strtotime($run['date_started'])),
				'date_finished' => $run['date_finished'] ? date($this->language->get('datetime_format'), strtotime($run['date_finished'])) : '',
				'summary'       => htmlspecialchars((string)$run['summary'], ENT_QUOTES, 'UTF-8'),
				'error'         => htmlspecialchars((string)$run['error'], ENT_QUOTES, 'UTF-8'),
				'actions'       => is_array($changes) ? $changes : array()
			);
		}

		$this->template = 'catalog/agent_runs.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getList() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		foreach (array('button_insert', 'button_delete', 'button_run', 'button_history', 'button_edit', 'text_list', 'text_no_results', 'text_show_all', 'text_enabled', 'text_disabled', 'text_always', 'text_run_confirm', 'text_run_error', 'column_name', 'column_description', 'column_schedule', 'column_access', 'text_access_read', 'text_access_write', 'column_status', 'column_last_run', 'column_last_result', 'column_action', 'text_cron_note') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('catalog/agent/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/agent/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['has_api_key']      = $this->model_catalog_agent->hasApiKey();
		$this->data['error_no_api_key'] = $this->language->get('error_no_api_key');

		$show_all = !empty($this->request->get['all']);

		$this->data['show_all']     = $show_all;
		$this->data['link_all']     = $this->url->link('catalog/agent', 'token=' . $this->session->data['token'] . ($show_all ? '' : '&all=1'), 'SSL');
		$this->data['text_filter']  = $show_all ? $this->language->get('text_show_active') : $this->language->get('text_show_all');

		$this->data['agents'] = array();

		foreach ($this->model_catalog_agent->getAgents(!$show_all) as $agent) {
			$last = $this->model_catalog_agent->getLastRun($agent['agent_id']);

			// Request::clean() guarda el texto ya escapado: se decodifica para recortar y se vuelve a escapar al mostrar.
			$description = strip_tags(html_entity_decode($agent['description'], ENT_QUOTES, 'UTF-8'));

			if (mb_strlen($description, 'UTF-8') > 120) {
				$description = mb_substr($description, 0, 120, 'UTF-8') . '...';
			}

			$this->data['agents'][] = array(
				'agent_id'     => $agent['agent_id'],
				'name'         => $agent['name'],
				'description'  => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
				'access'       => ($agent['access'] == 'write') ? 'write' : 'read',
				'schedule'     => ($agent['schedule_type'] == 'always') ? $this->language->get('text_schedule_always') : sprintf($this->language->get('text_schedule_time'), $agent['run_time']),
				'status'       => $agent['status'],
				'last_run'     => $agent['last_run'] ? date($this->language->get('datetime_format'), strtotime($agent['last_run'])) : '',
				'last_status'  => $last ? $last['status'] : '',
				'last_result'  => $last ? htmlspecialchars(mb_substr(($last['status'] == 'error' ? $last['error'] : $last['summary']), 0, 140, 'UTF-8'), ENT_QUOTES, 'UTF-8') : '',
				'edit'         => $this->url->link('catalog/agent/update', 'token=' . $this->session->data['token'] . '&agent_id=' . $agent['agent_id'], 'SSL'),
				'history'      => $this->url->link('catalog/agent/runs', 'token=' . $this->session->data['token'] . '&agent_id=' . $agent['agent_id'], 'SSL')
			);
		}

		$this->data['run_url'] = html_entity_decode($this->url->link('catalog/agent/run', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');

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

		$this->template = 'catalog/agent_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		foreach (array('entry_ai_provider', 'text_help_ai_provider', 'text_no_key_suffix', 'entry_name', 'entry_description', 'entry_schedule', 'entry_access', 'text_access_read', 'text_access_write', 'text_help_access', 'entry_run_time', 'entry_status', 'text_schedule_time_opt', 'text_schedule_always_opt', 'text_help_description', 'text_help_always', 'text_enabled', 'text_disabled', 'button_save', 'button_cancel') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		foreach (array('warning', 'name', 'description', 'run_time', 'ai_provider') as $key) {
			$this->data['error_' . $key] = isset($this->error[$key]) ? $this->error[$key] : '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['agent_id'])) {
			$this->data['action'] = $this->url->link('catalog/agent/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/agent/update', 'token=' . $this->session->data['token'] . '&agent_id=' . (int)$this->request->get['agent_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/agent', 'token=' . $this->session->data['token'], 'SSL');

		$info = array();

		if (isset($this->request->get['agent_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$info = $this->model_catalog_agent->getAgent($this->request->get['agent_id']);
		}

		$fields = array(
			'agent_ai_provider' => array('ai_provider', ''),
			'agent_name'        => array('name', ''),
			'agent_description' => array('description', ''),
			'agent_access'      => array('access', 'read'),
			'agent_schedule'    => array('schedule_type', 'time'),
			'agent_run_time'    => array('run_time', '08:00'),
			'agent_status'      => array('status', '1')
		);

		foreach ($fields as $key => $def) {
			if (isset($this->request->post[$key])) {
				$this->data[$key] = $this->request->post[$key];
			} elseif (!empty($info)) {
				$this->data[$key] = $info[$def[0]];
			} else {
				$this->data[$key] = $def[1];
			}
		}

		// Combo de IA: solo las que tienen API KEY. Un agente ya guardado con una IA que ahora no la tiene la conserva marcada.
		$this->data['ai_providers'] = $this->model_catalog_agent->getAvailableProviders();

		if ($this->data['agent_ai_provider'] === '' || $this->data['agent_ai_provider'] === null) {
			$primary = (string)$this->config->get('config_ai_provider');

			$this->data['agent_ai_provider'] = isset($this->data['ai_providers'][$primary]) ? $primary : (isset($this->data['ai_providers']['claude']) ? 'claude' : key($this->data['ai_providers']));
		}

		$this->data['ai_provider_missing'] = '';

		if (!isset($this->data['ai_providers'][$this->data['agent_ai_provider']])) {
			$this->data['ai_provider_missing'] = $this->model_catalog_agent->getProviderLabel($this->data['agent_ai_provider']);
		}

		$this->template = 'catalog/agent_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/agent')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$providers = $this->model_catalog_agent->getAvailableProviders();

		if (!isset($this->request->post['agent_ai_provider']) || !isset($providers[$this->request->post['agent_ai_provider']])) {
			$this->error['ai_provider'] = $this->language->get('error_ai_provider');
		}

		$name = isset($this->request->post['agent_name']) ? trim($this->request->post['agent_name']) : '';

		if (utf8_strlen($name) < 3 || utf8_strlen($name) > 128) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!isset($this->request->post['agent_description']) || trim($this->request->post['agent_description']) === '') {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!isset($this->request->post['agent_access']) || $this->request->post['agent_access'] != 'write') {
			$this->request->post['agent_access'] = 'read';
		}

		$schedule = isset($this->request->post['agent_schedule']) ? $this->request->post['agent_schedule'] : '';

		if ($schedule != 'time' && $schedule != 'always') {
			$this->request->post['agent_schedule'] = 'time';
			$schedule                              = 'time';
		}

		if ($schedule == 'time' && !preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', isset($this->request->post['agent_run_time']) ? $this->request->post['agent_run_time'] : '')) {
			$this->error['run_time'] = $this->language->get('error_run_time');
		}

		if ($schedule == 'always' && empty($this->request->post['agent_run_time'])) {
			$this->request->post['agent_run_time'] = '00:00';
		}

		if (!isset($this->request->post['agent_status'])) {
			$this->request->post['agent_status'] = 1;
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/agent')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
