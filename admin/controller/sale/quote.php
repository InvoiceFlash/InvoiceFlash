<?php
class ControllerSaleQuote extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/quote');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quote');

    	$this->getList();
  	}
	
  	public function insert() {
		$this->load->language('sale/quote');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quote');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->model_sale_quote->addQuote($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';
			
			if (isset($this->request->get['filter_quote_id'])) {
				$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_quote_status_id'])) {
				$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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
			
			$this->redirect($this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
    	$this->getForm();
  	}
	
  	public function update() {
		$this->load->language('sale/quote');
;
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quote');
    	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_quote->editQuote($this->request->get['quote_id'], $this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_quote_id'])) {
				$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_quote_status_id'])) {
				$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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
			
			$this->redirect($this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
    	$this->getForm();
  	}
	
  	public function delete() {
		$this->load->language('sale/quote');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quote');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $quote_id) {
				$this->model_sale_quote->deleteQuote($quote_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_quote_id'])) {
				$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_quote_status_id'])) {
				$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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

			$this->redirect($this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['filter_quote_id'])) {
			$filter_quote_id = $this->request->get['filter_quote_id'];
		} else {
			$filter_quote_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_quote_status_id'])) {
			$filter_quote_status_id = $this->request->get['filter_quote_status_id'];
		} else {
			$filter_quote_status_id = null;
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
			$sort = 'o.quote_id';
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

		if (isset($this->request->get['filter_quote_id'])) {
			$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_quote_status_id'])) {
			$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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
			'href'      => $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['invoice'] = $this->url->link('sale/quote/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('sale/quote/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('sale/quote/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['quotes'] = array();

		$data = array(
			'filter_quote_id'        => $filter_quote_id,
			'filter_customer'	     => $filter_customer,
			'filter_quote_status_id' => $filter_quote_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$quote_total = $this->model_sale_quote->getTotalQuotes($data);

		$results = $this->model_sale_quote->getQuotes($data);

    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/quote/info', 'token=' . $this->session->data['token'] . '&quote_id=' . $result['quote_id'] . $url, 'SSL'),
				'icon' => 'info-circle',
				'button' => 'primary'
			);
			
			if (strtotime($result['date_added']) > strtotime('-' . (int)$this->config->get('config_order_edit') . ' day')) {
				$action[] = array(
					'text' => $this->language->get('text_edit'),
					'href' => $this->url->link('sale/quote/update', 'token=' . $this->session->data['token'] . '&quote_id=' . $result['quote_id'] . $url, 'SSL'),
					'icon' => 'edit',
					'button' => 'dark'
				);
			}
			
			$this->data['quotes'][] = array(
				'quote_id'      => $result['quote_id'],
				'customer'      => $result['customer'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['quote_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['column_quote_id'] = $this->language->get('column_quote_id');
    	$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_quote'] = $this->language->get('button_quote');
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

		if (isset($this->request->get['filter_quote_id'])) {
			$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_quote_status_id'])) {
			$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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

		$this->data['sort_order'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . '&sort=o.quote_id' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_quote_id'])) {
			$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_quote_status_id'])) {
			$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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
		$pagination->total = $quote_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_quote_id'] = $filter_quote_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_quote_status_id'] = $filter_quote_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

    	$this->data['quote_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/quote_list.tpl';

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
		// $this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_quote'] = $this->language->get('text_quote');
		
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_quote_status'] = $this->language->get('entry_quote_status');
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
		$this->data['entry_to_name'] = $this->language->get('entry_to_name');
		$this->data['entry_to_email'] = $this->language->get('entry_to_email');
		$this->data['entry_from_name'] = $this->language->get('entry_from_name');
		$this->data['entry_from_email'] = $this->language->get('entry_from_email');
		$this->data['entry_theme'] = $this->language->get('entry_theme');	
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
			
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		// $this->data['button_add_voucher'] = $this->language->get('button_add_voucher');
		$this->data['button_update_total'] = $this->language->get('button_update_total');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_upload'] = $this->language->get('button_upload');
		//$this->data['button_quote'] = $this->language->get('button_quote');

		$this->data['tab_quote'] = $this->language->get('tab_quote');
		$this->data['tab_customer'] = $this->language->get('tab_customer');
		$this->data['tab_payment'] = $this->language->get('tab_payment');
		$this->data['tab_shipping'] = $this->language->get('tab_shipping');
		$this->data['tab_product'] = $this->language->get('tab_product');
		// $this->data['tab_voucher'] = $this->language->get('tab_voucher');
		$this->data['tab_total'] = $this->language->get('tab_total');

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

		if (isset($this->request->get['filter_quote_id'])) {
			$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_quote_status_id'])) {
			$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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
			'href'      => $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
			'separator' => ' :: '
		);

		if (!isset($this->request->get['quote_id'])) {
			$this->data['action'] = $this->url->link('sale/quote/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/quote/update', 'token=' . $this->session->data['token'] . '&quote_id=' . $this->request->get['quote_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['quote_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$quote_info = $this->model_sale_quote->getQuote($this->request->get['quote_id']);
    	}

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['quote_id'])) {
			$this->data['quote_id'] = $this->request->get['quote_id'];
		} else {
			$this->data['quote_id'] = 0;
		}
					
    	if (isset($this->request->post['store_id'])) {
      		$this->data['store_id'] = $this->request->post['store_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['store_id'] = $quote_info['store_id'];
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
		
		if (isset($this->request->post['customer'])) {
			$this->data['customer'] = $this->request->post['customer'];
		} elseif (!empty($quote_info)) {
			$this->data['customer'] = $quote_info['customer'];
		} else {
			$this->data['customer'] = '';
		}
						
		if (isset($this->request->post['customer_id'])) {
			$this->data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($quote_info)) {
			$this->data['customer_id'] = $quote_info['customer_id'];
		} else {
			$this->data['customer_id'] = '';
		}
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($quote_info)) {
			$this->data['customer_group_id'] = $quote_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '';
		}
		
		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
								
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($quote_info)) { 
			$this->data['firstname'] = $quote_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($quote_info)) { 
			$this->data['lastname'] = $quote_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($quote_info)) { 
			$this->data['email'] = $quote_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
				
    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($quote_info)) { 
			$this->data['telephone'] = $quote_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}
		
    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($quote_info)) { 
			$this->data['fax'] = $quote_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}	
		
		if (isset($this->request->post['affiliate_id'])) {
      		$this->data['affiliate_id'] = $this->request->post['affiliate_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['affiliate_id'] = $quote_info['affiliate_id'];
		} else {
      		$this->data['affiliate_id'] = '';
    	}
		
		if (isset($this->request->post['affiliate'])) {
      		$this->data['affiliate'] = $this->request->post['affiliate'];
    	} elseif (!empty($quote_info)) { 
			$this->data['affiliate'] = ($quote_info['affiliate_id'] ? $quote_info['affiliate_firstname'] . ' ' . $quote_info['affiliate_lastname'] : '');
		} else {
      		$this->data['affiliate'] = '';
    	}
				
		if (isset($this->request->post['quote_status_id'])) {
      		$this->data['quote_status_id'] = $this->request->post['quote_status_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['quote_status_id'] = $quote_info['quote_status_id'];
		} else {
      		$this->data['quote_status_id'] = '';
    	}
			
		$this->load->model('localisation/order_status');

		$this->data['quote_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
    	if (isset($this->request->post['comment'])) {
      		$this->data['comment'] = $this->request->post['comment'];
    	} elseif (!empty($quote_info)) { 
			$this->data['comment'] = $quote_info['comment'];
		} else {
      		$this->data['comment'] = '';
    	}	
		
		$this->load->model('sale/customer');

		if (isset($this->request->post['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
		} elseif (!empty($quote_info)) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($quote_info['customer_id']);
		} else {
			$this->data['addresses'] = array();
		}
			
    	if (isset($this->request->post['payment_firstname'])) {
      		$this->data['payment_firstname'] = $this->request->post['payment_firstname'];
		} elseif (!empty($quote_info)) { 
			$this->data['payment_firstname'] = $quote_info['payment_firstname'];
		} else {
      		$this->data['payment_firstname'] = '';
    	}

    	if (isset($this->request->post['payment_lastname'])) {
      		$this->data['payment_lastname'] = $this->request->post['payment_lastname'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_lastname'] = $quote_info['payment_lastname'];
		} else {
      		$this->data['payment_lastname'] = '';
    	}

    	if (isset($this->request->post['payment_company'])) {
      		$this->data['payment_company'] = $this->request->post['payment_company'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_company'] = $quote_info['payment_company'];
		} else {
      		$this->data['payment_company'] = '';
    	}
		
    	if (isset($this->request->post['payment_company_id'])) {
      		$this->data['payment_company_id'] = $this->request->post['payment_company_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_company_id'] = $quote_info['payment_company_id'];
		} else {
      		$this->data['payment_company_id'] = '';
    	}
		
    	if (isset($this->request->post['payment_tax_id'])) {
      		$this->data['payment_tax_id'] = $this->request->post['payment_tax_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_tax_id'] = $quote_info['payment_tax_id'];
		} else {
      		$this->data['payment_tax_id'] = '';
    	}
				
    	if (isset($this->request->post['payment_address_1'])) {
      		$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_address_1'] = $quote_info['payment_address_1'];
		} else {
      		$this->data['payment_address_1'] = '';
    	}

    	if (isset($this->request->post['payment_address_2'])) {
      		$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_address_2'] = $quote_info['payment_address_2'];
		} else {
      		$this->data['payment_address_2'] = '';
    	}
		
    	if (isset($this->request->post['payment_city'])) {
      		$this->data['payment_city'] = $this->request->post['payment_city'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_city'] = $quote_info['payment_city'];
		} else {
      		$this->data['payment_city'] = '';
    	}

    	if (isset($this->request->post['payment_postcode'])) {
      		$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_postcode'] = $quote_info['payment_postcode'];
		} else {
      		$this->data['payment_postcode'] = '';
    	}
				
    	if (isset($this->request->post['payment_country_id'])) {
      		$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_country_id'] = $quote_info['payment_country_id'];
		} else {
      		$this->data['payment_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['payment_zone_id'])) {
      		$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_zone_id'] = $quote_info['payment_zone_id'];
		} else {
      		$this->data['payment_zone_id'] = '';
    	}
						
    	if (isset($this->request->post['payment_method'])) {
      		$this->data['payment_method'] = $this->request->post['payment_method'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_method'] = $quote_info['payment_method'];
		} else {
      		$this->data['payment_method'] = '';
    	}
		
    	if (isset($this->request->post['payment_code'])) {
      		$this->data['payment_code'] = $this->request->post['payment_code'];
    	} elseif (!empty($quote_info)) { 
			$this->data['payment_code'] = $quote_info['payment_code'];
		} else {
      		$this->data['payment_code'] = '';
    	}			
			
    	if (isset($this->request->post['shipping_firstname'])) {
      		$this->data['shipping_firstname'] = $this->request->post['shipping_firstname'];
		} elseif (!empty($quote_info)) { 
			$this->data['shipping_firstname'] = $quote_info['shipping_firstname'];
		} else {
      		$this->data['shipping_firstname'] = '';
    	}

    	if (isset($this->request->post['shipping_lastname'])) {
      		$this->data['shipping_lastname'] = $this->request->post['shipping_lastname'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_lastname'] = $quote_info['shipping_lastname'];
		} else {
      		$this->data['shipping_lastname'] = '';
    	}

    	if (isset($this->request->post['shipping_company'])) {
      		$this->data['shipping_company'] = $this->request->post['shipping_company'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_company'] = $quote_info['shipping_company'];
		} else {
      		$this->data['shipping_company'] = '';
    	}

    	if (isset($this->request->post['shipping_address_1'])) {
      		$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_address_1'] = $quote_info['shipping_address_1'];
		} else {
      		$this->data['shipping_address_1'] = '';
    	}

    	if (isset($this->request->post['shipping_address_2'])) {
      		$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_address_2'] = $quote_info['shipping_address_2'];
		} else {
      		$this->data['shipping_address_2'] = '';
    	}
		
    	if (isset($this->request->post['shipping_city'])) {
      		$this->data['shipping_city'] = $this->request->post['shipping_city'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_city'] = $quote_info['shipping_city'];
		} else {
      		$this->data['shipping_city'] = '';
    	}
		
    	if (isset($this->request->post['shipping_postcode'])) {
      		$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_postcode'] = $quote_info['shipping_postcode'];
		} else {
      		$this->data['shipping_postcode'] = '';
    	}
				
    	if (isset($this->request->post['shipping_country_id'])) {
      		$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_country_id'] = $quote_info['shipping_country_id'];
		} else {
      		$this->data['shipping_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['shipping_zone_id'])) {
      		$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_zone_id'] = $quote_info['shipping_zone_id'];
		} else {
      		$this->data['shipping_zone_id'] = '';
    	}	
						
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();															
		
    	if (isset($this->request->post['shipping_method'])) {
      		$this->data['shipping_method'] = $this->request->post['shipping_method'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_method'] = $quote_info['shipping_method'];
		} else {
      		$this->data['shipping_method'] = '';
    	}	
		
    	if (isset($this->request->post['shipping_code'])) {
      		$this->data['shipping_code'] = $this->request->post['shipping_code'];
    	} elseif (!empty($quote_info)) { 
			$this->data['shipping_code'] = $quote_info['shipping_code'];
		} else {
      		$this->data['shipping_code'] = '';
    	}

		if (isset($this->request->post['quote_product'])) {
			$quote_products = $this->request->post['quote_product'];
		} elseif (isset($this->request->get['quote_id'])) {
			$quote_products = $this->model_sale_quote->getQuoteProducts($this->request->get['quote_id']);			
		} else {
			$quote_products = array();
		}
		
		$this->load->model('catalog/product');
		
		$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
		$this->data['quote_products'] = array();		
		
		foreach ($quote_products as $quote_product) {
			if (isset($quote_product['quote_option'])) {
				$quote_option = $quote_product['quote_option'];
			} elseif (isset($this->request->get['quote_id'])) {
				$quote_option = $this->model_sale_quote->getQuoteOptions($this->request->get['quote_id'], $quote_product['quote_product_id']);
			} else {
				$quote_option = array();
			}

			// if (isset($quote_product['quote_download'])) {
			// 	$quote_download = $quote_product['quote_download'];
			// } elseif (isset($this->request->get['quote_id'])) {
			// 	$quote_download = $this->model_sale_quote->getQuoteDownloads($this->request->get['quote_id'], $quote_product['quote_product_id']);
			// } else {
			// 	$quote_download = array();
			// }
											
			$this->data['quote_products'][] = array(
				'quote_product_id' => $quote_product['quote_product_id'],
				'product_id'       => $quote_product['product_id'],
				'name'             => $quote_product['name'],
				'model'            => $quote_product['model'],
				'option'           => $quote_option,
				// 'download'         => $quote_download,
				'quantity'         => $quote_product['quantity'],
				'price'            => $quote_product['price'],
				'total'            => $quote_product['total'],
				'tax'              => $quote_product['tax']
			);
		}
		
		// if (isset($this->request->post['quote_voucher'])) {
		// 	$this->data['quote_vouchers'] = $this->request->post['quote_voucher'];
		// } elseif (isset($this->request->get['quote_id'])) {
		// 	$this->data['quote_vouchers'] = $this->model_sale_quote->getQuoteVouchers($this->request->get['quote_id']);			
		// } else {
		// 	$this->data['quote_vouchers'] = array();
		// }
       
		// $this->load->model('sale/voucher_theme');
					
		// $this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
						
		if (isset($this->request->post['quote_total'])) {
      		$this->data['quote_totals'] = $this->request->post['quote_total'];
    	} elseif (isset($this->request->get['quote_id'])) { 
			$this->data['quote_totals'] = $this->model_sale_quote->getQuoteTotals($this->request->get['quote_id']);
		} else {
      		$this->data['quote_totals'] = array();
    	}	
		
		$this->template = 'sale/quote_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
  	}
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/quote')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
		
    	if ((utf8_strlen($this->request->post['payment_firstname']) < 1) || (utf8_strlen($this->request->post['payment_firstname']) > 32)) {
      		$this->error['payment_firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->post['payment_lastname']) < 1) || (utf8_strlen($this->request->post['payment_lastname']) > 32)) {
      		$this->error['payment_lastname'] = $this->language->get('error_lastname');
    	}

    	if ((utf8_strlen($this->request->post['payment_address_1']) < 3) || (utf8_strlen($this->request->post['payment_address_1']) > 128)) {
      		$this->error['payment_address_1'] = $this->language->get('error_address_1');
    	}

    	if ((utf8_strlen($this->request->post['payment_city']) < 3) || (utf8_strlen($this->request->post['payment_city']) > 128)) {
      		$this->error['payment_city'] = $this->language->get('error_city');
    	}
		
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['payment_country_id']);
		
		if ($country_info) {
			if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['payment_postcode']) < 2) || (utf8_strlen($this->request->post['payment_postcode']) > 10)) {
				$this->error['payment_postcode'] = $this->language->get('error_postcode');
			}
			
			// VAT Validation
			$this->load->helper('vat');
			
			if ($this->config->get('config_vat') && $this->request->post['payment_tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['payment_tax_id']) != 'invalid')) {
				$this->error['payment_tax_id'] = $this->language->get('error_vat');
			}				
		}

    	if ($this->request->post['payment_country_id'] == '') {
      		$this->error['payment_country'] = $this->language->get('error_country');
    	}
		
    	if ($this->request->post['payment_zone_id'] == '') {
      		$this->error['payment_zone'] = $this->language->get('error_zone');
    	}	
		
    	if ($this->request->post['payment_method'] == '') {
      		$this->error['payment_zone'] = $this->language->get('error_zone');
    	}			
		
		if (!$this->request->post['payment_method']) {
			$this->error['payment_method'] = $this->language->get('error_payment');
		}	
					
		// Check if any products require shipping
		$shipping = false;
		
		if (isset($this->request->post['quote_product'])) {
			$this->load->model('catalog/product');
			
			foreach ($this->request->post['quote_product'] as $quote_product) {
				$product_info = $this->model_catalog_product->getProduct($quote_product['product_id']);
			
				if ($product_info && $product_info['shipping']) {
					$shipping = true;
				}
			}
		}
		
		if ($shipping) {
			if ((utf8_strlen($this->request->post['shipping_firstname']) < 1) || (utf8_strlen($this->request->post['shipping_firstname']) > 32)) {
				$this->error['shipping_firstname'] = $this->language->get('error_firstname');
			}
	
			if ((utf8_strlen($this->request->post['shipping_lastname']) < 1) || (utf8_strlen($this->request->post['shipping_lastname']) > 32)) {
				$this->error['shipping_lastname'] = $this->language->get('error_lastname');
			}
			
			if ((utf8_strlen($this->request->post['shipping_address_1']) < 3) || (utf8_strlen($this->request->post['shipping_address_1']) > 128)) {
				$this->error['shipping_address_1'] = $this->language->get('error_address_1');
			}
	
			if ((utf8_strlen($this->request->post['shipping_city']) < 3) || (utf8_strlen($this->request->post['shipping_city']) > 128)) {
				$this->error['shipping_city'] = $this->language->get('error_city');
			}
	
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2) || (utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
				$this->error['shipping_postcode'] = $this->language->get('error_postcode');
			}
	
			if ($this->request->post['shipping_country_id'] == '') {
				$this->error['shipping_country'] = $this->language->get('error_country');
			}
			
			if ($this->request->post['shipping_zone_id'] == '') {
				$this->error['shipping_zone'] = $this->language->get('error_zone');
			}
			
			if (!$this->request->post['shipping_method']) {
				$this->error['shipping_method'] = $this->language->get('error_shipping');
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
	
   	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/quote')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
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
		
	public function info() {
		$this->load->model('sale/quote');

		if (isset($this->request->get['quote_id'])) {
			$quote_id = $this->request->get['quote_id'];
		} else {
			$quote_id = 0;
		}

		$quote_info = $this->model_sale_quote->getQuote($quote_id);

		if ($quote_info) {
			$this->load->language('sale/quote');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_quote_id'] = $this->language->get('text_quote_id');
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
			$this->data['text_quote_status'] = $this->language->get('text_quote_status');
			$this->data['text_comment'] = $this->language->get('text_comment');
			$this->data['text_affiliate'] = $this->language->get('text_affiliate');
			$this->data['text_commission'] = $this->language->get('text_commission');
			$this->data['text_ip'] = $this->language->get('text_ip');
			$this->data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$this->data['text_user_agent'] = $this->language->get('text_user_agent');
			$this->data['text_accept_language'] = $this->language->get('text_accept_language');
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
			$this->data['text_ip_region'] = $this->language->get('text_ip_region');
			$this->data['text_ip_city'] = $this->language->get('text_ip_city');
			$this->data['text_ip_latitude'] = $this->language->get('text_ip_latitude');
			$this->data['text_ip_longitude'] = $this->language->get('text_ip_longitude');
			$this->data['text_ip_isp'] = $this->language->get('text_ip_isp');
			$this->data['text_ip_org'] = $this->language->get('text_ip_org');
			$this->data['text_ip_asnum'] = $this->language->get('text_ip_asnum');
			$this->data['text_ip_user_type'] = $this->language->get('text_ip_user_type');
			$this->data['text_ip_country_confidence'] = $this->language->get('text_ip_country_confidence');
			$this->data['text_ip_region_confidence'] = $this->language->get('text_ip_region_confidence');
			$this->data['text_ip_city_confidence'] = $this->language->get('text_ip_city_confidence');
			$this->data['text_ip_postal_confidence'] = $this->language->get('text_ip_postal_confidence');
			$this->data['text_ip_postal_code'] = $this->language->get('text_ip_postal_code');
			$this->data['text_ip_accuracy_radius'] = $this->language->get('text_ip_accuracy_radius');
			$this->data['text_ip_net_speed_cell'] = $this->language->get('text_ip_net_speed_cell');
			$this->data['text_ip_metro_code'] = $this->language->get('text_ip_metro_code');
			$this->data['text_ip_area_code'] = $this->language->get('text_ip_area_code');
			$this->data['text_ip_time_zone'] = $this->language->get('text_ip_time_zone');
			$this->data['text_ip_region_name'] = $this->language->get('text_ip_region_name');
			$this->data['text_ip_domain'] = $this->language->get('text_ip_domain');
			$this->data['text_ip_country_name'] = $this->language->get('text_ip_country_name');
			$this->data['text_ip_continent_code'] = $this->language->get('text_ip_continent_code');
			$this->data['text_ip_corporate_proxy'] = $this->language->get('text_ip_corporate_proxy');
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
							
			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_filename'] = $this->language->get('column_filename');
			$this->data['column_remaining'] = $this->language->get('column_remaining');
						
			$this->data['entry_quote_status'] = $this->language->get('entry_quote_status');
			$this->data['entry_notify'] = $this->language->get('entry_notify');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			
			$this->data['button_quote'] = $this->language->get('button_quote');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_history'] = $this->language->get('button_add_history');
		
			$this->data['tab_quote'] = $this->language->get('tab_quote');
			$this->data['tab_payment'] = $this->language->get('tab_payment');
			$this->data['tab_shipping'] = $this->language->get('tab_shipping');
			$this->data['tab_product'] = $this->language->get('tab_product');
			$this->data['tab_history'] = $this->language->get('tab_history');
			$this->data['tab_fraud'] = $this->language->get('tab_fraud');
		
			$this->data['token'] = $this->session->data['token'];

			$url = '';

			if (isset($this->request->get['filter_quote_id'])) {
				$url .= '&filter_quote_id=' . $this->request->get['filter_quote_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_quote_status_id'])) {
				$url .= '&filter_quote_status_id=' . $this->request->get['filter_quote_status_id'];
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
				'href'      => $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
				'separator' => ' :: '
			);

			$this->data['printPDF'] = $this->url->link('sale/quote/invoice', 'token=' . $this->session->data['token'] . '&quote_id=' . (int)$this->request->get['quote_id'] . '&format=pdf', 'SSL');
			$this->data['sendEmail'] = $this->url->link('sale/quote/invoice', 'token=' . $this->session->data['token'] . '&quote_id=' . (int)$this->request->get['quote_id'] . '&format=email', 'SSL');
			$this->data['invoice'] = $this->url->link('sale/quote/invoice', 'token=' . $this->session->data['token'] . '&quote_id=' . (int)$this->request->get['quote_id'] . '&format=view', 'SSL');
			$this->data['cancel'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['view_order'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] .  '&order_id=' . $quote_info['invoice_no']);
			
			$this->data['quote_id'] = $this->request->get['quote_id'];
			
			if ($quote_info['invoice_no']) {
				$this->data['invoice_no'] = $quote_info['invoice_prefix'] . $quote_info['invoice_no'];
			} else {
				$this->data['invoice_no'] = '';
			}
			
			$this->data['store_name'] = $quote_info['store_name'];
			$this->data['store_url'] = $quote_info['store_url'];
			$this->data['firstname'] = $quote_info['firstname'];
			$this->data['lastname'] = $quote_info['lastname'];
						
			if ($quote_info['customer_id']) {
				$this->data['customer'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $quote_info['customer_id'], 'SSL');
			} else {
				$this->data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($quote_info['customer_group_id']);

			if ($customer_group_info) {
				$this->data['customer_group'] = $customer_group_info['name'];
			} else {
				$this->data['customer_group'] = '';
			}

			$this->data['email'] = $quote_info['email'];
			$this->data['telephone'] = $quote_info['telephone'];
			$this->data['fax'] = $quote_info['fax'];
			$this->data['comment'] = nl2br($quote_info['comment']);
			$this->data['shipping_method'] = $quote_info['shipping_method'];
			$this->data['payment_method'] = $quote_info['payment_method'];
			$this->data['total'] = $this->currency->format($quote_info['total'], $quote_info['currency_code'], $quote_info['currency_value']);
			
			if ($quote_info['total'] < 0) {
				$this->data['credit'] = $quote_info['total'];
			} else {
				$this->data['credit'] = 0;
			}
			
			$this->load->model('sale/customer');
						
			// $this->data['credit_total'] = $this->model_sale_customer->getTotalTransactionsByQuoteId($this->request->get['quote_id']); 
			
			$this->data['affiliate_firstname'] = $quote_info['affiliate_firstname'];
			$this->data['affiliate_lastname'] = $quote_info['affiliate_lastname'];
			
			if ($quote_info['affiliate_id']) {
				$this->data['affiliate'] = $this->url->link('sale/affiliate/update', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $quote_info['affiliate_id'], 'SSL');
			} else {
				$this->data['affiliate'] = '';
			}
			
			$this->data['commission'] = $this->currency->format($quote_info['commission'], $quote_info['currency_code'], $quote_info['currency_value']);
						
			$this->load->model('sale/affiliate');
			
			// $this->data['commission_total'] = $this->model_sale_affiliate->getTotalTransactionsByQuoteId($this->request->get['quote_id']); 

			$this->load->model('localisation/order_status');

			$quote_status_info = $this->model_localisation_order_status->getOrderStatus($quote_info['quote_status_id']);

			if ($quote_status_info) {
				$this->data['quote_status'] = $quote_status_info['name'];
			} else {
				$this->data['quote_status'] = '';
			}
			
			$this->data['ip'] = $quote_info['ip'];
			$this->data['forwarded_ip'] = $quote_info['forwarded_ip'];
			$this->data['user_agent'] = $quote_info['user_agent'];
			$this->data['accept_language'] = $quote_info['accept_language'];
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($quote_info['date_added']));
			$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($quote_info['date_modified']));		
			$this->data['payment_firstname'] = $quote_info['payment_firstname'];
			$this->data['payment_lastname'] = $quote_info['payment_lastname'];
			$this->data['payment_company'] = $quote_info['payment_company'];
			$this->data['payment_company_id'] = $quote_info['payment_company_id'];
			$this->data['payment_tax_id'] = $quote_info['payment_tax_id'];
			$this->data['payment_address_1'] = $quote_info['payment_address_1'];
			$this->data['payment_address_2'] = $quote_info['payment_address_2'];
			$this->data['payment_city'] = $quote_info['payment_city'];
			$this->data['payment_postcode'] = $quote_info['payment_postcode'];
			$this->data['payment_zone'] = $quote_info['payment_zone'];
			$this->data['payment_zone_code'] = $quote_info['payment_zone_code'];
			$this->data['payment_country'] = $quote_info['payment_country'];			
			$this->data['shipping_firstname'] = $quote_info['shipping_firstname'];
			$this->data['shipping_lastname'] = $quote_info['shipping_lastname'];
			$this->data['shipping_company'] = $quote_info['shipping_company'];
			$this->data['shipping_address_1'] = $quote_info['shipping_address_1'];
			$this->data['shipping_address_2'] = $quote_info['shipping_address_2'];
			$this->data['shipping_city'] = $quote_info['shipping_city'];
			$this->data['shipping_postcode'] = $quote_info['shipping_postcode'];
			$this->data['shipping_zone'] = $quote_info['shipping_zone'];
			$this->data['shipping_zone_code'] = $quote_info['shipping_zone_code'];
			$this->data['shipping_country'] = $quote_info['shipping_country'];

			$this->data['products'] = array();

			$products = $this->model_sale_quote->getQuoteProducts($this->request->get['quote_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_quote->getQuoteOptions($this->request->get['quote_id'], $product['quote_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					// } else {
					// 	$option_data[] = array(
					// 		'name'  => $option['name'],
					// 		'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
					// 		'type'  => $option['type'],
					// 		'href'  => $this->url->link('sale/quote/download', 'token=' . $this->session->data['token'] . '&quote_id=' . $this->request->get['quote_id'] . '&quote_option_id=' . $option['quote_option_id'], 'SSL')
					// 	);						
					}
				}

				$this->data['products'][] = array(
					'quote_product_id' => $product['quote_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quote_info['currency_code'], $quote_info['currency_value']),
					'total'    		   => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quote_info['currency_code'], $quote_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}
		
			// $this->data['vouchers'] = array();	
			
			// $vouchers = $this->model_sale_quote->getQuoteVouchers($this->request->get['quote_id']);
			 
			// foreach ($vouchers as $voucher) {
			// 	$this->data['vouchers'][] = array(
			// 		'description' => $voucher['description'],
			// 		'amount'      => $this->currency->format($voucher['amount'], $quote_info['currency_code'], $quote_info['currency_value']),
			// 		'href'        => $this->url->link('sale/voucher/update', 'token=' . $this->session->data['token'] . '&voucher_id=' . $voucher['voucher_id'], 'SSL')
			// 	);
			// }
		
			$this->data['totals'] = $this->model_sale_quote->getQuoteTotals($this->request->get['quote_id']);

			// $this->data['downloads'] = array();

			// foreach ($products as $product) {
			// 	$results = $this->model_sale_quote->getQuoteDownloads($this->request->get['quote_id'], $product['quote_product_id']);
	
			// 	foreach ($results as $result) {
			// 		$this->data['downloads'][] = array(
			// 			'name'      => $result['name'],
			// 			'filename'  => $result['mask'],
			// 			'remaining' => $result['remaining']
			// 		);
			// 	}
			// }
			
			$this->data['quote_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$this->data['quote_status_id'] = $quote_info['quote_status_id'];

			// Fraud
			$this->load->model('sale/fraud');
			
			$fraud_info = $this->model_sale_fraud->getFraud($quote_info['quote_id']);
			
			if ($fraud_info) {
				$this->data['country_match'] = $fraud_info['country_match'];
				
				if ($fraud_info['country_code']) {
					$this->data['country_code'] = $fraud_info['country_code'];
				} else {
					$this->data['country_code'] = '';
				}
				
				$this->data['high_risk_country'] = $fraud_info['high_risk_country'];
				$this->data['distance'] = $fraud_info['distance'];
				
				if ($fraud_info['ip_region']) {
					$this->data['ip_region'] = $fraud_info['ip_region'];
				} else {
					$this->data['ip_region'] = '';
				}
								
				if ($fraud_info['ip_city']) {
					$this->data['ip_city'] = $fraud_info['ip_city'];
				} else {
					$this->data['ip_city'] = '';
				}
				
				$this->data['ip_latitude'] = $fraud_info['ip_latitude'];
				$this->data['ip_longitude'] = $fraud_info['ip_longitude'];

				if ($fraud_info['ip_isp']) {
					$this->data['ip_isp'] = $fraud_info['ip_isp'];
				} else {
					$this->data['ip_isp'] = '';
				}
				
				if ($fraud_info['ip_org']) {
					$this->data['ip_org'] = $fraud_info['ip_org'];
				} else {
					$this->data['ip_org'] = '';
				}
								
				$this->data['ip_asnum'] = $fraud_info['ip_asnum'];
				
				if ($fraud_info['ip_user_type']) {
					$this->data['ip_user_type'] = $fraud_info['ip_user_type'];
				} else {
					$this->data['ip_user_type'] = '';
				}
				
				if ($fraud_info['ip_country_confidence']) {
					$this->data['ip_country_confidence'] = $fraud_info['ip_country_confidence'];
				} else {
					$this->data['ip_country_confidence'] = '';
				}
												
				if ($fraud_info['ip_region_confidence']) {
					$this->data['ip_region_confidence'] = $fraud_info['ip_region_confidence'];
				} else {
					$this->data['ip_region_confidence'] = '';
				}
				
				if ($fraud_info['ip_city_confidence']) {
					$this->data['ip_city_confidence'] = $fraud_info['ip_city_confidence'];
				} else {
					$this->data['ip_city_confidence'] = '';
				}
				
				if ($fraud_info['ip_postal_confidence']) {
					$this->data['ip_postal_confidence'] = $fraud_info['ip_postal_confidence'];
				} else {
					$this->data['ip_postal_confidence'] = '';
				}
				
				if ($fraud_info['ip_postal_code']) {
					$this->data['ip_postal_code'] = $fraud_info['ip_postal_code'];
				} else {
					$this->data['ip_postal_code'] = '';
				}
								
				$this->data['ip_accuracy_radius'] = $fraud_info['ip_accuracy_radius'];
				
				if ($fraud_info['ip_net_speed_cell']) {
					$this->data['ip_net_speed_cell'] = $fraud_info['ip_net_speed_cell'];
				} else {
					$this->data['ip_net_speed_cell'] = '';
				}
								
				$this->data['ip_metro_code'] = $fraud_info['ip_metro_code'];
				$this->data['ip_area_code'] = $fraud_info['ip_area_code'];
				
				if ($fraud_info['ip_time_zone']) {
					$this->data['ip_time_zone'] = $fraud_info['ip_time_zone'];
				} else {
					$this->data['ip_time_zone'] = '';
				}

				if ($fraud_info['ip_region_name']) {
					$this->data['ip_region_name'] = $fraud_info['ip_region_name'];
				} else {
					$this->data['ip_region_name'] = '';
				}				
				
				if ($fraud_info['ip_domain']) {
					$this->data['ip_domain'] = $fraud_info['ip_domain'];
				} else {
					$this->data['ip_domain'] = '';
				}
				
				if ($fraud_info['ip_country_name']) {
					$this->data['ip_country_name'] = $fraud_info['ip_country_name'];
				} else {
					$this->data['ip_country_name'] = '';
				}	
								
				if ($fraud_info['ip_continent_code']) {
					$this->data['ip_continent_code'] = $fraud_info['ip_continent_code'];
				} else {
					$this->data['ip_continent_code'] = '';
				}
				
				if ($fraud_info['ip_corporate_proxy']) {
					$this->data['ip_corporate_proxy'] = $fraud_info['ip_corporate_proxy'];
				} else {
					$this->data['ip_corporate_proxy'] = '';
				}
								
				$this->data['anonymous_proxy'] = $fraud_info['anonymous_proxy'];
				$this->data['proxy_score'] = $fraud_info['proxy_score'];
				
				if ($fraud_info['is_trans_proxy']) {
					$this->data['is_trans_proxy'] = $fraud_info['is_trans_proxy'];
				} else {
					$this->data['is_trans_proxy'] = '';
				}	
							
				$this->data['free_mail'] = $fraud_info['free_mail'];
				$this->data['carder_email'] = $fraud_info['carder_email'];
				
				if ($fraud_info['high_risk_username']) {
					$this->data['high_risk_username'] = $fraud_info['high_risk_username'];
				} else {
					$this->data['high_risk_username'] = '';
				}
							
				if ($fraud_info['high_risk_password']) {
					$this->data['high_risk_password'] = $fraud_info['high_risk_password'];
				} else {
					$this->data['high_risk_password'] = '';
				}		
				
				$this->data['bin_match'] = $fraud_info['bin_match'];

				if ($fraud_info['bin_country']) {
					$this->data['bin_country'] = $fraud_info['bin_country'];
				} else {
					$this->data['bin_country'] = '';
				}	
								
				$this->data['bin_name_match'] = $fraud_info['bin_name_match'];
				
				if ($fraud_info['bin_name']) {
					$this->data['bin_name'] = $fraud_info['bin_name'];
				} else {
					$this->data['bin_name'] = '';
				}	
								
				$this->data['bin_phone_match'] = $fraud_info['bin_phone_match'];

				if ($fraud_info['bin_phone']) {
					$this->data['bin_phone'] = $fraud_info['bin_phone'];
				} else {
					$this->data['bin_phone'] = '';
				}	
				
				if ($fraud_info['customer_phone_in_billing_location']) {
					$this->data['customer_phone_in_billing_location'] = $fraud_info['customer_phone_in_billing_location'];
				} else {
					$this->data['customer_phone_in_billing_location'] = '';
				}	
												
				$this->data['ship_forward'] = $fraud_info['ship_forward'];

				if ($fraud_info['city_postal_match']) {
					$this->data['city_postal_match'] = $fraud_info['city_postal_match'];
				} else {
					$this->data['city_postal_match'] = '';
				}	
				
				if ($fraud_info['ship_city_postal_match']) {
					$this->data['ship_city_postal_match'] = $fraud_info['ship_city_postal_match'];
				} else {
					$this->data['ship_city_postal_match'] = '';
				}	
								
				$this->data['score'] = $fraud_info['score'];
				$this->data['explanation'] = $fraud_info['explanation'];
				$this->data['risk_score'] = $fraud_info['risk_score'];
				$this->data['queries_remaining'] = $fraud_info['queries_remaining'];
				$this->data['maxmind_id'] = $fraud_info['maxmind_id'];
				$this->data['error'] = $fraud_info['error'];
			} else {
				$this->data['maxmind_id'] = '';
			}
			
			$this->template = 'sale/quote_info.tpl';
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

	// public function createInvoiceNo() {
	// 	$this->language->load('sale/quote');

	// 	$json = array();
		
 //     	if (!$this->user->hasPermission('modify', 'sale/quote')) {
 //      		$json['error'] = $this->language->get('error_permission'); 
	// 	} elseif (isset($this->request->get['quote_id'])) {
	// 		$this->load->model('sale/quote');
			
	// 		$invoice_no = $this->model_sale_quote->createInvoiceNo($this->request->get['quote_id']);
			
	// 		if ($invoice_no) {
	// 			$json['invoice_no'] = $invoice_no;
	// 		} else {
	// 			$json['error'] = $this->language->get('error_action');
	// 		}
	// 	}

	// 	$this->response->setOutput(json_encode($json));
 //  	}

  	public function createOrder() {
  		$this->language->load('sale/quote');

  		$json = array();

  		if (!$this->user->hasPermission('modify', 'sale/quote')) {
  			$json['error'] = $this->language->get('error_permission');
  		} elseif (isset($this->request->get['quote_id'])) {
  			$this->load->model('sale/quote');

  			$invoice_no = $this->model_sale_quote->createOrder($this->request->get['quote_id']);

  			if ($invoice_no) {
  				$json['invoice_no'] = $invoice_no;
  			} else {
  				$json['error'] = $this->language->get('error_action');
  			}
  			
  		}
  		$this->response->setOutput(json_encode($json));
  		
  	}

	public function history() {
    	$this->language->load('sale/quote');
		
		$this->data['error'] = '';
		$this->data['success'] = '';
		
		$this->load->model('sale/quote');
	
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/quote')) { 
				$this->data['error'] = $this->language->get('error_permission');
			}
			
			if (!$this->data['error']) { 
				$this->model_sale_quote->addQuoteHistory($this->request->get['quote_id'], $this->request->post);
				
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
			
		$results = $this->model_sale_quote->getQuoteHistories($this->request->get['quote_id'], ($page - 1) * 10, 10);
      		
		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_quote->getTotalQuoteHistories($this->request->get['quote_id']);
			
		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/quote/history', 'token=' . $this->session->data['token'] . '&quote_id=' . $this->request->get['quote_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'sale/quote_history.tpl';		
		
		$this->response->setOutput($this->render());
  	}
	
	public function upload() {
		$this->language->load('sale/quote');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!empty($this->request->files['file']['name'])) {
				$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');
				
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}	  	
				
				$allowed = array();
				
				$filetypes = explode(',', $this->config->get('config_upload_allowed'));
				
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}
				
				if (!in_array(utf8_substr(strrchr($filename, '.'), 1), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
							
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		
			if (!isset($json['error'])) {
				if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
					$file = basename($filename) . '.' . md5(mt_rand());
					
					$json['file'] = $file;
					
					move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
				}
							
				$json['success'] = $this->language->get('text_upload');
			}	
		}
		
		$this->response->setOutput(json_encode($json));
	}

	public function invoice() {
		$this->language->load('sale/quote');

		//add
		if (isset($this->request->get['format'])) {
			$lcFormat = $this->request->get['format'];
		} else {
			$lcFormat = '';
		}
		//endAdd

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_invoice'] = $this->language->get('text_invoice');

		$this->data['text_quote'] = $this->language->get('text_quote');
		$this->data['text_quote_id'] = $this->language->get('text_quote_id');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_company_id'] = $this->language->get('text_company_id');
		$this->data['text_tax_id'] = $this->language->get('text_tax_id');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$this->load->model('sale/quote');

		$this->load->model('setting/setting');

		$this->data['quotes'] = array();

		$quotes = array();

		if (isset($this->request->post['selected'])) {
			$quotes = $this->request->post['selected'];
		} elseif (isset($this->request->get['quote_id'])) {
			$quotes[] = $this->request->get['quote_id'];
		}

		foreach ($quotes as $quote_id) {
			$quote_info = $this->model_sale_quote->getquote($quote_id);

			if ($quote_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $quote_info['store_id']);

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

				if ($quote_info['invoice_no']) {
					$invoice_no = $quote_info['invoice_prefix'] . $quote_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				if ($quote_info['shipping_address_format']) {
					$format = $quote_info['shipping_address_format'];
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
					'firstname' => $quote_info['shipping_firstname'],
					'lastname'  => $quote_info['shipping_lastname'],
					'company'   => $quote_info['shipping_company'],
					'address_1' => $quote_info['shipping_address_1'],
					'address_2' => $quote_info['shipping_address_2'],
					'city'      => $quote_info['shipping_city'],
					'postcode'  => $quote_info['shipping_postcode'],
					'zone'      => $quote_info['shipping_zone'],
					'zone_code' => $quote_info['shipping_zone_code'],
					'country'   => $quote_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($quote_info['payment_address_format']) {
					$format = $quote_info['payment_address_format'];
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
					'firstname' => $quote_info['payment_firstname'],
					'lastname'  => $quote_info['payment_lastname'],
					'company'   => $quote_info['payment_company'],
					'address_1' => $quote_info['payment_address_1'],
					'address_2' => $quote_info['payment_address_2'],
					'city'      => $quote_info['payment_city'],
					'postcode'  => $quote_info['payment_postcode'],
					'zone'      => $quote_info['payment_zone'],
					'zone_code' => $quote_info['payment_zone_code'],
					'country'   => $quote_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$product_data = array();

				$products = $this->model_sale_quote->getquoteProducts($quote_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_quote->getquoteOptions($quote_id, $product['quote_product_id']);

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
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quote_info['currency_code'], $quote_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quote_info['currency_code'], $quote_info['currency_value'])
					);
				}

				// $voucher_data = array();

				// $vouchers = $this->model_sale_quote->getOrderVouchers($quote_id);

				// foreach ($vouchers as $voucher) {
				// 	$voucher_data[] = array(
				// 		'description' => $voucher['description'],
				// 		'amount'      => $this->currency->format($voucher['amount'], $quote_info['currency_code'], $quote_info['currency_value'])
				// 	);
				// }

				$total_data = $this->model_sale_quote->getquoteTotals($quote_id);

				$this->data['quotes'][] = array(
					'quote_id'	         => $quote_id,
					'invoice_no'         => $invoice_no,
					'date_added'         => date($this->language->get('date_format_short'), strtotime($quote_info['date_added'])),
					'store_name'         => $quote_info['store_name'],
					'store_url'          => rtrim($quote_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $quote_info['email'],
					'telephone'          => $quote_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $quote_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_company_id' => $quote_info['payment_company_id'],
					'payment_tax_id'     => $quote_info['payment_tax_id'],
					'payment_method'     => $quote_info['payment_method'],
					'product'            => $product_data,
					// 'voucher'            => $voucher_data,
					'total'              => $total_data,
					'comment'            => nl2br($quote_info['comment'])
				);
			}
		}

		//Add
		$Log = new Log('quote_mail.log');
		$Log->write($this->request->post);
		if (!empty($this->config->get('config_logo'))) {
			$this->data['logo'] = DIR_IMAGE . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = DIR_IMAGE . 'no_image.jpg';
		}
		 
		if ($lcFormat=='pdf') {
			$this->renderPDF('sale/quote_printPDF.tpl', 'pdf', 'quote', $quote_id);
		} elseif ($lcFormat=='email') {
			$this->renderPDF('sale/quote_printPDF.tpl', 'email', 'quote', $quote_id);
			$to = $this->request->post['to'];
			$subject = $this->request->post['subject'];
			$text = $this->request->post['message'];
			
			$lcFile = DIR_DOWNLOAD . 'quote_' . $quote_id . '.pdf';
			$this->sendnewmail($to, $subject, $text, $lcFile);

			$this->redirect($this->url->link('sale/quote/info', 'token=' . $this->session->data['token'] .'&quote_id=' . $quote_id, 'SSL'));
			
		} else {
			$this->template = 'sale/quote_invoice.tpl';
			$this->response->setOutput($this->render());
		}
		//endAdd
	}

}
?>