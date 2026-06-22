<?php
class ControllerToolsInvoiceDesign extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tools/invoice_design');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// Uses its own setting group (not 'config') because
			// ModelSettingSetting::editSetting() deletes every key in the
			// group before re-inserting - reusing 'config' here would wipe
			// out the whole store configuration.
			$this->model_setting_setting->editSetting('invoice_design', array(
				'header_html' => $this->request->post['header_html'],
				'footer_html' => $this->request->post['footer_html']
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('tools/invoice_design', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getForm() {
		$this->load->model('setting/setting');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_header'] = $this->language->get('entry_header');
		$this->data['entry_footer'] = $this->language->get('entry_footer');
		$this->data['help_header'] = $this->language->get('help_header');
		$this->data['help_footer'] = $this->language->get('help_footer');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tools/invoice_design', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('tools/invoice_design', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');

		$design = $this->model_setting_setting->getSetting('invoice_design');

		if (isset($this->request->post['header_html'])) {
			$this->data['header_html'] = $this->request->post['header_html'];
		} elseif (isset($design['header_html'])) {
			$this->data['header_html'] = $design['header_html'];
		} else {
			$this->data['header_html'] = '';
		}

		if (isset($this->request->post['footer_html'])) {
			$this->data['footer_html'] = $this->request->post['footer_html'];
		} elseif (isset($design['footer_html'])) {
			$this->data['footer_html'] = $design['footer_html'];
		} else {
			$this->data['footer_html'] = '';
		}

		$this->template = 'tools/invoice_design_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'tools/invoice_design')) {
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
