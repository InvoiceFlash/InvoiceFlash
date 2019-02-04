<?php 

class ModelSaleQuote extends Model {
	public function addQuote($data) {
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
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
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
		
		$sql = "INSERT INTO `" . DB_PREFIX . "quote` SET 
			`invoice_prefix` = '" . $this->db->escape($invoice_prefix) . "',
			 `store_id` = '" . (int)$data['store_id'] . "', 
			 `store_name` = '" . $this->db->escape($store_name) . "', 
			 `store_url` = '" . $this->db->escape($store_url) . "', 
			 `customer_id` = '" . (int)$data['customer_id'] . "', 
			 `customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
			 `firstname` = '" . $this->db->escape($data['firstname']) . "', 
			 `lastname` = '" . $this->db->escape($data['lastname']) . "', 
			 `email` = '" . $this->db->escape($data['email']) . "', 
			 `telephone` = '" . $this->db->escape($data['telephone']) . "', 
			 `fax` = '" . $this->db->escape($data['fax']) . "', 
			 `payment_firstname` = '" . $this->db->escape($data['payment_firstname']) . "', 
			 `payment_lastname` = '" . $this->db->escape($data['payment_lastname']) . "', 
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
			 `shipping_firstname` = '" . $this->db->escape($data['shipping_firstname']) . "', 
			 `shipping_lastname` = '" . $this->db->escape($data['shipping_lastname']) . "', 
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
			 `quote_status_id` = '" . (int)$data['invoice_status_id'] . "', 
			 `language_id` = '" . (int)$this->config->get('config_language_id') . "', 
			 `currency_id` = '" . (int)$currency_id . "', 
			 `currency_code` = '" . $this->db->escape($currency_code) . "', 
			 `currency_value` = '" . (float)$currency_value . "',  
			 `date_added` = now(), 
			 `date_modified` = now()";

		$this->db->query($sql);

      	$quote_id = $this->db->getLastId();
		
      	if (isset($data['quote_product'])) {		

      		foreach ($data['quote_product'] as $quote_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "quote_product SET 
					quote_id = '" . (int)$quote_id . "', 
					product_id = '" . (int)$quote_product['product_id'] . "', 
					name = '" . $this->db->escape($quote_product['name']) . "', 
					model = '" . $this->db->escape($quote_product['model']) . "', quantity = '" . (int)$quote_product['quantity'] . "', price = '" . (float)$quote_product['price'] . "', total = '" . (float)$quote_product['total'] . "', tax = '" . (float)$quote_product['tax'] . "'");
				
				//name_ext = '" . $this->db->escape($quote_product['name_ext']) . "', 
				
				$quote_product_id = $this->db->getLastId();
	
				if (isset($quote_product['quote_option'])) {
					foreach ($quote_product['quote_option'] as $quote_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "quote_option SET quote_id = '" . (int)$quote_id . "', quote_product_id = '" . (int)$quote_product_id . "', product_option_id = '" . (int)$quote_option['product_option_id'] . "', product_option_value_id = '" . (int)$quote_option['product_option_value_id'] . "', name = '" . $this->db->escape($quote_option['name']) . "', `value` = '" . $this->db->escape($quote_option['value']) . "', `type` = '" . $this->db->escape($quote_option['type']) . "'");
					}
				}
				
			}
		}
		
		// Get the total
		$total = 0;
		
		if (isset($data['quote_total'])) {		
      		foreach ($data['quote_total'] as $quote_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "quote_total SET quote_id = '" . (int)$quote_id . "', code = '" . $this->db->escape($quote_total['code']) . "', title = '" . $this->db->escape($quote_total['title']) . "', text = '" . $this->db->escape($quote_total['text']) . "', `value` = '" . (float)$quote_total['value'] . "', sort_order = '" . (int)$quote_total['sort_order'] . "'");
			}
			
			$total += $quote_total['value'];
		}

		// Update quote total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET total = '" . (float)$total . "' WHERE quote_id = '" . (int)$quote_id . "'"); 	
	}

	public function getQuoteTotals($quote_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_total WHERE quote_id = '" . (int)$quote_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function editQuote($quote_id, $data) {
		$this->load->model('localisation/country');
		
		$this->load->model('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);
		
		if ($country_info) {
			$shipping_country = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country = '';	
			$shipping_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
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
			$payment_address_format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';					
		}
	
		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		
		if ($zone_info) {
			$payment_zone = $zone_info['name'];
		} else {
			$payment_zone = '';			
		}		


		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET 
		`store_id` = '" . (int)$data['store_id'] . "', 
		`customer_id` = '" . (int)$data['customer_id'] . "', 
		`customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
		`firstname` = '" . $this->db->escape($data['firstname']) . "', 
		`lastname` = '" . $this->db->escape($data['lastname']) . "', 
		`email` = '" . $this->db->escape($data['email']) . "', 
		`telephone` = '" . $this->db->escape($data['telephone']) . "', 
		`fax` = '" . $this->db->escape($data['fax']) . "', 
		`payment_firstname` = '" . $this->db->escape($data['payment_firstname']) . "', 
		`payment_lastname` = '" . $this->db->escape($data['payment_lastname']) . "', 
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
		`shipping_firstname` = '" . $this->db->escape($data['shipping_firstname']) . "', 
		`shipping_lastname` = '" . $this->db->escape($data['shipping_lastname']) . "', 
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
		`quote_status_id` = '" . (int)$data['invoice_status_id'] . "', 
		`language_id` = '" . (int)$this->config->get('config_language_id') . "', 
		`date_modified` = now() WHERE `quote_id` = '" . (int)$quote_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'"); 
       	$this->db->query("DELETE FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "'");
		
      	if (isset($data['quote_product'])) {		
      		foreach ($data['quote_product'] as $quote_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "quote_product SET 
					quote_product_id = '" . (int)$quote_product['quote_product_id'] . "', 
					quote_id = '" . (int)$quote_id . "', 
					product_id = '" . (int)$quote_product['product_id'] . "', 
					name = '" . $this->db->escape($quote_product['name']) . "', 
					model = '" . $this->db->escape($quote_product['model']) . "', quantity = '" . (int)$quote_product['quantity'] . "', price = '" . (float)$quote_product['price'] . "', total = '" . (float)$quote_product['total'] . "', tax = '" . (float)$quote_product['tax'] . "'");
			
				$quote_product_id = $this->db->getLastId();
	
				if (isset($quote_product['quote_option'])) {
					foreach ($quote_product['quote_option'] as $quote_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "quote_option SET quote_option_id = '" . (int)$quote_option['quote_option_id'] . "', quote_id = '" . (int)$quote_id . "', quote_product_id = '" . (int)$quote_product_id . "', product_option_id = '" . (int)$quote_option['product_option_id'] . "', product_option_value_id = '" . (int)$quote_option['product_option_value_id'] . "', name = '" . $this->db->escape($quote_option['name']) . "', `value` = '" . $this->db->escape($quote_option['value']) . "', `type` = '" . $this->db->escape($quote_option['type']) . "'");
					}
				}
			}
		}
		
		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_total WHERE quote_id = '" . (int)$quote_id . "'");
		
		if (isset($data['quote_total'])) {		
      		foreach ($data['quote_total'] as $quote_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "quote_total SET quote_total_id = '" . (int)$quote_total['quote_total_id'] . "', quote_id = '" . (int)$quote_id . "', code = '" . $this->db->escape($quote_total['code']) . "', title = '" . $this->db->escape($quote_total['title']) . "', text = '" . $this->db->escape($quote_total['text']) . "', `value` = '" . (float)$quote_total['value'] . "', sort_order = '" . (int)$quote_total['sort_order'] . "'");
			}
			
			$total += $quote_total['value'];
		}

