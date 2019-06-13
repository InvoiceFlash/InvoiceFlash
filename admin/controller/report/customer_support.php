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

		if (isset($this->request->get['filter_status_id'])) {
			$filter_status_id = $this->request->get['filter_status_id'];
		} else {
			$filter_status_id = '';
		}
				
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

		if (isset($this->request->get['filter_status_id'])) {
			$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
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
			'filter_status_id'	     => $filter_status_id, 
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

			if ($result['dfinsoport']=='0000-00-00' || $result['dfinsoport']==null) {
				$dfinsoport = '';
			} else {
				$dfinsoport = date($this->language->get('date_format_short'), strtotime($result['dfinsoport']));
			}

			$this->data['customers'][] = array(
				'customer'          => $result['company'],
				'city'              => $result['city'],
				'date_added'        => $dfinsoport,
				'email'             => $result['email'],
				'telephone'         => $result['telephone'],
				'status'            => $result['status'] ,
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], 'SSL'),
				'action'            => $action
			);
		}
		
		$this->data['print_list'] = $this->url->link('report/customer_support/printList', 'token=' . $this->session->data['token'] . $url, 'SSL');
		 
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
		$this->data['button_clear'] = $this->language->get('button_clear');
		$this->data['button_print'] = $this->language->get('button_print');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/contract');
		
		$this->data['statuses'] = array();

		$results = $this->model_localisation_contract->getContracts();

		foreach ($results as $status) {
			$this->data['statuses'][] = array(
				'status_id' => $status['contract_status_id'],
				'name' => $status['name']
			);
		}
			
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_status_id'])) {
			$url .= '&filter_status_id=' . $this->request->get['filter_status_id'];
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
		$this->data['filter_status_id'] = $filter_status_id;		
				 
		$this->template = 'report/customer_support.tpl';
		 
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		
	}

	public function printList() {
		$this->load->language('report/customer_support');

		$this->load->model('report/customer');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		if (isset($this->request->get['filter_date_start'])) {
			$this->data['filter_date_start'] = $this->request->get['filter_date_start'];
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$this->data['filter_date_end'] = $this->request->get['filter_date_end'];
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
		if (isset($this->request->get['filter_status_id'])) {
			$filter_status_id = $this->request->get['filter_status_id'];
			$this->data['filter_status_id'] = $filter_status_id;
			$status_name = $this->model_report_customer->getContractStatusName($filter_status_id);
		} else {
			$filter_status_id = '';
			$status_name = '';
		}
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_status_id'	     => $filter_status_id
		);

		$this->data['contracts'] = array();

		$contracts = $this->model_report_customer->getSupport($data);

		foreach ($contracts as $contract) {
			if ($contract['dfinsoport']=='0000-00-00' || $contract['dfinsoport']==null) {
				$dfinsoport = '';
			} else {
				$dfinsoport = date($this->language->get('date_format_short'), strtotime($contract['dfinsoport']));
			}

			$this->data['contracts'][] = array(
				'customer'          => $contract['company'],
				'city'              => $contract['city'],
				'date'       		=> $dfinsoport,
				'email'             => $contract['email'],
				'telephone'         => $contract['telephone']
			);
		}
		
		$this->data['heading_title_print'] = $this->language->get('heading_title_print');

		$this->data['column_date'] = $this->language->get('column_date_added');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_city'] = $this->language->get('column_city');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_telephone'] = $this->language->get('column_phone');

		$this->data['text_date_start'] = sprintf($this->language->get('text_date_start'), $filter_date_start);
		$this->data['text_date_end'] = sprintf($this->language->get('text_date_end'), $filter_date_end);
		$this->data['text_status_id'] = sprintf($this->language->get('text_status_id'), $status_name);

		$this->data['text_today'] = date($this->language->get('date_format_long'));

		$this->template = 'report/customer_support_print.tpl';
		
		$this->response->setOutput($this->render());
	}
}
?>