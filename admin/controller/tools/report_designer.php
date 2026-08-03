<?php
class ControllerToolsReportDesigner extends Controller {

	private $routes = array(
		'quote'    => 'sale/quote',
		'order'    => 'sale/order',
		'delivery' => 'sale/delivery',
		'invoice'  => 'sale/invoice'
	);

	private $id_params = array(
		'quote'    => 'quote_id',
		'order'    => 'order_id',
		'delivery' => 'delivery_id',
		'invoice'  => 'invoice_id'
	);

	public function index() {
		$this->load->language('tools/report_designer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select_document'] = $this->language->get('text_select_document');
		$this->data['text_existing_formats'] = $this->language->get('text_existing_formats');
		$this->data['text_new_format_name'] = $this->language->get('text_new_format_name');
		$this->data['text_active'] = $this->language->get('text_active');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_no_formats'] = $this->language->get('text_no_formats');
		$this->data['text_confirm_delete'] = $this->language->get('text_confirm_delete');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_create_edit'] = $this->language->get('button_create_edit');
		$this->data['button_edit'] = $this->language->get('button_edit');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_close'] = $this->language->get('button_close');

		$this->data['types'] = array(
			array('type' => 'quote', 'name' => $this->language->get('text_type_quote')),
			array('type' => 'order', 'name' => $this->language->get('text_type_order')),
			array('type' => 'delivery', 'name' => $this->language->get('text_type_delivery')),
			array('type' => 'invoice', 'name' => $this->language->get('text_type_invoice'))
		);

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tools/report_designer', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['token'] = $this->session->data['token'];
		// url->link() siempre devuelve '&amp;' (pensado para markup HTML); estas
		// URLs se usan dentro de <script> como cadenas JS, donde el navegador
		// NO decodifica entidades, así que hay que hacerlo a mano o el '&amp;'
		// literal rompe el parseo de query string (el token se pierde).
		$this->data['get_formats'] = html_entity_decode($this->url->link('tools/report_designer/getFormats', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['create_format'] = html_entity_decode($this->url->link('tools/report_designer/createFormat', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['edit_format'] = html_entity_decode($this->url->link('tools/report_designer/edit', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['delete_format'] = html_entity_decode($this->url->link('tools/report_designer/deleteFormat', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');

		$this->template = 'tools/report_designer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function getFormats() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$json = array();

		$type = isset($this->request->get['type']) ? $this->request->get['type'] : '';

		if (!$this->model_tools_report_designer->isValidType($type)) {
			$json['error'] = $this->language->get('error_type');
		} else {
			$active_id = $this->model_tools_report_designer->getActiveFormatId($type);

			$formats = array();

			foreach ($this->model_tools_report_designer->getFormats($type) as $format) {
				$formats[] = array(
					'report_format_id' => (int)$format['report_format_id'],
					'name'             => $format['name'],
					'is_default'       => (bool)$format['is_default'],
					'is_active'        => ($active_id == $format['report_format_id'])
				);
			}

			$json['formats'] = $formats;
			$json['edit_url'] = html_entity_decode($this->url->link('tools/report_designer/edit', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8') . '&report_format_id=';
		}

		$this->response->setOutput(json_encode($json));
	}

	public function createFormat() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tools/report_designer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$type = isset($this->request->post['type']) ? $this->request->post['type'] : '';
			$source_format_id = isset($this->request->post['source_format_id']) ? (int)$this->request->post['source_format_id'] : 0;
			$name = isset($this->request->post['name']) ? trim($this->request->post['name']) : '';

			if (!$this->model_tools_report_designer->isValidType($type)) {
				$json['error'] = $this->language->get('error_type');
			} elseif ($name === '') {
				$json['error'] = $this->language->get('error_name');
			} else {
				$source = $this->model_tools_report_designer->getFormat($source_format_id);

				if (!$source || $source['type'] != $type) {
					$json['error'] = $this->language->get('error_source');
				} else {
					$report_format_id = $this->model_tools_report_designer->addFormat($type, $name, $source['html_content'], $this->user->getId());

					$json['success'] = $this->language->get('text_success_created');
					$json['report_format_id'] = $report_format_id;
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function edit() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$report_format_id = isset($this->request->get['report_format_id']) ? (int)$this->request->get['report_format_id'] : 0;

		$format = $this->model_tools_report_designer->getFormat($report_format_id);

		if (!$format || $format['is_default']) {
			$this->redirect($this->url->link('tools/report_designer', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_activate'] = $this->language->get('button_activate');
		$this->data['button_preview'] = $this->language->get('button_preview');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_active'] = $this->language->get('text_active');
		$this->data['text_no_document_to_preview'] = $this->language->get('text_no_document_to_preview');
		$this->data['text_merge_tags_hint'] = $this->language->get('text_merge_tags_hint');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tools/report_designer', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['format_name'] = $format['name'];
		$this->data['report_format_id'] = (int)$format['report_format_id'];
		$this->data['type'] = $format['type'];
		$this->data['html_content'] = $format['html_content'];
		$this->data['merge_tags'] = $this->model_tools_report_designer->getMergeTagKeys($format['type']);

		$active_id = $this->model_tools_report_designer->getActiveFormatId($format['type']);
		$this->data['is_active'] = ($active_id == $format['report_format_id']);

		$this->data['cancel'] = $this->url->link('tools/report_designer', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['save'] = html_entity_decode($this->url->link('tools/report_designer/save', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['activate'] = html_entity_decode($this->url->link('tools/report_designer/activate', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');
		$this->data['preview'] = html_entity_decode($this->url->link('tools/report_designer/preview', 'token=' . $this->session->data['token'], 'SSL'), ENT_QUOTES, 'UTF-8');

		$this->template = 'tools/report_designer_edit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function save() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tools/report_designer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$report_format_id = isset($this->request->post['report_format_id']) ? (int)$this->request->post['report_format_id'] : 0;
			$html_content = isset($this->request->post['html_content']) ? $this->model_tools_report_designer->decodeHtmlInput($this->request->post['html_content']) : '';

			$format = $this->model_tools_report_designer->getFormat($report_format_id);

			if (!$format) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($format['is_default']) {
				$json['error'] = $this->language->get('error_is_default');
			} else {
				$this->model_tools_report_designer->editFormatHtml($report_format_id, $html_content);

				$json['success'] = $this->language->get('text_success_saved');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function activate() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tools/report_designer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$report_format_id = isset($this->request->post['report_format_id']) ? (int)$this->request->post['report_format_id'] : 0;

			$format = $this->model_tools_report_designer->getFormat($report_format_id);

			if (!$format) {
				$json['error'] = $this->language->get('error_not_found');
			} else {
				$this->model_tools_report_designer->setActiveFormat($format['type'], $report_format_id);

				$json['success'] = $this->language->get('text_success_activated');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function deleteFormat() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tools/report_designer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$report_format_id = isset($this->request->post['report_format_id']) ? (int)$this->request->post['report_format_id'] : 0;

			$format = $this->model_tools_report_designer->getFormat($report_format_id);

			if (!$format) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($format['is_default']) {
				$json['error'] = $this->language->get('error_is_default');
			} elseif ($this->model_tools_report_designer->getActiveFormatId($format['type']) == $report_format_id) {
				$json['error'] = $this->language->get('error_is_active');
			} else {
				$this->model_tools_report_designer->deleteFormat($report_format_id);

				$json['success'] = $this->language->get('text_success_deleted');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	// Guarda primero (si el formato no es is_default) para que la vista
	// previa refleje exactamente lo que hay en CKEditor ahora mismo, y
	// devuelve la URL del PDF real generado con ese contenido.
	public function preview() {
		$this->load->language('tools/report_designer');
		$this->load->model('tools/report_designer');

		$json = array();

		$report_format_id = isset($this->request->post['report_format_id']) ? (int)$this->request->post['report_format_id'] : 0;

		$format = $this->model_tools_report_designer->getFormat($report_format_id);

		if (!$format) {
			$json['error'] = $this->language->get('error_not_found');
		} else {
			if (!$format['is_default'] && isset($this->request->post['html_content'])) {
				$this->model_tools_report_designer->editFormatHtml($report_format_id, $this->model_tools_report_designer->decodeHtmlInput($this->request->post['html_content']));
			}

			$doc_id = $this->model_tools_report_designer->getLatestDocumentId($format['type']);

			if (!$doc_id) {
				$json['error'] = $this->language->get('text_no_document_to_preview');
			} else {
				$route = $this->routes[$format['type']];
				$id_param = $this->id_params[$format['type']];

				// window.open() usa esta URL tal cual (no es markup HTML), así
				// que hay que decodificar el '&amp;' que devuelve url->link().
				$json['url'] = html_entity_decode($this->url->link($route . '/invoice', 'token=' . $this->session->data['token'] . '&' . $id_param . '=' . $doc_id . '&format=pdf&preview_report_format_id=' . $report_format_id, 'SSL'), ENT_QUOTES, 'UTF-8');
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>
