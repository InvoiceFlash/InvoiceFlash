<?php
class ControllerSaleInvoice extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/invoice');

    	$this->getList();
  	}
	
  	public function insert() {
		$this->load->language('sale/invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/invoice');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			$this->model_sale_invoice->addInvoice($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';
			
			if (isset($this->request->get['filter_invoice_id'])) {
				$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_invoice_status_id'])) {
				$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
						
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			
			$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'sale/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
	   
			$this->getList();
	  	}else{
			$this->getForm();
	  	}
  	}
	
  	public function update() {
		$this->load->language('sale/invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/invoice');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_sale_invoice->editInvoice($this->request->get['invoice_id'], $this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_invoice_id'])) {
				$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_invoice_status_id'])) {
				$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
						
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			
			$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
    	if (!$this->user->hasPermission('modify', 'sale/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		
			$this->getList();
		}else{
			$this->getForm();
		}
  	}
	
  	public function delete() {
		$this->load->language('sale/invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/invoice');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $invoice_id) {
				$this->model_sale_invoice->createNegativeInvoice($invoice_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_invoice_id'])) {
				$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_invoice_status_id'])) {
				$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
						
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

			$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

  	private function getList() {
  		if (!extension_loaded('openssl')) {
			$this->data['error_warning'] = 'OpenSSL library is not installed. You cannot sign invoices.';
		} else {
			if (!extension_loaded('curl')) {
				$this->data['error_warning'] = 'curl library is not installed. You cannot sign invoices.';
			}
		}
		
		if (isset($this->request->get['filter_invoice_id'])) {
			$filter_invoice_id = $this->request->get['filter_invoice_id'];
		} else {
			$filter_invoice_id = null;
		}

		if (isset($this->request->get['filter_company'])) {
			$filter_company = $this->request->get['filter_company'];
		} else {
			$filter_company = null;
		}

		if (isset($this->request->get['filter_invoice_status_id'])) {
			$filter_invoice_status_id = $this->request->get['filter_invoice_status_id'];
		} else {
			$filter_invoice_status_id = null;
		}
		
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_invoice_status_id'])) {
			$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			'href'      => $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['invoice'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['print'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		// add print selection
		$reports = array_slice(scandir(DIR_TEMPLATE . 'sale/reports'), 2);

		$this->data['reports'] = array();

		foreach ($reports as $report) {
			$name = ucfirst(str_replace('_', ' ', str_replace('.tpl', '', str_replace('_invoice', '', $report))));

			if (strpos($name, 'Invoice')!==FALSE) {
				$this->data['reports'][] = array(
					'name' => $name,
					'report' => $report
				);
			}
		}
		// end add

		$this->data['invoices'] = array();

		$data = array(
			'filter_invoice_id'        => $filter_invoice_id,
			'filter_company'	     => $filter_company,
			'filter_invoice_status_id' => $filter_invoice_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$invoice_total = $this->model_sale_invoice->getTotalInvoices($data);

		$results = $this->model_sale_invoice->getInvoices($data);

    	foreach ($results as $result) {
			$action = array();
                      
			$action[] = array(
				'href' => $this->url->link('sale/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL'),
				'icon' => 'far fa-eye',
				'color' => 'info'
			);
			
			if ($this->model_sale_invoice->getPaidReceipts($result['invoice_id']) == 0)	{
				$action[] = array(
					'href' => $this->url->link('sale/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL'),
					'icon' => 'fas fa-edit',
					'color' => 'default'
				);
			}
			
			$this->data['invoices'][] = array(
				'invoice_id'      => $result['invoice_id'],
				'company'       => $result['company'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['invoice_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['column_invoice_id'] = $this->language->get('column_invoice_id');
    	$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['error_no_selection'] = $this->language->get('error_no_selection');

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

		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_invoice_status_id'])) {
			$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_invoice'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=o.invoice_id' . $url, 'SSL');
		$this->data['sort_company'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=company' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_invoice_status_id'])) {
			$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $invoice_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_invoice_id'] = $filter_invoice_id;
		$this->data['filter_company'] = $filter_company;
		$this->data['filter_invoice_status_id'] = $filter_invoice_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/invoice_status');

		$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/invoice_list.tpl';

		$this->children = array(

			'common/header',

			'common/footer'

		);

		$this->response->setOutput($this->render());
  	}
	
  	public function getForm() {
		$this->load->model('sale/customer');
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');  
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_sub_total'] = $this->language->get('text_sub_total');
		$this->data['text_invoice'] = $this->language->get('text_invoice');
		$this->data['text_invoice_details'] = $this->language->get('text_invoice_details');
		
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_vat'] = $this->language->get('entry_vat');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_invoice_status'] = $this->language->get('entry_invoice_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');	
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$this->data['entry_country'] = $this->language->get('entry_country');		
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		//add
		$this->data['entry_name_ext'] = $this->language->get('entry_name_ext');
		$this->data['entry_price'] = $this->language->get('entry_price');
		// end
		$this->data['entry_to_name'] = $this->language->get('entry_to_name');
		$this->data['entry_to_email'] = $this->language->get('entry_to_email');
		$this->data['entry_from_name'] = $this->language->get('entry_from_name');
		$this->data['entry_from_email'] = $this->language->get('entry_from_email');
		$this->data['entry_theme'] = $this->language->get('entry_theme');	
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
			
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_update_total'] = $this->language->get('button_update_total');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['tab_invoice'] = $this->language->get('tab_invoice');
		$this->data['tab_customer'] = $this->language->get('tab_customer');
		$this->data['tab_payment'] = $this->language->get('tab_payment');
		$this->data['tab_shipping'] = $this->language->get('tab_shipping');
		$this->data['tab_product'] = $this->language->get('tab_product');
		$this->data['tab_total'] = $this->language->get('tab_total');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['paid_out'])) {
			$this->data['error_paid_out'] = $this->error['paid_out'];
		} else {
			$this->data['error_paid_out'] = '';
		}

		if (isset($this->error['remittance'])) {
			$this->data['error_remittance'] = $this->error['remittance'];
		} else {
			$this->data['error_remittance'] = '';
		}
		
 		if (isset($this->error['company'])) {
			$this->data['error_customer'] = $this->error['company'];
		} else {
			$this->data['error_customer'] = '';
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

		if (isset($this->error['payment_address_1'])) {
			$this->data['error_payment_address_1'] = $this->error['payment_address_1'];
		} else {
			$this->data['error_payment_address_1'] = '';
		}
		
		if (isset($this->error['payment_city'])) {
			$this->data['error_payment_city'] = $this->error['payment_city'];
		} else {
			$this->data['error_payment_city'] = '';
		}
		
		if (isset($this->error['payment_postcode'])) {
			$this->data['error_payment_postcode'] = $this->error['payment_postcode'];
		} else {
			$this->data['error_payment_postcode'] = '';
		}
		
		if (isset($this->error['payment_tax_id'])) {
			$this->data['error_payment_tax_id'] = $this->error['payment_tax_id'];
		} else {
			$this->data['error_payment_tax_id'] = '';
		}
				
		if (isset($this->error['payment_country'])) {
			$this->data['error_payment_country'] = $this->error['payment_country'];
		} else {
			$this->data['error_payment_country'] = '';
		}
		
		if (isset($this->error['payment_zone'])) {
			$this->data['error_payment_zone'] = $this->error['payment_zone'];
		} else {
			$this->data['error_payment_zone'] = '';
		}
		
		if (isset($this->error['payment_method'])) {
			$this->data['error_payment_method'] = $this->error['payment_method'];
		} else {
			$this->data['error_payment_method'] = '';
		}

		if (isset($this->error['shipping_address_1'])) {
			$this->data['error_shipping_address_1'] = $this->error['shipping_address_1'];
		} else {
			$this->data['error_shipping_address_1'] = '';
		}
		
		if (isset($this->error['shipping_city'])) {
			$this->data['error_shipping_city'] = $this->error['shipping_city'];
		} else {
			$this->data['error_shipping_city'] = '';
		}
		
		if (isset($this->error['shipping_postcode'])) {
			$this->data['error_shipping_postcode'] = $this->error['shipping_postcode'];
		} else {
			$this->data['error_shipping_postcode'] = '';
		}
		
		if (isset($this->error['shipping_country'])) {
			$this->data['error_shipping_country'] = $this->error['shipping_country'];
		} else {
			$this->data['error_shipping_country'] = '';
		}
		
		if (isset($this->error['shipping_zone'])) {
			$this->data['error_shipping_zone'] = $this->error['shipping_zone'];
		} else {
			$this->data['error_shipping_zone'] = '';
		}
		
		if (isset($this->error['shipping_method'])) {
			$this->data['error_shipping_method'] = $this->error['shipping_method'];
		} else {
			$this->data['error_shipping_method'] = '';
		}
								
		$url = '';

		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_invoice_status_id'])) {
			$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
			'href'      => $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
			'separator' => ' :: '
		);

		if (!isset($this->request->get['invoice_id'])) {
			$this->data['action'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['invoice_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$invoice_info = $this->model_sale_invoice->getInvoice($this->request->get['invoice_id']);
		}

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['invoice_id'])) {
			$this->data['invoice_id'] = $this->request->get['invoice_id'];
		} else {
			$this->data['invoice_id'] = 0;
		}
					
    	if (isset($this->request->post['store_id'])) {
      		$this->data['store_id'] = $this->request->post['store_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['store_id'] = $invoice_info['store_id'];
		} else {
      		$this->data['store_id'] = '';
    	}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['store_url'] = HTTPS_CATALOG;
		} else {
			$this->data['store_url'] = HTTP_CATALOG;
		}
		
		if (isset($this->request->post['company'])) {
			$this->data['company'] = $this->request->post['company'];
		} elseif (!empty($invoice_info)) {
			$this->data['company'] = $invoice_info['company'];
		} else {
			$this->data['company'] = '';
		}
						
		if (isset($this->request->post['customer_id'])) {
			$this->data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($invoice_info)) {
			$this->data['customer_id'] = $invoice_info['customer_id'];
		} else {
			$this->data['customer_id'] = '';
		}
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($invoice_info)) {
			$this->data['customer_group_id'] = $invoice_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '';
		}
		
		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['company'])) {
      		$this->data['company'] = $this->request->post['company'];
		} elseif (!empty($invoice_info)) { 
			$this->data['company'] = $invoice_info['company'];
		} else {
      		$this->data['company'] = '';
    	}
		
    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['email'] = $invoice_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
				
    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['telephone'] = $invoice_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}
		
    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['fax'] = $invoice_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}	
		
		if (isset($this->request->post['invoice_status_id'])) {
      		$this->data['invoice_status_id'] = $this->request->post['invoice_status_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['invoice_status_id'] = $invoice_info['invoice_status_id'];
		} else {
      		$this->data['invoice_status_id'] = '';
    	}
			
		$this->load->model('localisation/invoice_status');
		
		$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();	
		
		$this->load->model('setting/extension');

		$this->data['shipping_option_codes'] = $this->model_sale_invoice->getInvoiceShippingCodes();
		
    	if (isset($this->request->post['comment'])) {
      		$this->data['comment'] = $this->request->post['comment'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['comment'] = $invoice_info['comment'];
		} else {
      		$this->data['comment'] = '';
    	}	
		
		$this->load->model('sale/customer');

		if (isset($this->request->post['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
		} elseif (!empty($invoice_info)) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($invoice_info['customer_id']);
		} else {
			$this->data['addresses'] = array();
		}

    	if (isset($this->request->post['payment_company'])) {
      		$this->data['payment_company'] = $this->request->post['payment_company'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_company'] = $invoice_info['payment_company'];
		} else {
      		$this->data['payment_company'] = '';
    	}
		
    	if (isset($this->request->post['payment_company_id'])) {
      		$this->data['payment_company_id'] = $this->request->post['payment_company_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_company_id'] = $invoice_info['payment_company_id'];
		} else {
      		$this->data['payment_company_id'] = '';
    	}
		
    	if (isset($this->request->post['payment_address_1'])) {
      		$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_address_1'] = $invoice_info['payment_address_1'];
		} else {
      		$this->data['payment_address_1'] = '';
    	}

    	if (isset($this->request->post['payment_address_2'])) {
      		$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_address_2'] = $invoice_info['payment_address_2'];
		} else {
      		$this->data['payment_address_2'] = '';
    	}
		
    	if (isset($this->request->post['payment_city'])) {
      		$this->data['payment_city'] = $this->request->post['payment_city'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_city'] = $invoice_info['payment_city'];
		} else {
      		$this->data['payment_city'] = '';
    	}

    	if (isset($this->request->post['payment_postcode'])) {
      		$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_postcode'] = $invoice_info['payment_postcode'];
		} else {
      		$this->data['payment_postcode'] = '';
    	}
				
    	if (isset($this->request->post['payment_country_id'])) {
      		$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_country_id'] = $invoice_info['payment_country_id'];
		} else {
      		$this->data['payment_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['payment_zone_id'])) {
      		$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_zone_id'] = $invoice_info['payment_zone_id'];
		} else {
      		$this->data['payment_zone_id'] = '';
    	}
						
    	if (isset($this->request->post['payment_method'])) {
      		$this->data['payment_method'] = $this->request->post['payment_method'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_method'] = $invoice_info['payment_method'];
		} else {
      		$this->data['payment_method'] = '';
    	}
		
    	if (isset($this->request->post['payment_code'])) {
      		$this->data['payment_code'] = $this->request->post['payment_code'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_code'] = $invoice_info['payment_code'];
		} else {
      		$this->data['payment_code'] = '';
    	}			

    	if (isset($this->request->post['shipping_company'])) {
      		$this->data['shipping_company'] = $this->request->post['shipping_company'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_company'] = $invoice_info['shipping_company'];
		} else {
      		$this->data['shipping_company'] = '';
    	}

    	if (isset($this->request->post['shipping_address_1'])) {
      		$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_address_1'] = $invoice_info['shipping_address_1'];
		} else {
      		$this->data['shipping_address_1'] = '';
    	}

    	if (isset($this->request->post['shipping_address_2'])) {
      		$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_address_2'] = $invoice_info['shipping_address_2'];
		} else {
      		$this->data['shipping_address_2'] = '';
    	}
		
    	if (isset($this->request->post['shipping_city'])) {
      		$this->data['shipping_city'] = $this->request->post['shipping_city'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_city'] = $invoice_info['shipping_city'];
		} else {
      		$this->data['shipping_city'] = '';
    	}
		
    	if (isset($this->request->post['shipping_postcode'])) {
      		$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_postcode'] = $invoice_info['shipping_postcode'];
		} else {
      		$this->data['shipping_postcode'] = '';
    	}
				
    	if (isset($this->request->post['shipping_country_id'])) {
      		$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_country_id'] = $invoice_info['shipping_country_id'];
		} else {
      		$this->data['shipping_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['shipping_zone_id'])) {
      		$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_zone_id'] = $invoice_info['shipping_zone_id'];
		} else {
      		$this->data['shipping_zone_id'] = '';
    	}	
						
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();															
		
    	if (isset($this->request->post['shipping_method'])) {
      		$this->data['shipping_method'] = $this->request->post['shipping_method'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_method'] = $invoice_info['shipping_method'];
		} else {
      		$this->data['shipping_method'] = '';
    	}	
		
    	if (isset($this->request->post['shipping_code'])) {
      		$this->data['shipping_code'] = $this->request->post['shipping_code'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_code'] = $invoice_info['shipping_code'];
		} else {
      		$this->data['shipping_code'] = '';
    	}

		if (isset($this->request->post['invoice_product'])) {
			$invoice_products = $this->request->post['invoice_product'];
		} elseif (isset($this->request->get['invoice_id'])) {
			$invoice_products = $this->model_sale_invoice->getInvoiceProducts($this->request->get['invoice_id']);			
		} else {
			$invoice_products = array();
		}
		
		$this->load->model('catalog/product');
		
		//$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
		$this->data['invoice_products'] = array();		
		
		foreach ($invoice_products as $invoice_product) {
			if (isset($invoice_product['invoice_option'])) {
				$invoice_option = $invoice_product['invoice_option'];
			} elseif (isset($this->request->get['invoice_id'])) {
				$invoice_option = $this->model_sale_invoice->getInvoiceOptions($this->request->get['invoice_id'], $invoice_product['invoice_product_id']);
			} else {
				$invoice_option = array();
			}
											
			$this->data['invoice_products'][] = array(
				'invoice_product_id' => $invoice_product['invoice_product_id'],
				'product_id'       => $invoice_product['product_id'],
				'name'             => $invoice_product['name'],
				'model'            => $invoice_product['model'],
				'option'           => $invoice_option,
				'quantity'         => $invoice_product['quantity'],
				'price'			   => $this->currency->format($invoice_product['price'], $invoice_info['currency_code'], $invoice_info['currency_value']),
				'total'            => $this->currency->format($invoice_product['total'], $invoice_info['currency_code'], $invoice_info['currency_value']),
				'tax'              => $invoice_product['tax']
			);
		}
		
		if (isset($this->request->post['invoice_total'])) {
      		$this->data['invoice_totals'] = $this->request->post['invoice_total'];
    	} elseif (isset($this->request->get['invoice_id'])) { 
			$this->data['invoice_totals'] = $this->model_sale_invoice->getInvoiceTotals($this->request->get['invoice_id']);
		} else {
      		$this->data['invoice_totals'] = array();
		}	
		
		$this->load->model('localisation/payment');
		$this->load->model('localisation/shipping');

		$this->data['payments'] = $this->model_localisation_payment->getPayments();
		$this->data['shippings'] = $this->model_localisation_shipping->getShippings();
		
		$this->template = 'sale/invoice_form.tpl';

		$this->children = array(

			'common/header',

			'common/footer'

		);

		$this->response->setOutput($this->render());
  	}
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/invoice')) {
      		$this->error['warning'] = $this->language->get('error_permission');
		}
		

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    
	
   	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
	
	public function info() {
		$this->load->model('sale/invoice');

		if (isset($this->request->get['invoice_id'])) {
			$invoice_id = $this->request->get['invoice_id'];
		} else {
			$invoice_id = 0;
		}

		$invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

		if ($invoice_info) {
			$this->load->language('sale/invoice');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_invoice_id'] = $this->language->get('text_invoice_id');
			$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
			$this->data['text_store_name'] = $this->language->get('text_store_name');
			$this->data['text_store_url'] = $this->language->get('text_store_url');		
			$this->data['text_customer'] = $this->language->get('text_customer');
			$this->data['text_customer_group'] = $this->language->get('text_customer_group');
			$this->data['text_email'] = $this->language->get('text_email');
			$this->data['text_telephone'] = $this->language->get('text_telephone');
			$this->data['text_fax'] = $this->language->get('text_fax');

			$this->data['text_total'] = $this->language->get('text_total');
			$this->data['text_invoice_status'] = $this->language->get('text_invoice_status');
			$this->data['text_invoice_status'] = $this->language->get('text_invoice_status');
			$this->data['text_comment'] = $this->language->get('text_comment');
			$this->data['text_commission'] = $this->language->get('text_commission');
			$this->data['text_date_added'] = $this->language->get('text_date_added');
			$this->data['text_date_modified'] = $this->language->get('text_date_modified');			
			$this->data['text_company'] = $this->language->get('text_company');
			$this->data['text_company_id'] = $this->language->get('text_company_id');
			$this->data['text_tax_id'] = $this->language->get('text_tax_id');
			$this->data['text_address_1'] = $this->language->get('text_address_1');
			$this->data['text_address_2'] = $this->language->get('text_address_2');
			$this->data['text_city'] = $this->language->get('text_city');
			$this->data['text_postcode'] = $this->language->get('text_postcode');
			$this->data['text_zone'] = $this->language->get('text_zone');
			$this->data['text_zone_code'] = $this->language->get('text_zone_code');
			$this->data['text_country'] = $this->language->get('text_country');
			$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$this->data['text_payment_method'] = $this->language->get('text_payment_method');	
			$this->data['text_download'] = $this->language->get('text_download');
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_generate'] = $this->language->get('text_generate');
			$this->data['text_commission_add'] = $this->language->get('text_commission_add');
			$this->data['text_commission_remove'] = $this->language->get('text_commission_remove');
			$this->data['text_credit_add'] = $this->language->get('text_credit_add');
			$this->data['text_credit_remove'] = $this->language->get('text_credit_remove');
			$this->data['text_country_match'] = $this->language->get('text_country_match');
			$this->data['text_country_code'] = $this->language->get('text_country_code');
			$this->data['text_high_risk_country'] = $this->language->get('text_high_risk_country');
			$this->data['text_distance'] = $this->language->get('text_distance');
			$this->data['text_anonymous_proxy'] = $this->language->get('text_anonymous_proxy');
			$this->data['text_proxy_score'] = $this->language->get('text_proxy_score');
			$this->data['text_is_trans_proxy'] = $this->language->get('text_is_trans_proxy');
			$this->data['text_free_mail'] = $this->language->get('text_free_mail');
			$this->data['text_carder_email'] = $this->language->get('text_carder_email');
			$this->data['text_high_risk_username'] = $this->language->get('text_high_risk_username');
			$this->data['text_high_risk_password'] = $this->language->get('text_high_risk_password');
			$this->data['text_bin_match'] = $this->language->get('text_bin_match');
			$this->data['text_bin_country'] = $this->language->get('text_bin_country');
			$this->data['text_bin_name_match'] = $this->language->get('text_bin_name_match');
			$this->data['text_bin_name'] = $this->language->get('text_bin_name');
			$this->data['text_bin_phone_match'] = $this->language->get('text_bin_phone_match');
			$this->data['text_bin_phone'] = $this->language->get('text_bin_phone');
			$this->data['text_customer_phone_in_billing_location'] = $this->language->get('text_customer_phone_in_billing_location');
			$this->data['text_ship_forward'] = $this->language->get('text_ship_forward');
			$this->data['text_city_postal_match'] = $this->language->get('text_city_postal_match');
			$this->data['text_ship_city_postal_match'] = $this->language->get('text_ship_city_postal_match');
			$this->data['text_score'] = $this->language->get('text_score');
			$this->data['text_explanation'] = $this->language->get('text_explanation');
			$this->data['text_risk_score'] = $this->language->get('text_risk_score');
			$this->data['text_queries_remaining'] = $this->language->get('text_queries_remaining');
			$this->data['text_maxmind_id'] = $this->language->get('text_maxmind_id');
			$this->data['text_error'] = $this->language->get('text_error');
			// Add
			$this->data['entry_invoice_id'] = $this->language->get('entry_invoice_id');
			// End add
			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_download'] = $this->language->get('column_download');
			$this->data['column_filename'] = $this->language->get('column_filename');
			$this->data['column_remaining'] = $this->language->get('column_remaining');
						
			$this->data['entry_invoice_status'] = $this->language->get('entry_invoice_status');
			$this->data['entry_notify'] = $this->language->get('entry_notify');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			
			$this->data['button_invoice'] = $this->language->get('button_invoice');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_history'] = $this->language->get('button_add_history');
		
			$this->data['tab_invoice'] = $this->language->get('tab_invoice');
			$this->data['tab_payment'] = $this->language->get('tab_payment');
			$this->data['tab_shipping'] = $this->language->get('tab_shipping');
			$this->data['tab_product'] = $this->language->get('tab_product');
			$this->data['tab_invoice_history'] = $this->language->get('tab_invoice_history');
			$this->data['tab_fraud'] = $this->language->get('tab_fraud');
			$this->data['tab_history'] = $this->language->get('tab_history');
		
			$this->data['token'] = $this->session->data['token'];

			$url = '';

			if (isset($this->request->get['filter_invoice_id'])) {
				$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_invoice_status_id'])) {
				$url .= '&filter_invoice_status_id=' . $this->request->get['filter_invoice_status_id'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
						
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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
				'href'      => $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
				'separator' => ' :: '
			);

			$this->data['printPDF'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'] . '&format=pdf', 'SSL');
			$this->data['facturae'] = $this->url->link('sale/invoice/facturae', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'], 'SSL');
			$this->data['invoice'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'] . '&format=view', 'SSL');
			$this->data['sendEmail'] = $this->url->link('sale/invoice/email', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'], 'SSL');
			$this->data['cancel'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');

			// add print selection
			$this->data['print'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'], 'SSL');

			$reports = array_slice(scandir(DIR_TEMPLATE . 'sale/reports'), 2);

			$this->data['reports'] = array();

			foreach ($reports as $report) {
				$name = ucwords(str_replace('_', ' ', str_replace('.tpl', '', str_replace('_invoice', '', $report))));

				if (strpos($name, 'Invoice')!==FALSE) {
					$this->data['reports'][] = array(
						'name' => $name,
						'report' => $report
					);
				}
			}
			// end add
			
			$this->data['invoice_id'] = $this->request->get['invoice_id'];
			
			if ($invoice_info['invoice_no']) {
				$this->data['invoice_no'] = $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];
			} else {
				$this->data['invoice_no'] = '';
			}
			
			$this->data['store_name'] = $invoice_info['store_name'];
			$this->data['store_url'] = $invoice_info['store_url'];
						
			if ($invoice_info['customer_id']) {
				$this->data['customer'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $invoice_info['customer_id'], 'SSL');
			} else {
				$this->data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($invoice_info['customer_group_id']);

			if ($customer_group_info) {
				$this->data['customer_group'] = $customer_group_info['name'];
			} else {
				$this->data['customer_group'] = '';
			}

			$this->data['email'] = $invoice_info['email'];
			$this->data['telephone'] = $invoice_info['telephone'];
			$this->data['fax'] = $invoice_info['fax'];
			$this->data['company'] = $invoice_info['company'];
			$this->data['date_added'] = $invoice_info['date_added'];
			$this->data['date_modified'] = $invoice_info['date_modified'];
			$this->data['comment'] = nl2br($invoice_info['comment']);
			$this->data['shipping_method'] = $invoice_info['shipping_method'];
			$this->data['payment_method'] = $invoice_info['payment_method'];
			$this->data['total'] = $this->currency->format($invoice_info['total'], $invoice_info['currency_code'], $invoice_info['currency_value']);
			
			if ($invoice_info['total'] < 0) {
				$this->data['credit'] = $invoice_info['total'];
			} else {
				$this->data['credit'] = 0;
			}
			
			$this->load->model('sale/customer');

			$this->load->model('localisation/invoice_status');

			$invoice_status_info = $this->model_localisation_invoice_status->getInvoiceStatus($invoice_info['invoice_status_id']);

			if ($invoice_status_info) {
				$this->data['invoice_status'] = $invoice_status_info['name'];
			} else {
				$this->data['invoice_status'] = '';
			}
			
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($invoice_info['date_added']));
			$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($invoice_info['date_modified']));		
			$this->data['payment_company'] = $invoice_info['payment_company'];
			$this->data['payment_company_id'] = $invoice_info['payment_company_id'];
			$this->data['payment_tax_id'] = $invoice_info['payment_tax_id'];
			$this->data['payment_address_1'] = $invoice_info['payment_address_1'];
			$this->data['payment_address_2'] = $invoice_info['payment_address_2'];
			$this->data['payment_city'] = $invoice_info['payment_city'];
			$this->data['payment_postcode'] = $invoice_info['payment_postcode'];
			$this->data['payment_zone'] = $invoice_info['payment_zone'];
			$this->data['payment_zone_code'] = $invoice_info['payment_zone_code'];
			$this->data['payment_country'] = $invoice_info['payment_country'];			
			$this->data['shipping_company'] = $invoice_info['shipping_company'];
			$this->data['shipping_address_1'] = $invoice_info['shipping_address_1'];
			$this->data['shipping_address_2'] = $invoice_info['shipping_address_2'];
			$this->data['shipping_city'] = $invoice_info['shipping_city'];
			$this->data['shipping_postcode'] = $invoice_info['shipping_postcode'];
			$this->data['shipping_zone'] = $invoice_info['shipping_zone'];
			$this->data['shipping_zone_code'] = $invoice_info['shipping_zone_code'];
			$this->data['shipping_country'] = $invoice_info['shipping_country'];

			$this->data['products'] = array();

			$products = $this->model_sale_invoice->getInvoiceProducts($this->request->get['invoice_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_invoice->getInvoiceOptions($this->request->get['invoice_id'], $product['invoice_product_id']);

				foreach ($options as $option) {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
						'type'  => $option['type']
					);
				}

				$this->data['products'][] = array(
					'invoice_product_id' => $product['invoice_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price']),
					'total'    		   => $this->currency->format($product['total']),
					'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}
		
			$this->data['totals'] = $this->model_sale_invoice->getInvoiceTotals($this->request->get['invoice_id']);
			
			$this->data['invoice_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();

			$this->data['invoice_status_id'] = $invoice_info['invoice_status_id'];

			$this->template = 'sale/invoice_info.tpl';
			
			$this->children = array(
				'common/header',
				'common/footer'
			);
			
			$this->response->setOutput($this->render());
		} else {
			$this->load->language('error/not_found');

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
    	$this->language->load('sale/invoice');
		
		$this->data['error'] = '';
		$this->data['success'] = '';
		
		$this->load->model('sale/invoice');
	
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/invoice')) { 
				$this->data['error'] = $this->language->get('error_permission');
			}
			
			if (!$this->data['error']) { 
				$this->model_sale_invoice->addInvoiceHistory($this->request->get['invoice_id'], $this->request->post);
				
				$this->data['success'] = $this->language->get('text_success');
			}
		}
				
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_notify'] = $this->language->get('column_notify');
		$this->data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  
		
		$this->data['histories'] = array();
			
		$results = $this->model_sale_invoice->getInvoiceHistories($this->request->get['invoice_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_invoice->getTotalInvoiceHistories($this->request->get['invoice_id']);
			
		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/invoice/history', 'token=' . $this->session->data['token'] . '&invoice_id=' . $this->request->get['invoice_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'sale/invoice_history.tpl';
		
		$this->response->setOutput($this->render());
  	}
			
  	public function invoice() {
		
		if (isset($this->request->get['format'])) {
			$lcFormat = $this->request->get['format'];
		} else {
			$lcFormat = '';
		}

		$this->data['lang'] = $this->config->get('config_language');
		$this->load->language('sale/invoice');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_invoice'] = $this->language->get('text_invoice');

		$this->data['text_invoice_id'] = $this->language->get('text_invoice_id');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_nif'] = $this->language->get('text_nif');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_company_id'] = $this->language->get('text_company_id');
		$this->data['text_tax_id'] = $this->language->get('text_tax_id');		
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_invoice_details'] = $this->language->get('text_invoice_details');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$this->load->model('sale/invoice');

		$this->load->model('setting/setting');

		// Custom header/footer content set in Sale > Invoice Design.
		$invoice_design = $this->model_setting_setting->getSetting('invoice_design');

		$this->data['header_html'] = isset($invoice_design['header_html']) ? $invoice_design['header_html'] : '';
		$this->data['footer_html'] = isset($invoice_design['footer_html']) ? $invoice_design['footer_html'] : '';

		$this->data['invoices'] = array();

		$invoices = array();

		if (isset($this->request->post['selected'])) {
			$invoices = $this->request->post['selected'];
		} elseif (isset($this->request->get['invoice_id'])) {
			$invoices[] = $this->request->get['invoice_id'];
		}

		$invoices = array_unique($invoices);
		
		// Add
        if (isset($this->request->post['report'])) {
			$lcReport = $this->request->post['report'];
		} elseif (isset($this->request->get['report'])) {
			$lcReport = $this->request->get['report'];
		} else {
			$lcReport = '';
		}
        // End add
	
		foreach ($invoices as $invoice_id) {
			$invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

			if ($invoice_info) {
				
				$store_info = $this->model_setting_setting->getSetting('config', $invoice_info['store_id']);
				
				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = isset($store_info['config_fax']) ? $store_info['config_fax'] : '';
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = (string)$this->config->get('config_fax');
				}
				
				//add
				$store_nif = $this->config->get('config_vat_id');

				if (!$store_nif) {
					$store_nif = $this->config->get('config_nif');
				}
				//end add
				
				if ($invoice_info['invoice_no']) {
					$invoice_no = $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				if ($invoice_info['shipping_address_format']) {
					$format = $invoice_info['shipping_address_format'];
				} else {
					$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'company'   => $invoice_info['shipping_company'],
					'address_1' => $invoice_info['shipping_address_1'],
					'address_2' => $invoice_info['shipping_address_2'],
					'city'      => $invoice_info['shipping_city'],
					'postcode'  => $invoice_info['shipping_postcode'],
					'zone'      => $invoice_info['shipping_zone'],
					'zone_code' => $invoice_info['shipping_zone_code'],
					'country'   => $invoice_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($invoice_info['payment_address_format']) {
					$format = $invoice_info['payment_address_format'];
				} else {
					$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'company'   => $invoice_info['payment_company'],
					'address_1' => $invoice_info['payment_address_1'],
					'address_2' => $invoice_info['payment_address_2'],
					'city'      => $invoice_info['payment_city'],
					'postcode'  => $invoice_info['payment_postcode'],
					'zone'      => $invoice_info['payment_zone'],
					'zone_code' => $invoice_info['payment_zone_code'],
					'country'   => $invoice_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$product_data = array();

				$products = $this->model_sale_invoice->getInvoiceProducts($invoice_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_invoice->getInvoiceOptions($invoice_id, $product['invoice_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);								
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'image'    => ($product['image']=='' ? 'no_image.jpg' : $product['image']),
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price']),
						'total'    => $this->currency->format($product['total'])
					);
				}
				
				$total_data = $this->model_sale_invoice->getInvoiceTotals($invoice_id);

				//add - VeriFactu QR code (Real Decreto 1007/2023, arts. 20-21)
				$qr_code = '';
				$qr_code_pdf = '';
				$qr_verifiable = defined('VERIFACTU_ONLINE_MODE') ? VERIFACTU_ONLINE_MODE : false;
				$qr_numserie = $invoice_no ? $invoice_no : ($invoice_info['invoice_prefix'] . $invoice_id);

				if ($store_nif && $qr_numserie) {
					require_once(DIR_SYSTEM . 'library/verifactu.php');
					require_once(DIR_SYSTEM . 'external/tcpdf/tcpdf_barcodes_2d.php');

					$verifactu = new Verifactu($store_nif, $invoice_info['store_name'], array());
					$verifactu->setProduction(defined('VERIFACTU_PRODUCTION') ? VERIFACTU_PRODUCTION : false);

					$qr_url = $verifactu->getQrUrl($store_nif, $qr_numserie, $invoice_info['date_added'], number_format((float)$invoice_info['total'], 2, '.', ''), $qr_verifiable);

					$qr_barcode = new TCPDF2DBarcode($qr_url, 'QRCODE,M');
					$qr_png = $qr_barcode->getBarcodePngData(6, 6, array(0, 0, 0));

					if ($qr_png !== false) {
						$qr_code = 'data:image/png;base64,' . base64_encode($qr_png);

						// TCPDF's writeHTML() cannot render base64 data-uri images, so the PDF needs a real file
						$qr_code_pdf = DIR_CACHE . 'qr_invoice_' . $invoice_id . '.png';
						file_put_contents($qr_code_pdf, $qr_png);
					}
				}
				//end add

				$this->data['invoices'][] = array(
					'invoice_id'	         => $invoice_id,
					'invoice_no'         => $invoice_no,
					'invoice_prefix'     => $invoice_info['invoice_prefix'],
					'date_added'         => date($this->language->get('date_format_short'), strtotime($invoice_info['date_added'])),
					'store_name'         => $invoice_info['store_name'],
					'store_url'          => rtrim($invoice_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'store_nif'          => $store_nif,
					'email'              => $invoice_info['email'],
					'vat_id'             => '',
					'name_ext' 			 => '',
					'telephone'          => $invoice_info['telephone'],
					'shipping_address'   => $shipping_address,
					'payment_address'    => $payment_address,
					'payment_company_id' => $invoice_info['payment_company_id'],
					'payment_tax_id'     => $invoice_info['payment_tax_id'],
					'payment_address'    => $payment_address,
					'payment_method'     => $invoice_info['payment_method'],
					'shipping_method'    => $invoice_info['shipping_method'],
					'product'            => $product_data,
					'total'              => $total_data,
					'comment'            => nl2br($invoice_info['comment']),
					'qr_code'            => $qr_code,
					'qr_code_pdf'        => $qr_code_pdf,
					'qr_verifiable'      => $qr_verifiable
				);
			}
		}

		$this->data['logo'] = $this->config->get('config_logo');

		if ($lcFormat=='pdf') {
			$this->renderPDF('sale/invoice_printPDF.tpl', 'pdf', 'invoice', $invoice_id);
		} elseif ($lcFormat=='email') {
			$this->renderPDF('sale/invoice_printPDF.tpl', 'email', 'invoice', $invoice_id);

			$json = array();

			if ($this->request->post['to']=='' || filter_var($this->request->post['to'], FILTER_VALIDATE_EMAIL)==false) {
				$json['error']['to'] = $this->language->get('error_to');
			} 
	
			if ($this->request->post['subject']=='') {
				$json['error']['subject'] = $this->language->get('error_subject');
			}
	
			if ($this->request->post['message']=='') {
				$json['error']['message'] = $this->language->get('error_message');
			} 

			if (empty($json['error'])) {
				$data['customer_id'] = 0;
				$data['potential_id'] = 0;
				$data['supplier_id'] = 0;
				
				$data['invoice_id'] = $this->request->get['invoice_id'];
				
				$data['to'] = $this->request->post['to'];
				$data['subject'] = $this->request->post['subject'];
				$data['text'] = $this->request->post['message'];
				$data['code'] = md5($this->request->post['message']);
				
				$data['file'] = DIR_DOWNLOAD . 'invoice_' . $invoice_id . '.pdf';
				
				$this->sendnewmail($data['to'], $data['subject'], $data['text'], $data['file']);
				
				$this->load->model('catalog/mail');
				
				$this->model_catalog_mail->addMailSended($data);
				
				$json['success'] = $this->language->get('text_success_email');
			}

			$this->response->setOutput(json_encode($json));
		} else {
			if ($lcReport=='') {
				$this->template = 'sale/invoice_invoice.tpl';
			} else {
				$this->template = 'sale/reports/' . $lcReport;
			}
			
			$this->response->setOutput($this->render());
		}
	}

	public function checkFacturae() {
		$this->load->language('sale/invoice');

		$json = array();

		if (!$this->config->get('config_vat_id')) {
			$json['error'] = $this->language->get('error_facturae_vat_id');
		} elseif (!$this->config->get('certificado') || !$this->config->get('clave')) {
			$json['error'] = $this->language->get('error_facturae_certificate');
		} else {
			$json['success'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function facturae() {
		$this->load->language('sale/invoice');

		if (!$this->config->get('config_vat_id')) {
			$this->response->setOutput($this->language->get('error_facturae_vat_id'));
			return;
		}

		if (!$this->config->get('certificado') || !$this->config->get('clave')) {
			$this->response->setOutput($this->language->get('error_facturae_certificate'));
			return;
		}

		$this->load->model('sale/invoice');

		$invoice_id = isset($this->request->get['invoice_id']) ? (int)$this->request->get['invoice_id'] : 0;

		$invoice_info = $this->model_sale_invoice->getInvoice($invoice_id);

		if (!$invoice_info) {
			exit();
		}

		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$this->load->model('localisation/tax_rate');

		$products = $this->model_sale_invoice->getInvoiceProducts($invoice_id);
		$totals = $this->model_sale_invoice->getInvoiceTotals($invoice_id);

		// Seller (issuer) party, taken from the store settings.
		$seller_address = (string)$this->config->get('config_address');
		$seller_postcode = '';
		$seller_town = '';

		if (preg_match('/(\d{5})\s*[\,\-]?\s*(.*)$/m', $seller_address, $matches)) {
			$seller_postcode = $matches[1];
			$seller_town = trim($matches[2]);
			$seller_address = trim(str_replace($matches[0], '', $seller_address));
		}

		$seller_country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
		$seller_zone = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));

		$seller_nif = $this->config->get('config_vat_id');

		if (!$seller_nif) {
			$seller_nif = $this->config->get('config_nif');
		}

		$seller = array(
			'name'     => $this->config->get('config_name'),
			'nif'      => preg_replace('/[^A-Za-z0-9]/', '', (string)$seller_nif),
			'address'  => $seller_address,
			'postcode' => $seller_postcode,
			'town'     => $seller_town,
			'province' => $seller_zone ? $seller_zone['name'] : '',
			'country'  => $seller_country && $seller_country['iso_code_3'] ? $seller_country['iso_code_3'] : 'ESP'
		);

		// Buyer party, taken from the billing details stored on the invoice.
		$buyer_name = $invoice_info['payment_company'] ? $invoice_info['payment_company'] : $invoice_info['company'];
		$buyer_nif = $invoice_info['payment_tax_id'] ? $invoice_info['payment_tax_id'] : $invoice_info['payment_company_id'];

		$buyer = array(
			'name'        => $buyer_name,
			'nif'         => preg_replace('/[^A-Za-z0-9]/', '', (string)$buyer_nif),
			'address'     => trim($invoice_info['payment_address_1'] . ' ' . $invoice_info['payment_address_2']),
			'postcode'    => $invoice_info['payment_postcode'],
			'town'        => $invoice_info['payment_city'],
			'province'    => $invoice_info['payment_zone'],
			'country'     => $invoice_info['payment_iso_code_3'] ? $invoice_info['payment_iso_code_3'] : 'ESP',
			'person_type' => $buyer_name ? 'J' : 'F'
		);

		// Tax breakdown: the taxable base is derived from the tax amount and
		// rate so it stays correct even when an invoice mixes several VAT rates.
		$tax_rate_lookup = array();

		foreach ($this->model_localisation_tax_rate->getTaxRates() as $tax_rate) {
			$tax_rate_lookup[$tax_rate['name']] = $tax_rate;
		}

		$sub_total = 0;
		$tax_total = 0;
		$taxes = array();

		foreach ($totals as $total) {
			if ($total['code'] == 'sub_total') {
				$sub_total = (float)$total['value'];
			}

			if ($total['code'] == 'tax') {
				$rate = 0;

				if (isset($tax_rate_lookup[$total['title']]) && $tax_rate_lookup[$total['title']]['type'] == 'P') {
					$rate = (float)$tax_rate_lookup[$total['title']]['rate'];
				}

				$amount = (float)$total['value'];

				$taxes[] = array(
					'rate'   => $rate,
					'base'   => $rate > 0 ? round($amount / ($rate / 100), 2) : $sub_total,
					'amount' => $amount
				);

				$tax_total += $amount;
			}
		}

		if (!$taxes) {
			$taxes[] = array(
				'rate'   => 0,
				'base'   => $sub_total,
				'amount' => 0
			);
		}

		$lines = array();

		foreach ($products as $product) {
			$line_total = (float)$product['total'];
			$line_tax = (float)$product['tax'] * (float)$product['quantity'];
			$line_rate = $line_total > 0 ? round(($line_tax / $line_total) * 100, 2) : 0;

			$lines[] = array(
				'name'     => $product['name'],
				'quantity' => (float)$product['quantity'],
				'price'    => (float)$product['price'],
				'total'    => $line_total,
				'tax_rate' => $line_rate,
				'tax'      => $line_tax,
				'model'    => $product['model']
			);
		}

		$invoice_no = $invoice_info['invoice_no'] ? $invoice_info['invoice_no'] : $invoice_info['invoice_id'];

		$xml = $this->buildFacturaeXml(array(
			'invoice_no'     => $invoice_no,
			'invoice_series' => $invoice_info['invoice_prefix'],
			'date_added'     => $invoice_info['date_added'],
			'date_due'       => $invoice_info['date_due'],
			'seller'         => $seller,
			'buyer'          => $buyer,
			'lines'          => $lines,
			'taxes'          => $taxes,
			'sub_total'      => $sub_total,
			'tax_total'      => $tax_total,
			'total'          => (float)$invoice_info['total']
		));

		$this->response->addHeader('Content-Type: application/xml; charset=UTF-8');
		$this->response->addHeader('Content-Disposition: attachment; filename="facturae_' . $invoice_info['invoice_prefix'] . $invoice_no . '.xml"');
		$this->response->setOutput($xml);
	}

	protected function buildFacturaeXml($data) {
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;

		$root = $doc->createElementNS('http://www.facturae.es/Facturae/2014/v3.2.1/Facturae', 'fe:Facturae');
		$root->setAttribute('xmlns', 'http://uri.etsi.org/01903/v1.3.2#');
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
		$doc->appendChild($root);

		$issue_date = date('Y-m-d', strtotime($data['date_added']));
		$end_date = (!empty($data['date_due']) && strtotime($data['date_due']) > strtotime($issue_date)) ? date('Y-m-d', strtotime($data['date_due'])) : $issue_date;

		// FileHeader
		$fileHeader = $doc->createElement('FileHeader');
		$fileHeader->setAttribute('xmlns', '');
		$root->appendChild($fileHeader);

		$fileHeader->appendChild($doc->createElement('SchemaVersion', '3.2.1'));
		$fileHeader->appendChild($doc->createElement('Modality', 'I'));
		$fileHeader->appendChild($doc->createElement('InvoiceIssuerType', 'EM'));

		$batch = $doc->createElement('Batch');
		$fileHeader->appendChild($batch);

		$batch->appendChild($doc->createElement('BatchIdentifier', $data['invoice_no'] . $data['invoice_series']));
		$batch->appendChild($doc->createElement('InvoicesCount', '1'));

		$totalInvoicesAmount = $doc->createElement('TotalInvoicesAmount');
		$totalInvoicesAmount->appendChild($doc->createElement('TotalAmount', $this->facturaeAmount($data['total'])));
		$batch->appendChild($totalInvoicesAmount);

		$totalOutstandingAmount = $doc->createElement('TotalOutstandingAmount');
		$totalOutstandingAmount->appendChild($doc->createElement('TotalAmount', $this->facturaeAmount($data['total'])));
		$batch->appendChild($totalOutstandingAmount);

		$totalExecutableAmount = $doc->createElement('TotalExecutableAmount');
		$totalExecutableAmount->appendChild($doc->createElement('TotalAmount', $this->facturaeAmount($data['total'])));
		$batch->appendChild($totalExecutableAmount);

		$batch->appendChild($doc->createElement('InvoiceCurrencyCode', 'EUR'));

		// Parties
		$parties = $doc->createElement('Parties');
		$parties->setAttribute('xmlns', '');
		$root->appendChild($parties);

		$parties->appendChild($this->buildFacturaePartyXml($doc, 'SellerParty', $data['seller'], 'J'));
		$parties->appendChild($this->buildFacturaePartyXml($doc, 'BuyerParty', $data['buyer'], $data['buyer']['person_type']));

		// Invoices
		$invoicesEl = $doc->createElement('Invoices');
		$invoicesEl->setAttribute('xmlns', '');
		$root->appendChild($invoicesEl);

		$invoiceEl = $doc->createElement('Invoice');
		$invoicesEl->appendChild($invoiceEl);

		$invoiceHeader = $doc->createElement('InvoiceHeader');
		$invoiceEl->appendChild($invoiceHeader);
		$invoiceHeader->appendChild($doc->createElement('InvoiceNumber', $data['invoice_no']));

		if ($data['invoice_series']) {
			$invoiceHeader->appendChild($doc->createElement('InvoiceSeriesCode', $data['invoice_series']));
		}

		$invoiceHeader->appendChild($doc->createElement('InvoiceDocumentType', 'FC'));
		$invoiceHeader->appendChild($doc->createElement('InvoiceClass', 'OO'));

		$invoiceIssueData = $doc->createElement('InvoiceIssueData');
		$invoiceEl->appendChild($invoiceIssueData);
		$invoiceIssueData->appendChild($doc->createElement('IssueDate', $issue_date));

		$placeOfIssue = $doc->createElement('PlaceOfIssue');
		$invoiceIssueData->appendChild($placeOfIssue);
		$placeOfIssue->appendChild($doc->createElement('PostCode', $data['seller']['postcode'] ? $data['seller']['postcode'] : '00000'));
		$placeOfIssue->appendChild($doc->createElement('PlaceOfIssueDescription'));

		$invoicingPeriod = $doc->createElement('InvoicingPeriod');
		$invoiceIssueData->appendChild($invoicingPeriod);
		$invoicingPeriod->appendChild($doc->createElement('StartDate', $issue_date));
		$invoicingPeriod->appendChild($doc->createElement('EndDate', $end_date));

		$invoiceIssueData->appendChild($doc->createElement('InvoiceCurrencyCode', 'EUR'));

		$exchangeRateDetails = $doc->createElement('ExchangeRateDetails');
		$invoiceIssueData->appendChild($exchangeRateDetails);
		$exchangeRateDetails->appendChild($doc->createElement('ExchangeRate', '1'));
		$exchangeRateDetails->appendChild($doc->createElement('ExchangeRateDate', $issue_date));

		$invoiceIssueData->appendChild($doc->createElement('TaxCurrencyCode', 'EUR'));
		$invoiceIssueData->appendChild($doc->createElement('LanguageName', 'es'));

		$taxesOutputs = $doc->createElement('TaxesOutputs');
		$invoiceEl->appendChild($taxesOutputs);

		foreach ($data['taxes'] as $tax) {
			$taxesOutputs->appendChild($this->buildFacturaeTaxXml($doc, $tax));
		}

		$invoiceTotals = $doc->createElement('InvoiceTotals');
		$invoiceEl->appendChild($invoiceTotals);
		$invoiceTotals->appendChild($doc->createElement('TotalGrossAmount', $this->facturaeAmount($data['sub_total'])));
		$invoiceTotals->appendChild($doc->createElement('TotalGrossAmountBeforeTaxes', $this->facturaeAmount($data['sub_total'])));
		$invoiceTotals->appendChild($doc->createElement('TotalTaxOutputs', $this->facturaeAmount($data['tax_total'])));
		$invoiceTotals->appendChild($doc->createElement('TotalTaxesWithheld', $this->facturaeAmount(0)));
		$invoiceTotals->appendChild($doc->createElement('InvoiceTotal', $this->facturaeAmount($data['total'])));
		$invoiceTotals->appendChild($doc->createElement('TotalFinancialExpenses', '0.00'));
		$invoiceTotals->appendChild($doc->createElement('TotalOutstandingAmount', $this->facturaeAmount($data['total'])));
		$invoiceTotals->appendChild($doc->createElement('TotalExecutableAmount', $this->facturaeAmount($data['total'])));

		$items = $doc->createElement('Items');
		$invoiceEl->appendChild($items);

		foreach ($data['lines'] as $line) {
			$lineEl = $doc->createElement('InvoiceLine');
			$items->appendChild($lineEl);
			$lineEl->appendChild($doc->createElement('ReceiverTransactionReference'));
			$lineEl->appendChild($doc->createElement('ItemDescription', $line['name']));
			$lineEl->appendChild($doc->createElement('Quantity', $this->facturaeAmount($line['quantity'])));
			$lineEl->appendChild($doc->createElement('UnitPriceWithoutTax', $this->facturaeAmount($line['price'])));
			$lineEl->appendChild($doc->createElement('TotalCost', $this->facturaeAmount($line['total'])));

			$discountsAndRebates = $doc->createElement('DiscountsAndRebates');
			$lineEl->appendChild($discountsAndRebates);
			$discount = $doc->createElement('Discount');
			$discountsAndRebates->appendChild($discount);
			$discount->appendChild($doc->createElement('DiscountReason', 'Descuento'));
			$discount->appendChild($doc->createElement('DiscountAmount', $this->facturaeAmount(0)));

			$lineEl->appendChild($doc->createElement('GrossAmount', $this->facturaeAmount($line['total'])));

			$lineTaxesOutputs = $doc->createElement('TaxesOutputs');
			$lineEl->appendChild($lineTaxesOutputs);
			$lineTaxesOutputs->appendChild($this->buildFacturaeTaxXml($doc, array(
				'rate'   => $line['tax_rate'],
				'base'   => $line['total'],
				'amount' => $line['tax']
			)));

			if ($line['model']) {
				$lineEl->appendChild($doc->createElement('ArticleCode', str_pad($line['model'], 15, '0', STR_PAD_LEFT)));
			}
		}

		return $doc->saveXML();
	}

	protected function buildFacturaeTaxXml($doc, $tax) {
		$taxEl = $doc->createElement('Tax');
		$taxEl->appendChild($doc->createElement('TaxTypeCode', '01'));
		$taxEl->appendChild($doc->createElement('TaxRate', $this->facturaeAmount($tax['rate'])));

		$taxableBase = $doc->createElement('TaxableBase');
		$taxableBase->appendChild($doc->createElement('TotalAmount', $this->facturaeAmount($tax['base'])));
		$taxEl->appendChild($taxableBase);

		$taxAmount = $doc->createElement('TaxAmount');
		$taxAmount->appendChild($doc->createElement('TotalAmount', $this->facturaeAmount($tax['amount'])));
		$taxEl->appendChild($taxAmount);

		return $taxEl;
	}

	protected function buildFacturaePartyXml($doc, $tagName, $party, $personType) {
		$partyEl = $doc->createElement($tagName);

		$isSpain = ($party['country'] == 'ESP');

		$taxIdentification = $doc->createElement('TaxIdentification');
		$partyEl->appendChild($taxIdentification);
		$taxIdentification->appendChild($doc->createElement('PersonTypeCode', $personType));
		$taxIdentification->appendChild($doc->createElement('ResidenceTypeCode', $isSpain ? 'R' : 'U'));
		$taxIdentification->appendChild($doc->createElement('TaxIdentificationNumber', $party['nif']));

		$partyEl->appendChild($doc->createElement('PartyIdentification'));

		$legalEntity = $doc->createElement($personType == 'F' ? 'Individual' : 'LegalEntity');
		$partyEl->appendChild($legalEntity);

		if ($personType == 'F') {
			$nameParts = explode(' ', trim($party['name']), 2);
			$legalEntity->appendChild($doc->createElement('Name', $nameParts[0]));
			$legalEntity->appendChild($doc->createElement('FirstSurname', isset($nameParts[1]) ? $nameParts[1] : ''));
		} else {
			$legalEntity->appendChild($doc->createElement('CorporateName', $party['name']));
		}

		$addressEl = $doc->createElement($isSpain ? 'AddressInSpain' : 'OverseasAddress');
		$legalEntity->appendChild($addressEl);
		$addressEl->appendChild($doc->createElement('Address', $party['address']));
		$addressEl->appendChild($doc->createElement('PostCode', $party['postcode']));
		$addressEl->appendChild($doc->createElement('Town', $party['town']));
		$addressEl->appendChild($doc->createElement('Province', $party['province']));
		$addressEl->appendChild($doc->createElement('CountryCode', $party['country']));

		return $partyEl;
	}

	protected function facturaeAmount($value) {
		$value = round((float)$value, 2);

		if (fmod($value, 1) == 0) {
			return number_format($value, 0, '.', '');
		}

		return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
	}

	public function checkInvoice() {
		$this->load->language('sale/invoice');

		$json = array();

		if ($this->user->hasPermission('modify', 'sale/invoice')) {

			// Reset everything
			unset($this->session->data['cart']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['store_address']);
			unset($this->session->data['customer_id']);
			
			// Models
			$this->load->model('setting/setting');
			$this->load->model('setting/extension');
			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');
			$this->load->model('sale/customer');
			$this->load->model('catalog/product');

			$this->session->data['cart'] = array();

			$settings = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);

			foreach ($settings as $key => $value) {
				$this->config->set($key, $value);
			}

			// Customer
			if ($this->request->post['customer_id']) {
				$this->session->data['customer_id'] = $this->request->post['customer_id'];
				$customer_info = $this->model_sale_customer->getCustomer($this->request->post['customer_id']);
			} else {
				// Customer Group
				$this->config->set('config_customer_group_id', $this->request->post['customer_group_id']);
				$this->session->data['customer_id'] = 0;
				$customer_info = array();
			}

			// Product
			if (isset($this->request->post['invoice_product'])) {
				foreach ($this->request->post['invoice_product'] as $invoice_product) {
					$product_info = $this->model_catalog_product->getProduct($invoice_product['product_id']);
					$option_data = array();

					if (isset($invoice_product['invoice_option'])) {
						foreach ($invoice_product['invoice_option'] as $option) {
							if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { 
								$option_data[$option['product_option_id']] = $option['product_option_value_id'];
							} elseif ($option['type'] == 'checkbox') {
								$option_data[$option['product_option_id']][] = $option['product_option_value_id'];
							} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
								$option_data[$option['product_option_id']] = $option['value'];						
							}
						}
					}

					if ($product_info) {	
						$this->session->data['cart'][] = array(
							'product_id' => $product_info['product_id'],
							'name'		 => $product_info['name'], 
							'model'		 => $product_info['model'], 
							'quantity' 	 => $invoice_product['quantity'], 
							'option'	 => $option_data,
							'price'		 => $product_info['price'], 
							'tax_class_id'=> $product_info['tax_class_id'],
							'total'		 => ($product_info['price']*$invoice_product['quantity']),
							'shipping'	 => $product_info['shipping']
						);
					}
				}
			}

			if (isset($this->request->post['product_id']) && $this->request->post['product_id'] != 0) {
				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

				if ($product_info) {
					if (isset($this->request->post['quantity'])) {
						$quantity = $this->request->post['quantity'];
					} else {
						$quantity = 1;
					}

					if (isset($this->request->post['option'])) {
						$option = $this->request->post['option'];
					} else {
						$option = array();
					}

					$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);

					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['product']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}

					if (!isset($json['error']['product']['option'])) {
						$this->session->data['cart'][] = array(
							'product_id' 	=> $this->request->post['product_id'],
							'name'		 	=> $product_info['name'],
							'model'		 	=> $product_info['model'],
							'quantity' 	 	=> $quantity,
							'option' 	 	=> $option,
							'price'		 	=> $product_info['price'],
							'tax_class_id'	=> $product_info['tax_class_id'],
							'total'		 	=> ($product_info['price']*$quantity),
							'shipping'	 	=> $product_info['shipping']
						);

					}
				} else {
					$json['error']['product']['not_found'] = $this->language->get('error_action');
				}
			}

			// Products
			$json['invoice_product'] = array();
			
			$products = $this->session->data['cart'];

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				$option_data = $this->model_catalog_product->getProductOptions($product['product_id']);
				$option = array();
				$i=0;
				foreach ($product['option'] as $option_id => $value) {
					$option[] = array(
						'product_option_id'			=> $option_id, 
						'name'						=> $option_data[$i]['name'], 
						'value'						=> $value, 
						'type'						=> $option_data[$i]['type']
					);
					$i++;
				}
				
				$json['invoice_product'][] = array(
					'product_id' 	=> $product['product_id'],
					'name'       	=> $product['name'],
					'model'      	=> $product['model'], 
					'quantity'   	=> $product['quantity'],
					'option'   		=> $option,
					'price'      	=> $this->currency->format($product['price']),	
					'tax_class_id'	=> $product['tax_class_id'], 
					'total'      	=> $this->currency->format($product['total'])
				);
			}

			// Totals
			$json['invoice_total'] = array();					
			$total = 0;
			$taxes = $this->getTaxes($products);

			$sort_order = array(); 

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($json['invoice_total'], $total, $taxes);
				}

				$sort_order = array(); 

				foreach ($json['invoice_total'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['invoice_total']);				
			}

			if (!isset($json['error'])) { 
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error']['warning'] = $this->language->get('error_warning');
			}
			
		}
		
		// Reset everything
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['shipping_address']);
		unset($this->session->data['payment_address']);
		unset($this->session->data['store_address']);
		unset($this->session->data['customer_id']);


		$this->response->setOutput(json_encode($json));
	}

	public function getTaxes($data) {
		$this->load->model('catalog/product');
		
		$tax_data = array();

		foreach ($data as $product) {
			if ($product['tax_class_id']!=0) {
				$tax_rates = $this->model_catalog_product->getProductRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}
	
	public function validateEdit() {
		if (!$this->user->hasPermission('modify', 'sale/invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
	  	}
	  
		$this->load->model('sale/invoice');

		$paid_receipts = $this->model_sale_invoice->getPaidReceipts($this->request->get['invoice_id']);
		
		if ($paid_receipts != 0) {
			$this->error['paid_out'] = $this->language->get('error_paid_out');
		} 

		if (!$this->error) {
			return true;
		} else {
			return false;
	 	}
	}
	
}
?>