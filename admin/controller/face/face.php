<?php
class ControllerFaceFace extends Controller {
	private $error = array();

	private $setting_keys = array(
		'face_active',
		'face_environment',
		'face_email'
	);

	// Listado de facturas enviadas a FACe y su estado de tramitacion.
	public function index() {
		$this->language->load('face/face');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('face/face');

		$filter_status = isset($this->request->get['filter_status']) ? (string)$this->request->get['filter_status'] : '';
		$page = isset($this->request->get['page']) ? max(1, (int)$this->request->get['page']) : 1;
		$limit = (int)$this->config->get('config_admin_limit') ? (int)$this->config->get('config_admin_limit') : 20;

		$this->data['heading_title'] = $this->language->get('heading_title');

		foreach (array('text_no_results', 'text_all', 'text_confirm_resend', 'text_confirm_cancel', 'text_prompt_reason', 'column_invoice', 'column_customer', 'column_date', 'column_total', 'column_registry', 'column_environment', 'column_status', 'column_action', 'entry_status', 'button_setting', 'button_refresh', 'button_refresh_all', 'button_resend', 'button_cancel_invoice', 'button_xml', 'button_filter') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['statuses'] = $this->getStatuses();

		$this->data['filter_status'] = $filter_status;
		$this->data['can_modify'] = $this->user->hasPermission('modify', 'face/face') && $this->user->hasPermission('modify', 'sale/invoice');

		$this->data['warnings'] = array();

		$requirements = $this->model_face_face->requirementsError();

		if ($requirements) {
			$this->data['warnings'][] = $requirements;
		}

		if ($this->model_face_face->isActive()) {
			$configuration = $this->model_face_face->configurationError();

			if ($configuration) {
				$this->data['warnings'][] = $configuration;
			}
		} else {
			$this->data['warnings'][] = $this->language->get('text_inactive');
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['breadcrumbs'] = $this->getBreadcrumbs();

		$token = $this->session->data['token'];

		$this->data['setting'] = $this->url->link('face/face/setting', 'token=' . $token, 'SSL');
		$this->data['filter_action'] = str_replace('&amp;', '&', $this->url->link('face/face', 'token=' . $token, 'SSL'));
		$this->data['send_url'] = str_replace('&amp;', '&', $this->url->link('sale/invoice/faceSend', 'token=' . $token, 'SSL'));
		$this->data['refresh_url'] = str_replace('&amp;', '&', $this->url->link('face/face/refresh', 'token=' . $token, 'SSL'));
		$this->data['cancel_url'] = str_replace('&amp;', '&', $this->url->link('face/face/cancel', 'token=' . $token, 'SSL'));

		$filter = array(
			'filter_status' => $filter_status,
			'start'         => ($page - 1) * $limit,
			'limit'         => $limit
		);

		$this->data['records'] = array();

		foreach ($this->model_face_face->getRecords($filter) as $record) {
			$customer = $record['payment_company'] ? $record['payment_company'] : trim($record['firstname'] . ' ' . $record['lastname']);
			$invoice_no = $record['invoice_no'] ? $record['invoice_no'] : $record['invoice_id'];
			$registered = $record['status'] == ModelFaceFace::STATUS_REGISTERED;

			$this->data['records'][] = array(
				'invoice_id'  => $record['invoice_id'],
				'number'      => $record['invoice_prefix'] . $invoice_no,
				'customer'    => $customer,
				'date'        => $record['date_added'] ? date($this->language->get('date_format_short'), strtotime($record['date_added'])) : '',
				'total'       => $this->currency->format($record['total'], $this->config->get('config_currency'), '', true, true),
				'registry'    => $record['registry_number'],
				'dir3'        => trim($record['management_body'] . ' / ' . $record['processing_unit'] . ' / ' . $record['accounting_office'], ' /'),
				'environment' => $this->language->get($record['production'] ? 'text_production' : 'text_test'),
				'registered'  => $registered,
				'badge'       => $this->badge($record),
				'status_text' => $registered ? ($record['tramit_text'] !== '' ? $record['tramit_text'] : $this->language->get('text_status_pending')) : $this->language->get('text_status_error'),
				'reason'      => nl2br(htmlspecialchars($registered ? $record['tramit_reason'] : $record['message'], ENT_QUOTES, 'UTF-8')),
				'cancel_text' => $registered ? $record['cancel_text'] : '',
				'can_cancel'  => $registered && $record['tramit_code'] != ModelFaceFace::TRAMIT_CANCELED && $record['tramit_code'] != ModelFaceFace::TRAMIT_REJECTED,
				'can_resend'  => !$registered,
				'invoice'     => $this->url->link('sale/invoice/info', 'token=' . $token . '&invoice_id=' . $record['invoice_id'], 'SSL'),
				'xml'         => $this->url->link('face/face/xml', 'token=' . $token . '&invoice_id=' . $record['invoice_id'], 'SSL')
			);
		}

		$pagination = new Pagination();
		$pagination->total = $this->model_face_face->getTotalRecords($filter);
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('face/face', 'token=' . $token . ($filter_status !== '' ? '&filter_status=' . urlencode($filter_status) : '') . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'face/face_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function setting() {
		$this->language->load('face/face');

		$this->document->setTitle($this->language->get('heading_setting'));

		$this->load->model('setting/setting');
		$this->load->model('face/face');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateSetting()) {
			$data = array();

			foreach ($this->setting_keys as $key) {
				$data[$key] = isset($this->request->post[$key]) ? trim((string)$this->request->post[$key]) : '';
			}

			$this->model_setting_setting->editSetting('face', $data);

			$this->session->data['success'] = $this->language->get('text_success_setting');

			$this->redirect($this->url->link('face/face', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_setting');

		foreach (array('text_yes', 'text_no', 'text_test', 'text_production', 'text_certificate_note', 'text_email_note', 'text_dir3_note', 'text_requirements_ok', 'entry_active', 'entry_environment', 'entry_email', 'button_save', 'button_cancel', 'button_test') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['requirements'] = $this->model_face_face->requirementsError();

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_email'] = isset($this->error['email']) ? $this->error['email'] : '';

		$this->data['breadcrumbs'] = $this->getBreadcrumbs();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_setting'),
			'href'      => $this->url->link('face/face/setting', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('face/face/setting', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('face/face', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['test_url'] = str_replace('&amp;', '&', $this->url->link('face/face/test', 'token=' . $this->session->data['token'], 'SSL'));

		$defaults = array(
			'face_environment' => 'test',
			'face_email'       => $this->config->get('config_email')
		);

		foreach ($this->setting_keys as $key) {
			if (isset($this->request->post[$key])) {
				$this->data[$key] = $this->request->post[$key];
			} elseif ($this->config->get($key) !== null) {
				$this->data[$key] = $this->config->get($key);
			} else {
				$this->data[$key] = isset($defaults[$key]) ? $defaults[$key] : '';
			}
		}

		$this->template = 'face/face_setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// Prueba el certificado y la conexion con el entorno guardado en Ajustes.
	public function test() {
		$this->language->load('face/face');

		$this->load->model('face/face');

		$json = array();

		if (!$this->user->hasPermission('modify', 'face/face')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$result = $this->model_face_face->testConnection();

			if ($result['success']) {
				$json['success'] = $result['message'];
			} else {
				$json['error'] = $result['message'];
			}
		}

		$this->output($json);
	}

	// Consulta el estado de una factura (invoice_id) o de todas las pendientes.
	public function refresh() {
		$this->language->load('face/face');

		$this->load->model('face/face');

		$json = array();

		if (!$this->user->hasPermission('modify', 'face/face')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (!empty($this->request->post['invoice_id'])) {
			$result = $this->model_face_face->refresh((int)$this->request->post['invoice_id']);

			if ($result['success']) {
				$json['success'] = $result['message'];
			} else {
				$json['error'] = $result['message'];
			}
		} else {
			$json['success'] = sprintf($this->language->get('text_success_refresh_all'), $this->model_face_face->refreshPending());
		}

		$this->output($json);
	}

	public function cancel() {
		$this->language->load('face/face');

		$this->load->model('face/face');

		$json = array();

		if (!$this->user->hasPermission('modify', 'face/face')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$invoice_id = isset($this->request->post['invoice_id']) ? (int)$this->request->post['invoice_id'] : 0;
			$reason = isset($this->request->post['reason']) ? html_entity_decode((string)$this->request->post['reason'], ENT_QUOTES, 'UTF-8') : '';

			$result = $this->model_face_face->cancel($invoice_id, $reason);

			if ($result['success']) {
				$json['success'] = $result['message'];
			} else {
				$json['error'] = $result['message'];
			}
		}

		$this->output($json);
	}

	// Descarga del XML Facturae firmado tal como se envio a FACe.
	public function xml() {
		$this->load->model('face/face');

		$invoice_id = isset($this->request->get['invoice_id']) ? (int)$this->request->get['invoice_id'] : 0;

		$record = $this->user->hasPermission('access', 'face/face') ? $this->model_face_face->getRecord($invoice_id) : array();

		if (!$record || $record['signed_xml'] === '') {
			$this->redirect($this->url->link('face/face', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->response->addHeader('Content-Type: application/xml; charset=utf-8');
		$this->response->addHeader('Content-Disposition: attachment; filename="facturae_' . (int)$invoice_id . '.xml"');
		$this->response->setOutput($record['signed_xml']);
	}

	private function output($json) {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Codigos de tramitacion de FACe (consultarEstados) para el filtro del listado.
	private function getStatuses() {
		return array(
			ModelFaceFace::STATUS_ERROR => $this->language->get('text_status_error'),
			'1200'                      => $this->language->get('text_status_1200'),
			'1300'                      => $this->language->get('text_status_1300'),
			'2400'                      => $this->language->get('text_status_2400'),
			'2500'                      => $this->language->get('text_status_2500'),
			'2600'                      => $this->language->get('text_status_2600'),
			'3100'                      => $this->language->get('text_status_3100')
		);
	}

	private function badge($record) {
		if ($record['status'] != ModelFaceFace::STATUS_REGISTERED) {
			return 'bg-danger text-white';
		}

		switch ($record['tramit_code']) {
			case ModelFaceFace::TRAMIT_PAID:
				return 'bg-success text-white';
			case ModelFaceFace::TRAMIT_REJECTED:
				return 'bg-danger text-white';
			case ModelFaceFace::TRAMIT_CANCELED:
				return 'bg-secondary text-white';
			default:
				return 'bg-info text-dark';
		}
	}

	private function validateSetting() {
		if (!$this->user->hasPermission('modify', 'face/face')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$email = isset($this->request->post['face_email']) ? trim((string)$this->request->post['face_email']) : '';

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	private function getBreadcrumbs() {
		return array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('face/face', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			)
		);
	}
}
