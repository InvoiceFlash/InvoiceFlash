<?php 

class ModelSaleDraft extends Model {
	public function addDraft($data) {
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
			
		if (isset($setting_info['draft_prefix'])) {
			$draft_prefix = $setting_info['draft_prefix'];
		} else {
			$draft_prefix = $this->config->get('config_draft_prefix');
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
		
		$sql = "INSERT INTO `" . DB_PREFIX . "draft` SET 
			`draft_prefix` = '" . $this->db->escape($draft_prefix) . "',
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
			 `draft_status_id` = '" . (int)$data['draft_status_id'] . "', 
			 `language_id` = '" . (int)$this->config->get('config_language_id') . "', 
			 `currency_id` = '" . (int)$currency_id . "', 
			 `currency_code` = '" . $this->db->escape($currency_code) . "', 
			 `currency_value` = '" . (float)$currency_value . "',  
			 `date_added` = now(), 
			 `date_modified` = now()";

		$this->db->query($sql);

      	$draft_id = $this->db->getLastId();
		
      	if (isset($data['draft_product'])) {		

      		foreach ($data['draft_product'] as $draft_product) {
				
				// Valores float de price y total que llegan como strings
				$price = floatval(preg_replace("/[^-0-9\.]/","",$draft_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/","",$draft_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "draft_product SET 
				draft_product_id = '" . (int)$draft_product['draft_product_id'] . "', 
				draft_id = '" . (int)$draft_id . "', 
				product_id = '" . (int)$draft_product['product_id'] . "', 
				name = '" . $this->db->escape($draft_product['name']) . "', 
				model = '" . $this->db->escape($draft_product['model']) . "', quantity = '" . (int)$draft_product['quantity'] . "', price = '" . $price . "', total = '" . $total . "', tax = '" . $draft_product['tax'] . "'");
				
				//name_ext = '" . $this->db->escape($draft_product['name_ext']) . "', 
				
				$draft_product_id = $this->db->getLastId();
	
				if (isset($draft_product['draft_option'])) {
					foreach ($draft_product['draft_option'] as $draft_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "draft_option SET draft_id = '" . (int)$draft_id . "', draft_product_id = '" . (int)$draft_product_id . "', product_option_id = '" . (int)$draft_option['product_option_id'] . "', product_option_value_id = '" . (int)$draft_option['product_option_value_id'] . "', name = '" . $this->db->escape($draft_option['name']) . "', `value` = '" . $this->db->escape($draft_option['value']) . "', `type` = '" . $this->db->escape($draft_option['type']) . "'");
					}
				}
				
			}
		}
		
		// Get the total
		$total = 0;
		
		if (isset($data['draft_total'])) {		
      		foreach ($data['draft_total'] as $draft_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "draft_total SET draft_id = '" . (int)$draft_id . "', code = '" . $this->db->escape($draft_total['code']) . "', title = '" . $this->db->escape($draft_total['title']) . "', text = '" . $this->db->escape($draft_total['text']) . "', `value` = '" . (float)$draft_total['value'] . "', sort_order = '" . (int)$draft_total['sort_order'] . "'");
			}
			
			$total += $draft_total['value'];
		}

		// Update draft total
		$this->db->query("UPDATE `" . DB_PREFIX . "draft` SET total = '" . (float)$total . "' WHERE draft_id = '" . (int)$draft_id . "'");
	}

	public function getDraftTotals($draft_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "draft_total WHERE draft_id = '" . (int)$draft_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function editDraft($draft_id, $data) {
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


		$this->db->query("UPDATE `" . DB_PREFIX . "draft` SET 
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
		`draft_status_id` = '" . (int)$data['draft_status_id'] . "', 
		`language_id` = '" . (int)$this->config->get('config_language_id') . "', 
		`date_modified` = now() WHERE `draft_id` = '" . (int)$draft_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_product WHERE draft_id = '" . (int)$draft_id . "'"); 
       	$this->db->query("DELETE FROM " . DB_PREFIX . "draft_option WHERE draft_id = '" . (int)$draft_id . "'");
		
      	if (isset($data['draft_product'])) {		
      		foreach ($data['draft_product'] as $draft_product) {	
      			
				// Valores float de price y total que llegan como strings
				$price = floatval(preg_replace("/[^-0-9\.]/","",$draft_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/","",$draft_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "draft_product SET 
				draft_product_id = '" . (int)$draft_product['draft_product_id'] . "', 
				draft_id = '" . (int)$draft_id . "', 
				product_id = '" . (int)$draft_product['product_id'] . "', 
				name = '" . $this->db->escape($draft_product['name']) . "', 
				model = '" . $this->db->escape($draft_product['model']) . "', quantity = '" . (int)$draft_product['quantity'] . "', price = '" . $price . "', total = '" . $total . "', tax = '" . $draft_product['tax'] . "'");
				
				$draft_product_id = $this->db->getLastId();
	
				if (isset($draft_product['draft_option'])) {
					foreach ($draft_product['draft_option'] as $draft_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "draft_option SET draft_option_id = '" . (int)$draft_option['draft_option_id'] . "', draft_id = '" . (int)$draft_id . "', draft_product_id = '" . (int)$draft_product_id . "', product_option_id = '" . (int)$draft_option['product_option_id'] . "', product_option_value_id = '" . (int)$draft_option['product_option_value_id'] . "', name = '" . $this->db->escape($draft_option['name']) . "', `value` = '" . $this->db->escape($draft_option['value']) . "', `type` = '" . $this->db->escape($draft_option['type']) . "'");
					}
				}
			}
		}
		
		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_total WHERE draft_id = '" . (int)$draft_id . "'");
		
		if (isset($data['draft_total'])) {		
      		foreach ($data['draft_total'] as $draft_total) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "draft_total SET draft_total_id = '" . (int)$draft_total['draft_total_id'] . "', draft_id = '" . (int)$draft_id . "', code = '" . $this->db->escape($draft_total['code']) . "', title = '" . $this->db->escape($draft_total['title']) . "', text = '" . $this->db->escape($draft_total['text']) . "', `value` = '" . (float)$draft_total['value'] . "', sort_order = '" . (int)$draft_total['sort_order'] . "'");
			}
			
			$total += $draft_total['value'];
		}

		// Update draft total
		$this->db->query("UPDATE `" . DB_PREFIX . "draft` SET total = '" . (float)$total . "' WHERE draft_id = '" . (int)$draft_id . "'");
	}

	public function deleteDraft($draft_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "draft WHERE draft_id = '" . (int)$draft_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_product WHERE draft_id = '" . (int)$draft_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_option WHERE draft_id = '" . (int)$draft_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "draft_total WHERE draft_id = '" . (int)$draft_id . "'");
	}

	public function getDraft($draft_id) {
		$draft_query = $this->db->query("SELECT o.*, c.company as company
			FROM `" . DB_PREFIX . "draft` o 
			LEFT JOIN " . DB_PREFIX . "customer c ON o.customer_id=c.customer_id  
			WHERE o.draft_id = '" . (int)$draft_id . "'");
		
		
		if ($draft_query->num_rows) {
		
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$draft_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$draft_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$draft_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$draft_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
		
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($draft_query->row['language_id']);
			
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
				'draft_id'              => $draft_query->row['draft_id'],
				'draft_no'              => $draft_query->row['draft_no'],
				'draft_prefix'          => $draft_query->row['draft_prefix'],
				'store_id'                => $draft_query->row['store_id'],
				'store_name'              => $draft_query->row['store_name'],
				'store_url'               => $draft_query->row['store_url'],
				'customer_id'             => $draft_query->row['customer_id'],
				'company'                 => $draft_query->row['company'],
				'customer_group_id'       => $draft_query->row['customer_group_id'],
				'telephone'               => $draft_query->row['telephone'],
				'fax'                     => $draft_query->row['fax'],
				'email'                   => $draft_query->row['email'],
				'payment_company'         => $draft_query->row['payment_company'],
				'payment_company_id'      => $draft_query->row['payment_company_id'],
				'payment_tax_id'          => $draft_query->row['payment_tax_id'],
				'payment_address_1'       => $draft_query->row['payment_address_1'],
				'payment_address_2'       => $draft_query->row['payment_address_2'],
				'payment_postcode'        => $draft_query->row['payment_postcode'],
				'payment_city'            => $draft_query->row['payment_city'],
				'payment_zone_id'         => $draft_query->row['payment_zone_id'],
				'payment_zone'            => $draft_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $draft_query->row['payment_country_id'],
				'payment_country'         => $draft_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $draft_query->row['payment_address_format'],
				'payment_method'          => $draft_query->row['payment_method'],
				'payment_code'            => $draft_query->row['payment_code'],				
				'shipping_company'        => $draft_query->row['shipping_company'],
				'shipping_address_1'      => $draft_query->row['shipping_address_1'],
				'shipping_address_2'      => $draft_query->row['shipping_address_2'],
				'shipping_postcode'       => $draft_query->row['shipping_postcode'],
				'shipping_city'           => $draft_query->row['shipping_city'],
				'shipping_zone_id'        => $draft_query->row['shipping_zone_id'],
				'shipping_zone'           => $draft_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $draft_query->row['shipping_country_id'],
				'shipping_country'        => $draft_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $draft_query->row['shipping_address_format'],
				'shipping_method'         => $draft_query->row['shipping_method'],
				'shipping_code'           => $draft_query->row['shipping_code'],
				'comment'                 => $draft_query->row['comment'],
				'total'                   => $draft_query->row['total'],
				'draft_status_id'       => $draft_query->row['draft_status_id'],
				'commission'              => $draft_query->row['commission'],
				'language_id'             => $draft_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $draft_query->row['currency_id'],
				'currency_code'           => $draft_query->row['currency_code'],
				'currency_value'          => $draft_query->row['currency_value'],					
				'date_added'              => $draft_query->row['date_added'],
				'date_modified'           => $draft_query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getDrafts($data = array()) {
		$sql = "SELECT o.draft_id, o.shipping_company, os.name AS `status`, os.color, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, c.company AS company FROM `" . DB_PREFIX . "draft` o LEFT JOIN `" . DB_PREFIX . "draft_status` os ON o.draft_status_id = os.draft_status_id LEFT JOIN `" . DB_PREFIX . "customer` c ON o.customer_id = c.customer_id WHERE os.language_id = '" . $this->config->get('config_language_id') . "'";

		if (isset($data['filter_draft_status_id']) && !is_null($data['filter_draft_status_id'])) {
			$sql .= " AND o.draft_status_id = '" . (int)$data['filter_draft_status_id'] . "'";
		} else {
			$sql .= " AND o.draft_status_id > '0'";
		}

		if (!empty($data['filter_draft_id'])) {
			$sql .= " AND o.draft_id = '" . (int)$data['filter_draft_id'] . "'";
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
			'o.draft_id',
			'company',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.draft_id";
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

	public function getDraftProducts($draft_id) {
		$query = $this->db->query("SELECT op.*, p.image as image FROM " . DB_PREFIX . "draft_product op left join " . DB_PREFIX . "product p on  op.product_id = p.product_id WHERE draft_id = '" . (int)$draft_id . "'");

		return $query->rows;
	}

	public function getDraftOption($draft_id, $draft_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "draft_option WHERE draft_id = '" . (int)$invocie_id . "' AND draft_option_id = '" . (int)$invocie_option_id . "'");

		return $query->row;
	}

	public function getDraftOptions($draft_id, $draft_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "draft_option WHERE draft_id = '" . (int)$draft_id . "' AND draft_product_id = '" . (int)$draft_product_id . "'");

		return $query->rows;
	}

	public function getTotalDrafts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "draft`";

		if (isset($data['filter_draft_status_id']) && !is_null($data['filter_draft_status_id'])) {
			$sql .= " WHERE draft_status_id = '" . (int)$data['filter_draft_status_id'] . "'";
		} else {
			$sql .= " WHERE draft_status_id > '0'";
		}

		if (!empty($data['filter_draft_id'])) {
			$sql .= " AND draft_id = '" . (int)$data['filter_draft_id'] . "'";
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

	public function getTotalDraftsByStoreId($store_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "draft` WHERE store_id = '" . (int)$store_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalDraftsByDraftStatusId($draft_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "draft` WHERE draft_status_id = '" . (int)$draft_status_id . "' AND draft_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalDraftsByLanguageId($language_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "draft` WHERE language_id = '" . (int)$language_id . "' AND draft_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalDraftsByCurrencyId($currency_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "draft` WHERE currency_id = '" . (int)$currency_id . "' AND draft_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "draft` WHERE draft_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalSalesByYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "draft` WHERE draft_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < CURDATE() ");

		return $query->row['total'];
	}

	public function getTotalSalesLastYear($year) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "draft` WHERE draft_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND date_added < DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ");

		return $query->row['total'];
	}

	public function addDraftHistory($draft_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "draft` SET draft_status_id = '" . (int)$data['draft_status_id'] . "', date_modified = NOW() WHERE draft_id = '" . (int)$draft_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "draft_history SET draft_id = '" . (int)$draft_id . "', draft_status_id = '" . (int)$data['draft_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$draft_info = $this->getDraft($draft_id);

		

      	if ($data['notify']) {
			$language = new Language($draft_info['language_directory']);
			$language->load($draft_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $draft_info['store_name'], $draft_id);

			$message  = $language->get('text_order') . ' ' . $draft_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($draft_info['date_added'])) . "\n\n";
			
			$draft_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "draft_status WHERE draft_status_id = '" . (int)$data['draft_status_id'] . "' AND language_id = '" . (int)$draft_info['language_id'] . "'");
				
			if ($draft_status_query->num_rows) {
				$message .= $language->get('text_draft_status') . "\n";
				$message .= $draft_status_query->row['name'] . "\n\n";
			}
			
			if ($draft_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($draft_info['store_url'] . 'index.php?route=account/order/info&draft_id=' . $draft_id, ENT_QUOTES, 'UTF-8') . "\n\n";
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
			$mail->setTo($draft_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($draft_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function getDraftHistories($draft_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "draft_history oh LEFT JOIN " . DB_PREFIX . "draft_status os ON oh.draft_status_id = os.draft_status_id WHERE oh.draft_id = '" . (int)$draft_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalDraftHistories($draft_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "draft_history WHERE draft_id = '" . (int)$draft_id . "'");

		return $query->row['total'];
	}

	public function getTotalDraftHistoriesByDraftStatusId($draft_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "draft_history WHERE draft_status_id = '" . (int)$draft_status_id . "'");

		return $query->row['total'];
	}

	public function getDraftShippingCodes(){
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