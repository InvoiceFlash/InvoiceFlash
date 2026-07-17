<?php
class ModelToolImport extends Model {
	public function importProducts($rows) {
		$imported = 0;
		$updated = 0;
		$errors = array();

		if (!empty($rows)) {
			array_shift($rows);
		}

		$language_id = (int)$this->config->get('config_language_id');
		$row_number = 1;

		foreach ($rows as $row) {
			$row_number++;

			if (empty(array_filter($row, function ($value) { return trim((string)$value) !== ''; }))) {
				continue;
			}

			$model = isset($row[0]) ? trim($row[0]) : '';
			$description = isset($row[1]) ? trim($row[1]) : '';
			$name = $description;
			$price = isset($row[2]) && $row[2] !== '' ? (float)str_replace(',', '.', $row[2]) : 0;
			$quantity = isset($row[3]) && $row[3] !== '' ? (int)$row[3] : 0;
			$status = isset($row[4]) && $row[4] !== '' ? (int)$row[4] : 1;

			if ($model === '' || $description === '') {
				$errors[] = sprintf($this->language->get('error_row'), $row_number, $this->language->get('error_required'));
				continue;
			}

			$query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE model = '" . $this->db->escape($model) . "'");

			if ($query->num_rows) {
				$product_id = $query->row['product_id'];

				$this->db->query("UPDATE `" . DB_PREFIX . "product` SET price = '" . (float)$price . "', quantity = '" . (int)$quantity . "', status = '" . (int)$status . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

				$description_query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product_description` WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . $language_id . "'");

				if ($description_query->num_rows) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET name = '" . $this->db->escape($name) . "', description = '" . $this->db->escape($description) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . $language_id . "'");
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET product_id = '" . (int)$product_id . "', language_id = '" . $language_id . "', name = '" . $this->db->escape($name) . "', meta_keyword = '', meta_description = '', description = '" . $this->db->escape($description) . "', tag = ''");
				}

				$updated++;
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET model = '" . $this->db->escape($model) . "', sku = '', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '" . (int)$quantity . "', minimum = '1', subtract = '1', stock_status_id = '0', date_available = '" . date('Y-m-d') . "', manufacturer_id = '0', shipping = '1', price = '" . (float)$price . "', points = '0', weight = '0.00000000', weight_class_id = '0', length = '0.00000000', width = '0.00000000', height = '0.00000000', length_class_id = '0', sort_order = '0', status = '" . (int)$status . "', tax_class_id = '0', date_added = NOW(), date_modified = NOW()");

				$product_id = $this->db->getLastId();

				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET product_id = '" . (int)$product_id . "', language_id = '" . $language_id . "', name = '" . $this->db->escape($name) . "', meta_keyword = '', meta_description = '', description = '" . $this->db->escape($description) . "', tag = ''");

				$imported++;
			}
		}

		$this->cache->delete('product');

		return array(
			'imported' => $imported,
			'updated'  => $updated,
			'errors'   => $errors
		);
	}

	public function importCustomers($rows) {
		$imported = 0;
		$updated = 0;
		$errors = array();

		if (!empty($rows)) {
			array_shift($rows);
		}

		$row_number = 1;

		foreach ($rows as $row) {
			$row_number++;

			if (empty(array_filter($row, function ($value) { return trim((string)$value) !== ''; }))) {
				continue;
			}

			$company = isset($row[0]) ? trim($row[0]) : '';
			$nif = isset($row[1]) ? trim($row[1]) : '';
			$email = isset($row[2]) ? trim($row[2]) : '';
			$telephone = isset($row[3]) ? trim($row[3]) : '';
			$address_1 = isset($row[4]) ? trim($row[4]) : '';
			$city = isset($row[5]) ? trim($row[5]) : '';
			$postcode = isset($row[6]) ? trim($row[6]) : '';
			$country_id = $this->resolveCountryId(isset($row[7]) ? trim($row[7]) : '');

			if ($company === '' || $email === '') {
				$errors[] = sprintf($this->language->get('error_row'), $row_number, $this->language->get('error_required_customer'));
				continue;
			}

			$query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` WHERE email = '" . $this->db->escape($email) . "'");

			if ($query->num_rows) {
				$customer_id = $query->row['customer_id'];

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET company = '" . $this->db->escape($company) . "', telephone = '" . $this->db->escape($telephone) . "', date_modified = NOW() WHERE customer_id = '" . (int)$customer_id . "'");

				$fl_query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "fl_customers` WHERE customer_id = '" . (int)$customer_id . "'");

				if ($fl_query->num_rows) {
					$this->db->query("UPDATE `" . DB_PREFIX . "fl_customers` SET nif = '" . $this->db->escape($nif) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "fl_customers` SET customer_id = '" . (int)$customer_id . "', nif = '" . $this->db->escape($nif) . "', country_id = '" . (int)$country_id . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "'");
				}

				$updated++;
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET company = '" . $this->db->escape($company) . "', approved = '1', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', customer_group_id = '1', status = '1', date_added = NOW(), date_modified = NOW()");

				$customer_id = $this->db->getLastId();

				$this->db->query("INSERT INTO `" . DB_PREFIX . "fl_customers` SET customer_id = '" . (int)$customer_id . "', nif = '" . $this->db->escape($nif) . "', country_id = '" . (int)$country_id . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "'");

				if ($address_1 !== '' || $city !== '' || $postcode !== '') {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET customer_id = '" . (int)$customer_id . "', company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($nif) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "'");

					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}

				$imported++;
			}
		}

		return array(
			'imported' => $imported,
			'updated'  => $updated,
			'errors'   => $errors
		);
	}

