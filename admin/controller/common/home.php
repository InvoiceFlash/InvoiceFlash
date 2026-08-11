<?php   
class ControllerCommonHome extends Controller {   
	public function index() {
		$this->language->load('common/home');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_latest_10_quotes'] = $this->language->get('text_latest_10_quotes');
		$this->data['text_total_sale'] = $this->language->get('text_total_sale');
		$this->data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
		$this->data['text_total_order'] = $this->language->get('text_total_order');
		$this->data['text_total_customer'] = $this->language->get('text_total_customer');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_week'] = $this->language->get('text_week');
		$this->data['text_month'] = $this->language->get('text_month');
		$this->data['text_year'] = $this->language->get('text_year');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_quote'] = $this->language->get('column_quote');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_firstname'] = $this->language->get('column_firstname');
		$this->data['column_lastname'] = $this->language->get('column_lastname');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['entry_range'] = $this->language->get('entry_range');

		// Search
		$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_search_customer'] = $this->language->get('text_search_customer');
		$this->data['text_search_product']  = $this->language->get('text_search_product');

		$this->data['button_search'] = $this->language->get('button_search');

		$this->data['text_view_dashboard'] = $this->language->get('text_view_dashboard');
		$this->data['text_view_claude_chat'] = $this->language->get('text_view_claude_chat');
		$this->data['text_claude_chat'] = $this->language->get('text_claude_chat');
		$this->data['text_claude_chat_placeholder'] = $this->language->get('text_claude_chat_placeholder');
		$this->data['text_claude_chat_input_placeholder'] = $this->language->get('text_claude_chat_input_placeholder');
		$this->data['error_claude_chat_connection'] = $this->language->get('error_claude_chat_connection');
		$this->data['claude_chat_url'] = str_replace('&amp;', '&', $this->url->link('common/home/claudeChat', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['ai_chat_model'] = ($this->config->get('config_ai_provider') == 'ollama') ? 'qwen3:1.7b' : 'claude-opus-4-8';

		// Actions
		$this->data['text_actions'] = $this->language->get('text_actions');
		$this->data['text_add_customer'] = $this->language->get('text_add_customer');
		$this->data['text_view_inbox'] = $this->language->get('text_view_inbox');
		$this->data['text_new_invoice'] = $this->language->get('text_new_invoice');
		$this->data['text_add_product'] = $this->language->get('text_add_product');

		$this->data['add_customer'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['view_inbox'] = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['new_invoice'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['add_product'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'], 'SSL');

		// Check javascript
		$this->data['error_javascript'] = $this->language->get('error_javascript');
		
		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_install'] = $this->language->get('error_install');
		} else {
			$this->data['error_install'] = '';
		}

		// Check image directory is writable
		$file = DIR_IMAGE . 'test';

		$handle = fopen($file, 'a+'); 

		fwrite($handle, '');

		fclose($handle); 		

		if (!file_exists($file)) {
			$this->data['error_image'] = sprintf($this->language->get('error_image'), DIR_IMAGE);
		} else {
			$this->data['error_image'] = '';

			unlink($file);
		}

		// Check image cache directory is writable
		$file = DIR_IMAGE . 'cache/test';

		$handle = fopen($file, 'a+'); 

		fwrite($handle, '');

		fclose($handle); 		

		if (!file_exists($file)) {
			$this->data['error_image_cache'] = sprintf($this->language->get('error_image_cache'), DIR_IMAGE . 'cache/');
		} else {
			$this->data['error_image_cache'] = '';

			unlink($file);
		}

		// Check cache directory is writable
		$file = DIR_CACHE . 'test';

		$handle = fopen($file, 'a+'); 

		fwrite($handle, '');

		fclose($handle); 		

		if (!file_exists($file)) {
			$this->data['error_cache'] = sprintf($this->language->get('error_image_cache'), DIR_CACHE);
		} else {
			$this->data['error_cache'] = '';

			unlink($file);
		}

		// Check download directory is writable
		$file = DIR_DOWNLOAD . 'test';

		$handle = fopen($file, 'a+'); 

		fwrite($handle, '');

		fclose($handle); 		

		if (!file_exists($file)) {
			$this->data['error_download'] = sprintf($this->language->get('error_download'), DIR_DOWNLOAD);
		} else {
			$this->data['error_download'] = '';

			unlink($file);
		}

		// Check logs directory is writable
		$file = DIR_LOGS . 'test';

		$handle = fopen($file, 'a+'); 

		fwrite($handle, '');

		fclose($handle); 		

		if (!file_exists($file)) {
			$this->data['error_logs'] = sprintf($this->language->get('error_logs'), DIR_LOGS);
		} else {
			$this->data['error_logs'] = '';

			unlink($file);
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('sale/invoice');

		$this->data['total_sale'] = $this->currency->format($this->model_sale_invoice->getTotalSales(), $this->config->get('config_currency'), '', true, true);
		$this->data['total_sale_year'] = $this->currency->format($this->model_sale_invoice->getTotalSalesByYear(date('Y')), $this->config->get('config_currency'), '', true, true);
		$this->data['total_order'] = $this->model_sale_invoice->getTotalInvoices();

		$this->load->model('sale/customer');

		$this->data['total_customer'] = $this->model_sale_customer->getTotalCustomers();

		$this->data['invoices'] = array(); 

		$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);

		$results = $this->model_sale_invoice->getInvoices($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'], 'SSL')
			);

			$this->data['invoices'][] = array(
				'invoice_id'   => $result['invoice_id'],
				'company'   => $result['company'],
				'status'     => $result['status'],
				'color'		 => $result['color'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'action'     => $action
			);
		}

		$this->load->model('sale/quote');
		$this->data['quotes'] = array(); 

		$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);

		$results = $this->model_sale_quote->getQuotes($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/quote/info', 'token=' . $this->session->data['token'] . '&quote_id=' . $result['quote_id'], 'SSL')
			);

			$this->data['quotes'][] = array(
				'quote_id'   => $result['quote_id'],
				'company'   => $result['company'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'action'     => $action
			);
		}

		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->updateCurrencies();
		}

		// Check permission for view each panel
		$this->data['view'] = array();
		// Quick Actions
		if ($this->user->hasPermission('modify', 'sale/customer') || $this->user->hasPermission('modify', 'sale/quote') || $this->user->hasPermission('modify', 'sale/invoice')) {
			$this->data['view']['quick_action'] = true;
		} else {
			$this->data['view']['quick_action'] = false;
		}

		// Overview & Statistics
		$this->data['view']['over'] = $this->user->hasPermission('access', 'common/home_statistics');

		// Latest Quotes
		$this->data['view']['last_quotes'] = $this->user->hasPermission('access', 'common/home_latest_quotes');

		// Latest Invoices
		$this->data['view']['last_invoice'] = $this->user->hasPermission('access', 'common/home_latest_invoices');

		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function chart() {
		$this->language->load('common/home');

		$data = array();

		$data['invoice'] = array();
		$data['customer'] = array();
		$data['xaxis'] = array();

		$data['invoice']['label'] = $this->language->get('text_order');
		$data['customer']['label'] = $this->language->get('text_customer');

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			case 'day':
				for ($i = 0; $i < 24; $i++) {
					$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE (DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "') GROUP BY HOUR(date_added) ORDER BY date_added ASC";

					$query = $this->db->query($sql);

					if ($query->num_rows) {
						$data['invoice']['data'][]  = array($i, (int)$query->row['total']);
					} else {
						$data['invoice']['data'][]  = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "' GROUP BY HOUR(date_added) ORDER BY date_added ASC");

					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
				}					
				break;
			case 'week':
				$date_start = strtotime('-' . date('w') . ' days'); 

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)";

					$query = $this->db->query($sql);

					if ($query->num_rows) {
						$data['invoice']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['invoice']['data'][] = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DATE(date_added)");

					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('D', strtotime($date)));
				}

				break;
			default:
			case 'month':
				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE (DATE(date_added) = '" . $this->db->escape($date) . "') GROUP BY DAY(date_added)");

					if ($query->num_rows) {
						$data['invoice']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['invoice']['data'][] = array($i, 0);
					}	

					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = '" . $this->db->escape($date) . "' GROUP BY DAY(date_added)");

					if ($query->num_rows) {
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}	

					$data['xaxis'][] = array($i, date('j', strtotime($date)));
				}
				break;
			case 'year':
				for ($i = 1; $i <= 12; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");

					if ($query->num_rows) {
						$data['invoice']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['invoice']['data'][] = array($i, 0);
					}

					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . $i . "' GROUP BY MONTH(date_added)");

					if ($query->num_rows) { 
						$data['customer']['data'][] = array($i, (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array($i, 0);
					}

					$data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
				}			
				break;	
		} 

		$this->response->setOutput(json_encode($data));
	}

