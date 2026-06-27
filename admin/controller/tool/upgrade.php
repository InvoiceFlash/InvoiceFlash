<?php
class ControllerToolUpgrade extends Controller {
	public function index() {
		$this->language->load('tool/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/upgrade');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/upgrade', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_check'] = $this->language->get('button_check');
		$this->data['button_upgrade'] = $this->language->get('button_upgrade');
		$this->data['text_current_commit'] = $this->language->get('text_current_commit');
		$this->data['text_latest_commit'] = $this->language->get('text_latest_commit');
		$this->data['text_up_to_date'] = $this->language->get('text_up_to_date');
		$this->data['text_update_available'] = $this->language->get('text_update_available');
		$this->data['text_view_changes'] = $this->language->get('text_view_changes');
		$this->data['text_loading'] = $this->language->get('text_loading');
		$this->data['text_confirm'] = $this->language->get('text_confirm_upgrade');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$status = $this->model_tool_upgrade->getStatus();

		$this->data['current_commit'] = $status['current_commit'] ? substr($status['current_commit'], 0, 7) : '-';
		$this->data['latest_commit'] = $status['latest_commit'] ? substr($status['latest_commit'], 0, 7) : '-';
		$this->data['has_update'] = ($status['latest_commit'] && $status['current_commit'] && ($status['latest_commit'] != $status['current_commit']));
		$this->data['compare_url'] = $this->model_tool_upgrade->getCompareUrl($status);
		$this->data['check_url'] = $this->url->link('tool/upgrade/check', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['upgrade_url'] = $this->url->link('tool/upgrade/upgrade', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['can_upgrade'] = $this->user->hasPermission('modify', 'tool/upgrade');

		$this->template = 'tool/upgrade.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function check() {
		$this->load->model('tool/upgrade');

		$this->model_tool_upgrade->check();

		$status = $this->model_tool_upgrade->getStatus();

		$this->response->addHeader('Content-Type: application/json');

		$this->response->setOutput(json_encode(array(
			'current_commit' => $status['current_commit'] ? substr($status['current_commit'], 0, 7) : '-',
			'latest_commit'  => $status['latest_commit'] ? substr($status['latest_commit'], 0, 7) : '-',
			'has_update'     => ($status['latest_commit'] && $status['current_commit'] && ($status['latest_commit'] != $status['current_commit']))
		)));
	}

	public function upgrade() {
		$this->language->load('tool/upgrade');

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('tool/upgrade');

			$result = $this->model_tool_upgrade->upgrade();

			if ($result['success']) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['error'] = $result['error'];
			}
		}

		$this->redirect($this->url->link('tool/upgrade', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>
