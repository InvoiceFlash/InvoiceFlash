<?php
class ControllerToolImport extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tool/import');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/import') && $this->validate()) {
			$file = $this->request->files['file'];
			$type = isset($this->request->post['type']) ? $this->request->post['type'] : 'product';

			require_once(DIR_SYSTEM . 'library/xlsx.php');

			$xlsx = new Xlsx();
			$rows = $xlsx->read($file['tmp_name']);

			if ($rows === false) {
				$this->error['warning'] = $this->language->get('error_file');
			} else {
				$this->load->model('tool/import');

				if ($type == 'customer') {
					$result = $this->model_tool_import->importCustomers($rows);
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

		$this->getForm();
	}

	public function template() {
		require_once(DIR_SYSTEM . 'library/xlsx.php');

		$type = isset($this->request->get['type']) ? $this->request->get['type'] : 'product';

		$xlsx = new Xlsx();

		if ($type == 'customer') {
			$xlsx->setHeaders(array('Empresa', 'NIF/CIF', 'Email', 'Telefono', 'Direccion', 'Ciudad', 'Codigo Postal'));
			$xlsx->addRow(array('Empresa de Ejemplo SL', 'B12345678', 'contacto@ejemplo.com', '600000000', 'Calle Mayor 1', 'Madrid', '28001'));

			$content = $xlsx->build('Clientes');
			$filename = 'plantilla_clientes.xlsx';
		} else {
			$xlsx->setHeaders(array('Codigo Articulo', 'Descripcion', 'Precio', 'Cantidad', 'Estado'));
			$xlsx->addRow(array('REF-001', 'Descripcion del producto de ejemplo', '19.99', '100', '1'));

			$content = $xlsx->build('Productos');
			$filename = 'plantilla_productos.xlsx';
		}

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->response->addheader('Content-Disposition: attachment; filename=' . $filename);
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
		$this->data['template_product'] = $this->url->link('tool/import/template', 'token=' . $this->session->data['token'] . '&type=product', 'SSL');
		$this->data['template_customer'] = $this->url->link('tool/import/template', 'token=' . $this->session->data['token'] . '&type=customer', 'SSL');

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
