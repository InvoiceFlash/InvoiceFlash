<?php 

class ModelSaleInvoice extends Model {
	public function addInvoice($data) {
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
		
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET 
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
			 `invoice_status_id` = '" . (int)$data['invoice_status_id'] . "', 
			 `language_id` = '" . (int)$this->config->get('config_language_id') . "', 
			 `currency_id` = '" . (int)$currency_id . "', 
			 `currency_code` = '" . $this->db->escape($currency_code) . "', 
			 `currency_value` = '" . (float)$currency_value . "',  
			 `date_added` = now(), 
			 `date_modified` = now()";

		$this->db->query($sql);

      	$invoice_id = $this->db->getLastId();
		
      	if (isset($data['invoice_product'])) {		

      		foreach ($data['invoice_product'] as $invoice_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_product SET 
					invoice_id = '" . (int)$invoice_id . "', 
					product_id = '" . (int)$invoice_product['product_id'] . "', 
					name = '" . $this->db->escape($invoice_product['name']) . "', 
					model = '" . $this->db->escape($invoice_product['model']) . "', quantity = '" . (int)$invoice_product['quantity'] . "', price = '" . (float)$invoice_product['price'] . "', total = '" . (float)$invoice_product['total'] . "', tax = '" . (float)$invoice_product['tax'] . "'");
				
				//name_ext = '" . $this->db->escape($invoice_product['name_ext']) . "', 
				
				$invoice_product_id = $this->db->getLastId();
	
				if (isset($invoice_product['invoice_option'])) {
					foreach ($invoice_product['invoice_option'] as $invoice_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_option SET invoice_id = '" . (int)$invoice_id . "', invoice_product_id = '" . (int)$invoice_product_id . "', product_option_id = '" . (int)$invoice_option['product_option_id'] . "', product_option_value_id = '" . (int)$invoice_option['product_option_value_id'] . "', name = '" . $this->db->escape($invoice_option['name']) . "', `value` = '" . $this->db->escape($invoice_option['value']) . "', `type` = '" . $this->db->escape($invoice_option['type']) . "'");
					}
				}
				
			}
		}
		
		// Get the total
		$total = 0;
		
		if (isset($data['invoice_total'])) {		
      		foreach ($data['invoice_total'] as $invoice_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_total SET invoice_id = '" . (int)$invoice_id . "', code = '" . $this->db->escape($invoice_total['code']) . "', title = '" . $this->db->escape($invoice_total['title']) . "', text = '" . $this->db->escape($invoice_total['text']) . "', `value` = '" . (float)$invoice_total['value'] . "', sort_order = '" . (int)$invoice_total['sort_order'] . "'");
			}
			
			$total += $invoice_total['value'];
		}

		// Update invoice total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "invoice` SET total = '" . (float)$total . "' WHERE invoice_id = '" . (int)$invoice_id . "'"); 	
	
		// Receipt
		$this->db->query("INSERT INTO " . DB_PREFIX . "receipt SET 
			date_due = now(), date_added = now(), invoice_id = '" . (int)$invoice_id . "', amount='" . (float)$total . "'") ;
	}

	public function getInvoiceTotals($invoice_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_total WHERE invoice_id = '" . (int)$invoice_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function editInvoice($invoice_id, $data) {
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


		$this->db->query("UPDATE `" . DB_PREFIX . "invoice` SET 
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
		`invoice_status_id` = '" . (int)$data['invoice_status_id'] . "', 
		`language_id` = '" . (int)$this->config->get('config_language_id') . "', 
		`date_modified` = now() WHERE `invoice_id` = '" . (int)$invoice_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_product WHERE invoice_id = '" . (int)$invoice_id . "'"); 
       	$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_option WHERE invoice_id = '" . (int)$invoice_id . "'");
		
      	if (isset($data['invoice_product'])) {		
      		foreach ($data['invoice_product'] as $invoice_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_product SET 
					invoice_product_id = '" . (int)$invoice_product['invoice_product_id'] . "', 
					invoice_id = '" . (int)$invoice_id . "', 
					product_id = '" . (int)$invoice_product['product_id'] . "', 
					name = '" . $this->db->escape($invoice_product['name']) . "', 
					model = '" . $this->db->escape($invoice_product['model']) . "', quantity = '" . (int)$invoice_product['quantity'] . "', price = '" . (float)$invoice_product['price'] . "', total = '" . (float)$invoice_product['total'] . "', tax = '" . (float)$invoice_product['tax'] . "'");
			
				$invoice_product_id = $this->db->getLastId();
	
				if (isset($invoice_product['invoice_option'])) {
					foreach ($invoice_product['invoice_option'] as $invoice_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_option SET invoice_option_id = '" . (int)$invoice_option['invoice_option_id'] . "', invoice_id = '" . (int)$invoice_id . "', invoice_product_id = '" . (int)$invoice_product_id . "', product_option_id = '" . (int)$invoice_option['product_option_id'] . "', product_option_value_id = '" . (int)$invoice_option['product_option_value_id'] . "', name = '" . $this->db->escape($invoice_option['name']) . "', `value` = '" . $this->db->escape($invoice_option['value']) . "', `type` = '" . $this->db->escape($invoice_option['type']) . "'");
					}
				}
			}
		}
		
		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_total WHERE invoice_id = '" . (int)$invoice_id . "'");
		
		if (isset($data['invoice_total'])) {		
      		foreach ($data['invoice_total'] as $invoice_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_total SET invoice_total_id = '" . (int)$invoice_total['invoice_total_id'] . "', invoice_id = '" . (int)$invoice_id . "', code = '" . $this->db->escape($invoice_total['code']) . "', title = '" . $this->db->escape($invoice_total['title']) . "', text = '" . $this->db->escape($invoice_total['text']) . "', `value` = '" . (float)$invoice_total['value'] . "', sort_order = '" . (int)$invoice_total['sort_order'] . "'");
			}
			
			$total += $invoice_total['value'];
		}

		// Update invoice total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "invoice` SET total = '" . (float)$total . "' WHERE invoice_id = '" . (int)$invoice_id . "'"); 	

	}

	public function deleteInvoice($invoice_id) {
		$invoice_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "invoice` WHERE invoice_status_id > '0' AND invoice_id = '" . (int)$invoice_id . "'");

		if ($invoice_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_product WHERE invoice_id = '" . (int)$invoice_id . "'");

			foreach($product_query->rows as $product) {
				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_option WHERE invoice_id = '" . (int)$invoice_id . "' AND invoice_product_id = '" . (int)$product['invoice_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice WHERE invoice_id = '" . (int)$invoice_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_product WHERE invoice_id = '" . (int)$invoice_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_option WHERE invoice_id = '" . (int)$invoice_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "invoice_total WHERE invoice_id = '" . (int)$invoice_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "receipt WHERE invoice_id = '" . (int)$invoice_id . "'");
	}

	public function getInvoice($invoice_id) {
		$invoice_query = $this->db->query("SELECT o.*, CONCAT(c.firstname, ' ', c.lastname) as customer, c.company as company
			FROM `" . DB_PREFIX . "invoice` o 
		LEFT JOIN " . DB_PREFIX . "customer c ON o.customer_id=c.customer_id  
		WHERE o.invoice_id = '" . (int)$invoice_id . "'");
		
		
		if ($invoice_query->num_rows) {
		
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$invoice_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$invoice_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$invoice_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$invoice_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($invoice_query->row['language_id']);
			
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
				'invoice_id'              => $invoice_query->row['invoice_id'],
				'invoice_no'              => $invoice_query->row['invoice_no'],
				'invoice_prefix'          => $invoice_query->row['invoice_prefix'],
				'store_id'                => $invoice_query->row['store_id'],
				'store_name'              => $invoice_query->row['store_name'],
				'store_url'               => $invoice_query->row['store_url'],
				'customer_id'             => $invoice_query->row['customer_id'],
				'company'                 => $invoice_query->row['company'],
				'customer'                => $invoice_query->row['customer'],
				'customer_group_id'       => $invoice_query->row['customer_group_id'],
				'firstname'               => $invoice_query->row['firstname'],
				'lastname'                => $invoice_query->row['lastname'],
				'telephone'               => $invoice_query->row['telephone'],
				'fax'                     => $invoice_query->row['fax'],
				'email'                   => $invoice_query->row['email'],
				'payment_firstname'       => $invoice_query->row['payment_firstname'],
				'payment_lastname'        => $invoice_query->row['payment_lastname'],
				'payment_company'         => $invoice_query->row['payment_company'],
				'payment_company_id'      => $invoice_query->row['payment_company_id'],
				'payment_tax_id'          => $invoice_query->row['payment_tax_id'],
				'payment_address_1'       => $invoice_query->row['payment_address_1'],
				'payment_address_2'       => $invoice_query->row['payment_address_2'],
				'payment_postcode'        => $invoice_query->row['payment_postcode'],
				'payment_city'            => $invoice_query->row['payment_city'],
				'payment_zone_id'         => $invoice_query->row['payment_zone_id'],
				'payment_zone'            => $invoice_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $invoice_query->row['payment_country_id'],
				'payment_country'         => $invoice_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $invoice_query->row['payment_address_format'],
				'payment_method'          => $invoice_query->row['payment_method'],
				'payment_code'            => $invoice_query->row['payment_code'],				
				'shipping_firstname'      => $invoice_query->row['shipping_firstname'],
				'shipping_lastname'       => $invoice_query->row['shipping_lastname'],
				'shipping_company'        => $invoice_query->row['shipping_company'],
				'shipping_address_1'      => $invoice_query->row['shipping_address_1'],
				'shipping_address_2'      => $invoice_query->row['shipping_address_2'],
				'shipping_postcode'       => $invoice_query->row['shipping_postcode'],
				'shipping_city'           => $invoice_query->row['shipping_city'],
				'shipping_zone_id'        => $invoice_query->row['shipping_zone_id'],
				'shipping_zone'           => $invoice_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $invoice_query->row['shipping_country_id'],
				'shipping_country'        => $invoice_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $invoice_query->row['shipping_address_format'],
				'shipping_method'         => $invoice_query->row['shipping_method'],
				'shipping_code'           => $invoice_query->row['shipping_code'],
				'comment'                 => $invoice_query->row['comment'],
				'total'                   => $invoice_query->row['total'],
				'invoice_status_id'       => $invoice_query->row['invoice_status_id'],
				'commission'              => $invoice_query->row['commission'],
				'language_id'             => $invoice_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $invoice_query->row['currency_id'],
				'currency_code'           => $invoice_query->row['currency_code'],
				'currency_value'          => $invoice_query->row['currency_value'],					
				'date_added'              => $invoice_query->row['date_added'],
				'date_modified'           => $invoice_query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getInvoices($data = array()) {
		$sql = "SELECT o.invoice_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, o.shipping_company, os.name AS `status`, os.color, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, '' AS company FROM `" . DB_PREFIX . "invoice` o LEFT JOIN `" . DB_PREFIX . "invoice_status` os ON o.invoice_status_id = os.invoice_status_id LEFT JOIN `" . DB_PREFIX . "customer` c ON o.customer_id = c.customer_id WHERE os.language_id = '" . $this->config->get('config_language_id') . "'";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " AND o.invoice_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " AND o.invoice_status_id > '0'";
		}

		if (!empty($data['filter_invoice_id'])) {
			$sql .= " AND o.invoice_id = '" . (int)$data['filter_invoice_id'] . "'";
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
			'o.invoice_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.invoice_id";
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

	public function getInvoiceProducts($invoice_id) {
		$query = $this->db->query("SELECT op.*, p.image as image FROM " . DB_PREFIX . "invoice_product op left join " . DB_PREFIX . "product p on  op.product_id = p.product_id WHERE invoice_id = '" . (int)$invoice_id . "'");

		return $query->rows;
	}

	public function getInvoiceOption($invoice_id, $invoice_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_option WHERE invoice_id = '" . (int)$invocie_id . "' AND invoice_option_id = '" . (int)$invocie_option_id . "'");

		return $query->row;
	}

	public function getInoviceOptions($invoice_id, $invoice_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_option WHERE invoice_id = '" . (int)$invoice_id . "' AND invoice_product_id = '" . (int)$invoice_product_id . "'");

		return $query->rows;
	}

	public function getTotalInvoices($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice`";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " WHERE invoice_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " WHERE invoice_status_id > '0'";
		}

		if (!empty($data['filter_invoice_id'])) {
			$sql .= " AND invoice_id = '" . (int)$data['filter_invoice_id'] . "'";
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

	public function getTotalInvoicesByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalInvoicesByInvoiceStatusId($invoice_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE invoice_status_id = '" . (int)$invoice_status_id . "' AND invoice_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalInvoicesByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE language_id = '" . (int)$language_id . "' AND invoice_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalInvoicesByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "invoice` WHERE currency_id = '" . (int)$currency_id . "' AND invoice_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "invoice` WHERE invoice_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "invoice` WHERE invoice_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < CURDATE() ");

		return $query->row['total'];
	}

	public function getTotalSalesLastYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "invoice` WHERE invoice_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ");

		return $query->row['total'];
	}

	public function addInvoiceHistory($invoice_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "invoice` SET invoice_status_id = '" . (int)$data['invoice_status_id'] . "', date_modified = NOW() WHERE invoice_id = '" . (int)$invoice_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "invoice_history SET invoice_id = '" . (int)$invoice_id . "', invoice_status_id = '" . (int)$data['invoice_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$invoice_info = $this->getInvoice($invoice_id);

		

      	if ($data['notify']) {
			$language = new Language($invoice_info['language_directory']);
			$language->load($invoice_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $invoice_info['store_name'], $invoice_id);

			$message  = $language->get('text_order') . ' ' . $invoice_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($invoice_info['date_added'])) . "\n\n";
			
			$invoice_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "invoice_status WHERE invoice_status_id = '" . (int)$data['invoice_status_id'] . "' AND language_id = '" . (int)$invoice_info['language_id'] . "'");
				
			if ($invoice_status_query->num_rows) {
				$message .= $language->get('text_invoice_status') . "\n";
				$message .= $invoice_status_query->row['name'] . "\n\n";
			}
			
			if ($invoice_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($invoice_info['store_url'] . 'index.php?route=account/order/info&invoice_id=' . $invoice_id, ENT_QUOTES, 'UTF-8') . "\n\n";
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
			$mail->setTo($invoice_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($invoice_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getInvoiceHistories($invoice_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "invoice_history oh LEFT JOIN " . DB_PREFIX . "invoice_status os ON oh.invoice_status_id = os.invoice_status_id WHERE oh.invoice_id = '" . (int)$invoice_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalInvoiceHistories($invoice_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice_history WHERE invoice_id = '" . (int)$invoice_id . "'");

		return $query->row['total'];
	}

	public function getTotalInvoiceHistoriesByInvoiceStatusId($invoice_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "invoice_history WHERE invoice_status_id = '" . (int)$invoice_status_id . "'");

		return $query->row['total'];
	}

	public function getInvoiceShippingCodes(){
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