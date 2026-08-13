<?php
class ControllerToolPendingInvoices extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('tool/pending_invoices');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('tool/pending_invoices');
		$this->getList();
	}

	public function incorporate() {
		$this->load->language('tool/pending_invoices');
		$this->load->model('tool/pending_invoices');

		if (!$this->user->hasPermission('modify', 'tool/pending_invoices')) {
			$this->session->data['error'] = $this->language->get('error_permission');
			$this->redirect($this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$pending_id = isset($this->request->get['pending_id']) ? (int)$this->request->get['pending_id'] : 0;
		$pending_info = $this->model_tool_pending_invoices->getPendingInvoice($pending_id);

		if (!$pending_info) {
			$this->session->data['error'] = $this->language->get('error_not_found');
			$this->redirect($this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$supplier_data = json_decode($pending_info['supplier_data'], true);
		$invoice_data  = json_decode($pending_info['invoice_data'], true);
		$supplier_id   = isset($supplier_data['supplier_id']) ? (int)$supplier_data['supplier_id'] : 0;

		$this->load->model('purchase/supplier');
		$supplier_info = $supplier_id ? $this->model_purchase_supplier->getSupplier($supplier_id) : false;

		if (!$supplier_info) {
			$this->session->data['error'] = $this->language->get('error_supplier_not_found');
			$this->redirect($this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$invoice_products = array();

		foreach ((isset($invoice_data['lines']) ? $invoice_data['lines'] : array()) as $line) {
			$invoice_products[] = array(
				'invoice_product_id' => 0,
				'product_id'         => 0,
				'name'               => isset($line['description']) ? $line['description'] : '',
				'model'              => '',
				'quantity'           => isset($line['quantity']) ? $line['quantity'] : 1,
				'price'              => isset($line['price']) ? $line['price'] : 0,
				'discount'           => isset($line['discount']) ? $line['discount'] : 0,
				'tax'                => isset($line['tax_amount']) ? $line['tax_amount'] : 0,
			);
		}

		$sub_total_sort = (int)$this->config->get('sub_total_sort_order');
		$tax_sort       = (int)$this->config->get('tax_sort_order');
		$total_sort     = (int)$this->config->get('total_sort_order');

		$invoice_totals = array();

		$invoice_totals[] = array(
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format(isset($invoice_data['sub_total']) ? $invoice_data['sub_total'] : 0, '', '', true, true),
			'value'      => isset($invoice_data['sub_total']) ? $invoice_data['sub_total'] : 0,
			'sort_order' => $sub_total_sort,
		);

		foreach ((isset($invoice_data['tax_rows']) ? $invoice_data['tax_rows'] : array()) as $tax_row) {
			$title = isset($tax_row['rate']) && $tax_row['rate'] !== null
				? sprintf($this->language->get('text_vat_rate'), round($tax_row['rate']))
				: $this->language->get('text_vat');

			$invoice_totals[] = array(
				'code'       => 'tax',
				'title'      => $title,
				'text'       => $this->currency->format($tax_row['amount'], '', '', true, true),
				'value'      => $tax_row['amount'],
				'sort_order' => $tax_sort,
			);
		}

		$invoice_totals[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'text'       => $this->currency->format(isset($invoice_data['total']) ? $invoice_data['total'] : 0, '', '', true, true),
			'value'      => isset($invoice_data['total']) ? $invoice_data['total'] : 0,
			'sort_order' => $total_sort,
		);

		$data = array(
			'store_id'            => 0,
			'supplier_id'         => $supplier_id,
			'supplier_invoice_no' => isset($invoice_data['number']) ? $invoice_data['number'] : '',
			'email'               => $supplier_info['email'],
			'telephone'           => $supplier_info['telephone'],
			'fax'                 => $supplier_info['fax'],
			'payment_company'     => $supplier_info['company'],
			'payment_company_id'  => $supplier_info['company_id'],
			'payment_tax_id'      => $supplier_info['tax_id'],
			'payment_address_1'   => $supplier_info['address_1'],
			'payment_address_2'   => $supplier_info['address_2'],
			'payment_city'        => $supplier_info['city'],
			'payment_postcode'    => $supplier_info['postcode'],
			'payment_country_id'  => $supplier_info['country_id'],
			'payment_zone_id'     => $supplier_info['zone_id'],
			'payment_method'      => '',
			'payment_code'        => '',
			'shipping_company'    => '',
			'shipping_address_1'  => '',
			'shipping_address_2'  => '',
			'shipping_city'       => '',
			'shipping_postcode'   => '',
			'shipping_country_id' => 0,
			'shipping_zone_id'    => 0,
			'shipping_method'     => '',
			'shipping_code'       => '',
			'comment'             => sprintf($this->language->get('text_incorporated_comment'), $pending_info['subject']),
			'invoice_status_id'   => 1,
			'invoice_product'     => $invoice_products,
			'invoice_total'       => $invoice_totals,
		);

		$this->load->model('purchase/invoice');
		$new_invoice_id = $this->model_purchase_invoice->addInvoice($data);

		// Mueve el adjunto de la carpeta de pendientes a la carpeta estándar de
		// facturas de proveedor, igual que hace el import automático.
		if (!empty($pending_info['attachment_path']) && is_file($pending_info['attachment_path'])) {
			$project_root = rtrim(str_replace('\\', '/', dirname(DIR_APPLICATION)), '/');
			$new_dir = $project_root . '/docs/suppliers/invoices/' . date('Y-m') . '/' . $new_invoice_id . '/';

			if (!is_dir($new_dir)) {
				mkdir($new_dir, 0755, true);
			}

			$new_path = $new_dir . basename($pending_info['attachment_path']);

			if (rename($pending_info['attachment_path'], $new_path)) {
				$this->db->query("UPDATE `" . DB_PREFIX . "purchase_invoice` SET attachment_path = '" . $this->db->escape($new_path) . "' WHERE invoice_id = '" . (int)$new_invoice_id . "'");
				@rmdir(dirname($pending_info['attachment_path']));
			}
		}

		$this->load->model('tool/user_logs');
		$this->model_tool_user_logs->addLog(array(
			'user_id'       => $this->user->getId(),
			'username'      => $this->user->getUserName(),
			'action'        => 'create',
			'document_type' => 'purchase_invoice',
			'document_id'   => (int)$new_invoice_id,
			'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
		));

		$this->model_tool_pending_invoices->deletePendingInvoice($pending_id);

		$this->session->data['success'] = $this->language->get('text_success_incorporate');
		$this->redirect($this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function delete() {
		$this->load->language('tool/pending_invoices');
		$this->load->model('tool/pending_invoices');

		if (isset($this->request->post['selected']) && $this->user->hasPermission('modify', 'tool/pending_invoices')) {
			foreach ($this->request->post['selected'] as $pending_id) {
				$pending_info = $this->model_tool_pending_invoices->getPendingInvoice($pending_id);

				if ($pending_info && !empty($pending_info['attachment_path']) && is_file($pending_info['attachment_path'])) {
					@unlink($pending_info['attachment_path']);
					@rmdir(dirname($pending_info['attachment_path']));
				}

				$this->model_tool_pending_invoices->deletePendingInvoice($pending_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->redirect($this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL'));
	}

	protected function getList() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results']    = $this->language->get('text_no_results');
		$this->data['column_subject']     = $this->language->get('column_subject');
		$this->data['column_from']        = $this->language->get('column_from');
		$this->data['column_supplier']    = $this->language->get('column_supplier');
		$this->data['column_total']       = $this->language->get('column_total');
		$this->data['column_reason']      = $this->language->get('column_reason');
		$this->data['column_date_added']  = $this->language->get('column_date_added');
		$this->data['column_action']      = $this->language->get('column_action');
		$this->data['button_incorporate'] = $this->language->get('button_incorporate');
		$this->data['button_delete']      = $this->language->get('button_delete');
		$this->data['button_view']        = $this->language->get('button_view');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;

		$data = array(
			'start' => ($page - 1) * 20,
			'limit' => 20,
		);

		$results = $this->model_tool_pending_invoices->getPendingInvoices($data);

		$this->data['pending_invoices'] = array();

		foreach ($results as $result) {
			$supplier_data = json_decode($result['supplier_data'], true);
			$invoice_data  = json_decode($result['invoice_data'], true);

			$this->data['pending_invoices'][] = array(
				'pending_id'  => $result['pending_id'],
				'subject'     => $result['subject'],
				'from_email'  => $result['from_email'],
				'supplier'    => $supplier_data && !empty($supplier_data['company']) ? $supplier_data['company'] : '',
				'total'       => $invoice_data && isset($invoice_data['total']) ? $this->currency->format($invoice_data['total'], '', '', true, true) : '',
				'reason'      => $result['reason'],
				'date_added'  => date($this->language->get('date_format_short') . ' H:i', strtotime($result['date_added'])),
				'view'        => !empty($result['attachment_path']) ? $this->url->link('tool/pending_invoices/view', 'token=' . $this->session->data['token'] . '&pending_id=' . $result['pending_id'], 'SSL') : '',
				'incorporate' => $this->url->link('tool/pending_invoices/incorporate', 'token=' . $this->session->data['token'] . '&pending_id=' . $result['pending_id'], 'SSL'),
			);
		}

		$total = $this->model_tool_pending_invoices->getTotalPendingInvoices();

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = 20;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('tool/pending_invoices', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['token'] = $this->session->data['token'];
		$this->data['delete'] = $this->url->link('tool/pending_invoices/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'tool/pending_invoices_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function view() {
		if (!$this->user->hasPermission('access', 'tool/pending_invoices')) {
			http_response_code(403);
			exit('Permission denied');
		}

		if (empty($this->request->get['pending_id'])) {
			http_response_code(400);
			exit('Missing pending_id');
		}

		$this->load->model('tool/pending_invoices');
		$pending_info = $this->model_tool_pending_invoices->getPendingInvoice((int)$this->request->get['pending_id']);

		if (!$pending_info || empty($pending_info['attachment_path']) || !is_file($pending_info['attachment_path'])) {
			http_response_code(404);
			exit('Document not found');
		}

		$file = $pending_info['attachment_path'];
		$ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		$mime_types = array(
			'pdf'  => 'application/pdf',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png'  => 'image/png',
			'xml'  => 'application/xml',
		);

		$content_type = isset($mime_types[$ext]) ? $mime_types[$ext] : 'application/octet-stream';

		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		header('Content-Type: ' . $content_type);
		header('Content-Disposition: inline; filename="' . basename($pending_info['attachment_filename']) . '"');
		header('Content-Length: ' . filesize($file));
		header('Cache-Control: private');
		readfile($file);
		exit;
	}
}
