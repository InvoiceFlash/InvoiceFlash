<?php

class ModelToolStockMovement extends Model {
	// Aplica el movimiento al stock del producto y deja rastro en el kardex.
	// $data: product_id, product_name, model, movement_type ('in'|'out'), quantity (positivo),
	// document_type, document_id, party_type ('customer'|'supplier'|''), party_id, party_name,
	// user_id, username.
	public function registerMovement($data) {
		$quantity = abs((int)$data['quantity']);

		if (!$quantity || empty($data['product_id'])) {
			return;
		}

		$delta = ($data['movement_type'] == 'out') ? -$quantity : $quantity;

		$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity + (" . (int)$delta . ") WHERE product_id = '" . (int)$data['product_id'] . "'");

		$balance_query = $this->db->query("SELECT quantity FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$data['product_id'] . "'");

		$balance_after = $balance_query->num_rows ? (int)$balance_query->row['quantity'] : 0;

		$this->db->query("INSERT INTO " . DB_PREFIX . "stock_movement SET
			product_id = '" . (int)$data['product_id'] . "',
			product_name = '" . $this->db->escape($data['product_name']) . "',
			model = '" . $this->db->escape(isset($data['model']) ? $data['model'] : '') . "',
			movement_type = '" . $this->db->escape($data['movement_type']) . "',
			quantity = '" . $quantity . "',
			balance_after = '" . (int)$balance_after . "',
			document_type = '" . $this->db->escape($data['document_type']) . "',
			document_id = '" . (int)$data['document_id'] . "',
			party_type = '" . $this->db->escape(isset($data['party_type']) ? $data['party_type'] : '') . "',
			party_id = '" . (int)(isset($data['party_id']) ? $data['party_id'] : 0) . "',
			party_name = '" . $this->db->escape(isset($data['party_name']) ? $data['party_name'] : '') . "',
			user_id = '" . (int)$data['user_id'] . "',
			username = '" . $this->db->escape($data['username']) . "',
			date_added = NOW()");

		return $balance_after;
	}
}
?>
