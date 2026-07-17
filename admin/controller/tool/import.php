<?php
class ControllerToolImport extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tool/import');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/import') && $this->validate()) {
			$file = $this->request->files['file'];

			require_once(DIR_SYSTEM . 'library/xlsx.php');

			$xlsx = new Xlsx();
			$rows = $xlsx->read($file['tmp_name']);

			if ($rows === false) {
				$this->error['warning'] = $this->language->get('error_file');
			} else {
				$this->load->model('tool/import');

				$result = $this->model_tool_import->importProducts($rows);

				$this->session->data['success'] = sprintf($this->language->get('text_success'), $result['imported'], $result['updated']);

				if ($result['errors']) {
					$this->session->data['import_errors'] = $result['errors'];
				}

				$this->redirect($this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->getForm();
	}

	public function template() {
		require_once(DIR_SYSTEM . 'library/xlsx.php');

		$xlsx = new Xlsx();

		$xlsx->setHeaders(array('Modelo', 'Nombre', 'Descripcion', 'Precio', 'Cantidad', 'Estado'));
		$xlsx->addRow(array('REF-001', 'Producto de ejemplo', 'Descripcion del producto', '19.99', '100', '1'));

		$content = $xlsx->build('Productos');

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->response->addheader('Content-Disposition: attachment; filename=plantilla_productos.xlsx');
		$this->response->addheader('Content-Transfer-Encoding: binary');
		$this->response->addheader('Content-Length: ' . strlen($content));

		$this->response->setOutput($content);
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_form'] = $this->language->get('text_form');
		$this->data['text_example'] = $this->language->get('text_example');
		$this->data['text_example_help'] = $this->language->get('text_example_help');

		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_file'] = $this->language->get('entry_file');

		$this->data['text_type_product'] = $this->language->get('text_type_product');
		$this->data['text_type_customer'] = $this->language->get('text_type_customer');
		$this->data['text_type_supplier'] = $this->language->get('text_type_supplier');

		$this->data['button_import'] = $this->language->get('button_import');
		$this->data['button_template'] = $this->language->get('button_template');

		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_status'] = $this->language->get('column_status');

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
		$this->data['template'] = $this->url->link('tool/import/template', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'tool/import.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'tool/import')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->files['file']['name']) || !is_uploaded_file($this->request->files['file']['tmp_name'])) {
			$this->error['warning'] = $this->language->get('error_upload');
		} elseif (strtolower(pathinfo($this->request->files['file']['name'], PATHINFO_EXTENSION)) != 'xlsx') {
			$this->error['warning'] = $this->language->get('error_extension');
		}

		return !$this->error;
	}
}
