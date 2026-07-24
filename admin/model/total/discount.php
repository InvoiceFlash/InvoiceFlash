<?php
class ModelTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('discount_status')) {
			$this->language->load('total/discount');

			$discount = isset($this->request->post['global_discount']) ? (float)preg_replace('/[^0-9.]/', '', $this->request->post['global_discount']) : 0;

			if ($discount > 0) {
				$total_data[] = array(
					'code'       => 'discount',
					'title'      => $this->language->get('text_discount'),
					'text'       => $this->currency->format(-$discount, '', '', true, true),
					'value'      => -$discount,
					'sort_order' => $this->config->get('discount_sort_order')
				);

				$total -= $discount;
			}
		}
	}
}
?>
