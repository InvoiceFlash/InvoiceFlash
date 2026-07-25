<?php
class ModelToolImport extends Model {
	private $flashZoneCache = array();
	private $flashGeneralTaxClassId = null;

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

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET company = '" . $this->db->escape($company) . "', telephone = '" . $this->db->escape($telephone) . "', date_modified = NOW(), nif = '" . $this->db->escape($nif) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

				$updated++;
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET company = '" . $this->db->escape($company) . "', approved = '1', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', customer_group_id = '1', status = '1', date_added = NOW(), date_modified = NOW(), nif = '" . $this->db->escape($nif) . "', country_id = '" . (int)$country_id . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "'");

				$customer_id = $this->db->getLastId();

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
		@set_time_limit(0);
		@ini_set('max_execution_time', 0);

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

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

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

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

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

		$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE contable_account = '" . $this->db->escape($code) . "'");

		if ($query->num_rows) {
			$customer_id = $query->row['customer_id'];

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET company = '" . $this->db->escape($title) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($phone) . "', fax = '" . $this->db->escape($fax) . "', date_modified = NOW(), nif = '" . $this->db->escape($cif) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "' WHERE customer_id = '" . (int)$customer_id . "'");

			return;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET company = '" . $this->db->escape($title) . "', approved = '1', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($phone) . "', fax = '" . $this->db->escape($fax) . "', customer_group_id = '1', status = '1', date_added = NOW(), date_modified = NOW(), nif = '" . $this->db->escape($cif) . "', contable_account = '" . $this->db->escape($code) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "'");

		$customer_id = $this->db->getLastId();

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

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

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

	public function importFlashGestion($path, $options) {
		require_once(DIR_SYSTEM . 'library/dbf.php');

		$path = rtrim(str_replace('\\', '/', $path), '/');

		$result = array(
			'products'  => 0,
			'customers' => 0,
			'contacts'  => 0,
			'suppliers' => 0,
			'errors'    => array()
		);

		if (!empty($options['product'])) {
			$this->importFlashProducts($path, $result, !empty($options['company_code']) ? $options['company_code'] : '');
		}

		if (!empty($options['customer'])) {
			$this->importFlashCustomers($path, $result, !empty($options['company_code']) ? $options['company_code'] : '');
			$this->importFlashContacts($path, $result, !empty($options['company_code']) ? $options['company_code'] : '');
		}

		if (!empty($options['supplier'])) {
			$this->importFlashSuppliers($path, $result);
		}

		return $result;
	}

	private function findFlashDbf($path, $names, &$result) {
		foreach ($names as $name) {
			foreach (array($name, strtoupper($name), strtolower($name)) as $candidate) {
				if (is_readable($path . '/' . $candidate)) {
					return $path . '/' . $candidate;
				}
			}
		}

		$result['errors'][] = sprintf($this->language->get('error_saconta_file'), $names[0]);

		return false;
	}

	// ttab11 relaciona cliente (T11CCLI, = ttab4.T4CCLI) con el centro/empresa (T11CCEN) al que pertenece.
	// Un mismo cliente puede aparecer en varios centros, así que se recogen todas las coincidencias.
	private function getFlashGestionCompanyClients($path, $company_code, &$result) {
		$file = $this->findFlashDbf($path, array('ttab11.dbf'), $result);

		if (!$file) {
			return false;
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return false;
		}

		$clients = array();

		foreach ($dbf->rows() as $row) {
			// T11CCEN puede venir como campo Numérico (entero, sin ceros a la izquierda) o
			// Carácter ('006'); se normaliza a entero para comparar sin depender del tipo de campo.
			$centro = trim((string)$row['T11CCEN']);

			if ($centro !== '' && (int)$centro === (int)$company_code) {
				$clients[trim($row['T11CCLI'])] = true;
			}
		}

		return $clients;
	}

	// confent tiene una fila por usuario con la empresa/centro (CODEMP/CODCEN) que tiene configurados
	// y el almacén de entradas (CALMACE) que usa esa empresa.
	private function getFlashGestionCompanyWarehouses($path, $company_code, &$result) {
		$file = $this->findFlashDbf($path, array('confent.dbf'), $result);

		if (!$file) {
			return false;
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return false;
		}

		$warehouses = array();

		foreach ($dbf->rows() as $row) {
			// Mismo motivo que en T11CCEN: CODCEN puede venir como Numérico sin ceros a la izquierda.
			$centro = trim((string)$row['CODCEN']);

			if ($centro !== '' && (int)$centro === (int)$company_code) {
				$almacen = trim($row['CALMACE']);

				if ($almacen !== '') {
					$warehouses[$almacen] = true;
				}
			}
		}

		return $warehouses;
	}

