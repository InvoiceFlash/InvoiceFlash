<?php
class ControllerToolSpecialOperations extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tool/special_operations');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->getForm();
	}

	public function wipe() {
		$this->language->load('tool/special_operations');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateWipe()) {
			$this->load->model('tool/special_operations');

			$total = $this->model_tool_special_operations->wipeData();

			$this->load->model('tool/user_logs');

			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'delete',
				'document_type' => 'special_operations_wipe',
				'document_id'   => 0,
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));

			$this->session->data['success'] = sprintf($this->language->get('text_success'), $total);
		} else {
			$this->session->data['error_warning'] = !empty($this->error['warning']) ? $this->error['warning'] : $this->language->get('error_permission');
		}

		$this->redirect($this->url->link('tool/special_operations', 'token=' . $this->session->data['token'], 'SSL'));
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_delete_data']    = $this->language->get('text_delete_data');
		$this->data['text_warning_keep']   = $this->language->get('text_warning_keep');
		$this->data['text_warning_delete'] = $this->language->get('text_warning_delete');

		$this->data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];

			unset($this->session->data['error_warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/special_operations', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('tool/special_operations/wipe', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token']  = $this->session->data['token'];

		$this->template = 'tool/special_operations.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateWipe() {
		if (!$this->user->hasPermission('modify', 'tool/special_operations')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
