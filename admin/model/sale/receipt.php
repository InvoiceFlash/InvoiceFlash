<?php
class ModelSaleReceipt extends Model {
	public function generateRemittance($nammout) {
      	$this->db->query("INSERT INTO `" . DB_PREFIX . "remittances` SET bank_id = 1, 
			amount = " .(float)$nammout.", date_added = now() ");
		
      	$remittance_id = $this->db->getLastId();
		
		return $remittance_id;
	}
	
	public function generateRemittance_lines($remittance_id,$order_id,$amount) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "remittances_lines` 
			SET remittance_id = '" .$remittance_id ."', 
			customer_name = (SELECT i.firstname FROM receipt r LEFT JOIN invoice i ON r.invoice_id = i.invoice_id WHERE receipt_id = " .  (int)$order_id . "), 
			amount = '" .$amount ."',
			date_vto = now(),
			receipt_id='" . (int)$order_id . "'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "receipt` SET paid = 1, remittance_id = '" .$remittance_id ."' where receipt_id = '" . (int)$order_id . "'");
	}
	
	public function checknoremittance($receipt_id) {
		$query = $this->db->query("SELECT COUNT(receipt_id) AS total FROM remittances_lines WHERE receipt_id = " . (int)$receipt_id);

		if ($query->row['total']==0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function amountreceipt($order_id) {
		$query = $this->db->query("SELECT amount AS total FROM `" . DB_PREFIX . "receipt` WHERE receipt_id = " . (int)$order_id);

		return $query->row['total'];
	}

	public function getTotalReceipts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "receipt AS r ";
		
		if (isset($data['filter_customer'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "invoice AS i ON i.invoice_id = r.invoice_id";
		}

		$sql .= " WHERE 1 = 1";

		if (isset($data['filter_receipt_id'])) {
			$sql .= " AND receipt_id = " . (int)$data['filter_receipt_id'];
		}

		if (isset($data['filter_remittance_id'])) {
			$sql .= " AND remittance_id = " . (int)$data['filter_remittance_id'];
		}

		if (isset($data['filter_invoice_id'])) {
			$sql .= " AND invoice_id = " . (int)$data['filter_invoice_id'];
		}

		if (isset($data['filter_customer'])) {
			$sql .= " AND CONCAT(i.firstname, ' ', i.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (isset($data['filter_status'])) {
			$sql .= " AND ";

			if ($data['filter_status']==1) {
				$sql .= 'paid = 1';
			} else {
				$sql .= 'paid = 0';
			}
		}

		if (isset($data['filter_total'])) {
			$sql .= " AND amount = " . (float)$data['filter_total'];
		}

		if (isset($data['filter_date_added'])) {
			$sql .= " AND date_added = " . $this->db->escape($data['filter_date_added']);
		}

		if (isset($data['filter_date_modified'])) {
			$sql .= " AND date_modified = " . $this->db->escape($data['filter_date_modified']);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];

	}

	public function getReceipts($data = array()) {
		$sql = "SELECT r.*, c.company AS customer FROM " . DB_PREFIX . "receipt AS r LEFT JOIN " . DB_PREFIX . "invoice AS i ON i.invoice_id = r.invoice_id LEFT JOIN " . DB_PREFIX . "customer AS c ON c.customer_id = i.customer_id WHERE 1 = 1";
		
		if (isset($data['filter_receipt_id'])) {
			$sql .= " AND receipt_id = " . (int)$data['filter_receipt_id'];
		}

		if (isset($data['filter_remittance_id'])) {
			$sql .= " AND remittance_id = " . (int)$data['filter_remittance_id'];
		}

		if (isset($data['filter_invoice_id'])) {
			$sql .= " AND invoice_id = " . (int)$data['filter_invoice_id'];
		}

		if (isset($data['filter_customer'])) {
			$sql .= " AND i.company LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (isset($data['filter_status'])) {
			$sql .= " AND ";

			if ($data['filter_status']==1) {
				$sql .= "paid = 1";
			} else {
				$sql .= "paid = 0";
			}
		}

		if (isset($data['filter_total'])) {
			$sql .= " AND amount = " . (float)$data['filter_total'];
		}

		if (isset($data['filter_date_added'])) {
			$sql .= " AND date_added = " . $this->db->escape($data['filter_date_added']);
		}

		if (isset($data['filter_date_modified'])) {
			$sql .= " AND date_modified = " . $this->db->escape($data['filter_date_modified']);
		}

		$sort_data = array(
			'customer',
			'r.receipt_id',
			'r.remittance_id',
			'r.invoice_id',
			'r.paid',
			'r.amount',
			'r.date_added',
			'r.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.receipt_id";	
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

	public function editReceipt($receipt_id, $data = array()) {
		if ($data['status_id']==1) {
			$paid=0;
		} else {
			$paid=1;
		}
		

		$this->db->query("UPDATE " . DB_PREFIX . "receipt SET paid = " . (int)$paid . ", date_modified = now() WHERE receipt_id = " . (int)$receipt_id);

	}

	public function getReceipt($receipt_id)	{
		$query = $this->db->query("SELECT IF(paid = '1', 2,1) AS status_id FROM " . DB_PREFIX . "receipt WHERE receipt_id = " . (int)$receipt_id);

		return $query->row;
	}

	public function getReceiptAmmount($receipt_id) {
		$query = $this->db->query("SELECT amount FROM " . DB_PREFIX . "receipt WHERE receipt_id = " . (int)$receipt_id);

		return $query->row['amount'];
	}

}
?>