	// ttab32 relaciona artículo (T32CODART, = ttab22.T22CODART) con el almacén (T32CALMAC) en el que tiene stock.
	private function getFlashGestionWarehouseProducts($path, $warehouses, &$result) {
		$file = $this->findFlashDbf($path, array('ttab32.dbf'), $result);

		if (!$file) {
			return false;
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return false;
		}

		$products = array();

		foreach ($dbf->rows() as $row) {
			if (isset($warehouses[trim($row['T32CALMAC'])])) {
				$products[trim($row['T32CODART'])] = true;
			}
		}

		return $products;
	}

	private function importFlashProducts($path, &$result, $company_code = '') {
		$file = $this->findFlashDbf($path, array('ttab22.dbf'), $result);

		if (!$file) {
			return;
		}

		$allowed_products = null;

		if ($company_code !== '') {
			$warehouses = $this->getFlashGestionCompanyWarehouses($path, $company_code, $result);

			if ($warehouses === false) {
				return;
			}

			$allowed_products = $this->getFlashGestionWarehouseProducts($path, $warehouses, $result);

			if ($allowed_products === false) {
				return;
			}
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

		$batch      = array();
		$batch_size = 20;

		foreach ($dbf->rows() as $row) {
			$model = trim($row['T22CODART']);

			if ($model === '') {
				continue;
			}

			if ($allowed_products !== null && !isset($allowed_products[$model])) {
				continue;
			}

			$name = trim($row['T22NART']);

			if ($name === '') {
				continue;
			}

			$description = trim($row['T22NARTEXT']);

			if ($description === '') {
				$description = $name;
			}

			$batch[] = array(
				'model'       => $model,
				'name'        => $name,
				'description' => $description,
				'price'       => (float)$row['T22PVP'],
				'status'      => (!empty($row['LLSINUSO']) || !empty($row['T22INACTIV'])) ? 0 : 1
			);

			if (count($batch) >= $batch_size) {
				$this->flushFlashProductBatch($batch, $result);
				$batch = array();
			}
		}

		if ($batch) {
			$this->flushFlashProductBatch($batch, $result);
		}

		$this->cache->delete('product');
	}

	// Traduce nombre y descripción de cada producto del lote a inglés (en un único lote/petición
	// a la API de Claude) y da de alta/actualiza el producto con SKU = código de artículo,
	// clase de impuesto "General" y el nombre/descripción en español (language_id 2) e inglés
	// (language_id 1, traducido).
	private function flushFlashProductBatch($batch, &$result) {
		$texts = array();

		foreach ($batch as $i => $item) {
			$texts[$i . '_name']        = $item['name'];
			$texts[$i . '_description'] = $item['description'];
		}

		$translated = $this->translateTextsToEnglish($texts, $result);

		$tax_class_id = $this->resolveGeneralTaxClassId($result);

		foreach ($batch as $i => $item) {
			$name_en        = isset($translated[$i . '_name']) ? $translated[$i . '_name'] : $item['name'];
			$description_en = isset($translated[$i . '_description']) ? $translated[$i . '_description'] : $item['description'];

			$this->upsertFlashProduct($item['model'], $item['price'], $item['status'], $tax_class_id, $item['name'], $item['description'], $name_en, $description_en);

			$result['products']++;
		}
	}

	private function upsertFlashProduct($model, $price, $status, $tax_class_id, $name_es, $description_es, $name_en, $description_en) {
		$query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE model = '" . $this->db->escape($model) . "'");

		if ($query->num_rows) {
			$product_id = $query->row['product_id'];

			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET sku = '" . $this->db->escape($model) . "', price = '" . (float)$price . "', status = '" . (int)$status . "', tax_class_id = '" . (int)$tax_class_id . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET model = '" . $this->db->escape($model) . "', sku = '" . $this->db->escape($model) . "', upc = '', ean = '', jan = '', isbn = '', mpn = '', location = '', quantity = '0', minimum = '1', subtract = '1', stock_status_id = '0', date_available = '" . date('Y-m-d') . "', manufacturer_id = '0', shipping = '1', price = '" . (float)$price . "', points = '0', weight = '0.00000000', weight_class_id = '0', length = '0.00000000', width = '0.00000000', height = '0.00000000', length_class_id = '0', sort_order = '0', status = '" . (int)$status . "', tax_class_id = '" . (int)$tax_class_id . "', date_added = NOW(), date_modified = NOW()");

			$product_id = $this->db->getLastId();
		}

		$this->upsertProductDescription($product_id, 2, $name_es, $description_es);
		$this->upsertProductDescription($product_id, 1, $name_en, $description_en);
	}

	private function upsertProductDescription($product_id, $language_id, $name, $description) {
		$query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product_description` WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET name = '" . $this->db->escape($name) . "', description = '" . $this->db->escape($description) . "' WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($name) . "', meta_keyword = '', meta_description = '', description = '" . $this->db->escape($description) . "', tag = ''");
		}
	}

	private function resolveGeneralTaxClassId(&$result) {
		if ($this->flashGeneralTaxClassId !== null) {
			return $this->flashGeneralTaxClassId;
		}

		$query = $this->db->query("SELECT tax_class_id FROM `" . DB_PREFIX . "tax_class` WHERE title = 'General' LIMIT 1");

		if ($query->num_rows) {
			$this->flashGeneralTaxClassId = (int)$query->row['tax_class_id'];
		} else {
			$result['errors'][] = 'No se encontró la clase de impuesto "General"; los productos se han importado sin clase de impuesto.';
			$this->flashGeneralTaxClassId = 0;
		}

		return $this->flashGeneralTaxClassId;
	}

	// Traduce un conjunto de textos (nombre/descripción de producto) de español a inglés en una
	// única petición a la API de Claude (system > Ajustes > IA > API KEY Claude). Si no hay API
	// key configurada, o la traducción falla, se devuelve el texto original sin traducir.
	private function translateTextsToEnglish($texts, &$result) {
		if (!$texts) {
			return array();
		}

		$api_key = $this->config->get('config_claude_api_key');

		if (!$api_key) {
			return $texts;
		}

		$translations = $texts;
		$chunks       = array_chunk($texts, 40, true);

		foreach ($chunks as $chunk) {
			$keys   = array_keys($chunk);
			$values = array_values($chunk);

			$payload = array(
				'model'      => 'claude-opus-4-8',
				'max_tokens' => 4096,
				'system'     => 'Traduce cada texto del array JSON de español a inglés. Son nombres y descripciones de productos de un catálogo. Devuelve ÚNICAMENTE un array JSON de strings, en el mismo orden y con la misma longitud que el array recibido, sin explicaciones ni texto adicional ni bloques de código. Si un texto ya está en inglés, o es un código/referencia no traducible, devuélvelo tal cual.',
				'messages'   => array(
					array('role' => 'user', 'content' => json_encode($values, JSON_UNESCAPED_UNICODE))
				)
			);

			$ch = curl_init('https://api.anthropic.com/v1/messages');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'x-api-key: ' . $api_key,
				'anthropic-version: 2023-06-01'
			));
			curl_setopt($ch, CURLOPT_TIMEOUT, 120);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			$raw        = curl_exec($ch);
			$http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_error = curl_error($ch);
			curl_close($ch);

			if ($raw === false || $http_code !== 200) {
				$result['errors'][] = 'No se pudo traducir al ingl&eacute;s (' . ($curl_error !== '' ? $curl_error : 'HTTP ' . $http_code) . '); se ha usado el texto original en los dos idiomas.';
				continue;
			}

			$resp     = json_decode($raw, true);
			$text_out = isset($resp['content'][0]['text']) ? trim($resp['content'][0]['text']) : '';
			$text_out = preg_replace('/^```(?:json)?\s*/', '', $text_out);
			$text_out = preg_replace('/\s*```$/', '', $text_out);

			$decoded = json_decode($text_out, true);

			if (is_array($decoded) && (count($decoded) === count($values))) {
				foreach ($keys as $i => $key) {
					$translations[$key] = (string)$decoded[$i];
				}
			} else {
				$result['errors'][] = 'Respuesta de traducci&oacute;n inv&aacute;lida; se ha usado el texto original en los dos idiomas para parte de los productos.';
			}
		}

		return $translations;
	}

