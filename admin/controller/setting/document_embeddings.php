<?php
class ControllerSettingDocumentEmbeddings extends Controller {
	private $status_dir;
	private $script_path;

	// Tipos validos para --batch-type, en el mismo orden que el combo. La clave del
	// array es el valor que viaja en el <select>/POST, el valor es la etiqueta del
	// idioma (ver admin/language/*/setting/document_embeddings.php).
	private $type_labels = array(
		'product_document' => 'text_type_product_document',
		'mail_in'           => 'text_type_mail_in',
		'mail_out'          => 'text_type_mail_out',
		'customer_note'     => 'text_type_customer_note',
		'customer_document' => 'text_type_customer_document',
		'supplier_note'     => 'text_type_supplier_note',
		'supplier_document' => 'text_type_supplier_document',
	);

	public function __construct($registry) {
		parent::__construct($registry);

		$this->status_dir  = DIR_SYSTEM . 'vendor/document_embeddings/';
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

		$type = $this->getRequestedType();

		$this->data['active_type']  = $type;
		$this->data['docemb_types'] = $this->buildTypeOptions();

		$this->data['document_files'] = $this->model_setting_document_embeddings->getUnindexedItems($type);

		$this->data['docemb_run_url']    = $this->url->link('setting/document_embeddings/run', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['docemb_status_url'] = $this->url->link('setting/document_embeddings/status', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['docemb_index_url']  = $this->url->link('setting/document_embeddings', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['text_docemb_instruction'] = $this->language->get('text_docemb_instruction');
		$this->data['text_docemb_type']        = $this->language->get('text_docemb_type');
		$this->data['column_docemb_title']     = $this->language->get('column_docemb_title');
		$this->data['column_docemb_related']   = $this->language->get('column_docemb_related');
		$this->data['column_docemb_date']      = $this->language->get('column_docemb_date');
		$this->data['text_docemb_no_results']  = $this->language->get('text_docemb_no_results');
		$this->data['button_docemb_run']       = $this->language->get('button_docemb_run');
		$this->data['button_docemb_filter']    = $this->language->get('button_docemb_filter');
		$this->data['error_docemb_select_warning']  = $this->language->get('error_docemb_select_warning');
		$this->data['error_docemb_launch']          = $this->language->get('error_docemb_launch');
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

		$type = isset($this->request->post['type']) ? (string)$this->request->post['type'] : '';

		if (!isset($this->type_labels[$type])) {
			$json['error'] = $this->language->get('error_docemb_launch');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$ids = array();

		if (isset($this->request->post['selected'])) {
			foreach ((array)$this->request->post['selected'] as $id) {
				$id = (int)$id;

				if ($id > 0) {
					$ids[] = $id;
				}
			}
		}

		if (!$ids) {
			$json['error'] = $this->language->get('error_docemb_select_warning');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		// mail_in/mail_out son la misma tabla `mails`, distinguida solo por type='R'/'E'
		// en la consulta de listado - para Python (que solo conoce document_id =
		// "mail_<id>") ambas caen en el mismo modo de lote "mail".
		$batch_type = (strpos($type, 'mail_') === 0) ? 'mail' : $type;

		$launched = $this->spawnPython($batch_type, $ids);

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

		$type = $this->getRequestedType();

		$data = array('running' => false);

		$status_file = $this->status_dir . 'status_docemb.json';

		if (file_exists($status_file)) {
			$content = json_decode(file_get_contents($status_file), true);

			if (is_array($content)) {
				$data = $content;
			}
		}

		$this->load->model('setting/document_embeddings');

		$data['documents'] = $this->model_setting_document_embeddings->getUnindexedItems($type);

		$this->response->setOutput(json_encode($data));
	}

	private function getRequestedType() {
		$type = isset($this->request->get['type']) ? (string)$this->request->get['type'] : '';

		return isset($this->type_labels[$type]) ? $type : 'product_document';
	}

	private function buildTypeOptions() {
		$options = array();

		foreach ($this->type_labels as $value => $label_key) {
			$options[] = array(
				'value' => $value,
				'text'  => $this->language->get($label_key)
			);
		}

		return $options;
	}

	private function isRunning() {
		$status_file = $this->status_dir . 'status_docemb.json';

		if (!file_exists($status_file)) {
			return false;
		}

		$data = json_decode(file_get_contents($status_file), true);

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

	private function spawnPython($batch_type, $ids) {
		$python = $this->findPython();

		if (!$python) {
			return false;
		}

		if (!is_dir($this->status_dir)) {
			mkdir($this->status_dir, 0755, true);
		}

		$this->load->model('setting/document_embeddings');
		$docs_dir = $this->model_setting_document_embeddings->getDocsDir();

		$status_file = $this->status_dir . 'status_docemb.json';
		$log_file    = $this->status_dir . 'last_run_docemb.log';

		// Escribir el estado "en marcha" ANTES de lanzar el proceso: el propio script
		// Python no escribe su primer status.json hasta que arranca de verdad
		// (interprete + imports de fitz/mariadb, un par de segundos), y mientras tanto
		// este fichero seguiria teniendo el contenido de la ejecucion anterior
		// (running=false). Sin este escritura previa, el primer sondeo del navegador
		// justo despues de pulsar "Indexar" podia leer ese estado viejo, dar el
		// proceso por terminado y dejar de sondear aunque siguiera corriendo de
		// verdad - el usuario solo lo veia si recargaba la pagina a mano.
		file_put_contents($status_file, json_encode(array(
			'running'         => true,
			'started_at'      => gmdate('c'),
			'finished_at'     => null,
			'total_files'     => count($ids),
			'processed_files' => 0,
			'current_file'    => null,
			'current_page'    => 0,
			'total_pages'     => 0,
			'errors'          => array(),
		)));

		$env = array(
			'DOCEMB_DB_HOST'     => DB_HOSTNAME,
			'DOCEMB_DB_PORT'     => (string)DB_PORT,
			'DOCEMB_DB_USER'     => DB_USERNAME,
			'DOCEMB_DB_PASSWORD' => DB_PASSWORD,
			'DOCEMB_DB_NAME'     => DB_DATABASE,
			'DOCEMB_DB_PREFIX'   => DB_PREFIX,
			'DOCEMB_LANGUAGE_ID' => (string)(int)$this->config->get('config_language_id'),
			'DOCEMB_DOCS_DIR'    => $docs_dir,
			'DOCEMB_STATUS_FILE' => $status_file,
		);

		$args = '--batch-type ' . escapeshellarg($batch_type) . ' --batch-ids ' . escapeshellarg(implode(',', $ids));

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
