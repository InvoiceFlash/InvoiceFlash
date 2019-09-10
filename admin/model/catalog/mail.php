<?php
class ModelCatalogMail extends Model {

	public function maxgroup_idmessage() {
		$query = $this->db->query("SELECT MAX(group_id) AS group_id FROM `" . DB_PREFIX . "mail_messages`");
	
		if ($query->row['group_id']) {
			$group_id = (int)$query->row['group_id'] + 1;
		} else {
			$group_id = 1;
		}
		
		return $group_id;
	}
	
	public function storemails($group_id,$email,$subject,$message) {
	
      	$this->db->query("INSERT INTO " . DB_PREFIX . "mail_messages 
			SET mailto = '" . $this->db->escape($email) . "', 
				group_id = '" . $this->db->escape($group_id) . "', 
			    subject = '" . $this->db->escape($subject) . "',
				user_id_created = '" . $this->user->getId() . "',
				date_added = now() , 				
				message = '" . $this->db->escape($message) . "'"
		);
		
		return $this->db->getLastId();
	}
	
	public function getTotalmails_in($data = array()) {
	
      	$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "fl_mails WHERE type= 'E' or type= 'R'");

      	return $query->row['total'] ;
	}
	
	public function getmails_in($data = array()) {
	
		$sql = "SELECT mails.*, c.company FROM " . DB_PREFIX . "fl_mails AS mails  
					LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = mails.customer_id 
					WHERE type= 'R' AND bleido <> 2";

		if ($data['filter_company'] != '') {
			$sql .= " AND c.company = '" . $this->db->escape($data['filter_company'])  . "'";
		}

		$sql .= " ORDER BY mails.date_added DESC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
      	return $query->rows;
	}
	
	// public function getattached($nid) {
	
		// $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mail_attach 
				// WHERE message_id = ". $nid );
				
      	// return $query->rows;
	
	// }
	
	public function getTotalmails_out($data = array()) {
	
      	$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "fl_mails WHERE type= 'E'");
				
      	return $query->row['total'] ;
	}
	
	public function getmails_out($data = array()) {
		$this->load->library('util');
		$ut = Util::get_instance($this->registry);
		
		$sql = "SELECT m.mail_id, 
			CASE
			WHEN m.customer_id != 0 THEN c.company";
			
		if ($ut->checkTableExists('c_supplier')) {
			$sql .= " WHEN m.supplier_id != 0 THEN s.company";
		}

		if ($ut->checkTableExists('fl_potentials')) {
			$sql .= " WHEN m.potential_id != 0 THEN p.company";
		}

		$sql .= " ELSE `client`
			END AS company
			, m.title, m.message, m.date_added 
			FROM " . DB_PREFIX . "fl_mails AS m 
			LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = m.customer_id";
			
		if ($ut->checkTableExists('c_supplier')) {	
			$sql .= "LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = m.supplier_id";
		}

		if ($ut->checkTableExists('fl_potentials')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "fl_potentials p ON p.potentials_id = m.potential_id";
		}
		
		$sql .= " WHERE TYPE= 'E' ORDER BY m.date_added DESC";
		
      	$query = $this->db->query($sql);
				
      	return $query->rows;
	}
	
	public function updatemailsatus($message_id) {
	
		$this->db->query("UPDATE " . DB_PREFIX . "mail_messages 
			SET sended = 1 ,
				user_id_sended = '" . $this->user->getId() . "',
				date_sended = now()
			WHERE message_id = " . $this->db->escape($message_id)) ;
	}
	
	public function editSetting($group, $key, $value) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = '" . $this->db->escape($group) . "'");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
	}
	
	
	public function writemail_out($from, $lcTo, $message, $subject) {
		$sql = "INSERT INTO " . DB_PREFIX . "mail_out (mailfrom, mailto, message, subject, date_added) values ('". $from . "','" . $lcTo . "','" .$this->db->escape($message). "',
				'" . $this->db->escape($subject) . "', now() )" ;
				
		$this->db->query($sql) ;
	}
	
	public function getmails() {

		if (strpos($this->config->get('config_smtp_username'), 'gmail') !== false){
			$authhost=':993/imap/ssl/novalidate-cert';
		}else{
			$authhost=':110/pop3/notls';
		}
		// with certificate
		$connection = imap_open('{'. $this->config->get('config_smtp_host') . $authhost . '}INBOX', $this->config->get('config_smtp_username'), $this->config->get('config_smtp_password')) or die("Problem : ". imap_last_error()); 

		// control errores
		$foo = imap_errors();
		
		$count = imap_num_msg($connection);

		for ($i = 1; $i <= $count; $i++) {

			$header     = imap_header($connection, $i);
			$date       = $header->udate;

			 //Contenido del email
	        $body = imap_fetchbody($connection, $i, "1.1");
			if ($body == "") {
			    $body = imap_fetchbody($connection, $i, "1");
			}

			$message_id = $header->Msgno;
			$from       = $header->from;
			foreach ($from as $id => $object) {
				//$fromname = $object->personal ;
				$fromaddress = $object->mailbox . "@" . $object->host;
			}

			//Search if stored that mail
			$sql = "SELECT count(*) as mail FROM " . DB_PREFIX . "fl_mails where code='" .$message_id. "'"; 
			
			$query = $this->db->query($sql);
	
			if ($query->row['mail'] == 0){
				//Search customer by mail
				$sql_customer = "SELECT customer_id FROM " . DB_PREFIX . "customer where email='" .$fromaddress. "'" ; 
				$query_customer = $this->db->query($sql_customer);

				// Search contact by mail
				$sql_contact = "SELECT customer_id FROM `" . DB_PREFIX . "customer_contacts` WHERE cemail = '" . $fromaddress . "'";
				$query_contact = $this->db->query($sql_contact);
				
				if (isset($query_customer->row['customer_id'])){
					$customer_id = $query_customer->row['customer_id'];					
				} else if (isset($query_contact->row['customer_id'])) {
					$customer_id = $query_contact->row['customer_id'];
				} else {
					$customer_id = 0;
				}

				$sql = "INSERT INTO " . DB_PREFIX . "fl_mails (client, code, title, message, date_added, type, customer_id) 
					values('". $this->db->escape($fromaddress) . "',
						 '" . $this->db->escape($message_id) . "',
						 '" . $this->db->escape(iconv_mime_decode($header->subject,0, "ISO-8859-1")) . "',
						 '" . $this->db->escape($body) . "', FROM_UNIXTIME('". $date. "' ), 'R', '" . $customer_id . "')" ;
				
				$this->db->query($sql);
			}
		}
		
		//echo $sql;
		
		imap_close($connection); 

	}
	
	public function editconfig($key, $value){
		$this->db->query("update " . DB_PREFIX . "setting  set `value`=" . $this->db->escape($value)." WHERE `key` = '". $this->db->escape($key)."'");
		
		return ;
	}
	
	public function deleteMails($mail_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "fl_mails SET bleido = 2 WHERE mail_id = " . (int)$mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "fl_mail_files WHERE mail_id = " . (int)$mail_id);
	}
	
	public function deleteMails_out($mail_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "fl_mails WHERE mail_id = " . (int)$mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "fl_mail_files WHERE mail_id = " . (int)$mail_id);
	}
	
	public function getMail($mail_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "fl_mails where mail_id = " . $mail_id);
		
		return $query->row ;
	}

	public function addMailSended($data) {
		$sql = "INSERT INTO `" . DB_PREFIX . "fl_mails` SET nusuario = " . (int)$this->user->getId() . ", date_added = now(), title = '" . $this->db->escape($data['subject']) . "', message = '" . $this->db->escape($data['text']) . "', type = 'E', code = '" . $this->db->escape($data['code']) . "', client = '" . $this->db->escape($data['to']) . "', bleido = 1, tag_id = 0, ";

		if ($data['customer_id']!=0) {
			$sql .= "customer_id = " . (int)$data['customer_id'] . ", supplier_id = 0, potential_id = 0";
		} elseif ($data['supplier_id']!=0) {
			$sql .= "customer_id = 0, supplier_id = " . (int)$data['supplier_id'] . ", potential_id = 0";
		} elseif ($data['potential_id']!=0) {
			$sql .= "customer_id = 0, supplier_id = 0, potential_id = " . (int)$data['potential_id'];
		} else {
			$sql .= "customer_id = 0, supplier_id = 0, potential_id = 0";
		}
		
		$this->db->query($sql);
	}

}
?>