	// ttab29 relaciona el código de código postal de ttab4 (T4CCP, código interno tipo '001410')
	// con el código postal real (T29CP, p.ej. '01001'), la ciudad (T29POBLA) y la provincia
	// (T29PROV): T4CCP NO es el código postal en sí, es una clave a buscar en ttab29.
	private function getFlashGestionLocations($path, &$result) {
		$file = $this->findFlashDbf($path, array('ttab29.dbf'), $result);

		if (!$file) {
			return array();
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return array();
		}

		$locations = array();

		foreach ($dbf->rows() as $row) {
			$code = trim($row['T29CCP']);

			if ($code !== '') {
				$locations[$code] = array(
					'postcode' => trim($row['T29CP']),
					'city'     => trim($row['T29POBLA']),
					'province' => trim($row['T29PROV'])
				);
			}
		}

		return $locations;
	}

	// ttab31 relaciona el código de cliente (T31CCLI, = ttab4.T4CCLI) con su(s) cuenta(s)
	// bancaria(s). Un cliente puede tener varias filas (histórico de cuentas), pero siempre
	// hay como mucho una marcada T31ACTIVO=1 (comprobado sobre datos reales: 324/324 clientes
	// con cuenta tienen exactamente 0 o 1 fila activa, nunca más) - es la que hay que usar.
	// El IBAN español no viene en un solo campo: hay que componerlo a partir de T31CIBAN
	// (país + dígitos de control, 4 car.) + T31CENTBCO (entidad, 4) + T31CAGBCO (oficina, 4)
	// + T31CDC (dígitos de control de la CCC, 2) + T31CCUENBA (nº de cuenta, 10) = 24 car.
	// Si la concatenación no da exactamente 24 caracteres (cuenta vacía, o datos de prueba/
	// corruptos vistos en el propio SaConta, p.ej. '111111111111111111111111111111111') se
	// descarta en vez de guardar basura en customer.bank_cc.
	private function getFlashGestionBankAccounts($path, &$result) {
		$file = $this->findFlashDbf($path, array('ttab31.dbf'), $result);

		if (!$file) {
			return array();
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return array();
		}

		$accounts = array();

		foreach ($dbf->rows() as $row) {
			if ((string)$row['T31ACTIVO'] !== '1') {
				continue;
			}

			$client_code = trim($row['T31CCLI']);

			if ($client_code === '') {
				continue;
			}

			$iban = trim($row['T31CIBAN']) . trim($row['T31CENTBCO']) . trim($row['T31CAGBCO']) . trim($row['T31CDC']) . trim($row['T31CCUENBA']);

			if (strlen($iban) !== 24) {
				continue;
			}

			$accounts[$client_code] = array(
				'iban' => $iban,
				'bic'  => trim($row['T31SWIFT'])
			);
		}

		return $accounts;
	}

