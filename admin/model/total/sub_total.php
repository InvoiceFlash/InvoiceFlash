<?php
class ModelTotalSubTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->language->load('total/sub_total');
		
		$products = $this->session->data['cart'];

		$sub_total = 0;

		foreach ($products as $product) {
			$sub_total += $product['total'];
		}

		// If the global discount already ran (lower sort_order), fold it in here so
		// Sub-Total is shown net of it, instead of a separate later subtraction.
		foreach ($total_data as $existing_total) {
			if ($existing_total['code'] == 'discount') {
				$sub_total += $existing_total['value'];
				break;
			}
		}

		$total_data[] = array(
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($sub_total, '', '', true, true),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);

		$total += $sub_total;
	}
}
?>