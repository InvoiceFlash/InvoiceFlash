<?php

class ModelPurchasePurchaseOrder extends Model {
	public function addPurchaseOrder($data) {
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

		$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order SET
			`po_number` = '" . $this->db->escape($data['po_number']) . "',
			`store_id` = '" . (int)$data['store_id'] . "',
			`store_name` = '" . $this->db->escape($store_name) . "',
			`store_url` = '" . $this->db->escape($store_url) . "',
			`supplier_id` = '" . (int)$data['supplier_id'] . "',
			`shipping_method` = '" . $this->db->escape($data['shipping_method']) . "',
			`shipping_code` = '" . $this->db->escape($data['shipping_code']) . "',
			`payment_method` = '" . $this->db->escape($data['payment_method']) . "',
			`payment_code` = '" . $this->db->escape($data['payment_code']) . "',
			`purchase_order_status_id` = '" . (int)$data['purchase_order_status_id'] . "',
			`language_id` = '" . (int)$this->config->get('config_language_id') . "',
			`currency_id` = '" . (int)$currency_id . "',
			`currency_code` = '" . $this->db->escape($currency_code) . "',
			`currency_value` = '" . (float)$currency_value . "',
			`date_added` = NOW(),
			`date_modified` = NOW()");

		$purchase_order_id = $this->db->getLastId();

		$this->addPurchaseOrderProducts($purchase_order_id, $data);

		return $purchase_order_id;
	}

	public function editPurchaseOrder($purchase_order_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET
			`po_number` = '" . $this->db->escape($data['po_number']) . "',
			`store_id` = '" . (int)$data['store_id'] . "',
			`supplier_id` = '" . (int)$data['supplier_id'] . "',
			`shipping_method` = '" . $this->db->escape($data['shipping_method']) . "',
			`shipping_code` = '" . $this->db->escape($data['shipping_code']) . "',
			`payment_method` = '" . $this->db->escape($data['payment_method']) . "',
			`payment_code` = '" . $this->db->escape($data['payment_code']) . "',
			`purchase_order_status_id` = '" . (int)$data['purchase_order_status_id'] . "',
			`language_id` = '" . (int)$this->config->get('config_language_id') . "',
			`date_modified` = NOW()
			WHERE `purchase_order_id` = '" . (int)$purchase_order_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_product WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_total WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");

		$this->addPurchaseOrderProducts($purchase_order_id, $data);
	}

