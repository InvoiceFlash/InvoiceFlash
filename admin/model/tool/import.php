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
			$name = isset($row[1]) ? trim($row[1]) : '';
			$description = isset($row[2]) ? trim($row[2]) : '';
			$price = isset($row[3]) && $row[3] !== '' ? (float)str_replace(',', '.', $row[3]) : 0;
			$quantity = isset($row[4]) && $row[4] !== '' ? (int)$row[4] : 0;
			$status = isset($row[5]) && $row[5] !== '' ? (int)$row[5] : 1;

			if ($model === '' || $name === '') {
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
}
