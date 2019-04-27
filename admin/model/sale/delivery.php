<?php 

class ModelSaleDelivery extends Model {
	public function addDelivery($data) {
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
		
		$sql = "INSERT INTO `" . DB_PREFIX . "delivery` SET 
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
			 `delivery_status_id` = '" . (int)$data['delivery_status_id'] . "', 
			 `language_id` = '" . (int)$this->config->get('config_language_id') . "', 
			 `currency_id` = '" . (int)$currency_id . "', 
			 `currency_code` = '" . $this->db->escape($currency_code) . "', 
			 `currency_value` = '" . (float)$currency_value . "',  
			 `date_added` = now(), 
			 `date_modified` = now()";

		$this->db->query($sql);

      	$delivery_id = $this->db->getLastId();
		
      	if (isset($data['delivery_product'])) {		

      		foreach ($data['delivery_product'] as $delivery_product) {	
      		
				// Valores float de price y total que llegan como strings
				$price = floatval(preg_replace("/[^-0-9\.]/","",$delivery_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/","",$delivery_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_product SET 
				delivery_product_id = '" . (int)$delivery_product['delivery_product_id'] . "', 
				delivery_id = '" . (int)$delivery_id . "', 
				product_id = '" . (int)$delivery_product['product_id'] . "', 
				name = '" . $this->db->escape($delivery_product['name']) . "', 
				model = '" . $this->db->escape($delivery_product['model']) . "', quantity = '" . (int)$delivery_product['quantity'] . "', price = '" . $price . "', total = '" . $total . "', tax = '" . $delivery_product['tax'] . "'");
				
				$delivery_product_id = $this->db->getLastId();
	
				if (isset($delivery_product['delivery_option'])) {
					foreach ($delivery_product['delivery_option'] as $delivery_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_option SET delivery_id = '" . (int)$delivery_id . "', delivery_product_id = '" . (int)$delivery_product_id . "', product_option_id = '" . (int)$delivery_option['product_option_id'] . "', product_option_value_id = '" . (int)$delivery_option['product_option_value_id'] . "', name = '" . $this->db->escape($delivery_option['name']) . "', `value` = '" . $this->db->escape($delivery_option['value']) . "', `type` = '" . $this->db->escape($delivery_option['type']) . "'");
					}
				}
				
			}
		}
		
		// Get the total
		$total = 0;
		
		if (isset($data['delivery_total'])) {		
      		foreach ($data['delivery_total'] as $delivery_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_total SET delivery_id = '" . (int)$delivery_id . "', code = '" . $this->db->escape($delivery_total['code']) . "', title = '" . $this->db->escape($delivery_total['title']) . "', text = '" . $this->db->escape($delivery_total['text']) . "', `value` = '" . (float)$delivery_total['value'] . "', sort_order = '" . (int)$delivery_total['sort_order'] . "'");
			}
			
			$total += $delivery_total['value'];
		}

		// Update delivery total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "delivery` SET total = '" . (float)$total . "' WHERE delivery_id = '" . (int)$delivery_id . "'"); 	
	}

	public function getDeliveryTotals($delivery_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_total WHERE delivery_id = '" . (int)$delivery_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function editDelivery($delivery_id, $data) {
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


		$this->db->query("UPDATE `" . DB_PREFIX . "delivery` SET 
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
		`delivery_status_id` = '" . (int)$data['invoice_status_id'] . "', 
		`date_modified` = now() WHERE `delivery_id` = '" . (int)$delivery_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_product WHERE delivery_id = '" . (int)$delivery_id . "'"); 
       	$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_option WHERE delivery_id = '" . (int)$delivery_id . "'");
		
      	if (isset($data['delivery_product'])) {		
      		foreach ($data['delivery_product'] as $delivery_product) {
				
				// Valores float de price y total que llegan como strings
				$price = floatval(preg_replace("/[^-0-9\.]/","",$delivery_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/","",$delivery_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_product SET 
				delivery_product_id = '" . (int)$delivery_product['delivery_product_id'] . "', 
				delivery_id = '" . (int)$delivery_id . "', 
				product_id = '" . (int)$delivery_product['product_id'] . "', 
				name = '" . $this->db->escape($delivery_product['name']) . "', 
				model = '" . $this->db->escape($delivery_product['model']) . "', quantity = '" . (int)$delivery_product['quantity'] . "', price = '" . $price . "', total = '" . $total . "', tax = '" . $delivery_product['tax'] . "'");
			
				$delivery_product_id = $this->db->getLastId();
	
				if (isset($delivery_product['delivery_option'])) {
					foreach ($delivery_product['delivery_option'] as $delivery_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_option SET delivery_option_id = '" . (int)$delivery_option['delivery_option_id'] . "', delivery_id = '" . (int)$delivery_id . "', delivery_product_id = '" . (int)$delivery_product_id . "', product_option_id = '" . (int)$delivery_option['product_option_id'] . "', product_option_value_id = '" . (int)$delivery_option['product_option_value_id'] . "', name = '" . $this->db->escape($delivery_option['name']) . "', `value` = '" . $this->db->escape($delivery_option['value']) . "', `type` = '" . $this->db->escape($delivery_option['type']) . "'");
					}
				}
			}
		}
		
		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_total WHERE delivery_id = '" . (int)$delivery_id . "'");
		
		if (isset($data['delivery_total'])) {		
      		foreach ($data['delivery_total'] as $delivery_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_total SET delivery_total_id = '" . (int)$delivery_total['delivery_total_id'] . "', delivery_id = '" . (int)$delivery_id . "', code = '" . $this->db->escape($delivery_total['code']) . "', title = '" . $this->db->escape($delivery_total['title']) . "', text = '" . $this->db->escape($delivery_total['text']) . "', `value` = '" . (float)$delivery_total['value'] . "', sort_order = '" . (int)$delivery_total['sort_order'] . "'");
			}
			
			$total += $delivery_total['value'];
		}

		// Update delivery total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "delivery` SET total = '" . (float)$total . "' WHERE delivery_id = '" . (int)$delivery_id . "'"); 	

	}

	public function deleteDelivery($delivery_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery WHERE delivery_id = '" . (int)$delivery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_product WHERE delivery_id = '" . (int)$delivery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_option WHERE delivery_id = '" . (int)$delivery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "delivery_total WHERE delivery_id = '" . (int)$delivery_id . "'");
	}

	public function getDelivery($delivery_id) {
		$delivery_query = $this->db->query("SELECT o.*, c.company as company
			FROM `" . DB_PREFIX . "delivery` o 
		LEFT JOIN " . DB_PREFIX . "customer c ON o.customer_id=c.customer_id  
		WHERE o.delivery_id = '" . (int)$delivery_id . "'");
		
		
		if ($delivery_query->num_rows) {
		
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$delivery_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$delivery_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$delivery_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$delivery_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($delivery_query->row['language_id']);
			
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
				'delivery_id'              => $delivery_query->row['delivery_id'],
				'invoice_no'              => $delivery_query->row['invoice_no'],
				'invoice_prefix'          => $delivery_query->row['invoice_prefix'],
				'store_id'                => $delivery_query->row['store_id'],
				'store_name'              => $delivery_query->row['store_name'],
				'store_url'               => $delivery_query->row['store_url'],
				'customer_id'             => $delivery_query->row['customer_id'],
				'company'                 => $delivery_query->row['company'],
				'customer_group_id'       => $delivery_query->row['customer_group_id'],
				'telephone'               => $delivery_query->row['telephone'],
				'fax'                     => $delivery_query->row['fax'],
				'email'                   => $delivery_query->row['email'],
				'payment_company'         => $delivery_query->row['payment_company'],
				'payment_company_id'      => $delivery_query->row['payment_company_id'],
				'payment_tax_id'          => $delivery_query->row['payment_tax_id'],
				'payment_address_1'       => $delivery_query->row['payment_address_1'],
				'payment_address_2'       => $delivery_query->row['payment_address_2'],
				'payment_postcode'        => $delivery_query->row['payment_postcode'],
				'payment_city'            => $delivery_query->row['payment_city'],
				'payment_zone_id'         => $delivery_query->row['payment_zone_id'],
				'payment_zone'            => $delivery_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $delivery_query->row['payment_country_id'],
				'payment_country'         => $delivery_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $delivery_query->row['payment_address_format'],
				'payment_method'          => $delivery_query->row['payment_method'],
				'payment_code'            => $delivery_query->row['payment_code'],				
				'shipping_company'        => $delivery_query->row['shipping_company'],
				'shipping_address_1'      => $delivery_query->row['shipping_address_1'],
				'shipping_address_2'      => $delivery_query->row['shipping_address_2'],
				'shipping_postcode'       => $delivery_query->row['shipping_postcode'],
				'shipping_city'           => $delivery_query->row['shipping_city'],
				'shipping_zone_id'        => $delivery_query->row['shipping_zone_id'],
				'shipping_zone'           => $delivery_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $delivery_query->row['shipping_country_id'],
				'shipping_country'        => $delivery_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $delivery_query->row['shipping_address_format'],
				'shipping_method'         => $delivery_query->row['shipping_method'],
				'shipping_code'           => $delivery_query->row['shipping_code'],
				'comment'                 => $delivery_query->row['comment'],
				'total'                   => $delivery_query->row['total'],
				'invoice_status_id'       => $delivery_query->row['delivery_status_id'],
				'commission'              => $delivery_query->row['commission'],
				'language_id'             => $delivery_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $delivery_query->row['currency_id'],
				'currency_code'           => $delivery_query->row['currency_code'],
				'currency_value'          => $delivery_query->row['currency_value'],					
				'date_added'              => $delivery_query->row['date_added'],
				'date_modified'           => $delivery_query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getDeliveries($data = array()) {
		$sql = "SELECT o.delivery_id, c.company AS company, os.name AS `status`, os.color, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX."delivery` o LEFT JOIN `".DB_PREFIX."invoice_status` os ON o.delivery_status_id = os.invoice_status_id LEFT JOIN `".DB_PREFIX."customer` c ON o.customer_id = c.customer_id WHERE os.language_id = '" . $this->config->get('config_language_id') . "'";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " AND o.delivery_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " AND o.delivery_status_id != '0'";
		}

		if (!empty($data['filter_delivery_id'])) {
			$sql .= " AND o.delivery_id = '" . (int)$data['filter_delivery_id'] . "'";
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
			'o.delivery_id',
			'company',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.delivery_id";
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

	public function getDeliveryProducts($delivery_id) {
		$query = $this->db->query("SELECT op.*, p.image as image FROM " . DB_PREFIX . "delivery_product op left join " . DB_PREFIX . "product p on  op.product_id = p.product_id WHERE delivery_id = '" . (int)$delivery_id . "'");

		return $query->rows;
	}

	public function getDeliveryOption($delivery_id, $delivery_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_option WHERE delivery_id = '" . (int)$invocie_id . "' AND delivery_option_id = '" . (int)$invocie_option_id . "'");

		return $query->row;
	}

	public function getDeliveryOptions($delivery_id, $delivery_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delivery_option WHERE delivery_id = '" . (int)$delivery_id . "' AND delivery_product_id = '" . (int)$delivery_product_id . "'");

		return $query->rows;
	}

	public function getTotalDeliveries($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "delivery`";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " WHERE delivery_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " WHERE delivery_status_id > '0'";
		}

		if (!empty($data['filter_delivery_id'])) {
			$sql .= " AND delivery_id = '" . (int)$data['filter_delivery_id'] . "'";
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

	public function getTotalDeliveriesByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "delivery` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalDeliveriesByDeliveryStatusId($delivery_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "delivery` WHERE delivery_status_id = '" . (int)$delivery_status_id . "' AND delivery_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalDeliveriesByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "delivery` WHERE language_id = '" . (int)$language_id . "' AND delivery_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalDeliveriesByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "delivery` WHERE currency_id = '" . (int)$currency_id . "' AND delivery_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "delivery` WHERE delivery_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "delivery` WHERE delivery_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < CURDATE() ");

		return $query->row['total'];
	}

	public function getTotalSalesLastYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "delivery` WHERE delivery_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ");

		return $query->row['total'];
	}

	public function addDeliveryHistory($delivery_id, $data) {
		
		$this->db->query("UPDATE `" . DB_PREFIX . "delivery` SET delivery_status_id = '" . (int)$data['delivery_status_id'] . "', date_modified = NOW() WHERE delivery_id = '" . (int)$delivery_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "delivery_history SET delivery_id = '" . (int)$delivery_id . "', delivery_status_id = '" . (int)$data['delivery_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$delivery_info = $this->getDelivery($delivery_id);
	}

	public function getDeliveryHistories($delivery_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "delivery_history oh LEFT JOIN " . DB_PREFIX . "invoice_status os ON oh.delivery_status_id = os.invoice_status_id WHERE oh.delivery_id = '" . (int)$delivery_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalDeliveryHistories($delivery_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "delivery_history WHERE delivery_id = '" . (int)$delivery_id . "'");

		return $query->row['total'];
	}

	public function getTotalDeliveryHistoriesByDeliveryStatusId($delivery_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "delivery_history WHERE delivery_status_id = '" . (int)$delivery_status_id . "'");

		return $query->row['total'];
	}

	public function getDeliveryShippingCodes(){
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