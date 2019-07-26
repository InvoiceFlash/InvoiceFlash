<?php 
class ControllerCatalogMail extends Controller {
	private $error = array(); 
     
  	public function index() {
		$this->load->language('catalog/mail');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		    
		$this->load->model('catalog/mail');
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['tab_inbox'] = $this->language->get('tab_inbox');
		$this->data['tab_mail'] = $this->language->get('tab_mail');
		$this->data['tab_out'] = $this->language->get('tab_outbox');
		$this->data['tab_options'] = $this->language->get('tab_options');

		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_date'] = $this->language->get('column_date');
		$this->data['column_subject'] = $this->language->get('column_subject');
		$this->data['column_to'] = $this->language->get('column_to');
		$this->data['column_received'] = $this->language->get('column_received');
		$this->data['column_from'] = $this->language->get('column_from');
	
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_file'] = $this->language->get('entry_file');
		$this->data['entry_to']    = $this->language->get('entry_to');
		$this->data['entry_file']    = $this->language->get('entry_file');
		$this->data['entry_sendlimit'] = $this->language->get('entry_sendlimit');
		$this->data['entry_waiting'] = $this->language->get('entry_waiting');
		$this->data['entry_select_category']  = $this->language->get('entry_select_category');
		$this->data['entry_sleep']  = $this->language->get('entry_sleep');
		$this->data['entry_ckedit_width']  = $this->language->get('entry_ckedit_width');
		$this->data['entry_ckedit_height']  = $this->language->get('entry_ckedit_height');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_select_product'] = $this->language->get('entry_select_product');
        
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_reset'] = $this->language->get('button_reset');
		$this->data['button_test']  = $this->language->get('button_test');
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_delete']  = $this->language->get('button_delete');
		$this->data['button_cancel']  = $this->language->get('button_cancel');
		$this->data['button_upload']  = $this->language->get('button_upload');
		$this->data['button_filter']  = $this->language->get('button_filter');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (!extension_loaded('imap')) {
			$this->data['error_warning'] = $this->language->get('text_alert_imap');
		}

		if (empty($this->config->get('config_smtp_host')) || empty($this->config->get('config_smtp_username')) || empty($this->config->get('config_smtp_password'))){
			$this->data['error_config'] = $this->language->get('error_config') ;
		} else {
			$this->data['error_config'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['page_in'])) {
			$page_in = $this->request->get['page_in'];
			$url .= '&page_in=' . $page_in;
		} else {
			$page_in = 1;
		}

		if (isset($this->request->get['page_out'])) {
			$page_out = $this->request->get['page_out'];
			$url .= '&page_out=' . $page_out;
		} else {
			$page_out = 1;
		}

		if (isset($this->request->get['filter_company'])) {
			$filter_company = $this->request->get['filter_company'];
			$url .= '&filter_company=' . $filter_company;
		} else {
			$filter_company = '';
		}
		
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['getmail'] = $this->url->link('catalog/mail/getmails', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/mail/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['send'] = $this->url->link('catalog/mail/send', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$this->data['token'] = $this->session->data['token'];

		$data = array(
			'filter_company' => $filter_company,
			'start' => ($page_in - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$this->data['mails_ins'] = array();
		
		$mails_in_total = $this->model_catalog_mail->getTotalmails_in($data);
	
		$results = $this->model_catalog_mail->getmails_in($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('catalog/mail/mail_form', 'token=' . $this->session->data['token'] . '&mail_id=' . $result['mail_id'] . $url, 'SSL')
			);
			
			$this->data['mails_ins'][] = array(
				'mail_id'     => $result['mail_id'],
				'company'     => $result['company'],
				'mailfrom'    => $result['client'],
				'title'       => $result['title'],
				'message'     => $result['message'],
				'created'     => $result['date_added'],
				'sel_mail_in'    => isset($this->request->post['sel_mail_in']) && in_array($result['mail_id'], $this->request->post['sel_mail_in']),
				'action'      => $action
			);
		}	
	
		$this->data['mails_outs'] = array();
		
		$data = array(
			'start' => ($page_out - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$mails_out_total = $this->model_catalog_mail->getTotalmails_out($data);
	
		$results = $this->model_catalog_mail->getmails_out($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('catalog/mail/mail_form', 'token=' . $this->session->data['token'] . '&mail_id=' . $result['mail_id'] . $url, 'SSL')
			);
						
			$this->data['mails_outs'][] = array(
				'mail_id'     => $result['mail_id'],
				'company'     => $result['company'],
				'subject'     => $result['title'],
				'message'     => $result['message'],
				'date_added'  => $result['date_added'],
				'sel_mail_out'=> isset($this->request->post['sel_mail_out']) && in_array($result['mail_id'], $this->request->post['sel_mail_out']),
				'action'      => $action
			);
		}	
		
		if (isset($this->request->post['title'])) {
      		$this->data['title'] = $this->request->post['title'];
		} else {
      		$this->data['title'] = '' ;
    	}
		
		if (isset($this->request->post['message'])) {
      		$this->data['message'] = $this->request->post['message'];
		} else {
      		$this->data['message'] = '' ;
    	}
		
		$pag_mail_in = new Pagination();
		$pag_mail_in->total = $mails_in_total;
		$pag_mail_in->page  = $page_in;
		$pag_mail_in->limit = $this->config->get('config_admin_limit');
		$pag_mail_in->text  = $this->language->get('text_pagination');
		$pag_mail_in->url   = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url . '&page_in={page_in}', 'SSL');

		$this->data['pag_mail_in'] = $pag_mail_in->render();

		$pag_mail_out = new Pagination();
		$pag_mail_out->total = $mails_out_total;
		$pag_mail_out->page  = $page_out;
		$pag_mail_out->limit = $this->config->get('config_admin_limit');
		$pag_mail_out->text  = $this->language->get('text_pagination');
		$pag_mail_out->url   = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url . '&page_out={page_out}', 'SSL');

		$this->data['pag_mail_out'] = $pag_mail_out->render();

		$this->data['filter_company'] = $filter_company;
		
		$this->template = 'catalog/mail_list.tpl';		
		
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
	
	public function delete() {
	
		$this->load->language('catalog/mail');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		    
		$this->load->model('catalog/mail');
	
		if (isset($this->request->post['sel_mail_in'])) {

			foreach ($this->request->post['sel_mail_in'] as $mail_id) {
				$this->model_catalog_mail->deleteMails($mail_id);
	  		}
			
		}
		
		if (isset($this->request->post['sel_mail_out'])) {

			foreach ($this->request->post['sel_mail_out'] as $mail_id) {
				$this->model_catalog_mail->deleteMails_out($mail_id);
	  		}
			
		}
		
		$this->redirect($this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL'));
		
	}
	
	public function getmails() {
	
		$this->load->model('catalog/mail');
		
		if (!extension_loaded('imap')) {
			echo 'Imap library is not installed.';
		}else{
			if ($this->config->get('config_smtp_host')== null || $this->config->get('config_smtp_username')== null || $this->config->get('config_smtp_password')== null){
				// return;
			}else{
				$this->model_catalog_mail->getmails();
			}
		}

		$this->redirect($this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function send() {
		$this->language->load('catalog/mail');

		$json = array();

		if ($this->request->post['to']=='') {
			$json['error']['to'] = $this->language->get('error_to');
		}

		if ($this->request->post['subject']=='') {
			$json['error']['subject'] = $this->language->get('error_subject');
		}

		if ($this->request->post['message']=='') {
			$json['error']['message'] = $this->language->get('error_message');
		}

		if (empty($json['error'])) {
			
			$this->load->model('sale/customer');
			$customer = $this->model_sale_customer->getCustomerByEmail($this->request->post['to']);
			
			$data['potential_id'] = 0;
			$data['supplier_id'] = 0;
			
			if (!empty($customer)) {
				$data['customer_id'] = $customer['customer_id'];
			} else {
				$data['customer_id'] = 0;
			}
			
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
			
			$this->sendnewmail($data['to'], $data['subject'], $data['text'], $data['file']);
			
			$this->load->model('catalog/mail');
			
			$this->model_catalog_mail->addMailSended($data);
			
			$json['success'] = $this->language->get('text_success_email');
		}
		
		$this->response->setOutput(json_encode($json));
	}	

	public function mail_form() {
	
		$this->load->language('catalog/mail');
    	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_from']      = $this->language->get('entry_from');
		$this->data['entry_subject']    = $this->language->get('entry_subject');
		$this->data['entry_message']    = $this->language->get('entry_message');

		$this->data['button_reply'] = $this->language->get('button_reply');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$url = '';

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cancel'] = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$mail_id = $this->request->get['mail_id'] ;
		
		$this->load->model('catalog/mail');
		
		$mail = $this->model_catalog_mail->getMail($mail_id) ;

		$this->data['reply'] = $this->url->link('catalog/mail/reply', 'token=' . $this->session->data['token'] . '&mail_id=' . $mail_id, '');
		
		$this->data['from']    = $mail['client'] ;
		$this->data['subject'] = $mail['title'] ;
		$this->data['message'] = html_entity_decode($mail['message']);

		$this->data['message'] = strip_tags($this->data['message'], '<br>');
		$this->data['message'] = str_replace("\n", "<br />", $this->data['message']);
		
		$this->template = 'catalog/mail_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	
	}

	public function reply()	{
		$store_name = $this->config->get('config_name');
		
		$lcTo      = $_POST['to'];
		$lcmessage = $_POST['message'];
		$lcsubject = $_POST['subject'];

		
		$message  = '<html dir="ltr" lang="en">' . "\n";
		$message .= '<head>' . "\n";
		$message .= '<title>' . $lcsubject . '</title>' . "\n";
		$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
		$message .= '</head>' . "\n";
		$message .= '<body>' . html_entity_decode($lcmessage, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
		$message .= '</html>' . "\n";

		$message = str_replace("{NAME}",$this->config->get('config_owner'),$message);
		$message = str_replace("{EMAIL}",$this->config->get('config_smtp_username'),$message);

		if (strlen($this->config->get('config_smtp_host')) < 1 or
			strlen($this->config->get('config_smtp_username')) < 1){
			echo 'Your Mail server is not configurated.' ;
		}else{
			$mail = new Mail();	
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				

			$mail->setTo ($lcTo);			
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject($lcsubject);	

			$mail->setHtml($message);
			$mail->send();
			
			// Insert in table mail_out
			$this->load->model('catalog/mail');
			$this->model_catalog_mail->writemail_out($this->config->get('config_email'), $lcTo, $lcmessage, $lcsubject);
			
			$this->redirect($this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
}
?>