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
		
		$this->getForm();
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
		
    	$this->getForm();
  	}
	
  	public function delete() {
		$this->load->language('sale/invoice');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/invoice');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $invoice_id) {
				$this->model_sale_invoice->deleteInvoice($invoice_id);
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
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/invoice/info', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL')
			);
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/invoice/update', 'token=' . $this->session->data['token'] . '&invoice_id=' . $result['invoice_id'] . $url, 'SSL')
			);
			
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
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
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
		
 		if (isset($this->error['company'])) {
			$this->data['error_customer'] = $this->error['company'];
		} else {
			$this->data['error_customer'] = '';
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
						
 		if (isset($this->error['payment_firstname'])) {
			$this->data['error_payment_firstname'] = $this->error['payment_firstname'];
		} else {
			$this->data['error_payment_firstname'] = '';
		}

 		if (isset($this->error['payment_lastname'])) {
			$this->data['error_payment_lastname'] = $this->error['payment_lastname'];
		} else {
			$this->data['error_payment_lastname'] = '';
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

 		if (isset($this->error['shipping_firstname'])) {
			$this->data['error_shipping_firstname'] = $this->error['shipping_firstname'];
		} else {
			$this->data['error_shipping_firstname'] = '';
		}

 		if (isset($this->error['shipping_lastname'])) {
			$this->data['error_shipping_lastname'] = $this->error['shipping_lastname'];
		} else {
			$this->data['error_shipping_lastname'] = '';
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
		
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($invoice_info)) { 
			$this->data['firstname'] = $invoice_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['lastname'] = $invoice_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
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
			
    	if (isset($this->request->post['payment_firstname'])) {
      		$this->data['payment_firstname'] = $this->request->post['payment_firstname'];
		} elseif (!empty($invoice_info)) { 
			$this->data['payment_firstname'] = $invoice_info['payment_firstname'];
		} else {
      		$this->data['payment_firstname'] = '';
    	}

    	if (isset($this->request->post['payment_lastname'])) {
      		$this->data['payment_lastname'] = $this->request->post['payment_lastname'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['payment_lastname'] = $invoice_info['payment_lastname'];
		} else {
      		$this->data['payment_lastname'] = '';
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
			
    	if (isset($this->request->post['shipping_firstname'])) {
      		$this->data['shipping_firstname'] = $this->request->post['shipping_firstname'];
		} elseif (!empty($invoice_info)) { 
			$this->data['shipping_firstname'] = $invoice_info['shipping_firstname'];
		} else {
      		$this->data['shipping_firstname'] = '';
    	}

    	if (isset($this->request->post['shipping_lastname'])) {
      		$this->data['shipping_lastname'] = $this->request->post['shipping_lastname'];
    	} elseif (!empty($invoice_info)) { 
			$this->data['shipping_lastname'] = $invoice_info['shipping_lastname'];
		} else {
      		$this->data['shipping_lastname'] = '';
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
		
		$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
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
			$this->data['text_firstname'] = $this->language->get('text_firstname');
			$this->data['text_lastname'] = $this->language->get('text_lastname');
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
			$this->data['invoice'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'] . '&format=view', 'SSL');
			$this->data['sendEmail'] = $this->url->link('sale/invoice/invoice', 'token=' . $this->session->data['token'] . '&invoice_id=' . (int)$this->request->get['invoice_id'] . '&format=email', 'SSL');
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
			$this->data['firstname'] = $invoice_info['firstname'];
			$this->data['lastname'] = $invoice_info['lastname'];
						
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
			$this->data['payment_firstname'] = $invoice_info['payment_firstname'];
			$this->data['payment_lastname'] = $invoice_info['payment_lastname'];
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
			$this->data['shipping_firstname'] = $invoice_info['shipping_firstname'];
			$this->data['shipping_lastname'] = $invoice_info['shipping_lastname'];
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
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				
				//add
				$store_nif = $this->config->get('config_nif');
				//end add
				
				if ($invoice_info['invoice_no']) {
					$invoice_no = $invoice_info['invoice_prefix'] . $invoice_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				if ($invoice_info['shipping_address_format']) {
					$format = $invoice_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
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
					'firstname' => $invoice_info['shipping_firstname'],
					'lastname'  => $invoice_info['shipping_lastname'],
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
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
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
					'firstname' => $invoice_info['payment_firstname'],
					'lastname'  => $invoice_info['payment_lastname'],
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
					'comment'            => nl2br($invoice_info['comment'])
				);
			}
		}

		$this->data['logo'] = $this->config->get('config_logo');

		if ($lcFormat=='pdf') {
			$this->renderPDF('sale/invoice_printPDF.tpl', 'pdf', 'invoice', $invoice_id);
		} elseif ($lcFormat=='email') {
			$this->renderPDF('sale/invoice_printPDF.tpl', 'email', 'invoice', $invoice_id);
			$to = $this->request->post['to'];
			$subject = $this->request->post['subject'];
			$text = $this->request->post['message'];

			$lcFile = DIR_DOWNLOAD . 'invoice_' . $invoice_id . '.pdf';
			$this->sendnewmail($to, $subject, $text, $lcFile);

			$this->redirect($this->url->link('sale/invoice/info', 'token=' . $this->session->data['token'] .'&invoice_id=' . $invoice_id, 'SSL'));
		} else {
			if ($lcReport=='') {
				$this->template = 'sale/invoice_invoice.tpl';
			} else {
				$this->template = 'sale/reports/' . $lcReport;
			}
			
			$this->response->setOutput($this->render());
		}
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

			if (isset($this->request->post['product_id'])) {
				$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

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
}
?>