	// Compara nombres de provincia/zona ignorando mayúsculas, acentos y entidades HTML
	// (p.ej. zone.name = '&Aacute;lava' vs ttab29.T29PROV = 'Alava').
	// No se usa iconv('...//TRANSLIT') porque en este entorno convierte 'Á' en "'A"
	// (antepone un apóstrofo) en vez de 'A', lo que rompe la comparación.
	private function normalizeZoneName($name) {
		$name = html_entity_decode($name, ENT_QUOTES, 'UTF-8');

		$accents = array(
			'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'Á' => 'A', 'À' => 'A', 'Ä' => 'A', 'Â' => 'A',
			'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e', 'É' => 'E', 'È' => 'E', 'Ë' => 'E', 'Ê' => 'E',
			'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i', 'Í' => 'I', 'Ì' => 'I', 'Ï' => 'I', 'Î' => 'I',
			'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'Ó' => 'O', 'Ò' => 'O', 'Ö' => 'O', 'Ô' => 'O',
			'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u', 'Ú' => 'U', 'Ù' => 'U', 'Ü' => 'U', 'Û' => 'U',
			'ñ' => 'n', 'Ñ' => 'N', 'ç' => 'c', 'Ç' => 'C'
		);

		$name = strtr($name, $accents);
		$name = strtoupper(trim($name));

		// ttab29 usa variantes/nombres alternativos para algunas provincias que no coinciden
		// literalmente con el nombre de la zona en InvoiceFlash (nombre oficial, capital de
		// provincia, o nombre en euskera en vez de en castellano).
		$aliases = array(
			'A CORUNA'                  => 'LA CORUNA',
			'CORUNA'                    => 'LA CORUNA',
			'ORENSE'                    => 'OURENSE',
			'GRAN CANARIA'              => 'LAS PALMAS',
			'LAS PALMAS DE GRAN CANARIA' => 'LAS PALMAS',
			'TENERIFE'                  => 'SANTA CRUZ DE TENERIFE',
			'S. C. DE TENERIFE'         => 'SANTA CRUZ DE TENERIFE',
			'STA. CRUZ DE TENERIFE'     => 'SANTA CRUZ DE TENERIFE',
			'BIZKAIA'                   => 'VIZCAYA',
			'VIZKAYA'                   => 'VIZCAYA',
			'GIPUZKOA'                  => 'GUIPUZCOA',
			'GUIPUZKOA'                 => 'GUIPUZCOA',
			'ILLES BALEARS'             => 'BALEARES',
			'IBIZA'                     => 'BALEARES',
			'MENORCA - BALEARS'         => 'BALEARES',
			'LOGRONO'                   => 'LA RIOJA'
		);

		return isset($aliases[$name]) ? $aliases[$name] : $name;
	}

