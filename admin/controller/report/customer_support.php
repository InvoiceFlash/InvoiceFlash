<?php
class ControllerReportCustomerSupport extends Controller {
	public function index() {     
		$this->load->language('report/customer_support');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
        if (isset($this->request->get['uiemail'])) {
			$uiemail = $this->request->get['uiemail'];
		} else {
			$uiemail = '';
		}

        if (isset($this->request->get['uisubject'])) {
			$uisubject = $this->request->get['uisubject'];
		} else {
			$uisubject = '';
		}
        
        if (isset($this->request->get['uitext'])) {
			$uitext = $this->request->get['uitext'];
		} else {
			$uitext = '';
		}
        //End add
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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
			'href'      => $this->url->link('report/customer_support', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/customer');
		
		$this->data['customers'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$order_total = $this->model_report_customer->getTotalSupport($data);
		$results = $this->model_report_customer->getSupport($data);
		
		foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, 'SSL')
			);

			if ($result['date_support']=='0000-00-00' || $result['date_support']==null) {
				$date_support = '';
			} else {
				$date_support = date($this->language->get('date_format_short'), strtotime($result['date_support']));
			}

			$this->data['customers'][] = array(
				'customer'          => $result['company'],
				'city'              => $result['city'],
				'date_added'        => $date_support,
				'email'             => $result['email'],
				'telephone'         => $result['telephone'],
				'status'            => $result['status'] ,
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL'),
				'action'            => $action
			);
		}
		
		
		 
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		// $this->data['text_print'] = $this->language->get('text_print');
		
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_address'] = $this->language->get('column_address');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_state'] = $this->language->get('column_state');
		$this->data['column_orderid'] = $this->language->get('column_orderid');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_phone'] = $this->language->get('column_phone');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_products'] = $this->language->get('column_products');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_pdtname'] = $this->language->get('column_pdtname');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_options'] = $this->language->get('column_options');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['text_all_status'] = $this->language->get('text_all_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/invoice_status');
		
		$this->data['order_statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();
			
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/customer_support', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
				
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
				 
		$this->template = 'report/customer_support.tpl';
		
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		
	}
}
?>