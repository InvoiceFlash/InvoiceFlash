<?php
class ControllerSaleOrder extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

    	$this->getList();
  	}
	
  	public function insert() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->request->post['user_id'] = $this->user->getId();

			if (empty($this->request->post['order_status_id'])) {
				$this->request->post['order_status_id'] = 1;
			}

			$new_order_id = $this->model_sale_order->addOrder($this->request->post);

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'create',
				'document_type' => 'sale_order',
				'document_id'   => (int)$new_order_id,
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
			));

			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
			
			$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
	   
			$this->getList();
	  	}else{
			$this->getForm();
	  	}
  	}
	
  	public function update() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$order_before    = $this->model_sale_order->getOrder($this->request->get['order_id']);
			$products_before = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			$this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);

			$diff         = $this->diffFields($order_before, $this->request->post);
			$product_diff = $this->diffOrderProducts($products_before, isset($this->request->post['order_product']) ? $this->request->post['order_product'] : array());

			$this->load->model('tool/user_logs');
			$this->model_tool_user_logs->addLog(array(
				'user_id'       => $this->user->getId(),
				'username'      => $this->user->getUserName(),
				'action'        => 'edit',
				'document_type' => 'sale_order',
				'document_id'   => (int)$this->request->get['order_id'],
				'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				'original'      => array_merge($diff['original'], $product_diff['original']),
				'cambiado'      => array_merge($diff['changed'], $product_diff['changed']),
			));

			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
			
			$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		
			$this->getList();
		}else{
			$this->getForm();
		}
  	}
	
  	public function delete() {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			$this->load->model('tool/user_logs');

			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);

				$this->model_tool_user_logs->addLog(array(
					'user_id'       => $this->user->getId(),
					'username'      => $this->user->getUserName(),
					'action'        => 'delete',
					'document_type' => 'sale_order',
					'document_id'   => (int)$order_id,
					'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
				));
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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

			$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

  	public function convert() {
		$this->load->language('sale/order');

		$this->load->model('sale/order');
		$this->load->model('sale/delivery');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			$new_delivery_id = 0;
			$converted_count = 0;

			foreach ($this->request->post['selected'] as $order_id) {
				$order_info = $this->model_sale_order->getOrder($order_id);

				// Igual que createDelivery(): si el pedido ya tiene un albarán asociado, se omite.
				if ($order_info && !$order_info['invoice_no']) {
					$order_products = $this->model_sale_order->getOrderProducts($order_id);

					$delivery_products = array();

					foreach ($order_products as $product) {
						$order_option = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

						$delivery_products[] = array(
							'delivery_product_id' 	=> $product['order_product_id'],
							'product_id' 			=> $product['product_id'],
							'name' 					=> $product['name'],
							'model' 				=> $product['model'],
							'delivery_option' 		=> $order_option,
							'quantity'			 	=> $product['quantity'],
							'price'				 	=> $product['price'],
							'discount'				=> $product['discount'],
							'total'				 	=> $product['total'],
							'tax' 					=> $product['tax'],
							'reward	'				=> $product['reward']
						);
					}

					$delivery_totals = $this->model_sale_order->getOrderTotals($order_id);

					$data = array(
						'invoice_no'	=> $order_info['invoice_no'],
						'invoice_prefix'	=> $order_info['invoice_prefix'],
						'store_id'	=> $order_info['store_id'],
						'store_name'	=> $order_info['store_name'],
						'store_url'	=> $order_info['store_url'],
						'customer_id'	=> $order_info['customer_id'],
						'customer_group_id'	=> $order_info['customer_group_id'],
						'email'	=> $order_info['email'],
						'telephone'	=> $order_info['telephone'],
						'fax'	=> $order_info['fax'],
						'payment_company'	=> $order_info['payment_company'],
						'payment_company_id'	=> $order_info['payment_company_id'],
						'payment_tax_id'	=> $order_info['payment_tax_id'],
						'payment_address_1'	=> $order_info['payment_address_1'],
						'payment_address_2'	=> $order_info['payment_address_2'],
						'payment_city'	=> $order_info['payment_city'],
						'payment_postcode'	=> $order_info['payment_postcode'],
						'payment_country'	=> $order_info['payment_country'],
						'payment_country_id'	=> $order_info['payment_country_id'],
						'payment_zone'	=> $order_info['payment_zone'],
						'payment_zone_id'	=> $order_info['payment_zone_id'],
						'payment_address_format'	=> $order_info['payment_address_format'],
						'payment_method'	=> $order_info['payment_method'],
						'payment_code'	=> $order_info['payment_code'],
						'shipping_company'	=> $order_info['shipping_company'],
						'shipping_address_1'	=> $order_info['shipping_address_1'],
						'shipping_address_2'	=> $order_info['shipping_address_2'],
						'shipping_city'	=> $order_info['shipping_city'],
						'shipping_postcode'	=> $order_info['shipping_postcode'],
						'shipping_country'	=> $order_info['shipping_country'],
						'shipping_country_id'	=> $order_info['shipping_country_id'],
						'shipping_zone'	=> $order_info['shipping_zone'],
						'shipping_zone_id'	=> $order_info['shipping_zone_id'],
						'shipping_address_format'	=> $order_info['shipping_address_format'],
						'shipping_method'	=> $order_info['shipping_method'],
						'shipping_code'	=> $order_info['shipping_code'],
						'comment'	=> $order_info['comment'],
						'total'	=> $order_info['total'],
						'delivery_status_id'	=> $order_info['order_status_id'],
						'commission'	=> $order_info['commission'],
						'language_id'	=> $order_info['language_id'],
						'currency_id'	=> $order_info['currency_id'],
						'currency_code'	=> $order_info['currency_code'],
						'currency_value'	=> $order_info['currency_value'],
						'date_added'	=> $order_info['date_added'],
						'date_modified'	=> $order_info['date_modified'],
						'delivery_product' => $delivery_products,
						'delivery_total' => $delivery_totals
					);

					$this->model_sale_delivery->addDelivery($data);

					$query = $this->db->query("SELECT delivery_id FROM `" . DB_PREFIX . "delivery` ORDER BY delivery_id DESC LIMIT 1");

					$new_delivery_id = $query->row['delivery_id'];
					$converted_count++;

					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = " . (int)$new_delivery_id . " WHERE order_id = " . (int)$order_id);

					$this->load->model('tool/user_logs');

					$this->model_tool_user_logs->addLog(array(
						'user_id'       => $this->user->getId(),
						'username'      => $this->user->getUserName(),
						'action'        => 'create',
						'document_type' => 'sale_delivery',
						'document_id'   => (int)$new_delivery_id,
						'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
					));
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_convert');

			if ($this->config->get('config_open_next_convert') && $converted_count == 1) {
				$this->session->data['open_next_url'] = $this->url->link('sale/delivery/info', 'token=' . $this->session->data['token'] . '&delivery_id=' . $new_delivery_id, 'SSL');
			}

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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

			$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

  	public function copy() {
		$this->load->language('sale/order');

		$this->load->model('sale/order');
		$this->load->model('tool/user_logs');

		if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $order_id) {
				$order_info = $this->model_sale_order->getOrder($order_id);

				if ($order_info) {
					$order_products = array();

					foreach ($this->model_sale_order->getOrderProducts($order_id) as $order_product) {
						$order_products[] = array(
							'order_product_id' => 0,
							'product_id'       => $order_product['product_id'],
							'name'             => $order_product['name'],
							'model'            => $order_product['model'],
							'order_option'     => $this->model_sale_order->getOrderOptions($order_id, $order_product['order_product_id']),
							'quantity'         => $order_product['quantity'],
							'price'            => $order_product['price'],
							'discount'         => $order_product['discount'],
							'total'            => $order_product['total'],
							'tax'              => $order_product['tax'],
							'reward'           => $order_product['reward']
						);
					}

					$data = array(
						'user_id'                 => $this->user->getId(),
						'store_id'                => $order_info['store_id'],
						'customer_id'             => $order_info['customer_id'],
						'customer_group_id'       => $order_info['customer_group_id'],
						'email'                   => $order_info['email'],
						'telephone'               => $order_info['telephone'],
						'fax'                     => $order_info['fax'],
						'payment_company'         => $order_info['payment_company'],
						'payment_company_id'      => $order_info['payment_company_id'],
						'payment_tax_id'          => $order_info['payment_tax_id'],
						'payment_address_1'       => $order_info['payment_address_1'],
						'payment_address_2'       => $order_info['payment_address_2'],
						'payment_city'            => $order_info['payment_city'],
						'payment_postcode'        => $order_info['payment_postcode'],
						'payment_country'         => $order_info['payment_country'],
						'payment_country_id'      => $order_info['payment_country_id'],
						'payment_zone'            => $order_info['payment_zone'],
						'payment_zone_id'         => $order_info['payment_zone_id'],
						'payment_address_format'  => $order_info['payment_address_format'],
						'payment_method'          => $order_info['payment_method'],
						'payment_code'            => $order_info['payment_code'],
						'shipping_company'        => $order_info['shipping_company'],
						'shipping_address_1'      => $order_info['shipping_address_1'],
						'shipping_address_2'      => $order_info['shipping_address_2'],
						'shipping_city'           => $order_info['shipping_city'],
						'shipping_postcode'       => $order_info['shipping_postcode'],
						'shipping_country'        => $order_info['shipping_country'],
						'shipping_country_id'     => $order_info['shipping_country_id'],
						'shipping_zone'           => $order_info['shipping_zone'],
						'shipping_zone_id'        => $order_info['shipping_zone_id'],
						'shipping_address_format' => $order_info['shipping_address_format'],
						'shipping_method'         => $order_info['shipping_method'],
						'shipping_code'           => $order_info['shipping_code'],
						'comment'                 => $order_info['comment'],
						'order_status_id'         => $order_info['order_status_id'],
						'order_product'           => $order_products,
						'order_total'             => $this->model_sale_order->getOrderTotals($order_id)
					);

					$new_order_id = $this->model_sale_order->addOrder($data);

					$this->model_tool_user_logs->addLog(array(
						'user_id'       => $this->user->getId(),
						'username'      => $this->user->getUserName(),
						'action'        => 'create',
						'document_type' => 'sale_order',
						'document_id'   => (int)$new_order_id,
						'ip'            => isset($this->request->server['REMOTE_ADDR']) ? $this->request->server['REMOTE_ADDR'] : '',
					));
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_copy');

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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

			$this->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_company'])) {
			$filter_company = $this->request->get['filter_company'];
		} else {
			$filter_company = null;
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
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

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['invoice'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['print'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['printPDF'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'] . '&format=pdf', 'SSL');
		$this->data['insert'] = $this->url->link('sale/order/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['convert'] = $this->url->link('sale/order/convert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('sale/order/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		// add print selection
		$reports = array_slice(scandir(DIR_TEMPLATE . 'sale/reports'), 2);

		$this->data['reports'] = array();

		foreach ($reports as $report) {
			$name = ucfirst(str_replace('_', ' ', str_replace('.tpl', '', str_replace('_invoice', '', $report))));

			if (strpos($name, 'Order')!==FALSE) {
				$this->data['reports'][] = array(
					'name' => $name,
					'report' => $report
				);
			}
		}
		// end add

		$this->data['orders'] = array();

		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_company'	     => $filter_company,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_sale_order->getTotalOrders($data);

		$results = $this->model_sale_order->getOrders($data);

    	foreach ($results as $result) {
			$action = array();
                      
			$action[] = array(
				'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'icon' => 'far fa-eye',
				'color' => 'info'
			);
			
			$has_delivery = $this->model_sale_order->checkDelivery($result['order_id']);

			// Si no se ha generado el delivery, se puede editar el order
			if (!$has_delivery) {
				$action[] = array(
					'href' => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
					'icon' => 'fas fa-edit',
					'color' => 'default'
				);
			}

			$this->data['orders'][] = array(
				'order_id'      => $result['order_id'],
				'company'       => $result['company'],
				'status'        => $result['status'],
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'], true, true),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'has_delivery'  => $has_delivery,
				'action'        => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['column_order_id'] = $this->language->get('column_order_id');
    	$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_convert_delivery'] = $this->language->get('button_convert_delivery');
		$this->data['button_copy'] = $this->language->get('button_copy');
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

		if (isset($this->session->data['open_next_url'])) {
			$this->data['open_next_url'] = $this->session->data['open_next_url'];

			unset($this->session->data['open_next_url']);
		} else {
			$this->data['open_next_url'] = '';
		}

		$this->data['config_open_next_convert'] = $this->config->get('config_open_next_convert') ? true : false;

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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

		$this->data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$this->data['sort_company'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=company' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_company'] = $filter_company;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/order_list.tpl';

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
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_invoice_details'] = $this->language->get('text_invoice_details');
		
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_vat'] = $this->language->get('entry_vat');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
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
		$this->data['entry_global_discount'] = $this->language->get('entry_global_discount');
		$this->data['entry_discount'] = $this->language->get('entry_discount');
		$this->data['entry_tax_rate'] = $this->language->get('entry_tax_rate');
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_delivery_date'] = $this->language->get('column_delivery_date');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_update_total'] = $this->language->get('button_update_total');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['tab_order'] = $this->language->get('tab_order');
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

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_company'])) {
			$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
			'separator' => ' :: '
		);

		if (!isset($this->request->get['order_id'])) {
			$this->data['action'] = $this->url->link('sale/order/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		}

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['order_id'])) {
			$this->data['order_id'] = $this->request->get['order_id'];
		} else {
			$this->data['order_id'] = 0;
		}
					
    	if (isset($this->request->post['store_id'])) {
      		$this->data['store_id'] = $this->request->post['store_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['store_id'] = $order_info['store_id'];
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
		} elseif (!empty($order_info)) {
			$this->data['company'] = $order_info['company'];
		} else {
			$this->data['company'] = '';
		}
						
		if (isset($this->request->post['customer_id'])) {
			$this->data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($order_info)) {
			$this->data['customer_id'] = $order_info['customer_id'];
		} else {
			$this->data['customer_id'] = '';
		}
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($order_info)) {
			$this->data['customer_group_id'] = $order_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '';
		}
		
		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		if (isset($this->request->post['company'])) {
      		$this->data['company'] = $this->request->post['company'];
		} elseif (!empty($order_info)) { 
			$this->data['company'] = $order_info['company'];
		} else {
      		$this->data['company'] = '';
    	}
		
    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($order_info)) { 
			$this->data['email'] = $order_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
				
    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($order_info)) { 
			$this->data['telephone'] = $order_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}
		
    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($order_info)) { 
			$this->data['fax'] = $order_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}	
		
		if (isset($this->request->post['order_status_id'])) {
      		$this->data['order_status_id'] = $this->request->post['order_status_id'];
    	} elseif (!empty($order_info)) {
			$this->data['order_status_id'] = $order_info['order_status_id'];
		} else {
      		$this->data['order_status_id'] = 1;
    	}
			
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	
		
		$this->load->model('setting/extension');

		$this->data['shipping_option_codes'] = $this->model_sale_order->getOrderShippingCodes();
		
    	if (isset($this->request->post['comment'])) {
      		$this->data['comment'] = $this->request->post['comment'];
    	} elseif (!empty($order_info)) { 
			$this->data['comment'] = $order_info['comment'];
		} else {
      		$this->data['comment'] = '';
    	}	
		
		$this->load->model('sale/customer');

		if (isset($this->request->post['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
		} elseif (!empty($order_info)) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($order_info['customer_id']);
		} else {
			$this->data['addresses'] = array();
		}

    	if (isset($this->request->post['payment_company'])) {
      		$this->data['payment_company'] = $this->request->post['payment_company'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_company'] = $order_info['payment_company'];
		} else {
      		$this->data['payment_company'] = '';
    	}
		
    	if (isset($this->request->post['payment_company_id'])) {
      		$this->data['payment_company_id'] = $this->request->post['payment_company_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_company_id'] = $order_info['payment_company_id'];
		} else {
      		$this->data['payment_company_id'] = '';
    	}
		
    	if (isset($this->request->post['payment_address_1'])) {
      		$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_address_1'] = $order_info['payment_address_1'];
		} else {
      		$this->data['payment_address_1'] = '';
    	}

    	if (isset($this->request->post['payment_address_2'])) {
      		$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_address_2'] = $order_info['payment_address_2'];
		} else {
      		$this->data['payment_address_2'] = '';
    	}
		
    	if (isset($this->request->post['payment_city'])) {
      		$this->data['payment_city'] = $this->request->post['payment_city'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_city'] = $order_info['payment_city'];
		} else {
      		$this->data['payment_city'] = '';
    	}

    	if (isset($this->request->post['payment_postcode'])) {
      		$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_postcode'] = $order_info['payment_postcode'];
		} else {
      		$this->data['payment_postcode'] = '';
    	}
				
    	if (isset($this->request->post['payment_country_id'])) {
      		$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_country_id'] = $order_info['payment_country_id'];
		} else {
      		$this->data['payment_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['payment_zone_id'])) {
      		$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_zone_id'] = $order_info['payment_zone_id'];
		} else {
      		$this->data['payment_zone_id'] = '';
    	}
						
    	if (isset($this->request->post['payment_method'])) {
      		$this->data['payment_method'] = $this->request->post['payment_method'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_method'] = $order_info['payment_method'];
		} else {
      		$this->data['payment_method'] = '';
    	}
		
    	if (isset($this->request->post['payment_code'])) {
      		$this->data['payment_code'] = $this->request->post['payment_code'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_code'] = $order_info['payment_code'];
		} else {
      		$this->data['payment_code'] = '';
    	}			

    	if (isset($this->request->post['shipping_company'])) {
      		$this->data['shipping_company'] = $this->request->post['shipping_company'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_company'] = $order_info['shipping_company'];
		} else {
      		$this->data['shipping_company'] = '';
    	}

    	if (isset($this->request->post['shipping_address_1'])) {
      		$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
		} else {
      		$this->data['shipping_address_1'] = '';
    	}

    	if (isset($this->request->post['shipping_address_2'])) {
      		$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
		} else {
      		$this->data['shipping_address_2'] = '';
    	}
		
    	if (isset($this->request->post['shipping_city'])) {
      		$this->data['shipping_city'] = $this->request->post['shipping_city'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_city'] = $order_info['shipping_city'];
		} else {
      		$this->data['shipping_city'] = '';
    	}
		
    	if (isset($this->request->post['shipping_postcode'])) {
      		$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
		} else {
      		$this->data['shipping_postcode'] = '';
    	}
				
    	if (isset($this->request->post['shipping_country_id'])) {
      		$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
		} else {
      		$this->data['shipping_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['shipping_zone_id'])) {
      		$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
		} else {
      		$this->data['shipping_zone_id'] = '';
    	}	
						
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();															
		
    	if (isset($this->request->post['shipping_method'])) {
      		$this->data['shipping_method'] = $this->request->post['shipping_method'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_method'] = $order_info['shipping_method'];
		} else {
      		$this->data['shipping_method'] = '';
    	}	
		
    	if (isset($this->request->post['shipping_code'])) {
      		$this->data['shipping_code'] = $this->request->post['shipping_code'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_code'] = $order_info['shipping_code'];
		} else {
      		$this->data['shipping_code'] = '';
    	}

		if (isset($this->request->post['order_product'])) {
			$order_products = $this->request->post['order_product'];
		} elseif (isset($this->request->get['order_id'])) {
			$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);			
		} else {
			$order_products = array();
		}
		
		$this->load->model('catalog/product');
		
		//$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
		$this->data['order_products'] = array();		
		
		foreach ($order_products as $order_product) {
			if (isset($order_product['order_option'])) {
				$order_option = $order_product['order_option'];
			} elseif (isset($this->request->get['order_id'])) {
				$order_option = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
			} else {
				$order_option = array();
			}
											
			$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);

			$this->data['order_products'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'name'             => $order_product['name'],
				'model'            => $order_product['model'],
				'option'           => $order_option,
				'quantity'         => $order_product['quantity'],
				'price'			   => $this->currency->format($order_product['price'], $order_info['currency_code'], $order_info['currency_value'], true, true),
				'price_raw'         => number_format((float)$order_product['price'], 2, '.', ''),
				'catalog_price_raw' => $product_info ? number_format((float)$product_info['price'], 2, '.', '') : number_format((float)$order_product['price'], 2, '.', ''),
				'total'            => $this->currency->format($order_product['total'], $order_info['currency_code'], $order_info['currency_value'], true, true),
				'tax'              => $order_product['tax'],
				'discount_raw'     => (!empty($order_product['discount'])) ? number_format((float)preg_replace('/[^0-9\.]/', '', $order_product['discount']), 2, '.', '') : ''
			);
		}
		
		if (isset($this->request->post['order_total'])) {
      		$this->data['order_totals'] = $this->request->post['order_total'];
    	} elseif (isset($this->request->get['order_id'])) {
			$this->data['order_totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		} else {
      		$this->data['order_totals'] = array();
		}

		if (isset($this->request->post['global_discount'])) {
			$this->data['global_discount'] = $this->request->post['global_discount'];
		} else {
			$this->data['global_discount'] = '';

			foreach ($this->data['order_totals'] as $order_total) {
				if ($order_total['code'] == 'discount') {
					$this->data['global_discount'] = number_format(abs((float)$order_total['value']), 2, '.', '');
					break;
				}
			}
		}


		$this->load->model('localisation/payment');
		$this->load->model('localisation/shipping');

		$this->data['payments'] = $this->model_localisation_payment->getPayments();
		$this->data['shippings'] = $this->model_localisation_shipping->getShippings();
		
		$this->template = 'sale/order_form.tpl';

		$this->children = array(

			'common/header',

			'common/footer'

		);

		$this->response->setOutput($this->render());
  	}
	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$this->error['warning'] = $this->language->get('error_permission');
		}
		

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    
	
   	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	// Igual que ControllerSaleDraft::diffDraftProducts() (mismo criterio de emparejar
	// por posicion + product_id, no por order_product_id: el recalculo AJAX de
	// precio/cantidad reconstruye la tabla de lineas desde el carrito de sesion y lo
	// deja vacio en cada edicion).
	private function diffOrderProducts($old_products, $new_products) {
		$original = array();
		$changed  = array();

		$old_products = array_values($old_products);
		$new_products = array_values($new_products);

		foreach ($new_products as $i => $product) {
			if (!isset($old_products[$i]) || (int)$old_products[$i]['product_id'] !== (int)$product['product_id']) {
				continue;
			}

			$old   = $old_products[$i];
			$label = 'Producto: ' . $old['name'];

			$old_price = number_format((float)preg_replace('/[^-0-9\.]/', '', $old['price']), 2, '.', '');
			$new_price = number_format((float)preg_replace('/[^-0-9\.]/', '', $product['price']), 2, '.', '');

			if ($old_price !== $new_price) {
				$original[$label . ' > Precio'] = $old_price;
				$changed[$label . ' > Precio']  = $new_price;
			}

			$old_qty = (string)(int)$old['quantity'];
			$new_qty = (string)(int)$product['quantity'];

			if ($old_qty !== $new_qty) {
				$original[$label . ' > Cantidad'] = $old_qty;
				$changed[$label . ' > Cantidad']  = $new_qty;
			}
		}

		return array('original' => $original, 'changed' => $changed);
	}

	public function info() {
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$this->load->language('sale/order');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_order_id'] = $this->language->get('text_order_id');
			$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
			$this->data['text_store_name'] = $this->language->get('text_store_name');
			$this->data['text_store_url'] = $this->language->get('text_store_url');		
			$this->data['text_customer'] = $this->language->get('text_customer');
			$this->data['text_customer_group'] = $this->language->get('text_customer_group');
			$this->data['text_email'] = $this->language->get('text_email');
			$this->data['text_telephone'] = $this->language->get('text_telephone');
			$this->data['text_fax'] = $this->language->get('text_fax');
			$this->data['text_created_by'] = $this->language->get('text_created_by');
			$this->data['text_from_quote'] = $this->language->get('text_from_quote');

			$this->data['text_total'] = $this->language->get('text_total');
			$this->data['text_order_status'] = $this->language->get('text_order_status');
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
			$this->data['text_generate_delivery'] = $this->language->get('text_generate_delivery');
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
			$this->data['entry_order_id'] = $this->language->get('entry_order_id');
			// End add
			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_download'] = $this->language->get('column_download');
			$this->data['column_filename'] = $this->language->get('column_filename');
			$this->data['column_remaining'] = $this->language->get('column_remaining');
						
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['entry_notify'] = $this->language->get('entry_notify');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			
			$this->data['button_invoice'] = $this->language->get('button_invoice');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_history'] = $this->language->get('button_add_history');
			$this->data['button_generate'] = $this->language->get('button_generate');
		
			$this->data['tab_order'] = $this->language->get('tab_order');
			$this->data['tab_payment'] = $this->language->get('tab_payment');
			$this->data['tab_shipping'] = $this->language->get('tab_shipping');
			$this->data['tab_product'] = $this->language->get('tab_product');
			$this->data['tab_order_history'] = $this->language->get('tab_order_history');
			$this->data['tab_fraud'] = $this->language->get('tab_fraud');
			$this->data['tab_history'] = $this->language->get('tab_history');
		
			$this->data['token'] = $this->session->data['token'];

			$url = '';

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_company'])) {
				$url .= '&filter_company=' . urlencode(html_entity_decode($this->request->get['filter_company'], ENT_QUOTES, 'UTF-8'));
			}
												
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
				'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
				'separator' => ' :: '
			);

			$this->data['printPDF'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'] . '&format=pdf', 'SSL');
			$this->data['invoice'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'] . '&format=view', 'SSL');
			$this->data['sendEmail'] = $this->url->link('sale/order/email', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');
			$this->data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');

			// add print selection
			$this->data['print'] = $this->url->link('sale/order/document', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'], 'SSL');

			$reports = array_slice(scandir(DIR_TEMPLATE . 'sale/reports'), 2);

			$this->data['reports'] = array();

			foreach ($reports as $report) {
				$name = ucwords(str_replace('_', ' ', str_replace('.tpl', '', str_replace('_invoice', '', $report))));

				if (strpos($name, 'Order')!==FALSE) {
					$this->data['reports'][] = array(
						'name' => $name,
						'report' => $report
					);
				}
			}
			// end add
			
			$this->data['order_id'] = $this->request->get['order_id'];
			
			if ($order_info['invoice_no']) {
				$this->data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				$this->data['generate'] = $this->url->link('sale/delivery/info', 'token=' . $this->session->data['token'] . '&delivery_id=' . (int)$order_info['invoice_no'], 'SSL');
			} else {
				$this->data['invoice_no'] = '';
				$this->data['generate'] = $this->url->link('sale/order/createDelivery', 'token=' . $this->session->data['token'] . '&order_id=' . (int)$order_info['order_id'], 'SSL');
			}
			
			$this->data['store_name'] = $order_info['store_name'];
			$this->data['store_url'] = $order_info['store_url'];
						
			if ($order_info['customer_id']) {
				$this->data['customer'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], 'SSL');
			} else {
				$this->data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$this->data['customer_group'] = $customer_group_info['name'];
			} else {
				$this->data['customer_group'] = '';
			}

			$this->data['email'] = $order_info['email'];
			$this->data['telephone'] = $order_info['telephone'];
			$this->data['fax'] = $order_info['fax'];
			$this->data['created_by'] = $order_info['created_by'];

			$quote_query = $this->db->query("SELECT quote_id FROM `" . DB_PREFIX . "quote` WHERE invoice_no = '" . (int)$order_id . "'");

			if ($quote_query->num_rows) {
				$this->data['from_quote_id'] = $quote_query->row['quote_id'];
				$this->data['from_quote_href'] = $this->url->link('sale/quote/info', 'token=' . $this->session->data['token'] . '&quote_id=' . $quote_query->row['quote_id'], 'SSL');
			} else {
				$this->data['from_quote_id'] = '';
				$this->data['from_quote_href'] = '';
			}

			$this->data['company'] = $order_info['company'];
			$this->data['date_added'] = $order_info['date_added'];
			$this->data['date_modified'] = $order_info['date_modified'];
			$this->data['comment'] = nl2br($order_info['comment']);
			$this->data['shipping_method'] = $order_info['shipping_method'];
			$this->data['payment_method'] = $order_info['payment_method'];
			$this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], true, true);
			
			if ($order_info['total'] < 0) {
				$this->data['credit'] = $order_info['total'];
			} else {
				$this->data['credit'] = 0;
			}

			$this->data['credit_total'] = 0;

			$this->load->model('sale/customer');

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$this->data['order_status'] = $order_status_info['name'];
			} else {
				$this->data['order_status'] = '';
			}
			
			$this->data['date_added'] = date($this->language->get('date_format_short') . ' H:i', strtotime($order_info['date_added']));
			$this->data['date_modified'] = date($this->language->get('date_format_short') . ' H:i', strtotime($order_info['date_modified']));
			$this->data['payment_company'] = $order_info['payment_company'];
			$this->data['payment_company_id'] = $order_info['payment_company_id'];
			$this->data['payment_tax_id'] = $order_info['payment_tax_id'];
			$this->data['payment_address_1'] = $order_info['payment_address_1'];
			$this->data['payment_address_2'] = $order_info['payment_address_2'];
			$this->data['payment_city'] = $order_info['payment_city'];
			$this->data['payment_postcode'] = $order_info['payment_postcode'];
			$this->data['payment_zone'] = $order_info['payment_zone'];
			$this->data['payment_zone_code'] = $order_info['payment_zone_code'];
			$this->data['payment_country'] = $order_info['payment_country'];			
			$this->data['shipping_company'] = $order_info['shipping_company'];
			$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
			$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
			$this->data['shipping_city'] = $order_info['shipping_city'];
			$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
			$this->data['shipping_zone'] = $order_info['shipping_zone'];
			$this->data['shipping_zone_code'] = $order_info['shipping_zone_code'];
			$this->data['shipping_country'] = $order_info['shipping_country'];

			$this->data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
						'type'  => $option['type']
					);
				}

				$this->data['products'][] = array(
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'], '', '', true, true),
					'total'    		   => $this->currency->format($product['total'], '', '', true, true),
					'href'     		   => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}
		
			$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$this->data['order_status_id'] = $order_info['order_status_id'];

			$this->template = 'sale/order_info.tpl';
			
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
    	$this->language->load('sale/order');
		
		$this->data['error'] = '';
		$this->data['success'] = '';
		
		$this->load->model('sale/order');
	
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/order')) { 
				$this->data['error'] = $this->language->get('error_permission');
			}
			
			if (!$this->data['error']) { 
				$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);
				
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
			
		$results = $this->model_sale_order->getOrderHistories($this->request->get['order_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
        	$this->data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
        		'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
        	);
      	}			
		
		$history_total = $this->model_sale_order->getTotalOrderHistories($this->request->get['order_id']);
			
		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/order/history', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'sale/order_history.tpl';
		
		$this->response->setOutput($this->render());
  	}
			
  	public function document() {
		
		if (isset($this->request->get['format'])) {
			$lcFormat = $this->request->get['format'];
		} else {
			$lcFormat = '';
		}

		$this->data['lang'] = $this->config->get('config_language');
		$this->load->language('sale/order');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_order'] = $this->language->get('text_order');

		$this->data['text_order_id'] = $this->language->get('text_order_id');
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
		$this->data['column_discount'] = $this->language->get('column_discount');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$this->load->model('sale/order');

		$this->load->model('setting/setting');

		$this->data['orders'] = array();

		$invoices = array();

		if (isset($this->request->post['selected'])) {
			$invoices = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$invoices[] = $this->request->get['order_id'];
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

		if ($lcReport !== '' && (!preg_match('/^[A-Za-z0-9_-]+\.tpl$/', $lcReport) || !is_file(DIR_TEMPLATE . 'sale/reports/' . $lcReport))) {
			$lcReport = '';
		}
        // End add
	
		foreach ($invoices as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);
				
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

				// the address setting is a textarea, so drop trailing/blank lines that would print as empty rows
				$store_address = preg_replace("/[\r\n]+/", "\n", trim($store_address));

				//add
				$store_nif = $this->config->get('config_vat_id');

				if (!$store_nif) {
					$store_nif = $this->config->get('config_nif');
				}
				//end add

				// issuer postcode + town (town lives in the store's Region/State setting), shown on its own line under the street
				$this->load->model('localisation/zone');

				$store_zone = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));
				$store_postcode = (string)$this->config->get('config_postcode');

				if (preg_match('/^(\d{2})(\d{3})$/', $store_postcode, $postcode_match)) {
					$store_postcode = $postcode_match[1] . '.' . $postcode_match[2];
				}

				$store_locality = trim($store_postcode . ' ' . (!empty($store_zone['name']) ? mb_strtoupper($store_zone['name'], 'UTF-8') : ''));
				
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{postcode} {city}' . "\n" . '{country}';
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

				$shipping_company  = $order_info['shipping_company'];
				$shipping_postcode = $order_info['shipping_postcode'];

				if (preg_match('/^(\d{2})(\d{3})$/', $shipping_postcode, $postcode_match)) {
					$shipping_postcode = $postcode_match[1] . '.' . $postcode_match[2];
				}

				$replace = array(
					'company'   => '',
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => mb_strtoupper($order_info['shipping_city'], 'UTF-8'),
					'postcode'  => $shipping_postcode,
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				$shipping_address = preg_replace('#^(<br\s*/?>)+#', '', $shipping_address);

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
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

				$payment_company = $order_info['payment_company'];

				$replace = array(
					'company'   => '',
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				$payment_address = preg_replace('#^(<br\s*/?>)+#', '', $payment_address);

				// fall back to the customer's general info (General tab) when the order has no billing name/address/tax-id of its own
				$payment_tax_id = $order_info['payment_tax_id'];

				if ((!$payment_company || !$payment_address || !$payment_tax_id) && $order_info['customer_id']) {
					$this->load->model('sale/customer');
					$customer_general = $this->model_sale_customer->getCustomer($order_info['customer_id']);

					if (!empty($customer_general)) {
						if (!$payment_company && $customer_general['company']) {
							$payment_company = $customer_general['company'];
						}

						if (!$payment_address && $customer_general['address']) {
							$customer_postcode = $customer_general['postcode'];

							if (preg_match('/^(\d{2})(\d{3})$/', $customer_postcode, $postcode_match)) {
								$customer_postcode = $postcode_match[1] . '.' . $postcode_match[2];
							}

							$payment_address = trim($customer_general['address']) . '<br />' . trim($customer_postcode . ' ' . $customer_general['city']);
						}

						if (!$payment_tax_id) {
							$payment_tax_id = $customer_general['nif'];
						}
					}
				}

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

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
						'price'    => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], true, true),
						'discount' => (!empty($product['discount'])) ? $this->currency->format($product['discount'], $order_info['currency_code'], $order_info['currency_value'], true, true) : '',
						'total'    => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value'], true, true)
					);
				}
				
				$total_data = $this->model_sale_order->getOrderTotals($order_id);
				
				$this->data['orders'][] = array(
					'order_id'	         => $order_id,
					'invoice_no'         => $invoice_no,
					'invoice_prefix'     => $order_info['invoice_prefix'],
					'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_locality'     => $store_locality,
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'store_nif'          => $store_nif,
					'email'              => $order_info['email'],
					'vat_id'             => '',
					'name_ext' 			 => '',
					'telephone'          => $order_info['telephone'],
					'shipping_company'   => $shipping_company,
					'shipping_address'   => $shipping_address,
					'payment_company'    => $payment_company,
					'payment_address'    => $payment_address,
					'payment_company_id' => $order_info['payment_company_id'],
					'payment_tax_id'     => $payment_tax_id,
					'payment_method'     => $order_info['payment_method'],
					'shipping_method'    => $order_info['shipping_method'],
					'product'            => $product_data,
					'total'              => $total_data,
					'comment'            => nl2br($order_info['comment'])
				);
			}
		}

		$this->data['logo'] = $this->config->get('config_logo');

		if ($lcReport == 'order_invoice_without_price.tpl') {
			$pdf_template = 'sale/order_printPDF_without_price.tpl';
		} else {
			$pdf_template = 'sale/order_printPDF.tpl';
		}

		$this->load->model('tools/report_designer');

		if (isset($this->request->get['preview_report_format_id'])) {
			$custom_html = $this->model_tools_report_designer->getPreviewHtml((int)$this->request->get['preview_report_format_id'], 'order', $this->data);
		} else {
			$custom_html = $this->model_tools_report_designer->getRenderableCustomHtml('order', $this->data);
		}

		if ($lcFormat=='pdf') {
			if ($custom_html !== false) {
				$this->renderPDFFromHtml($custom_html, 'pdf', 'order', $order_id);
			} else {
				$this->renderPDF($pdf_template, 'pdf', 'order', $order_id);
			}
		} elseif ($lcFormat=='email') {
			if ($custom_html !== false) {
				$this->renderPDFFromHtml($custom_html, 'email', 'order', $order_id);
			} else {
				$this->renderPDF($pdf_template, 'email', 'order', $order_id);
			}

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
				$data['customer_id'] = $order_info['customer_id'];
				$data['potential_id'] = $order_info['potential_id'];
				$data['supplier_id'] = $order_info['supplier_id'];
				
				$data['order_id'] = $this->request->get['order_id'];
				
				$data['to'] = $this->request->post['to'];
				$data['subject'] = $this->request->post['subject'];
				$data['text'] = $this->request->post['message'];
				$data['code'] = md5($this->request->post['message']);
				
				$data['file'] = DIR_DOWNLOAD . 'invoice_' . $order_id . '.pdf';

				$mail_error = $this->sendnewmail($data['to'], $data['subject'], $data['text'], $data['file']);

				if ($mail_error) {
					$json['error']['message'] = $mail_error;
				} else {
					$this->load->model('catalog/mail');

					$this->model_catalog_mail->addMailSended($data);

					$json['success'] = $this->language->get('text_success_email');
				}
			}

			$this->response->setOutput(json_encode($json));
		} else {
			if ($lcReport=='') {
				$this->template = 'sale/order_invoice.tpl';
			} else {
				$this->template = 'sale/reports/' . $lcReport;
			}
			
			$this->response->setOutput($this->render());
		}
	}

	public function checkOrder() {
		$this->load->language('sale/order');

		$json = array();

		if ($this->user->hasPermission('modify', 'sale/order')) {

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
			if (isset($this->request->post['order_product'])) {
				foreach ($this->request->post['order_product'] as $order_product) {
					$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);
					$option_data = array();

					if (isset($order_product['order_option'])) {
						foreach ($order_product['order_option'] as $option) {
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
						$use_price = (isset($order_product['price']) && $order_product['price'] !== '')
							? (float)preg_replace('/[^-0-9\.]/', '', $order_product['price'])
							: (float)$product_info['price'];

						$use_name = (isset($order_product['name']) && trim($order_product['name']) !== '')
							? $order_product['name']
							: $product_info['name'];

						$discount_percent = isset($order_product['discount']) ? (float)preg_replace('/[^0-9\.]/', '', $order_product['discount']) : 0;
						$discount_amount = ($use_price * $order_product['quantity']) * ($discount_percent / 100);

						$this->session->data['cart'][] = array(
							'product_id' => $product_info['product_id'],
							'name'		 => $use_name,
							'model'		 => $product_info['model'],
							'quantity' 	 => $order_product['quantity'],
							'option'	 => $option_data,
							'price'		 => $use_price,
							'catalog_price' => $product_info['price'],
							'tax_class_id'=> $product_info['tax_class_id'],
							'total'		 => ($use_price*$order_product['quantity']) - $discount_amount,
							'shipping'	 => $product_info['shipping'],
							'discount'   => $discount_percent
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
						$use_price = (isset($this->request->post['price_override']) && $this->request->post['price_override'] !== '')
							? (float)$this->request->post['price_override']
							: (float)$product_info['price'];

						$discount_percent = isset($this->request->post['discount']) ? (float)preg_replace('/[^0-9\.]/', '', $this->request->post['discount']) : 0;
						$discount_amount = ($use_price * $quantity) * ($discount_percent / 100);

						$this->session->data['cart'][] = array(
							'product_id' 	=> $this->request->post['product_id'],
							'name'		 	=> $product_info['name'],
							'model'		 	=> $product_info['model'],
							'quantity' 	 	=> $quantity,
							'option' 	 	=> $option,
							'price'		 	=> $use_price,
							'tax_class_id'	=> $product_info['tax_class_id'],
							'total'		 	=> ($use_price * $quantity) - $discount_amount,
							'shipping'	 	=> $product_info['shipping'],
							'discount'		=> $discount_percent
						);

					}
				} else {
					$json['error']['product']['not_found'] = $this->language->get('error_action');
				}
			}

			// Products
			$json['order_product'] = array();
			
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
				
				$json['order_product'][] = array(
					'product_id' 	=> $product['product_id'],
					'name'       	=> $product['name'],
					'model'      	=> $product['model'],
					'quantity'   	=> $product['quantity'],
					'option'   		=> $option,
					'price'      	=> $this->currency->format($product['price'], '', '', true, true),
					'price_raw'         => number_format((float)$product['price'], 2, '.', ''),
					'catalog_price_raw' => number_format((float)(isset($product['catalog_price']) ? $product['catalog_price'] : $product['price']), 2, '.', ''),
					'tax_class_id'	=> $product['tax_class_id'],
					'total'      	=> $this->currency->format($product['total'], '', '', true, true),
					'discount'      => (!empty($product['discount'])) ? number_format((float)$product['discount'], 2, '.', '') : ''
				);
			}

			// Totals
			$json['order_total'] = array();					
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

					$this->{'model_total_' . $result['code']}->getTotal($json['order_total'], $total, $taxes);
				}

				$sort_order = array(); 

				foreach ($json['order_total'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['order_total']);				
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

		// VAT sobre el sub-total (neto de descuento de línea + descuento global),
		// no sobre el precio unitario de catálogo.
		$global_discount_percent = isset($this->request->post['global_discount']) ? (float)preg_replace('/[^0-9.]/', '', $this->request->post['global_discount']) : 0;

		foreach ($data as $product) {
			if ($product['tax_class_id']!=0) {
				$unit_price_after_discount = $product['quantity'] ? ($product['total'] / $product['quantity']) : $product['price'];
				$unit_price_for_tax = $unit_price_after_discount * (1 - ($global_discount_percent / 100));

				$tax_rates = $this->model_catalog_product->getProductRates($unit_price_for_tax, $product['tax_class_id']);

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

	public function createDelivery() {
		$order_id = $this->request->get['order_id'];

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$order_products = $this->model_sale_order->getOrderProducts($order_id);

			$delivery_products = array();

			foreach ($order_products as $product) {
				$order_option = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

				$delivery_products[] = array(
					'delivery_product_id' 	=> $product['order_product_id'],
					'product_id' 			=> $product['product_id'],
					'name' 					=> $product['name'],
					'model' 				=> $product['model'],
					'delivery_option' 		=> $order_option,
					'quantity'			 	=> $product['quantity'],
					'price'				 	=> $product['price'],
					'discount'				=> $product['discount'],
					'total'				 	=> $product['total'],
					'tax' 					=> $product['tax'],
					'reward	'				=> $product['reward']
				);
			}

			$delivery_totals = $this->model_sale_order->getOrderTotals($order_id);

			$data = array(
				'invoice_no'	=> $order_info['invoice_no'],
				'invoice_prefix'	=> $order_info['invoice_prefix'],
				'store_id'	=> $order_info['store_id'],
				'store_name'	=> $order_info['store_name'],
				'store_url'	=> $order_info['store_url'],
				'customer_id'	=> $order_info['customer_id'],
				'customer_group_id'	=> $order_info['customer_group_id'],
				'email'	=> $order_info['email'],
				'telephone'	=> $order_info['telephone'],
				'fax'	=> $order_info['fax'],
				'payment_company'	=> $order_info['payment_company'],
				'payment_company_id'	=> $order_info['payment_company_id'],
				'payment_tax_id'	=> $order_info['payment_tax_id'],
				'payment_address_1'	=> $order_info['payment_address_1'],
				'payment_address_2'	=> $order_info['payment_address_2'],
				'payment_city'	=> $order_info['payment_city'],
				'payment_postcode'	=> $order_info['payment_postcode'],
				'payment_country'	=> $order_info['payment_country'],
				'payment_country_id'	=> $order_info['payment_country_id'],
				'payment_zone'	=> $order_info['payment_zone'],
				'payment_zone_id'	=> $order_info['payment_zone_id'],
				'payment_address_format'	=> $order_info['payment_address_format'],
				'payment_method'	=> $order_info['payment_method'],
				'payment_code'	=> $order_info['payment_code'],
				'shipping_company'	=> $order_info['shipping_company'],
				'shipping_address_1'	=> $order_info['shipping_address_1'],
				'shipping_address_2'	=> $order_info['shipping_address_2'],
				'shipping_city'	=> $order_info['shipping_city'],
				'shipping_postcode'	=> $order_info['shipping_postcode'],
				'shipping_country'	=> $order_info['shipping_country'],
				'shipping_country_id'	=> $order_info['shipping_country_id'],
				'shipping_zone'	=> $order_info['shipping_zone'],
				'shipping_zone_id'	=> $order_info['shipping_zone_id'],
				'shipping_address_format'	=> $order_info['shipping_address_format'],
				'shipping_method'	=> $order_info['shipping_method'],
				'shipping_code'	=> $order_info['shipping_code'],
				'comment'	=> $order_info['comment'],
				'total'	=> $order_info['total'],
				'delivery_status_id'	=> $order_info['order_status_id'],
				'commission'	=> $order_info['commission'],
				'language_id'	=> $order_info['language_id'],
				'currency_id'	=> $order_info['currency_id'],
				'currency_code'	=> $order_info['currency_code'],
				'currency_value'	=> $order_info['currency_value'],
				'date_added'	=> $order_info['date_added'],
				'date_modified'	=> $order_info['date_modified'],
				'delivery_product' => $delivery_products,
				'delivery_total' => $delivery_totals
			);
			
			$this->load->model('sale/delivery');

			$this->model_sale_delivery->addDelivery($data);

			$query = $this->db->query("SELECT delivery_id, invoice_prefix FROM `" . DB_PREFIX . "delivery` ORDER BY delivery_id DESC LIMIT 1");

			$invoice_no = $query->row['delivery_id'];
			$invoice_prefix = $query->row['invoice_prefix'];

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = " . (int)$invoice_no . " WHERE order_id = " . (int)$order_id);

			$this->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, 'SSL'));
		}
	}
}
?>