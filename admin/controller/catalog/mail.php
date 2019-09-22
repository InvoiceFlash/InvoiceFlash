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

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = 'in';
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

		if ($type=='in') {
			$page_in = $page;
			$page_out = 1;
		} else {
			$page_in = 1;
			$page_out = $page;
		}

		$data = array(
			'filter_company' => $filter_company,
			'start' => ($page_in - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		// require_once(DIR_SYSTEM . 'vendor/imap/ImapConnect.php'); 
		// require_once(DIR_SYSTEM . 'vendor/imap/ImapClient.php'); 
		// require_once(DIR_SYSTEM . 'vendor/imap/IncomingMessage.php');
		// require_once(DIR_SYSTEM . 'vendor/imap/TypeAttachments.php');
		// require_once(DIR_SYSTEM . 'vendor/imap/section.php');
		// require_once(DIR_SYSTEM . 'vendor/imap/SubtypeBody.php');
		// require_once(DIR_SYSTEM . 'vendor/imap/TypeBody.php');
		// require_once(DIR_SYSTEM . 'vendor/imap/IncomingMessageAttachment.php');
		
		
		// $imap = new ImapClient([
			// 'flags' => [
				// 'service' => ImapConnect::SERVICE_IMAP,
				// 'encrypt' => ImapConnect::ENCRYPT_SSL,
				// /* This NOVALIDATE_CERT is used when the server connecting to the imap
				 // * servers is not https but the imap is. This ignores the failure.
				 // */
				// 'validateCertificates' => ImapConnect::NOVALIDATE_CERT,
			// ],
			// 'mailbox' => [
				// 'remote_system_name' => 'mail.coompras.com',
			// ],
			// 'connect' => [
				// 'username' => 'pruebas@coompras.com',
				// 'password' => 'MdATec0T'
			// ]
		// ]);
	
		// $imap->selectFolder('INBOX');
		// $emails = $imap->getMessages();
		
		// $count = 0 ;
		// foreach($emails as $email){
			// $count = $count + 1;
			// if ($count == 4){
				
				// echo $imap->incomingMessage->header->from ;
				// echo '<BR>';
				// echo $imap->incomingMessage->header->date;
				// echo '<BR>';
				// echo $imap->incomingMessage->header->subject ;
				// echo '<BR>';
				//*********************************
				// echo '<pre>';
				// var_dump ($imap->incomingMessage->message);
				// echo'</pre>';
				// echo $imap->incomingMessage->message->plain ;
				// echo $imap->incomingMessage->message->plain->charset ;
				// echo $imap->incomingMessage->message->plain->body ;
				// echo $imap->incomingMessage->message->info[0]->body ;
				// var_dump($imap->incomingMessage->message->info[1]->body) ;// este es el bueno
				
				
			// }

		// };
				
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
				'created'     => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
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
				'date_added'  => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
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
		$pag_mail_in->url   = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url . '&page={page}' . '&type=in', 'SSL');

		$this->data['pag_mail_in'] = $pag_mail_in->render();

		$pag_mail_out = new Pagination();
		$pag_mail_out->total = $mails_out_total;
		$pag_mail_out->page  = $page_out;
		$pag_mail_out->limit = $this->config->get('config_admin_limit');
		$pag_mail_out->text  = $this->language->get('text_pagination');
		$pag_mail_out->url   = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'] . $url . '&page={page}' . '&type=out', 'SSL');

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
				echo 'Imap library is not configurated.';
			}else{
				
				require_once(DIR_SYSTEM . 'vendor/imap/ImapConnect.php'); 
				require_once(DIR_SYSTEM . 'vendor/imap/ImapClient.php'); 
				require_once(DIR_SYSTEM . 'vendor/imap/IncomingMessage.php');
				require_once(DIR_SYSTEM . 'vendor/imap/TypeAttachments.php');
				require_once(DIR_SYSTEM . 'vendor/imap/Section.php');
				require_once(DIR_SYSTEM . 'vendor/imap/SubtypeBody.php');
				require_once(DIR_SYSTEM . 'vendor/imap/TypeBody.php');
				require_once(DIR_SYSTEM . 'vendor/imap/IncomingMessageAttachment.php');
				
				$this->model_catalog_mail->getmails();
			}
		}

		$this->redirect($this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function send() {
		
		$json = array();
		
		$this->language->load('catalog/mail');
		
		$log=new Log('mail.log'); 

		if (!$this->user->hasPermission('modify', 'catalog/mail')) {
			$json['error']['permis'] = $this->language->get('error_permis');
		}

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
		
		//$message = $mail['message'];
		$message = $this->remover_js($mail['message']);
		
		if (substr($message, 0, 1) == '<' or strpos($message, '<!doct') == true) {
		}else{
			$message = str_replace("\n", "<br />", $message); // Necesario para los ficheros de texto
		}
		//$message = quoted_printable_decode ($message);
		
		$message = preg_replace("/<p\s(.+?)>(.+?)<\/p>/is", "<span>$2</span><br>", $message);
		
		
		$this->data['message'] = $message;

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
			
			// Insert in table fl_mails
			$this->load->model('catalog/mail');

			// customer
			$this->load->model('sale/customer');
			$customer_info = $this->model_sale_customer->getCustomerByEmail($lcTo);
			if ($customer_info) {
				$customer_id = $customer_info['customer_id'];
			} else {
				$customer_id = 0;
			}

			$data = array(
				'subject'		=> $lcsubject,
				'text'			=> $message,
				'code'			=> md5($message),
				'to'			=> $lcTo,
				'customer_id'	=> $customer_id
			);

			$this->model_catalog_mail->addMailSended($data);
			
			$this->redirect($this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	
	protected function validateForm() {
		
		if (!$this->user->hasPermission('modify', 'catalog/mail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	// añadido
	public function remover_js($html) {
		$javascript = '/<script[^>]*?>.*?<\/script>/si';
		$html = preg_replace($javascript, '', $html);
		$javascript = '/<script[^>]*?javascript{1}[^>]*?>.*?<\/script>/si';
		$html = preg_replace($javascript, '', $html);
		return $html;
	}
}
?>