	private function addPurchaseOrderProducts($purchase_order_id, $data) {
		if (isset($data['purchase_order_product'])) {
			foreach ($data['purchase_order_product'] as $purchase_order_product) {
				$price = (float)preg_replace('/[^-0-9\.]/', '', $purchase_order_product['price']);
				$total = (float)preg_replace('/[^-0-9\.]/', '', $purchase_order_product['total']);

				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product SET
					purchase_order_id = '" . (int)$purchase_order_id . "',
					product_id = '" . (int)$purchase_order_product['product_id'] . "',
					name = '" . $this->db->escape($purchase_order_product['name']) . "',
					model = '" . $this->db->escape($purchase_order_product['model']) . "',
					quantity = '" . (int)$purchase_order_product['quantity'] . "',
					price = '" . $price . "',
					total = '" . $total . "',
					tax = '" . (float)$purchase_order_product['tax'] . "'");
			}
		}

		$total = 0;

		if (isset($data['purchase_order_total'])) {
			foreach ($data['purchase_order_total'] as $purchase_order_total) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_total SET
					purchase_order_id = '" . (int)$purchase_order_id . "',
					code = '" . $this->db->escape($purchase_order_total['code']) . "',
					title = '" . $this->db->escape($purchase_order_total['title']) . "',
					text = '" . $this->db->escape($purchase_order_total['text']) . "',
					`value` = '" . (float)$purchase_order_total['value'] . "',
					sort_order = '" . (int)$purchase_order_total['sort_order'] . "'");

				$total += $purchase_order_total['value'];
			}
		}

		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET total = '" . (float)$total . "' WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");
	}

	public function deletePurchaseOrder($purchase_order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_product WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_total WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_history WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");
	}

	public function getPurchaseOrder($purchase_order_id) {
		$query = $this->db->query("SELECT po.*, pos.name AS `status`, s.company AS supplier_company, s.firstname AS supplier_firstname, s.lastname AS supplier_lastname, s.email AS supplier_email, s.telephone AS supplier_telephone FROM " . DB_PREFIX . "purchase_order po LEFT JOIN " . DB_PREFIX . "purchase_order_status pos ON po.purchase_order_status_id = pos.purchase_order_status_id AND pos.language_id = '" . (int)$this->config->get('config_language_id') . "' LEFT JOIN " . DB_PREFIX . "supplier s ON po.supplier_id = s.supplier_id WHERE po.purchase_order_id = '" . (int)$purchase_order_id . "'");

		return $query->num_rows ? $query->row : false;
	}

	public function getPurchaseOrders($data = array()) {
		$sql = "SELECT po.purchase_order_id, po.po_number, s.company AS supplier_company, pos.name AS `status`, po.total, po.currency_code, po.currency_value, po.date_added, po.date_modified FROM " . DB_PREFIX . "purchase_order po LEFT JOIN " . DB_PREFIX . "purchase_order_status pos ON po.purchase_order_status_id = pos.purchase_order_status_id LEFT JOIN " . DB_PREFIX . "supplier s ON po.supplier_id = s.supplier_id WHERE pos.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (isset($data['filter_purchase_order_status_id']) && !is_null($data['filter_purchase_order_status_id'])) {
			$sql .= " AND po.purchase_order_status_id = '" . (int)$data['filter_purchase_order_status_id'] . "'";
		} else {
			$sql .= " AND po.purchase_order_status_id > '0'";
		}

		if (!empty($data['filter_purchase_order_id'])) {
			$sql .= " AND po.purchase_order_id = '" . (int)$data['filter_purchase_order_id'] . "'";
		}

		if (!empty($data['filter_supplier'])) {
			$sql .= " AND s.company LIKE '" . $this->db->escape($data['filter_supplier']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(po.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND po.total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'po.purchase_order_id',
			'supplier_company',
			'status',
			'po.date_added',
			'po.date_modified',
			'po.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY po.purchase_order_id";
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

	public function getTotalPurchaseOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase_order po LEFT JOIN " . DB_PREFIX . "supplier s ON po.supplier_id = s.supplier_id";

		if (isset($data['filter_purchase_order_status_id']) && !is_null($data['filter_purchase_order_status_id'])) {
			$sql .= " WHERE po.purchase_order_status_id = '" . (int)$data['filter_purchase_order_status_id'] . "'";
		} else {
			$sql .= " WHERE po.purchase_order_status_id > '0'";
		}

		if (!empty($data['filter_purchase_order_id'])) {
			$sql .= " AND po.purchase_order_id = '" . (int)$data['filter_purchase_order_id'] . "'";
		}

		if (!empty($data['filter_supplier'])) {
			$sql .= " AND s.company LIKE '%" . $this->db->escape($data['filter_supplier']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(po.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND po.total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getPurchaseOrderProducts($purchase_order_id) {
		$query = $this->db->query("SELECT pop.*, p.image AS image FROM " . DB_PREFIX . "purchase_order_product pop LEFT JOIN " . DB_PREFIX . "product p ON pop.product_id = p.product_id WHERE pop.purchase_order_id = '" . (int)$purchase_order_id . "'");

		return $query->rows;
	}

	public function getPurchaseOrderTotals($purchase_order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_order_total WHERE purchase_order_id = '" . (int)$purchase_order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function addPurchaseOrderHistory($purchase_order_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "purchase_order SET purchase_order_status_id = '" . (int)$data['purchase_order_status_id'] . "', date_modified = NOW() WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_history SET purchase_order_id = '" . (int)$purchase_order_id . "', purchase_order_status_id = '" . (int)$data['purchase_order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");
	}

	public function getPurchaseOrderHistories($purchase_order_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT poh.date_added, pos.name AS status, poh.comment, poh.notify FROM " . DB_PREFIX . "purchase_order_history poh LEFT JOIN " . DB_PREFIX . "purchase_order_status pos ON poh.purchase_order_status_id = pos.purchase_order_status_id WHERE poh.purchase_order_id = '" . (int)$purchase_order_id . "' AND pos.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY poh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalPurchaseOrderHistories($purchase_order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "purchase_order_history WHERE purchase_order_id = '" . (int)$purchase_order_id . "'");

		return $query->row['total'];
	}

	public function getPurchaseOrderStatuses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "purchase_order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY purchase_order_status_id");

		return $query->rows;
	}
}
?>
