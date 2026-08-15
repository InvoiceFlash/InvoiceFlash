<?php
class ControllerSaleCustomer extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		$this->getList();
	}

	public function insert() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$customer_id = $this->model_sale_customer->addCustomer($this->request->post);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'create',
				'document_type' => 'customer',
				'document_id'   => (int)$customer_id,
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . $this->request->get['filter_company'];
			}

			if (isset($this->request->get['filter_telephone'])) {
				$url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->editCustomer($this->request->get['customer_id'], $this->request->post);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'edit',
				'document_type' => 'customer',
				'document_id'   => (int)$this->request->get['customer_id'],
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . $this->request->get['filter_company'];
			}

			if (isset($this->request->get['filter_telephone'])) {
				$url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (!isset($this->request->get['continue'])) {
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . $url . '&customer_id=' . $this->request->get['customer_id']  . '&continue=true', 'SSL'));
			}

		}

		$this->getForm();
	}

	public function sepa() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$customer_id = isset($this->request->get['customer_id']) ? (int)$this->request->get['customer_id'] : 0;

		$this->load->model('sale/customer');

		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if (!$customer_info) {
			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');

		// Datos del acreedor (la propia empresa)
		$this->data['creditor_id']      = (string)$this->config->get('creditor_id');
		$this->data['creditor_name']    = (string)$this->config->get('config_name');
		$this->data['creditor_address'] = (string)$this->config->get('config_address');

		$banks = $this->config->get('banks');
		$bank_default = $this->config->get('bank_default');

		if ($banks && isset($banks[$bank_default])) {
			$this->data['creditor_iban'] = $banks[$bank_default]['iban'];
			$this->data['creditor_bic']  = $banks[$bank_default]['bic'];
		} else {
			$this->data['creditor_iban'] = (string)$this->config->get('iban');
			$this->data['creditor_bic']  = (string)$this->config->get('bic');
		}

		// Datos del deudor (el cliente)
		$this->data['customer_id']    = $customer_id;
		$this->data['debtor_name']    = $customer_info['company'];
		$this->data['debtor_nif']     = $customer_info['nif'];
		$this->data['debtor_address'] = $customer_info['address'];
		$this->data['debtor_city']    = $customer_info['city'];
		$this->data['debtor_postcode'] = $customer_info['postcode'];
		$this->data['debtor_iban']    = $customer_info['bank_cc'];
		$this->data['debtor_bic']     = $customer_info['bic'];

		if (!empty($customer_info['country_id'])) {
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($customer_info['country_id']);

			$this->data['debtor_country'] = $country_info ? $country_info['name'] : '';
		} else {
			$this->data['debtor_country'] = '';
		}

		if (!empty($customer_info['zone_id'])) {
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($customer_info['zone_id']);

			$this->data['debtor_province'] = $zone_info ? $zone_info['name'] : '';
		} else {
			$this->data['debtor_province'] = '';
		}

		$this->data['mandate_reference'] = $this->data['creditor_id'] . '...' . str_pad($customer_id, 5, '0', STR_PAD_LEFT);

		$this->renderPDF('sale/customer_sepa_printPDF.tpl', 'pdf', 'sepa', $customer_id);
	}

	public function delete() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_sale_customer->deleteCustomer($customer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . $this->request->get['filter_company'];
			}

			if (isset($this->request->get['filter_telephone'])) {
				$url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function export() {
		$this->language->load('sale/customer');

		if (!$this->user->hasPermission('access', 'sale/customer') || empty($this->request->post['selected'])) {
			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('sale/customer');

		$customers = $this->model_sale_customer->getCustomersByIds($this->request->post['selected']);

		require_once(DIR_SYSTEM . 'library/xlsx.php');

		$xlsx = new Xlsx();

		$xlsx->setHeaders(array(
			$this->language->get('column_company'),
			$this->language->get('column_email'),
			$this->language->get('column_telephone'),
			$this->language->get('column_customer_group'),
			$this->language->get('column_status'),
			$this->language->get('column_date_added')
		));

		foreach ($customers as $customer) {
			$xlsx->addRow(array(
				$customer['company'],
				$customer['email'],
				$customer['telephone'],
				$customer['customer_group'],
				$customer['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				date($this->language->get('date_format_short'), strtotime($customer['date_added']))
			));
		}

		$this->response->addHeader('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->response->addHeader('Content-Disposition: attachment; filename="customers_' . date('Y-m-d') . '.xlsx"');
		$this->response->setOutput($xlsx->build($this->language->get('heading_title')));
	}

	protected function getList() {
		if (isset($this->request->get['filter_company'])) {
			$filter_company = $this->request->get['filter_company'];
		} else {
			$filter_company = null;
		}
		
		if (isset($this->request->get['filter_telephone'])) {
			$filter_telephone = $this->request->get['filter_telephone'];
		} else {
			$filter_telephone = null;
		}
		
		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}		

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'company';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
	
		$url = '';

		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . $this->request->get['filter_company'];
		}

		if (isset($this->request->get['filter_telephone'])) {
			$url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['export'] = $this->url->link('sale/customer/export', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['customers'] = array();

		$data = array(		
			'filter_company'		   => $filter_company, 
			'filter_telephone'         => $filter_telephone, 
			'filter_email'             => $filter_email, 
			'filter_customer_group_id' => $filter_customer_group_id, 
			'filter_status'            => $filter_status, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$customer_total = $this->model_sale_customer->getTotalCustomers($data);

		$results = $this->model_sale_customer->getCustomers($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
			);

			$this->data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'company'		 => $result['company'], 
				'telephone'      => $result['telephone'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['customer_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');	
		$this->data['text_select'] = $this->language->get('text_select');	
		$this->data['text_default'] = $this->language->get('text_default');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['column_customer_id'] = $this->language->get('column_customer_id');	
		$this->data['column_company'] = $this->language->get('column_company');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');	

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_export'] = $this->language->get('button_export');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . $this->request->get['filter_company'];
		}

		if (isset($this->request->get['filter_telephone'])) {
			$url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_company'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=company' . $url, 'SSL');
		$this->data['sort_telephone'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=telephone' . $url, 'SSL');
		$this->data['sort_email'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, 'SSL');
		$this->data['sort_customer_group'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, 'SSL');
        $this->data['sort_date_added'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, 'SSL');
        
        $url = '';

		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . $this->request->get['filter_company'];
		}

		if (isset($this->request->get['filter_telephone'])) {
			$url .= '&filter_telephone=' . $this->request->get['filter_telephone'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
        }
        
        if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
        }
        
        $pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();
        
        $this->data['filter_company'] = $filter_company;
        $this->data['filter_telephone'] = $filter_telephone;
        $this->data['filter_email'] = $filter_email;
        $this->data['filter_customer_group_id'] = $filter_customer_group_id;
        $this->data['filter_status'] = $filter_status;
        $this->data['filter_date_added'] = $filter_date_added;

        $this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('setting/store');

		$this->data['stores'] = $this->model_setting_store->getStores();

		$this->data['sort'] = $sort;

		$this->data['order'] = $order;

		$this->template = 'sale/customer_list.tpl';
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
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');
		$this->data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
		$this->data['text_valid'] = $this->language->get('text_valid');
		$this->data['text_no_valid'] = $this->language->get('text_no_valid');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_view'] = $this->language->get('text_view');

		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_email_subject'] = $this->language->get('column_email_subject');
		$this->data['column_email_sender'] = $this->language->get('column_email_sender');
		$this->data['column_quote'] = $this->language->get('column_quote');
		$this->data['column_quote'] = $this->language->get('column_quote');
		$this->data['column_delivery'] = $this->language->get('column_delivery');
		$this->data['column_invoice'] = $this->language->get('column_invoice');
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_product_id'] = $this->language->get('column_product_id');
		$this->data['column_product_name'] = $this->language->get('column_product_name');
		$this->data['column_order_date'] = $this->language->get('column_order_date');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer_representative'] = $this->language->get('entry_customer_representative');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_default'] = $this->language->get('entry_default');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['entry_nif'] = $this->language->get('entry_nif');
		$this->data['entry_contable_account'] = $this->language->get('entry_contable_account');
		$this->data['entry_digital_invoice'] = $this->language->get('entry_digital_invoice');
		$this->data['entry_web'] = $this->language->get('entry_web');
		$this->data['entry_vat_regime'] = $this->language->get('entry_vat_regime');
		$this->data['text_vat_regime_general'] = $this->language->get('text_vat_regime_general');
		$this->data['text_vat_regime_comunitario'] = $this->language->get('text_vat_regime_comunitario');
		$this->data['text_vat_regime_internacional'] = $this->language->get('text_vat_regime_internacional');

		$this->data['text_datecreated'] = $this->language->get('text_datecreated');
		$this->data['text_date_modified'] = $this->language->get('text_date_modified');
		$this->data['text_last_modified_by'] = $this->language->get('text_last_modified_by');
		$this->data['text_bank_cc'] = $this->language->get('text_bank');
		$this->data['text_bic'] = $this->language->get('text_bic');
		$this->data['text_fiscal'] = $this->language->get('text_fiscal');
		$this->data['text_receptor'] = $this->language->get('text_receptor');
		$this->data['text_paid'] = $this->language->get('text_paid');
		$this->data['text_role_address'] = $this->language->get('text_role_address');
		$this->data['text_role_city'] = $this->language->get('text_role_city');
		$this->data['text_role_province'] = $this->language->get('text_role_province');
		$this->data['text_role_postcode'] = $this->language->get('text_role_postcode');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_enabled'] = $this->language->get('text_enabled');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_address'] = $this->language->get('button_add_address');
		$this->data['button_add_history'] = $this->language->get('button_add_history');
		$this->data['button_add_reward'] = $this->language->get('button_add_reward');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_email'] = $this->language->get('button_email');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_web'] = $this->language->get('button_web');
		$this->data['error_web'] = $this->language->get('error_web');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_notes'] = $this->language->get('tab_notes');
		$this->data['tab_address'] = $this->language->get('tab_address');
		$this->data['tab_history'] = $this->language->get('tab_history');
		$this->data['tab_receipts'] = $this->language->get('tab_receipts');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_ip'] = $this->language->get('tab_ip');
		$this->data['tab_email'] = $this->language->get('tab_email');
		$this->data['tab_products'] = $this->language->get('tab_products');
		$this->data['tab_quotes'] = $this->language->get('tab_quotes');
		$this->data['tab_orders'] = $this->language->get('tab_orders');
		$this->data['tab_delivery'] = $this->language->get('tab_delivery');
		$this->data['tab_invoice'] = $this->language->get('tab_invoice');
		$this->data['tab_info'] = $this->language->get('tab_info');
		$this->data['tab_various'] = $this->language->get('tab_various');

		// Contacts
		$this->data['tab_contacts'] = $this->language->get('tab_contacts');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_telephone'] = $this->language->get('column_telephone');
		$this->data['column_contact_email'] = $this->language->get('column_contact_email');
		$this->data['column_puesto'] = $this->language->get('column_puesto');
		$this->data['column_date'] = $this->language->get('column_date');

		$this->data['button_add_contact'] = $this->language->get('button_add_contact');
		$this->data['button_remove_contact'] = $this->language->get('button_remove_contact');

		// Banks
		$this->data['tab_banks'] = $this->language->get('tab_banks');
		$this->data['column_bank_name'] = $this->language->get('column_bank_name');
		$this->data['column_iban'] = $this->language->get('column_iban');
		$this->data['button_add_bank'] = $this->language->get('button_add_bank');

		// Documentos
		$this->data['tab_contracts'] = $this->language->get('tab_contracts');

		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_date_added'] = $this->language->get('column_date_added');

		$this->data['button_add_contract'] = $this->language->get('button_add_contract');

		// Notas
		$this->data['column_user'] = $this->language->get('column_user');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$this->data['button_add_note'] = $this->language->get('button_add_note');
		$this->data['button_delete_note'] = $this->language->get('button_delete_note');

		// New Email (Modal)
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_subject'] = $this->language->get('text_subject');
		$this->data['text_message'] = $this->language->get('text_message');

		$this->data['button_new_email'] = $this->language->get('button_new_email');
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_upload'] = $this->language->get('button_upload');
				
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$this->data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$this->data['customer_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}

		if (isset($this->error['address_tax_id'])) {
			$this->data['error_address_tax_id'] = $this->error['address_tax_id'];
		} else {
			$this->data['error_address_tax_id'] = '';
		}

		if (isset($this->error['address_address_1'])) {
			$this->data['error_address_address_1'] = $this->error['address_address_1'];
		} else {
			$this->data['error_address_address_1'] = '';
		}

		if (isset($this->error['notas'])) {
			$this->data['error_notas'] = $this->error['notas'];
		} else {
			$this->data['error_notas'] = '';
		}

		if (isset($this->error['address_city'])) {
			$this->data['error_address_city'] = $this->error['address_city'];
		} else {
			$this->data['error_address_city'] = '';
		}

		if (isset($this->error['address_postcode'])) {
			$this->data['error_address_postcode'] = $this->error['address_postcode'];
		} else {
			$this->data['error_address_postcode'] = '';
		}

		if (isset($this->error['address_country'])) {
			$this->data['error_address_country'] = $this->error['address_country'];
		} else {
			$this->data['error_address_country'] = '';
		}

		if (isset($this->error['address_zone'])) {
			$this->data['error_address_zone'] = $this->error['address_zone'];
		} else {
			$this->data['error_address_zone'] = '';
		}

		if (($this->config->get('config_mail_protocol') == 'smtp') && (empty($this->config->get('config_smtp_host')) || empty($this->config->get('config_smtp_username')) || empty($this->config->get('config_smtp_password')))){
			$this->data['error_server'] = $this->language->get('error_server') ;
		}

		$url = '';

		if (isset($this->request->get['filter_telephone'])) {
			$url .= '&filter_telephone=' . urlencode(html_entity_decode($this->request->get['filter_telephone'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['customer_id'])) {
			$this->data['action'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL');

		}

		$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['new_email'] = $this->url->link('sale/customer/new_email', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');
		$this->data['sepa'] = $this->url->link('sale/customer/sepa', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');
		$this->data['button_sepa'] = $this->language->get('button_sepa');

		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
		}

		$this->data['emails'] = array();
		$this->data['products'] = array();
		$this->data['quotes'] = array();
		$this->data['orders'] = array();
		$this->data['deliveries'] = array();
		$this->data['invoices'] = array();
		$this->data['contacts'] = array();
		$this->data['contracts'] = array();
		$this->data['has_documents'] = false;
		$this->data['add_contact'] = '';
		$this->data['add_contract'] = '';
		$this->data['banks'] = array();
		$this->data['add_bank'] = '';

		if (isset($this->request->get['customer_id'])){

			// Emails
			$this->data['emails'] = array();
			if (isset($customer_info)) {
				$results = $this->model_sale_customer->getEmailsByCustomerId($this->request->get['customer_id']);

				foreach ($results as $result) {
					$sender = '';

					if ($result['type'] == 'E') {
						// Correo enviado por nosotros (ver Model->addMailSended()): 'client' guarda
						// el destinatario, no el remitente - el remitente somos nosotros mismos.
						$sender = $this->config->get('config_name') . ' (' . $this->config->get('config_email') . ')';
					} else {
						// Correo recibido (importado por IMAP, ver ModelCatalogMail->getmails()):
						// 'client' guarda de verdad la direccion de quien lo envio.
						$arr_sender = $this->model_sale_customer->getCustomerByEmail($result['client']);

						if (empty($arr_sender)) {
							$arr_sender = $this->model_sale_customer->getCustomerContactByEmail($result['client']);

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
						'mail_id'   => $result['mail_id'],
						'sender'	 => $sender,
						'subject'    => $result['title'],
						'text'       => $message,
						'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
					);
				}
			}
			
			//Products
			$products_total = $this->model_sale_customer->getProductsCustomerTotal($this->request->get['customer_id']);
		
			$productsresults = $this->model_sale_customer->getProductsCustomer($this->request->get['customer_id']);
			
			$this->data['products'] = array();
			
			foreach ($productsresults as $productsresult) {
				
				$this->data['products'][] = array(
					'product_id' => $productsresult['product_id'] ,
					'name'       => $productsresult['name'] ,
					'order_id'   => $productsresult['order_id'] ,
					'date'       => date($this->language->get('date_format_short'), strtotime($productsresult['date_added'])),
					'quantity'   => $productsresult['quantity'] ,
					'total'      => $this->currency->format($productsresult['total'], $this->config->get('config_currency'))
				);
			}
			
			// Quotes
			$invoice_total = $this->model_sale_customer->getQuotesCustomerTotal($this->request->get['customer_id']);
			
			$results = $this->model_sale_customer->getQuotesCustomer($this->request->get['customer_id']);
				
			$this->data['quotes'] = array();
			
			foreach ($results as $result) {
					
				$action = array();
					
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => HTTPS_SERVER . 'index.php?route=sale/quote/update&token=' . $this->session->data['token'] . '&quote_id=' . $result['quote_id'] . $url
				);
				
				$this->data['quotes'][] = array(
				'quote_id' => $result['quote_id'] ,
				'date'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'   => $action ,
				'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}
			
			// orders
			$invoice_total = $this->model_sale_customer->getOrdersCustomerTotal($this->request->get['customer_id']);
			
			$results = $this->model_sale_customer->getordersCustomer($this->request->get['customer_id']);
				
			$this->data['orders'] = array();
			
			foreach ($results as $result) {
					
				$action = array();
					
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url
				);
				
				$this->data['orders'][] = array(
				'order_id' => $result['order_id'] ,
				'date'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'action'   => $action ,
				'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}

			// Deliveries
			$deliver_total = $this->model_sale_customer->getDeliveryCustomerTotal($this->request->get['customer_id']);
			
			$results = $this->model_sale_customer->getDeliveryCustomer($this->request->get['customer_id']);
				
			$this->data['deliveries'] = array();
			
			foreach ($results as $result) {
					
				$action = array();
					
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => HTTPS_SERVER . 'index.php?route=sale/delivery/update&token=' . $this->session->data['token'] . '&delivery_id=' . $result['delivery_id'] . $url
				);
				
				$this->data['deliveries'][] = array(
					'delivery_id' => $result['delivery_id'] ,
					'date'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'action'   => $action ,
					'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}
			
			// Invoices
			$invoice_total = $this->model_sale_customer->getInvoicesCustomerTotal($this->request->get['customer_id']);
			
			$results = $this->model_sale_customer->getInvoicesCustomer($this->request->get['customer_id']);
				
			$this->data['invoices'] = array();
			
			foreach ($results as $result) {
					
				$action = array();
					
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => HTTPS_SERVER . 'index.php?route=sale/invoice/update&token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url
				);
				
				$this->data['invoices'][] = array(
					'invoice_id' => $result['invoice_id'] ,
					'date'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'action'   => $action ,
					'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
				);
			}

			// Contacts
			$contacts_total = $this->model_sale_customer->getCustomerContactsTotal($this->request->get['customer_id']);

			$this->data['contacts'] = array();
			

			if (isset($customer_info)) {
				$results = $this->model_sale_customer->getCustomerContacts($this->request->get['customer_id']);

				foreach ($results as $result) {
					$action = array();

					$link = $this->url->link('sale/customer/updateContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['customer_contacts_id'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
		   			$action[] = array(
						'link'	=> '<a class="btn btn-default" href="'.$link.'"><i class="fa fa-edit"></i><span class="hidden-xs"> ' . $this->language->get('text_edit') . '</span></a>'
		   			);

		   			$link = $this->url->link('sale/customer/deleteContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['customer_contacts_id'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
		   			$action[] = array(
						'link'	=> '<a class="btn btn-danger" href="'.$link.'"><i class="fa fa-trash"></i><span class="hidden-xs"> ' . $this->language->get('text_delete') . '</span></a>'
		   			);

					$this->data['contacts'][] = array(
						'contact_id' => $result['customer_contacts_id'],
						'name' => $result['cname'],
						'email' => $result['cemail'],
						'telephone' => $result['ctelef1'],
						'puesto' => $result['cpuesto'],
						'action' => $action
					);
				}
			}

			$this->data['add_contact'] = $this->url->link('sale/customer/insertContact', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');

			// Banks
			$this->data['banks'] = array();

			if (isset($customer_info)) {
				$results = $this->model_sale_customer->getCustomerBanks($this->request->get['customer_id']);

				foreach ($results as $result) {
					$action = array();

					$link = $this->url->link('sale/customer/updateBank', 'token=' . $this->session->data['token'] . '&bank_id=' . $result['customer_bank_id'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
					$action[] = array(
						'link'	=> '<a class="btn btn-default" href="'.$link.'"><i class="fa fa-edit"></i><span class="hidden-xs"> ' . $this->language->get('text_edit') . '</span></a>'
					);

					$link = $this->url->link('sale/customer/deleteBank', 'token=' . $this->session->data['token'] . '&bank_id=' . $result['customer_bank_id'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
					$action[] = array(
						'link'	=> '<a class="btn btn-danger" href="'.$link.'"><i class="fa fa-trash"></i><span class="hidden-xs"> ' . $this->language->get('text_delete') . '</span></a>'
					);

					$this->data['banks'][] = array(
						'bank_id'   => $result['customer_bank_id'],
						'bank_name' => $result['bank_name'],
						'iban'      => $result['iban'],
						'action'    => $action
					);
				}
			}

			$this->data['add_bank'] = $this->url->link('sale/customer/insertBank', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');

			// Customer documents
			$this->data['contracts'] = array();

			if (isset($customer_info)) {
				$results = $this->model_sale_customer->getCustomerDocuments($this->request->get['customer_id']);

				foreach ($results as $result) {
					$action = array();

					$link = $this->url->link('sale/customer/viewContract', 'token=' . $this->session->data['token'] . '&document_id=' . $result['document_id'], 'SSL');
					$action[] = array(
						'link' => '<a class="btn btn-default" href="'.$link.'" target="_blank"><i class="fa fa-eye"></i> <span class="hidden-xs">'.$this->language->get('button_view').'</span></a>'
					);

					$link = $this->url->link('sale/customer/deleteContract', 'token=' . $this->session->data['token'] . '&document_id=' . $result['document_id'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL');
					$action[] = array(
						'link' => '<a class="btn btn-danger" href="'.$link.'" onclick="return confirm(text_confirm);"><i class="fa fa-trash"></i> <span class="hidden-xs">'.$this->language->get('text_delete').'</span></a>'
					);

					$this->data['contracts'][] = array(
						'document_id'	=> $result['document_id'],
						'filename'		=> $result['filename'],
						'date_added'	=> date($this->language->get('date_format_short') . ' H:i', strtotime($result['date_added'])),
						'action'		=> $action
					);
				}
			}

			$this->data['has_documents'] = !empty($this->data['contracts']);

			$this->data['add_contract'] = $this->url->link('sale/customer/insertContract', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');

			
		}
		// end add
		
		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($customer_info)) {
			$this->data['telephone'] = $customer_info['telephone'];

		} else {
			$this->data['telephone'] = '';
		}

		if (isset($this->request->post['company'])) {
			$this->data['company'] = $this->request->post['company'];
		} elseif (!empty($customer_info)) {
			$this->data['company'] = $customer_info['company'];

			$this->data['heading_title'] = $customer_info['company'];
		} else {
			$this->data['company'] = '';
		}
		

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (!empty($customer_info)) {
			$this->data['email'] = $customer_info['email'];
		} else {

			$this->data['email'] = '';
		}

		if (isset($this->requeset->post['web'])) {
			$this->data['web'] = $this->request->post['web'];
		} elseif (!empty($customer_info)) {
			$this->data['web'] = $customer_info['cwww'];
		} else {
			$this->data['web'] = '';
		}
		
		if (isset($this->request->post['notes'])) {
      		$this->data['notes'] = $this->request->post['notes'];
    	} elseif (isset($customer_info)) { 
			$this->data['notes'] = $customer_info['notes'];
		} else {
      		$this->data['notes'] = '';
    	}
	
		if (isset($customer_info)) { 
			$this->data['date_added'] = date($this->language->get('datetime_format'), strtotime($customer_info['date_added']));
		} else {
      		$this->data['date_added'] = '';
    	}

		if (isset($customer_info)) {
			$this->data['date_modified'] = date($this->language->get('datetime_format'), strtotime($customer_info['date_modified']));
		} else {
			$this->data['date_modified'] = '';
		}

		if (isset($customer_info) && !empty($customer_info['last_modified_by'])) {
			$this->data['last_modified_by'] = $customer_info['last_modified_by'];
		} else {
			$this->data['last_modified_by'] = '';
		}

		if (isset($this->request->post['newsletter'])) {
			$this->data['newsletter'] = $this->request->post['newsletter'];
		} elseif (!empty($customer_info)) {
			$this->data['newsletter'] = $customer_info['newsletter'];
		} else {
			$this->data['newsletter'] = '';
		}

		if (isset($this->request->post['bank_cc'])) {
			$this->data['bank_cc'] = $this->request->post['bank_cc'];
		} elseif (!empty($customer_info)) {
			$this->data['bank_cc'] = $customer_info['bank_cc'];
		} else {
			$this->data['bank_cc'] = '';
		}

		if (isset($this->request->post['bic'])) {
			$this->data['bic'] = $this->request->post['bic'];
		} elseif (!empty($customer_info)) {
			$this->data['bic'] = $customer_info['bic'];
		} else {
			$this->data['bic'] = '';
		}

		if (isset($this->request->post['efaccafi'])) {
			$this->data['efaccafi'] = $this->request->post['efaccafi'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccafi'] = $customer_info['efaccafi'];
		} else {
			$this->data['efaccafi'] = '';
		}

		if (isset($this->request->post['efaccafi_address'])) {
			$this->data['efaccafi_address'] = $this->request->post['efaccafi_address'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccafi_address'] = $customer_info['efaccafi_address'];
		} else {
			$this->data['efaccafi_address'] = '';
		}

		if (isset($this->request->post['efaccafi_city'])) {
			$this->data['efaccafi_city'] = $this->request->post['efaccafi_city'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccafi_city'] = $customer_info['efaccafi_city'];
		} else {
			$this->data['efaccafi_city'] = '';
		}

		if (isset($this->request->post['efaccafi_province'])) {
			$this->data['efaccafi_province'] = $this->request->post['efaccafi_province'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccafi_province'] = $customer_info['efaccafi_province'];
		} else {
			$this->data['efaccafi_province'] = '';
		}

		if (isset($this->request->post['efaccafi_postcode'])) {
			$this->data['efaccafi_postcode'] = $this->request->post['efaccafi_postcode'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccafi_postcode'] = $customer_info['efaccafi_postcode'];
		} else {
			$this->data['efaccafi_postcode'] = '';
		}

		if (isset($this->request->post['efaccare'])) {
			$this->data['efaccare'] = $this->request->post['efaccare'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccare'] = $customer_info['efaccare'];
		} else {
			$this->data['efaccare'] = '';
		}

		if (isset($this->request->post['efaccare_address'])) {
			$this->data['efaccare_address'] = $this->request->post['efaccare_address'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccare_address'] = $customer_info['efaccare_address'];
		} else {
			$this->data['efaccare_address'] = '';
		}

		if (isset($this->request->post['efaccare_city'])) {
			$this->data['efaccare_city'] = $this->request->post['efaccare_city'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccare_city'] = $customer_info['efaccare_city'];
		} else {
			$this->data['efaccare_city'] = '';
		}

		if (isset($this->request->post['efaccare_province'])) {
			$this->data['efaccare_province'] = $this->request->post['efaccare_province'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccare_province'] = $customer_info['efaccare_province'];
		} else {
			$this->data['efaccare_province'] = '';
		}

		if (isset($this->request->post['efaccare_postcode'])) {
			$this->data['efaccare_postcode'] = $this->request->post['efaccare_postcode'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccare_postcode'] = $customer_info['efaccare_postcode'];
		} else {
			$this->data['efaccare_postcode'] = '';
		}

		if (isset($this->request->post['efaccapa'])) {
			$this->data['efaccapa'] = $this->request->post['efaccapa'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccapa'] = $customer_info['efaccapa'];
		} else {
			$this->data['efaccapa'] = '';
		}

		if (isset($this->request->post['efaccapa_address'])) {
			$this->data['efaccapa_address'] = $this->request->post['efaccapa_address'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccapa_address'] = $customer_info['efaccapa_address'];
		} else {
			$this->data['efaccapa_address'] = '';
		}

		if (isset($this->request->post['efaccapa_city'])) {
			$this->data['efaccapa_city'] = $this->request->post['efaccapa_city'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccapa_city'] = $customer_info['efaccapa_city'];
		} else {
			$this->data['efaccapa_city'] = '';
		}

		if (isset($this->request->post['efaccapa_province'])) {
			$this->data['efaccapa_province'] = $this->request->post['efaccapa_province'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccapa_province'] = $customer_info['efaccapa_province'];
		} else {
			$this->data['efaccapa_province'] = '';
		}

		if (isset($this->request->post['efaccapa_postcode'])) {
			$this->data['efaccapa_postcode'] = $this->request->post['efaccapa_postcode'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccapa_postcode'] = $customer_info['efaccapa_postcode'];
		} else {
			$this->data['efaccapa_postcode'] = '';
		}

		if (isset($this->request->post['contable_account'])) {
			$this->data['contable_account'] = $this->request->post['contable_account'];
		} elseif (!empty($customer_info)) {
			$this->data['contable_account'] = $customer_info['contable_account'];
		} else {
			$this->data['contable_account'] = '';
		}

		$this->data['contable_account_maxlength'] = (int)$this->config->get('config_conta_digits') ?: 10;

		if (isset($this->request->post['digital_invoice'])) {
			$this->data['digital_invoice'] = $this->request->post['digital_invoice'];
		} elseif (!empty($customer_info)) {
			$this->data['digital_invoice'] = $customer_info['digital_invoice'];
		} else {
			$this->data['digital_invoice'] = 0;
		}

		if (isset($this->request->post['vat_regime'])) {
			$this->data['vat_regime'] = $this->request->post['vat_regime'];
		} elseif (!empty($customer_info) && !empty($customer_info['vat_regime'])) {
			$this->data['vat_regime'] = $customer_info['vat_regime'];
		} else {
			$this->data['vat_regime'] = 'general';
		}

		$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($customer_info)) {
			$this->data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		$this->load->model('sale/customer_representative');

		$this->data['customer_representatives'] = $this->model_sale_customer_representative->getCustomerRepresentatives();

		if (isset($this->request->post['customer_representative_id'])) {
			$this->data['customer_representative_id'] = $this->request->post['customer_representative_id'];
		} elseif (!empty($customer_info)) {
			$this->data['customer_representative_id'] = $customer_info['customer_representative_id'];
		} else {
			$this->data['customer_representative_id'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($customer_info)) {
			$this->data['status'] = $customer_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) { 
			$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['customer_address'])) {
			$this->data['addresses'] = $this->request->post['customer_address'];
		} elseif (isset($this->request->get['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
		} else {
			$this->data['addresses'] = array();
		}

		if (isset($this->request->post['address_id'])) {
			$this->data['address_id'] = $this->request->post['address_id'];
		} elseif (!empty($customer_info)) {
			$this->data['address_id'] = $customer_info['address_id'];
		} else {
			$this->data['address_id'] = '';
		}

		if (isset($this->request->post['nif'])) {
			$this->data['nif'] = $this->request->post['nif'];
		} elseif (!empty($customer_info)) {
			$this->data['nif'] = $customer_info['nif'];
		} else {
			$this->data['nif'] = '';
		}

		$fields = array('address', 'city', 'postcode');

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($customer_info)) {
				$this->data[$field] = $customer_info[$field];
			} else {
				$this->data[$field] = '';
			}
		}

		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($customer_info)) {
			$this->data['country_id'] = $customer_info['country_id'];
		} else {
			$this->data['country_id'] = 0;
		}

		if (isset($this->request->post['zone_id'])) {
			$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($customer_info)) {
			$this->data['zone_id'] = $customer_info['zone_id'];
		} else {
			$this->data['zone_id'] = 0;
		}

		$this->data['ips'] = array();

		if (!empty($customer_info)) {
			$results = $this->model_sale_customer->getIpsByCustomerId($this->request->get['customer_id']);

			foreach ($results as $result) {
				$ban_ip_total = $this->model_sale_customer->getTotalBanIpsByIp($result['ip']);

				$this->data['ips'][] = array(
					'ip'         => $result['ip'],
					'total'      => $this->model_sale_customer->getTotalCustomersByIp($result['ip']),
					'date_added' => date('d/m/y', strtotime($result['date_added'])),
					'filter_ip'  => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
					'ban_ip'     => $ban_ip_total
				);
			}

			// Notes
			$note_total = $this->model_sale_customer->getCustomerNotesTotal($this->request->get['customer_id']);
			
			$results = $this->model_sale_customer->getCustomerNotes($this->request->get['customer_id']);
				
			$this->data['notes'] = array();
			
			foreach ($results as $result) {
				$note_info = $this->model_sale_customer->getCustomerNote($result['customer_history_id']);

				$this->data['notes'][] = array(
					'note_id'  => $result['customer_history_id'] ,
					'date'     => date($this->language->get('date_format_short'), strtotime($note_info['date_added'])),
					'user'	   => $note_info['user'],
					'comment'  => nl2br($note_info['comment']), 
					'delete'   => $this->url->link('sale/customer/deleteNote', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'] . '&note_id=' . $result['customer_history_id'], 'SSL')
				);
			}

		}		
		$this->data['add_note'] = $this->url->link('sale/customer/insertNote', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');

		$this->template = 'sale/customer_form.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {

		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['company']) < 1) || (utf8_strlen($this->request->post['company']) > 32)) {
			$this->error['company'] = $this->language->get('error_company');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		$customer_info = $this->model_sale_customer->getCustomerByEmail($this->request->post['email']);

		if (!isset($this->request->get['customer_id'])) {
			if ($customer_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
  
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {

		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$blocking = $this->model_sale_customer->getBlockingDocument($customer_id);

				if ($blocking) {
					$customer_info = $this->model_sale_customer->getCustomer($customer_id);

					$this->error['warning'] = sprintf($this->language->get('error_document_' . $blocking['type']), ($customer_info ? $customer_info['company'] : $customer_id), $blocking['document_id']);
					break;
				}
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}



	public function login() {

		$json = array();

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('sale/customer');

		$customer_info = $this->model_sale_customer->getCustomer($customer_id);

		if ($customer_info) {

			$token = md5(mt_rand());

			$this->model_sale_customer->editToken($customer_id, $token);

			if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			$this->load->model('setting/store');
			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$this->redirect($store_info['url'] . 'index.php?route=account/login&token=' . $token);
			} else { 
				$this->redirect(HTTP_CATALOG . 'index.php?route=account/login&token=' . $token);
			}
		} else {
			$this->language->load('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_not_found'] = $this->language->get('text_not_found');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$this->template = 'error/not_found.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}
	}

	public function history() {
		$this->language->load('sale/customer');

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addHistory($this->request->get['customer_id'], $this->request->post['comment']);
			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}		

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  

		$this->data['histories'] = array();

		$results = $this->model_sale_customer->getHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['histories'][] = array(
				'comment'     => $result['comment'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_customer->getTotalHistories($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_history.tpl';		

		$this->response->setOutput($this->render());
	}

	public function receipts() {
		$this->language->load('sale/customer');
		$this->language->load('sale/receipt');

		$this->load->model('sale/receipt');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_paid'] = $this->language->get('text_paid');
		$this->data['text_pending'] = $this->language->get('text_pending');

		$this->data['column_invoice_id'] = $this->language->get('column_invoice_id');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_due'] = $this->language->get('column_date_due');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data = array(
			'filter_customer_id' => $this->request->get['customer_id'],
			'sort'               => 'r.date_due',
			'order'              => 'DESC',
			'start'              => ($page - 1) * 10,
			'limit'              => 10
		);

		$this->data['receipts'] = array();

		$results = $this->model_sale_receipt->getReceipts($data);

		foreach ($results as $result) {
			$this->data['receipts'][] = array(
				'invoice_id' => $result['invoice_id'],
				'paid'       => $result['paid'],
				'total'      => $this->currency->format($result['amount'], $result['currency_code'], $result['currency_value'], true, true),
				'date_due'   => date($this->language->get('date_format_short'), strtotime($result['date_due']))
			);
		}

		$receipt_total = $this->model_sale_receipt->getTotalReceipts($data);

		$pagination = new Pagination();
		$pagination->total = $receipt_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/receipts', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_receipts.tpl';

		$this->response->setOutput($this->render());
	}

	public function reward() {

		$this->language->load('sale/customer');

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 
			$this->model_sale_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

			$this->data['success'] = $this->language->get('text_success');
		} else {
			$this->data['success'] = '';
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->user->hasPermission('modify', 'sale/customer')) {
			$this->data['error_warning'] = $this->language->get('error_permission');
		} else {
			$this->data['error_warning'] = '';
		}	

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_balance'] = $this->language->get('text_balance');

		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_description'] = $this->language->get('column_description');
		$this->data['column_points'] = $this->language->get('column_points');


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  

		$this->data['rewards'] = array();

		$results = $this->model_sale_customer->getRewards($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {

			$this->data['rewards'][] = array(
				'points'      => $result['points'],
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$this->data['balance'] = $this->model_sale_customer->getRewardTotal($this->request->get['customer_id']);

		$reward_total = $this->model_sale_customer->getTotalRewards($this->request->get['customer_id']);


		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/reward', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/customer_reward.tpl';		

		$this->response->setOutput($this->render());
	}


	public function addBanIP() {

		$this->language->load('sale/customer');

		$json = array();

		if (isset($this->request->post['ip'])) { 
			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$json['error'] = $this->language->get('error_permission');
			} else {
				$this->load->model('sale/customer');
				$this->model_sale_customer->addBanIP($this->request->post['ip']);
				$json['success'] = $this->language->get('text_success');
			}
		}
		$this->response->setOutput(json_encode($json));
	}


	public function removeBanIP() {

		$this->language->load('sale/customer');

		$json = array();

		if (isset($this->request->post['ip'])) { 

			if (!$this->user->hasPermission('modify', 'sale/customer')) {
				$json['error'] = $this->language->get('error_permission');
			} else {
				$this->load->model('sale/customer');
				$this->model_sale_customer->removeBanIP($this->request->post['ip']);
				$json['success'] = $this->language->get('text_success');
			}

		}

		$this->response->setOutput(json_encode($json));
	}



	public function autocomplete() {
		$json = array();

		$this->load->model('sale/customer');

		if (isset($this->request->get['filter_name'])) {

			$data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_sale_customer->getCustomers($data);

		}

		if (isset($this->request->get['filter_company'])) {
			$data = array(
				'filter_company' => $this->request->get['filter_company'],
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_sale_customer->getCustomers($data);
		}

		
		foreach ($results as $result) {
			$json[] = array(
				'customer_id'       => $result['customer_id'], 
				'customer_group_id' => $result['customer_group_id'],
				'company'           => strip_tags(html_entity_decode($result['company'], ENT_QUOTES, 'UTF-8')),
				'name'          	=> strip_tags(html_entity_decode($result['company'], ENT_QUOTES, 'UTF-8')),
				'customer_group'    => $result['customer_group'],
				'email'             => $result['email'],
				'telephone'         => $result['telephone'],
				'address'           => $this->model_sale_customer->getAddresses($result['customer_id'])
			);					
		}
		
		$sort_order = array();
		
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}

	public function searchCustomers() {
		$this->load->model('sale/customer');

		$filter_company = isset($this->request->post['filter_company']) ? html_entity_decode($this->request->post['filter_company'], ENT_QUOTES, 'UTF-8') : '';
		$filter_contact = isset($this->request->post['filter_contact']) ? html_entity_decode($this->request->post['filter_contact'], ENT_QUOTES, 'UTF-8') : '';

		$data = array(
			'filter_company' => $filter_company,
			'filter_contact' => $filter_contact,
			'sort'           => 'company',
			'order'          => 'ASC',
			'start'          => 0,
			'limit'          => 200
		);

		$total = $this->model_sale_customer->getTotalCustomers($data);

		$this->response->addHeader('Content-Type: application/json');

		if ($total > 200) {
			$this->response->setOutput(json_encode(array('warning' => 'Hay más de 200 clientes. Añade un filtro por nombre/empresa para acotar la búsqueda.')));
			return;
		}

		$results = $this->model_sale_customer->getCustomers($data);

		$json = array();

		foreach ($results as $result) {
			$customer_banks = $this->model_sale_customer->getCustomerBanks($result['customer_id']);

			$banks = array();

			foreach ($customer_banks as $customer_bank) {
				$banks[] = array(
					'customer_bank_id' => $customer_bank['customer_bank_id'],
					'bank_name'        => $customer_bank['bank_name'],
					'iban'             => $customer_bank['iban']
				);
			}

			$json[] = array(
				'customer_id'       => $result['customer_id'],
				'customer_group_id' => $result['customer_group_id'],
				'company'           => strip_tags(html_entity_decode($result['company'], ENT_QUOTES, 'UTF-8')),
				'customer_group'    => $result['customer_group'],
				'email'             => $result['email'],
				'telephone'         => $result['telephone'],
				'banks'             => $banks,
				'address'           => $this->model_sale_customer->getAddresses($result['customer_id'])
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function address() {

		$json = array();

		if (!empty($this->request->get['address_id'])) {
			$this->load->model('sale/customer');
			$json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->setOutput(json_encode($json));		
	}


	public function updateContact() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      	  	$this->model_sale_customer->editCustomerContact($this->request->post, $this->request->get['contact_id']);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&contact_id=' . $this->request->get['contact_id'], 'SSL'));
		}

		$this->getContactForm();
	}

	public function insertContact() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateContactForm()) {
      	  	$this->model_sale_customer->addCustomerContact($this->request->post, $this->request->get['customer_id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getContactForm();
	}

	public function validateContactForm() {
		$log = new Log('contact.log'); $log->write($this->request->post);
		if (utf8_strlen($this->request->post['name']) < 3 || utf8_strlen($this->request->post['name']) > 50) {
			$this->error['name'] = $this->language->get('text_error_contact_name');
		}

		if ($this->error) {
			return false;
		} else {
			return true;
		}
	}

	public function deleteContact() {
		
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (isset($this->request->get['contact_id'])) {

      	  	$this->model_sale_customer->deleteCustomerContact($this->request->get['contact_id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function getContactForm() {
		$this->data['heading_title'] 			= $this->language->get('heading_contact');

		$this->data['entry_name']				= $this->language->get('entry_name');
		$this->data['entry_email']				= $this->language->get('entry_email');
		$this->data['entry_telephone']			= $this->language->get('entry_telephone');
		$this->data['entry_telephone2']			= $this->language->get('entry_telephone2');
		$this->data['entry_puesto']				= $this->language->get('entry_puesto');
		$this->data['entry_notas']				= $this->language->get('entry_notas');

		$this->data['button_save']				= $this->language->get('button_save');
		$this->data['button_cancel']			= $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->request->get['contact_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$contact_info = $this->model_sale_customer->getCustomerContact($this->request->get['contact_id']);
		}
		
		if (isset($this->request->get['contact_id'])) {
			$this->data['contact_id'] = $this->request->get['contact_id'];
		} else {
			$this->data['contact_id'] = 0;
		}
		

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

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
			$this->data['action'] = $this->url->link('sale/customer/insertContact', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/updateContact', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&contact_id=' . $this->data['contact_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');		

		$this->template = 'sale/customer_contacts.tpl';
		$this->children = array(
			'common/header',
			
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	public function updateBank() {
		$this->load->language('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateBankForm()) {
			$this->model_sale_customer->editCustomerBank($this->request->post, $this->request->get['bank_id']);
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&bank_id=' . $this->request->get['bank_id'], 'SSL'));
		}

		$this->getBankForm();
	}

	public function insertBank() {
		$this->load->language('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateBankForm()) {
			$this->model_sale_customer->addCustomerBank($this->request->post, $this->request->get['customer_id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getBankForm();
	}

	public function validateBankForm() {
		if (utf8_strlen($this->request->post['bank_name']) < 2 || utf8_strlen($this->request->post['bank_name']) > 100) {
			$this->error['bank_name'] = $this->language->get('text_error_bank_name');
		}

		if (utf8_strlen($this->request->post['iban']) < 15 || utf8_strlen($this->request->post['iban']) > 34) {
			$this->error['iban'] = $this->language->get('text_error_iban');
		}

		if ($this->error) {
			return false;
		} else {
			return true;
		}
	}

	public function deleteBank() {
		$this->load->language('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (isset($this->request->get['bank_id'])) {
			$this->model_sale_customer->deleteCustomerBank($this->request->get['bank_id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function getBankForm() {
		$this->data['heading_title'] 			= $this->language->get('heading_bank');

		$this->data['entry_bank_name']			= $this->language->get('entry_bank_name');
		$this->data['entry_iban']				= $this->language->get('entry_iban');

		$this->data['button_save']				= $this->language->get('button_save');
		$this->data['button_cancel']			= $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_bank'),
			'href'      => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->request->get['bank_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$bank_info = $this->model_sale_customer->getCustomerBank($this->request->get['bank_id']);
		}

		if (isset($this->request->get['bank_id'])) {
			$this->data['bank_id'] = $this->request->get['bank_id'];
		} else {
			$this->data['bank_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['bank_name'])) {
			$this->data['error_bank_name'] = $this->error['bank_name'];
		} else {
			$this->data['error_bank_name'] = '';
		}

		if (isset($this->error['iban'])) {
			$this->data['error_iban'] = $this->error['iban'];
		} else {
			$this->data['error_iban'] = '';
		}

		if (isset($this->request->post['bank_name'])) {
			$this->data['bank_name'] = $this->request->post['bank_name'];
		} elseif (isset($bank_info)) {
			$this->data['bank_name'] = $bank_info['bank_name'];
		} else {
			$this->data['bank_name'] = '';
		}

		if (isset($this->request->post['iban'])) {
			$this->data['iban'] = $this->request->post['iban'];
		} elseif (isset($bank_info)) {
			$this->data['iban'] = $bank_info['iban'];
		} else {
			$this->data['iban'] = '';
		}

		if ($this->data['bank_id'] == 0) {
			$this->data['action'] = $this->url->link('sale/customer/insertBank', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/updateBank', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&bank_id=' . $this->data['bank_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');

		$this->template = 'sale/customer_banks.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}


	private function getCustomerDocumentDir($company) {
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

		return $project_root . '/docs/customer/' . $first_char . '/' . $company_sanitized . '/';
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

	// Lanza en segundo plano la indexacion RAG de un documento (PDF) recien adjuntado
	// a un cliente, si "Activar RAG" + "Usar IA" estan activos y Ollama tiene el
	// modelo de embeddings disponible (ver isOllamaEmbeddingModelAvailable() en la
	// clase base Controller, compartida con catalog/product).
	private function spawnCustomerDocumentEmbedding($customer_id, $document_id, $abs_file_path, $original_name) {
		$args = '--customer-id ' . (int)$customer_id
			. ' --customer-document-id ' . (int)$document_id
			. ' --file ' . escapeshellarg($abs_file_path)
			. ' --original-name ' . escapeshellarg($original_name);

		return $this->spawnEmbeddingScript('customer_' . (int)$document_id, $args);
	}

	// Igual que spawnCustomerDocumentEmbedding() pero para una nota de cliente (texto
	// ya guardado en customer_history, sin fichero de por medio).
	private function spawnCustomerNoteEmbedding($customer_id, $note_id) {
		$args = '--customer-id ' . (int)$customer_id . ' --customer-note-id ' . (int)$note_id;

		return $this->spawnEmbeddingScript('customer_note_' . (int)$note_id, $args);
	}

	public function insertContract() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) {
			$customer_id = (int)$this->request->get['customer_id'];

			if (!isset($_FILES['document']) || empty($_FILES['document']['tmp_name']) || !is_uploaded_file($_FILES['document']['tmp_name'])) {
				$this->error['warning'] = $this->language->get('error_upload');
			} else {
				$ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));

				if (!in_array($ext, array('pdf', 'xlsx'))) {
					$this->error['warning'] = $this->language->get('error_document_type');
				} else {
					$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					$dir = $this->getCustomerDocumentDir($customer_info ? $customer_info['company'] : '');

					if (!is_dir($dir)) {
						mkdir($dir, 0755, true);
					}

					$safe_name = preg_replace('/[^A-Za-z0-9_.\-]/', '_', basename($_FILES['document']['name']));
					if ($safe_name === '' || $safe_name === '.' . $ext) {
						$safe_name = 'documento.' . $ext;
					}

					// No pisar un documento distinto que por casualidad tenga el mismo nombre
					// (aquí, a diferencia de purchase/invoice, un cliente puede tener varios).
					$path = $dir . $safe_name;
					if (is_file($path)) {
						$path = $dir . pathinfo($safe_name, PATHINFO_FILENAME) . '_' . date('YmdHis') . '.' . $ext;
					}

					if (move_uploaded_file($_FILES['document']['tmp_name'], $path)) {
						$document_id = $this->model_sale_customer->addCustomerDocument($customer_id, $_FILES['document']['name'], $path);

						if (($ext == 'pdf') && $this->config->get('config_product_vector_embeddings') && $this->config->get('config_ai_enabled') && $this->isOllamaEmbeddingModelAvailable()) {
							$this->spawnCustomerDocumentEmbedding($customer_id, $document_id, $path, $_FILES['document']['name']);
						}

						$this->session->data['success'] = $this->language->get('text_success');

						$this->redirect($this->url->link('sale/customer/insertContract', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, 'SSL'));
					} else {
						$this->error['warning'] = $this->language->get('error_document_upload');
					}
				}
			}
		}

		$this->getContractForm();
	}

	public function deleteContract() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (isset($this->request->get['document_id']) && $this->user->hasPermission('modify', 'sale/customer')) {
			$document_info = $this->model_sale_customer->getCustomerDocument($this->request->get['document_id']);

			if ($document_info) {
				if (is_file($document_info['stored_filename'])) {
					@unlink($document_info['stored_filename']);
				}

				$this->model_sale_customer->deleteCustomerDocumentEmbeddings($this->request->get['document_id']);
				$this->model_sale_customer->deleteCustomerDocument($this->request->get['document_id']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function viewContract() {
		if (!$this->user->hasPermission('access', 'sale/customer')) {
			http_response_code(403);
			exit('Permission denied');
		}

		if (empty($this->request->get['document_id'])) {
			http_response_code(400);
			exit('Missing document_id');
		}

		$this->load->model('sale/customer');

		$document_info = $this->model_sale_customer->getCustomerDocument((int)$this->request->get['document_id']);

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

	public function getContractForm() {
		$this->load->model('sale/customer');

		$this->data['heading_title'] = $this->language->get('heading_title_contract');

		$this->data['entry_document'] = $this->language->get('entry_document');
		$this->data['button_upload']  = $this->language->get('button_upload');
		$this->data['button_view']    = $this->language->get('button_view');
		$this->data['button_cancel']  = $this->language->get('button_cancel');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action']  = $this->language->get('column_action');
		$this->data['text_no_documents'] = $this->language->get('text_no_documents');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$customer_id = (int)$this->request->get['customer_id'];

		$this->data['customer_id'] = $customer_id;

		$documents = $this->model_sale_customer->getCustomerDocuments($customer_id);

		$this->data['documents'] = array();

		foreach ($documents as $document) {
			$this->data['documents'][] = array(
				'document_id' => $document['document_id'],
				'filename'    => $document['filename'],
				'date_added'  => date($this->language->get('date_format_short') . ' H:i', strtotime($document['date_added'])),
				'view'        => $this->url->link('sale/customer/viewContract', 'token=' . $this->session->data['token'] . '&document_id=' . $document['document_id'], 'SSL'),
				'delete'      => $this->url->link('sale/customer/deleteContract', 'token=' . $this->session->data['token'] . '&document_id=' . $document['document_id'] . '&customer_id=' . $customer_id, 'SSL')
			);
		}

		$this->data['action'] = $this->url->link('sale/customer/insertContract', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, 'SSL');

		$this->data['cancel'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, 'SSL');

		$this->template = 'sale/customer_contract.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	public function insertNote() {
		$this->load->language('sale/customer');

		$this->load->model('sale/customer');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$customer_id = (int)$this->request->get['customer_id'];
			$note_id = $this->model_sale_customer->addCustomerNote($this->request->post, $customer_id);

			if ($this->config->get('config_product_vector_embeddings') && $this->config->get('config_ai_enabled') && $this->isOllamaEmbeddingModelAvailable()) {
				$this->spawnCustomerNoteEmbedding($customer_id, $note_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->request->get['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getNoteForm();
	}

	public function deleteNote() {
		$this->load->language('sale/customer');

		$this->load->model('sale/customer');

		if (isset($this->request->get['note_id'])) {
			$this->model_sale_customer->deleteCustomerNoteEmbeddings($this->request->get['note_id']);
			$this->model_sale_customer->deleteCustomerNote($this->request->get['note_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->request->get['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function getNoteForm() {
		$this->load->model('sale/customer');

		$this->data['heading_title'] = $this->language->get('heading_title_note');

		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_date_note'] = $this->language->get('entry_date_note');
		$this->data['entry_user'] = $this->language->get('entry_user');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'),
			'separator' => ' :: '
		);

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (isset($this->request->post['date_added'])) {
			$this->data['date_added'] = $this->request->post['date_added'];
		} else {
			$this->data['date_added'] = date("Y-m-d");
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

		$this->data['action'] = $this->url->link('sale/customer/insertNote',  'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
		$this->data['cancel'] = $this->url->link('sale/customer/update',  'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');

		$this->template = 'sale/customer_note_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	public function new_email(){
		$this->language->load('sale/customer');
		$json = array();
		
		if ($this->user->hasPermission('modify', 'catalog/mail')) {

			if ($this->request->post['to']=='' || filter_var($this->request->post['to'], FILTER_VALIDATE_EMAIL)==false) {
				$json['error']['to'] = $this->language->get('error_to');
			} 

			if ($this->request->post['subject']=='') {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if ($this->request->post['message']=='') {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if(empty($json['error'])){
				$data['customer_id'] = $this->request->get['customer_id'];
				$data['potential_id'] = 0;
				$data['supplier_id'] = 0;
				
				$data['to'] = $this->request->post['to'];
				$data['subject'] = $this->request->post['subject'];

				$data['text'] = $this->request->post['message'];
				$data['code'] = md5($this->request->post['message']);
				
				$data['file'] = '';
				if (is_file($this->request->post['filename'])){
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

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}

		$this->response->setOutput(json_encode($json));
	}
}

?>