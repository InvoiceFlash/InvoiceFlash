<?php
class ControllerExtensionModule extends Controller {
	public function index() {
		$this->language->load('extension/module');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_action'] = $this->language->get('column_action');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('module');

		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/module/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('module', $value);

				unset($extensions[$key]);
			}
		}

		$this->data['extensions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/module/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->language->load('module/' . $extension);

				$action = array();

				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('extension/module/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('module/' . $extension . '', 'token=' . $this->session->data['token'], 'SSL')
					);

					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('extension/module/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				}

				$this->data['extensions'][] = array(
					'name'   => $this->language->get('heading_title'),
					'action' => $action
				);
			}
		}

		// VQMod XML modules (activos y desactivados)
		$vqmod_dir    = dirname(DIR_APPLICATION) . '/vqmod/xml/';
		$vqmod_ignore = array('vqmod_invoiceflash.xml');
		$vqmod_all    = array_merge(
			(array)glob($vqmod_dir . '*.xml'),
			(array)glob($vqmod_dir . '*.xml.disabled')
		);

		foreach ($vqmod_all as $vqmod_file) {
			$basename = basename($vqmod_file);

			if (in_array($basename, $vqmod_ignore) || in_array(str_replace('.disabled', '', $basename), $vqmod_ignore)) {
				continue;
			}

			$active = (substr($basename, -9) !== '.disabled');

			$xml = @simplexml_load_file($vqmod_file);
			if (!$xml) continue;

			$id      = isset($xml->id)      ? (string)$xml->id      : str_replace(array('.xml.disabled', '.xml'), '', $basename);
			$version = isset($xml->version)  ? (string)$xml->version : '';
			$author  = isset($xml->author)   ? (string)$xml->author  : '';

			$label = htmlspecialchars($id);
			if ($version) $label .= ' <small class="text-muted">v' . htmlspecialchars($version) . '</small>';
			if ($author)  $label .= ' <small class="text-muted">— ' . htmlspecialchars($author) . '</small>';

			if ($active) {
				$label .= ' <span class="badge" style="background:#28a745;color:#fff;padding:3px 7px;">VQMod activo</span>';
				$action = array(array(
					'text' => 'Desactivar',
					'href' => str_replace('&amp;', '&', $this->url->link('extension/module/disableVqmod', 'token=' . $this->session->data['token'] . '&file=' . urlencode($basename), 'SSL'))
				));
			} else {
				$label .= ' <span class="badge" style="background:#dc3545;color:#fff;padding:3px 7px;">VQMod inactivo</span>';
				$action = array(array(
					'text' => 'Activar',
					'href' => str_replace('&amp;', '&', $this->url->link('extension/module/enableVqmod', 'token=' . $this->session->data['token'] . '&file=' . urlencode($basename), 'SSL'))
				));
			}

			$this->data['extensions'][] = array(
				'name'   => $label,
				'action' => $action
			);
		}

		$this->data['button_ia'] = $this->language->get('button_ia');
		$this->data['ia_url']    = str_replace('&amp;', '&', $this->url->link('extension/module/ia', 'token=' . $this->session->data['token'], 'SSL'));

		$this->template = 'extension/module.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function ia() {
		$this->language->load('extension/module');

		$this->document->setTitle($this->language->get('text_ia'));

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_ia'),
			'href'      => $this->url->link('extension/module/ia', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('text_ia');
		$this->data['chat_url']      = str_replace('&amp;', '&', $this->url->link('extension/module/iaChat', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['cancel']        = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// Settings > AI's own tab was removed from the UI, so this screen is now the only place
		// to actually type in config_claude_api_key — the save button persists it server-side too
		// (not just in localStorage), since other modules (tool/borme, tool/import) read it from there.
		$this->data['save_key_url']         = str_replace('&amp;', '&', $this->url->link('extension/module/saveApiKey', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['config_claude_api_key'] = (string)$this->config->get('config_claude_api_key');

		$this->data['server_software'] = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
		$this->data['server_timeout']  = $this->detectWebServerTimeout($this->data['server_software']);

		$this->template = 'extension/module_ia.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// Best-effort only: reads the web server's own config file (not PHP's) to show the request
	// timeout the browser will actually hit, since it's independent from max_execution_time.
	private function detectWebServerTimeout($server_software) {
		if (stripos($server_software, 'nginx') !== false) {
			foreach ((array)glob('C:/nginx*/conf/nginx.conf') as $conf) {
				$content = @file_get_contents($conf);

				if ($content && preg_match('/proxy_read_timeout\s+(\d+)/i', $content, $match)) {
					return $match[1] . 's (proxy_read_timeout, ' . $conf . ')';
				}
			}

			return 'nginx detectado, pero no se pudo leer nginx.conf automáticamente (revisa proxy_read_timeout / fastcgi_read_timeout a mano).';
		}

		if (stripos($server_software, 'apache') !== false) {
			foreach ((array)glob('C:/Program Files (x86)/EasyPHP-Devserver-17/eds-binaries/httpserver/*/conf/httpd.conf') as $conf) {
				$content = @file_get_contents($conf);

				if ($content && preg_match('/^\s*Timeout\s+(\d+)/mi', $content, $match)) {
					return $match[1] . 's (directiva Timeout, ' . $conf . ')';
				}
			}

			return '60s (valor por defecto de Apache 2.4 — no se encontró una directiva Timeout explícita).';
		}

		return 'No se pudo determinar (SERVER_SOFTWARE: ' . ($server_software ? $server_software : 'desconocido') . ').';
	}

	public function saveApiKey() {
		$this->response->addHeader('Content-Type: application/json');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$json['error'] = 'No permission';
			$this->response->setOutput(json_encode($json));
			return;
		}

		$input   = json_decode(file_get_contents('php://input'), true);
		$api_key = isset($input['api_key']) ? trim($input['api_key']) : '';

		$exists = $this->db->query("SELECT setting_id FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `group` = 'config' AND `key` = 'config_claude_api_key'");

		if ($exists->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($api_key) . "' WHERE store_id = '0' AND `group` = 'config' AND `key` = 'config_claude_api_key'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'config', `key` = 'config_claude_api_key', `value` = '" . $this->db->escape($api_key) . "', serialized = '0'");
		}

		$json['success'] = true;

		$this->response->setOutput(json_encode($json));
	}

	public function iaChat() {
		set_time_limit(0);

		$this->response->addHeader('Content-Type: application/json');

		if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
			$this->response->setOutput(json_encode(array('error' => 'Method not allowed')));
			return;
		}

		$input   = json_decode(file_get_contents('php://input'), true);
		$api_key = isset($input['api_key'])  ? trim($input['api_key'])  : '';
		$messages = isset($input['messages']) ? $input['messages']       : array();
		$message  = isset($input['message'])  ? trim($input['message'])  : '';

		if (!$api_key || !$message) {
			$this->response->setOutput(json_encode(array('error' => 'Faltan campos requeridos')));
			return;
		}

		$messages[] = array('role' => 'user', 'content' => $message);

		$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/xml/';

		$tools = array(
			array(
				'name'         => 'list_vqmod_files',
				'description'  => 'Lista todos los archivos XML VQMod en el directorio vqmod/xml/',
				'input_schema' => array('type' => 'object', 'properties' => new stdClass(), 'required' => array())
			),
			array(
				'name'         => 'read_vqmod_file',
				'description'  => 'Lee el contenido de un archivo XML VQMod existente',
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'filename' => array('type' => 'string', 'description' => 'Nombre del archivo (solo el nombre, sin ruta)')
					),
					'required'   => array('filename')
				)
			),
			array(
				'name'         => 'write_vqmod_file',
				'description'  => 'Escribe o crea un archivo XML VQMod. Solo archivos .xml en vqmod/xml/',
				'input_schema' => array(
					'type'       => 'object',
					'properties' => array(
						'filename' => array('type' => 'string', 'description' => 'Nombre del archivo (solo el nombre, sin ruta, debe terminar en .xml)'),
						'content'  => array('type' => 'string', 'description' => 'Contenido XML completo del archivo')
					),
					'required'   => array('filename', 'content')
				)
			)
		);

		$system = 'Eres un asistente especializado en crear y modificar módulos VQMod para InvoiceFlash.

PUEDES hacer todo lo siguiente mediante archivos VQMod XML:
- Crear nuevos módulos con funcionalidad nueva
- Modificar la interfaz de usuario: ocultar o mostrar elementos del menú, botones, columnas, etc.
- Cambiar estilos, colores o apariencia de cualquier parte del sistema
- Añadir, modificar o reorganizar secciones de las plantillas

NO ESTÁS AUTORIZADO PARA:
- Borrar tablas o registros de la base de datos
- Ejecutar sentencias SQL de tipo DELETE, DROP o TRUNCATE
- Acceder o modificar ficheros fuera de la carpeta vqmod/xml/

Ante cualquier solicitud de borrar datos o registros, responde únicamente:
"No estoy autorizado para borrar datos. Solo puedo crear o modificar módulos VQMod."

Cuando sí crees un módulo, usa este formato XML estándar:
<?xml version="1.0" encoding="UTF-8"?>
<modification>
  <id>nombre-modulo</id>
  <version>1.0.0</version>
  <author>InvoiceFlash IA</author>
  <file path="ruta/relativa/al/fichero.php">
    <operation>
      <search><![CDATA[código PHP a buscar]]></search>
      <add position="before|after|replace"><![CDATA[código PHP a añadir]]></add>
    </operation>
  </file>
</modification>

Responde siempre en español.';

		$content    = array();
		$stop_reason = '';
		$max_iterations = 10;

		for ($i = 0; $i < $max_iterations; $i++) {
			$payload = array(
				'model'      => 'claude-opus-4-8',
				'max_tokens' => 4096,
				'system'     => $system,
				'tools'      => $tools,
				'messages'   => $messages
			);

			$ch = curl_init('https://api.anthropic.com/v1/messages');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'x-api-key: ' . $api_key,
				'anthropic-version: 2023-06-01'
			));
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			$raw        = curl_exec($ch);
			$http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_error = curl_error($ch);
			curl_close($ch);

			if ($raw === false) {
				$this->response->setOutput(json_encode(array('error' => 'cURL error: ' . $curl_error)));
				return;
			}
			if ($http_code !== 200) {
				$err = json_decode($raw, true);
				$msg = (isset($err['error']['message'])) ? $err['error']['message'] : 'HTTP ' . $http_code . ': ' . substr($raw, 0, 200);
				$this->response->setOutput(json_encode(array('error' => $msg)));
				return;
			}

			$resp = json_decode($raw, true);

			if (!isset($resp['content']) || !is_array($resp['content'])) {
				$this->response->setOutput(json_encode(array('error' => 'Respuesta inesperada de la API: ' . substr($raw, 0, 300))));
				return;
			}

			$stop_reason = isset($resp['stop_reason']) ? $resp['stop_reason'] : '';
			$content     = $resp['content'];

			// json_decode(..., true) turns {} into [] — the API requires tool input to be an object, not an array
			foreach ($content as &$block) {
				if (isset($block['type']) && $block['type'] === 'tool_use' && empty($block['input'])) {
					$block['input'] = new stdClass();
				}
			}
			unset($block);

			$messages[] = array('role' => 'assistant', 'content' => $content);

			if ($stop_reason !== 'tool_use') break;

			$tool_results = array();
			foreach ($content as $block) {
				if ($block['type'] === 'tool_use') {
					$result         = $this->executeVqmodTool($block['name'], $block['input'], $vqmod_dir);
					$tool_results[] = array(
						'type'        => 'tool_result',
						'tool_use_id' => $block['id'],
						'content'     => $result
					);
				}
			}
			$messages[] = array('role' => 'user', 'content' => $tool_results);
		}

		$final_text = '';
		foreach ($content as $block) {
			if ($block['type'] === 'text') {
				$final_text .= $block['text'];
			}
		}

		$this->response->setOutput(json_encode(array(
			'reply'    => $final_text,
			'messages' => $messages
		)));
	}

	private function executeVqmodTool($tool_name, $tool_input, $vqmod_dir) {
		$real_dir = realpath($vqmod_dir);

		switch ($tool_name) {
			case 'list_vqmod_files':
				$files  = array_merge(
					(array)glob($vqmod_dir . '*.xml'),
					(array)glob($vqmod_dir . '*.xml.disabled')
				);
				return json_encode(array_map('basename', $files));

			case 'read_vqmod_file':
				$filename = basename(isset($tool_input['filename']) ? $tool_input['filename'] : '');
				if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.(xml|xml\.disabled)$/', $filename)) {
					return 'Error: Nombre de archivo no válido.';
				}
				$path = $vqmod_dir . $filename;
				$real = realpath($path);
				if (!$real || strpos($real, $real_dir) !== 0) {
					return 'Error: Acceso denegado.';
				}
				if (!file_exists($path)) {
					return 'Error: Archivo no encontrado.';
				}
				return file_get_contents($path);

			case 'write_vqmod_file':
				$filename     = basename(isset($tool_input['filename']) ? $tool_input['filename'] : '');
				$file_content = isset($tool_input['content']) ? $tool_input['content'] : '';
				if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.xml$/', $filename)) {
					return 'Error: Nombre inválido. Solo se permiten archivos .xml sin subdirectorios.';
				}
				$path = $vqmod_dir . $filename;
				if (file_put_contents($path, $file_content) === false) {
					return 'Error: No se pudo escribir el archivo.';
				}
				$this->clearVqmodCache();
				return 'Archivo escrito correctamente: ' . $filename;

			default:
				return 'Error: Herramienta desconocida.';
		}
	}

	public function install() {
		$this->language->load('extension/module');

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/extension');

			$this->model_setting_extension->install('module', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'module/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'module/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'install')) {
				$class->install();
			}

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function uninstall() {
		$this->language->load('extension/module');

		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->session->data['error'] = $this->language->get('error_permission');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/extension');
			$this->load->model('setting/setting');

			$this->model_setting_extension->uninstall('module', $this->request->get['extension']);

			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/module/' . $this->request->get['extension'] . '.php');

			$class = 'ControllerModule' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);

			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}

	public function disableVqmod() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$file = basename(rawurldecode($this->request->get['file']));

		// Solo ficheros .xml activos, sin path traversal
		if (substr($file, -4) === '.xml' && strpos($file, '/') === false && strpos($file, '\\') === false) {
			$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/xml/';
			$src = $vqmod_dir . $file;
			$dst = $src . '.disabled';

			if (file_exists($src)) {
				rename($src, $dst);
				$this->clearVqmodCache();
			}
		}

		$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function enableVqmod() {
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$file = basename(rawurldecode($this->request->get['file']));

		// Solo ficheros .xml.disabled, sin path traversal
		if (substr($file, -13) === '.xml.disabled' && strpos($file, '/') === false && strpos($file, '\\') === false) {
			$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/xml/';
			$src = $vqmod_dir . $file;
			$dst = $vqmod_dir . substr($file, 0, -9); // quita .disabled

			if (file_exists($src)) {
				rename($src, $dst);
				$this->clearVqmodCache();
			}
		}

		$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
	}

	private function clearVqmodCache() {
		$vqmod_dir = dirname(DIR_APPLICATION) . '/vqmod/';

		@unlink($vqmod_dir . 'mods.cache');
		@unlink($vqmod_dir . 'checked.cache');

		$cache_files = glob($vqmod_dir . 'vqcache/*');
		if ($cache_files) {
			foreach ($cache_files as $f) {
				@unlink($f);
			}
		}
	}
}
?>