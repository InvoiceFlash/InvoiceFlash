<?php

class ModelPurchaseReception extends Model {
	public function addReception($data) {
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($data['store_id']);

		if ($store_info) {
			$store_name = $store_info['name'];
			$store_url = $store_info['url'];
		} else {
			$store_name = $this->config->get('config_name');
			$store_url = HTTP_CATALOG;
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

		$this->db->query("INSERT INTO " . DB_PREFIX . "reception SET
			`purchase_order_id` = '" . (int)(!empty($data['purchase_order_id']) ? $data['purchase_order_id'] : 0) . "',
			`supplier_delivery_no` = '" . $this->db->escape(isset($data['supplier_delivery_no']) ? $data['supplier_delivery_no'] : '') . "',
			`store_id` = '" . (int)$data['store_id'] . "',
			`store_name` = '" . $this->db->escape($store_name) . "',
			`store_url` = '" . $this->db->escape($store_url) . "',
			`supplier_id` = '" . (int)$data['supplier_id'] . "',
			`shipping_method` = '" . $this->db->escape($data['shipping_method']) . "',
			`shipping_code` = '" . $this->db->escape($data['shipping_code']) . "',
			`payment_method` = '" . $this->db->escape($data['payment_method']) . "',
			`payment_code` = '" . $this->db->escape($data['payment_code']) . "',
			`comment` = '" . $this->db->escape(isset($data['comment']) ? $data['comment'] : '') . "',
			`reception_status_id` = '" . (!empty($data['reception_status_id']) ? (int)$data['reception_status_id'] : 1) . "',
			`language_id` = '" . (int)$this->config->get('config_language_id') . "',
			`currency_id` = '" . (int)$currency_id . "',
			`currency_code` = '" . $this->db->escape($currency_code) . "',
			`currency_value` = '" . (float)$currency_value . "',
			`date_added` = NOW(),
			`date_modified` = NOW()");

		$reception_id = $this->db->getLastId();

		$this->addReceptionProducts($reception_id, $data);

		return $reception_id;
	}

	public function editReception($reception_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "reception SET
			`purchase_order_id` = '" . (int)(!empty($data['purchase_order_id']) ? $data['purchase_order_id'] : 0) . "',
			`supplier_delivery_no` = '" . $this->db->escape(isset($data['supplier_delivery_no']) ? $data['supplier_delivery_no'] : '') . "',
			`store_id` = '" . (int)$data['store_id'] . "',
			`supplier_id` = '" . (int)$data['supplier_id'] . "',
			`shipping_method` = '" . $this->db->escape($data['shipping_method']) . "',
			`shipping_code` = '" . $this->db->escape($data['shipping_code']) . "',
			`payment_method` = '" . $this->db->escape($data['payment_method']) . "',
			`payment_code` = '" . $this->db->escape($data['payment_code']) . "',
			`comment` = '" . $this->db->escape(isset($data['comment']) ? $data['comment'] : '') . "',
			`reception_status_id` = '" . (int)$data['reception_status_id'] . "',
			`language_id` = '" . (int)$this->config->get('config_language_id') . "',
			`date_modified` = NOW()
			WHERE `reception_id` = '" . (int)$reception_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "reception_product WHERE reception_id = '" . (int)$reception_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "reception_total WHERE reception_id = '" . (int)$reception_id . "'");

		$this->addReceptionProducts($reception_id, $data);
	}

	private function addReceptionProducts($reception_id, $data) {
		if (isset($data['reception_product'])) {
			foreach ($data['reception_product'] as $reception_product) {
				$price = (float)preg_replace('/[^-0-9\.]/', '', $reception_product['price']);
				$discount = isset($reception_product['discount']) ? (float)preg_replace('/[^0-9\.]/', '', $reception_product['discount']) : 0;

				// $discount es un porcentaje (0-100), no un importe; recalculado en
				// servidor en vez de confiar en el campo oculto total posteado.
				$total = ($price * (int)$reception_product['quantity']) * (1 - ($discount / 100));

				$this->db->query("INSERT INTO " . DB_PREFIX . "reception_product SET
					reception_id = '" . (int)$reception_id . "',
					product_id = '" . (int)$reception_product['product_id'] . "',
					name = '" . $this->db->escape($reception_product['name']) . "',
					model = '" . $this->db->escape($reception_product['model']) . "',
					quantity = '" . (int)$reception_product['quantity'] . "',
					price = '" . $price . "',
					discount = '" . $discount . "',
					total = '" . $total . "',
					tax = '" . (float)$reception_product['tax'] . "'");
			}
		}

		$total = 0;

		if (isset($data['reception_total'])) {
			foreach ($data['reception_total'] as $reception_total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "reception_total SET
					reception_id = '" . (int)$reception_id . "',
					code = '" . $this->db->escape($reception_total['code']) . "',
					title = '" . $this->db->escape($reception_total['title']) . "',
					text = '" . $this->db->escape($reception_total['text']) . "',
					`value` = '" . (float)$reception_total['value'] . "',
					sort_order = '" . (int)$reception_total['sort_order'] . "'");

				// Solo la fila "total" (ya es la suma del resto) fija el total guardado;
				// sumar todas las filas aquí duplicaría el importe (mismo bug ya
				// corregido en purchase_order, ver ModelPurchasePurchaseOrder).
				if ($reception_total['code'] == 'total') {
					$total = $reception_total['value'];
				}
			}
		}

		$this->db->query("UPDATE " . DB_PREFIX . "reception SET total = '" . (float)$total . "' WHERE reception_id = '" . (int)$reception_id . "'");
	}

	public function deleteReception($reception_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "reception WHERE reception_id = '" . (int)$reception_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "reception_product WHERE reception_id = '" . (int)$reception_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "reception_total WHERE reception_id = '" . (int)$reception_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "reception_history WHERE reception_id = '" . (int)$reception_id . "'");
	}

	public function getReception($reception_id) {
		$query = $this->db->query("SELECT r.*, rs.name AS `status`, s.company AS supplier_company, s.firstname AS supplier_firstname, s.lastname AS supplier_lastname, s.email AS supplier_email, s.telephone AS supplier_telephone FROM " . DB_PREFIX . "reception r LEFT JOIN " . DB_PREFIX . "reception_status rs ON r.reception_status_id = rs.reception_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' LEFT JOIN " . DB_PREFIX . "supplier s ON r.supplier_id = s.supplier_id WHERE r.reception_id = '" . (int)$reception_id . "'");

		return $query->num_rows ? $query->row : false;
	}

	public function getReceptions($data = array()) {
		$sql = "SELECT r.reception_id, r.purchase_order_id, r.supplier_delivery_no, s.company AS supplier_company, rs.name AS `status`, r.total, r.currency_code, r.currency_value, r.date_added, r.date_modified FROM " . DB_PREFIX . "reception r LEFT JOIN " . DB_PREFIX . "reception_status rs ON r.reception_status_id = rs.reception_status_id LEFT JOIN " . DB_PREFIX . "supplier s ON r.supplier_id = s.supplier_id WHERE rs.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($data['filter_reception_status_id']) && !is_null($data['filter_reception_status_id'])) {
			$sql .= " AND r.reception_status_id = '" . (int)$data['filter_reception_status_id'] . "'";
		} else {
			$sql .= " AND r.reception_status_id > '0'";
		}

		if (!empty($data['filter_reception_id'])) {
			$sql .= " AND r.reception_id = '" . (int)$data['filter_reception_id'] . "'";
		}

		if (!empty($data['filter_supplier'])) {
			$sql .= " AND s.company LIKE '" . $this->db->escape($data['filter_supplier']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND r.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'r.reception_id',
			'supplier_company',
			'status',
			'r.date_added',
			'r.date_modified',
			'r.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.reception_id";
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

	public function getTotalReceptions($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reception r LEFT JOIN " . DB_PREFIX . "supplier s ON r.supplier_id = s.supplier_id";

		if (isset($data['filter_reception_status_id']) && !is_null($data['filter_reception_status_id'])) {
			$sql .= " WHERE r.reception_status_id = '" . (int)$data['filter_reception_status_id'] . "'";
		} else {
			$sql .= " WHERE r.reception_status_id > '0'";
		}

		if (!empty($data['filter_reception_id'])) {
			$sql .= " AND r.reception_id = '" . (int)$data['filter_reception_id'] . "'";
		}

		if (!empty($data['filter_supplier'])) {
			$sql .= " AND s.company LIKE '%" . $this->db->escape($data['filter_supplier']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND r.total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getReceptionProducts($reception_id) {
		$query = $this->db->query("SELECT rp.*, p.image AS image FROM " . DB_PREFIX . "reception_product rp LEFT JOIN " . DB_PREFIX . "product p ON rp.product_id = p.product_id WHERE rp.reception_id = '" . (int)$reception_id . "'");

		return $query->rows;
	}

	public function getReceptionTotals($reception_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "reception_total WHERE reception_id = '" . (int)$reception_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function addReceptionHistory($reception_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "reception SET reception_status_id = '" . (int)$data['reception_status_id'] . "', date_modified = NOW() WHERE reception_id = '" . (int)$reception_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "reception_history SET reception_id = '" . (int)$reception_id . "', reception_status_id = '" . (int)$data['reception_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
	}

	public function getReceptionHistories($reception_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "reception_history rh LEFT JOIN " . DB_PREFIX . "reception_status rs ON rh.reception_status_id = rs.reception_status_id WHERE rh.reception_id = '" . (int)$reception_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReceptionHistories($reception_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reception_history WHERE reception_id = '" . (int)$reception_id . "'");

		return $query->row['total'];
	}

	public function getReceptionStatuses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "reception_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY reception_status_id");

		return $query->rows;
	}

	public function checkInvoice($reception_id) {
		$query = $this->db->query("SELECT invoice_no FROM " . DB_PREFIX . "reception WHERE reception_id = '" . (int)$reception_id . "'");

		return $query->num_rows && $query->row['invoice_no'] != 0;
	}
}
?>
