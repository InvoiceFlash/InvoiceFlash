<?php

class ModelSaleCustomer extends Model {

	public function addCustomer($data) {
		$date = strtotime($data['date_support']);
		$date_support = ($date ? date('Y-m-d', $date) : null);

		$sql = "INSERT INTO " . DB_PREFIX . "customer SET 
			company = '" . $this->db->escape($data['company']) . "', 
			approved = '1',
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "', 
			customer_group_id = '" . (int)$data['customer_group_id'] . "', 
			`status` = '" . (int)$data['status'] . "', 
			date_added = NOW(), 
			date_modified = NOW(), 
			date_support = '" . $date_support . "'";
		
		$this->db->query($sql);

		$customer_id = $this->db->getLastId();

		$bank_cc = str_replace(" ", "", $data['bank_cc']);

		$this->db->query("INSERT INTO " . DB_PREFIX . "fl_customers SET customer_id = " . (int)$customer_id . ", bank_cc = '" . $this->db->escape($bank_cc) . "', bic = '" . $this->db->escape($data['bic']) . "', efaccafi =''" . $this->db->escape($data['efaccafi']) . ", efaccare = '" . $this->db->escape($data['efaccare']) . "', efaccapa = '" . $this->db->escape($data['efaccapa']) . "', nif = '" . $this->db->escape($data['nif']) . "', cwww = '" . $this->db->escape($data['web']) . "'");

		if (isset($data['address'])) {

			foreach ($data['address'] as $address) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', company = '" . $this->db->escape($address['company']) . "', company_id = '" . $this->db->escape($address['company_id']) . "', tax_id = '" . $this->db->escape($address['tax_id']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");


				if (isset($address['default'])) {

					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

				}

			}

		}

	}



	public function editCustomer($customer_id, $data) {
		$date = strtotime($data['date_support']);
		$date_support = ($date ? date('Y-m-d', $date) : null);

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET 
			company = '" . $this->db->escape($data['company']) . "', 
			notes = '', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (int)$data['newsletter'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_support = '" . $date_support . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$bank_cc = str_replace(" ", "", $data['bank_cc']);

		$this->db->query("UPDATE " . DB_PREFIX . "fl_customers SET bank_cc = '" . $this->db->escape($bank_cc) . "', bic = '" . $this->db->escape($data['bic']) . "', efaccafi =''" . $this->db->escape($data['efaccafi']) . ", efaccare = '" . $this->db->escape($data['efaccare']) . "', efaccapa = '" . $this->db->escape($data['efaccapa']) . "', nif = '" . $this->db->escape($data['nif']) . "', digital_invoice = '" . (int)$data['digital_invoice'] . "', cwww = '" . $this->db->escape($data['web']) . "' WHERE customer_id = " . (int)$customer_id);

		// if ($data['password']) {

			// $this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "' WHERE customer_id = '" . (int)$customer_id . "'");

		// }



		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");



		if (isset($data['address'])) {

			foreach ($data['address'] as $address) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET address_id = '" . (int)$address['address_id'] . "', customer_id = '" . (int)$customer_id . "', company = '" . $this->db->escape($address['company']) . "', company_id = '" . $this->db->escape($address['company_id']) . "', tax_id = '" . $this->db->escape($address['tax_id']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");



				if (isset($address['default'])) {

					$address_id = $this->db->getLastId();



					$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

				}

			}

		}

	}



	public function editToken($customer_id, $token) {

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int)$customer_id . "'");

	}



	public function deleteCustomer($customer_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_contacts WHERE customer_id = " . (int)$customer_id);

	}

	public function getCustomer($customer_id) {

		$query = $this->db->query("SELECT c.company, c.email, ce.nif, c.telephone, ce.cwww, c.fax, c.newsletter, c.customer_group_id, c.status, c.date_support, ce.bank_cc, ce.bic, ce.efaccafi, ce.efaccapa, ce.efaccare, ce.digital_invoice, c.date_added, c.date_modified, c.notes, c.address_id, c.store_id	FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "fl_customers ce on ce.customer_id = c.customer_id WHERE c.customer_id = '" . (int)$customer_id . "'");

		return $query->row;

	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;

	}



	public function getCustomers($data = array()) {
		$sql = "SELECT c.customer_id, c.company, c.telephone, c.customer_group_id, cgd.name AS customer_group , c.email, c.status, c.ip, c.date_added FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_company'])) {
			$implode[] = "c.company LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
		}

		if (!empty($data['filter_telephone'])) {
			$implode[] = "telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'company',
			'telephone',
			'c.email',
			'customer_group',
			'c.status',
			'c.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY company";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

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

	public function getAddress($address_id) {

		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");



		if ($address_query->num_rows) {

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");



			if ($country_query->num_rows) {

				$country = $country_query->row['name'];

				$iso_code_2 = $country_query->row['iso_code_2'];

				$iso_code_3 = $country_query->row['iso_code_3'];

				$address_format = $country_query->row['address_format'];

			} else {

				$country = '';

				$iso_code_2 = '';

				$iso_code_3 = '';	

				$address_format = '';

			}



			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");



			if ($zone_query->num_rows) {

				$zone = $zone_query->row['name'];

				$zone_code = $zone_query->row['code'];

			} else {

				$zone = '';

				$zone_code = '';

			}		



			return array(

				'address_id'     => $address_query->row['address_id'],

				'customer_id'    => $address_query->row['customer_id'],

				'firstname'      => $address_query->row['firstname'],

				'lastname'       => $address_query->row['lastname'],

				'company'        => $address_query->row['company'],

				'company_id'     => $address_query->row['company_id'],

				'tax_id'         => $address_query->row['tax_id'],

				'address_1'      => $address_query->row['address_1'],

				'address_2'      => $address_query->row['address_2'],

				'postcode'       => $address_query->row['postcode'],

				'city'           => $address_query->row['city'],

				'zone_id'        => $address_query->row['zone_id'],

				'zone'           => $zone,

				'zone_code'      => $zone_code,

				'country_id'     => $address_query->row['country_id'],

				'country'        => $country,	

				'iso_code_2'     => $iso_code_2,

				'iso_code_3'     => $iso_code_3,

				'address_format' => $address_format

			);

		}

	}



	public function getAddresses($customer_id) {

		$address_data = array();



		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");



		foreach ($query->rows as $result) {

			$address_info = $this->getAddress($result['address_id']);



			if ($address_info) {

				$address_data[$result['address_id']] = $address_info;

			}

		}		



		return $address_data;

	}	



	public function getTotalCustomers($data = array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";


		$implode = array();



		if (!empty($data['filter_company'])) {

			$implode[] = "company LIKE '%" . $this->db->escape($data['filter_company']) . "%'";

		}

		if (!empty($data['filter_telephone'])) {

			$implode[] = "telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";

		}

		if (!empty($data['filter_email'])) {

			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";

		}



		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {

			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";

		}



		if (!empty($data['filter_customer_group_id'])) {

			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";

		}	




		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {

			$implode[] = "status = '" . (int)$data['filter_status'] . "'";

		}			



		if (!empty($data['filter_date_added'])) {

			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";

		}



		if ($implode) {

			$sql .= " WHERE " . implode(" AND ", $implode);

		}



		$query = $this->db->query($sql);



		return $query->row['total'];

	}



	public function getTotalAddressesByCustomerId($customer_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->row['total'];

	}



	public function getTotalAddressesByCountryId($country_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");



		return $query->row['total'];

	}	



	public function getTotalAddressesByZoneId($zone_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");



		return $query->row['total'];

	}



	public function getTotalCustomersByCustomerGroupId($customer_group_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int)$customer_group_id . "'");



		return $query->row['total'];

	}



	public function addHistory($customer_id, $comment) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int)$customer_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");

	}	



	public function getHistories($customer_id, $start = 0, $limit = 10) { 

		if ($start < 0) {

			$start = 0;

		}



		if ($limit < 1) {

			$limit = 10;

		}	



		$query = $this->db->query("SELECT comment, date_added FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);



		return $query->rows;

	}	



	public function getTotalHistories($customer_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->row['total'];

	}	



	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0) {

		$customer_info = $this->getCustomer($customer_id);



		if ($customer_info) { 

			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");



			$this->language->load('mail/customer');



			if ($customer_info['store_id']) {

				$this->load->model('setting/store');



				$store_info = $this->model_setting_store->getStore($customer_info['store_id']);



				if ($store_info) {

					$store_name = $store_info['name'];

				} else {

					$store_name = $this->config->get('config_name');

				}	

			} else {

				$store_name = $this->config->get('config_name');

			}



			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";

			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($customer_id)));



			$mail = new Mail();

			$mail->protocol = $this->config->get('config_mail_protocol');

			$mail->parameter = $this->config->get('config_mail_parameter');

			$mail->hostname = $this->config->get('config_smtp_host');

			$mail->username = $this->config->get('config_smtp_username');

			$mail->password = $this->config->get('config_smtp_password');

			$mail->port = $this->config->get('config_smtp_port');

			$mail->timeout = $this->config->get('config_smtp_timeout');

			$mail->setTo($customer_info['email']);

			$mail->setFrom($this->config->get('config_email'));

			$mail->setSender($store_name);

			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));

			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));

			$mail->send();

		}

	}



	public function deleteTransaction($order_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");

	}



	public function getTransactions($customer_id, $start = 0, $limit = 10) {

		if ($start < 0) {

			$start = 0;

		}



		if ($limit < 1) {

			$limit = 10;

		}	



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);



		return $query->rows;

	}



	public function getTotalTransactions($customer_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->row['total'];

	}



	public function getTransactionTotal($customer_id) {

		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->row['total'];

	}



	public function getTotalTransactionsByOrderId($order_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");



		return $query->row['total'];

	}	



	public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {

		$customer_info = $this->getCustomer($customer_id);



		if ($customer_info) { 

			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");



			$this->language->load('mail/customer');



			if ($order_id) {

				$this->load->model('sale/order');



				$order_info = $this->model_sale_order->getOrder($order_id);



				if ($order_info) {

					$store_name = $order_info['store_name'];

				} else {

					$store_name = $this->config->get('config_name');

				}	

			} else {

				$store_name = $this->config->get('config_name');

			}		



			$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";

			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));



			$mail = new Mail();

			$mail->protocol = $this->config->get('config_mail_protocol');

			$mail->parameter = $this->config->get('config_mail_parameter');

			$mail->hostname = $this->config->get('config_smtp_host');

			$mail->username = $this->config->get('config_smtp_username');

			$mail->password = $this->config->get('config_smtp_password');

			$mail->port = $this->config->get('config_smtp_port');

			$mail->timeout = $this->config->get('config_smtp_timeout');

			$mail->setTo($customer_info['email']);

			$mail->setFrom($this->config->get('config_email'));

			$mail->setSender($store_name);

			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_reward_subject'), $store_name), ENT_QUOTES, 'UTF-8'));

			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));

			$mail->send();

		}

	}



	public function deleteReward($order_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");

	}



	public function getRewards($customer_id, $start = 0, $limit = 10) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);



		return $query->rows;

	}



	public function getTotalRewards($customer_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->row['total'];

	}



	public function getRewardTotal($customer_id) {

		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->row['total'];

	}		



	public function getTotalCustomerRewardsByOrderId($order_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "'");



		return $query->row['total'];

	}



	public function getIpsByCustomerId($customer_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$customer_id . "'");



		return $query->rows;

	}	



	public function getTotalCustomersByIp($ip) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");



		return $query->row['total'];

	}



	public function addBanIp($ip) {

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");

	}



	public function removeBanIp($ip) {

		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

	}


	public function getTotalBanIpsByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}
	
	public function getDeliveryCustomerTotal($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "delivery` WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getDeliveryCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "delivery` WHERE customer_id = '" . (int)$customer_id . "' order by date_added desc");
		
		return $query->rows;
	}
	
	
	public function getInvoicesCustomerTotal($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getInvoicesCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "invoice` WHERE customer_id = '" . (int)$customer_id . "' order by date_added desc");
		
		return $query->rows;
	}
	
	public function getEmailsByCustomerId($customer_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fl_mails` WHERE bleido != 2 AND customer_id=" . (int)$customer_id . " ORDER BY date_added DESC");

		return $query->rows;
	}
	
	public function getProductsCustomerTotal($customer_id) {
		$query = $this->db->query("SELECT COUNT(product_id) AS total FROM `" . DB_PREFIX . "order_product` op,`" . DB_PREFIX . "order` o  WHERE op.order_id = o.order_id and o.customer_id = '" . (int)$customer_id . "'");
		return $query->row['total'];
	}	
	
	public function getProductsCustomer($customer_id) {
		$query = $this->db->query("SELECT op.*, o.date_added, pd.name FROM `" . DB_PREFIX . "order_product` op ,`" . DB_PREFIX . "order` o, `" . DB_PREFIX . "product_description` pd WHERE o.customer_id = '" . (int)$customer_id . "' and op.order_id = o.order_id and pd.product_id = op.product_id GROUP BY order_product_id");
		
		return $query->rows;
	}
	
	public function getQuotesCustomerTotal($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote` WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getQuotesCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote` WHERE customer_id = '" . (int)$customer_id . "' order by date_added desc");
		
		return $query->rows;
	}
	
	public function getOrdersCustomerTotal($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getOrdersCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE customer_id = '" . (int)$customer_id . "' order by date_added desc");
		
		return $query->rows;
	}

	public function getCustomerContacts($customer_id) {
		$query = $this->db->query("SELECT customer_contacts_id, cname, date_added, ctelef1, cpuesto, cemail FROM " . DB_PREFIX . "customer_contacts WHERE customer_id = " . (int)$customer_id);

		return $query->rows;
	}

	public function getCustomerContactsTotal($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_contacts WHERE customer_id = " . (int)$customer_id);

		return $query->row['total'];
	}

	public function getCustomerContact($customer_contacts_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_contacts WHERE customer_contacts_id = " . $customer_contacts_id);

		return $query->row;
	}

	public function getCustomerContactByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_contacts WHERE LCASE(cemail) = '" . $this->db->escape(strtolower($email)) . "'");

		return $query->row;
	}

	public function addCustomerContact($data, $customer_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_contacts SET 
			customer_id = " . $customer_id . ", 
			cname = '" . $data['name'] . "', 
			cpuesto = '" . $data['puesto'] . "', 
			cemail = '" . $data['email'] . "', 
			ctelef1 = '" . $data['telef1'] . "', 
			ctelef2 = '" . $data['telef2'] . "', 
			mnotas = '" . $data['notas'] . "', 
			nusualta = " . (int)$this->user->getID() . ", 
			caplalta = 'web', 
			tultmod = now(),
			nusuultmod = " . (int)$this->user->getID() . ", 
			caplultmod = 'web', 
			date_added = now()");
	}

	public function editCustomerContact($data, $customer_contacts_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_contacts SET 
			cname = '" . $data['name'] . "', 
			cpuesto = '" . $data['puesto'] . "', 
			cemail = '" . $data['email'] . "', 
			ctelef1 = '" . $data['telef1'] . "', 
			ctelef2 = '" . $data['telef2'] . "', 
			mnotas = '" . $data['notas'] . "', 
			tultmod = now(),
			nusuultmod = " . (int)$this->user->getID() . ", 
			caplultmod = 'web' WHERE customer_contacts_id = " . $customer_contacts_id);
	}

	public function deleteCustomerContact($customer_contacts_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_contacts WHERE customer_contacts_id = " . (int)$customer_contacts_id);
	}

	public function getCustomerContracts ($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "fl_contracts WHERE customer_id = " . (int)$customer_id);

		return $query->rows;
	}

	public function getCustomerContract($contracts_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "fl_contracts WHERE contracts_id = " . (int)$contracts_id);

		return $query->row;
	}

	public function getCustomerContractStatus() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "fl_contracts_status");

		return $query->rows;
	}

	public function addCustomerContract($data, $customer_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "fl_contracts SET 
			customer_id = " . (int)$customer_id . ", 
			narticulo = " . (int)$data['product_id'] . ", 
			quantity = " . (int)$data['quantity'] . ", 
			dcompra = DATE('" . $data['date_purchased'] . "'),
			dfinsoport = DATE('" . $data['end_support'] . "'),
			mnotas = '" . $this->db->escape($data['notes']) . "',
			contract_status = " . (int)$data['contract_status_id'] . ",
			talta = now(), 
			nusualta = '" . $this->user->getID() . "',
			caplalta = 'web'");
	}

	public function editCustomerContract($data, $contracts_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "fl_contracts SET 
			narticulo = " . (int)$data['product_id'] . ", 
			quantity = " . (int)$data['quantity'] . ", 
			dcompra = DATE('" . $data['date_purchased'] . "'),
			dfinsoport = DATE('" . $data['end_support'] . "'),
			mnotas = '" . $this->db->escape($data['notes']) . "',
			contract_status = " . (int)$data['contract_status_id'] . ",
			tultmod = now(), 
			nusuultmod = '" . $this->user->getID() . "',
			caplultmod = 'web' WHERE contracts_id = " . (int)$contracts_id);
	}

	public function deleteCustomerContract($contracts_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "fl_contracts WHERE contracts_id = " . (int)$contracts_id);
	}
 
 	function addCustomerNote($data, $customer_id) {
 		$sql = "INSERT INTO `" . DB_PREFIX . "customer_history` SET 
			`customer_id` = '" . (int)$customer_id . "', 
			`comment` = '" . $this->db->escape($data['comment']) . "', 
			`date_added` = NOW(), 
			`user_id` = '" . (int)$this->user->getID() . "'";

 		$query = $this->db->query($sql);
 	}

 	function deleteCustomerNote($note_id) {
 		$sql = "DELETE FROM `" . DB_PREFIX . "customer_history` WHERE customer_history_id = '" . (int)$note_id . "'";

 		$query = $this->db->query($sql);
 	}

 	function getCustomerNotes($customer_id) {
 		$sql = "SELECT customer_history_id, comment, date_added FROM `" . DB_PREFIX . "customer_history` WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added DESC";

 		$query = $this->db->query($sql);

 		return $query->rows;
 	}

 	function getCustomerNote($note_id) {
 		$sql = "SELECT ch.comment, ch.date_added, u.username AS user FROM " . DB_PREFIX . "customer_history ch LEFT JOIN `" . DB_PREFIX . "user` u ON ch.user_id = u.user_id WHERE ch.customer_history_id = " . (int)$note_id;

 		$query = $this->db->query($sql);

 		return $query->row;
 	}

 	function getCustomerNotesTotal($customer_id) {
 		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_history` WHERE customer_id = " . (int)$customer_id;
		
 		$query = $this->db->query($sql);

 		return $query->row['total'];
 	}

}

?>