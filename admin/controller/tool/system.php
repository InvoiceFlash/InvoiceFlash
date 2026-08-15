<?php
class ControllerToolSystem extends Controller {

	public function index() {
		$this->language->load('tool/system');

		$this->document->setTitle($this->language->get('heading_title'));

		if (!$this->user->hasPermission('access', 'tool/system')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/system', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['text_php_version'] = $this->language->get('text_php_version');
		$this->data['text_db_version'] = $this->language->get('text_db_version');
		$this->data['text_ollama_status'] = $this->language->get('text_ollama_status');
		$this->data['text_ai_model'] = $this->language->get('text_ai_model');
		$this->data['text_rag_status'] = $this->language->get('text_rag_status');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['php_version'] = PHP_VERSION;
		$this->data['db_version'] = $this->getDbVersion();

		$ollama_url = $this->config->get('config_ollama_url') ?: 'http://127.0.0.1:11434/api/chat';
		$ollama_base = preg_replace('#/api/.*$#', '', $ollama_url);

		$this->data['ollama_installed'] = $this->isOllamaReachable($ollama_base);
		$this->data['ollama_base'] = $ollama_base;

		if ($this->config->get('config_ai_provider') == 'ollama') {
			$this->data['ai_model'] = 'Ollama (qwen3:1.7b)';
		} else {
			$this->data['ai_model'] = 'Claude (claude-opus-4-8)';
		}

		$this->data['ai_enabled'] = $this->config->get('config_ai_enabled') !== '0';
		$this->data['rag_enabled'] = $this->data['ai_enabled'] && $this->config->get('config_product_vector_embeddings');

		$this->template = 'tool/system_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getDbVersion() {
		try {
			$query = $this->db->query("SELECT VERSION() AS version");

			return $query->row['version'];
		} catch (\Throwable $e) {
			return '';
		}
	}

	private function isOllamaReachable($base_url) {
		if (!function_exists('curl_init')) {
			return false;
		}

		$ch = curl_init($base_url . '/api/tags');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return ($response !== false) && ($http_code == 200);
	}
}
