<?php   
class ControllerCommonHome extends Controller {   
	// Propuestas de cambio creadas durante la peticion actual del chat (se devuelven al navegador).
	private $chat_proposals = array();

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
		$this->data['text_view_calendar'] = $this->language->get('text_view_calendar');
		$this->data['text_view_claude_chat'] = $this->language->get('text_view_claude_chat');
		$this->data['text_claude_chat'] = $this->language->get('text_claude_chat');
		$this->data['text_claude_chat_placeholder'] = $this->language->get('text_claude_chat_placeholder');
		$this->data['text_claude_chat_input_placeholder'] = $this->language->get('text_claude_chat_input_placeholder');
		$this->data['error_claude_chat_connection'] = $this->language->get('error_claude_chat_connection');
		$this->data['claude_chat_url'] = str_replace('&amp;', '&', $this->url->link('common/home/claudeChat', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['chat_actions_enabled'] = $this->config->get('config_ai_actions_enabled') ? 1 : 0;
		$this->data['chat_approve_url'] = str_replace('&amp;', '&', $this->url->link('common/home/approveAction', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['chat_reject_url'] = str_replace('&amp;', '&', $this->url->link('common/home/rejectAction', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['chat_rag_active'] = ($this->config->get('config_ai_enabled') && $this->config->get('config_product_vector_embeddings')) ? 1 : 0;
		$this->data['text_chat_rag_active'] = $this->language->get('text_chat_rag_active');
		$this->data['chat_proposal_texts'] = array(
			'title'    => html_entity_decode($this->language->get('text_chat_proposal_title'), ENT_QUOTES, 'UTF-8'),
			'approve'  => html_entity_decode($this->language->get('button_chat_approve'), ENT_QUOTES, 'UTF-8'),
			'reject'   => html_entity_decode($this->language->get('button_chat_reject'), ENT_QUOTES, 'UTF-8'),
			'applied'  => html_entity_decode($this->language->get('text_chat_proposal_applied'), ENT_QUOTES, 'UTF-8'),
			'rejected' => html_entity_decode($this->language->get('text_chat_proposal_rejected'), ENT_QUOTES, 'UTF-8'),
			'error'    => html_entity_decode($this->language->get('error_chat_proposal'), ENT_QUOTES, 'UTF-8')
		);
		$ai_provider_now = $this->config->get('config_ai_provider');
		$this->data['ai_chat_model'] = ($ai_provider_now == 'ollama') ? 'qwen3:1.7b' : (($ai_provider_now == 'openai') ? 'gpt-4o-mini' : (($ai_provider_now == 'nvidia') ? 'meta/llama-3.1-8b-instruct' : (($ai_provider_now == 'openrouter') ? 'openai/gpt-4o-mini' : (($ai_provider_now == 'deepseek') ? 'deepseek-flash' : 'claude-opus-4-8'))));

		$this->data['text_view_kanban'] = $this->language->get('text_view_kanban');
		$this->data['text_kanban'] = $this->language->get('text_kanban');
		$this->data['text_kanban_new_placeholder'] = $this->language->get('text_kanban_new_placeholder');
		$this->data['text_kanban_add'] = $this->language->get('text_kanban_add');
		$this->data['text_kanban_delete_confirm'] = $this->language->get('text_kanban_delete_confirm');
		$this->data['kanban_columns'] = array(
			'pending'     => $this->language->get('text_kanban_pending'),
			'in_progress' => $this->language->get('text_kanban_in_progress'),
			'done'        => $this->language->get('text_kanban_done')
		);
		foreach (array('attach', 'text', 'photo', 'document', 'photos', 'documents', 'save', 'close', 'upload', 'choose_type', 'text_placeholder', 'no_files', 'delete_file', 'projects', 'project_new', 'project_name', 'project_start', 'project_end', 'project_delete_confirm', 'shared_with_me', 'share', 'share_no_users', 'owner', 'project') as $k) {
			$this->data['text_kanban_' . $k] = $this->language->get('text_kanban_' . $k);
		}
		$this->data['kanban_upload_url'] = str_replace('&amp;', '&', $this->url->link('common/home/kanbanUpload', 'token=' . $this->session->data['token'], 'SSL'));
		$this->data['kanban_url'] = str_replace('&amp;', '&', $this->url->link('common/home/kanban', 'token=' . $this->session->data['token'], 'SSL'));

		// Actions
		$this->data['text_actions'] = $this->language->get('text_actions');
		$this->data['text_add_customer'] = $this->language->get('text_add_customer');
		$this->data['text_view_inbox'] = $this->language->get('text_view_inbox');
		$this->data['text_new_invoice'] = $this->language->get('text_new_invoice');
		$this->data['text_add_product'] = $this->language->get('text_add_product');
		$this->data['text_pending_invoices'] = $this->language->get('text_pending_invoices');

		$this->data['add_customer'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['view_inbox'] = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['new_invoice'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['add_product'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['pending_invoices'] = $this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL');

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
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'], true, true),
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
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'], true, true),
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

		$this->data['view']['pending_invoices'] = $this->user->hasPermission('access', 'tool/pending_invoices');

		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function isKanbanAdmin($user_id) {
		$q = $this->db->query("SELECT user_group_id FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");
		return $q->num_rows && (int)$q->row['user_group_id'] == 1;
	}

	// Devuelve la tarjeta si el usuario puede verla (propietaria, compartida con el o administrador), con 'can_manage'.
	private function getKanbanCard($card_id, $user_id) {
		$q = $this->db->query("SELECT kanban_card_id, user_id, kanban_project_id, status FROM " . DB_PREFIX . "kanban_card WHERE kanban_card_id = '" . (int)$card_id . "'");
		if (!$q->num_rows) {
			return false;
		}
		$card = $q->row;
		$admin = $this->isKanbanAdmin($user_id);
		$card['can_manage'] = ((int)$card['user_id'] == (int)$user_id) || $admin;
		if (!$card['can_manage']) {
			$s = $this->db->query("SELECT 1 FROM " . DB_PREFIX . "kanban_share WHERE kanban_card_id = '" . (int)$card_id . "' AND user_id = '" . (int)$user_id . "'");
			if (!$s->num_rows) {
				return false;
			}
		}
		return $card;
	}

	private function deleteKanbanCardRows($card_id, $owner_id) {
		$atts = $this->db->query("SELECT filename FROM " . DB_PREFIX . "kanban_attachment WHERE kanban_card_id = '" . (int)$card_id . "'");
		foreach ($atts->rows as $att) {
			@unlink($this->getKanbanDir($owner_id) . $att['filename']);
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "kanban_attachment WHERE kanban_card_id = '" . (int)$card_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "kanban_share WHERE kanban_card_id = '" . (int)$card_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "kanban_card WHERE kanban_card_id = '" . (int)$card_id . "'");
	}

	public function kanban() {
		$user_id = (int)$this->user->getId();
		$statuses = array('pending', 'in_progress', 'done');
		$action = isset($this->request->post['action']) ? $this->request->post['action'] : 'list';
		$id = isset($this->request->post['id']) ? (int)$this->request->post['id'] : 0;
		$project_id = isset($this->request->post['project_id']) ? (int)$this->request->post['project_id'] : 0;
		$json = array();

		if ($user_id) {
			$has = $this->db->query("SELECT COUNT(*) AS t FROM " . DB_PREFIX . "kanban_project WHERE user_id = '" . $user_id . "'");
			if (!(int)$has->row['t']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "kanban_project SET user_id = '" . $user_id . "', name = 'General', date_added = NOW()");
				$this->db->query("UPDATE " . DB_PREFIX . "kanban_card SET kanban_project_id = '" . (int)$this->db->getLastId() . "' WHERE user_id = '" . $user_id . "' AND kanban_project_id = 0");
			}
		}

		$card = ($user_id && $id) ? $this->getKanbanCard($id, $user_id) : false;

		if (!$user_id) {
			$json['error'] = 'Not logged in';
		} elseif ($action == 'project_save' || $action == 'project_delete') {
			$name = isset($this->request->post['name']) ? trim(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8')) : '';
			$dates = array();
			foreach (array('date_start', 'date_end') as $k) {
				$v = isset($this->request->post[$k]) ? $this->request->post[$k] : '';
				$dates[$k] = preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? "'" . $v . "'" : 'NULL';
			}
			if ($action == 'project_delete') {
				$cards = $this->db->query("SELECT kanban_card_id FROM " . DB_PREFIX . "kanban_card WHERE kanban_project_id = '" . $id . "' AND user_id = '" . $user_id . "'");
				foreach ($cards->rows as $c) {
					$this->deleteKanbanCardRows((int)$c['kanban_card_id'], $user_id);
				}
				$this->db->query("DELETE FROM " . DB_PREFIX . "kanban_project WHERE kanban_project_id = '" . $id . "' AND user_id = '" . $user_id . "'");
				if ($project_id == $id) { $project_id = 0; }
			} elseif ($name !== '') {
				if ($id) {
					$this->db->query("UPDATE " . DB_PREFIX . "kanban_project SET name = '" . $this->db->escape($name) . "', date_start = " . $dates['date_start'] . ", date_end = " . $dates['date_end'] . " WHERE kanban_project_id = '" . $id . "' AND user_id = '" . $user_id . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "kanban_project SET user_id = '" . $user_id . "', name = '" . $this->db->escape($name) . "', date_start = " . $dates['date_start'] . ", date_end = " . $dates['date_end'] . ", date_added = NOW()");
					$project_id = (int)$this->db->getLastId();
				}
			}
		} elseif ($action == 'add') {
			$title = isset($this->request->post['title']) ? trim(html_entity_decode($this->request->post['title'], ENT_QUOTES, 'UTF-8')) : '';
			$owned = $this->db->query("SELECT kanban_project_id FROM " . DB_PREFIX . "kanban_project WHERE kanban_project_id = '" . $project_id . "' AND user_id = '" . $user_id . "'");
			if ($title !== '' && $owned->num_rows) {
				$max = $this->db->query("SELECT MAX(sort_order) AS m FROM " . DB_PREFIX . "kanban_card WHERE user_id = '" . $user_id . "' AND kanban_project_id = '" . $project_id . "' AND status = 'pending'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "kanban_card SET user_id = '" . $user_id . "', kanban_project_id = '" . $project_id . "', title = '" . $this->db->escape($title) . "', status = 'pending', sort_order = '" . ((int)$max->row['m'] + 1) . "', date_added = NOW()");
			}
		} elseif ($action == 'move') {
			$status = isset($this->request->post['status']) ? $this->request->post['status'] : '';
			$order = (isset($this->request->post['order']) && is_array($this->request->post['order'])) ? $this->request->post['order'] : array();
			if ($card && $card['can_manage'] && in_array($status, $statuses)) {
				$this->db->query("UPDATE " . DB_PREFIX . "kanban_card SET status = '" . $this->db->escape($status) . "' WHERE kanban_card_id = '" . $id . "'");
				$own = $this->isKanbanAdmin($user_id) ? '1' : "user_id = '" . $user_id . "'";
				foreach (array_values($order) as $pos => $card_id) {
					$this->db->query("UPDATE " . DB_PREFIX . "kanban_card SET sort_order = '" . (int)$pos . "' WHERE kanban_card_id = '" . (int)$card_id . "' AND " . $own);
				}
			}
		} elseif ($action == 'delete') {
			if ($card && $card['can_manage']) {
				$this->deleteKanbanCardRows($id, (int)$card['user_id']);
			}
		} elseif ($action == 'detail') {
			$json['detail'] = true;
			if ($card) {
				$d = $this->db->query("SELECT description FROM " . DB_PREFIX . "kanban_card WHERE kanban_card_id = '" . $id . "'");
				$json['text'] = html_entity_decode((string)$d->row['description'], ENT_QUOTES, 'UTF-8');
				$json['attachments'] = $this->getKanbanAttachments($id);
				$json['can_manage'] = $card['can_manage'];
			} else {
				$json['text'] = '';
				$json['attachments'] = array();
			}
		} elseif ($action == 'save_text') {
			if ($card && $card['can_manage']) {
				$text = isset($this->request->post['text']) ? html_entity_decode($this->request->post['text'], ENT_QUOTES, 'UTF-8') : '';
				$this->db->query("UPDATE " . DB_PREFIX . "kanban_card SET description = '" . $this->db->escape($text) . "' WHERE kanban_card_id = '" . $id . "'");
			}
		} elseif ($action == 'att_delete') {
			$att = $this->db->query("SELECT filename, kanban_card_id FROM " . DB_PREFIX . "kanban_attachment WHERE kanban_attachment_id = '" . $id . "'");
			$acard = $att->num_rows ? $this->getKanbanCard($att->row['kanban_card_id'], $user_id) : false;
			$json['detail'] = true;
			if ($acard && $acard['can_manage']) {
				@unlink($this->getKanbanDir((int)$acard['user_id']) . $att->row['filename']);
				$this->db->query("DELETE FROM " . DB_PREFIX . "kanban_attachment WHERE kanban_attachment_id = '" . $id . "'");
				$json['attachments'] = $this->getKanbanAttachments((int)$acard['kanban_card_id']);
			} else {
				$json['attachments'] = array();
			}
		} elseif ($action == 'share_get') {
			$json['detail'] = true;
			$json['users'] = array();
			if ($card && (int)$card['user_id'] == $user_id) {
				$shared = array();
				foreach ($this->db->query("SELECT user_id FROM " . DB_PREFIX . "kanban_share WHERE kanban_card_id = '" . $id . "'")->rows as $r) {
					$shared[(int)$r['user_id']] = true;
				}
				foreach ($this->db->query("SELECT user_id, username FROM " . DB_PREFIX . "user WHERE status = '1' AND user_id <> '" . $user_id . "' ORDER BY username ASC")->rows as $u) {
					$json['users'][] = array('id' => (int)$u['user_id'], 'username' => $u['username'], 'checked' => isset($shared[(int)$u['user_id']]));
				}
			}
		} elseif ($action == 'share_save') {
			if ($card && (int)$card['user_id'] == $user_id) {
				$ids = (isset($this->request->post['users']) && is_array($this->request->post['users'])) ? $this->request->post['users'] : array();
				$this->db->query("DELETE FROM " . DB_PREFIX . "kanban_share WHERE kanban_card_id = '" . $id . "'");
				foreach (array_unique(array_map('intval', $ids)) as $uid) {
					if ($uid && $uid != $user_id) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "kanban_share SET kanban_card_id = '" . $id . "', user_id = '" . $uid . "'");
					}
				}
			}
		}

		if (empty($json['detail']) && $user_id) {
			$json['projects'] = array();
			$pq = $this->db->query("SELECT kanban_project_id, name, date_start, date_end FROM " . DB_PREFIX . "kanban_project WHERE user_id = '" . $user_id . "' ORDER BY sort_order ASC, kanban_project_id ASC");
			foreach ($pq->rows as $p) {
				$json['projects'][] = array('id' => (int)$p['kanban_project_id'], 'name' => $p['name'], 'date_start' => (string)$p['date_start'], 'date_end' => (string)$p['date_end']);
			}
			$valid = ($project_id == -1);
			foreach ($json['projects'] as $p) {
				if ($p['id'] == $project_id) { $valid = true; }
			}
			if (!$valid && $json['projects']) { $project_id = $json['projects'][0]['id']; }
			$json['project_id'] = $project_id;

			$sc = $this->db->query("SELECT COUNT(*) AS t FROM " . DB_PREFIX . "kanban_share WHERE user_id = '" . $user_id . "'");
			$json['shared_total'] = (int)$sc->row['t'];

			$is_admin = $this->isKanbanAdmin($user_id);
			$select = "SELECT c.kanban_card_id, c.user_id, c.title, c.status, u.username AS owner, p.name AS project_name, (c.description IS NOT NULL AND c.description <> '') AS has_text, (SELECT COUNT(*) FROM " . DB_PREFIX . "kanban_attachment a WHERE a.kanban_card_id = c.kanban_card_id AND a.type = 'photo') AS photos, (SELECT COUNT(*) FROM " . DB_PREFIX . "kanban_attachment a WHERE a.kanban_card_id = c.kanban_card_id AND a.type = 'document') AS docs, (SELECT COUNT(*) FROM " . DB_PREFIX . "kanban_share s WHERE s.kanban_card_id = c.kanban_card_id) AS shares FROM " . DB_PREFIX . "kanban_card c LEFT JOIN " . DB_PREFIX . "user u ON u.user_id = c.user_id LEFT JOIN " . DB_PREFIX . "kanban_project p ON p.kanban_project_id = c.kanban_project_id";
			if ($project_id == -1) {
				$query = $this->db->query($select . " WHERE c.kanban_card_id IN (SELECT kanban_card_id FROM " . DB_PREFIX . "kanban_share WHERE user_id = '" . $user_id . "') ORDER BY c.sort_order ASC, c.kanban_card_id ASC");
			} else {
				$query = $this->db->query($select . " WHERE c.user_id = '" . $user_id . "' AND c.kanban_project_id = '" . (int)$project_id . "' ORDER BY c.sort_order ASC, c.kanban_card_id ASC");
			}
			$json['cards'] = array();
			foreach ($query->rows as $row) {
				$mine = ((int)$row['user_id'] == $user_id);
				$json['cards'][] = array(
					'id'         => (int)$row['kanban_card_id'],
					'title'      => $row['title'],
					'status'     => $row['status'],
					'has_text'   => (int)$row['has_text'],
					'photos'     => (int)$row['photos'],
					'docs'       => (int)$row['docs'],
					'shares'     => (int)$row['shares'],
					'owner'      => $mine ? '' : $row['owner'],
					'project'    => $mine ? '' : (string)$row['project_name'],
					'is_owner'   => $mine,
					'can_manage' => ($mine || $is_admin)
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	private function getKanbanDir($user_id) {
		return dirname(DIR_APPLICATION) . '/docs/kanban/' . (int)$user_id . '/';
	}

	private function getKanbanAttachments($card_id) {
		$out = array();
		$query = $this->db->query("SELECT kanban_attachment_id, type, name FROM " . DB_PREFIX . "kanban_attachment WHERE kanban_card_id = '" . (int)$card_id . "' ORDER BY kanban_attachment_id ASC");
		foreach ($query->rows as $row) {
			$out[] = array(
				'id'   => (int)$row['kanban_attachment_id'],
				'type' => $row['type'],
				'name' => $row['name'],
				'url'  => str_replace('&amp;', '&', $this->url->link('common/home/kanbanFile', 'token=' . $this->session->data['token'] . '&id=' . (int)$row['kanban_attachment_id'], 'SSL'))
			);
		}
		return $out;
	}

	public function kanbanUpload() {
		$user_id = (int)$this->user->getId();
		$card_id = isset($this->request->post['id']) ? (int)$this->request->post['id'] : 0;
		$type = (isset($this->request->post['type']) && $this->request->post['type'] == 'photo') ? 'photo' : 'document';
		$allowed = ($type == 'photo') ? array('jpg', 'jpeg', 'png', 'gif', 'webp') : array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'csv');
		$json = array('errors' => array());

		$card = $user_id ? $this->getKanbanCard($card_id, $user_id) : false;

		if (!$card || !$card['can_manage']) {
			$json['errors'][] = 'Invalid card';
		} elseif (!empty($this->request->files['file']['name'][0])) {
			$owner_id = (int)$card['user_id'];
			$dir = $this->getKanbanDir($owner_id);
			if (!is_dir($dir)) {
				@mkdir($dir, 0777, true);
			}
			$files = $this->request->files['file'];
			foreach ($files['name'] as $i => $original) {
				$ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
				if ($files['error'][$i] == UPLOAD_ERR_INI_SIZE || $files['error'][$i] == UPLOAD_ERR_FORM_SIZE) {
					$json['errors'][] = $original . ' (max ' . ini_get('upload_max_filesize') . ')';
				} elseif ((int)$files['error'][$i] !== UPLOAD_ERR_OK) {
					$json['errors'][] = $original . ' (upload error ' . (int)$files['error'][$i] . ')';
				} elseif (!is_uploaded_file(html_entity_decode($files['tmp_name'][$i], ENT_COMPAT, 'UTF-8'))) {
					$json['errors'][] = $original . ' (not a valid upload: ' . $files['tmp_name'][$i] . ')';
				} elseif (!in_array($ext, $allowed) || $files['size'][$i] > 20 * 1024 * 1024) {
					$json['errors'][] = $original . ' (type/size)';
				} else {
					$stored = uniqid() . '.' . $ext;
					if (move_uploaded_file(html_entity_decode($files['tmp_name'][$i], ENT_COMPAT, 'UTF-8'), $dir . $stored)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "kanban_attachment SET kanban_card_id = '" . $card_id . "', user_id = '" . $owner_id . "', type = '" . $type . "', filename = '" . $this->db->escape($stored) . "', name = '" . $this->db->escape($original) . "', date_added = NOW()");
					} else {
						$json['errors'][] = $original . ' (cannot save to ' . $dir . (is_dir($dir) ? ', dir not writable' : ', dir missing') . ')';
					}
				}
			}
		}

		$json['attachments'] = $card ? $this->getKanbanAttachments($card_id) : array();
		$this->response->setOutput(json_encode($json));
	}

	public function kanbanFile() {
		$user_id = (int)$this->user->getId();
		$id = isset($this->request->get['id']) ? (int)$this->request->get['id'] : 0;
		$att = $this->db->query("SELECT filename, name, kanban_card_id FROM " . DB_PREFIX . "kanban_attachment WHERE kanban_attachment_id = '" . $id . "'");
		$card = ($user_id && $att->num_rows) ? $this->getKanbanCard($att->row['kanban_card_id'], $user_id) : false;
		$path = $card ? $this->getKanbanDir((int)$card['user_id']) . $att->row['filename'] : '';

		if (!$path || !is_file($path)) {
			header('HTTP/1.1 404 Not Found');
			exit;
		}

		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		$mimes = array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp', 'pdf' => 'application/pdf', 'txt' => 'text/plain', 'csv' => 'text/csv', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'xls' => 'application/vnd.ms-excel', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$inline = in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt'));

		if (ob_get_level()) { ob_end_clean(); }
		header('Content-Type: ' . (isset($mimes[$ext]) ? $mimes[$ext] : 'application/octet-stream'));
		header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . str_replace('"', '', basename($att->row['name'])) . '"');
		header('X-Content-Type-Options: nosniff');
		header('Content-Length: ' . filesize($path));
		readfile($path);
		exit;
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
				'common/calendar',
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

		if (in_array($provider, array('openai', 'nvidia', 'openrouter', 'deepseek'))) {
			$this->openaiCompatChat($messages, $message, $provider);
			return;
		}

		$api_key = (string)$this->config->get('config_claude_api_key');

		if (!$api_key) {
			$this->response->setOutput(json_encode(array('error' => $this->language->get('error_claude_chat_no_api_key'))));
			return;
		}

		$messages[] = array('role' => 'user', 'content' => $message);

		$tools = array();

		// Las herramientas de busqueda en documentos (RAG) solo se ofrecen si hay un Ollama con
		// nomic-embed-text disponible: los embeddings siempre se generan en local.
		$tool_defs = $this->getHomeChatToolDefs();
		$with_docs = $this->isOllamaEmbeddingModelAvailable();

		if ($with_docs) {
			$tool_defs = array_merge($tool_defs, array($this->getDocumentSearchToolDef(), $this->getCustomerDocumentSearchToolDef(), $this->getSupplierDocumentSearchToolDef()));
		}

		$tool_defs = $this->withActionTools($tool_defs);

		$route     = $this->routeChatMessage($message, $with_docs);
		$tool_defs = $this->filterToolDefsByRoute($tool_defs, $route);

		foreach ($tool_defs as $def) {
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

		if ($with_docs) {
			$system .= '

También dispones de search_product_documents, search_customer_documents y search_supplier_documents para buscar en el contenido de los documentos. Cuando el usuario pregunte por especificaciones técnicas, capacidades o medidas que puedan estar en la ficha en PDF de un producto, usa search_product_documents. Cuando pregunte qué dijo, acordó o preguntó un cliente concreto en una nota, un contrato/documento adjunto o un email (enviado o recibido), usa search_customer_documents con el customer_id de ese cliente (si no lo sabes, resuélvelo antes con query_database sobre la tabla customer). Cuando pregunte qué dijo o se anotó sobre un proveedor concreto en un documento o una nota, usa search_supplier_documents con el supplier_id de ese proveedor (si no lo sabes, resuélvelo antes con query_database sobre la tabla supplier). Nunca respondas un dato técnico o textual que no venga literalmente del resultado de estas herramientas. Cita cada dato de documentos con su referencia ([1], [2]...) y su fuente. Si el resultado trae status insufficient_evidence, responde que no consta esa información en los documentos; no la inventes.';
		}

		$system .= $this->getRouteHint($route);

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
			if (PHP_VERSION_ID < 80000) {
				curl_close($ch);
			}

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
			'reply'     => $final_text,
			'messages'  => $messages,
			'proposals' => $this->chat_proposals
		)));
	}

	// ---------------------------------------------------------------------------------------------
	// Cambios propuestos desde el chat (aprobar antes de escribir).
	// El modelo NUNCA escribe: propose_action solo guarda una propuesta en la sesion y la devuelve al
	// navegador, que la muestra con los botones Aprobar/Rechazar. approveAction() es el unico punto que
	// ejecuta, usa los datos GUARDADOS (no los que lleguen en la peticion) y comprueba otra vez el permiso
	// de modificacion del usuario. Solo existe si config_ai_actions_enabled esta activo.
	// Cada accion: label, help, permission (ruta de permiso 'modify'), prepare($c,$data) -> valida y
	// describe el cambio, execute($c,$data) -> lo aplica, log($data) -> array(document_type, document_id).
	// Los modulos pueden anadir acciones con ficheros admin/chat_actions/*.php que devuelvan un array de definiciones.
	// ---------------------------------------------------------------------------------------------
	private function getChatActionRegistry() {
		static $registry = null;

		if ($registry !== null) {
			return $registry;
		}

		$registry = array(
			'customer_add_note' => array(
				'label'      => 'Añadir una nota a un cliente',
				'help'       => 'customer_add_note: data = {customer_id (entero, id del cliente), comment (texto de la nota)}',
				'permission' => 'sale/customer',
				'prepare'    => function ($c, $data) {
					$customer_id = isset($data['customer_id']) ? (int)$data['customer_id'] : 0;
					$comment     = isset($data['comment']) ? trim((string)$data['comment']) : '';

					if ($customer_id <= 0) {
						return array('error' => 'Falta customer_id: resuélvelo antes con query_database sobre la tabla customer.');
					}

					if ($comment === '') {
						return array('error' => 'Falta el texto de la nota (comment).');
					}

					if (mb_strlen($comment, 'UTF-8') > 2000) {
						return array('error' => 'La nota es demasiado larga (máximo 2000 caracteres).');
					}

					$c->load->model('sale/customer');

					$customer = $c->model_sale_customer->getCustomer($customer_id);

					if (!$customer) {
						return array('error' => 'No existe ningún cliente con ese customer_id.');
					}

					$name = !empty($customer['company']) ? $customer['company'] : trim((isset($customer['firstname']) ? $customer['firstname'] : '') . ' ' . (isset($customer['lastname']) ? $customer['lastname'] : ''));

					return array(
						'target'  => 'Cliente #' . $customer_id . ' · ' . $name,
						'changes' => array('Cliente' => $name, 'Nota nueva' => $comment),
						'data'    => array('customer_id' => $customer_id, 'comment' => $comment)
					);
				},
				'execute'    => function ($c, $data) {
					$c->load->model('sale/customer');

					// Igual que insertNote(): el texto se guarda escapado, como llega del formulario.
					$note_id = $c->model_sale_customer->addCustomerNote(array('comment' => htmlspecialchars($data['comment'], ENT_COMPAT, 'UTF-8')), (int)$data['customer_id']);

					return array('ok' => (bool)$note_id, 'message' => 'Nota añadida al cliente #' . (int)$data['customer_id'] . '.');
				},
				'log'        => function ($data) {
					return array('customer', (int)$data['customer_id']);
				}
			)
		);

		$dir = DIR_APPLICATION . 'chat_actions/';

		if (is_dir($dir)) {
			foreach (glob($dir . '*.php') as $file) {
				$defs = include($file);

				if (!is_array($defs)) {
					continue;
				}

				foreach ($defs as $name => $def) {
					if (!isset($registry[$name]) && isset($def['label']) && isset($def['permission']) && isset($def['prepare']) && isset($def['execute'])) {
						$registry[$name] = $def;
					}
				}
			}
		}

		return $registry;
	}

	private function withActionTools($tool_defs) {
		if (!$this->config->get('config_ai_actions_enabled')) {
			return $tool_defs;
		}

		$tool_defs[] = array(
			'name'        => 'propose_action',
			'description' => 'Proposes a change to the data (it does NOT execute anything). The user sees the exact change in the chat and must press Approve for it to be applied. Use it when the user asks you to create or change something; never claim the change is done, only that the proposal is waiting for approval.',
			'parameters'  => array(
				'type'       => 'object',
				'properties' => array(
					'action' => array('type' => 'string', 'enum' => array_keys($this->getChatActionRegistry()), 'description' => 'The action to propose.'),
					'data'   => array('type' => 'object', 'description' => 'The fields of the action (see the action list in the instructions).')
				),
				'required'   => array('action', 'data')
			)
		);

		return $tool_defs;
	}

	private function getActionPromptNote() {
		$help = array();

		foreach ($this->getChatActionRegistry() as $def) {
			if (isset($def['help'])) {
				$help[] = $def['help'];
			}
		}

		return ' Si el usuario te pide CAMBIAR algo, no puedes hacerlo directamente: usa propose_action para PROPONER el cambio. La propuesta no se ejecuta hasta que el usuario pulse Aprobar en el chat, así que no digas que ya está hecho: di que has dejado la propuesta pendiente de aprobación. Resuelve antes los ids con query_database. Acciones disponibles: ' . implode(' | ', $help) . '.';
	}

	private function proposeChatAction($action, $data) {
		if (!$this->config->get('config_ai_actions_enabled')) {
			return json_encode(array('error' => 'Las propuestas de cambio desde el chat están desactivadas.'));
		}

		$registry = $this->getChatActionRegistry();

		if (!isset($registry[$action])) {
			return json_encode(array('error' => 'Acción desconocida. Acciones válidas: ' . implode(', ', array_keys($registry)) . '.'));
		}

		$def = $registry[$action];

		if (!$this->user->hasPermission('modify', $def['permission'])) {
			return json_encode(array('error' => 'El usuario actual no tiene permiso para esta acción.'));
		}

		$prepare  = $def['prepare'];
		$prepared = $prepare($this, $data);

		if (!is_array($prepared) || isset($prepared['error'])) {
			return json_encode(array('error' => (is_array($prepared) && isset($prepared['error'])) ? $prepared['error'] : 'No se ha podido preparar la propuesta.'));
		}

		$id        = md5(uniqid(mt_rand(), true));
		$proposals = isset($this->session->data['chat_proposals']) ? $this->session->data['chat_proposals'] : array();

		foreach ($proposals as $key => $old) {
			if ((time() - $old['created']) > 3600) {
				unset($proposals[$key]);
			}
		}

		while (count($proposals) >= 30) {
			array_shift($proposals);
		}

		$proposals[$id] = array(
			'action'  => $action,
			'data'    => $prepared['data'],
			'target'  => $prepared['target'],
			'changes' => $prepared['changes'],
			'user_id' => (int)$this->user->getId(),
			'created' => time(),
			'status'  => 'pending'
		);

		$this->session->data['chat_proposals'] = $proposals;

		$this->chat_proposals[] = array(
			'id'      => $id,
			'label'   => $def['label'],
			'target'  => $prepared['target'],
			'changes' => $prepared['changes']
		);

		return json_encode(array(
			'status'      => 'pending_approval',
			'proposal_id' => $id,
			'message'     => 'Propuesta mostrada al usuario, pendiente de su aprobación. NO se ha aplicado ningún cambio todavía.'
		));
	}

	// Devuelve la propuesta pendiente del usuario actual o un texto de error.
	private function takePendingProposal($id) {
		if (!$this->config->get('config_ai_actions_enabled')) {
			return 'Las propuestas de cambio desde el chat están desactivadas.';
		}

		$proposals = isset($this->session->data['chat_proposals']) ? $this->session->data['chat_proposals'] : array();

		if (!isset($proposals[$id])) {
			return 'La propuesta no existe o ha caducado.';
		}

		$proposal = $proposals[$id];

		if ($proposal['status'] !== 'pending') {
			return 'La propuesta ya se ha resuelto.';
		}

		if (((int)$proposal['user_id'] !== (int)$this->user->getId()) || ((time() - $proposal['created']) > 3600)) {
			return 'La propuesta no existe o ha caducado.';
		}

		return $proposal;
	}

	private function readProposalId() {
		$input = json_decode(file_get_contents('php://input'), true);

		return (is_array($input) && isset($input['proposal_id'])) ? (string)$input['proposal_id'] : '';
	}

	public function approveAction() {
		$this->response->addHeader('Content-Type: application/json');

		if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
			$this->response->setOutput(json_encode(array('ok' => false, 'message' => 'Method not allowed')));
			return;
		}

		$id       = $this->readProposalId();
		$proposal = $this->takePendingProposal($id);

		if (!is_array($proposal)) {
			$this->response->setOutput(json_encode(array('ok' => false, 'message' => $proposal)));
			return;
		}

		$registry = $this->getChatActionRegistry();

		if (!isset($registry[$proposal['action']]) || !$this->user->hasPermission('modify', $registry[$proposal['action']]['permission'])) {
			$this->response->setOutput(json_encode(array('ok' => false, 'message' => 'El usuario actual no tiene permiso para esta acción.')));
			return;
		}

		$def = $registry[$proposal['action']];

		// Se marca como resuelta ANTES de ejecutar: un doble clic nunca aplica el cambio dos veces.
		$this->session->data['chat_proposals'][$id]['status'] = 'approved';

		try {
			$execute = $def['execute'];
			$result  = $execute($this, $proposal['data']);
		} catch (Exception $e) {
			$result = array('ok' => false, 'message' => $e->getMessage());
		} catch (\Throwable $e) {
			$result = array('ok' => false, 'message' => $e->getMessage());
		}

		if (!is_array($result) || empty($result['ok'])) {
			$this->session->data['chat_proposals'][$id]['status'] = 'failed';
			$this->response->setOutput(json_encode(array('ok' => false, 'message' => (is_array($result) && isset($result['message'])) ? $result['message'] : 'No se ha podido aplicar el cambio.')));
			return;
		}

		if (isset($def['log'])) {
			$log_fn = $def['log'];
			$log    = $log_fn($proposal['data']);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'edit',
				'document_type' => $log[0],
				'document_id'   => (int)$log[1],
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				'cambiado'      => array_merge(array('origen' => 'Chat IA (aprobado por el usuario)', 'accion' => $def['label']), $proposal['changes'])
			));
		}

		$this->response->setOutput(json_encode(array('ok' => true, 'message' => isset($result['message']) ? $result['message'] : '')));
	}

	public function rejectAction() {
		$this->response->addHeader('Content-Type: application/json');

		if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
			$this->response->setOutput(json_encode(array('ok' => false, 'message' => 'Method not allowed')));
			return;
		}

		$id       = $this->readProposalId();
		$proposal = $this->takePendingProposal($id);

		if (!is_array($proposal)) {
			$this->response->setOutput(json_encode(array('ok' => false, 'message' => $proposal)));
			return;
		}

		$this->session->data['chat_proposals'][$id]['status'] = 'rejected';

		$this->response->setOutput(json_encode(array('ok' => true, 'message' => '')));
	}

	// Enrutado de la pregunta (Database Routing): decide si va a la base de datos (SQL), a los
	// documentos (RAG) o a ambos, y solo ofrece al modelo las herramientas de esa ruta. Un modelo
	// pequeno elige mal entre SQL y busqueda en documentos cuando tiene las seis a la vez. Reglas
	// por palabras, sin llamada extra al modelo: si hay senales de las dos o de ninguna, la ruta es
	// 'both' (todas las herramientas, como antes), asi que un fallo del enrutado nunca quita una
	// herramienta que haga falta.
	//   'data'      -> list_tables, describe_table, query_database
	//   'documents' -> todas, con la indicacion de usar search_* (query_database solo para hallar el id)
	//   'both'      -> todas, sin indicacion
	private function routeChatMessage($message, $with_docs) {
		if (!$with_docs) {
			return 'data';
		}

		$text = mb_strtolower((string)$message, 'UTF-8');
		$text = strtr($text, array('á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n'));

		$docs_re = '/\b(dijo|dijeron|dice|dicen|dije|acordo|acordaron|acordamos|pacto|pactamos|pactado|comento|comentaron|escribio|escribieron|respondio|pregunto|preguntaron|pidio|pidieron|prometio|menciono|mencionaron|nota|notas|email|emails|correo|correos|mail|mails|contrato|contratos|clausula\w*|documento|documentos|pdf|adjunto\w*|ficha|fichas|tecnica|tecnicas|especificacion\w*|capacidad|medidas|dimension\w*|garantia|condiciones|manual)\b/u';
		$data_re = '/\b(cuantos|cuantas|cuanto|cuanta|total\w*|suma|sumar|importe\w*|facturad\w*|pendiente\w*|vencid\w*|cobrad\w*|impagad\w*|listado|lista|listar|top|mejores|peores|mayor\w*|menor\w*|media|promedio|ranking|ventas|compras|ingresos|gastos|saldo|stock|existencias|precio\w*|factura|facturas|pedido|pedidos|albaran|albaranes|presupuesto|presupuestos|clientes|proveedores|productos|por\s+(mes|ano|trimestre|cliente|producto))\b/u';

		$docs = preg_match($docs_re, $text);
		$data = preg_match($data_re, $text);

		// Una peticion de cambio ("anota...", "apunta...") necesita todas las herramientas (hallar el id y proponer).
		if ($this->config->get('config_ai_actions_enabled') && preg_match('/\b(anota|anotar|apunta|apuntar|anade|anadir|crea|crear|registra|registrar|guarda|guardar|deja|dejar)\b/u', $text)) {
			return 'both';
		}

		if ($docs && !$data) {
			return 'documents';
		}

		if ($data && !$docs) {
			return 'data';
		}

		return 'both';
	}

	// En la ruta 'data' se retiran las herramientas de busqueda en documentos.
	private function filterToolDefsByRoute($tool_defs, $route) {
		if ($route !== 'data') {
			return $tool_defs;
		}

		$out = array();

		foreach ($tool_defs as $def) {
			if (strpos($def['name'], 'search_') !== 0) {
				$out[] = $def;
			}
		}

		return $out;
	}

	private function getRouteHint($route) {
		$hint = '';

		if ($route === 'data') {
			$hint = ' Para ESTA pregunta usa list_tables, describe_table y query_database: es una consulta sobre datos estructurados de la base de datos y no dispones de busqueda en documentos.';
		} elseif ($route === 'documents') {
			$hint = ' Para ESTA pregunta el contenido esta en documentos, notas o emails: usa las herramientas search_product_documents, search_customer_documents o search_supplier_documents. Usa query_database solo para localizar el customer_id, supplier_id o product_id que necesites, no para buscar el contenido.';
		}

		if ($this->config->get('config_ai_actions_enabled')) {
			$hint .= $this->getActionPromptNote();
		}

		return $hint;
	}

	// Anade la indicacion de ruta como mensaje de sistema solo para esta peticion (no se guarda en el historial).
	private function withRouteHint($messages, $hint) {
		if ($hint !== '') {
			$messages[] = array('role' => 'system', 'content' => trim($hint));
		}

		return $messages;
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

	// Herramienta de busqueda semantica sobre if_document_chunks (embeddings de PDFs
	// de producto, ver setting/document_embeddings). NO forma parte de
	// getHomeChatToolDefs() a proposito: solo se ofrece en ollamaChat() (modelo local
	// qwen3:1.7b), nunca en claudeChat() — a peticion expresa del usuario.
	private function getDocumentSearchToolDef() {
		return array(
			'name'        => 'search_product_documents',
			'description' => 'Semantic search over previously imported product PDF documentation (technical datasheets). Use it when the user asks about product specifications, capacities, measurements or any technical detail that would be documented in a product PDF rather than in the regular database tables.',
			'parameters'  => array(
				'type'       => 'object',
				'properties' => array(
					'query'      => array('type' => 'string', 'description' => 'Natural language search query, in the same language as the documents (Spanish).'),
					'product_id' => array('type' => 'integer', 'description' => 'Optional: restrict the search to a specific product_id.'),
					'limit'      => array('type' => 'integer', 'description' => 'Optional: max number of passages to return, default 5, max 20.')
				),
				'required'   => array('query')
			)
		);
	}

	// Herramienta de busqueda semantica sobre if_document_chunks acotada a UN
	// cliente (documentos de contrato, notas y emails enviados/recibidos, ver
	// admin/model/sale/customer.php y admin/model/catalog/mail.php). customer_id
	// es OBLIGATORIO en el schema: if_document_chunks es un indice compartido por
	// TODOS los clientes y productos (ver searchProductDocuments() arriba), asi
	// que sin ese filtro una busqueda devolveria pasajes de cualquier otro
	// cliente. Mismo criterio de exposicion que search_product_documents: solo
	// se ofrece en ollamaChat(), nunca en claudeChat().
	private function getCustomerDocumentSearchToolDef() {
		return array(
			'name'        => 'search_customer_documents',
			'description' => 'Semantic search over a specific customer\'s indexed documents: uploaded contracts/files, internal notes, and sent/received emails. Requires a customer_id. Use it when the user asks what a given customer said, agreed to, or asked about in a note, contract or email, instead of query_database (that data is not stored as structured rows).',
			'parameters'  => array(
				'type'       => 'object',
				'properties' => array(
					'query'         => array('type' => 'string', 'description' => 'Natural language search query, in the same language as the documents (Spanish).'),
					'customer_id'   => array('type' => 'integer', 'description' => 'Required: restrict the search to this customer_id.'),
					'document_type' => array('type' => 'string', 'enum' => array('customer_document', 'customer_note', 'email_sent', 'email_received'), 'description' => 'Optional: restrict to one document type.'),
					'limit'         => array('type' => 'integer', 'description' => 'Optional: max number of passages to return, default 5, max 20.')
				),
				'required'   => array('query', 'customer_id')
			)
		);
	}

	// Igual que getCustomerDocumentSearchToolDef() pero para proveedores: documentos
	// (antes "contratos", ver nota en purchase/supplier.php) y notas indexados con
	// supplier_id/supplier_name en if_document_chunks. Mismo criterio de exposicion:
	// solo se ofrece en ollamaChat(), nunca en claudeChat().
	private function getSupplierDocumentSearchToolDef() {
		return array(
			'name'        => 'search_supplier_documents',
			'description' => 'Semantic search over a specific supplier\'s indexed documents: uploaded documents/contracts and internal notes. Requires a supplier_id. Use it when the user asks what a given supplier said, agreed to, or was noted about, instead of query_database (that content is not stored as structured rows).',
			'parameters'  => array(
				'type'       => 'object',
				'properties' => array(
					'query'         => array('type' => 'string', 'description' => 'Natural language search query, in the same language as the documents (Spanish).'),
					'supplier_id'   => array('type' => 'integer', 'description' => 'Required: restrict the search to this supplier_id.'),
					'document_type' => array('type' => 'string', 'enum' => array('supplier_document', 'supplier_note'), 'description' => 'Optional: restrict to one document type.'),
					'limit'         => array('type' => 'integer', 'description' => 'Optional: max number of passages to return, default 5, max 20.')
				),
				'required'   => array('query', 'supplier_id')
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
	// Prompt de sistema del chat con herramientas de BD y de busqueda en documentos (Ollama y
	// proveedores compatibles con OpenAI).
	private function getHomeChatSystemPrompt() {
		return 'Eres el asistente IA del panel de administración de InvoiceFlash, una aplicación de facturación y gestión (presupuestos, pedidos, albaranes, facturas, clientes, proveedores, contabilidad). Tienes 6 herramientas: list_tables, describe_table, query_database, search_product_documents, search_customer_documents y search_supplier_documents. Cuando el usuario pregunte por sus datos (clientes, facturas, pedidos...), SIEMPRE debes usar list_tables/describe_table/query_database para consultar la base de datos real con datos actuales, nunca preguntes al usuario por nombres de tabla ni respondas sin haber ejecutado una consulta con éxito. Para preguntas de tipo "cuántos/cuántas X tengo", usa directamente query_database con SELECT COUNT(*) FROM <tabla> (no hace falta describe_table para contar filas, solo para saber los nombres de columna cuando vayas a filtrar o mostrar campos concretos). Si una consulta falla porque la tabla no existe, NUNCA le pidas al usuario que compruebe el nombre: llama tú mismo a list_tables, busca el nombre correcto y repite la consulta, sin preguntar nada. Cuando el usuario pregunte por especificaciones técnicas, capacidades, medidas o cualquier dato que pueda estar en la ficha/documentación en PDF de un producto (no en las tablas normales de la base de datos), usa search_product_documents en vez de query_database. Cuando el usuario pregunte qué dijo, acordó o preguntó un cliente concreto en una nota, un contrato/documento adjunto o un email (enviado o recibido), usa search_customer_documents con el customer_id de ese cliente (si no lo sabes, resuélvelo antes con query_database sobre la tabla customer) en vez de query_database, porque ese contenido no está en columnas estructuradas. Cuando el usuario pregunte qué dijo, acordó o se anotó sobre un proveedor concreto en un documento o una nota, usa search_supplier_documents con el supplier_id de ese proveedor (si no lo sabes, resuélvelo antes con query_database sobre la tabla supplier) en vez de query_database. Nunca respondas un número que no venga literalmente del resultado de query_database, ni un dato técnico o textual que no venga literalmente del resultado de search_product_documents, search_customer_documents o search_supplier_documents. Cita cada dato de documentos con su referencia ([1], [2]...) y su fuente; si el resultado trae status insufficient_evidence, responde que no consta esa información en los documentos, sin inventarla. No estás autorizado para modificar ni borrar datos. Responde siempre en español, de forma breve y clara.';
	}

	// Chat con proveedores que hablan el formato chat/completions de OpenAI: OpenAI, NVIDIA,
	// OpenRouter y DeepSeek (modelo deepseek-flash = DeepSeek-V4.1-Flash-1017). Mismo bucle de herramientas que ollamaChat(). Las herramientas de busqueda en
	// documentos solo se ofrecen si hay un Ollama con nomic-embed-text disponible (los embeddings
	// siempre se generan en local).
	private function openaiCompatChat($messages, $message, $provider) {
		$providers = array(
			'openai'     => array('https://api.openai.com/v1/chat/completions', 'config_openai_api_key', 'gpt-4o-mini', 'OpenAI'),
			'nvidia'     => array('https://integrate.api.nvidia.com/v1/chat/completions', 'config_nvidia_api_key', 'meta/llama-3.1-8b-instruct', 'NVIDIA'),
			'openrouter' => array('https://openrouter.ai/api/v1/chat/completions', 'config_openrouter_api_key', 'openai/gpt-4o-mini', 'OpenRouter'),
			'deepseek'   => array('https://api.deepseek.com/chat/completions', 'config_deepseek_api_key', 'deepseek-flash', 'DeepSeek')
		);

		list($url, $key_name, $model, $label) = $providers[$provider];

		$api_key = trim((string)$this->config->get($key_name));

		if ($api_key === '') {
			$this->response->setOutput(json_encode(array('error' => sprintf($this->language->get('error_ai_chat_no_api_key'), $label))));
			return;
		}

		$tool_defs = $this->getHomeChatToolDefs();
		$with_docs = $this->isOllamaEmbeddingModelAvailable();

		if ($with_docs) {
			$tool_defs = array_merge($tool_defs, array($this->getDocumentSearchToolDef(), $this->getCustomerDocumentSearchToolDef(), $this->getSupplierDocumentSearchToolDef()));
		}

		$tool_defs  = $this->withActionTools($tool_defs);

		$route      = $this->routeChatMessage($message, $with_docs);
		$tool_defs  = $this->filterToolDefsByRoute($tool_defs, $route);
		$route_hint = $this->getRouteHint($route);

		// Un historial que no empieza por el mensaje de sistema viene de otro motor (formato distinto): se descarta.
		if ($messages && (!isset($messages[0]['role']) || $messages[0]['role'] !== 'system')) {
			$messages = array();
		}

		if (!$messages) {
			$prompt = $this->getHomeChatSystemPrompt();

			if (!$with_docs) {
				$prompt .= ' En esta sesion NO dispones de search_product_documents, search_customer_documents ni search_supplier_documents: si el usuario pregunta por el contenido de documentos, notas o emails, responde que la busqueda en documentos no esta disponible.';
			}

			$messages[] = array('role' => 'system', 'content' => $prompt);
		}

		$messages[] = array('role' => 'user', 'content' => $message);

		$tools = array();

		foreach ($tool_defs as $def) {
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
				'model'      => $model,
				'messages'   => $this->withRouteHint($messages, $route_hint),
				'tools'      => $tools,
				'max_tokens' => 1024
			);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $api_key));
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);

			if (is_file(DIR_SYSTEM . 'cacert.pem')) {
				curl_setopt($ch, CURLOPT_CAINFO, DIR_SYSTEM . 'cacert.pem');
			}

			$raw        = curl_exec($ch);
			$http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_error = curl_error($ch);
			if (PHP_VERSION_ID < 80000) {
				curl_close($ch);
			}

			if ($raw === false) {
				$this->response->setOutput(json_encode(array('error' => 'cURL error: ' . $curl_error)));
				return;
			}

			$resp = json_decode($raw, true);

			if (($http_code < 200) || ($http_code >= 300)) {
				$msg = isset($resp['error']['message']) ? $resp['error']['message'] : 'HTTP ' . $http_code . ': ' . substr($raw, 0, 200);
				$this->response->setOutput(json_encode(array('error' => $label . ': ' . $msg)));
				return;
			}

			if (!isset($resp['choices'][0]['message'])) {
				$this->response->setOutput(json_encode(array('error' => $label . ': ' . substr($raw, 0, 300))));
				return;
			}

			$final_message = $resp['choices'][0]['message'];
			$messages[]    = $final_message;

			if (empty($final_message['tool_calls'])) {
				break;
			}

			foreach ($final_message['tool_calls'] as $call) {
				$name = isset($call['function']['name']) ? $call['function']['name'] : '';
				$args = isset($call['function']['arguments']) ? json_decode($call['function']['arguments'], true) : array();

				if (!is_array($args)) {
					$args = array();
				}

				$messages[] = array(
					'role'         => 'tool',
					'tool_call_id' => isset($call['id']) ? $call['id'] : '',
					'content'      => $this->executeHomeChatTool($name, $args)
				);
			}
		}

		$reply = isset($final_message['content']) ? (string)$final_message['content'] : '';

		if (($reply === '') && !empty($final_message['tool_calls'])) {
			$reply = 'No he podido completar la respuesta tras varios intentos.';
		}

		$this->response->setOutput(json_encode(array(
			'reply'     => $reply,
			'messages'  => $messages,
			'proposals' => $this->chat_proposals
		)));
	}

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
				'content' => $this->getHomeChatSystemPrompt()
			);
		}

		$messages[] = array('role' => 'user', 'content' => $message);

		$tools = array();

		// search_product_documents/search_customer_documents/search_supplier_documents
		// solo se ofrecen aqui (rama Ollama/qwen3:1.7b), no en claudeChat() — no forman
		// parte de getHomeChatToolDefs() a proposito.
		$tool_defs = array_merge($this->getHomeChatToolDefs(), array($this->getDocumentSearchToolDef(), $this->getCustomerDocumentSearchToolDef(), $this->getSupplierDocumentSearchToolDef()));

		$tool_defs  = $this->withActionTools($tool_defs);

		$route      = $this->routeChatMessage($message, true);
		$tool_defs  = $this->filterToolDefsByRoute($tool_defs, $route);
		$route_hint = $this->getRouteHint($route);

		foreach ($tool_defs as $def) {
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
				'messages' => $this->fixEmptyOllamaToolArgs($this->withRouteHint($messages, $route_hint)),
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
			if (PHP_VERSION_ID < 80000) {
				curl_close($ch);
			}

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
			'reply'     => $reply,
			'messages'  => $messages,
			'proposals' => $this->chat_proposals
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

			case 'search_product_documents':
				$query      = isset($tool_input['query']) ? trim((string)$tool_input['query']) : '';
				$product_id = !empty($tool_input['product_id']) ? (int)$tool_input['product_id'] : 0;
				$limit      = isset($tool_input['limit']) ? (int)$tool_input['limit'] : 5;
				$limit      = min(max($limit, 1), 20);

				if ($query === '') {
					return json_encode(array('error' => 'Missing query.'));
				}

				$result = $this->searchProductDocuments($query, $product_id, $limit);

				if (isset($result['error'])) {
					return json_encode($result);
				}

				return $this->formatRagResponse($result);

			case 'search_customer_documents':
				$query         = isset($tool_input['query']) ? trim((string)$tool_input['query']) : '';
				$customer_id   = !empty($tool_input['customer_id']) ? (int)$tool_input['customer_id'] : 0;
				$document_type = isset($tool_input['document_type']) ? trim((string)$tool_input['document_type']) : '';
				$limit         = isset($tool_input['limit']) ? (int)$tool_input['limit'] : 5;
				$limit         = min(max($limit, 1), 20);

				if ($query === '') {
					return json_encode(array('error' => 'Missing query.'));
				}

				if ($customer_id <= 0) {
					return json_encode(array('error' => 'Missing or invalid customer_id.'));
				}

				$result = $this->searchCustomerDocuments($query, $customer_id, $document_type, $limit);

				if (isset($result['error'])) {
					return json_encode($result);
				}

				return $this->formatRagResponse($result);

			case 'search_supplier_documents':
				$query         = isset($tool_input['query']) ? trim((string)$tool_input['query']) : '';
				$supplier_id   = !empty($tool_input['supplier_id']) ? (int)$tool_input['supplier_id'] : 0;
				$document_type = isset($tool_input['document_type']) ? trim((string)$tool_input['document_type']) : '';
				$limit         = isset($tool_input['limit']) ? (int)$tool_input['limit'] : 5;
				$limit         = min(max($limit, 1), 20);

				if ($query === '') {
					return json_encode(array('error' => 'Missing query.'));
				}

				if ($supplier_id <= 0) {
					return json_encode(array('error' => 'Missing or invalid supplier_id.'));
				}

				$result = $this->searchSupplierDocuments($query, $supplier_id, $document_type, $limit);

				if (isset($result['error'])) {
					return json_encode($result);
				}

				return $this->formatRagResponse($result);

			case 'propose_action':
				$action = isset($tool_input['action']) ? trim((string)$tool_input['action']) : '';
				$data   = isset($tool_input['data']) ? $tool_input['data'] : array();

				if (is_string($data)) {
					$data = json_decode($data, true);
				}

				return $this->proposeChatAction($action, is_array($data) ? $data : array());

			default:
				return json_encode(array('error' => 'Unknown tool: ' . $tool_name));
		}
	}

	// Busqueda hibrida sobre if_document_chunks: semantica (embedding de la consulta +
	// VEC_DISTANCE_COSINE, MariaDB 11.7+) y por palabras (indice FULLTEXT sobre chunk_text),
	// combinadas con Reciprocal Rank Fusion. La parte por palabras acierta con referencias
	// exactas (F-2026-0117, nombres propios) que los embeddings no capturan; la semantica
	// acierta con sinonimos y preguntas en lenguaje natural. $where acota SIEMPRE el alcance
	// (cliente/proveedor/producto): nunca se construye a partir de la consulta del usuario.
	private function hybridSearch($columns, $where, $query, $limit) {
		$this->ensureChunkFulltextIndex();

		$table = DB_PREFIX . 'document_chunks';
		$pool  = max($limit * 4, 20);
		$q     = $this->buildFulltextQuery($query);

		$embedding   = $this->generateEmbedding($query);
		$distance_sql = '';

		if ($embedding !== false) {
			$vector_text  = '[' . implode(',', array_map(function ($v) { return (float)$v; }, $embedding)) . ']';
			$distance_sql = "VEC_DISTANCE_COSINE(embedding, VEC_FromText('" . $this->db->escape($vector_text) . "'))";
		}

		$semantic = array();

		if ($distance_sql !== '') {
			$result = $this->safeQuery("SELECT document_chunk_id, " . $columns . ", " . $distance_sql . " AS distance
				FROM `" . $table . "` WHERE " . $where . " ORDER BY distance ASC LIMIT " . (int)$pool);

			if (isset($result['error'])) {
				return $result;
			}

			// Umbral: los fragmentos demasiado lejos de la consulta no son una respuesta, son relleno.
			// Solo afecta a la parte semantica; una coincidencia por palabras se conserva siempre.
			$max_distance = (float)$this->config->get('config_rag_max_distance');

			if ($max_distance <= 0) {
				$max_distance = 0.5;
			}

			foreach ($result['rows'] as $row) {
				if ((float)$row['distance'] <= $max_distance) {
					$semantic[] = $row;
				}
			}
		}

		// Si el indice FULLTEXT no se pudo crear o la consulta no tiene palabras utiles, solo cuenta la semantica.
		$text = array();

		if ($q !== '') {
			$q_sql = $this->db->escape($q);

			$result = $this->safeQuery("SELECT document_chunk_id, " . $columns . ", " . ($distance_sql !== '' ? $distance_sql : 'NULL') . " AS distance
				FROM `" . $table . "` WHERE " . $where . " AND MATCH(chunk_text) AGAINST ('" . $q_sql . "' IN BOOLEAN MODE)
				ORDER BY MATCH(chunk_text) AGAINST ('" . $q_sql . "' IN BOOLEAN MODE) DESC LIMIT " . (int)$pool);

			$text = isset($result['error']) ? array() : $result['rows'];
		}

		if ($embedding === false && !$text) {
			return array('error' => 'No se ha podido generar el embedding de la consulta (Ollama no disponible o modelo nomic-embed-text no descargado) y no hay coincidencias por palabras.');
		}

		$merged = array();

		foreach (array('semantic' => $semantic, 'text' => $text) as $kind => $rows) {
			foreach (array_values($rows) as $rank => $row) {
				$id = $row['document_chunk_id'];

				if (!isset($merged[$id])) {
					$row['score'] = 0;
					$row['match'] = array();
					$merged[$id]  = $row;
				}

				$merged[$id]['score']  += 1 / (60 + $rank + 1);
				$merged[$id]['match'][] = $kind;
			}
		}

		uasort($merged, function ($x, $y) {
			if ($x['score'] == $y['score']) {
				return 0;
			}

			return ($x['score'] < $y['score']) ? 1 : -1;
		});

		$out = array();

		foreach (array_slice($merged, 0, $limit) as $row) {
			unset($row['document_chunk_id']);
			$row['score'] = round($row['score'], 5);
			$row['match'] = (count($row['match']) > 1) ? 'both' : $row['match'][0];
			$out[]        = $row;
		}

		return array('rows' => $out);
	}

	// Convierte la pregunta del usuario en una consulta FULLTEXT en modo booleano:
	//  - quita palabras vacias ("que", "cual", "dijo"...) y las de menos de 3 letras (el indice las ignora);
	//  - las referencias tipo F-2026-0117 se buscan como frase obligatoria (+"..."), porque el indice
	//    parte los guiones en trozos y sin comillas casaria cualquiera de ellos por separado;
	//  - el resto de palabras son opcionales (ordenan por relevancia) y llevan * al final para casar
	//    variantes (factura -> facturas, facturacion).
	// Devuelve '' si no queda nada util. Solo salen letras, numeros y los operadores que anade esta funcion.
	private function buildFulltextQuery($query) {
		static $stop = null;

		if ($stop === null) {
			$stop = array_flip(explode(' ', 'el la los las un una unos unas lo al del de y e o u a en con sin por para sobre entre hasta desde que cual cuales quien quienes como cuando donde cuanto cuanta cuantos cuantas cuyo es son fue fueron era ser sido esta este esto estos estas ese esa eso esos esas hay han has ha he tiene tienen tenia tener tengo dijo dice dicen dijeron decir pidio pide piden quiero quiere queria puede pueden podria mas menos muy tambien pero porque pues si no ni mi mis tu tus su sus nos nuestro nuestra me te se les le the and for with what which who when where how was were are has have had did does said says about from that this those these'));
		}

		$query = (string)$query;
		$terms = array();

		// Referencias: letras/numeros unidos por - / o . con al menos un digito (F-2026-0117, 2026/15, A.123).
		if (preg_match_all('/[\p{L}\p{N}]+(?:[-\/.][\p{L}\p{N}]+)+/u', $query, $m)) {
			foreach ($m[0] as $ref) {
				if (preg_match('/\d/', $ref)) {
					$terms[] = '+"' . str_replace('"', '', $ref) . '"';
					$query   = str_replace($ref, ' ', $query);
				}
			}
		}

		if (preg_match_all('/[\p{L}\p{N}]+/u', $query, $m)) {
			$seen = array();

			foreach ($m[0] as $word) {
				$plain = strtr(mb_strtolower($word, 'UTF-8'), array('á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u'));

				if (mb_strlen($word, 'UTF-8') < 3 || isset($stop[$plain]) || isset($seen[$plain])) {
					continue;
				}

				$seen[$plain] = true;

				// Plural sencillo: plazos -> plazo* (casa plazo, plazos, plazos...)
				if (mb_strlen($word, 'UTF-8') > 4 && mb_substr($word, -1, 1, 'UTF-8') == 's' && !ctype_digit($word)) {
					$word = mb_substr($word, 0, -1, 'UTF-8');
				}

				$terms[] = $word . '*';
			}
		}

		return implode(' ', $terms);
	}

	// Crea el indice FULLTEXT de chunk_text si no existe (instalaciones anteriores a la busqueda
	// hibrida). Una sola comprobacion por peticion; si el ALTER falla, la busqueda sigue siendo semantica.
	private function ensureChunkFulltextIndex() {
		static $checked = false;

		if ($checked) {
			return;
		}

		$checked = true;
		$table   = DB_PREFIX . 'document_chunks';
		$result  = $this->safeQuery("SHOW INDEX FROM `" . $table . "` WHERE Key_name = 'ft_chunk_text'");

		if (!isset($result['error']) && !$result['rows']) {
			$this->safeQuery("ALTER TABLE `" . $table . "` ADD FULLTEXT KEY `ft_chunk_text` (`chunk_text`)");
		}
	}

	// CRAG (Corrective RAG): evalua lo recuperado y corrige antes de responder.
	//  1. Cada fragmento recibe una confianza: 'high' si coincide por palabras Y por significado,
	//     'medium' si coincide por palabras o esta muy cerca en el vector, 'low' en otro caso.
	//  2. Si no hay ningun fragmento 'high'/'medium', se reintenta UNA vez con la pregunta reducida
	//     a sus palabras clave (sin palabras vacias), que suele ser mas precisa para el indice.
	//  3. Si tampoco entonces, no se devuelve relleno: status 'insufficient_evidence' y sin fragmentos.
	// Solo se conservan los fragmentos 'high'/'medium'. La distancia "muy cerca" (0.35) es ajustable
	// con config_rag_good_distance; con nomic-embed-text las distancias de lo relevante y lo irrelevante
	// se solapan, por eso la semantica sola no basta para dar confianza.
	private function searchWithCorrection($columns, $where, $query, $limit) {
		$good = (float)$this->config->get('config_rag_good_distance');

		if ($good <= 0) {
			$good = 0.35;
		}

		$attempts = 1;
		$used     = $query;
		$result   = $this->hybridSearch($columns, $where, $query, $limit);

		if (isset($result['error'])) {
			return $result;
		}

		$rows = $this->gradeChunks($result['rows'], $good);

		if (!$rows) {
			$condensed = $this->condenseQuery($query);

			if ($condensed !== '' && mb_strtolower($condensed, 'UTF-8') !== mb_strtolower(trim($query), 'UTF-8')) {
				$attempts = 2;
				$used     = $condensed;
				$retry    = $this->hybridSearch($columns, $where, $condensed, $limit);

				if (!isset($retry['error'])) {
					$rows = $this->gradeChunks($retry['rows'], $good);
				}
			}
		}

		return array(
			'rows'       => $rows,
			'status'     => $rows ? 'ok' : 'insufficient_evidence',
			'attempts'   => $attempts,
			'query_used' => $used
		);
	}

	private function gradeChunks($rows, $good_distance) {
		$out = array();

		foreach ($rows as $row) {
			$match = isset($row['match']) ? $row['match'] : '';

			if ($match === 'both') {
				$row['confidence'] = 'high';
			} elseif ($match === 'text') {
				$row['confidence'] = 'medium';
			} elseif (isset($row['distance']) && ($row['distance'] !== null) && ((float)$row['distance'] <= $good_distance)) {
				$row['confidence'] = 'medium';
			} else {
				continue;
			}

			$out[] = $row;
		}

		return $out;
	}

	// La pregunta reducida a palabras clave, sin operadores del modo booleano.
	private function condenseQuery($query) {
		return trim(preg_replace('/\s+/', ' ', str_replace(array('+', '"', '*'), ' ', $this->buildFulltextQuery($query))));
	}

	// Respuesta TIPADA de las herramientas de busqueda en documentos: estructura fija, cada fragmento con
	// su referencia [n] y su fuente, y un estado explicito. Si no hay evidencia suficiente, el modelo recibe
	// la orden de decirlo en lugar de inventar. (La validacion de la respuesta final del modelo no es posible
	// sin otra llamada; aqui se acota lo que el modelo recibe y lo que se le exige.)
	private function formatRagResponse($result) {
		if (isset($result['error'])) {
			return json_encode($result);
		}

		$labels = array(
			'customer_document' => 'Documento de cliente', 'customer_note' => 'Nota de cliente',
			'email_sent' => 'Email enviado', 'email_received' => 'Email recibido',
			'supplier_document' => 'Documento de proveedor', 'supplier_note' => 'Nota de proveedor'
		);

		$results = array();

		foreach (array_values($result['rows']) as $i => $row) {
			$type   = isset($row['document_type']) ? $row['document_type'] : 'product_document';
			$entity = '';

			foreach (array('customer_name', 'supplier_name', 'product_name') as $k) {
				if (!empty($row[$k])) {
					$entity = $row[$k];
				}
			}

			$results[] = array(
				'ref'        => '[' . ($i + 1) . ']',
				'source'     => array(
					'type'     => isset($labels[$type]) ? $labels[$type] : 'Documento de producto',
					'entity'   => $entity,
					'document' => isset($row['document']) ? $row['document'] : '',
					'page'     => isset($row['page']) ? (int)$row['page'] : 0
				),
				'confidence' => $row['confidence'],
				'passage'    => $row['chunk_text']
			);
		}

		$status = $results ? 'ok' : 'insufficient_evidence';

		return json_encode(array(
			'status'       => $status,
			'attempts'     => isset($result['attempts']) ? $result['attempts'] : 1,
			'query_used'   => isset($result['query_used']) ? $result['query_used'] : '',
			'results'      => $results,
			'instructions' => ($status === 'ok')
				? 'Responde SOLO con lo que digan los fragmentos y cita cada dato con su referencia, p. ej. [1], indicando la fuente. Si los fragmentos no contestan la pregunta, dilo.'
				: 'No se ha encontrado informacion suficiente en los documentos. Responde que no consta esa informacion; no la inventes ni la deduzcas.'
		));
	}

	private function searchProductDocuments($query, $product_id = 0, $limit = 5) {
		$where = $product_id ? "product_id = '" . (int)$product_id . "'" : '1 = 1';

		return $this->searchWithCorrection('document, product_id, product_name, page, chunk_text', $where, $query, $limit);
	}

	// Igual que searchProductDocuments() pero acotada a un customer_id concreto
	// (documentos, notas y emails de sale/customer/update). customer_id NUNCA es
	// opcional aqui (a diferencia de product_id en la busqueda de producto): al
	// ser if_document_chunks un indice compartido por todos los clientes, omitir
	// el filtro expondria pasajes de otros clientes en la respuesta del chat.
	private function searchCustomerDocuments($query, $customer_id, $document_type = '', $limit = 5) {
		$where = "customer_id = '" . (int)$customer_id . "'";

		if ($document_type !== '') {
			$where .= " AND document_type = '" . $this->db->escape($document_type) . "'";
		}

		return $this->searchWithCorrection('document, document_type, customer_id, customer_name, page, chunk_text', $where, $query, $limit);
	}

	// Igual que searchCustomerDocuments() pero acotada a un supplier_id concreto
	// (documentos y notas de purchase/supplier). supplier_id NUNCA es opcional aqui,
	// mismo motivo que customer_id en searchCustomerDocuments().
	private function searchSupplierDocuments($query, $supplier_id, $document_type = '', $limit = 5) {
		$where = "supplier_id = '" . (int)$supplier_id . "'";

		if ($document_type !== '') {
			$where .= " AND document_type = '" . $this->db->escape($document_type) . "'";
		}

		return $this->searchWithCorrection('document, document_type, supplier_id, supplier_name, page, chunk_text', $where, $query, $limit);
	}

	private function generateEmbedding($text) {
		$chat_url = (string)$this->config->get('config_ollama_url');

		if ($chat_url === '') {
			$chat_url = 'http://127.0.0.1:11434/api/chat';
		}

		$base = preg_replace('#/api/.*$#', '', $chat_url);
		$url  = rtrim($base, '/') . '/api/embed';

		$payload = array('model' => 'nomic-embed-text', 'input' => $text);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		$raw = curl_exec($ch);
		if (PHP_VERSION_ID < 80000) {
			curl_close($ch);
		}

		if ($raw === false) {
			return false;
		}

		$resp = json_decode($raw, true);

		if (empty($resp['embeddings'][0]) || !is_array($resp['embeddings'][0])) {
			return false;
		}

		return $resp['embeddings'][0];
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