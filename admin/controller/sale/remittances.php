<?php
class ControllerSaleRemittances extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/remittances');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/remittances');

    	$this->getList();
	}

	public function delete() {
		$this->load->language('sale/remittances');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/remittances');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $remittance_id) {
				$this->model_sale_remittances->deleteRemittance($remittance_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_remittance_id'])) {
				$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
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

			$this->redirect($this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}

	public function getList() {
		if (isset($this->request->get['filter_remittance_id'])) {
			$filter_remittance_id = $this->request->get['filter_remittance_id'];
		} else {
			$filter_remittance_id = null;
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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.remittance_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->requeset->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';

		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			'text'		=> $this->language->get('text_home'),
			'href'		=> $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('heading_title'),
			'href'		=> $this->url->link('sale/remittances', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['printRemittances'] = $this->url->link('sale/remittances/printRemittances', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('sale/remittances/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['remittances'] = array();

		$data = array(
			'filter_remittance_id' 	=> $filter_remittance_id,
			'filter_total' 			=> $filter_total,
			'filter_date_added' 	=> $filter_date_added,
			'sort' 					=> $sort,
			'order' 				=> $order,
			'page'					=> $page
		);

		$remittance_total = $this->model_sale_remittances->getTotalRemittances($data);

		$results = $this->model_sale_remittances->getRemittances($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text'	=> $this->language->get('text_view'),
				'href'  => $this->url->link('sale/remittances/info', 'token=' . $this->session->data['token'] . '&remittance_id=' . $result['remittance_id'] . $url, 'SSL')
			);

			$this->data['remittances'][] = array(
				'remittance_id'		=> $result['remittance_id'],
				'total'				=> $this->currency->format($result['total']),
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'			=> isset($this->request->post['selected']) && in_array($result['remittance_id'], $this->request->post['selected']),
				'action'			=> $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_select_one'] = $this->language->get('text_select_one');
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['column_remittance_id'] = $this->language->get('column_remittance_id');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_remittance'] = $this->language->get('button_remittance');
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

		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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

		$this->data['sort_remittance_id'] = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . '&sort=r.remittance_id' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . '&sort=r.amount' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
		$pagination->total = $remittance_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_remittance_id'] = $filter_remittance_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;
		
		$this->load->model('localisation/invoice_status');

		$this->data['statuses'] = $this->model_localisation_invoice_status->getInvoiceStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/remittances_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	public function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
	}

	public function info() {
		$this->load->model('sale/remittances');

		if (isset($this->request->get['remittance_id'])) {
			$remittance_id = $this->request->get['remittance_id'];
		} else {
			$remittance_id = 0;
		}

		$this->data['remittances_lines'] = array();
		
		$lineas = $this->model_sale_remittances->getRemittancesLines($remittance_id);
		
		foreach ($lineas as $line) {
	
			$this->data['remittances_lines'][] = array(
					'receipt_id'   => $line['receipt_id'],
					'customer'     => $line['company'],
					'date_vto'     => date($this->language->get('date_format_short'), strtotime($line['date_vto'])),
					'amount'       => $this->currency->format($line['amount'])
			);
		}

		$this->load->language('sale/remittances');

		$this->document->setTitle($this->language->get('heading_title_lines'));

		$this->data['heading_title'] = $this->language->get('heading_title_lines');
			
		$this->data['text_remittance_id'] = $this->language->get('text_remittance_id');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_store_name'] = $this->language->get('text_store_name');
		$this->data['text_store_url'] = $this->language->get('text_store_url');		
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_email'] = $this->language->get('text_email');

		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_reward'] = $this->language->get('text_reward');		
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_commission'] = $this->language->get('text_commission');
		$this->data['text_ip'] = $this->language->get('text_ip');
		$this->data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
		$this->data['text_user_agent'] = $this->language->get('text_user_agent');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_generate'] = $this->language->get('text_generate');
		$this->data['text_ip_latitude'] = $this->language->get('text_ip_latitude');
		$this->data['text_ip_longitude'] = $this->language->get('text_ip_longitude');
		$this->data['text_ip_isp'] = $this->language->get('text_ip_isp');
		$this->data['text_ip_org'] = $this->language->get('text_ip_org');
		$this->data['text_ip_asnum'] = $this->language->get('text_ip_asnum');
		$this->data['text_ip_user_type'] = $this->language->get('text_ip_user_type');
		$this->data['text_risk_score'] = $this->language->get('text_risk_score');
		$this->data['text_queries_remaining'] = $this->language->get('text_queries_remaining');
		$this->data['text_maxmind_id'] = $this->language->get('text_maxmind_id');
		$this->data['text_error'] = $this->language->get('text_error');
						
		$this->data['column_remittance'] = $this->language->get('column_remittance');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_amount'] = $this->language->get('column_amount');
		$this->data['column_date'] = $this->language->get('column_date');
					
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		
		$this->data['button_cancel'] = $this->language->get('button_cancel');
				
		$this->data['tab_order'] = $this->language->get('tab_order');
		$this->data['tab_payment'] = $this->language->get('tab_payment');
		$this->data['tab_shipping'] = $this->language->get('tab_shipping');
		$this->data['tab_product'] = $this->language->get('tab_product');
		$this->data['tab_order_history'] = $this->language->get('tab_order_history');
		$this->data['tab_fraud'] = $this->language->get('tab_fraud');
	
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

		if (isset($this->request->get['filter_remittance_id'])) {
			$url .= '&filter_remittance_id=' . $this->request->get['filter_remittance_id'];
			$filter_remittance_id = $this->request->get['filter_remittance_id'];
		} else {
			$filter_remittance_id = '';
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('text_home'),
			'href'		=> $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'		=> $this->language->get('heading_title'),
			'href'		=> $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['cancel'] = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['remittance_id'] = $this->request->get['remittance_id'];

		$remittances_lines_total = $this->model_sale_remittances->getRemittanceLinesTotal($remittance_id);
			
		$pagination = new Pagination();
		$pagination->total = $remittances_lines_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/remittances_lines.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
	}

	public function printRemittances() {
		$this->load->language('sale/remittances');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_remittance_id'] = $this->language->get('text_remittance_id');
		$this->data['text_remittance'] = $this->language->get('heading_title');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_date'] = $this->language->get('text_date');
		$this->data['text_iban'] = $this->language->get('text_iban');
		$this->data['text_bic'] = $this->language->get('text_bic');

		$this->data['column_customer_id'] = $this->language->get('column_customer_id');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_bank_cc'] = $this->language->get('column_bank_cc');
		$this->data['column_amount'] = $this->language->get('column_amount');
		$this->data['column_date_due'] = $this->language->get('column_date_due');
		$this->data['column_invoice_no'] = $this->language->get('column_invoice_no');

		$this->load->model('sale/remittances');

		$this->load->model('setting/setting');

		$this->load->model('tool/image');

		$logo = $this->config->get('config_logo');

		if (isset($logo) && file_exists(DIR_IMAGE . $logo)) {
			$this->data['logo'] = $this->model_tool_image->resize($logo, 100, 100);
		} else {
			$this->data['logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		$this->data['remittances'] = array();

		$remittances = array();

		if (isset($this->request->post['selected'])) {
			$remittances = $this->request->post['selected'];
		} elseif (isset($this->request->get['remittance_id'])) {
			$remittances[] = $this->request->get['remittance_id'];
		}

		$remittances = array_unique($remittances);

		foreach ($remittances as $remittance_id) {
			$remittance_info = $this->model_sale_remittances->getRemittance($remittance_id);

			if ($remittance_info) {
				$store_info = $this->model_setting_setting->getSetting('config');
				
				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
				}

				$store_name = $this->config->get('config_title');
				$store_nif = $this->config->get('config_nif');

				$bic = $this->config->get('bic');
				$iban = $this->config->get('iban');

				$remittance_lines = array();

				$lines = $this->model_sale_remittances->getRemittancesLines($remittance_id);

				foreach ($lines as $line) {
					$remittance_lines[] = array(
						'customer_id'	=> $line['customer_id'],
						'customer'		=> $line['company'],
						'date_due'		=> date($this->language->get('date_format_short'), strtotime($line['date_vto'])),
						'invoice_no'	=> $line['invoice_no'],
						'bank_cc'		=> $line['bank_cc'],
						'amount'		=> $this->currency->format($line['amount'])
					);
				}
			}

			$this->data['remittances'][] = array(
				'remittance_id' 	=> $remittance_id,
				'date'				=> date($this->language->get('date_format_short'), strtotime($remittance_info['date_added'])),
				'store_name'        => $store_name,
				'store_url'         => rtrim(HTTP_CATALOG, '/'),
				'store_address'     => nl2br($store_address),
				'store_email'       => $store_email,
				'bic'       		=> $bic,
				'iban'       		=> $iban,
				'store_telephone'   => $store_telephone,
				'store_nif'         => $store_nif,
				'remittance_lines'  => $remittance_lines
			);
		}
		
		$this->data['logo'] = $this->config->get('config_logo');

		$this->template = 'sale/remittances_print.tpl';
			
		$this->response->setOutput($this->render());
	}
}
?>