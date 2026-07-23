<?php
class ControllerToolImport extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tool/import');

		$this->document->setTitle($this->language->get('heading_title'));

		$type = isset($this->request->post['type']) ? $this->request->post['type'] : 'product';

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/import') && $this->validate($type)) {
			if ($type == 'saconta') {
				$this->load->model('tool/import');

				$result = $this->model_tool_import->importSaconta(trim($this->request->post['path']));

				$this->session->data['success'] = sprintf($this->language->get('text_success_saconta'), $result['ctab6'], $result['ctab61'], $result['customers'], $result['ctab8']);

				if ($result['errors']) {
					$this->session->data['import_errors'] = $result['errors'];
				}

				$this->redirect($this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'));
			} elseif ($type == 'flash_gestion') {
				$this->load->model('tool/import');

				$options = array(
					'product'      => !empty($this->request->post['import_product']),
					'customer'     => !empty($this->request->post['import_customer']),
					'supplier'     => !empty($this->request->post['import_supplier']),
					'company_code' => isset($this->request->post['company_code']) ? trim($this->request->post['company_code']) : ''
				);

				$result = $this->model_tool_import->importFlashGestion(trim($this->request->post['path']), $options);

				$this->session->data['success'] = sprintf($this->language->get('text_success_flash_gestion'), $result['products'], $result['customers'], $result['suppliers']);

				if ($result['errors']) {
					$this->session->data['import_errors'] = $result['errors'];
				}

				$this->redirect($this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$file = $this->request->files['file'];

				require_once(DIR_SYSTEM . 'library/xlsx.php');

				$xlsx = new Xlsx();
				$rows = $xlsx->read($file['tmp_name']);

				if ($rows === false) {
					$this->error['warning'] = $this->language->get('error_file');
				} else {
					$this->load->model('tool/import');

					if ($type == 'customer') {
						$result = $this->model_tool_import->importCustomers($rows);
					} elseif ($type == 'supplier') {
						$result = $this->model_tool_import->importSuppliers($rows);
					} else {
						$result = $this->model_tool_import->importProducts($rows);
					}

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $result['imported'], $result['updated']);

					if ($result['errors']) {
						$this->session->data['import_errors'] = $result['errors'];
					}

					$this->redirect($this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}
		}

		$this->getForm();
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_form'] = $this->language->get('text_form');
		$this->data['text_example'] = $this->language->get('text_example');
		$this->data['text_example_help'] = $this->language->get('text_example_help');
		$this->data['text_saconta_help'] = $this->language->get('text_saconta_help');
		$this->data['text_flash_gestion_help'] = $this->language->get('text_flash_gestion_help');

		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_file'] = $this->language->get('entry_file');
		$this->data['entry_path'] = $this->language->get('entry_path');
		$this->data['entry_related_history_code'] = $this->language->get('entry_related_history_code');
		$this->data['entry_company_code'] = $this->language->get('entry_company_code');

		$this->data['text_type_product'] = $this->language->get('text_type_product');
		$this->data['text_type_customer'] = $this->language->get('text_type_customer');
		$this->data['text_type_supplier'] = $this->language->get('text_type_supplier');
		$this->data['text_type_saconta'] = $this->language->get('text_type_saconta');
		$this->data['text_type_flash_gestion'] = $this->language->get('text_type_flash_gestion');

		$this->data['button_import'] = $this->language->get('button_import');

		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_status'] = $this->language->get('column_status');

		$this->data['column_company'] = $this->language->get('column_company');
		$this->data['column_nif'] = $this->language->get('column_nif');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_address'] = $this->language->get('column_address');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_postcode'] = $this->language->get('column_postcode');
		$this->data['column_country'] = $this->language->get('column_country');

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

		if (isset($this->session->data['import_errors'])) {
			$this->data['import_errors'] = $this->session->data['import_errors'];

			unset($this->session->data['import_errors']);
		} else {
			$this->data['import_errors'] = array();
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'tool/import.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate($type) {
		if (!$this->user->hasPermission('modify', 'tool/import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($type == 'saconta') {
			$path = isset($this->request->post['path']) ? trim($this->request->post['path']) : '';

			if ($path === '' || !is_dir($path) || !is_readable($path)) {
				$this->error['warning'] = $this->language->get('error_path');
			}
		} elseif ($type == 'flash_gestion') {
			$path = isset($this->request->post['path']) ? trim($this->request->post['path']) : '';

			if ($path === '' || !is_dir($path) || !is_readable($path)) {
				$this->error['warning'] = $this->language->get('error_path');
			} elseif (empty($this->request->post['import_product']) && empty($this->request->post['import_customer']) && empty($this->request->post['import_supplier'])) {
				$this->error['warning'] = $this->language->get('error_nothing_selected');
			}
		} elseif (empty($this->request->files['file']['name']) || !is_uploaded_file($this->request->files['file']['tmp_name'])) {
			$this->error['warning'] = $this->language->get('error_upload');
		} elseif (strtolower(pathinfo($this->request->files['file']['name'], PATHINFO_EXTENSION)) != 'xlsx') {
			$this->error['warning'] = $this->language->get('error_extension');
		}

		return !$this->error;
	}
}
