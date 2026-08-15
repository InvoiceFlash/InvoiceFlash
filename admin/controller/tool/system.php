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
		$this->data['text_ram'] = $this->language->get('text_ram');
		$this->data['text_gpu'] = $this->language->get('text_gpu');
		$this->data['text_ollama_status'] = $this->language->get('text_ollama_status');
		$this->data['text_ai_model'] = $this->language->get('text_ai_model');
		$this->data['text_rag_status'] = $this->language->get('text_rag_status');
		$this->data['text_not_available'] = $this->language->get('text_not_available');

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

		$ram_bytes = $this->getTotalRam();
		$this->data['ram_total'] = ($ram_bytes !== null) ? $this->formatBytesToGb($ram_bytes) : '';

		$this->data['gpus'] = array();

		foreach ($this->getGpuInfo() as $gpu) {
			$this->data['gpus'][] = array(
				'name' => $gpu['name'],
				'vram' => ($gpu['vram_bytes'] !== null) ? $this->formatBytesToGb($gpu['vram_bytes']) : '',
			);
		}

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

	// RAM total en bytes (null si no se puede determinar, p.ej. fuera de Windows).
	private function getTotalRam() {
		if (stripos(PHP_OS, 'WIN') !== 0) {
			return null;
		}

		$out = @shell_exec('powershell -NoProfile -Command "(Get-CimInstance Win32_ComputerSystem).TotalPhysicalMemory"');
		$out = trim((string)$out);

		if (($out === '') || !ctype_digit($out)) {
			return null;
		}

		return (float)$out;
	}

	// Lista de GPUs: cada una con 'name' y 'vram_bytes' (null si AdapterRAM no lo reporta,
	// o si el valor parece el desbordamiento de 32 bits conocido de WMI para tarjetas con
	// mas de ~4GB de VRAM, en cuyo caso se marca como no fiable en vez de mostrar un dato falso).
	private function getGpuInfo() {
		if (stripos(PHP_OS, 'WIN') !== 0) {
			return array();
		}

		$out = @shell_exec('powershell -NoProfile -Command "Get-CimInstance Win32_VideoController | ForEach-Object { $_.Name + \'|\' + $_.AdapterRAM }"');

		if ($out === null) {
			return array();
		}

		$gpus = array();

		foreach (preg_split('/\r\n|\r|\n/', trim($out)) as $line) {
			$line = trim($line);

			if ($line === '') {
				continue;
			}

			$parts = explode('|', $line);
			$name = trim($parts[0]);
			$ram_raw = isset($parts[1]) ? trim($parts[1]) : '';

			$vram_bytes = null;

			if (ctype_digit($ram_raw) && ((int)$ram_raw > 0)) {
				// AdapterRAM es un entero de 32 bits con signo en WMI: para VRAM >= 4GB
				// el valor se desborda y ya no es fiable (valores cercanos a 4294967295,
				// o que tras interpretarse como negativo dan un numero absurdo).
				$reliable = ((int)$ram_raw < 4294967295);
				$vram_bytes = $reliable ? (float)$ram_raw : null;
			}

			$gpus[] = array(
				'name'       => $name,
				'vram_bytes' => $vram_bytes,
			);
		}

		return $gpus;
	}

	private function formatBytesToGb($bytes) {
		return number_format($bytes / 1073741824, 1) . ' GB';
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
