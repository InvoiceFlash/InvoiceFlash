<?php
class ControllerSaleReceipt extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/receipt');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/receipt');

    	$this->getList();
  	}

  	public function update() {
  		$this->language->load('sale/receipt');

  		$this->document->setTitle($this->language->get('heading_title'));

  		$this->load->model('sale/receipt');

  		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
  			$this->model_sale_receipt->editReceipt($this->request->get['receipt_id'], $this->request->post);

  			$this->session->data['success'] = $this->language->get('text_success');

  			$url = '';

  			if (isset($this->request->get['filter_receipt_id'])) {
  				$url .= '&filter_receipt_id=' . $this->request->get['filter_receipt_id'];
  			}
			
  			if (isset($this->request->get['filter_remittance_id'])) {
  				$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
  			}
			
			if (isset($this->request->get['filter_invoice_id'])) {
				$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
			}
			
			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . $this->request->get['filter_customer'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['filter_date_due'])) {
				$url .= '&filter_date_due=' . $this->request->get['filter_date_due'];
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

			$this->redirect($this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, 'SSL'));
  		}

  		$this->getForm();
  	}

  	private function getList() {
		if (isset($this->request->get['filter_receipt_id'])) {
			$filter_receipt_id = $this->request->get['filter_receipt_id'];
		} else {
			$filter_receipt_id = null;
		}
		
		if (isset($this->request->get['filter_remittance_id'])) {
			$filter_remittance_id = $this->request->get['filter_remittance_id'];
		} else {
			$filter_remittance_id = null;
		}
		
		if (isset($this->request->get['filter_invoice_id'])) {
			$filter_invoice_id = $this->request->get['filter_invoice_id'];
		} else {
			$filter_invoice_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		
		if (isset($this->request->get['filter_date_due'])) {
			$filter_date_due = $this->request->get['filter_date_due'];
		} else {
			$filter_date_due = null;
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.date_due';
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

		if (isset($this->request->get['filter_receipt_id'])) {
			$url .= '&filter_receipt_id=' . $this->request->get['filter_receipt_id'];
		}
		
		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}
		
		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if (isset($this->request->get['filter_date_due'])) {
			$url .= '&filter_date_due=' . $this->request->get['filter_date_due'];
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
			'href'      => $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['invoice'] = $this->url->link('sale/receipt/generate', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['receipts'] = array();

		$data = array(
			'filter_receipt_id'         => $filter_receipt_id,
			'filter_remittance_id'      => $filter_remittance_id,
			'filter_invoice_id'	     	=> $filter_invoice_id,
			'filter_customer' 			=> $filter_customer,
			'filter_status'  			=> $filter_status,
			'filter_total'      	 	=> $filter_total,
			'filter_date_due'   	 	=> $filter_date_due,
			'filter_date_modified'   	=> $filter_date_modified,
			'sort'                   	=> $sort,
			'order'                  	=> $order,
			'start'                  	=> ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  	=> $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_sale_receipt->getTotalReceipts($data);

		$results = $this->model_sale_receipt->getReceipts($data);

    	foreach ($results as $result) {
    		$action = array();

    		$action[] = array(
    			'text' => $this->language->get('text_edit'),
    			'href' => $this->url->link('sale/receipt/update', 'token=' . $this->request->get['token'] . '&receipt_id=' . $result['receipt_id'], 'SSL')
    		);
					
			$this->data['receipts'][] = array(
				'receipt_id'    => $result['receipt_id'],
				'remittance_id' => ($result['remittance_id']==0) ? 'No generated' : $result['remittance_id'],
				'invoice_id'    => $result['invoice_id'],
				'customer'      => $result['customer'],
				'status'        => $result['paid'],
				'total'         => $this->currency->format($result['amount'], $result['currency_code'], $result['currency_value']),
				'date_due'    => date($this->language->get('date_format_short'), strtotime($result['date_due'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['receipt_id'], $this->request->post['selected']),
				'action'		=> $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');
		$this->data['text_paid'] = $this->language->get('text_paid');
		$this->data['text_pending'] = $this->language->get('text_pending');

		$this->data['column_receipt_id'] = $this->language->get('column_receipt_id');
		$this->data['column_remittance_id'] = $this->language->get('column_remittance_id');
		$this->data['column_invoice_id'] = $this->language->get('column_invoice_id');
    	$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_due'] = $this->language->get('column_date_due');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_remittances'] = $this->language->get('button_remittances');
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

		if (isset($this->request->get['filter_receipt_id'])) {
			$url .= '&filter_receipt_id=' . $this->request->get['filter_receipt_id'];
		}
		
		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}
		
		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . urlencode(html_entity_decode($this->request->get['filter_invoice_id'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
					
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if (isset($this->request->get['filter_date_due'])) {
			$url .= '&filter_date_due=' . $this->request->get['filter_date_due'];
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

		$this->data['sort_receipt'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=o.receipt_id' . $url, 'SSL');
		$this->data['sort_remittance'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=o.remittance_id' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=o.invoice_id' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_due'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=o.date_due' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_receipt_id'])) {
			$url .= '&filter_receipt_id=' . $this->request->get['filter_receipt_id'];
		}
		
		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}
		
		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_due'])) {
			$url .= '&filter_date_due=' . $this->request->get['filter_date_due'];
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
		$pagination->url = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_receipt_id'] = $filter_receipt_id;
		$this->data['filter_remittance_id'] = $filter_remittance_id;
		$this->data['filter_invoice_id'] = $filter_invoice_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_due'] = $filter_date_due;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->data['order_statuses'][] = array(
			"order_status_id"  => 1,
			"name" => 'Pendiente',
		);

		$this->data['order_statuses'][] = array(
			"order_status_id"  => 2 ,
			"name" => 'Pagado',
		);
		
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/receipt_list.tpl';

		$this->children = array(

			'common/header',

			'common/footer'

		);

		$this->response->setOutput($this->render());
  	}
		
  	public function generate() {

		$this->load->language('sale/receipt');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/receipt');

		if ($this->request->post['selected']) {
			// Recogemos los receipts seleccionados por el usuario
			$receipts = $this->request->post['selected'];
			$continue = true; // controlador para seguir o no con la generaciom
			$mensaje = '';  // Mensaje de error en caso necesario
			$ammount = 0; // variable para calcular la cantidad total
			$remittance_id = 0; // id de la remesa creada
			
			// Comprobamos que ninguno haya sido generado anteriormente
			foreach ($receipts as $receipt_id) {
				if (!$this->model_sale_receipt->checknoremittance($receipt_id)) {
					$mensaje = "Error: The receipt ID " . $receipt_id . " has already been generated.";
					$continue = false;
				} else {
					$ammount += $this->model_sale_receipt->getReceiptAmmount($receipt_id);
					$continue = true;
				}
			}

			// Si no ha habido error, generamos la remesa
			if ($continue) {
				$remittance_id = $this->model_sale_receipt->generateRemittance($ammount);

				// Generamos una linea de la remesa por recibo seleccionado
				foreach ($receipts as $receipt_id) {
					$this->model_sale_receipt->generateRemittance_lines($remittance_id,$receipt_id,$this->model_sale_receipt->getReceiptAmmount($receipt_id));
				}
			}

		} else {
			$mensaje = 'Error: Select at least one receipt';
		}

		// Mostramos el error, en caso que lo haya
		$this->error['warning'] = $mensaje;

		$this->getList();
		
	}

	public function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
		
	}

	public function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->request->get['token'];

		if ($this->request->get['receipt_id']) {
			$this->data['receipt_id'] = $this->request->get['receipt_id'];
		} else {
			$this->data['receipt_id'] = 0;
		}

		$url = '';
		
		if (isset($this->request->get['filter_receipt_id'])) {
			$url .= '&filter_receipt_id=' . $this->request->get['filter_receipt_id'];
		}
		
		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}
		
		if (isset($this->request->get['filter_invoice_id'])) {
			$url .= '&filter_invoice_id=' . $this->request->get['filter_invoice_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
											
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_due'])) {
			$url .= '&filter_date_due=' . $this->request->get['filter_date_due'];
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

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['action'] = $this->url->link('sale/receipt/update', 'token=' . $this->session->data['token'] . '&receipt_id=' . $this->request->get['receipt_id'] . $url, 'SSL');

   		$this->data['cancel'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'] . $url, 'SSL');

   		if (isset($this->request->get['receipt_id'])) {
   			$receipt_info = $this->model_sale_receipt->getReceipt($this->request->get['receipt_id']);
   		}

   		if (isset($this->request->post['status_id'])) {
			$this->data['status_id'] = $this->request->post['status_id'];
		} elseif (!empty($receipt_info)) {
			$this->data['status_id'] = $receipt_info['status_id'];
		} else {
			$this->data['status_id'] = 0;
		}

		$this->data['statuses'] = array();

		$this->data['statuses'][] = array(
			"status_id"  => 1,
			"name" => 'Pendiente',
		);

		$this->data['statuses'][] = array(
			"status_id"  => 2 ,
			"name" => 'Pagado',
		);

		$this->template = 'sale/receipt_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

}
?>