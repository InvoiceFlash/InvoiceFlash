<?php
class ControllerToolUserLogs extends Controller {

	public function index() {
		$this->load->language('tool/user_logs');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/user_logs');
		$this->getList();
	}

	public function delete() {
		$this->load->language('tool/user_logs');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/user_logs');

		if (isset($this->request->post['selected']) && $this->user->hasPermission('modify', 'tool/user_logs')) {
			foreach ($this->request->post['selected'] as $log_id) {
				$this->model_tool_user_logs->deleteLog($log_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->redirect($this->url->link('tool/user_logs', 'token=' . $this->session->data['token'], 'SSL'));
	}

	private function getList() {
		$filter_username  = isset($this->request->get['filter_username'])  ? $this->request->get['filter_username']  : '';
		$filter_action    = isset($this->request->get['filter_action'])    ? $this->request->get['filter_action']    : '';
		$filter_date_from = isset($this->request->get['filter_date_from']) ? $this->request->get['filter_date_from'] : '';
		$filter_date_to   = isset($this->request->get['filter_date_to'])   ? $this->request->get['filter_date_to']   : '';
		$filter_reference = isset($this->request->get['filter_reference']) ? $this->request->get['filter_reference'] : '';
		$page             = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
		$limit            = 50;

		// Convertir DD-MM-YYYY a YYYY-MM-DD para la query
		$db_date_from = $this->convertDate($filter_date_from);
		$db_date_to   = $this->convertDate($filter_date_to);

		$filter_data = array(
			'filter_username'  => $filter_username,
			'filter_action'    => $filter_action,
			'filter_date_from' => $db_date_from,
			'filter_date_to'   => $db_date_to,
			'filter_reference' => $filter_reference,
			'start'            => ($page - 1) * $limit,
			'limit'            => $limit,
		);

		$results = $this->model_tool_user_logs->getLogs($filter_data);
		$total   = $this->model_tool_user_logs->getTotalLogs($filter_data);
		$users   = $this->model_tool_user_logs->getUsers();

		$logs = array();
		foreach ($results as $row) {
			switch ($row['action']) {
				case 'login':  $action_label = $this->language->get('text_login');  break;
				case 'create': $action_label = $this->language->get('text_create'); break;
				case 'edit':   $action_label = $this->language->get('text_edit');   break;
				case 'delete': $action_label = $this->language->get('text_delete'); break;
				default:       $action_label = $row['action'];
			}

			switch ($row['document_type']) {
				case 'sale_invoice':     $doc_label = $this->language->get('text_sale_invoice');     break;
				case 'purchase_invoice': $doc_label = $this->language->get('text_purchase_invoice'); break;
				case 'quote':            $doc_label = $this->language->get('text_quote');             break;
				case 'sale_order':       $doc_label = $this->language->get('text_sale_order');        break;
				case 'sale_delivery':    $doc_label = $this->language->get('text_sale_delivery');     break;
				case 'sale_draft':       $doc_label = $this->language->get('text_sale_draft');        break;
				case 'customer':         $doc_label = $this->language->get('text_customer');          break;
				case 'supplier':         $doc_label = $this->language->get('text_supplier');          break;
				case 'purchase_order':   $doc_label = $this->language->get('text_purchase_order');    break;
				default:                 $doc_label = '';
			}

			$href = '';
			if ($row['document_type'] === 'customer' && $row['document_id']) {
				$href = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'supplier' && $row['document_id']) {
				$href = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'purchase_order' && $row['document_id']) {
				$href = $this->url->link('purchase/purchase_order/update', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'sale_invoice' && $row['document_id']) {
				$href = $this->url->link('sale/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'purchase_invoice' && $row['document_id']) {
				$href = $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'quote' && $row['document_id']) {
				$href = $this->url->link('sale/quote/info', 'token=' . $this->session->data['token'] . '&quote_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'sale_order' && $row['document_id']) {
				$href = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'sale_delivery' && $row['document_id']) {
				$href = $this->url->link('sale/delivery/info', 'token=' . $this->session->data['token'] . '&delivery_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'sale_draft' && $row['document_id']) {
				$href = $this->url->link('sale/draft/info', 'token=' . $this->session->data['token'] . '&draft_id=' . (int)$row['document_id'], 'SSL');
			}

			$original = !empty($row['original']) ? json_decode($row['original'], true) : array();
			$cambiado = !empty($row['cambiado']) ? json_decode($row['cambiado'], true) : array();

			$changes = array();
			if ($original && $cambiado) {
				foreach ($cambiado as $field => $new_value) {
					$changes[] = array(
						'field'    => ucfirst(str_replace('_', ' ', $field)),
						'original' => isset($original[$field]) ? $original[$field] : '',
						'changed'  => $new_value,
					);
				}
			}

			$logs[] = array(
				'log_id'      => $row['log_id'],
				'date_added'  => $row['date_added'],
				'username'    => $row['username'],
				'action'      => $action_label,
				'action_raw'  => $row['action'],
				'document'    => $doc_label,
				'reference'   => $row['document_ref'],
				'href'        => $href,
				'ip'          => $row['ip'],
				'changes'     => $changes,
			);
		}

		$url = '';
		if ($filter_username)  $url .= '&filter_username='  . urlencode($filter_username);
		if ($filter_action)    $url .= '&filter_action='    . urlencode($filter_action);
		if ($filter_date_from) $url .= '&filter_date_from=' . urlencode($filter_date_from);
		if ($filter_date_to)   $url .= '&filter_date_to='   . urlencode($filter_date_to);
		if ($filter_reference) $url .= '&filter_reference=' . urlencode($filter_reference);

		$this->load->library('pagination');
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page  = $page;
		$pagination->limit = $limit;
		$pagination->text  = '{start}-{end} / {total}';
		$pagination->url   = $this->url->link('tool/user_logs', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['logs']             = $logs;
		$this->data['delete']           = $this->url->link('tool/user_logs/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['pagination']       = $pagination->render();
		$this->data['users']            = $users;
		$this->data['token']            = $this->session->data['token'];
		$this->data['filter_username']  = $filter_username;
		$this->data['filter_action']    = $filter_action;
		$this->data['filter_date_from'] = $filter_date_from;
		$this->data['filter_date_to']   = $filter_date_to;
		$this->data['filter_reference'] = $filter_reference;

		$this->data['heading_title']    = $this->language->get('heading_title');
		$this->data['text_no_results']  = $this->language->get('text_no_results');
		$this->data['text_login']       = $this->language->get('text_login');
		$this->data['text_create']      = $this->language->get('text_create');
		$this->data['text_edit']        = $this->language->get('text_edit');
		$this->data['text_delete']      = $this->language->get('text_delete');
		$this->data['column_date_from']  = $this->language->get('column_date_from');
		$this->data['column_username']  = $this->language->get('column_username');
		$this->data['column_action']    = $this->language->get('column_action');
		$this->data['column_document']  = $this->language->get('column_document');
		$this->data['column_reference'] = $this->language->get('column_reference');
		$this->data['column_ip']        = $this->language->get('column_ip');
		$this->data['column_changes']   = $this->language->get('column_changes');
		$this->data['filter_reference_placeholder'] = $this->language->get('filter_reference');
		$this->data['button_filter']    = $this->language->get('button_filter');
		$this->data['button_delete']    = $this->language->get('button_delete');
		$this->data['button_view_changes'] = $this->language->get('button_view_changes');
		$this->data['text_no_changes']  = $this->language->get('text_no_changes');
		$this->data['text_changes_title'] = $this->language->get('text_changes_title');
		$this->data['text_field']       = $this->language->get('text_field');
		$this->data['text_original']    = $this->language->get('text_original');
		$this->data['text_changed']     = $this->language->get('text_changed');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/user_logs', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->template = 'tool/user_logs_list.tpl';
		$this->children = array('common/header', 'common/footer');
		$this->response->setOutput($this->render());
	}

	private function convertDate($date) {
		// Convierte DD-MM-YYYY (formato datepicker) a YYYY-MM-DD (formato MySQL)
		if ($date && preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $date, $m)) {
			return $m[3] . '-' . $m[2] . '-' . $m[1];
		}
		return '';
	}
}
?>