	private function resolveZoneId($country_id, $province) {
		$province = trim($province);

		if ($province === '') {
			return 0;
		}

		if (!isset($this->flashZoneCache[$country_id])) {
			$zones = array();

			$query = $this->db->query("SELECT zone_id, name FROM `" . DB_PREFIX . "zone` WHERE country_id = '" . (int)$country_id . "'");

			foreach ($query->rows as $row) {
				$zones[$this->normalizeZoneName($row['name'])] = (int)$row['zone_id'];
			}

			$this->flashZoneCache[$country_id] = $zones;
		}

		$key = $this->normalizeZoneName($province);

		return isset($this->flashZoneCache[$country_id][$key]) ? $this->flashZoneCache[$country_id][$key] : 0;
	}

	private function importFlashCustomers($path, &$result, $company_code = '') {
		$file = $this->findFlashDbf($path, array('ttab4.dbf'), $result);

		if (!$file) {
			return;
		}

		$allowed_clients = null;

		if ($company_code !== '') {
			$allowed_clients = $this->getFlashGestionCompanyClients($path, $company_code, $result);

			if ($allowed_clients === false) {
				return;
			}
		}

		$locations = $this->getFlashGestionLocations($path, $result);
		$bank_accounts = $this->getFlashGestionBankAccounts($path, $result);

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

		foreach ($dbf->rows() as $row) {
			$client_code = trim($row['T4CCLI']);

			if ($allowed_clients !== null && !isset($allowed_clients[$client_code])) {
				continue;
			}

			$bank_account = isset($bank_accounts[$client_code]) ? $bank_accounts[$client_code] : null;

			$contable_account = trim($row['T4CCONTA']);
			$company = trim($row['T4NOM']);

			if ($company === '') {
				$company = trim($row['T4NOM2']);
			}

			if ($company === '') {
				continue;
			}

			$nif = trim($row['T4CIF']);
			$email = trim($row['T4CORREO']);
			$telephone = trim($row['T4TEL1']);

			if ($telephone === '') {
				$telephone = trim($row['T4TEL2']);
			}

			$fax = trim($row['T4FAX']);
			$web = trim($row['T4WEB']);
			$address_1 = trim($row['T4DOM']);
			$postcode_code = trim($row['T4CCP']);
			$location = isset($locations[$postcode_code]) ? $locations[$postcode_code] : array('postcode' => '', 'city' => '', 'province' => '');
			$postcode = $location['postcode'];
			$city = $location['city'];
			$country_id = $this->resolveCountryId('');
			$zone_id = $this->resolveZoneId($country_id, $location['province']);
			$status = (!empty($row['LLSINUSO']) || !empty($row['T4INACTIV'])) ? 0 : 1;
			$date_added = !empty($row['T4FECHALT']) ? "'" . $this->db->escape($row['T4FECHALT']) . "'" : 'NOW()';

			// El código de cliente FLASH (cod_flash) es la clave única real: contable_account
			// puede venir vacío o compartido entre varios clientes (p. ej. una cuenta genérica),
			// lo que antes hacía que se sobrescribiera siempre al mismo cliente ya existente.
			$customer_id = null;

			if ($client_code !== '') {
				$query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` WHERE cod_flash = '" . (int)$client_code . "'");

				if ($query->num_rows) {
					$customer_id = $query->row['customer_id'];
				}
			}

			if ($customer_id) {
				$bank_sql = '';

				if ($bank_account !== null) {
					$bank_sql = ", bank_cc = '" . $this->db->escape($bank_account['iban']) . "'";

					if ($bank_account['bic'] !== '') {
						$bank_sql .= ", bic = '" . $this->db->escape($bank_account['bic']) . "'";
					}
				}

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET company = '" . $this->db->escape($company) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', fax = '" . $this->db->escape($fax) . "', status = '" . (int)$status . "', date_modified = NOW(), nif = '" . $this->db->escape($nif) . "', contable_account = '" . $this->db->escape($contable_account) . "', cwww = '" . $this->db->escape($web) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', zone_id = '" . (int)$zone_id . "', country_id = '" . (int)$country_id . "'" . $bank_sql . " WHERE customer_id = '" . (int)$customer_id . "'");

				$address_query = $this->db->query("SELECT address_id FROM `" . DB_PREFIX . "address` WHERE customer_id = '" . (int)$customer_id . "' ORDER BY address_id ASC LIMIT 1");

				if ($address_query->num_rows) {
					$this->db->query("UPDATE `" . DB_PREFIX . "address` SET company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($nif) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', zone_id = '" . (int)$zone_id . "', country_id = '" . (int)$country_id . "' WHERE address_id = '" . (int)$address_query->row['address_id'] . "'");
				} elseif ($address_1 !== '' || $postcode !== '' || $city !== '') {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET customer_id = '" . (int)$customer_id . "', company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($nif) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', zone_id = '" . (int)$zone_id . "', country_id = '" . (int)$country_id . "'");

					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			} else {
				$bank_sql = '';

				if ($bank_account !== null) {
					$bank_sql = ", bank_cc = '" . $this->db->escape($bank_account['iban']) . "'";

					if ($bank_account['bic'] !== '') {
						$bank_sql .= ", bic = '" . $this->db->escape($bank_account['bic']) . "'";
					}
				}

				$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET company = '" . $this->db->escape($company) . "', approved = '1', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', fax = '" . $this->db->escape($fax) . "', customer_group_id = '1', status = '" . (int)$status . "', date_added = " . $date_added . ", date_modified = NOW(), nif = '" . $this->db->escape($nif) . "', cod_flash = '" . (int)$client_code . "', contable_account = '" . $this->db->escape($contable_account) . "', cwww = '" . $this->db->escape($web) . "', address = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', zone_id = '" . (int)$zone_id . "', country_id = '" . (int)$country_id . "'" . $bank_sql . "");

				$customer_id = $this->db->getLastId();

				if ($address_1 !== '' || $postcode !== '' || $city !== '') {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET customer_id = '" . (int)$customer_id . "', company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($nif) . "', address_1 = '" . $this->db->escape($address_1) . "', city = '" . $this->db->escape($city) . "', postcode = '" . $this->db->escape($postcode) . "', zone_id = '" . (int)$zone_id . "', country_id = '" . (int)$country_id . "'");

					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
				}
			}

			$result['customers']++;
		}
	}

	// ttab4c tiene una fila por contacto de cliente (T4CCCLI = ttab4.T4CCLI); T4CCLINART es el ID
	// único de la fila en FLASH, se guarda en customer_contacts.cod_flash para poder reimportar sin
	// duplicar (actualiza el contacto ya importado en vez de crear uno nuevo cada vez).
	private function importFlashContacts($path, &$result, $company_code = '') {
		$file = $this->findFlashDbf($path, array('ttab4c.dbf'), $result);

		if (!$file) {
			return;
		}

		$allowed_clients = null;

		if ($company_code !== '') {
			$allowed_clients = $this->getFlashGestionCompanyClients($path, $company_code, $result);

			if ($allowed_clients === false) {
				return;
			}
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

		foreach ($dbf->rows() as $row) {
			$client_code = trim($row['T4CCCLI']);

			if ($allowed_clients !== null && !isset($allowed_clients[$client_code])) {
				continue;
			}

			if (!empty($row['T4CSINUSO'])) {
				continue;
			}

			$name = trim($row['T4CCNOMBRE']);

			if ($name === '') {
				continue;
			}

			$customer_id = 0;

			if ($client_code !== '') {
				$customer_query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` WHERE cod_flash = '" . (int)$client_code . "'");

				if ($customer_query->num_rows) {
					$customer_id = $customer_query->row['customer_id'];
				}
			}

			if (!$customer_id) {
				continue;
			}

			$contact_code = trim($row['T4CCLINART']);
			$puesto = trim($row['T4CCARGO']);
			$email = trim($row['T4CCEMAIL']);
			$telef1 = trim($row['T4CCTELEF']);
			$telef2 = trim($row['T4CCTELMOV']);

			$contact_id = null;

			if ($contact_code !== '') {
				$contact_query = $this->db->query("SELECT customer_contacts_id FROM `" . DB_PREFIX . "customer_contacts` WHERE cod_flash = '" . $this->db->escape($contact_code) . "'");

				if ($contact_query->num_rows) {
					$contact_id = $contact_query->row['customer_contacts_id'];
				}
			}

			if ($contact_id) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_contacts` SET customer_id = '" . (int)$customer_id . "', cname = '" . $this->db->escape($name) . "', cpuesto = '" . $this->db->escape($puesto) . "', cemail = '" . $this->db->escape($email) . "', ctelef1 = '" . $this->db->escape($telef1) . "', ctelef2 = '" . $this->db->escape($telef2) . "', tultmod = NOW(), caplultmod = 'flash' WHERE customer_contacts_id = " . (int)$contact_id);
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_contacts` SET customer_id = '" . (int)$customer_id . "', cod_flash = '" . $this->db->escape($contact_code) . "', cname = '" . $this->db->escape($name) . "', cpuesto = '" . $this->db->escape($puesto) . "', cemail = '" . $this->db->escape($email) . "', ctelef1 = '" . $this->db->escape($telef1) . "', ctelef2 = '" . $this->db->escape($telef2) . "', date_added = NOW(), caplalta = 'flash', tultmod = NOW(), caplultmod = 'flash'");
			}

			$result['contacts']++;
		}
	}

	private function importFlashSuppliers($path, &$result) {
		$file = $this->findFlashDbf($path, array('ttab14.dbf'), $result);

		if (!$file) {
			return;
		}

		try {
			$dbf = new Dbf($file);
		} catch (Exception $e) {
			$result['errors'][] = $e->getMessage();
			return;
		}

		foreach ($dbf->rows() as $row) {
			$company = trim($row['T14RAZSOCI']);

			if ($company === '') {
				$company = trim($row['T14NPROV']);
			}

			if ($company === '') {
				continue;
			}

			$tax_id = trim($row['T14CIF']);
			$email = trim($row['T14CORREO']);
			$telephone = trim($row['T14TEL1']);

			if ($telephone === '') {
				$telephone = trim($row['T14TEL2']);
			}

			$fax = trim($row['T14FAX']);
			$web = trim($row['T14WEB']);
			$address_1 = trim($row['T14DOM']);
			$postcode = trim($row['T14CCP']);
			$country_id = $this->resolveCountryId('');
			$status = (!empty($row['LLSINUSO']) || !empty($row['NINACTIV'])) ? 0 : 1;

			$supplier_id = null;

			if ($tax_id !== '') {
				$query = $this->db->query("SELECT supplier_id FROM `" . DB_PREFIX . "supplier` WHERE tax_id = '" . $this->db->escape($tax_id) . "'");
			} else {
				$query = $this->db->query("SELECT supplier_id FROM `" . DB_PREFIX . "supplier` WHERE company = '" . $this->db->escape($company) . "'");
			}

			if ($query->num_rows) {
				$supplier_id = $query->row['supplier_id'];
			}

			if ($supplier_id) {
				$this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($tax_id) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', fax = '" . $this->db->escape($fax) . "', web = '" . $this->db->escape($web) . "', address_1 = '" . $this->db->escape($address_1) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "', status = '" . (int)$status . "', date_modified = NOW() WHERE supplier_id = '" . (int)$supplier_id . "'");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "supplier` SET company = '" . $this->db->escape($company) . "', tax_id = '" . $this->db->escape($tax_id) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', fax = '" . $this->db->escape($fax) . "', web = '" . $this->db->escape($web) . "', address_1 = '" . $this->db->escape($address_1) . "', postcode = '" . $this->db->escape($postcode) . "', country_id = '" . (int)$country_id . "', status = '" . (int)$status . "', date_added = NOW(), date_modified = NOW()");
			}

			$result['suppliers']++;
		}
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
