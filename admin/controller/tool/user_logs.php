<?php
class ControllerToolUserLogs extends Controller {

	public function index() {
		$this->load->language('tool/user_logs');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/user_logs');
		$this->getList();
	}

	private function getList() {
		$filter_username  = isset($this->request->get['filter_username'])  ? $this->request->get['filter_username']  : '';
		$filter_action    = isset($this->request->get['filter_action'])    ? $this->request->get['filter_action']    : '';
		$filter_date_from = isset($this->request->get['filter_date_from']) ? $this->request->get['filter_date_from'] : '';
		$filter_date_to   = isset($this->request->get['filter_date_to'])   ? $this->request->get['filter_date_to']   : '';
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
				default:       $action_label = $row['action'];
			}

			switch ($row['document_type']) {
				case 'sale_invoice':     $doc_label = $this->language->get('text_sale_invoice');     break;
				case 'purchase_invoice': $doc_label = $this->language->get('text_purchase_invoice'); break;
				default:                 $doc_label = '';
			}

			$href = '';
			if ($row['document_type'] === 'sale_invoice' && $row['document_id']) {
				$href = $this->url->link('sale/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$row['document_id'], 'SSL');
			} elseif ($row['document_type'] === 'purchase_invoice' && $row['document_id']) {
				$href = $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$row['document_id'], 'SSL');
			}

			$logs[] = array(
				'date_added'  => $row['date_added'],
				'username'    => $row['username'],
				'action'      => $action_label,
				'action_raw'  => $row['action'],
				'document'    => $doc_label,
				'reference'   => $row['document_ref'],
				'href'        => $href,
				'ip'          => $row['ip'],
			);
		}

		$url = '';
		if ($filter_username)  $url .= '&filter_username='  . urlencode($filter_username);
		if ($filter_action)    $url .= '&filter_action='    . urlencode($filter_action);
		if ($filter_date_from) $url .= '&filter_date_from=' . urlencode($filter_date_from);
		if ($filter_date_to)   $url .= '&filter_date_to='   . urlencode($filter_date_to);

		$this->load->library('pagination');
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page  = $page;
		$pagination->limit = $limit;
		$pagination->text  = '{start}-{end} / {total}';
		$pagination->url   = $this->url->link('tool/user_logs', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['logs']             = $logs;
		$this->data['pagination']       = $pagination->render();
		$this->data['users']            = $users;
		$this->data['token']            = $this->session->data['token'];
		$this->data['filter_username']  = $filter_username;
		$this->data['filter_action']    = $filter_action;
		$this->data['filter_date_from'] = $filter_date_from;
		$this->data['filter_date_to']   = $filter_date_to;

		$this->data['heading_title']    = $this->language->get('heading_title');
		$this->data['text_no_results']  = $this->language->get('text_no_results');
		$this->data['text_login']       = $this->language->get('text_login');
		$this->data['text_create']      = $this->language->get('text_create');
		$this->data['text_edit']        = $this->language->get('text_edit');
		$this->data['column_date_from']  = $this->language->get('column_date_from');
		$this->data['column_date_to']    = $this->language->get('column_date_to');
		$this->data['column_username']  = $this->language->get('column_username');
		$this->data['column_action']    = $this->language->get('column_action');
		$this->data['column_document']  = $this->language->get('column_document');
		$this->data['column_reference'] = $this->language->get('column_reference');
		$this->data['column_ip']        = $this->language->get('column_ip');
		$this->data['button_filter']    = $this->language->get('button_filter');

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
