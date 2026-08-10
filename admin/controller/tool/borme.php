<?php
class ControllerToolBorme extends Controller {
	private $status_file;
	private $script_path;

	public function __construct($registry) {
		parent::__construct($registry);

		$this->status_file = DIR_SYSTEM . 'vendor/borme/borme_status.json';
		$this->script_path = DIR_SYSTEM . 'vendor/borme/borme_scraper.py';
	}

	public function index() {
		$this->load->language('tool/borme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/borme');

		$this->getList();
	}

	public function getList() {
		$page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit = 50;

		$filter_data = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/borme', 'token=' . $this->session->data['token'], 'SSL')
		);

		$results = $this->model_tool_borme->getBormes($filter_data);
		$total = $this->model_tool_borme->getTotalBormes($filter_data);

		$this->data['bormes'] = array();

		foreach ($results as $row) {
			$this->data['bormes'][] = array(
				'borme_id'     => $row['borme_id'],
				'borme_date'   => date('d-m-Y', strtotime($row['borme_date'])),
				'province'     => $row['province'],
				'company_name' => $row['company_name'],
				'city'         => $row['city'],
				'website'      => $row['website'],
				'email'        => $row['email'],
				'status'       => $row['status'],
			);
		}

		$pagination = new Pagination();
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->total = $total;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('tool/borme', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['token'] = $this->session->data['token'];

		$this->data['run_url'] = $this->url->link('tool/borme/run', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['status_url'] = $this->url->link('tool/borme/status', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['default_province'] = 'ZARAGOZA';
		$this->data['default_max_emails'] = 5;
		$this->data['default_date'] = date('d-m-Y', strtotime('-7 days'));

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['entry_date'] = $this->language->get('entry_date');
		$this->data['entry_province'] = $this->language->get('entry_province');
		$this->data['entry_max_emails'] = $this->language->get('entry_max_emails');
		$this->data['button_run'] = $this->language->get('button_run');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_province'] = $this->language->get('column_province');
		$this->data['column_company'] = $this->language->get('column_company');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_website'] = $this->language->get('column_website');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['text_status_found'] = $this->language->get('text_status_found');
		$this->data['text_status_not_found'] = $this->language->get('text_status_not_found');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_pagination'] = $this->language->get('text_pagination');
		$this->data['error_no_api_key'] = $this->language->get('error_no_api_key');
		$this->data['text_edit_email'] = $this->language->get('text_edit_email');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_website'] = $this->language->get('entry_website');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_close'] = $this->language->get('button_close');
		$this->data['error_invalid_email'] = $this->language->get('error_invalid_email');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['button_email'] = $this->language->get('button_email');
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_subject'] = $this->language->get('text_subject');
		$this->data['text_message'] = $this->language->get('text_message');

		$this->data['save_email_url'] = $this->url->link('tool/borme/saveEmail', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['send_email_url'] = $this->url->link('tool/borme/sendEmail', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['has_api_key'] = (bool)$this->config->get('config_claude_api_key');

		$this->template = 'tool/borme_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function run() {
		$this->load->language('tool/borme');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/borme')) {
			$json['error'] = $this->language->get('error_permission');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		if (!$this->config->get('config_claude_api_key')) {
			$json['error'] = $this->language->get('error_no_api_key');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		if ($this->isRunning()) {
			$json['error'] = $this->language->get('error_already_running');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$province = isset($this->request->post['province']) ? trim($this->request->post['province']) : 'ZARAGOZA';
		$max_emails = isset($this->request->post['max_emails']) ? (int)$this->request->post['max_emails'] : 5;
		$date = isset($this->request->post['date']) ? trim($this->request->post['date']) : '';

		if ($province === '') {
			$province = 'ZARAGOZA';
		}

		if ($max_emails < 1) {
			$max_emails = 5;
		}

		// solo se acepta YYYYMMDD (8 digitos); cualquier otra cosa se ignora y el script usa su valor por defecto (hoy - 7 dias)
		if ($date !== '' && !preg_match('/^\d{8}$/', $date)) {
			$date = '';
		}

		$launched = $this->launchScraper($province, $max_emails, $date);

		if ($launched) {
			$json['success'] = $this->language->get('text_started');
		} else {
			$json['error'] = $this->language->get('error_launch');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function status() {
		$this->response->addHeader('Content-Type: application/json');

		if (!file_exists($this->status_file)) {
			$this->response->setOutput(json_encode(array('running' => false)));
			return;
		}

		$content = file_get_contents($this->status_file);
		$data = json_decode($content, true);

		if (!is_array($data)) {
			$data = array('running' => false);
		}

		$this->response->setOutput(json_encode($data));
	}

	public function saveEmail() {
		$this->load->language('tool/borme');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/borme')) {
			$json['error'] = $this->language->get('error_permission');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$borme_id = isset($this->request->post['borme_id']) ? (int)$this->request->post['borme_id'] : 0;
		$email = isset($this->request->post['email']) ? trim($this->request->post['email']) : '';
		$website = isset($this->request->post['website']) ? trim($this->request->post['website']) : '';

		if ($website !== '' && !preg_match('#^https?://#i', $website)) {
			$website = 'https://' . $website;
		}

		if (!$borme_id || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$json['error'] = $this->language->get('error_invalid_email');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$this->load->model('tool/borme');
		$this->model_tool_borme->updateContact($borme_id, $email, $website);

		$json['success'] = true;
		$json['email'] = $email;
		$json['website'] = $website;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function sendEmail() {
		$this->load->language('tool/borme');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/borme')) {
			$json['error']['permission'] = $this->language->get('error_permission');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$to      = isset($this->request->post['to']) ? trim($this->request->post['to']) : '';
		$subject = isset($this->request->post['subject']) ? trim($this->request->post['subject']) : '';
		$message = isset($this->request->post['message']) ? $this->request->post['message'] : '';

		if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
			$json['error']['to'] = $this->language->get('error_to');
		}

		if ($subject === '') {
			$json['error']['subject'] = $this->language->get('error_subject');
		}

		if ($message === '') {
			$json['error']['message'] = $this->language->get('error_message');
		}

		if (empty($json['error'])) {
			$mail_error = $this->sendnewmail($to, $subject, $message, '');

			if ($mail_error) {
				$json['error']['message'] = $mail_error;
			} else {
				$json['success'] = $this->language->get('text_success_email');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function isRunning() {
		if (!file_exists($this->status_file)) {
			return false;
		}

		$data = json_decode(file_get_contents($this->status_file), true);

		if (empty($data['running'])) {
			return false;
		}

		// Un proceso "en marcha" desde hace mas de 30 minutos se considera
		// muerto (crash/kill externo) y se permite relanzar.
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
				// en Windows, los nombres sueltos ("python3") solo funcionan si estan en el PATH del proceso
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

	private function launchScraper($province, $max_emails, $date = '') {
		$python = $this->findPython();

		if (!$python) {
			return false;
		}

		if (!is_dir(dirname($this->status_file))) {
			mkdir(dirname($this->status_file), 0755, true);
		}

		$log_file = DIR_SYSTEM . 'vendor/borme/borme_last_run.log';

		$env = array(
			'DB_HOST'         => DB_HOSTNAME,
			'DB_USER'         => DB_USERNAME,
			'DB_PASS'         => DB_PASSWORD,
			'DB_NAME'         => DB_DATABASE,
			'DB_PORT'         => (string)DB_PORT,
			'DB_PREFIX'       => DB_PREFIX,
			'CLAUDE_API_KEY'  => $this->config->get('config_claude_api_key'),
			'STATUS_FILE'     => $this->status_file,
		);

		$args = '--province ' . escapeshellarg($province) . ' --max-emails ' . (int)$max_emails;

		if ($date !== '') {
			$args .= ' --date ' . escapeshellarg($date);
		}

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
