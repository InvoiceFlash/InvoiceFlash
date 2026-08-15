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
	
      	$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "mails WHERE type= 'E' or type= 'R'");

      	return $query->row['total'] ;
	}
	
	public function getmails_in($data = array()) {
	
		$sql = "SELECT mails.*, c.company FROM " . DB_PREFIX . "mails AS mails  
					LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = mails.customer_id 
					WHERE type= 'R' AND bleido <> 2";

		if ($data['filter_company'] != '') {
			$sql .= " AND c.company = '" . $this->db->escape($data['filter_company'])  . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND mails.client LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
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
	
      	$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "mails WHERE type= 'E'");
				
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
			FROM " . DB_PREFIX . "mails AS m 
			LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = m.customer_id";
			
		if ($ut->checkTableExists('c_supplier')) {	
			$sql .= " LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = m.supplier_id";
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

		$imap = new ImapClient([
			'flags' => [
				'service' => ImapConnect::SERVICE_IMAP,
				'encrypt' => ImapConnect::ENCRYPT_SSL,
				/* This NOVALIDATE_CERT is used when the server connecting to the imap
				 * servers is not https but the imap is. This ignores the failure.
				 */
				'validateCertificates' => ImapConnect::NOVALIDATE_CERT,
			],
			'mailbox' => [
				'remote_system_name' => $this->config->get('config_smtp_host') ,
			],
			'connect' => [
				'username' => $this->config->get('config_smtp_username') ,
				'password' => $this->config->get('config_smtp_password')
			]
		]);
	
		$imap->selectFolder('INBOX');
		$emails = $imap->getMessages();
		
		foreach($emails as $email){
						
			$from = $email->header->details->from ;
			foreach ($from as $id => $object) {
				$fromaddress = $object->mailbox . "@" . $object->host;
			}
			
			$message_id = $email->getID() ;

			if(isset($email->header->date)){
					$date = $email->header->date;
			}else{
					$date = date("Y-m-d H:i:s");
			}
			
			$subject = mb_convert_encoding($email->header->subject,"UTF-8","auto") ;
			
			if (isset($email->message->info[1]) and strlen($email->message->info[1]->body) > 10){
				$body    = $email->message->info[1]->body;
			}else{
				$body    = $email->message->info[0]->body;
			}

			//Search if stored that mail
			$sql = "SELECT count(*) as mail FROM " . DB_PREFIX . "mails where code='" .$message_id. "'"; 
			
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
				
				$sql = "INSERT INTO " . DB_PREFIX . "mails (client, code, title, message, date_added, type, customer_id) 
						values('". $this->db->escape($fromaddress) . "',
							 '" . $this->db->escape($message_id) . "',
							 '" . $this->db->escape(trim($subject)) . "',
							 '" . $this->db->escape($body) . "','" . date('Y-m-d H:i:s', strtotime($date) ). "', 'R', '" . $customer_id . "')" ;
					
				$this->db->query($sql);

				if ($customer_id != 0) {
					$this->spawnMailEmbeddingIfEnabled($this->db->getLastId());
				}
			}

		};

	}
	
	public function editconfig($key, $value){
		$this->db->query("update " . DB_PREFIX . "setting  set `value`=" . $this->db->escape($value)." WHERE `key` = '". $this->db->escape($key)."'");
		
		return ;
	}
	
	public function deleteMails($mail_id) {
		$this->deleteMailEmbeddings($mail_id);
		$this->db->query("UPDATE " . DB_PREFIX . "mails SET bleido = 2 WHERE mail_id = " . (int)$mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "mail_files WHERE mail_id = " . (int)$mail_id);
	}

	public function deleteMails_out($mail_id) {
		$this->deleteMailEmbeddings($mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "mails WHERE mail_id = " . (int)$mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "mail_files WHERE mail_id = " . (int)$mail_id);
	}
	
	public function getMail($mail_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mails where mail_id = " . $mail_id);

		return $query->row ;
	}

	// bleido: 0 = recibido, aun sin ver ("Ver" nunca pulsado); 1 = ya visto; 2 = borrado (ver deleteMails()).
	public function markMailViewed($mail_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "mails SET bleido = 1 WHERE mail_id = " . (int)$mail_id . " AND bleido = 0");
	}

	public function addMailSended($data) {
		$sql = "INSERT INTO `" . DB_PREFIX . "mails` SET nusuario = " . (int)$this->user->getId() . ", date_added = now(), title = '" . $this->db->escape($data['subject']) . "', message = '" . $this->db->escape($data['text']) . "', type = 'E', code = '" . $this->db->escape($data['code']) . "', client = '" . $this->db->escape($data['to']) . "', bleido = 1, tag_id = 0, ";

		if (isset($data['customer_id']) && $data['customer_id']!=0) {
			$sql .= "customer_id = " . (int)$data['customer_id'] . ", supplier_id = 0, potential_id = 0";
		} elseif (isset($data['supplier_id']) && $data['supplier_id']!=0) {
			$sql .= "customer_id = 0, supplier_id = " . (int)$data['supplier_id'] . ", potential_id = 0";
		} elseif (isset($data['potential_id']) && $data['potential_id']!=0) {
			$sql .= "customer_id = 0, supplier_id = 0, potential_id = " . (int)$data['potential_id'];
		} else {
			$sql .= "customer_id = 0, supplier_id = 0, potential_id = 0";
		}
		
		$this->db->query($sql);

		$mail_id = $this->db->getLastId();

		if (isset($data['customer_id']) && $data['customer_id'] != 0) {
			$this->spawnMailEmbeddingIfEnabled($mail_id);
		}

		return $mail_id;
	}

	// RAG: si "Activar RAG" + "Usar IA" estan activos y Ollama tiene el modelo de
	// embeddings disponible, indexa este email en segundo plano. Compartido por los
	// dos orígenes de filas en `mails` - enviados (addMailSended(), mas arriba,
	// llamado desde sale/customer, sale/quote, sale/order, sale/delivery,
	// sale/invoice y sale/draft) y recibidos (getmails(), mas abajo).
	private function spawnMailEmbeddingIfEnabled($mail_id) {
		if (!$this->config->get('config_product_vector_embeddings') || !$this->config->get('config_ai_enabled')) {
			return;
		}

		if (!$this->isOllamaEmbeddingModelAvailable()) {
			return;
		}

		$this->spawnEmbeddingScript('mail_' . (int)$mail_id, '--mail-id ' . (int)$mail_id);
	}

	private function isOllamaEmbeddingModelAvailable() {
		$chat_url = (string)$this->config->get('config_ollama_url');

		if ($chat_url === '') {
			$chat_url = 'http://127.0.0.1:11434/api/chat';
		}

		$base_url = preg_replace('#/api/.*$#', '', $chat_url);

		$ch = curl_init($base_url . '/api/tags');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		$raw = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if (($raw === false) || ($http_code != 200)) {
			return false;
		}

		$resp = json_decode($raw, true);

		if (empty($resp['models'])) {
			return false;
		}

		foreach ($resp['models'] as $model) {
			if (isset($model['name']) && (stripos($model['name'], 'nomic-embed-text') === 0)) {
				return true;
			}
		}

		return false;
	}

	private function findPythonForEmbeddings() {
		$candidates = array(
			'C:\\Users\\AlcuinoGarcia\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
			'python3',
			'python',
		);

		foreach ($candidates as $candidate) {
			if (stripos(PHP_OS, 'WIN') === 0 && strpos($candidate, ':\\') === false) {
				exec('where ' . escapeshellarg($candidate) . ' 2>NUL', $out, $code);
				if ($code === 0) {
					return $candidate;
				}
				continue;
			}

			if (strpos($candidate, ':\\') !== false) {
				if (file_exists($candidate)) {
					return $candidate;
				}
				continue;
			}

			exec('command -v ' . escapeshellarg($candidate) . ' 2>/dev/null', $out, $code);
			if ($code === 0) {
				return $candidate;
			}
		}

		return null;
	}

	private function spawnEmbeddingScript($status_suffix, $args) {
		$python = $this->findPythonForEmbeddings();

		if (!$python) {
			return false;
		}

		$script_path = DIR_SYSTEM . 'vendor/document_embeddings/document_embeddings.py';
		$status_dir  = DIR_SYSTEM . 'vendor/document_embeddings/';

		if (!is_dir($status_dir)) {
			mkdir($status_dir, 0755, true);
		}

		$status_file = $status_dir . 'status_' . $status_suffix . '.json';
		$log_file    = $status_dir . 'last_run_' . $status_suffix . '.log';

		$env = array(
			'DOCEMB_DB_HOST'     => DB_HOSTNAME,
			'DOCEMB_DB_PORT'     => (string)DB_PORT,
			'DOCEMB_DB_USER'     => DB_USERNAME,
			'DOCEMB_DB_PASSWORD' => DB_PASSWORD,
			'DOCEMB_DB_NAME'     => DB_DATABASE,
			'DOCEMB_DB_PREFIX'   => DB_PREFIX,
			'DOCEMB_LANGUAGE_ID' => (string)(int)$this->config->get('config_language_id'),
			'DOCEMB_STATUS_FILE' => $status_file,
		);

		if (stripos(PHP_OS, 'WIN') === 0) {
			foreach ($env as $key => $value) {
				putenv($key . '=' . $value);
			}

			$cmd = 'start /B "" ' . escapeshellarg($python) . ' ' . escapeshellarg($script_path) . ' ' . $args
				. ' > ' . escapeshellarg($log_file) . ' 2>&1';

			$handle = popen('cmd /c ' . $cmd, 'r');

			foreach ($env as $key => $value) {
				putenv($key);
			}

			if ($handle === false) {
				return false;
			}

			pclose($handle);

			return true;
		}

		$env_prefix = '';
		foreach ($env as $key => $value) {
			$env_prefix .= $key . '=' . escapeshellarg($value) . ' ';
		}

		$cmd = $env_prefix . escapeshellarg($python) . ' ' . escapeshellarg($script_path) . ' ' . $args
			. ' > ' . escapeshellarg($log_file) . ' 2>&1 &';

		exec($cmd, $out, $code);

		return true;
	}

	// Limpia los fragmentos/embeddings RAG de un email (ver document_embeddings.py::
	// process_mail(), document_id = "mail_<id>") - llamar antes de borrar/ocultar la fila.
	public function deleteMailEmbeddings($mail_id) {
		$doc_id = 'mail_' . (int)$mail_id;

		$this->db->query("DELETE FROM " . DB_PREFIX . "document_chunks WHERE document_id = '" . $this->db->escape($doc_id) . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "document_embedding_log WHERE document_id = '" . $this->db->escape($doc_id) . "'");
	}

}
?>