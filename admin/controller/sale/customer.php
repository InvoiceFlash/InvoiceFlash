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
			$this->model_sale_customer->addCustomer($this->request->post);

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

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
				$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . $url . '&product_id=' . $product_id  . '&continue=true', 'SSL'));
			}

		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->editCustomer($this->request->get['customer_id'], $this->request->post);

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

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
				$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . $url . '&product_id=' . $product_id  . '&continue=true', 'SSL'));
			}

		}

		$this->getForm();
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

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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

	public function approve() {
		$this->language->load('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif (isset($this->request->post['selected'])) {
			$approved = 0;

			foreach ($this->request->post['selected'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);

				if ($customer_info && !$customer_info['approved']) {
					$this->model_sale_customer->approve($customer_id);
					$approved++;
				}
			} 

			$this->session->data['success'] = sprintf($this->language->get('text_approved'), $approved);

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

			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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

		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = null;
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

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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

		$this->data['approve'] = $this->url->link('sale/customer/approve', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['customers'] = array();

		$data = array(		
			'filter_company'		   => $filter_company, 
			'filter_telephone'         => $filter_telephone, 
			'filter_email'             => $filter_email, 
			'filter_customer_group_id' => $filter_customer_group_id, 
			'filter_status'            => $filter_status, 
			'filter_approved'          => $filter_approved, 
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
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
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
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');	

		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
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

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
		$this->data['sort_approved'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=c.approved' . $url, 'SSL');
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

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
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
        $this->data['filter_approved'] = $filter_approved;
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
	
		$this->data['text_enabled'] = $this->language->get('text_enabled');

		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['text_select'] = $this->language->get('text_select');

		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['text_wait'] = $this->language->get('text_wait');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['text_add_ban_ip'] = $this->language->get('text_add_ban_ip');

		$this->data['text_remove_ban_ip'] = $this->language->get('text_remove_ban_ip');
		
		$this->data['column_order'] = $this->language->get('column_order');
		
		$this->data['column_email'] = $this->language->get('column_email');
		
		$this->data['column_email_subject'] = $this->language->get('column_email_subject');
		
		$this->data['column_email_text'] = $this->language->get('column_email_text');
		
		$this->data['column_quote'] = $this->language->get('column_quote');
		
		$this->data['column_quote'] = $this->language->get('column_quote');
		
		$this->data['column_delivery'] = $this->language->get('column_delivery');
				
		$this->data['column_invoice'] = $this->language->get('column_invoice');
		
		$this->data['column_ip'] = $this->language->get('column_ip');

		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['column_date_added'] = $this->language->get('column_date_added');

		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['column_product_id'] = $this->language->get('column_product_id');
		
		$this->data['column_product_name'] = $this->language->get('column_product_name');
		
		$this->data['column_order_date'] = $this->language->get('column_order_date');
		
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		

		

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');

		$this->data['entry_lastname'] = $this->language->get('entry_lastname');

		$this->data['entry_email'] = $this->language->get('entry_email');

		$this->data['entry_telephone'] = $this->language->get('entry_telephone');

		$this->data['entry_fax'] = $this->language->get('entry_fax');

		$this->data['entry_password'] = $this->language->get('entry_password');

		$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');

		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');

		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['entry_company'] = $this->language->get('entry_company');

		$this->data['entry_company_id'] = $this->language->get('entry_company_id');

		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');

		$this->data['entry_address_1'] = $this->language->get('entry_address_1');

		$this->data['entry_address_2'] = $this->language->get('entry_address_2');

		$this->data['entry_city'] = $this->language->get('entry_city');

		$this->data['entry_postcode'] = $this->language->get('entry_postcode');

		$this->data['entry_zone'] = $this->language->get('entry_zone');

		$this->data['entry_country'] = $this->language->get('entry_country');

		$this->data['entry_default'] = $this->language->get('entry_default');

		$this->data['entry_comment'] = $this->language->get('entry_comment');

		$this->data['entry_description'] = $this->language->get('entry_description');

		$this->data['entry_amount'] = $this->language->get('entry_amount');

		$this->data['entry_points'] = $this->language->get('entry_points');
		$this->data['text_datecreated'] = $this->language->get('text_datecreated');
		$this->data['text_date_modified'] = $this->language->get('text_date_modified');
		$this->data['text_date_support'] = $this->language->get('text_date_support');
		$this->data['text_bank_cc'] = $this->language->get('text_bank');
		$this->data['text_bic'] = $this->language->get('text_bic');
		$this->data['text_fiscal'] = $this->language->get('text_fiscal');
		$this->data['text_receptor'] = $this->language->get('text_receptor');
		$this->data['text_paid'] = $this->language->get('text_paid');

		$this->data['button_save'] = $this->language->get('button_save');

		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['button_add_address'] = $this->language->get('button_add_address');

		$this->data['button_add_history'] = $this->language->get('button_add_history');

		$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');

		$this->data['button_add_reward'] = $this->language->get('button_add_reward');

		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['button_email'] = $this->language->get('button_email');
		
		$this->data['button_continue'] = $this->language->get('button_continue');



		$this->data['tab_general'] = $this->language->get('tab_general');
		
		$this->data['tab_notes'] = $this->language->get('tab_notes');

		$this->data['tab_address'] = $this->language->get('tab_address');

		$this->data['tab_history'] = $this->language->get('tab_history');

		$this->data['tab_transaction'] = $this->language->get('tab_transaction');

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
		$this->data['column_date'] = $this->language->get('column_date');

		$this->data['button_add_contact'] = $this->language->get('button_add_contact');
		$this->data['button_remove_contact'] = $this->language->get('button_remove_contact');

		// Contratos
		$this->data['tab_contracts'] = $this->language->get('tab_contracts');

		$this->data['column_article'] = $this->language->get('column_article');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_end_support'] = $this->language->get('column_end_support');

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
		$this->data['text_alert_imap'] = $this->language->get('text_alert_imap');

		
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

		

		if (isset($this->error['firstname'])) {

			$this->data['error_firstname'] = $this->error['firstname'];

		} else {

			$this->data['error_firstname'] = '';

		}



		if (isset($this->error['lastname'])) {

			$this->data['error_lastname'] = $this->error['lastname'];

		} else {

			$this->data['error_lastname'] = '';

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



		if (isset($this->error['address_firstname'])) {

			$this->data['error_address_firstname'] = $this->error['address_firstname'];

		} else {

			$this->data['error_address_firstname'] = '';

		}



		if (isset($this->error['address_lastname'])) {

			$this->data['error_address_lastname'] = $this->error['address_lastname'];

		} else {

			$this->data['error_address_lastname'] = '';

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



		if (isset($this->request->get['filter_approved'])) {

			$url .= '&filter_approved=' . $this->request->get['filter_approved'];

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

		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

			$customer_info = $this->model_sale_customer->getCustomer($this->request->get['customer_id']);
		}
		
		if (isset($this->request->get['customer_id'])){
			
			// Emails
			$this->data['emails'] = array();
			
			if (isset($customer_info)) {
				$results = $this->model_sale_customer->getEmailsByCustomerId($this->request->get['customer_id']);
			
				foreach ($results as $result) {
					$this->data['emails'][] = array(
						'email_id'   => $result['mail_id'],
						'subject'    => $result['title'],
						'text'       => strip_tags($result['message'], '<br>'),
						'date_added' => date('d/m/y', strtotime($result['date_added']))
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

		   			$action[] = array(
		   				'text'	=> $this->language->get('text_edit'),
		   				'href'	=> $this->url->link('sale/customer/updateContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['customer_contacts_id'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL')
		   			);

		   			$action[] = array(
		   				'text'	=> $this->language->get('text_delete'),
		   				'href'	=> $this->url->link('sale/customer/deleteContact', 'token=' . $this->session->data['token'] . '&contact_id=' . $result['customer_contacts_id'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL')
		   			);

					$this->data['contacts'][] = array(
						'contact_id' => $result['customer_contacts_id'],
						'name' => $result['cname'],
						'email' => $result['cemail'],
						'telephone' => $result['ctelef1'],
						'date' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
						'action' => $action
					);
				}
			}

			$this->data['add_contact'] = $this->url->link('sale/customer/insertContact', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->data['customer_id'], 'SSL');

			// Contracts
			$this->data['contracts'] = array();

			if (isset($customer_info)) {
				$results = $this->model_sale_customer->getCustomerContracts($this->request->get['customer_id']);

				foreach ($results as $result) {
					$action = array();

					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('sale/customer/updateContract', 'token=' . $this->session->data['token'] . '&contract_id=' . $result['nid'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL') 
					);

					$action[] = array(
						'text' => $this->language->get('text_delete'),
						'href' => $this->url->link('sale/customer/deleteContract', 'token=' . $this->session->data['token'] . '&contract_id=' . $result['nid'] . '&customer_id=' . $this->request->get['customer_id'] . $url, 'SSL') 
					);

					$this->load->model('catalog/product');

					if ($result['narticulo'] > 0) {
						$product = $this->model_catalog_product->getProduct($result['narticulo']);
						$product_name = $product['name'];
					} else {
						$product_name = '';
					}
					
				
					$this->data['contracts'][] = array(
						'nid'			=> $result['nid'],
						'product'		=> $product_name,
						'quantity'		=> $result['ncantidad'],
						'end_support'	=> $result['dfinsoport'],
						'action'		=> $action
					);
				}
			}

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

		// if (isset($this->request->post['firstname'])) {
		// 	$this->data['firstname'] = $this->request->post['firstname'];
		// } elseif (!empty($customer_info)) { 
		// 	$this->data['firstname'] = $customer_info['firstname'];
		// } else {
		// 	$this->data['firstname'] = '';
		// }

		// if (isset($this->request->post['lastname'])) {
		// 	$this->data['lastname'] = $this->request->post['lastname'];
		// } elseif (!empty($customer_info)) {
		// 	$this->data['lastname'] = $customer_info['lastname'];
		// } else {
		// 	$this->data['lastname'] = '';
		// }

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
		
		if (isset($this->request->post['fax'])) {
			$this->data['fax'] = $this->request->post['fax'];

		} elseif (!empty($customer_info)) {

			$this->data['fax'] = $customer_info['fax'];

		} else {

			$this->data['fax'] = '';

		}

		if (isset($this->request->post['notes'])) {
      		$this->data['notes'] = $this->request->post['notes'];
    	} elseif (isset($customer_info)) { 
			$this->data['notes'] = $customer_info['notes'];
		} else {
      		$this->data['notes'] = '';
    	}
	
		if (isset($customer_info)) { 
			$this->data['date_added'] = date($this->language->get('date_format_short').' ' . $this->language->get('time_format'), strtotime($customer_info['date_added']));
		} else {
      		$this->data['date_added'] = '';
    	}

		if (isset($customer_info)) {
			if ($customer_info['date_modified'] == '0000-00-00 00:00:00') {
				$this->data['date_modified'] = $this->data['date_added'];
			} else {
				$this->data['date_modified'] = date($this->language->get('date_format_short').' ' . $this->language->get('time_format'), strtotime($customer_info['date_modified']));
			}
		} else {
			$this->data['date_modified'] = '';
    	}
		
		if (isset($customer_info)) {
			if ($customer_info['date_support']!='0000-00-00') {
				$this->data['date_support'] = date($this->language->get('date_format_short').' ' . $this->language->get('time_format'), strtotime($customer_info['date_support']));
			} else {
				$this->data['date_support'] = '';
			}
		} else {
      		$this->data['date_support'] = '';
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
				
		if (isset($this->request->post['efaccare'])) {
			$this->data['efaccare'] = $this->request->post['efaccare'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccare'] = $customer_info['efaccare'];
		} else {
			$this->data['efaccare'] = '';
		}
				
		if (isset($this->request->post['efaccapa'])) {
			$this->data['efaccapa'] = $this->request->post['efaccapa'];
		} elseif (!empty($customer_info)) {
			$this->data['efaccapa'] = $customer_info['efaccapa'];
		} else {
			$this->data['efaccapa'] = '';
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



		if (isset($this->request->post['address'])) { 

			$this->data['addresses'] = $this->request->post['address'];

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
					'date'     => $note_info['date_added'],
					'user'     => $note_info['Login'],
					'comment'  => $note_info['comment'], 
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
  
		if (isset($this->request->post['address'])) {

			foreach ($this->request->post['address'] as $key => $value) {

				if ((utf8_strlen($value['firstname']) < 1) || (utf8_strlen($value['firstname']) > 32)) {

					$this->error['address_firstname'][$key] = $this->language->get('error_firstname');

				}



				if ((utf8_strlen($value['lastname']) < 1) || (utf8_strlen($value['lastname']) > 32)) {

					$this->error['address_lastname'][$key] = $this->language->get('error_lastname');

				}	



				if ((utf8_strlen($value['address_1']) < 3) || (utf8_strlen($value['address_1']) > 128)) {

					$this->error['address_address_1'][$key] = $this->language->get('error_address_1');

				}



				if ((utf8_strlen($value['city']) < 2) || (utf8_strlen($value['city']) > 128)) {

					$this->error['address_city'][$key] = $this->language->get('error_city');

				} 



				$this->load->model('localisation/country');



				$country_info = $this->model_localisation_country->getCountry($value['country_id']);



				if ($country_info) {

					if ($country_info['postcode_required'] && (utf8_strlen($value['postcode']) < 2) || (utf8_strlen($value['postcode']) > 10)) {

						$this->error['address_postcode'][$key] = $this->language->get('error_postcode');

					}



					// VAT Validation

					$this->load->helper('vat');



					if ($this->config->get('config_vat') && $value['tax_id'] && (vat_validation($country_info['iso_code_2'], $value['tax_id']) == 'invalid')) {

						$this->error['address_tax_id'][$key] = $this->language->get('error_vat');

					}

				}



				if ($value['country_id'] == '') {

					$this->error['address_country'][$key] = $this->language->get('error_country');

				}



				if (!isset($value['zone_id']) || $value['zone_id'] == '') {

					$this->error['address_zone'][$key] = $this->language->get('error_zone');

				}	

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



		$transaction_total = $this->model_sale_customer->getTotalHistories($this->request->get['customer_id']);



		$pagination = new Pagination();

		$pagination->total = $transaction_total;

		$pagination->page = $page;

		$pagination->limit = 10; 

		$pagination->text = $this->language->get('text_pagination');

		$pagination->url = $this->url->link('sale/customer/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');



		$this->data['pagination'] = $pagination->render();



		$this->template = 'sale/customer_history.tpl';		



		$this->response->setOutput($this->render());

	}



	public function transaction() {

		$this->language->load('sale/customer');



		$this->load->model('sale/customer');



		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'sale/customer')) { 

			$this->model_sale_customer->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount']);



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

		$this->data['column_amount'] = $this->language->get('column_amount');



		if (isset($this->request->get['page'])) {

			$page = $this->request->get['page'];

		} else {

			$page = 1;

		}  



		$this->data['transactions'] = array();



		$results = $this->model_sale_customer->getTransactions($this->request->get['customer_id'], ($page - 1) * 10, 10);



		foreach ($results as $result) {

			$this->data['transactions'][] = array(

				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),

				'description' => $result['description'],

				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))

			);

		}



		$this->data['balance'] = $this->currency->format($this->model_sale_customer->getTransactionTotal($this->request->get['customer_id']), $this->config->get('config_currency'));



		$transaction_total = $this->model_sale_customer->getTotalTransactions($this->request->get['customer_id']);



		$pagination = new Pagination();

		$pagination->total = $transaction_total;

		$pagination->page = $page;

		$pagination->limit = 10; 

		$pagination->text = $this->language->get('text_pagination');

		$pagination->url = $this->url->link('sale/customer/transaction', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', 'SSL');



		$this->data['pagination'] = $pagination->render();



		$this->template = 'sale/customer_transaction.tpl';		



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
				'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				'customer_group'    => $result['customer_group'],
				'firstname'         => $result['firstname'],
				'lastname'          => $result['lastname'],
				'email'             => $result['email'],
				'telephone'         => $result['telephone'],
				'fax'               => $result['fax'],
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

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

      	  	$this->model_sale_customer->addCustomerContact($this->request->post, $this->request->get['customer_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getContactForm();
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
		$this->data['heading_contact'] 			= $this->language->get('heading_contact');

		$this->data['entry_name']				= $this->language->get('entry_name');
		$this->data['entry_email']				= $this->language->get('entry_email');
		$this->data['entry_telephone']			= $this->language->get('entry_telephone');
		$this->data['entry_telephone2']			= $this->language->get('entry_telephone2');
		$this->data['entry_puesto']				= $this->language->get('entry_puesto');
		$this->data['entry_notas']				= $this->language->get('entry_notas');

		$this->data['button_save']				= $this->language->get('button_save');
		$this->data['button_cancel']			= $this->language->get('button_cancel');

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


	public function updateContract() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

      	  	$this->model_sale_customer->editCustomerContract($this->request->post, $this->request->get['contract_id']);

			$this->session->data['success'] = $this->language->get('text_success');


			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&contract_id=' . $this->request->get['contract_id'], 'SSL'));
		}

		$this->getContractForm();
	}

	public function insertContract() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

      	  	$this->model_sale_customer->addCustomerContract($this->request->post, $this->request->get['customer_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getContractForm();
	}

	public function deleteContract() {
		
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

		if (isset($this->request->get['contract_id'])) {

      	  	$this->model_sale_customer->deleteCustomerContract($this->request->get['contract_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getForm();
	}

	public function getContractForm() {
		$this->load->model('sale/customer');

		$this->data['heading_title'] 			= $this->language->get('heading_title');

		$this->data['entry_article'] 			= $this->language->get('entry_article');
		$this->data['entry_quantity'] 			= $this->language->get('entry_quantity');
		$this->data['entry_date'] 				= $this->language->get('entry_date');
		$this->data['entry_end_support'] 		= $this->language->get('entry_end_support');
		$this->data['entry_notes'] 				= $this->language->get('entry_notes');
		$this->data['entry_status'] 			= $this->language->get('entry_status');

		$this->data['button_save']				= $this->language->get('button_save');
		$this->data['button_cancel']			= $this->language->get('button_cancel');

		$this->data['text_select']				= $this->language->get('text_select');

		if (isset($this->request->get['contract_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$contract_info = $this->model_sale_customer->getCustomerContract($this->request->get['contract_id']);
		}
		
		if (isset($this->request->get['contract_id'])) {
			$this->data['contract_id'] = $this->request->get['contract_id'];
		} else {
			$this->data['contract_id'] = 0;
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->load->model('catalog/product');
		$this->data['products'] = $this->model_catalog_product->getProducts();

		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (isset($contract_info)) {
			$this->data['product_id'] = $contract_info['narticulo'];
		} else {
			$this->data['product_id'] = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$this->data['quantity'] = $this->request->post['quantity'];
		} elseif (isset($contract_info)) {
			$this->data['quantity'] = $contract_info['ncantidad'];
		} else {
			$this->data['quantity'] = 1;
		}

		if (isset($this->request->post['date_purchased'])) {
			$this->data['date_purchased'] = $this->request->post['date_purchased'];
		} elseif (isset($contract_info)) {
			$this->data['date_purchased'] = $contract_info['dcompra'];
		} else {
			$this->data['date_purchased'] = '';
		}

		if (isset($this->request->post['end_support'])) {
			$this->data['end_support'] = $this->request->post['end_support'];
		} elseif (isset($contract_info)) {
			$this->data['end_support'] = $contract_info['dfinsoport'];
		} else {
			$this->data['end_support'] = '';
		}

		if (isset($this->request->post['notes'])) {
			$this->data['notes'] = $this->request->post['notes'];
		} elseif (isset($contract_info)) {
			$this->data['notes'] = $contract_info['mnotas'];
		} else {
			$this->data['notes'] = '';
		}

		$this->data['contract_statuses'] = $this->model_sale_customer->getCustomerContractStatus();

		if (isset($this->request->post['contract_status_id'])) {
			$this->data['contract_status_id'] = $this->request->post['contract_status_id'];
		} elseif (isset($contract_info)) {
			$this->data['contract_status_id'] = $contract_info['contract_status'];
		} else {
			$this->data['contract_status_id'] = 0;
		}

		if ($this->data['contract_id'] == 0) {
			$this->data['action'] = $this->url->link('sale/customer/insertContract', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/updateContract', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&contract_id=' . $this->data['contract_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL');		

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
			$this->model_sale_customer->addCustomerNote($this->request->post, $this->request->get['customer_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->request->get['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
		}

		$this->getNoteForm();
	}

	public function deleteNote() {
		$this->load->language('sale/customer');

		$this->load->model('sale/customer');

		if (isset($this->request->get['note_id'])) {
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
		$data['customer_id'] = $this->request->get['customer_id'];

		$data['to'] = $this->request->post['to'];
		$data['subject'] = $this->request->post['subject'];
		$data['text'] = $this->request->post['message'];
		$data['code'] = md5($this->request->post['message']);

		$lcFile = '';
		if (isset($this->request->post['filename']) && $this->request->post['filename']!=''){
			$lcFile = DIR_DOWNLOAD . $this->request->post['filename'];

			$newName = substr($lcFile, 0, strripos($lcFile, '.'));

			if (rename($lcFile, $newName)) {
				$lcFile = $newName;
			}
		}

		$this->sendnewmail($data['to'], $data['subject'], $data['text'], $lcFile);

		$this->load->model('catalog/mail');

		$this->model_catalog_mail->addMailSended($data);

		$this->redirect($this->url->link('sale/customer/update', 'token=' . $this->request->get['token'] . '&customer_id=' . $this->request->get['customer_id'], 'SSL'));
	}
}

?>