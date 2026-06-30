<?php

class ModelPurchaseInvoice extends Model {
	public function addInvoice($data) {
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($data['store_id']);

		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url  = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url  = HTTP_CATALOG;
		}

		$this->load->model('setting/setting');

		$setting_info = $this->model_setting_setting->getSetting('setting', $data['store_id']);

		if (isset($setting_info['purchase_invoice_prefix'])) {
			$invoice_prefix = $setting_info['purchase_invoice_prefix'];
		} elseif ($this->config->get('config_purchase_invoice_prefix')) {
			$invoice_prefix = $this->config->get('config_purchase_invoice_prefix');
		} else {
			$invoice_prefix = 'FRA-' . date('Y') . '-00';
		}

		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

		if ($country_info) {
			$shipping_country        = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country        = '';
			$shipping_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		$shipping_zone = $zone_info ? $zone_info['name'] : '';

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);

		if ($country_info) {
			$payment_country        = $country_info['name'];
			$payment_address_format = $country_info['address_format'];
		} else {
			$payment_country        = '';
			$payment_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		$payment_zone = $zone_info ? $zone_info['name'] : '';

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));

		if ($currency_info) {
			$currency_id    = $currency_info['currency_id'];
			$currency_code  = $currency_info['code'];
			$currency_value = $currency_info['value'];
		} else {
			$currency_id    = 0;
			$currency_code  = $this->config->get('config_currency');
			$currency_value = 1.00000;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "purchase_invoice` SET
			`invoice_prefix` = '" . $this->db->escape($invoice_prefix) . "',
			`supplier_invoice_no` = '" . $this->db->escape(isset($data['supplier_invoice_no']) ? $data['supplier_invoice_no'] : '') . "',
			`store_id` = '" . (int)$data['store_id'] . "',
			`store_name` = '" . $this->db->escape($store_name) . "',
			`store_url` = '" . $this->db->escape($store_url) . "',
			`supplier_id` = '" . (int)$data['supplier_id'] . "',
			`email` = '" . $this->db->escape($data['email']) . "',
			`telephone` = '" . $this->db->escape($data['telephone']) . "',
			`fax` = '" . $this->db->escape($data['fax']) . "',
			`payment_company` = '" . $this->db->escape($data['payment_company']) . "',
			`payment_company_id` = '" . $this->db->escape(isset($data['payment_company_id']) ? $data['payment_company_id'] : '') . "',
			`payment_tax_id` = '" . $this->db->escape(isset($data['payment_tax_id']) ? $data['payment_tax_id'] : '') . "',
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
			`invoice_status_id` = '" . (int)$data['invoice_status_id'] . "',
			`language_id` = '" . (int)$this->config->get('config_language_id') . "',
			`currency_id` = '" . (int)$currency_id . "',
			`currency_code` = '" . $this->db->escape($currency_code) . "',
			`currency_value` = '" . (float)$currency_value . "',
			`date_added` = NOW(),
			`date_modified` = NOW()");

		$invoice_id = $this->db->getLastId();

		if (isset($data['invoice_product'])) {
			foreach ($data['invoice_product'] as $invoice_product) {
				$price = floatval(preg_replace("/[^-0-9\.]/", "", $invoice_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/", "", $invoice_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_product SET
					invoice_product_id = '" . (int)$invoice_product['invoice_product_id'] . "',
					invoice_id = '" . (int)$invoice_id . "',
					product_id = '" . (int)$invoice_product['product_id'] . "',
					name = '" . $this->db->escape($invoice_product['name']) . "',
					model = '" . $this->db->escape($invoice_product['model']) . "',
					quantity = '" . (int)$invoice_product['quantity'] . "',
					price = '" . $price . "',
					total = '" . $total . "',
					tax = '" . (float)$invoice_product['tax'] . "'");

				$invoice_product_id = $this->db->getLastId();

				if (isset($invoice_product['invoice_option'])) {
					foreach ($invoice_product['invoice_option'] as $invoice_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_option SET
							invoice_id = '" . (int)$invoice_id . "',
							invoice_product_id = '" . (int)$invoice_product_id . "',
							product_option_id = '" . (int)$invoice_option['product_option_id'] . "',
							product_option_value_id = '" . (int)$invoice_option['product_option_value_id'] . "',
							name = '" . $this->db->escape($invoice_option['name']) . "',
							`value` = '" . $this->db->escape($invoice_option['value']) . "',
							`type` = '" . $this->db->escape($invoice_option['type']) . "'");
					}
				}
			}
		}

		$total = 0;

		if (isset($data['invoice_total'])) {
			foreach ($data['invoice_total'] as $invoice_total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_total SET
					invoice_id = '" . (int)$invoice_id . "',
					code = '" . $this->db->escape($invoice_total['code']) . "',
					title = '" . $this->db->escape($invoice_total['title']) . "',
					text = '" . $this->db->escape($invoice_total['text']) . "',
					`value` = '" . (float)$invoice_total['value'] . "',
					sort_order = '" . (int)$invoice_total['sort_order'] . "'");
			}
			$total += $invoice_total['value'];
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "purchase_invoice` SET total = '" . (float)$total . "' WHERE invoice_id = '" . (int)$invoice_id . "'");

		return $invoice_id;
	}

	public function editInvoice($invoice_id, $data) {
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$country_info = $this->model_localisation_country->getCountry($data['shipping_country_id']);

		if ($country_info) {
			$shipping_country        = $country_info['name'];
			$shipping_address_format = $country_info['address_format'];
		} else {
			$shipping_country        = '';
			$shipping_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['shipping_zone_id']);
		$shipping_zone = $zone_info ? $zone_info['name'] : '';

		$country_info = $this->model_localisation_country->getCountry($data['payment_country_id']);

		if ($country_info) {
			$payment_country        = $country_info['name'];
			$payment_address_format = $country_info['address_format'];
		} else {
			$payment_country        = '';
			$payment_address_format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}

		$zone_info = $this->model_localisation_zone->getZone($data['payment_zone_id']);
		$payment_zone = $zone_info ? $zone_info['name'] : '';

		$this->db->query("UPDATE `" . DB_PREFIX . "purchase_invoice` SET
			`store_id` = '" . (int)$data['store_id'] . "',
			`supplier_id` = '" . (int)$data['supplier_id'] . "',
			`supplier_invoice_no` = '" . $this->db->escape(isset($data['supplier_invoice_no']) ? $data['supplier_invoice_no'] : '') . "',
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
			`invoice_status_id` = '" . (int)$data['invoice_status_id'] . "',
			`language_id` = '" . (int)$this->config->get('config_language_id') . "',
			`date_modified` = NOW()
			WHERE `invoice_id` = '" . (int)$invoice_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_invoice_product WHERE invoice_id = '" . (int)$invoice_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_invoice_option WHERE invoice_id = '" . (int)$invoice_id . "'");

		if (isset($data['invoice_product'])) {
			foreach ($data['invoice_product'] as $invoice_product) {
				$price = floatval(preg_replace("/[^-0-9\.]/", "", $invoice_product['price']));
				$total = floatval(preg_replace("/[^-0-9\.]/", "", $invoice_product['total']));

				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_product SET
					invoice_product_id = '" . (int)$invoice_product['invoice_product_id'] . "',
					invoice_id = '" . (int)$invoice_id . "',
					product_id = '" . (int)$invoice_product['product_id'] . "',
					name = '" . $this->db->escape($invoice_product['name']) . "',
					model = '" . $this->db->escape($invoice_product['model']) . "',
					quantity = '" . (int)$invoice_product['quantity'] . "',
					price = '" . $price . "',
					total = '" . $total . "',
					tax = '" . (float)$invoice_product['tax'] . "'");

				$invoice_product_id = $this->db->getLastId();

				if (isset($invoice_product['invoice_option'])) {
					foreach ($invoice_product['invoice_option'] as $invoice_option) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_option SET
							invoice_option_id = '" . (int)$invoice_option['invoice_option_id'] . "',
							invoice_id = '" . (int)$invoice_id . "',
							invoice_product_id = '" . (int)$invoice_product_id . "',
							product_option_id = '" . (int)$invoice_option['product_option_id'] . "',
							product_option_value_id = '" . (int)$invoice_option['product_option_value_id'] . "',
							name = '" . $this->db->escape($invoice_option['name']) . "',
							`value` = '" . $this->db->escape($invoice_option['value']) . "',
							`type` = '" . $this->db->escape($invoice_option['type']) . "'");
					}
				}
			}
		}

		$total = 0;

		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_invoice_total WHERE invoice_id = '" . (int)$invoice_id . "'");

		if (isset($data['invoice_total'])) {
			foreach ($data['invoice_total'] as $invoice_total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_total SET
					invoice_total_id = '" . (int)$invoice_total['invoice_total_id'] . "',
					invoice_id = '" . (int)$invoice_id . "',
					code = '" . $this->db->escape($invoice_total['code']) . "',
					title = '" . $this->db->escape($invoice_total['title']) . "',
					text = '" . $this->db->escape($invoice_total['text']) . "',
					`value` = '" . (float)$invoice_total['value'] . "',
					sort_order = '" . (int)$invoice_total['sort_order'] . "'");
			}
			$total += $invoice_total['value'];
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "purchase_invoice` SET total = '" . (float)$total . "' WHERE invoice_id = '" . (int)$invoice_id . "'");
	}

	public function createNegativeInvoice($invoice_id) {
		$invoice_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice WHERE invoice_id = '" . (int)$invoice_id . "'");

		if (!$invoice_query->num_rows) {
			return false;
		}

		$invoice = $invoice_query->row;

		$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice SET
			invoice_prefix = '" . $this->db->escape($invoice['invoice_prefix']) . "',
			store_id = '" . (int)$invoice['store_id'] . "',
			store_name = '" . $this->db->escape($invoice['store_name']) . "',
			store_url = '" . $this->db->escape($invoice['store_url']) . "',
			supplier_id = '" . (int)$invoice['supplier_id'] . "',
			email = '" . $this->db->escape($invoice['email']) . "',
			telephone = '" . $this->db->escape($invoice['telephone']) . "',
			fax = '" . $this->db->escape($invoice['fax']) . "',
			payment_company = '" . $this->db->escape($invoice['payment_company']) . "',
			payment_company_id = '" . $this->db->escape($invoice['payment_company_id']) . "',
			payment_tax_id = '" . $this->db->escape($invoice['payment_tax_id']) . "',
			payment_address_1 = '" . $this->db->escape($invoice['payment_address_1']) . "',
			payment_address_2 = '" . $this->db->escape($invoice['payment_address_2']) . "',
			payment_city = '" . $this->db->escape($invoice['payment_city']) . "',
			payment_postcode = '" . $this->db->escape($invoice['payment_postcode']) . "',
			payment_country = '" . $this->db->escape($invoice['payment_country']) . "',
			payment_country_id = '" . (int)$invoice['payment_country_id'] . "',
			payment_zone = '" . $this->db->escape($invoice['payment_zone']) . "',
			payment_zone_id = '" . (int)$invoice['payment_zone_id'] . "',
			payment_address_format = '" . $this->db->escape($invoice['payment_address_format']) . "',
			payment_method = '" . $this->db->escape($invoice['payment_method']) . "',
			payment_code = '" . $this->db->escape($invoice['payment_code']) . "',
			shipping_company = '" . $this->db->escape($invoice['shipping_company']) . "',
			shipping_address_1 = '" . $this->db->escape($invoice['shipping_address_1']) . "',
			shipping_address_2 = '" . $this->db->escape($invoice['shipping_address_2']) . "',
			shipping_city = '" . $this->db->escape($invoice['shipping_city']) . "',
			shipping_postcode = '" . $this->db->escape($invoice['shipping_postcode']) . "',
			shipping_country = '" . $this->db->escape($invoice['shipping_country']) . "',
			shipping_country_id = '" . (int)$invoice['shipping_country_id'] . "',
			shipping_zone = '" . $this->db->escape($invoice['shipping_zone']) . "',
			shipping_zone_id = '" . (int)$invoice['shipping_zone_id'] . "',
			shipping_address_format = '" . $this->db->escape($invoice['shipping_address_format']) . "',
			shipping_method = '" . $this->db->escape($invoice['shipping_method']) . "',
			shipping_code = '" . $this->db->escape($invoice['shipping_code']) . "',
			comment = '" . $this->db->escape($invoice['comment']) . "',
			total = '" . (float)(-$invoice['total']) . "',
			invoice_status_id = '" . (int)$invoice['invoice_status_id'] . "',
			language_id = '" . (int)$invoice['language_id'] . "',
			currency_id = '" . (int)$invoice['currency_id'] . "',
			currency_code = '" . $this->db->escape($invoice['currency_code']) . "',
			currency_value = '" . (float)$invoice['currency_value'] . "',
			date_added = NOW(),
			date_modified = NOW()");

		$negative_invoice_id = $this->db->getLastId();

		$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice_product WHERE invoice_id = '" . (int)$invoice_id . "'");

		foreach ($product_query->rows as $invoice_product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_product SET
				invoice_id = '" . (int)$negative_invoice_id . "',
				product_id = '" . (int)$invoice_product['product_id'] . "',
				name = '" . $this->db->escape($invoice_product['name']) . "',
				model = '" . $this->db->escape($invoice_product['model']) . "',
				quantity = '" . (int)(-$invoice_product['quantity']) . "',
				price = '" . (float)$invoice_product['price'] . "',
				total = '" . (float)(-$invoice_product['total']) . "',
				tax = '" . (float)(-$invoice_product['tax']) . "'");

			$negative_product_id = $this->db->getLastId();

			$option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice_option WHERE invoice_id = '" . (int)$invoice_id . "' AND invoice_product_id = '" . (int)$invoice_product['invoice_product_id'] . "'");

			foreach ($option_query->rows as $invoice_option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_option SET
					invoice_id = '" . (int)$negative_invoice_id . "',
					invoice_product_id = '" . (int)$negative_product_id . "',
					product_option_id = '" . (int)$invoice_option['product_option_id'] . "',
					product_option_value_id = '" . (int)$invoice_option['product_option_value_id'] . "',
					name = '" . $this->db->escape($invoice_option['name']) . "',
					`value` = '" . $this->db->escape($invoice_option['value']) . "',
					`type` = '" . $this->db->escape($invoice_option['type']) . "'");
			}
		}

		$total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice_total WHERE invoice_id = '" . (int)$invoice_id . "' ORDER BY sort_order");

		$total = 0;

		foreach ($total_query->rows as $invoice_total) {
			$value = -$invoice_total['value'];

			$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_total SET
				invoice_id = '" . (int)$negative_invoice_id . "',
				code = '" . $this->db->escape($invoice_total['code']) . "',
				title = '" . $this->db->escape($invoice_total['title']) . "',
				text = '" . $this->db->escape($this->currency->format($value, $invoice['currency_code'], $invoice['currency_value'])) . "',
				`value` = '" . (float)$value . "',
				sort_order = '" . (int)$invoice_total['sort_order'] . "'");

			$total += $value;
		}

		$this->db->query("UPDATE " . DB_PREFIX . "purchase_invoice SET total = '" . (float)$total . "' WHERE invoice_id = '" . (int)$negative_invoice_id . "'");

		return $negative_invoice_id;
	}

	public function getInvoice($invoice_id) {
		$invoice_query = $this->db->query("SELECT o.*, s.company AS company
			FROM `" . DB_PREFIX . "purchase_invoice` o
			LEFT JOIN " . DB_PREFIX . "supplier s ON o.supplier_id = s.supplier_id
			WHERE o.invoice_id = '" . (int)$invoice_id . "'");

		if ($invoice_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$invoice_query->row['payment_country_id'] . "'");
			$payment_iso_code_2 = $country_query->num_rows ? $country_query->row['iso_code_2'] : '';
			$payment_iso_code_3 = $country_query->num_rows ? $country_query->row['iso_code_3'] : '';

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$invoice_query->row['payment_zone_id'] . "'");
			$payment_zone_code = $zone_query->num_rows ? $zone_query->row['code'] : '';

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$invoice_query->row['shipping_country_id'] . "'");
			$shipping_iso_code_2 = $country_query->num_rows ? $country_query->row['iso_code_2'] : '';
			$shipping_iso_code_3 = $country_query->num_rows ? $country_query->row['iso_code_3'] : '';

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$invoice_query->row['shipping_zone_id'] . "'");
			$shipping_zone_code = $zone_query->num_rows ? $zone_query->row['code'] : '';

			$this->load->model('localisation/language');
			$language_info = $this->model_localisation_language->getLanguage($invoice_query->row['language_id']);

			return array(
				'invoice_id'              => $invoice_query->row['invoice_id'],
				'invoice_no'              => $invoice_query->row['invoice_no'],
				'invoice_prefix'          => $invoice_query->row['invoice_prefix'],
				'supplier_invoice_no'     => $invoice_query->row['supplier_invoice_no'],
				'store_id'                => $invoice_query->row['store_id'],
				'store_name'              => $invoice_query->row['store_name'],
				'store_url'               => $invoice_query->row['store_url'],
				'supplier_id'             => $invoice_query->row['supplier_id'],
				'company'                 => $invoice_query->row['company'],
				'telephone'               => $invoice_query->row['telephone'],
				'fax'                     => $invoice_query->row['fax'],
				'email'                   => $invoice_query->row['email'],
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
				'language_id'             => $invoice_query->row['language_id'],
				'language_code'           => $language_info ? $language_info['code'] : '',
				'language_filename'       => $language_info ? $language_info['filename'] : '',
				'language_directory'      => $language_info ? $language_info['directory'] : '',
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
		$sql = "SELECT o.invoice_id, o.shipping_company, os.name AS `status`, os.color, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified, s.company AS company
			FROM `" . DB_PREFIX . "purchase_invoice` o
			LEFT JOIN `" . DB_PREFIX . "invoice_status` os ON o.invoice_status_id = os.invoice_status_id
			LEFT JOIN `" . DB_PREFIX . "supplier` s ON o.supplier_id = s.supplier_id
			WHERE os.language_id = '" . $this->config->get('config_language_id') . "'";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " AND o.invoice_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " AND o.invoice_status_id > '0'";
		}

		if (!empty($data['filter_invoice_id'])) {
			$sql .= " AND o.invoice_id = '" . (int)$data['filter_invoice_id'] . "'";
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

		$sort_data = array('o.invoice_id', 'company', 'status', 'o.date_added', 'o.date_modified', 'o.total');

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.invoice_id";
		}

		$sql .= (isset($data['order']) && $data['order'] == 'DESC') ? " DESC" : " ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		return $this->db->query($sql)->rows;
	}

	public function getTotalInvoices($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "purchase_invoice`";

		if (isset($data['filter_invoice_status_id']) && !is_null($data['filter_invoice_status_id'])) {
			$sql .= " WHERE invoice_status_id = '" . (int)$data['filter_invoice_status_id'] . "'";
		} else {
			$sql .= " WHERE invoice_status_id > '0'";
		}

		if (!empty($data['filter_invoice_id'])) {
			$sql .= " AND invoice_id = '" . (int)$data['filter_invoice_id'] . "'";
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

		return $this->db->query($sql)->row['total'];
	}

	public function getInvoiceProducts($invoice_id) {
		$query = $this->db->query("SELECT ip.*, p.image FROM " . DB_PREFIX . "purchase_invoice_product ip LEFT JOIN " . DB_PREFIX . "product p ON ip.product_id = p.product_id WHERE ip.invoice_id = '" . (int)$invoice_id . "'");
		return $query->rows;
	}

	public function getInvoiceOptions($invoice_id, $invoice_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice_option WHERE invoice_id = '" . (int)$invoice_id . "' AND invoice_product_id = '" . (int)$invoice_product_id . "'");
		return $query->rows;
	}

	public function getInvoiceTotals($invoice_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_invoice_total WHERE invoice_id = '" . (int)$invoice_id . "' ORDER BY sort_order");
		return $query->rows;
	}

	public function addInvoiceHistory($invoice_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "purchase_invoice` SET invoice_status_id = '" . (int)$data['invoice_status_id'] . "', date_modified = NOW() WHERE invoice_id = '" . (int)$invoice_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_invoice_history SET
			invoice_id = '" . (int)$invoice_id . "',
			invoice_status_id = '" . (int)$data['invoice_status_id'] . "',
			notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "',
			comment = '" . $this->db->escape(strip_tags($data['comment'])) . "',
			date_added = NOW()");
	}

	public function getInvoiceHistories($invoice_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify
			FROM " . DB_PREFIX . "purchase_invoice_history oh
			LEFT JOIN " . DB_PREFIX . "invoice_status os ON oh.invoice_status_id = os.invoice_status_id
			WHERE oh.invoice_id = '" . (int)$invoice_id . "'
			AND os.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);
		return $query->rows;
	}

	public function getTotalInvoiceHistories($invoice_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase_invoice_history WHERE invoice_id = '" . (int)$invoice_id . "'");
		return $query->row['total'];
	}

	public function getInvoiceShippingCodes() {
		$this->load->model('setting/extension');
		$results  = $this->model_setting_extension->getExtensions('shipping');
		$cshipping = array();
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->language('shipping/' . $result['code']);
				$cshipping[] = array(
					'shipping_method' => $this->language->get('heading_title'),
					'shipping_code'   => $result['code']
				);
			}
		}
		return $cshipping;
	}
}

?>
