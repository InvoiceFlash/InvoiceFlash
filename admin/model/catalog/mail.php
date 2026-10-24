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
	
	// Condicion SQL: correos del usuario actual o sin dueno (importados antes de que cada usuario tuviera su cuenta).
	private function ownerFilter($alias = '') {
		$col = ($alias === '') ? 'nusuario' : $alias . '.nusuario';

		return "(" . $col . " = '" . (int)$this->user->getId() . "' OR " . $col . " IS NULL OR " . $col . " = 0)";
	}

	private function tableExists($table) {
		$this->load->library('util');

		$ut = Util::get_instance($this->registry);

		return $ut->checkTableExists($table);
	}

	// Proveedor o potencial con el email del remitente (solo si el remitente no es ya un cliente). Devuelve array(supplier_id, potential_id).
	private function findSenderRelations($email, $customer_id) {
		$supplier_id  = 0;
		$potential_id = 0;

		if ($customer_id || ($email === '')) {
			return array(0, 0);
		}

		$email = $this->db->escape(strtolower($email));

		if ($this->tableExists('supplier')) {
			$query = $this->db->query("SELECT supplier_id FROM " . DB_PREFIX . "supplier WHERE LCASE(email) = '" . $email . "'");

			if ($query->num_rows) {
				$supplier_id = (int)$query->row['supplier_id'];
			}
		}

		if (!$supplier_id && $this->tableExists('potenciales')) {
			$query = $this->db->query("SELECT potencial_id FROM " . DB_PREFIX . "potenciales WHERE LCASE(email) = '" . $email . "'");

			if ($query->num_rows) {
				$potential_id = (int)$query->row['potencial_id'];
			}
		}

		return array($supplier_id, $potential_id);
	}

	// Correo recibido del usuario actual, aun sin borrar (para operar sobre el solo si es suyo).
	public function getOwnReceivedMail($mail_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mails WHERE mail_id = '" . (int)$mail_id . "' AND type = 'R' AND bleido <> 2 AND " . $this->ownerFilter());

		return $query->row;
	}

	public function canAddPotential() {
		return is_file(DIR_APPLICATION . 'model/crm/potencial.php') && $this->tableExists('potenciales');
	}

	// Remitente sin cliente, proveedor ni potencial.
	public function isUnknownSender($mail) {
		return empty($mail['customer_id']) && empty($mail['supplier_id']) && empty($mail['potential_id']);
	}

	// Enlaza este correo y los demas aun sin enlazar del mismo remitente y usuario. $type: customer | supplier | potential.
	private function linkSender($mail, $type, $id) {
		$fields = array('customer' => 'customer_id', 'supplier' => 'supplier_id', 'potential' => 'potential_id');

		$this->db->query("UPDATE " . DB_PREFIX . "mails SET " . $fields[$type] . " = '" . (int)$id . "' WHERE type = 'R' AND bleido <> 2 AND client = '" . $this->db->escape($mail['client']) . "' AND nusuario = '" . (int)$mail['nusuario'] . "' AND customer_id = 0 AND supplier_id = 0 AND (potential_id IS NULL OR potential_id = 0)");

		if ($type == 'customer') {
			$this->spawnMailEmbeddingIfEnabled($mail['mail_id']);
		}
	}

	// Asigna el remitente a un cliente o proveedor existente. Devuelve false si el destino no existe.
	public function assignSender($mail, $type, $id) {
		if ($type == 'customer') {
			$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$id . "'");
		} elseif (($type == 'supplier') && $this->tableExists('supplier')) {
			$query = $this->db->query("SELECT supplier_id FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$id . "'");
		} else {
			return false;
		}

		if (!$query->num_rows) {
			return false;
		}

		$this->linkSender($mail, $type, $id);

		return true;
	}

	// Da de alta el remitente como potencial (modulo CRM) y enlaza sus correos. Devuelve el id o false.
	public function addSenderAsPotential($mail) {
		if (!$this->canAddPotential() || ($mail['client'] === '')) {
			return false;
		}

		$this->load->model('crm/potencial');

		$existing = $this->model_crm_potencial->getPotencialByEmail($mail['client']);

		if ($existing) {
			$potential_id = (int)$existing['potencial_id'];
		} else {
			$potential_id = (int)$this->model_crm_potencial->addPotencial(array(
				'company' => $mail['client'], 'email' => $mail['client'], 'nif' => '', 'telephone' => '', 'web' => '',
				'bank_cc' => '', 'bic' => '', 'efaccafi' => '', 'efaccare' => '', 'efaccapa' => '',
				'address' => '', 'city' => '', 'postcode' => '',
				'country_id' => (int)$this->config->get('config_country_id'), 'zone_id' => 0, 'status' => 1
			));
		}

		if (!$potential_id) {
			return false;
		}

		$this->linkSender($mail, 'potential', $potential_id);

		return $potential_id;
	}

	// Busca clientes y proveedores para asignarles un remitente. Cada resultado: array('value' => 'customer:12', 'label' => ...).
	public function searchContacts($q) {
		$q       = $this->db->escape($q);
		$results = array();

		$query = $this->db->query("SELECT customer_id, company, email FROM " . DB_PREFIX . "customer WHERE company LIKE '%" . $q . "%' OR email LIKE '%" . $q . "%' ORDER BY company LIMIT 10");

		foreach ($query->rows as $row) {
			$results[] = array('value' => 'customer:' . $row['customer_id'], 'label' => $row['company'] . ' (' . $row['email'] . ')');
		}

		if ($this->tableExists('supplier')) {
			$query = $this->db->query("SELECT supplier_id, company, email FROM " . DB_PREFIX . "supplier WHERE company LIKE '%" . $q . "%' OR email LIKE '%" . $q . "%' ORDER BY company LIMIT 10");

			foreach ($query->rows as $row) {
				$results[] = array('value' => 'supplier:' . $row['supplier_id'], 'label' => $row['company'] . ' (' . $row['email'] . ')');
			}
		}

		return $results;
	}

	public function getTotalmails_in($data = array()) {
	
      	$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "mails WHERE (type= 'E' or type= 'R') AND " . $this->ownerFilter());

      	return $query->row['total'] ;
	}
	
	public function getmails_in($data = array()) {

		$has_supplier  = $this->tableExists('supplier');
		$has_potential = $this->tableExists('potenciales');

		$sql = "SELECT mails.*, " . ($has_supplier || $has_potential ? "CASE WHEN mails.customer_id != 0 THEN c.company" . ($has_supplier ? " WHEN mails.supplier_id != 0 THEN s.company" : "") . ($has_potential ? " WHEN mails.potential_id != 0 THEN p.company" : "") . " ELSE NULL END" : "c.company") . " AS company,
					(log.document_embedding_log_id IS NOT NULL) AS rag_indexed
				FROM " . DB_PREFIX . "mails AS mails
					LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = mails.customer_id
					" . ($has_supplier ? "LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = mails.supplier_id" : "") . "
					" . ($has_potential ? "LEFT JOIN " . DB_PREFIX . "potenciales p ON p.potencial_id = mails.potential_id" : "") . "
					LEFT JOIN " . DB_PREFIX . "document_embedding_log log ON log.document_id = CONCAT('mail_', mails.mail_id) AND log.status = 'done'
					WHERE type= 'R' AND bleido <> 2 AND " . $this->ownerFilter('mails');

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
	
      	$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "mails WHERE type= 'E' AND " . $this->ownerFilter());
				
      	return $query->row['total'] ;
	}
	
	public function getmails_out($data = array()) {
		$this->load->library('util');
		$ut = Util::get_instance($this->registry);
		
		$sql = "SELECT m.mail_id,
			(log.document_embedding_log_id IS NOT NULL) AS rag_indexed,
			CASE
			WHEN m.customer_id != 0 THEN c.company";
			
		if ($ut->checkTableExists('supplier')) {
			$sql .= " WHEN m.supplier_id != 0 THEN s.company";
		}

		if ($ut->checkTableExists('potenciales')) {
			$sql .= " WHEN m.potential_id != 0 THEN p.company";
		}

		$sql .= " ELSE `client`
			END AS company
			, m.title, m.message, m.date_added
			FROM " . DB_PREFIX . "mails AS m
			LEFT JOIN " . DB_PREFIX . "customer c ON c.customer_id = m.customer_id
			LEFT JOIN " . DB_PREFIX . "document_embedding_log log ON log.document_id = CONCAT('mail_', m.mail_id) AND log.status = 'done'";
			
		if ($ut->checkTableExists('supplier')) {	
			$sql .= " LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = m.supplier_id";
		}

		if ($ut->checkTableExists('potenciales')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "potenciales p ON p.potencial_id = m.potential_id";
		}
		
		$sql .= " WHERE TYPE= 'E' AND " . $this->ownerFilter('m') . " ORDER BY m.date_added DESC";
		
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
	// Descarga el correo del usuario actual con SU cuenta (POP3 al servidor comun de Ajustes), guarda cada mensaje
	// asignado a el y lo borra del servidor una vez guardado. Devuelve array('error'=>'', 'downloaded'=>n, 'remaining'=>n);
	// error es un codigo: no_user, no_imap, no_account, no_host, connect:<detalle>.
	// $user_id vacio = el usuario conectado (boton Descargar); el cron lo indica para cada usuario.
	public function downloadUserMails($max = 100, $user_id = 0) {
		$result = array('error' => '', 'downloaded' => 0, 'remaining' => 0, 'skipped' => 0);

		$user_id = $user_id ? (int)$user_id : (int)$this->user->getId();

		if (!$user_id) {
			$result['error'] = 'no_user';

			return $result;
		}

		if (!extension_loaded('imap')) {
			$result['error'] = 'no_imap';

			return $result;
		}

		$this->load->model('user/mail_account');

		$credentials = $this->model_user_mail_account->getCredentials($user_id);

		if (!$credentials) {
			$result['error'] = 'no_account';

			return $result;
		}

		// Servidor comun: el POP de Ajustes o, si esta vacio, el mismo host del SMTP.
		$host = trim((string)$this->config->get('config_user_mail_pop_host'));

		if ($host === '') {
			$host = trim((string)$this->config->get('config_smtp_host'));
		}

		$host = preg_replace('#^(ssl|tls)://#i', '', $host);

		if ($host === '') {
			$result['error'] = 'no_host';

			return $result;
		}

		$ssl_setting = $this->config->get('config_user_mail_pop_ssl');
		$ssl         = (($ssl_setting === null) || ($ssl_setting === '')) ? true : (bool)$ssl_setting;
		$port        = (int)$this->config->get('config_user_mail_pop_port');

		if ($port < 1) {
			$port = $ssl ? 995 : 110;
		}

		foreach (array('ImapConnect', 'ImapClient', 'IncomingMessage', 'TypeAttachments', 'Section', 'SubtypeBody', 'TypeBody', 'IncomingMessageAttachment') as $class) {
			require_once(DIR_SYSTEM . 'vendor/imap/' . $class . '.php');
		}

		set_time_limit(300);

		$mailbox = '{' . $host . ':' . $port . '/pop3' . ($ssl ? '/ssl/novalidate-cert' : '/notls') . '}INBOX';
		$stream  = @imap_open($mailbox, $credentials['username'], $credentials['password'], 0, 1);

		if (!$stream) {
			$error = imap_last_error();
			imap_errors();
			imap_alerts();

			$result['error'] = 'connect:' . ($error ? $error : '');

			return $result;
		}

		$log   = new Log('mail.log');
		$total = (int)imap_num_msg($stream);

		// Limite de antiguedad (Ajustes > Correo): lo anterior se deja en el servidor, sin descargar ni borrar.
		$max_days = (int)$this->config->get('config_user_mail_max_days');
		$oldest   = ($max_days > 0) ? strtotime('-' . $max_days . ' days') : 0;
		$dates    = array();

		if ($oldest && $total) {
			foreach ((array)imap_fetch_overview($stream, '1:' . $total, 0) as $overview) {
				$dates[(int)$overview->msgno] = isset($overview->udate) ? (int)$overview->udate : 0;
			}
		}

		$processed = 0;

		for ($i = 1; $i <= $total; $i++) {
			if ($oldest && !empty($dates[$i]) && ($dates[$i] < $oldest)) {
				$result['skipped']++;

				continue;
			}

			if ($processed >= (int)$max) {
				$result['remaining']++;

				continue;
			}

			$processed++;

			try {
				$stored = $this->storeDownloadedMessage($stream, $i, $user_id);
			} catch (Exception $e) {
				$log->write('Descarga de correo (usuario ' . $user_id . ', mensaje ' . $i . '): ' . $e->getMessage());

				continue;
			} catch (\Throwable $e) {
				$log->write('Descarga de correo (usuario ' . $user_id . ', mensaje ' . $i . '): ' . $e->getMessage());

				continue;
			}

			// Solo se borra del servidor lo que ya esta guardado.
			if ($stored !== false) {
				imap_delete($stream, $i);

				if ($stored === true) {
					$result['downloaded']++;
				}
			}
		}

		imap_close($stream, CL_EXPUNGE);
		imap_errors();
		imap_alerts();

		return $result;
	}

	// Cron: descarga el correo de todos los usuarios activos que tienen cuenta propia. Devuelve array(user_id => resultado).
	public function downloadAllUsersMails($max = 100) {
		$results = array();

		$this->load->model('user/mail_account');
		$this->model_user_mail_account->ensureTable();

		$query = $this->db->query("SELECT a.user_id FROM " . DB_PREFIX . "user_mail_account a INNER JOIN " . DB_PREFIX . "user u ON u.user_id = a.user_id WHERE u.status = '1' AND a.mail_username <> '' AND a.mail_password <> ''");

		foreach ($query->rows as $row) {
			$results[(int)$row['user_id']] = $this->downloadUserMails($max, (int)$row['user_id']);
		}

		return $results;
	}

	// Guarda un mensaje descargado. true = guardado; null = ya estaba guardado (se puede borrar del servidor); false = no se pudo.
	private function storeDownloadedMessage($stream, $number, $user_id) {
		$email = new IncomingMessage($stream, (int)$number);

		$fromaddress = '';

		if (isset($email->header->details->from)) {
			foreach ($email->header->details->from as $object) {
				$fromaddress = $object->mailbox . '@' . $object->host;
			}
		}

		// Identificador real del mensaje (Message-ID); sin el, una huella de sus cabeceras.
		$code = isset($email->header->details->message_id) ? trim($email->header->details->message_id) : '';

		if ($code === '') {
			$code = md5(imap_fetchheader($stream, $number));
		}

		$query = $this->db->query("SELECT mail_id FROM " . DB_PREFIX . "mails WHERE code = '" . $this->db->escape($code) . "' AND nusuario = '" . (int)$user_id . "'");

		if ($query->num_rows) {
			return null;
		}

		$time = isset($email->header->date) ? strtotime($email->header->date) : false;
		$date = date('Y-m-d H:i:s', $time ? $time : time());

		$subject = isset($email->header->subject) ? mb_convert_encoding($email->header->subject, 'UTF-8', 'auto') : '';

		if (isset($email->message->info[1]) && (strlen($email->message->info[1]->body) > 10)) {
			$body = $email->message->info[1]->body;
		} elseif (isset($email->message->info[0])) {
			$body = $email->message->info[0]->body;
		} else {
			$body = '';
		}

		// Cliente por su email o por el de uno de sus contactos.
		$customer_id = 0;

		if ($fromaddress !== '') {
			$by_customer = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($fromaddress) . "'");
			$by_contact  = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer_contacts` WHERE cemail = '" . $this->db->escape($fromaddress) . "'");

			if ($by_customer->num_rows) {
				$customer_id = (int)$by_customer->row['customer_id'];
			} elseif ($by_contact->num_rows) {
				$customer_id = (int)$by_contact->row['customer_id'];
			}
		}

		list($supplier_id, $potential_id) = $this->findSenderRelations($fromaddress, $customer_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "mails SET nusuario = '" . (int)$user_id . "', client = '" . $this->db->escape($fromaddress) . "', code = '" . $this->db->escape($code) . "', title = '" . $this->db->escape(trim($subject)) . "', message = '" . $this->db->escape($body) . "', date_added = '" . $date . "', type = 'R', customer_id = '" . $customer_id . "', supplier_id = '" . $supplier_id . "', potential_id = '" . $potential_id . "'");

		$mail_id = $this->db->getLastId();

		if (isset($email->attachments) && is_array($email->attachments)) {
			foreach ($email->attachments as $attachment) {
				if (isset($attachment->name) && isset($attachment->body)) {
					// La libreria no decodifica los adjuntos (su comprobacion de propiedades falla con este objeto): se hace aqui segun la codificacion MIME.
					$body = $attachment->body;
					$name = $attachment->name;
					$enc  = (isset($attachment->info->structure->encoding)) ? (int)$attachment->info->structure->encoding : 0;

					if ($enc == 3) {
						$body = imap_base64($body);
					} elseif ($enc == 4) {
						$body = quoted_printable_decode($body);
					}

					if (strpos($name, '=?') !== false) {
						$decoded = @iconv_mime_decode($name, 0, 'UTF-8');

						if ($decoded !== false && $decoded !== '') {
							$name = $decoded;
						}
					}

					$this->saveMailAttachment($mail_id, $name, $body);
				}
			}
		}

		if ($customer_id != 0) {
			$this->spawnMailEmbeddingIfEnabled($mail_id);
		}

		return true;
	}

	public function editconfig($key, $value){
		$this->db->query("update " . DB_PREFIX . "setting  set `value`=" . $this->db->escape($value)." WHERE `key` = '". $this->db->escape($key)."'");
		
		return ;
	}
	
	public function deleteMails($mail_id) {
		$this->deleteMailEmbeddings($mail_id);
		$this->deleteMailAttachments($mail_id);
		$this->db->query("UPDATE " . DB_PREFIX . "mails SET bleido = 2 WHERE mail_id = " . (int)$mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "mail_files WHERE mail_id = " . (int)$mail_id);
	}

	public function deleteMails_out($mail_id) {
		$this->deleteMailEmbeddings($mail_id);
		$this->deleteMailAttachments($mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "mails WHERE mail_id = " . (int)$mail_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "mail_files WHERE mail_id = " . (int)$mail_id);
	}
	
	public function getMail($mail_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mails where mail_id = " . (int)$mail_id);

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

		// Adjunto: se guarda una copia, porque el fichero original (p. ej. el PDF del presupuesto) se sobrescribe en el siguiente envio.
		if (!empty($data['file']) && is_file($data['file'])) {
			$this->saveMailAttachment($mail_id, basename($data['file']), file_get_contents($data['file']));
		}

		if (isset($data['customer_id']) && $data['customer_id'] != 0) {
			$this->spawnMailEmbeddingIfEnabled($mail_id);
		}

		return $mail_id;
	}

	// ---- Adjuntos de los correos (enviados y recibidos): docs/mails/<mail_id>/<fichero> ----
	// No hay tabla: el listado es el propio directorio. Un correo sin directorio no tiene adjuntos.
	public function getMailAttachmentsDir($mail_id) {
		return dirname(DIR_APPLICATION) . '/docs/mails/' . (int)$mail_id . '/';
	}

	// Nombre seguro para guardar en disco (sin rutas ni caracteres raros) y unico dentro del correo.
	public function saveMailAttachment($mail_id, $name, $content) {
		$max_bytes = 25 * 1024 * 1024;

		if (!is_string($content) || ($content === '') || (strlen($content) > $max_bytes)) {
			return false;
		}

		$name = str_replace('\\', '/', (string)$name);
		$name = basename($name);
		$name = trim(preg_replace('/[^A-Za-z0-9._ \-()]+/', '_', $name), ". \t");

		if ($name === '') {
			$name = 'adjunto';
		}

		// Los envios con fichero subido pierden la extension: si es un PDF, se recupera.
		if ((strpos($name, '.') === false) && (substr($content, 0, 4) === '%PDF')) {
			$name .= '.pdf';
		}

		$dir = $this->getMailAttachmentsDir($mail_id);

		if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
			return false;
		}

		$final = $name;
		$n     = 1;

		while (file_exists($dir . $final)) {
			$dot   = strrpos($name, '.');
			$final = ($dot === false) ? $name . '_' . $n : substr($name, 0, $dot) . '_' . $n . substr($name, $dot);
			$n++;
		}

		return (file_put_contents($dir . $final, $content) !== false) ? $final : false;
	}

	// Nombres de los adjuntos guardados de un correo (vacio si no tiene).
	public function getMailAttachments($mail_id) {
		$files = array();
		$dir   = $this->getMailAttachmentsDir($mail_id);

		if (is_dir($dir)) {
			foreach (scandir($dir) as $file) {
				if (($file !== '.') && ($file !== '..') && is_file($dir . $file)) {
					$files[] = $file;
				}
			}
		}

		return $files;
	}

	public function deleteMailAttachments($mail_id) {
		foreach ($this->getMailAttachments($mail_id) as $file) {
			@unlink($this->getMailAttachmentsDir($mail_id) . $file);
		}

		@rmdir($this->getMailAttachmentsDir($mail_id));
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
		if (PHP_VERSION_ID < 80000) {
			curl_close($ch);
		}

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