	public function login() {
		$route = '';

		if (isset($this->request->get['route'])) {
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
		}

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);	

		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
			return $this->forward('common/login');
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			$config_ignore = array();

			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}

			$ignore = array_merge($ignore, $config_ignore);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return $this->forward('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return $this->forward('common/login');
			}
		}
	}

	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';

			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'		
			);			

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return $this->forward('error/permission');
			}
		}
	}

	public function claudeChat() {
		set_time_limit(0);

		$this->response->addHeader('Content-Type: application/json');

		if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
			$this->response->setOutput(json_encode(array('error' => 'Method not allowed')));
			return;
		}

		$input    = json_decode(file_get_contents('php://input'), true);
		$messages = isset($input['messages']) ? $input['messages'] : array();
		$message  = isset($input['message']) ? trim($input['message']) : '';

		if ($message === '') {
			$this->response->setOutput(json_encode(array('error' => 'Empty message')));
			return;
		}

		$provider = (string)$this->config->get('config_ai_provider');

		if ($provider === '') {
			$provider = 'claude';
		}

		if ($provider === 'ollama') {
			$this->ollamaChat($messages, $message);
			return;
		}

		$api_key = (string)$this->config->get('config_claude_api_key');

		if (!$api_key) {
			$this->response->setOutput(json_encode(array('error' => $this->language->get('error_claude_chat_no_api_key'))));
			return;
		}

		$messages[] = array('role' => 'user', 'content' => $message);

		$tools = array();

		foreach ($this->getHomeChatToolDefs() as $def) {
			$tools[] = array(
				'name'         => $def['name'],
				'description'  => $def['description'],
				'input_schema' => $def['parameters']
			);
		}

		$system = 'Eres el asistente IA del panel de administración de InvoiceFlash, una aplicación de facturación y gestión (presupuestos, pedidos, albaranes, facturas, clientes, proveedores, contabilidad).

