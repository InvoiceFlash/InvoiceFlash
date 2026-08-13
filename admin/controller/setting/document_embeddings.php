<?php
class ControllerSettingDocumentEmbeddings extends Controller {
	private $status_file;
	private $script_path;

	public function __construct($registry) {
		parent::__construct($registry);

		$this->status_file = DIR_SYSTEM . 'vendor/document_embeddings/status.json';
		$this->script_path = DIR_SYSTEM . 'vendor/document_embeddings/document_embeddings.py';
	}

	public function index() {
		$this->load->language('setting/document_embeddings');

		$this->document->setTitle($this->language->get('heading_title'));

		if (!$this->user->hasPermission('access', 'setting/document_embeddings')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/document_embeddings');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/document_embeddings', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['document_files'] = $this->model_setting_document_embeddings->getDocuments();

		$this->data['docemb_run_url'] = $this->url->link('setting/document_embeddings/run', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['docemb_status_url'] = $this->url->link('setting/document_embeddings/status', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['text_docemb_instruction'] = $this->language->get('text_docemb_instruction');
		$this->data['column_docemb_filename'] = $this->language->get('column_docemb_filename');
		$this->data['column_docemb_product'] = $this->language->get('column_docemb_product');
		$this->data['column_docemb_status'] = $this->language->get('column_docemb_status');
		$this->data['column_docemb_pages'] = $this->language->get('column_docemb_pages');
		$this->data['column_docemb_chunks'] = $this->language->get('column_docemb_chunks');
		$this->data['column_docemb_date'] = $this->language->get('column_docemb_date');
		$this->data['text_docemb_no_results'] = $this->language->get('text_docemb_no_results');
		$this->data['text_docemb_status_pending'] = $this->language->get('text_docemb_status_pending');
		$this->data['text_docemb_status_processing'] = $this->language->get('text_docemb_status_processing');
		$this->data['text_docemb_status_done'] = $this->language->get('text_docemb_status_done');
		$this->data['text_docemb_status_error'] = $this->language->get('text_docemb_status_error');
		$this->data['text_docemb_no_product'] = $this->language->get('text_docemb_no_product');
		$this->data['button_docemb_run'] = $this->language->get('button_docemb_run');
		$this->data['error_docemb_select_warning'] = $this->language->get('error_docemb_select_warning');
		$this->data['error_docemb_launch'] = $this->language->get('error_docemb_launch');
		$this->data['error_docemb_already_running'] = $this->language->get('error_docemb_already_running');

		$this->template = 'setting/document_embeddings_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function run() {
		$this->load->language('setting/document_embeddings');

		$json = array();

		if (!$this->user->hasPermission('modify', 'setting/document_embeddings')) {
			$json['error'] = $this->language->get('error_permission');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		if ($this->isRunning()) {
			$json['error'] = $this->language->get('error_docemb_already_running');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$files = array();

		if (isset($this->request->post['selected'])) {
			foreach ((array)$this->request->post['selected'] as $filename) {
				$filename = basename(trim($filename));

				if ($filename !== '' && substr(strtolower($filename), -4) === '.pdf') {
					$files[] = $filename;
				}
			}
		}

		if (!$files) {
			$json['error'] = $this->language->get('error_docemb_select_warning');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$launched = $this->spawnPython($files);

		if ($launched) {
			$json['success'] = $this->language->get('text_docemb_started');
		} else {
			$json['error'] = $this->language->get('error_docemb_launch');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function status() {
		$this->load->language('setting/document_embeddings');

		$this->response->addHeader('Content-Type: application/json');

		if (!$this->user->hasPermission('access', 'setting/document_embeddings')) {
			$this->response->setOutput(json_encode(array('running' => false)));
			return;
		}

		$data = array('running' => false);

		if (file_exists($this->status_file)) {
			$content = json_decode(file_get_contents($this->status_file), true);

			if (is_array($content)) {
				$data = $content;
			}
		}

		$this->load->model('setting/document_embeddings');

		$data['documents'] = $this->model_setting_document_embeddings->getDocuments();

		$this->response->setOutput(json_encode($data));
	}

	private function isRunning() {
		if (!file_exists($this->status_file)) {
			return false;
		}

		$data = json_decode(file_get_contents($this->status_file), true);

		if (empty($data['running'])) {
			return false;
		}

		// un proceso "en marcha" desde hace mas de 30 minutos se considera muerto y se permite relanzar
		if (!empty($data['started_at']) && (strtotime($data['started_at']) < (time() - 1800))) {
			return false;
		}

		return true;
	}

	private function findPython() {
		$candidates = array(
			'C:\\Users\\AlcuinoGarcia\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
			'python3',
			'python',
		);

		foreach ($candidates as $candidate) {
			if (stripos(PHP_OS, 'WIN') === 0 && strpos($candidate, ':\\') === false) {
				exec('where ' . escapeshellarg($candidate) . ' 2>NUL', $out, $code);
				if ($code === 0) {
					return $candidate;
				}
				continue;
			}

			if (strpos($candidate, ':\\') !== false) {
				if (file_exists($candidate)) {
					return $candidate;
				}
				continue;
			}

			exec('command -v ' . escapeshellarg($candidate) . ' 2>/dev/null', $out, $code);
			if ($code === 0) {
				return $candidate;
			}
		}

		return null;
	}

	private function spawnPython($files) {
		$python = $this->findPython();

		if (!$python) {
			return false;
		}

		if (!is_dir(dirname($this->status_file))) {
			mkdir(dirname($this->status_file), 0755, true);
		}

		$this->load->model('setting/document_embeddings');
		$docs_dir = $this->model_setting_document_embeddings->getDocsDir();

		$log_file = DIR_SYSTEM . 'vendor/document_embeddings/last_run.log';

		$env = array(
			'DOCEMB_DB_HOST'     => DB_HOSTNAME,
			'DOCEMB_DB_PORT'     => (string)DB_PORT,
			'DOCEMB_DB_USER'     => DB_USERNAME,
			'DOCEMB_DB_PASSWORD' => DB_PASSWORD,
			'DOCEMB_DB_NAME'     => DB_DATABASE,
			'DOCEMB_DB_PREFIX'   => DB_PREFIX,
			'DOCEMB_LANGUAGE_ID' => (string)(int)$this->config->get('config_language_id'),
			'DOCEMB_DOCS_DIR'    => $docs_dir,
			'DOCEMB_STATUS_FILE' => $this->status_file,
		);

		$args = '--files ' . escapeshellarg(implode(',', $files));

		if (stripos(PHP_OS, 'WIN') === 0) {
			foreach ($env as $key => $value) {
				putenv($key . '=' . $value);
			}

			$cmd = 'start /B "" ' . escapeshellarg($python) . ' ' . escapeshellarg($this->script_path) . ' ' . $args
				. ' > ' . escapeshellarg($log_file) . ' 2>&1';

			$handle = popen('cmd /c ' . $cmd, 'r');

			foreach ($env as $key => $value) {
				putenv($key);
			}

			if ($handle === false) {
				return false;
			}

			pclose($handle);

			return true;
		}

		$env_prefix = '';
		foreach ($env as $key => $value) {
			$env_prefix .= $key . '=' . escapeshellarg($value) . ' ';
		}

		$cmd = $env_prefix . escapeshellarg($python) . ' ' . escapeshellarg($this->script_path) . ' ' . $args
			. ' > ' . escapeshellarg($log_file) . ' 2>&1 &';

		exec($cmd, $out, $code);

		return true;
	}
}
