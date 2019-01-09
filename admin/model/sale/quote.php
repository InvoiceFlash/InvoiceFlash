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

		$this->db->query("INSERT INTO `" . DB_PREFIX . "quote` SET `invoice_prefix` = '" . $this->db->escape($invoice_prefix) . "', `store_id` = '" . (int)$data['store_id'] . "', `store_name` = '" . (int)$data['store_id'] . "', `store_url` = '" . $this->db->escape($store_url) . "', `customer_id` = '" . (int)$data['customer_id'] . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['firstname']) . "', `email` = '" . $this->db->escape($data['email']) . "', `telephone` = '" . $this->db->escape($data['telephone']) . "', `fax` = '" . $this->db->escape($data['fax']) . "', `payment_firstname` = '" . $this->db->escape($data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape($data['payment_lastname']) . "', `payment_company` = '" . $this->db->escape($data['payment_company']) . "', `payment_company_id` = '" . (int)$data['payment_company_id'] . "', `payment_tax_id` = '" . (int)$data['payment_tax_id'] . "', `payment_address_1` = '" . $this->db->escape($data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape($data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape($data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape($data['payment_postcode']) . "', `payment_country` = '" . $this->db->escape($payment_country) . "', `payment_country_id` = '" . (int)$data['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape($payment_zone) . "', `payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape($payment_address_format) . "', `payment_method` = '" . $this->db->escape($data['payment_method']) . "', `payment_code` = '" . $this->db->escape($data['payment_code']) . "', `shipping_firstname` = '" . $this->db->escape($data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape($data['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape($data['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape($data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape($data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape($data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape($data['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape($shipping_country) . "', `shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape($shipping_zone) . "', `shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape($shipping_address_format) . "', `shipping_method` = '" . $this->db->escape($data['shipping_method']) . "', `shipping_code` = '" . $this->db->escape($data['shipping_code']) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `quote_status_id` = '" . (int)$data['quote_status_id'] . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `currency_id` = '" . (int)$currency_id . "', `currency_code` = '" . $this->db->escape($currency_code) . "', `currency_value` = '" . (float)$currency_value . "', `date_added` = NOW(), `date_modified` = NOW()");

		$quote_id = $this->db->getLastId();
		
		if (isset($data['order_product'])) {
			foreach ($data['order_product'] as $quote_product) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "quote_product` SET `quote_id` = '" . (int)$quote_id . "', `product_id` = '" . (int)$quote_product['product_id'] . "', `name` = '" . $this->db->escape($quote_product['name']) . "', `model` = '" . $this->db->escape($quote_product['model']) . "', `quantity` = '" . (int)$quote_product['quantity'] . "', `price` = '" . (float)$quote_product['price'] . "', `total` = '" . (float)$quote_product['total'] . "', `tax` = '" . (float)$quote_product['tax'] . "', `reward` = '" . (int)$quote_product['reward'] . "'");

				$quote_product_id = $this->db->getLastId();

				if (isset($quote_product['order_option'])) {
					foreach ($quote_product['order_option'] as $quote_option) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "quote_option` SET `quote_id` = '" . (int)$quote_id . "', `quote_product_id` = '" . (int)$quote_product_id . "', `product_option_id` = '" . (int)$quote_option['product_option_id'] . "', `product_option_value_id` = '" . (int)$quote_option['product_option_value_id'] . "', `name` = '" . $this->db->escape($quote_option['name']) . "', `value` = '" . $this->db->escape($quote_option['value']) . "', `type` = '" . $this->db->escape($quote_option['type']) . "'");

						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$quote_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$quote_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
		}

		// Get the total
		$total = 0;

		if (isset($data['order_total'])) {		
			foreach ($data['order_total'] as $quote_total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quote_total SET quote_id = '" . (int)$quote_id . "', code = '" . $this->db->escape($quote_total['code']) . "', title = '" . $this->db->escape($quote_total['title']) . "', text = '" . $this->db->escape($quote_total['text']) . "', `value` = '" . (float)$quote_total['value'] . "', sort_order = '" . (int)$quote_total['sort_order'] . "'");
			}

			$total += $quote_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			$this->load->model('sale/affiliate');

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);

			if ($affiliate_info) {
				$affiliate_id = $affiliate_info['affiliate_id']; 
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}

		// Update quote total			 
		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE quote_id = '" . (int)$quote_id . "'");

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

		// Restock products before subtracting the stock later on
		$quote_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote` WHERE quote_status_id > '0' AND quote_id = '" . (int)$quote_id . "'");

		if ($quote_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");

			foreach($product_query->rows as $product) {
				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "' AND quote_product_id = '" . (int)$product['quote_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
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

		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['firstname']) . "', `email` = '" . $this->db->escape($data['email']) . "', `telephone` = '" . $this->db->escape($data['telephone']) . "', `fax` = '" . $this->db->escape($data['fax']) . "', `payment_firstname` = '" . $this->db->escape($data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape($data['payment_lastname']) . "', `payment_company` = '" . $this->db->escape($data['payment_company']) . "', `payment_company_id` = '" . (int)$data['payment_company_id'] . "', `payment_tax_id` = '" . (int)$data['payment_tax_id'] . "', `payment_address_1` = '" . $this->db->escape($data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape($data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape($data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape($data['payment_postcode']) . "', `payment_country` = '" . $this->db->escape($payment_country) . "', `payment_country_id` = '" . (int)$data['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape($payment_zone) . "', `payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape($payment_address_format) . "', `payment_method` = '" . $this->db->escape($data['payment_method']) . "', `payment_code` = '" . $this->db->escape($data['payment_code']) . "', `shipping_firstname` = '" . $this->db->escape($data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape($data['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape($data['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape($data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape($data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape($data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape($data['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape($shipping_country) . "', `shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape($shipping_zone) . "', `shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape($shipping_address_format) . "', `shipping_method` = '" . $this->db->escape($data['shipping_method']) . "', `shipping_code` = '" . $this->db->escape($data['shipping_code']) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `quote_status_id` = '" . (int)$data['quote_status_id'] . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `currency_id` = '" . (int)$currency_id . "', `currency_code` = '" . $this->db->escape($currency_code) . "', `currency_value` = '" . (float)$currency_value . "', date_modified = NOW() WHERE quote_id = '" . (int)$quote_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'"); 
		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "'");

		if (isset($data['order_product'])) {
			foreach ($data['order_product'] as $quote_product) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quote_product SET quote_product_id = '" . (int)$quote_product['quote_product_id'] . "', quote_id = '" . (int)$quote_id . "', product_id = '" . (int)$quote_product['product_id'] . "', name = '" . $this->db->escape($quote_product['name']) . "', model = '" . $this->db->escape($quote_product['model']) . "', quantity = '" . (int)$quote_product['quantity'] . "', price = '" . (float)$quote_product['price'] . "', total = '" . (float)$quote_product['total'] . "', tax = '" . (float)$quote_product['tax'] . "', reward = '" . (int)$quote_product['reward'] . "'");

				$quote_product_id = $this->db->getLastId();

				if (isset($quote_product['order_option'])) {
					foreach ($quote_product['order_option'] as $quote_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "quote_option SET quote_option_id = '" . (int)$quote_option['quote_option_id'] . "', quote_id = '" . (int)$quote_id . "', quote_product_id = '" . (int)$quote_product_id . "', product_option_id = '" . (int)$quote_option['product_option_id'] . "', product_option_value_id = '" . (int)$quote_option['product_option_value_id'] . "', name = '" . $this->db->escape($quote_option['name']) . "', `value` = '" . $this->db->escape($quote_option['value']) . "', `type` = '" . $this->db->escape($quote_option['type']) . "'");


						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$quote_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$quote_option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}

			}
		}

		// Get the total
		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "quote_total WHERE quote_id = '" . (int)$quote_id . "'");

		if (isset($data['order_total'])) {		
			foreach ($data['order_total'] as $quote_total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "quote_total SET quote_total_id = '" . (int)$quote_total['quote_total_id'] . "', quote_id = '" . (int)$quote_id . "', code = '" . $this->db->escape($quote_total['code']) . "', title = '" . $this->db->escape($quote_total['title']) . "', text = '" . $this->db->escape($quote_total['text']) . "', `value` = '" . (float)$quote_total['value'] . "', sort_order = '" . (int)$quote_total['sort_order'] . "'");
			}

			$total += $quote_total['value'];
		}

		// Affiliate
		$affiliate_id = 0;
		$commission = 0;

		if (!empty($this->request->post['affiliate_id'])) {
			$this->load->model('sale/affiliate');

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($this->request->post['affiliate_id']);

			if ($affiliate_info) {
				$affiliate_id = $affiliate_info['affiliate_id']; 
				$commission = ($total / 100) * $affiliate_info['commission']; 
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET total = '" . (float)$total . "', affiliate_id = '" . (int)$affiliate_id . "', commission = '" . (float)$commission . "' WHERE quote_id = '" . (int)$quote_id . "'");
	}
	
	public function deleteQuote($quote_id) {
		$quote_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote` WHERE quote_status_id > '0' AND quote_id = '" . (int)$quote_id . "'");

		if ($quote_query->num_rows) {
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");

			foreach($product_query->rows as $product) {
				$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "' AND quote_product_id = '" . (int)$product['quote_product_id'] . "'");

				foreach ($option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity + " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "quote` WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quote_option` WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quote_product` WHERE quote_id = '" . (int)$quote_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "quote_total` WHERE quote_id = '" . (int)$quote_id . "'");
	}

	public function getQuote($quote_id) {
		$quote_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "quote` o WHERE o.quote_id = '" . (int)$quote_id . "'");

		if ($quote_query->num_rows) {
			$reward = 0;

			$quote_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");

			foreach ($quote_product_query->rows as $product) {
				$reward += $product['reward'];
			}			

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

			if ($quote_query->row['affiliate_id']) {
				$affiliate_id = $quote_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}				

			$this->load->model('sale/affiliate');

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';				
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
				'quote_id'                => $quote_query->row['quote_id'],
				'invoice_no'              => $quote_query->row['invoice_no'],
				'invoice_prefix'          => $quote_query->row['invoice_prefix'],
				'store_id'                => $quote_query->row['store_id'],
				'store_name'              => $quote_query->row['store_name'],
				'store_url'               => $quote_query->row['store_url'],
				'customer_id'             => $quote_query->row['customer_id'],
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
				'reward'                  => $reward,
				'quote_status_id'         => $quote_query->row['quote_status_id'],
				'affiliate_id'            => $quote_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $quote_query->row['commission'],
				'language_id'             => $quote_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,				
				'currency_id'             => $quote_query->row['currency_id'],
				'currency_code'           => $quote_query->row['currency_code'],
				'currency_value'          => $quote_query->row['currency_value'],
				'ip'                      => $quote_query->row['ip'],
				'forwarded_ip'            => $quote_query->row['forwarded_ip'], 
				'user_agent'              => $quote_query->row['user_agent'],	
				'accept_language'         => $quote_query->row['accept_language'],					
				'date_added'              => $quote_query->row['date_added'],
				'date_modified'           => $quote_query->row['date_modified']
			);
		} else {
			return false;
		}
	}
	
	public function getQuotes($data = array()) {
		$sql = "SELECT quote_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.quote_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "quote` o";

		if (isset($data['filter_quote_status_id']) && !is_null($data['filter_quote_status_id'])) {
			$sql .= " WHERE o.quote_status_id = '" . (int)$data['filter_quote_status_id'] . "'";
		} else {
			$sql .= " WHERE o.quote_status_id > '0'";
		}

		if (!empty($data['filter_quote_id'])) {
			$sql .= " AND o.quote_id = '" . (int)$data['filter_quote_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
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
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_product WHERE quote_id = '" . (int)$quote_id . "'");

		return $query->rows;
	}
	
	public function getQuoteOption($quote_id, $quote_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_option WHERE quote_id = '" . (int)$quote_id . "' AND quote_option_id = '" . (int)$quote_option_id . "'");

		return $query->row;
	}
	
	public function getQuoteOptions($quote_id, $quote_product_id) {
		$query = $this->db->query("SELECT oo.* FROM " . DB_PREFIX . "quote_option AS oo LEFT JOIN " . DB_PREFIX . "product_option po USING(product_option_id) LEFT JOIN `" . DB_PREFIX . "option` o USING(option_id) WHERE quote_id = '" . (int)$quote_id . "' AND quote_product_id = '" . (int)$quote_product_id . "' ORDER BY o.sort_order");

		return $query->rows;
	}

	// public function getQuoteDownloads($quote_id, $quote_product_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_download WHERE quote_id = '" . (int)$quote_id . "' AND quote_product_id = '" . (int)$quote_product_id . "'");

	// 	return $query->rows;
	// }
	
	// public function getQuoteVouchers($quote_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_voucher WHERE quote_id = '" . (int)$quote_id . "'");

	// 	return $query->rows;
	// }
	
	// public function getQuoteVoucherByVoucherId($voucher_id) {
 //      	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "quote_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

	// 	return $query->row;
	// }
				
	public function getQuoteTotals($quote_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_total WHERE quote_id = '" . (int)$quote_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getTotalQuotes($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "quote`";

		if (isset($data['filter_quote_status_id']) && !is_null($data['filter_quote_status_id'])) {
			$sql .= " WHERE quote_status_id = '" . (int)$data['filter_quote_status_id'] . "'";
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

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
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
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "quote` WHERE quote_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "'");

		return $query->row['total'];
	}

	public function createInvoiceNo($quote_id) {
		$order_info = $this->getOrder($order_id);

		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");

			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}

	public function createOrder($quote_id) {
		$quote_info = $this->getQuote($quote_id);

		$log = new Log('quote.log');
		$log->write('hola');

		if ($quote_info && !$quote_info['invoice_no']) {

			$quote_products = $this->getQuoteProducts($quote_id);

			$order_products = array();		
		
			foreach ($quote_products as $quote_product) {
				
				$quote_option = $this->getQuoteOptions($quote_id, $quote_product['quote_product_id']);
												
				$order_products[] = array(
					'order_product_id' => $quote_product['quote_product_id'],
					'product_id'       => $quote_product['product_id'],
					'name'             => $quote_product['name'],
					'model'            => $quote_product['model'],
					'order_option'     => $quote_option,
					'quantity'         => $quote_product['quantity'],
					'price'            => $quote_product['price'],
					'total'            => $quote_product['total'],
					'tax'              => $quote_product['tax'],
					'reward'           => $quote_product['reward']
				);
			}

			$order_totals = $this->getQuoteTotals($quote_id);
			
			$data = array(
				'store_id' => $quote_info['store_id'],
				'customer_id' => $quote_info['customer_id'],
				'customer_group_id' => $quote_info['customer_group_id'],
				'firstname' => $quote_info['firstname'],
				'lastname' => $quote_info['lastname'],
				'email' => $quote_info['email'],
				'telephone' => $quote_info['telephone'],
				'fax' => $quote_info['fax'],
				'payment_firstname' => $quote_info['payment_firstname'],
				'payment_lastname' => $quote_info['payment_lastname'],
				'payment_company' => $quote_info['payment_company'],
				'payment_company_id' => $quote_info['payment_company_id'],
				'payment_tax_id' => $quote_info['payment_tax_id'],
				'payment_address_1' => $quote_info['payment_address_1'],
				'payment_address_2' => $quote_info['payment_address_2'],
				'payment_city' => $quote_info['payment_city'],
				'payment_postcode' => $quote_info['payment_postcode'],
				'payment_country' => $quote_info['payment_country'],
				'payment_country_id' => $quote_info['payment_country_id'],
				'payment_zone' => $quote_info['payment_zone'],
				'payment_zone_id' => $quote_info['payment_zone_id'],
				'payment_address_format' => $quote_info['payment_address_format'],
				'payment_method' => $quote_info['payment_method'],
				'payment_code' => $quote_info['payment_code'],
				'shipping_firstname' => $quote_info['shipping_firstname'],
				'shipping_lastname' => $quote_info['shipping_lastname'],
				'shipping_company' => $quote_info['shipping_company'],
				'shipping_address_1' => $quote_info['shipping_address_1'],
				'shipping_address_2' => $quote_info['shipping_address_2'],
				'shipping_city' => $quote_info['shipping_city'],
				'shipping_postcode' => $quote_info['shipping_postcode'],
				'shipping_country' => $quote_info['shipping_country'],
				'shipping_country_id' => $quote_info['shipping_country_id'],
				'shipping_zone' => $quote_info['shipping_zone'],
				'shipping_zone_id' => $quote_info['shipping_zone_id'],
				'shipping_address_format' => $quote_info['shipping_address_format'],
				'shipping_method' => $quote_info['shipping_method'],
				'shipping_code' => $quote_info['shipping_code'],
				'comment' => $quote_info['comment'],
				'order_status_id' => $quote_info['quote_status_id'],
				'affiliate_id' => $quote_info['affiliate_id'],
				'user_agent' => $quote_info['user_agent'],
				'accept_language' => $quote_info['accept_language'],
				'order_product' => $order_products,
				'order_total' => $order_totals
			);


			$this->load->model('sale/order');


			$this->model_sale_order->addOrder($data);

			$query = $this->db->query("SELECT order_id, invoice_prefix FROM `" . DB_PREFIX . "order` ORDER BY order_id DESC LIMIT 1");

			$invoice_no = $query->row['order_id'];
			$invoice_prefix = $query->row['invoice_prefix'];

			$this->db->query("UPDATE quote SET invoice_no = " . (int)$invoice_no . " WHERE quote_id = " . (int)$quote_id);

			return $invoice_prefix . $invoice_no;
		} 
	}	
	
	public function addQuoteHistory($quote_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "quote` SET quote_status_id = '" . (int)$data['quote_status_id'] . "', date_modified = NOW() WHERE quote_id = '" . (int)$quote_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "quote_history SET quote_id = '" . (int)$quote_id . "', quote_status_id = '" . (int)$data['quote_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$quote_info = $this->getQuote($quote_id);

		if ($data['notify']) {
			$language = new Language($quote_info['language_directory']);
			$language->load($quote_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $quote_info['store_name'], $quote_id);

			$message  = $language->get('text_quote') . ' ' . $quote_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($quote_info['date_added'])) . "\n\n";

			$quote_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['quote_status_id'] . "' AND language_id = '" . (int)$quote_info['language_id'] . "'");

			if ($quote_status_query->num_rows) {
				$message .= $language->get('text_quote_status') . "\n";
				$message .= $quote_status_query->row['name'] . "\n\n";
			}

			if ($quote_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($quote_info['store_url'] . 'index.php?route=account/quote/info&quote_id=' . $quote_id, ENT_QUOTES, 'UTF-8') . "\n\n";
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
			$mail->setTo($quote_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($quote_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}
		
	public function getQuoteHistories($quote_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}	

		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "quote_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.quote_status_id = os.order_status_id WHERE oh.quote_id = '" . (int)$quote_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

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
	
	public function getEmailsByProductsQuoteed($products, $start, $end) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "quote` o LEFT JOIN " . DB_PREFIX . "quote_product op ON (o.quote_id = op.quote_id) WHERE (" . implode(" OR ", $implode) . ") AND o.quote_status_id <> '0' LIMIT " . (int)$start . "," . (int)$end);

		return $query->rows;
	}
	
	public function getTotalEmailsByProductsQuoteed($products) {
		$implode = array();

		foreach ($products as $product_id) {
			$implode[] = "op.product_id = '" . (int)$product_id . "'";
		}

		$query = $this->db->query("SELECT DISTINCT email FROM `" . DB_PREFIX . "quote` o LEFT JOIN " . DB_PREFIX . "quote_product op ON (o.quote_id = op.quote_id) WHERE (" . implode(" OR ", $implode) . ") AND o.quote_status_id <> '0'");

		return $query->row['total'];
	}

	
}
?>