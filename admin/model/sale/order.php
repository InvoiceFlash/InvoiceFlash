<?php 

class ModelSaleOrder extends Model {
	public function addOrder($data) {
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($data['store_id']);
		
		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
		}
		
		$this->load->model('setting/setting');
		
		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);
			
		if (isset($setting_info['invoice_prefix'])) {
			$invoice_prefix = $setting_info['invoice_prefix'];
		} else {
			$invoice_prefix = $this->config->get('config_invoice_prefix');
		}
		
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');
		
		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}	

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
		
		if ($currency_info) {
			$currency_id = $currency_info['currency_id'];
			$currency_code = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id = 0;
			$currency_code = $this->config->get('config_currency');
			$currency_value = 1.00000;			
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "order` SET 
			`invoice_prefix` = '" . $this->db->escape($invoice_prefix) . "',
			 `store_id` = '" . (int)$data['store_id'] . "', 
			 `store_name` = '" . $this->db->escape($store_name) . "', 
			 `store_url` = '" . $this->db->escape($store_url) . "', 
			 `customer_id` = '" . (int)$data['customer_id'] . "', 
			 `customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
			 `email` = '" . $this->db->escape($data['email']) . "', 
			 `telephone` = '" . $this->db->escape($data['telephone']) . "', 
			 `fax` = '" . $this->db->escape($data['fax']) . "', 
			 `payment_company` = '" . $this->db->escape($data['payment_company']) . "', 
			 `payment_address_1` = '" . $this->db->escape($data['payment_address_1']) . "', 
			 `payment_address_2` = '" . $this->db->escape($data['payment_address_2']) . "', 
			 `payment_city` = '" . $this->db->escape($data['payment_city']) . "', 
			 `payment_postcode` = '" . $this->db->escape($data['payment_postcode']) . "', 
			 `payment_country` = '" . $this->db->escape($payment_country) . "', 
			 `payment_country_id` = '" . (int)$data['payment_country_id'] . "', 
			 `payment_zone` = '" . $this->db->escape($payment_zone) . "', 
			 `payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', 
			 `payment_address_format` = '" . $this->db->escape($payment_address_format) . "', 
			 `payment_method` = '" . $this->db->escape($data['payment_method']) . "', 
			 `payment_code` = '" . $this->db->escape($data['payment_code']) . "', 
			 `shipping_company` = '" . $this->db->escape($data['shipping_company']) . "', 
			 `shipping_address_1` = '" . $this->db->escape($data['shipping_address_1']) . "', 
			 `shipping_address_2` = '" . $this->db->escape($data['shipping_address_2']) . "', 
			 `shipping_city` = '" . $this->db->escape($data['shipping_city']) . "', 
			 `shipping_postcode` = '" . $this->db->escape($data['shipping_postcode']) . "', 
			 `shipping_country` = '" . $this->db->escape($shipping_country) . "', 
			 `shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', 
			 `shipping_zone` = '" . $this->db->escape($shipping_zone) . "', 
			 `shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', 
			 `shipping_address_format` = '" . $this->db->escape($shipping_address_format) . "', 
			 `shipping_method` = '" . $this->db->escape($data['shipping_method']) . "', 
			 `shipping_code` = '" . $this->db->escape($data['shipping_code']) . "', 
			 `comment` = '" . $this->db->escape($data['comment']) . "', 
			 `order_status_id` = '" . (int)$data['order_status_id'] . "', 
			 `language_id` = '" . (int)$this->config->get('config_language_id') . "', 
			 `currency_id` = '" . (int)$currency_id . "', 
			 `currency_code` = '" . $this->db->escape($currency_code) . "', 
			 `currency_value` = '" . (float)$currency_value . "',  
			 `date_added` = now(), 
			 `date_modified` = now()";

		$this->db->query($sql);

      	$order_id = $this->db->getLastId();
		
      	if (isset($data['order_product'])) {		

      		foreach ($data['order_product'] as $order_product) {
				
				// Valores float de price y total que llegan como strings
				$price = floatval(preg_replace("/[^-0-9\.]/","",$order_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/","",$order_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET 
				order_id = '" . (int)$order_id . "', 
				product_id = '" . (int)$order_product['product_id'] . "', 
				name = '" . $this->db->escape($order_product['name']) . "', 
				model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . $price . "', total = '" . $total . "', tax = '" . $order_product['tax'] . "'");
				
				//name_ext = '" . $this->db->escape($order_product['name_ext']) . "', 
				
				$order_product_id = $this->db->getLastId();
	
				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
					}
				}
				
			}
		}
		
		// Get the total
		$total = 0;
		
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', text = '" . $this->db->escape($order_total['text']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "' WHERE order_id = '" . (int)$order_id . "'"); 	
	
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function editOrder($order_id, $data) {
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}	
		
		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		
		if ($zone_info) {
			$shipping_zone = $zone_info['name'];
		} else {
			$shipping_zone = '';			
		}	
					
		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);
		
		if ($country_info) {
			$payment_country = $country_info['name'];
			$payment_address_format = $country_info['address_format'];			
		} else {
			$payment_country = '';	
			$payment_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}		


		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET 
		`store_id` = '" . (int)$data['store_id'] . "', 
		`customer_id` = '" . (int)$data['customer_id'] . "', 
		`customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
		`email` = '" . $this->db->escape($data['email']) . "', 
		`telephone` = '" . $this->db->escape($data['telephone']) . "', 
		`fax` = '" . $this->db->escape($data['fax']) . "', 
		`payment_company` = '" . $this->db->escape($data['payment_company']) . "', 
		`payment_address_1` = '" . $this->db->escape($data['payment_address_1']) . "', 
		`payment_address_2` = '" . $this->db->escape($data['payment_address_2']) . "', 
		`payment_city` = '" . $this->db->escape($data['payment_city']) . "', 
		`payment_postcode` = '" . $this->db->escape($data['payment_postcode']) . "', 
		`payment_country` = '" . $this->db->escape($payment_country) . "', 
		`payment_country_id` = '" . (int)$data['payment_country_id'] . "', 
		`payment_zone` = '" . $this->db->escape($payment_zone) . "', 
		`payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', 
		`payment_address_format` = '" . $this->db->escape($payment_address_format) . "', 
		`payment_method` = '" . $this->db->escape($data['payment_method']) . "', 
		`payment_code` = '" . $this->db->escape($data['payment_code']) . "', 
		`shipping_company` = '" . $this->db->escape($data['shipping_company']) . "', 
		`shipping_address_1` = '" . $this->db->escape($data['shipping_address_1']) . "', 
		`shipping_address_2` = '" . $this->db->escape($data['shipping_address_2']) . "', 
		`shipping_city` = '" . $this->db->escape($data['shipping_city']) . "', 
		`shipping_postcode` = '" . $this->db->escape($data['shipping_postcode']) . "', 
		`shipping_country` = '" . $this->db->escape($shipping_country) . "', 
		`shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', 
		`shipping_zone` = '" . $this->db->escape($shipping_zone) . "', 
		`shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', 
		`shipping_address_format` = '" . $this->db->escape($shipping_address_format) . "', 
		`shipping_method` = '" . $this->db->escape($data['shipping_method']) . "', 
		`shipping_code` = '" . $this->db->escape($data['shipping_code']) . "', 
		`comment` = '" . $this->db->escape($data['comment']) . "', 
		`order_status_id` = '" . (int)$data['order_status_id'] . "', 
		`language_id` = '" . (int)$this->config->get('config_language_id') . "', 
		`date_modified` = now() WHERE `order_id` = '" . (int)$order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'"); 
       	$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		
      	if (isset($data['order_product'])) {		
      		foreach ($data['order_product'] as $order_product) {	
      			
				// Valores float de price y total que llegan como strings
				$price = floatval(preg_replace("/[^-0-9\.]/","",$order_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/","",$order_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET 
				order_product_id = '" . (int)$order_product['order_product_id'] . "', 
				order_id = '" . (int)$order_id . "', 
				product_id = '" . (int)$order_product['product_id'] . "', 
				name = '" . $this->db->escape($order_product['name']) . "', 
				model = '" . $this->db->escape($order_product['model']) . "', quantity = '" . (int)$order_product['quantity'] . "', price = '" . $price . "', total = '" . $total . "', tax = '" . $order_product['tax'] . "'");
				
				$order_product_id = $this->db->getLastId();
	
				if (isset($order_product['order_option'])) {
					foreach ($order_product['order_option'] as $order_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_option_id = '" . (int)$order_option['order_option_id'] . "', order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$order_option['product_option_id'] . "', product_option_value_id = '" . (int)$order_option['product_option_value_id'] . "', name = '" . $this->db->escape($order_option['name']) . "', `value` = '" . $this->db->escape($order_option['value']) . "', `type` = '" . $this->db->escape($order_option['type']) . "'");
					}
				}
			}
		}
		
		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
		
		if (isset($data['order_total'])) {		
      		foreach ($data['order_total'] as $order_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_total_id = '" . (int)$order_total['order_total_id'] . "', order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($order_total['code']) . "', title = '" . $this->db->escape($order_total['title']) . "', text = '" . $this->db->escape($order_total['text']) . "', `value` = '" . (float)$order_total['value'] . "', sort_order = '" . (int)$order_total['sort_order'] . "'");
			}
			
			$total += $order_total['value'];
		}

		// Update order total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total . "' WHERE order_id = '" . (int)$order_id . "'"); 	

	}

	public function deleteOrder($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT o.*, c.company as company
			FROM `" . DB_PREFIX . "order` o 
			LEFT JOIN " . DB_PREFIX . "customer c ON o.customer_id=c.customer_id  
			WHERE o.order_id = '" . (int)$order_id . "'");
		
		
		if ($order_query->num_rows) {
		
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
			
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}
			
			return array(
				'order_id'              => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'company'                 => $order_query->row['company'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_company_id'      => $order_query->row['payment_company_id'],
				'payment_tax_id'          => $order_query->row['payment_tax_id'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'       => $order_query->row['order_status_id'],
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],					
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, o.shipping_company, os.name AS `status`, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, c.company AS company FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_status` os ON o.order_status_id = os.order_status_id LEFT JOIN `" . DB_PREFIX . "customer` c ON o.customer_id = c.customer_id WHERE os.language_id = '" . $this->config->get('config_language_id') . "'";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_company'])) {
			$sql .= " AND c.company LIKE '" . $this->db->escape(utf8_strtolower($data['filter_company'])) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'company',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT op.*, p.image as image FROM " . DB_PREFIX . "order_product op left join " . DB_PREFIX . "product p on  op.product_id = p.product_id WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$invocie_id . "' AND order_option_id = '" . (int)$invocie_option_id . "'");

		return $query->row;
	}

	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_company'])) {
			$sql .= " AND payment_company LIKE '%" . $this->db->escape($data['filter_company']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOrdersByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalOrdersByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$order_status_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE language_id = '" . (int)$language_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrdersByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE currency_id = '" . (int)$currency_id . "' AND order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < CURDATE() ");

		return $query->row['total'];
	}

	public function getTotalSalesLastYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ");

		return $query->row['total'];
	}

	public function addOrderHistory($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$order_info = $this->getOrder($order_id);

		

      	if ($data['notify']) {
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);

			$message  = $language->get('text_order') . ' ' . $order_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
			if ($order_status_query->num_rows) {
				$message .= $language->get('text_order_status') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}
			
			if ($order_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}
			
			if ($data['comment']) {
				$message .= $language->get('text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $language->get('text_footer');

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalOrderHistories($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}

	public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_status_id = '" . (int)$order_status_id . "'");

		return $query->row['total'];
	}

	public function getOrderShippingCodes(){
		$results = $this->model_setting_extension->getExtensions('shipping');
					
		$cshipping =array();
					
		foreach ($results as $result) {
	
			if ($this->config->get($result['code'] . '_status')) {
							
				$this->load->language('shipping/'.$result['code']);

				$cshipping[] = array( 
					'shipping_method'  => $this->language->get('heading_title'),
					'shipping_code'    => $result['code']
				);
				
			}
		}
		
		return $cshipping ;
	}

}


?>