<?php

class ControllerPurchaseSupplier extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		$this->getList();
	}

	public function insert() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$supplier_id = $this->model_purchase_supplier->addSupplier($this->request->post);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'create',
				'document_type' => 'supplier',
				'document_id'   => (int)$supplier_id,
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_id, 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function update() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$supplier_before = $this->model_purchase_supplier->getSupplier($this->request->get['supplier_id']);

			$this->model_purchase_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);

			$diff = $this->diffFields($supplier_before, $this->request->post);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'edit',
				'document_type' => 'supplier',
				'document_id'   => (int)$this->request->get['supplier_id'],
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				'original'      => $diff['original'],
				'cambiado'      => $diff['changed'],
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$this->getList();
		} else {
			$this->getForm();
		}
	}

	public function delete() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->load->model('tool/user_logs');

			foreach ($this->request->post['selected'] as $supplier_id) {
				$this->model_purchase_supplier->deleteSupplier($supplier_id);

				$this->model_tool_user_logs->addLog(array(
					'user_id'       => $this->user->getId(),
					'username'      => $this->user->getUserName(),
					'action'        => 'delete',
					'document_type' => 'supplier',
					'document_id'   => (int)$supplier_id,
					'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				));
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function export() {
		$this->load->language('purchase/supplier');

		if (!$this->user->hasPermission('access', 'purchase/supplier') || empty($this->request->post['selected'])) {
			$this->redirect($this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('purchase/supplier');

		$suppliers = $this->model_purchase_supplier->getSuppliersByIds($this->request->post['selected']);

		require_once(DIR_SYSTEM . 'library/xlsx.php');

		$xlsx = new Xlsx();

		$xlsx->setHeaders(array(
			$this->language->get('column_company'),
			$this->language->get('column_name'),
			$this->language->get('column_email'),
			$this->language->get('column_telephone'),
			$this->language->get('column_status'),
			$this->language->get('column_date_added')
		));

		foreach ($suppliers as $supplier) {
			$xlsx->addRow(array(
				$supplier['company'],
				trim($supplier['firstname'] . ' ' . $supplier['lastname']),
				$supplier['email'],
				$supplier['telephone'],
				$supplier['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				date($this->language->get('date_format_short'), strtotime($supplier['date_added']))
			));
		}

		$this->response->addHeader('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->response->addHeader('Content-Disposition: attachment; filename="suppliers_' . date('Y-m-d') . '.xlsx"');
		$this->response->setOutput($xlsx->build($this->language->get('heading_title')));
	}

	public function autocomplete() {
		$this->load->model('purchase/supplier');

		$filter_name = isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '';

		$json = array();

		if ($filter_name) {
			$results = $this->model_purchase_supplier->autocomplete($filter_name);

			foreach ($results as $result) {
				$json[] = array(
					'supplier_id' => $result['supplier_id'],
					'name'        => $result['company'],
					'email'       => $result['email'],
					'telephone'   => $result['telephone'],
					'fax'         => $result['fax']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	// Datos de solo lectura para el modal "ojo" de detalles del proveedor
	// (p.ej. purchase/purchase_order/insert, que no guarda su propia copia
	// de la dirección/contacto del proveedor como sí hace purchase/invoice).
	public function getDetails() {
		$this->load->model('purchase/supplier');

		$supplier_id = isset($this->request->get['supplier_id']) ? (int)$this->request->get['supplier_id'] : 0;

		$supplier_info = $supplier_id ? $this->model_purchase_supplier->getSupplier($supplier_id) : false;

		if ($supplier_info) {
			$json = array(
				'company'    => $supplier_info['company'],
				'tax_id'     => $supplier_info['tax_id'],
				'email'      => $supplier_info['email'],
				'telephone'  => $supplier_info['telephone'],
				'fax'        => $supplier_info['fax'],
				'address_1'  => $supplier_info['address_1'],
				'address_2'  => $supplier_info['address_2'],
				'city'       => $supplier_info['city'],
				'postcode'   => $supplier_info['postcode'],
				'country'    => $supplier_info['country'],
				'zone'       => $supplier_info['zone']
			);
		} else {
			$json = array('error' => 'not_found');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function searchSuppliers() {
		$this->load->model('purchase/supplier');

		$filter_company = isset($this->request->post['filter_company']) ? html_entity_decode($this->request->post['filter_company'], ENT_QUOTES, 'UTF-8') : '';

		$data = array(
			'filter_company' => $filter_company,
			'sort'           => 'company',
			'order'          => 'ASC',
			'start'          => 0,
			'limit'          => 200
		);

		$total = $this->model_purchase_supplier->getTotalSuppliers($data);

		$this->response->addHeader('Content-Type: application/json');

		if ($total > 200) {
			$this->response->setOutput(json_encode(array('warning' => 'Hay más de 200 proveedores. Añade un filtro por nombre/empresa para acotar la búsqueda.')));
			return;
		}

		$results = $this->model_purchase_supplier->getSuppliers($data);

		$json = array();

		foreach ($results as $result) {
			$json[] = array(
				'supplier_id' => $result['supplier_id'],
				'company'     => strip_tags(html_entity_decode($result['company'], ENT_QUOTES, 'UTF-8')),
				'email'       => $result['email'],
				'telephone'   => $result['telephone'],
				'fax'         => $result['fax']
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	protected function getList() {
		$filter_company = isset($this->request->get['filter_company']) ? $this->request->get['filter_company'] : null;
		$filter_email = isset($this->request->get['filter_email']) ? $this->request->get['filter_email'] : null;
		$filter_status = isset($this->request->get['filter_status']) ? $this->request->get['filter_status'] : null;

		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'company';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$url = '';

		foreach (array('filter_company', 'filter_email', 'filter_status', 'sort', 'order', 'page') as $key) {
			if (isset($this->request->get[$key])) {
				$url .= '&' . $key . '=' . (($key == 'filter_company' || $key == 'filter_email') ? urlencode(html_entity_decode($this->request->get[$key], ENT_QUOTES, 'UTF-8')) : $this->request->get[$key]);
			}
		}

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['insert'] = $this->url->link('purchase/supplier/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('purchase/supplier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['export'] = $this->url->link('purchase/supplier/export', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data = array(
			'filter_company' => $filter_company,
			'filter_email'   => $filter_email,
			'filter_status'  => $filter_status,
			'sort'           => $sort,
			'order'          => $order,
			'start'          => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'          => $this->config->get('config_admin_limit')
		);

		$supplier_total = $this->model_purchase_supplier->getTotalSuppliers($data);

		$results = $this->model_purchase_supplier->getSuppliers($data);

		$this->data['suppliers'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'href'  => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $result['supplier_id'] . $url, 'SSL'),
				'icon'  => 'fas fa-edit',
				'color' => 'default'
			);

			$this->data['suppliers'][] = array(
				'supplier_id' => $result['supplier_id'],
				'company'     => $result['company'],
				'name'        => trim($result['firstname'] . ' ' . $result['lastname']),
				'email'       => $result['email'],
				'telephone'   => $result['telephone'],
				'status'      => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['supplier_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['column_company'] = $this->language->get('column_company');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_export'] = $this->language->get('button_export');

		$this->data['token'] = $this->session->data['token'];

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['sort_company'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=company' . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=email' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $supplier_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_company'] = $filter_company;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'purchase/supplier_list.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_contable_account'] = $this->language->get('entry_contable_account');
		$this->data['contable_account_maxlength'] = (int)$this->config->get('config_conta_digits') ?: 10;
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_web'] = $this->language->get('entry_web');
		$this->data['button_web'] = $this->language->get('button_web');
		$this->data['error_web'] = $this->language->get('error_web');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_notes'] = $this->language->get('tab_notes');
		$this->data['tab_contacts'] = $this->language->get('tab_contacts');
		$this->data['tab_contracts'] = $this->language->get('tab_contracts');
		$this->data['tab_email'] = $this->language->get('tab_email');
		$this->data['tab_orders'] = $this->language->get('tab_orders');
		$this->data['tab_recepciones'] = $this->language->get('tab_recepciones');
		$this->data['tab_products'] = $this->language->get('tab_products');
		$this->data['tab_invoices'] = $this->language->get('tab_invoices');

		$this->data['column_product_id'] = $this->language->get('column_product_id');
		$this->data['column_product_name'] = $this->language->get('column_product_name');
		$this->data['column_invoice'] = $this->language->get('column_invoice');
		$this->data['column_invoice_date'] = $this->language->get('column_invoice_date');
		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_email_subject'] = $this->language->get('column_email_subject');
		$this->data['column_email_sender'] = $this->language->get('column_email_sender');
		$this->data['text_view'] = $this->language->get('text_view');

		$this->data['column_contact_name'] = $this->language->get('column_contact_name');
		$this->data['column_contact_email'] = $this->language->get('column_contact_email');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_comment'] = $this->language->get('column_comment');
		$this->data['column_user'] = $this->language->get('column_user');
		$this->data['column_date'] = $this->language->get('column_date');

		// New Email (Modal)
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_subject'] = $this->language->get('text_subject');
		$this->data['text_message'] = $this->language->get('text_message');
		$this->data['button_new_email'] = $this->language->get('button_new_email');
		$this->data['button_send'] = $this->language->get('button_send');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_contact'] = $this->language->get('button_add_contact');
		$this->data['button_add_contract'] = $this->language->get('button_add_contract');
		$this->data['button_view'] = $this->language->get('button_view');
		$this->data['button_add_note'] = $this->language->get('button_add_note');

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_company'] = isset($this->error['company']) ? $this->error['company'] : '';
		$this->data['error_email'] = isset($this->error['email']) ? $this->error['email'] : '';

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			)
		);

		if (!isset($this->request->get['supplier_id'])) {
			$this->data['action'] = $this->url->link('purchase/supplier/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/supplier', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$supplier_info = $this->model_purchase_supplier->getSupplier($this->request->get['supplier_id']);
		} else {
			$supplier_info = array();
		}

		$this->data['token'] = $this->session->data['token'];
		$this->data['supplier_id'] = isset($this->request->get['supplier_id']) ? $this->request->get['supplier_id'] : 0;

		$fields = array('firstname', 'lastname', 'company', 'company_id', 'tax_id', 'contable_account', 'email', 'telephone', 'fax', 'web', 'address_1', 'address_2', 'city', 'postcode', 'country_id', 'zone_id');

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($supplier_info)) {
				$this->data[$field] = $supplier_info[$field];
			} else {
				$this->data[$field] = '';
			}
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($supplier_info)) {
			$this->data['status'] = $supplier_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		// Emails (mismo patron que ControllerSaleCustomer::getForm())
		$this->data['emails'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getEmailsBySupplierId($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$sender = '';

				if ($result['type'] == 'E') {
					// Correo enviado por nosotros (ver ModelCatalogMail->addMailSended()): 'client'
					// guarda el destinatario, no el remitente - el remitente somos nosotros mismos.
					$sender = $this->config->get('config_name') . ' (' . $this->config->get('config_email') . ')';
				} else {
					// Correo recibido (importado por IMAP, ver ModelCatalogMail->getmails()):
					// 'client' guarda de verdad la direccion de quien lo envio.
					$arr_sender = $this->model_purchase_supplier->getSupplierByEmail($result['client']);

					if (empty($arr_sender)) {
						$arr_sender = $this->model_purchase_supplier->getSupplierContactByEmail($result['client']);

						if (empty($arr_sender)) {
							$sender = $result['client'];
						} else {
							$sender = $arr_sender['cname'] . ' (' . $arr_sender['cemail'] . ')';
						}
					} else {
						$sender = $arr_sender['company'] . ' (' . $arr_sender['email'] . ')';
					}
				}

				$message = html_entity_decode($result['message']);

				$message = strip_tags($message, '<br>');
				$message = str_replace("\n", "<br />", $message);

				$this->data['emails'][] = array(
					'mail_id'    => $result['mail_id'],
					'sender'     => $sender,
					'subject'    => $result['title'],
					'text'       => $message,
					'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
				);
			}
		}

		$this->data['new_email'] = $this->url->link('purchase/supplier/new_email', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		$this->data['contacts'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getSupplierContacts($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$link = $this->url->link('purchase/supplier/updateContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['supplier_contacts_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-default" href="' . $link . '"><i class="fa fa-edit"></i><span class="hidden-xs"> ' . $this->language->get('text_edit') . '</span></a>'
				);

				$link = $this->url->link('purchase/supplier/deleteContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['supplier_contacts_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-danger" href="' . $link . '"><i class="fa fa-trash"></i><span class="hidden-xs"> ' . $this->language->get('text_delete') . '</span></a>'
				);

				$this->data['contacts'][] = array(
					'contact_id' => $result['supplier_contacts_id'],
					'name'       => $result['cname'],
					'email'      => $result['cemail'],
					'telephone'  => $result['ctelef1'],
					'action'     => $action
				);
			}
		}

		$this->data['add_contact'] = $this->url->link('purchase/supplier/insertContact', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		$this->data['notes'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getSupplierNotes($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$note_info = $this->model_purchase_supplier->getSupplierNote($result['supplier_history_id']);

				$this->data['notes'][] = array(
					'note_id' => $result['supplier_history_id'],
					'date'    => date($this->language->get('date_format_short'), strtotime($note_info['date_added'])),
					'user'    => $note_info['user'],
					'comment' => nl2br($note_info['comment']),
					'delete'  => $this->url->link('purchase/supplier/deleteNote', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'] . '&note_id=' . $result['supplier_history_id'], 'SSL')
				);
			}
		}

		$this->data['add_note'] = $this->url->link('purchase/supplier/insertNote', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		// Documentos (mismo patron que sale/customer: ver ModelSaleCustomer::getCustomerDocuments()
		// y el tab #tab-contracts de customer_form.tpl)
		$this->data['contracts'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getSupplierDocuments($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$link = $this->url->link('purchase/supplier/viewContract', 'token=' . $this->session->data['token'] . '&document_id=' . $result['document_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-default" href="' . $link . '" target="_blank"><i class="fa fa-eye"></i> <span class="hidden-xs">' . $this->language->get('button_view') . '</span></a>'
				);

				$link = $this->url->link('purchase/supplier/deleteContract', 'token=' . $this->session->data['token'] . '&document_id=' . $result['document_id'] . '&supplier_id=' . $supplier_info['supplier_id'], 'SSL');
				$action[] = array(
					'link' => '<a class="btn btn-danger" href="' . $link . '" onclick="return confirm(text_confirm);"><i class="fa fa-trash"></i> <span class="hidden-xs">' . $this->language->get('text_delete') . '</span></a>'
				);

				$this->data['contracts'][] = array(
					'document_id' => $result['document_id'],
					'filename'    => $result['filename'],
					'date_added'  => date($this->language->get('date_format_short') . ' H:i', strtotime($result['date_added'])),
					'action'      => $action
				);
			}
		}

		$this->data['has_documents'] = !empty($this->data['contracts']);

		$this->data['add_contract'] = $this->url->link('purchase/supplier/insertContract', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->data['supplier_id'], 'SSL');

		// Pedidos (Purchase Orders de este proveedor - mismo patron que tab_orders de cliente)
		$this->data['orders'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getPurchaseOrdersSupplier($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('purchase/purchase_order/update', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . $result['purchase_order_id'], 'SSL')
				);

				$this->data['orders'][] = array(
					'order_id' => $result['purchase_order_id'],
					'status'   => $result['status'],
					'date'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'action'   => $action,
					'total'    => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])
				);
			}
		}

		// Recepciones (mismos Pedidos de Compra, filtrados a los ya recibidos - no hay un
		// documento de "albaran de compra" propio, ver ModelPurchaseSupplier::getReceivedPurchaseOrdersSupplier())
		$this->data['receptions'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getReceivedPurchaseOrdersSupplier($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('purchase/purchase_order/update', 'token=' . $this->session->data['token'] . '&purchase_order_id=' . $result['purchase_order_id'], 'SSL')
				);

				$this->data['receptions'][] = array(
					'order_id' => $result['purchase_order_id'],
					'status'   => $result['status'],
					'date'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'action'   => $action,
					'total'    => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])
				);
			}
		}

		$this->data['products'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getProductsSupplier($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$this->data['products'][] = array(
					'product_id' => $result['product_id'],
					'name'       => $result['name'],
					'invoice_id' => $result['invoice_id'],
					'date'       => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'quantity'   => $result['quantity'],
					'total'      => $this->currency->format($result['total'], $this->config->get('config_currency')),
					'href'       => str_replace('&amp;', '&', $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'], 'SSL')),
				);
			}
		}

		$this->data['invoices'] = array();

		if (!empty($supplier_info)) {
			$results = $this->model_purchase_supplier->getInvoicesSupplier($supplier_info['supplier_id']);

			foreach ($results as $result) {
				$action = array();

				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('purchase/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'], 'SSL')
				);

				$this->data['invoices'][] = array(
					'invoice_id' => $result['invoice_id'],
					'date'       => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'action'     => $action,
					'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}
		}

		$this->template = 'purchase/supplier_form.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// Mismo patron que ControllerSaleCustomer::new_email() - el permiso se comprueba
	// contra catalog/mail (enviar correo), no purchase/supplier.
	public function new_email() {
		$this->language->load('purchase/supplier');
		$json = array();

		if ($this->user->hasPermission('modify', 'catalog/mail')) {

			if ($this->request->post['to'] == '' || filter_var($this->request->post['to'], FILTER_VALIDATE_EMAIL) == false) {
				$json['error']['to'] = $this->language->get('error_to');
			}

			if ($this->request->post['subject'] == '') {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if ($this->request->post['message'] == '') {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (empty($json['error'])) {
				$data['customer_id'] = 0;
				$data['potential_id'] = 0;
				$data['supplier_id'] = $this->request->get['supplier_id'];

				$data['to'] = $this->request->post['to'];
				$data['subject'] = $this->request->post['subject'];

				$data['text'] = $this->request->post['message'];
				$data['code'] = md5($this->request->post['message']);

				$data['file'] = '';
				if (is_file($this->request->post['filename'])) {
					$data['file'] = DIR_DOWNLOAD . $this->request->post['filename'];

					$newName = substr($data['file'], 0, strripos($data['file'], '.'));

					if (rename($data['file'], $newName)) {
						$data['file'] = $newName;
					}
				}

				$mail_error = $this->sendnewmail($data['to'], $data['subject'], $data['text'], $data['file']);

				if ($mail_error) {
					$json['error']['message'] = $mail_error;
				} else {
					$this->load->model('catalog/mail');

					$this->model_catalog_mail->addMailSended($data);

					$json['success'] = $this->language->get('text_success_email');
				}
			}
		} else {
			$json['error']['permission'] = $this->language->get('error_permission_email');
		}

		$this->response->setOutput(json_encode($json));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['company']) < 1) || (utf8_strlen($this->request->post['company']) > 92)) {
			$this->error['company'] = $this->language->get('error_company');
		}

		if ($this->request->post['email'] && !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function insertContact() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateContactForm()) {
			$this->model_purchase_supplier->addSupplierContact($this->request->post, $this->request->get['supplier_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getContactForm();
	}

	public function updateContact() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateContactForm()) {
			$this->model_purchase_supplier->editSupplierContact($this->request->post, $this->request->get['contact_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getContactForm();
	}

	public function deleteContact() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->get['contact_id']) && $this->validateDelete()) {
			$this->model_purchase_supplier->deleteSupplierContact($this->request->get['contact_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getForm();
	}

	private function validateContactForm() {
		if (!$this->user->hasPermission('modify', 'purchase/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (utf8_strlen($this->request->post['name']) < 3 || utf8_strlen($this->request->post['name']) > 50) {
			$this->error['name'] = $this->language->get('error_contact_name');
		}

		return !$this->error;
	}

	protected function getContactForm() {
		$this->data['heading_title'] = $this->language->get('heading_contact');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_telephone2'] = $this->language->get('entry_telephone2');
		$this->data['entry_puesto'] = $this->language->get('entry_puesto');
		$this->data['entry_notas'] = $this->language->get('entry_notas');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'),
				'separator' => ' :: '
			)
		);

		if (isset($this->request->get['contact_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$contact_info = $this->model_purchase_supplier->getSupplierContact($this->request->get['contact_id']);
		}

		if (isset($this->request->get['contact_id'])) {
			$this->data['contact_id'] = $this->request->get['contact_id'];
		} else {
			$this->data['contact_id'] = 0;
		}

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$this->data['error_name'] = isset($this->error['name']) ? $this->error['name'] : '';

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($contact_info)) {
			$this->data['name'] = $contact_info['cname'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (isset($contact_info)) {
			$this->data['email'] = $contact_info['cemail'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['telef1'])) {
			$this->data['telef1'] = $this->request->post['telef1'];
		} elseif (isset($contact_info)) {
			$this->data['telef1'] = $contact_info['ctelef1'];
		} else {
			$this->data['telef1'] = '';
		}

		if (isset($this->request->post['telef2'])) {
			$this->data['telef2'] = $this->request->post['telef2'];
		} elseif (isset($contact_info)) {
			$this->data['telef2'] = $contact_info['ctelef2'];
		} else {
			$this->data['telef2'] = '';
		}

		if (isset($this->request->post['puesto'])) {
			$this->data['puesto'] = $this->request->post['puesto'];
		} elseif (isset($contact_info)) {
			$this->data['puesto'] = $contact_info['cpuesto'];
		} else {
			$this->data['puesto'] = '';
		}

		if (isset($this->request->post['notas'])) {
			$this->data['notas'] = $this->request->post['notas'];
		} elseif (isset($contact_info)) {
			$this->data['notas'] = $contact_info['mnotas'];
		} else {
			$this->data['notas'] = '';
		}

		if ($this->data['contact_id'] == 0) {
			$this->data['action'] = $this->url->link('purchase/supplier/insertContact', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('purchase/supplier/updateContact', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'] . '&contact_id=' . $this->data['contact_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');

		$this->template = 'purchase/supplier_contacts.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// Documentos de proveedor: mismo patron que ControllerSaleCustomer (insertContract/
	// deleteContract/viewContract/getContractForm conservan el nombre historico "Contract",
	// pero desde aqui en adelante es un adjunto de fichero, no una ficha de contrato).
	private function getSupplierDocumentDir($company) {
		$accents = array(
			'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n','ü'=>'u',
			'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N','Ü'=>'U',
			'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u','ç'=>'c','Ç'=>'C',
		);

		$company_sanitized = trim(preg_replace('/[^A-Za-z0-9]+/', '_', strtr(trim((string)$company), $accents)), '_');

		if ($company_sanitized === '') {
			$company_sanitized = 'SIN_NOMBRE';
		}

		$first_char = strtoupper(substr($company_sanitized, 0, 1));

		if (!preg_match('/^[A-Z0-9]$/', $first_char)) {
			$first_char = '_';
		}

		$project_root = rtrim(str_replace('\\', '/', dirname(DIR_APPLICATION)), '/');

		return $project_root . '/docs/supplier/' . $first_char . '/' . $company_sanitized . '/';
	}

	private function findPythonForEmbeddings() {
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

	private function spawnEmbeddingScript($status_suffix, $args) {
		$python = $this->findPythonForEmbeddings();

		if (!$python) {
			return false;
		}

		$script_path = DIR_SYSTEM . 'vendor/document_embeddings/document_embeddings.py';
		$status_dir  = DIR_SYSTEM . 'vendor/document_embeddings/';

		if (!is_dir($status_dir)) {
			mkdir($status_dir, 0755, true);
		}

		$status_file = $status_dir . 'status_' . $status_suffix . '.json';
		$log_file    = $status_dir . 'last_run_' . $status_suffix . '.log';

		$env = array(
			'DOCEMB_DB_HOST'     => DB_HOSTNAME,
			'DOCEMB_DB_PORT'     => (string)DB_PORT,
			'DOCEMB_DB_USER'     => DB_USERNAME,
			'DOCEMB_DB_PASSWORD' => DB_PASSWORD,
			'DOCEMB_DB_NAME'     => DB_DATABASE,
			'DOCEMB_DB_PREFIX'   => DB_PREFIX,
			'DOCEMB_LANGUAGE_ID' => (string)(int)$this->config->get('config_language_id'),
			'DOCEMB_STATUS_FILE' => $status_file,
		);

		if (stripos(PHP_OS, 'WIN') === 0) {
			foreach ($env as $key => $value) {
				putenv($key . '=' . $value);
			}

			$cmd = 'start /B "" ' . escapeshellarg($python) . ' ' . escapeshellarg($script_path) . ' ' . $args
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

		$cmd = $env_prefix . escapeshellarg($python) . ' ' . escapeshellarg($script_path) . ' ' . $args
			. ' > ' . escapeshellarg($log_file) . ' 2>&1 &';

		exec($cmd, $out, $code);

		return true;
	}

	// Lanza en segundo plano la indexacion RAG de un documento (PDF) recien adjuntado a
	// un proveedor, si "Guardar representacion vectorial" + "Usar IA" estan activos y
	// Ollama tiene el modelo de embeddings disponible. Mismo patron que
	// ControllerSaleCustomer::spawnCustomerDocumentEmbedding().
	private function spawnSupplierDocumentEmbedding($supplier_id, $document_id, $abs_file_path, $original_name) {
		$args = '--supplier-id ' . (int)$supplier_id
			. ' --supplier-document-id ' . (int)$document_id
			. ' --file ' . escapeshellarg($abs_file_path)
			. ' --original-name ' . escapeshellarg($original_name);

		return $this->spawnEmbeddingScript('supplier_' . (int)$document_id, $args);
	}

	// Lanza en segundo plano la indexacion RAG de una nota de proveedor recien
	// creada, si "Guardar representacion vectorial" + "Usar IA" estan activos y
	// Ollama tiene el modelo de embeddings disponible. Mismo patron que
	// ControllerSaleCustomer::spawnCustomerNoteEmbedding().
	private function spawnSupplierNoteEmbedding($supplier_id, $note_id) {
		$args = '--supplier-id ' . (int)$supplier_id . ' --supplier-note-id ' . (int)$note_id;

		return $this->spawnEmbeddingScript('supplier_note_' . (int)$note_id, $args);
	}

	public function insertContract() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'purchase/supplier')) {
			$supplier_id = (int)$this->request->get['supplier_id'];

			if (!isset($_FILES['document']) || empty($_FILES['document']['tmp_name']) || !is_uploaded_file($_FILES['document']['tmp_name'])) {
				$this->error['warning'] = $this->language->get('error_upload');
			} else {
				$ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

				if (!in_array($ext, array('pdf', 'xlsx'))) {
					$this->error['warning'] = $this->language->get('error_document_type');
				} else {
					$supplier_info = $this->model_purchase_supplier->getSupplier($supplier_id);
					$dir = $this->getSupplierDocumentDir($supplier_info ? $supplier_info['company'] : '');

					if (!is_dir($dir)) {
						mkdir($dir, 0755, true);
					}

					$safe_name = preg_replace('/[^A-Za-z0-9_.\-]/', '_', basename($_FILES['document']['name']));
					if ($safe_name === '' || $safe_name === '.' . $ext) {
						$safe_name = 'documento.' . $ext;
					}

					// No pisar un documento distinto que por casualidad tenga el mismo nombre
					// (un proveedor puede tener varios documentos).
					$path = $dir . $safe_name;
					if (is_file($path)) {
						$path = $dir . pathinfo($safe_name, PATHINFO_FILENAME) . '_' . date('YmdHis') . '.' . $ext;
					}

					if (move_uploaded_file($_FILES['document']['tmp_name'], $path)) {
						$document_id = $this->model_purchase_supplier->addSupplierDocument($supplier_id, $_FILES['document']['name'], $path);

						if (($ext == 'pdf') && $this->config->get('config_product_vector_embeddings') && $this->config->get('config_ai_enabled') && $this->isOllamaEmbeddingModelAvailable()) {
							$this->spawnSupplierDocumentEmbedding($supplier_id, $document_id, $path, $_FILES['document']['name']);
						}

						$this->session->data['success'] = $this->language->get('text_success');

						$this->redirect($this->url->link('purchase/supplier/insertContract', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_id, 'SSL'));
					} else {
						$this->error['warning'] = $this->language->get('error_document_upload');
					}
				}
			}
		}

		$this->getContractForm();
	}

	public function deleteContract() {
		$this->load->language('purchase/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('purchase/supplier');

		if (isset($this->request->get['document_id']) && $this->validateDelete()) {
			$document_info = $this->model_purchase_supplier->getSupplierDocument($this->request->get['document_id']);

			if ($document_info) {
				if (is_file($document_info['stored_filename'])) {
					@unlink($document_info['stored_filename']);
				}

				$this->model_purchase_supplier->deleteSupplierDocumentEmbeddings($this->request->get['document_id']);
				$this->model_purchase_supplier->deleteSupplierDocument($this->request->get['document_id']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function viewContract() {
		if (!$this->user->hasPermission('access', 'purchase/supplier')) {
			http_response_code(403);
			exit('Permission denied');
		}

		if (empty($this->request->get['document_id'])) {
			http_response_code(400);
			exit('Missing document_id');
		}

		$this->load->model('purchase/supplier');

		$document_info = $this->model_purchase_supplier->getSupplierDocument((int)$this->request->get['document_id']);

		if (!$document_info) {
			http_response_code(404);
			exit('Document not found');
		}

		$file = $document_info['stored_filename'];

		if (!is_file($file)) {
			http_response_code(404);
			exit('File not found');
		}

		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		$mime_types = array(
			'pdf'  => 'application/pdf',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		);

		$content_type = isset($mime_types[$ext]) ? $mime_types[$ext] : 'application/octet-stream';

		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		header('Content-Type: ' . $content_type);
		header('Content-Disposition: inline; filename="' . basename($document_info['filename']) . '"');
		header('Content-Length: ' . filesize($file));
		header('Cache-Control: private');
		readfile($file);
		exit;
	}

	protected function getContractForm() {
		$this->load->model('purchase/supplier');

		$this->data['heading_title'] = $this->language->get('heading_title_contract');

		$this->data['entry_document'] = $this->language->get('entry_document');
		$this->data['button_upload']  = $this->language->get('button_upload');
		$this->data['button_view']    = $this->language->get('button_view');
		$this->data['button_cancel']  = $this->language->get('button_cancel');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action']  = $this->language->get('column_action');
		$this->data['text_no_documents'] = $this->language->get('text_no_documents');

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$supplier_id = (int)$this->request->get['supplier_id'];

		$this->data['supplier_id'] = $supplier_id;

		$documents = $this->model_purchase_supplier->getSupplierDocuments($supplier_id);

		$this->data['documents'] = array();

		foreach ($documents as $document) {
			$this->data['documents'][] = array(
				'document_id' => $document['document_id'],
				'filename'    => $document['filename'],
				'date_added'  => date($this->language->get('date_format_short') . ' H:i', strtotime($document['date_added'])),
				'view'        => $this->url->link('purchase/supplier/viewContract', 'token=' . $this->session->data['token'] . '&document_id=' . $document['document_id'], 'SSL'),
				'delete'      => $this->url->link('purchase/supplier/deleteContract', 'token=' . $this->session->data['token'] . '&document_id=' . $document['document_id'] . '&supplier_id=' . $supplier_id, 'SSL')
			);
		}

		$this->data['action'] = $this->url->link('purchase/supplier/insertContract', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_id, 'SSL');

		$this->data['cancel'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $supplier_id, 'SSL');

		$this->template = 'purchase/supplier_contract.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	public function insertNote() {
		$this->load->language('purchase/supplier');

		$this->load->model('purchase/supplier');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$supplier_id = (int)$this->request->get['supplier_id'];
			$note_id = $this->model_purchase_supplier->addSupplierNote($this->request->post, $supplier_id);

			if ($this->config->get('config_product_vector_embeddings') && $this->config->get('config_ai_enabled') && $this->isOllamaEmbeddingModelAvailable()) {
				$this->spawnSupplierNoteEmbedding($supplier_id, $note_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getNoteForm();
	}

	public function deleteNote() {
		$this->load->language('purchase/supplier');

		$this->load->model('purchase/supplier');

		if (isset($this->request->get['note_id']) && $this->validateDelete()) {
			$this->model_purchase_supplier->deleteSupplierNoteEmbeddings($this->request->get['note_id']);
			$this->model_purchase_supplier->deleteSupplierNote($this->request->get['note_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getNoteForm() {
		$this->load->model('purchase/supplier');

		$this->data['heading_title'] = $this->language->get('heading_title_note');

		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_date_note'] = $this->language->get('entry_date_note');
		$this->data['entry_user'] = $this->language->get('entry_user');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array(
			array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			),
			array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL'),
				'separator' => ' :: '
			)
		);

		$this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} else {
			$this->data['date_added'] = date('Y-m-d');
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} else {
			$this->data['user_id'] = $this->user->getId();
		}

		if (isset($this->request->post['user_name'])) {
			$this->data['user_name'] = $this->request->post['user_name'];
		} else {
			$this->data['user_name'] = $this->user->getUserName();
		}

		$this->data['action'] = $this->url->link('purchase/supplier/insertNote', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');
		$this->data['cancel'] = $this->url->link('purchase/supplier/update', 'token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'], 'SSL');

		$this->template = 'purchase/supplier_note_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
