<?php
class ModelTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('discount_status')) {
			$this->language->load('total/discount');

			$discount_percent = isset($this->request->post['global_discount']) ? (float)preg_replace('/[^0-9.]/', '', $this->request->post['global_discount']) : 0;

			if ($discount_percent > 0) {
				// Runs before sub_total (sort_order), so the % base is computed straight
				// from the cart rather than trusting $total to already hold the subtotal.
				$sub_total = 0;

				foreach ($this->session->data['cart'] as $product) {
					$sub_total += $product['total'];
				}

				$discount_amount = $sub_total * ($discount_percent / 100);

				$total_data[] = array(
					'code'       => 'discount',
					'title'      => $this->language->get('text_discount') . ' (' . $discount_percent . '%)',
					'text'       => $this->currency->format(-$discount_amount, '', '', true, true),
					'value'      => -$discount_amount,
					'sort_order' => $this->config->get('discount_sort_order')
				);

				// $total is left untouched here - sub_total.php folds this discount into
				// the net subtotal it adds, so it isn't subtracted twice.
			}
		}
	}
}
?>
