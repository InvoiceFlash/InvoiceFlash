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

	public function importSaconta($path) {
		require_once(DIR_SYSTEM . 'library/dbf.php');

		$path = rtrim(str_replace('\\', '/', $path), '/');

		$result = array(
			'ctab6'     => 0,
			'ctab61'    => 0,
			'customers' => 0,
			'ctab8'     => 0,
			'errors'    => array()
		);

		$this->importSacontaChart($path, $result);
		$this->importSacontaSubaccounts($path, $result);
		$this->importSacontaEntries($path, $result);

		return $result;
	}

	private function importSacontaChart($path, &$result) {
		$file = $path . '/ctab6.dbf';

		if (!is_readable($file)) {
			$result['errors'][] = sprintf($this->language->get('error_saconta_file'), 'ctab6.dbf');
			return;
		}

		$dbf = new Dbf($file);

		foreach ($dbf->rows() as $row) {
			$code = trim($row['T6CCTA']);

			if ($code === '') {
				continue;
			}

			$name = trim($row['T6CNOM']);
			$level = (int)$row['T6NNIV'];

			$query = $this->db->query("SELECT ctab6_id FROM " . DB_PREFIX . "ctab6 WHERE code = '" . $this->db->escape($code) . "'");

			if ($query->num_rows) {
				$this->db->query("UPDATE " . DB_PREFIX . "ctab6 SET name = '" . $this->db->escape($name) . "', `level` = '" . $level . "' WHERE ctab6_id = '" . (int)$query->row['ctab6_id'] . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ctab6 SET code = '" . $this->db->escape($code) . "', name = '" . $this->db->escape($name) . "', `level` = '" . $level . "'");
			}

			$result['ctab6']++;
		}
	}

	private function importSacontaSubaccounts($path, &$result) {
		$file = $path . '/ctab61.dbf';

		if (!is_readable($file)) {
			$result['errors'][] = sprintf($this->language->get('error_saconta_file'), 'ctab61.dbf');
			return;
		}

		$dbf = new Dbf($file);

		foreach ($dbf->rows() as $row) {
			$code = trim($row['T61CCTA']);

			if ($code === '') {
				continue;
			}

			$title       = trim($row['T61CNOM']);
			$debit       = (float)$row['T61NDEBE'];
			$credit      = (float)$row['T61NHABER'];
			$cif         = trim($row['T61CCIF']);
			$street_type = trim($row['T61CTIPOC']);
			$street      = trim($row['T61CCALLE']);
			$number      = trim($row['T61CNUM']);
			$city        = trim($row['T61CPOB']);
			$postcode    = trim($row['T61CCP']);
			$province    = trim($row['T61CPROV']);
			$country     = trim($row['T61CPAIS']);
			$vat_regime  = trim($row['T61CREGIVA']);
			$phone       = trim($row['T61CTF']);
			$fax         = trim($row['T61CFAX']);
			$email       = trim($row['T61CEMAIL']);
			$eu_vat_code = trim($row['T61CFINT']);

			$fields = "title = '" . $this->db->escape($title) . "',
				debit = '" . $debit . "',
				credit = '" . $credit . "',
				vat_regime = '" . $this->db->escape($vat_regime) . "',
				cif = '" . $this->db->escape($cif) . "',
				phone = '" . $this->db->escape($phone) . "',
				fax = '" . $this->db->escape($fax) . "',
				email = '" . $this->db->escape($email) . "',
				street_type = '" . $this->db->escape($street_type) . "',
				street = '" . $this->db->escape($street) . "',
				number = '" . $this->db->escape($number) . "',
				postcode = '" . $this->db->escape($postcode) . "',
				city = '" . $this->db->escape($city) . "',
				province = '" . $this->db->escape($province) . "',
				country = '" . $this->db->escape($country) . "',
				eu_vat_code = '" . $this->db->escape($eu_vat_code) . "'";

			$query = $this->db->query("SELECT ctab61_id FROM " . DB_PREFIX . "ctab61 WHERE code = '" . $this->db->escape($code) . "'");

			if ($query->num_rows) {
				$this->db->query("UPDATE " . DB_PREFIX . "ctab61 SET " . $fields . " WHERE ctab61_id = '" . (int)$query->row['ctab61_id'] . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "ctab61 SET code = '" . $this->db->escape($code) . "', " . $fields);
			}

			$result['ctab61']++;

			// Cuentas de clientes (grupo 430 del PGC): además, crea/actualiza el cliente correspondiente.
			// Se excluye la cuenta de grupo (p. ej. 4300000000, todo ceros tras el prefijo), que no es un cliente real.
			if (substr($code, 0, 3) === '430' && $title !== '' && trim(substr($code, 3), '0') !== '') {
				$this->importSacontaCustomer($code, $title, $cif, $email, $phone, $fax, $street_type, $street, $number, $city, $postcode, $country, $result);
			}
		}
	}

	private function importSacontaCustomer($code, $title, $cif, $email, $phone, $fax, $street_type, $street, $number, $city, $postcode, $country, &$result) {
		$country_id = $this->resolveCountryId($country);
		$address_1  = trim($street_type . ' ' . $street . ' ' . $number);

		$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "fl_customers WHERE contable_account = '" . $this->db->escape($code) . "'");

		if ($query->num_rows) {
			$customer_id = $query->row['customer_id'];

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET company = '" . $this->db->escape($title) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($phone) . "', fax = '" . $this->db->escape($fax) . "', date_modified = NOW() WHERE customer_id = '" . (int)$customer_id . "'");

			$this->db->query("UPDATE " . DB_PREFIX . "fl_customers SET nif = '" . $this->db->escape($cif) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

			return;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET company = '" . $this->db->escape($title) . "', approved = '1', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($phone) . "', fax = '" . $this->db->escape($fax) . "', customer_group_id = '1', status = '1', date_added = NOW(), date_modified = NOW()");

		$customer_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "fl_customers SET customer_id = '" . (int)$customer_id . "', nif = '" . $this->db->escape($cif) . "', contable_account = '" . $this->db->escape($code) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "'");

		if ($address_1 !== '' || $city !== '' || $postcode !== '') {
			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', company = '" . $this->db->escape($title) . "', tax_id = '" . $this->db->escape($cif) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "'");

			$address_id = $this->db->getLastId();

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
		}

		$result['customers']++;
	}

	private function importSacontaEntries($path, &$result) {
		$file = $path . '/ctab8.dbf';

		if (!is_readable($file)) {
			$result['errors'][] = sprintf($this->language->get('error_saconta_file'), 'ctab8.dbf');
			return;
		}

		$dbf = new Dbf($file);

		$username = $this->user->getUserName();
		$values = array();
		$count = 0;

		foreach ($dbf->rows() as $row) {
			$account = trim($row['T8CCTA']);

			if ($account === '') {
				continue;
			}

			$entry_id  = (int)$row['T8NASIEN'];
			$line_date = $row['T8DFECHA'] ? "'" . $this->db->escape($row['T8DFECHA']) . "'" : 'NULL';
			$concept   = $this->db->escape(trim($row['T8CCONCEP']));
			$debit     = (float)$row['T8NDEBE'];
			$credit    = (float)$row['T8NHABER'];

			$values[] = "(" . $entry_id . ", " . $line_date . ", '" . $this->db->escape($account) . "', '" . $concept . "', '" . $debit . "', '" . $credit . "', '0', '" . $this->db->escape($username) . "', NOW(), NOW())";

			$count++;

			if (count($values) >= 500) {
				$this->flushSacontaEntries($values);
				$values = array();
			}
		}

		if ($values) {
			$this->flushSacontaEntries($values);
		}

		$result['ctab8'] = $count;
	}

	private function flushSacontaEntries($values) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ctab8 (entry_id, line_date, account, concept, debit, credit, user_id, username, date_added, date_modified) VALUES " . implode(',', $values));
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