Puedes responder dudas generales sobre cómo usar la aplicación, y también consultar datos reales de la base de datos con las herramientas list_tables, describe_table y query_database cuando el usuario pregunte por sus propios datos (p. ej. "cuántas facturas pendientes tengo" o "cuáles son mis mejores clientes"). Antes de escribir una consulta sobre una tabla que no conozcas, usa describe_table para ver sus columnas reales — no asumas nombres de columna.

NO ESTÁS AUTORIZADO PARA modificar ni borrar datos de ningún tipo (nada de INSERT, UPDATE, DELETE, DROP, ALTER...), solo puedes leer. Si el usuario pide modificar o borrar algo, responde que no puedes hacerlo desde este chat.

Responde siempre en español, de forma breve y clara.';

		$content        = array();
		$stop_reason    = '';
		$max_iterations = 10;

		for ($i = 0; $i < $max_iterations; $i++) {
			$payload = array(
				'model'      => 'claude-opus-4-8',
				'max_tokens' => 2048,
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
				$msg = isset($err['error']['message']) ? $err['error']['message'] : 'HTTP ' . $http_code . ': ' . substr($raw, 0, 200);
				$this->response->setOutput(json_encode(array('error' => $msg)));
				return;
			}

			$resp = json_decode($raw, true);

			if (!isset($resp['content']) || !is_array($resp['content'])) {
				$this->response->setOutput(json_encode(array('error' => 'Unexpected API response: ' . substr($raw, 0, 300))));
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

			if ($stop_reason !== 'tool_use') {
				break;
			}

			$tool_results = array();
			foreach ($content as $block) {
				if ($block['type'] === 'tool_use') {
					$result         = $this->executeHomeChatTool($block['name'], $block['input']);
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

	// Definiciones de las 3 herramientas de solo-lectura sobre la BD, compartidas
	// entre claudeChat() (formato Anthropic, input_schema) y ollamaChat() (formato
	// OpenAI-style que usa Ollama, function.parameters) para no duplicarlas.
	private function getHomeChatToolDefs() {
		return array(
			array(
				'name'        => 'list_tables',
				'description' => 'Lists all tables in the InvoiceFlash database.',
				'parameters'  => array('type' => 'object', 'properties' => new stdClass(), 'required' => array())
			),
			array(
				'name'        => 'describe_table',
				'description' => 'Shows the columns of a table (name, type, nullability, key, default).',
				'parameters'  => array(
					'type'       => 'object',
					'properties' => array(
						'table' => array('type' => 'string', 'description' => 'Exact table name, as returned by list_tables')
					),
					'required'   => array('table')
				)
			),
			array(
				'name'        => 'query_database',
				'description' => 'Runs a single read-only SELECT query against the database and returns the rows (max 200).',
				'parameters'  => array(
					'type'       => 'object',
					'properties' => array(
						'sql' => array('type' => 'string', 'description' => 'A single SELECT statement')
					),
					'required'   => array('sql')
				)
			)
		);
	}

	// json_decode(..., true) convierte "arguments":{} en un array PHP vacío,
	// indistinguible de un array indexado vacío — al reenviar ese historial con
	// json_encode() saldría como [] en vez de {} y Ollama rechaza la petición con
	// "Value looks like object, but can't find closing '}' symbol". Pasa tanto con
	// un tool_call recién generado como con uno ya guardado en el historial que
	// llega desde el navegador (ida y vuelta por json_decode/json_encode), así que
	// se normaliza el array de mensajes completo justo antes de cada envío, no solo
	// el último mensaje.
	private function fixEmptyOllamaToolArgs($messages) {
		foreach ($messages as &$msg) {
			if (empty($msg['tool_calls'])) {
				continue;
			}

			foreach ($msg['tool_calls'] as &$call) {
				if (isset($call['function']['arguments']) && is_array($call['function']['arguments']) && empty($call['function']['arguments'])) {
					$call['function']['arguments'] = new stdClass();
				}
			}
			unset($call);
		}
		unset($msg);

		return $messages;
	}

	// Modelo local vía Ollama (p. ej. qwen3:1.7b) — mismo bucle de herramientas
	// (list_tables/describe_table/query_database) que la rama de Claude, adaptado
	// al formato de tool-calling de Ollama. think:true (no false): con think:false
	// el modelo se inventaba nombres de tabla y, si una consulta fallaba, le pedía
	// al usuario que comprobara el nombre en vez de corregirse solo — con
	// think:true acierta la tabla y se autocorrige de forma consistente, a cambio
	// de ser bastante más lento (varios segundos por llamada, y puede encadenar
	// 2-3 llamadas para una sola pregunta).
	private function ollamaChat($messages, $message) {
		$url = (string)$this->config->get('config_ollama_url');

		if ($url === '') {
			$url = 'http://127.0.0.1:11434/api/chat';
		}

		if (!$messages) {
			$messages[] = array(
				'role'    => 'system',
				'content' => 'Eres el asistente IA del panel de administración de InvoiceFlash, una aplicación de facturación y gestión (presupuestos, pedidos, albaranes, facturas, clientes, proveedores, contabilidad). Tienes 3 herramientas: list_tables, describe_table, query_database. Cuando el usuario pregunte por sus datos (clientes, facturas, pedidos...), SIEMPRE debes usar las herramientas para consultar la base de datos real con datos actuales, nunca preguntes al usuario por nombres de tabla ni respondas sin haber ejecutado una consulta con éxito. Para preguntas de tipo "cuántos/cuántas X tengo", usa directamente query_database con SELECT COUNT(*) FROM <tabla> (no hace falta describe_table para contar filas, solo para saber los nombres de columna cuando vayas a filtrar o mostrar campos concretos). Si una consulta falla porque la tabla no existe, NUNCA le pidas al usuario que compruebe el nombre: llama tú mismo a list_tables, busca el nombre correcto y repite la consulta, sin preguntar nada. Nunca respondas un número que no venga literalmente del resultado de query_database. No estás autorizado para modificar ni borrar datos. Responde siempre en español, de forma breve y clara.'
			);
		}

		$messages[] = array('role' => 'user', 'content' => $message);

		$tools = array();

		foreach ($this->getHomeChatToolDefs() as $def) {
			$tools[] = array(
				'type'     => 'function',
				'function' => array(
					'name'        => $def['name'],
					'description' => $def['description'],
					'parameters'  => $def['parameters']
				)
			);
		}

		$final_message  = array('content' => '');
		$max_iterations = 8;

		for ($i = 0; $i < $max_iterations; $i++) {
			$payload = array(
				'model'    => 'qwen3:1.7b',
				'messages' => $this->fixEmptyOllamaToolArgs($messages),
				'tools'    => $tools,
				'options'  => array('num_predict' => 1024),
				'think'    => true,
				'stream'   => false
			);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);

			$raw        = curl_exec($ch);
			$http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_error = curl_error($ch);
			curl_close($ch);

			if ($raw === false) {
				$this->response->setOutput(json_encode(array('error' => 'cURL error: ' . $curl_error)));
				return;
			}

			$resp = json_decode($raw, true);

			if (!isset($resp['message'])) {
				$this->response->setOutput(json_encode(array('error' => 'Respuesta inesperada de Ollama (HTTP ' . $http_code . '): ' . substr($raw, 0, 300))));
				return;
			}

			$final_message = $resp['message'];
			$messages[]    = $final_message;

			if (empty($final_message['tool_calls'])) {
				break;
			}

			foreach ($final_message['tool_calls'] as $call) {
				$name   = isset($call['function']['name']) ? $call['function']['name'] : '';
				$args   = isset($call['function']['arguments']) ? $call['function']['arguments'] : array();
				$result = $this->executeHomeChatTool($name, $args);

				$messages[] = array('role' => 'tool', 'content' => $result);
			}
		}

		$reply = isset($final_message['content']) ? $final_message['content'] : '';

		if (($reply === '') && !empty($final_message['tool_calls'])) {
			$reply = 'No he podido completar la respuesta tras varios intentos.';
		}

		$this->response->setOutput(json_encode(array(
			'reply'    => $reply,
			'messages' => $messages
		)));
	}

	private function executeHomeChatTool($tool_name, $tool_input) {
		switch ($tool_name) {
			case 'list_tables':
				$result = $this->safeQuery('SHOW TABLES');

				if (isset($result['error'])) {
					return json_encode($result);
				}

				$tables = array();

				foreach ($result['rows'] as $row) {
					$tables[] = array_values($row)[0];
				}

				return json_encode(array('tables' => $tables));

			case 'describe_table':
				$table = isset($tool_input['table']) ? $tool_input['table'] : '';

				if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
					return json_encode(array('error' => 'Invalid table name.'));
				}

				$tablesResult = $this->safeQuery('SHOW TABLES');

				if (isset($tablesResult['error'])) {
					return json_encode($tablesResult);
				}

				$validTables = array();

				foreach ($tablesResult['rows'] as $row) {
					$validTables[] = array_values($row)[0];
				}

				if (!in_array($table, $validTables)) {
					return json_encode(array('error' => 'Table "' . $table . '" does not exist.'));
				}

				$result = $this->safeQuery('DESCRIBE `' . $table . '`');

				return json_encode($result);

			case 'query_database':
				$sql  = isset($tool_input['sql']) ? $tool_input['sql'] : '';
				$safe = $this->sanitizeReadOnlySql($sql);

				if ($safe === false) {
					return json_encode(array('error' => 'Only single SELECT/SHOW/DESCRIBE statements are allowed, no write keywords.'));
				}

				$result = $this->safeQuery($safe);

				if (isset($result['error'])) {
					return json_encode($result);
				}

				$rows = array_slice($result['rows'], 0, 200);

				return json_encode(array('rows' => $rows, 'row_count' => count($rows)));

			default:
				return json_encode(array('error' => 'Unknown tool: ' . $tool_name));
		}
	}

	// Only single SELECT/SHOW/DESCRIBE statements, no write keywords — returns false if it doesn't qualify.
	private function sanitizeReadOnlySql($sql) {
		$sql = trim($sql);

		if (substr_count($sql, ';') > 1) {
			return false;
		}

		$sql = rtrim($sql, "; \t\n\r");

		if (!preg_match('/^(SELECT|SHOW|DESCRIBE|DESC)\s/i', $sql)) {
			return false;
		}

		if (preg_match('/\b(INSERT|UPDATE|DELETE|DROP|ALTER|TRUNCATE|CREATE|GRANT|REVOKE|REPLACE|CALL|EXEC|OUTFILE|LOAD_FILE|INFILE)\b/i', $sql)) {
			return false;
		}

		if (stripos($sql, 'SELECT') === 0 && !preg_match('/\bLIMIT\s+\d+/i', $sql)) {
			$sql .= ' LIMIT 200';
		}

		return $sql;
	}

	// DBMySQLi::query() calls trigger_error()+exit() on a SQL error, which would otherwise kill this
	// whole AJAX request and break the JSON response — a temporary error handler turns it into a
	// catchable exception instead (throwing from inside the handler skips the exit() that follows).
	private function safeQuery($sql) {
		set_error_handler(function ($errno, $errstr) {
			throw new \RuntimeException($errstr);
		});

		try {
			$query = $this->db->query($sql);
			restore_error_handler();
			return array('rows' => isset($query->rows) ? $query->rows : array());
		} catch (\Throwable $e) {
			restore_error_handler();
			return array('error' => $e->getMessage());
		}
	}
}
?>