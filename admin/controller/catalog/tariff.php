<?php
class ControllerCatalogTariff extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('catalog/tariff');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/tariff');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/tariff');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/tariff');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tariff->addTariff($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/tariff', 'token=' . $this->session->data['token'] . $this->listUrl(), 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/tariff');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/tariff');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tariff->editTariff($this->request->get['tariff_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/tariff', 'token=' . $this->session->data['token'] . $this->listUrl(), 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/tariff');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/tariff');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $tariff_id) {
				$this->model_catalog_tariff->deleteTariff($tariff_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/tariff', 'token=' . $this->session->data['token'] . $this->listUrl(), 'SSL'));
		}

		$this->getList();
	}

	private function listUrl() {
		$url = '';

		foreach (array('sort', 'order', 'page') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . $this->request->get[$key];
			}
		}

		return $url;
	}

	protected function breadcrumbs() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/tariff', 'token=' . $this->session->data['token'] . $this->listUrl(), 'SSL'),
			'separator' => ' :: '
		);
	}

	protected function getList() {
		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'name';
		$order = (isset($this->request->get['order']) && $this->request->get['order'] == 'DESC') ? 'DESC' : 'ASC';
		$page = isset($this->request->get['page']) ? max(1, (int)$this->request->get['page']) : 1;
		$token = $this->session->data['token'];
		$url = $this->listUrl();

		$this->breadcrumbs();

		$this->data['insert'] = $this->url->link('catalog/tariff/insert', 'token=' . $token . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/tariff/delete', 'token=' . $token . $url, 'SSL');

		$limit = $this->config->get('config_admin_limit');

		$results = $this->model_catalog_tariff->getTariffs(array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		));

		$this->data['tariffs'] = array();

		foreach ($results as $result) {
			$this->data['tariffs'][] = array(
				'tariff_id' => $result['tariff_id'],
				'name'      => $result['name'] . ($result['is_default'] ? $this->language->get('text_default') : ''),
				'percent'   => number_format($result['percent'], 2) . ' %',
				'date_end'  => $result['date_end'] ? date($this->language->get('date_format_short'), strtotime($result['date_end'])) : '',
				'selected'  => isset($this->request->post['selected']) && in_array($result['tariff_id'], $this->request->post['selected']),
				'action'    => array(array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('catalog/tariff/update', 'token=' . $token . '&tariff_id=' . $result['tariff_id'] . $url, 'SSL'),
					'icon' => '<i class="fa fa-edit"></i>'
				))
			);
		}

		foreach (array('heading_title', 'text_no_results', 'column_name', 'column_percent', 'column_date_end', 'column_action', 'button_insert', 'button_delete') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$flip = ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		$this->data['sort_name'] = $this->url->link('catalog/tariff', 'token=' . $token . '&sort=name' . $flip, 'SSL');
		$this->data['sort_percent'] = $this->url->link('catalog/tariff', 'token=' . $token . '&sort=percent' . $flip, 'SSL');
		$this->data['sort_date_end'] = $this->url->link('catalog/tariff', 'token=' . $token . '&sort=date_end' . $flip, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $this->model_catalog_tariff->getTotalTariffs();
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/tariff', 'token=' . $token . '&sort=' . $sort . '&order=' . $order . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/tariff_list.tpl';
		$this->children = array('common/header', 'common/footer');

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$token = $this->session->data['token'];

		foreach (array('heading_title', 'entry_name', 'entry_percent', 'entry_date_end', 'entry_default', 'help_percent', 'help_date_end', 'help_default', 'button_save', 'button_cancel') as $key) {
			$this->data[$key] = $this->language->get($key);
		}

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';
		$this->data['error_percent'] = isset($this->error['percent']) ? $this->error['percent'] : '';
		$this->data['error_date_end'] = isset($this->error['date_end']) ? $this->error['date_end'] : '';

		$this->breadcrumbs();

		if (!isset($this->request->get['tariff_id'])) {
			$this->data['action'] = $this->url->link('catalog/tariff/insert', 'token=' . $token . $this->listUrl(), 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/tariff/update', 'token=' . $token . '&tariff_id=' . $this->request->get['tariff_id'] . $this->listUrl(), 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/tariff', 'token=' . $token . $this->listUrl(), 'SSL');

		$tariff_info = isset($this->request->get['tariff_id']) ? $this->model_catalog_tariff->getTariff($this->request->get['tariff_id']) : array();

		foreach (array('name' => '', 'percent' => '0', 'date_end' => '', 'is_default' => 0) as $key => $default) {
			if (isset($this->request->post[$key])) {
				$this->data[$key] = $this->request->post[$key];
			} elseif ($tariff_info) {
				$this->data[$key] = $tariff_info[$key];
			} else {
				$this->data[$key] = $default;
			}
		}

		$this->template = 'catalog/tariff_form.tpl';
		$this->children = array('common/header', 'common/footer');

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/tariff')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		$percent = str_replace(',', '.', $this->request->post['percent']);

		if (!is_numeric($percent) || $percent < 0 || $percent > 100) {
			$this->error['percent'] = $this->language->get('error_percent');
		} else {
			$this->request->post['percent'] = $percent;
		}

		if ($this->request->post['date_end'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->request->post['date_end'])) {
			$this->error['date_end'] = $this->language->get('error_date_end');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/tariff')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
?>