		// Update quote total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET total = '" . (float)$total . "' WHERE quote_id = '" . (int)$quote_id . "'"); 	

	}

	public function deleteQuote($quote_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "quote WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_total WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "receipt WHERE quote_id = '" . (int)$quote_id . "'");
	}

	public function getQuote($quote_id) {
		$quote_query = $this->db->query("SELECT o.*, CONCAT(c.firstname, ' ', c.lastname) as customer, c.company as company
			FROM `" . DB_PREFIX . "quote` o 
		LEFT JOIN " . DB_PREFIX . "customer c ON o.customer_id=c.customer_id  
		WHERE o.quote_id = '" . (int)$quote_id . "'");
		
		
		if ($quote_query->num_rows) {
		
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$quote_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$quote_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$quote_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$quote_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($quote_query->row['language_id']);
			
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
				'quote_id'              => $quote_query->row['quote_id'],
				'invoice_no'              => $quote_query->row['invoice_no'],
				'invoice_prefix'          => $quote_query->row['invoice_prefix'],
				'store_id'                => $quote_query->row['store_id'],
				'store_name'              => $quote_query->row['store_name'],
				'store_url'               => $quote_query->row['store_url'],
				'customer_id'             => $quote_query->row['customer_id'],
				'company'                 => $quote_query->row['company'],
				'customer'                => $quote_query->row['customer'],
				'customer_group_id'       => $quote_query->row['customer_group_id'],
				'firstname'               => $quote_query->row['firstname'],
				'lastname'                => $quote_query->row['lastname'],
				'telephone'               => $quote_query->row['telephone'],
				'fax'                     => $quote_query->row['fax'],
				'email'                   => $quote_query->row['email'],
				'payment_firstname'       => $quote_query->row['payment_firstname'],
				'payment_lastname'        => $quote_query->row['payment_lastname'],
				'payment_company'         => $quote_query->row['payment_company'],
				'payment_company_id'      => $quote_query->row['payment_company_id'],
				'payment_tax_id'          => $quote_query->row['payment_tax_id'],
				'payment_address_1'       => $quote_query->row['payment_address_1'],
				'payment_address_2'       => $quote_query->row['payment_address_2'],
				'payment_postcode'        => $quote_query->row['payment_postcode'],
				'payment_city'            => $quote_query->row['payment_city'],
				'payment_zone_id'         => $quote_query->row['payment_zone_id'],
				'payment_zone'            => $quote_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $quote_query->row['payment_country_id'],
				'payment_country'         => $quote_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $quote_query->row['payment_address_format'],
				'payment_method'          => $quote_query->row['payment_method'],
				'payment_code'            => $quote_query->row['payment_code'],				
				'shipping_firstname'      => $quote_query->row['shipping_firstname'],
				'shipping_lastname'       => $quote_query->row['shipping_lastname'],
				'shipping_company'        => $quote_query->row['shipping_company'],
				'shipping_address_1'      => $quote_query->row['shipping_address_1'],
				'shipping_address_2'      => $quote_query->row['shipping_address_2'],
				'shipping_postcode'       => $quote_query->row['shipping_postcode'],
				'shipping_city'           => $quote_query->row['shipping_city'],
				'shipping_zone_id'        => $quote_query->row['shipping_zone_id'],
				'shipping_zone'           => $quote_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $quote_query->row['shipping_country_id'],
				'shipping_country'        => $quote_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $quote_query->row['shipping_address_format'],
				'shipping_method'         => $quote_query->row['shipping_method'],
				'shipping_code'           => $quote_query->row['shipping_code'],
				'comment'                 => $quote_query->row['comment'],
				'total'                   => $quote_query->row['total'],
				'invoice_status_id'       => $quote_query->row['quote_status_id'],
				'commission'              => $quote_query->row['commission'],
				'language_id'             => $quote_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $quote_query->row['currency_id'],
				'currency_code'           => $quote_query->row['currency_code'],
				'currency_value'          => $quote_query->row['currency_value'],					
				'date_added'              => $quote_query->row['date_added'],
				'date_modified'           => $quote_query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getQuotes($data = array()) {
		$sql = "SELECT o.quote_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.shipping_company, os.name AS `status`, os.color, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `".DB_PREFIX."quote` o LEFT JOIN `".DB_PREFIX."invoice_status` os ON o.quote_status_id = os.invoice_status_id LEFT JOIN `".DB_PREFIX."customer` c ON o.customer_id = c.customer_id WHERE os.language_id = '" . $this->config->get('config_language_id') . "'";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " AND o.quote_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " AND o.quote_status_id != '0'";
		}

		if (!empty($data['filter_quote_id'])) {
			$sql .= " AND o.quote_id = '" . (int)$data['filter_quote_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_customer'])) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.quote_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.quote_id";
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

	public function getQuoteProducts($quote_id) {
		$query = $this->db->query("SELECT op.*, p.image as image FROM " . DB_PREFIX . "quote_product op left join " . DB_PREFIX . "product p on  op.product_id = p.product_id WHERE quote_id = '" . (int)$quote_id . "'");

		return $query->rows;
	}

	public function getQuoteOption($quote_id, $quote_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$invocie_id . "' AND quote_option_id = '" . (int)$invocie_option_id . "'");

		return $query->row;
	}

	public function getInoviceOptions($quote_id, $quote_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "' AND quote_product_id = '" . (int)$quote_product_id . "'");

		return $query->rows;
	}

	public function getTotalQuotes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote`";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " WHERE quote_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " WHERE quote_status_id > '0'";
		}

		if (!empty($data['filter_quote_id'])) {
			$sql .= " AND quote_id = '" . (int)$data['filter_quote_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
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

	public function getTotalQuotesByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalQuotesByQuoteStatusId($quote_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote` WHERE quote_status_id = '" . (int)$quote_status_id . "' AND quote_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalQuotesByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote` WHERE language_id = '" . (int)$language_id . "' AND quote_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalQuotesByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote` WHERE currency_id = '" . (int)$currency_id . "' AND quote_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "quote` WHERE quote_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "quote` WHERE quote_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < CURDATE() ");

		return $query->row['total'];
	}

	public function getTotalSalesLastYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "quote` WHERE quote_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ");

		return $query->row['total'];
	}

	public function addQuoteHistory($quote_id, $data) {
		
		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET quote_status_id = '" . (int)$data['quote_status_id'] . "', date_modified = NOW() WHERE quote_id = '" . (int)$quote_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "quote_history SET quote_id = '" . (int)$quote_id . "', quote_status_id = '" . (int)$data['quote_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$quote_info = $this->getQuote($quote_id);
	}

	public function getQuoteHistories($quote_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "quote_history oh LEFT JOIN " . DB_PREFIX . "invoice_status os ON oh.quote_status_id = os.invoice_status_id WHERE oh.quote_id = '" . (int)$quote_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalQuoteHistories($quote_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quote_history WHERE quote_id = '" . (int)$quote_id . "'");

		return $query->row['total'];
	}

	public function getTotalQuoteHistoriesByQuoteStatusId($quote_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "quote_history WHERE quote_status_id = '" . (int)$quote_status_id . "'");

		return $query->row['total'];
	}

	public function getQuoteShippingCodes(){
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