	public function importSuppliers($rows) {
		$imported = 0;
		$updated = 0;
		$errors = array();

		if (!empty($rows)) {
			array_shift($rows);
		}

		$row_number = 1;

		foreach ($rows as $row) {
			$row_number++;

			if (empty(array_filter($row, function ($value) { return trim((string)$value) !== ''; }))) {
				continue;
			}

			$company = isset($row[0]) ? trim($row[0]) : '';
			$tax_id = isset($row[1]) ? trim($row[1]) : '';
			$email = isset($row[2]) ? trim($row[2]) : '';
			$telephone = isset($row[3]) ? trim($row[3]) : '';
			$address_1 = isset($row[4]) ? trim($row[4]) : '';
			$city = isset($row[5]) ? trim($row[5]) : '';
			$postcode = isset($row[6]) ? trim($row[6]) : '';
			$country_id = $this->resolveCountryId(isset($row[7]) ? trim($row[7]) : '');

			if ($company === '' || $email === '') {
				$errors[] = sprintf($this->language->get('error_row'), $row_number, $this->language->get('error_required_customer'));
				continue;
			}

			$query = $this->db->query("SELECT supplier_id FROM `" . DB_PREFIX . "supplier` WHERE email = '" . $this->db->escape($email) . "'");

			if ($query->num_rows) {
				$supplier_id = $query->row['supplier_id'];

				$this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($tax_id) . "', telephone = '" . $this->db->escape($telephone) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "', date_modified = NOW() WHERE supplier_id = '" . (int)$supplier_id . "'");

				$updated++;
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "supplier` SET company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($tax_id) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "', status = '1', date_added = NOW(), date_modified = NOW()");

				$imported++;
			}
		}

		return array(
			'imported' => $imported,
			'updated'  => $updated,
			'errors'   => $errors
		);
	}

	private function resolveCountryId($country) {
		$country = trim($country);

		if ($country === '' || strcasecmp($country, 'España') == 0 || strcasecmp($country, 'Espana') == 0) {
			return 195;
		}

		$query = $this->db->query("SELECT country_id FROM `" . DB_PREFIX . "country` WHERE LOWER(name) = LOWER('" . $this->db->escape($country) . "') OR iso_code_2 = '" . $this->db->escape(strtoupper($country)) . "' OR iso_code_3 = '" . $this->db->escape(strtoupper($country)) . "' LIMIT 1");

		if ($query->num_rows) {
			return (int)$query->row['country_id'];
		}

		return 195;
	}
}
