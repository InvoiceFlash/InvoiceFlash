<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<?php $fa = 'file-alt'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>

	<div class="panel-body">

		<ul class="nav nav-tabs"><li class="nav-item"><a class="nav-link active"href="#tab-customer" data-toggle="tab"><?php echo $tab_customer; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-total" data-toggle="tab"><?php echo $tab_total; ?></a></li></ul>

		<form class="form-horizontal" action="<?php echo $action; ?>" onsubmit="return validateForm()" method="post" enctype="multipart/form-data" id="form">

		<div class="tab-content">

			<div id="tab-customer" class="tab-pane active">

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_store; ?></label>
					<div class="control-field col-sm-4">

						<select name="store_id" class="form-control">

							<option value="0"><?php echo $text_default; ?></option>

							<?php foreach ($stores as $store) { ?>

								<?php if ($store['store_id'] == $store_id) { ?>

								<option value="<?php echo $store['store_id']; ?>" selected=""><?php echo $store['name']; ?></option>

								<?php } else { ?>

								<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>

								<?php } ?>

							<?php } ?>

						</select>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_customer; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="customer" value="<?php echo $customer; ?>" id="order-customer" autocomplete="off" class="form-control">

						<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

						<input type="hidden" name="customer_group_id" value="<?php echo $customer_group_id; ?>">

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_customer_group; ?></label>
					<div class="control-field col-sm-4">

						<select id="customer_group_id" class="form-control"<?php echo $customer_id ? ' disabled=""' :''; ?>>

							<?php foreach ($customer_groups as $customer_group) { ?>

								<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>

								<option value="<?php echo $customer_group['customer_group_id']; ?>" selected=""><?php echo $customer_group['name']; ?></option>

								<?php } else { ?>

								<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>

								<?php } ?>

							<?php } ?>

						</select>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_firstname; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="firstname" value="<?php echo $firstname; ?>" class="form-control">

						<?php if ($error_firstname) { ?>

							<div class="help-block error"><?php echo $error_firstname; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_lastname; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control">

						<?php if ($error_lastname) { ?>

							<div class="help-block error"><?php echo $error_lastname; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_email; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">

						<?php if ($error_email) { ?>

							<div class="help-block error"><?php echo $error_email; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_telephone; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">

						<?php if ($error_telephone) { ?>

							<div class="help-block error"><?php echo $error_telephone; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_fax; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="fax" value="<?php echo $fax; ?>" class="form-control">

					</div>

				</div>

			</div>

			<div id="tab-payment" class="tab-pane">

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_address; ?></label>
					<div class="control-field col-sm-4">

						<select name="payment_address" class="form-control">

							<option value="0" selected=""><?php echo $text_none; ?></option>

							<?php foreach ($addresses as $address) { ?>

								<option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>

							<?php } ?>

						</select>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_firstname; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" class="form-control">

						<?php if ($error_payment_firstname) { ?>

							<div class="help-block error"><?php echo $error_payment_firstname; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_lastname; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" class="form-control">

						<?php if ($error_payment_lastname) { ?>

							<div class="help-block error"><?php echo $error_payment_lastname; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_company; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_company" value="<?php echo $payment_company; ?>" class="form-control">

					</div>

				</div>

				<div class="form-group" id="company-id-display">

					<label class="control-label col-sm-2"><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_company_id" value="<?php echo $payment_company_id; ?>" class="form-control">

					</div>

				</div>

				<div class="form-group" id="tax-id-display">

					<label class="control-label col-sm-2"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_tax_id" value="<?php echo $payment_tax_id; ?>" class="form-control">

						<?php if ($error_payment_tax_id) { ?>

						<div class="help-block error"><?php echo $error_payment_tax_id; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_address_1; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" class="form-control">

						<?php if ($error_payment_address_1) { ?>

							<div class="help-block error"><?php echo $error_payment_address_1; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_address_2; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" class="form-control">

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_city; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_city" value="<?php echo $payment_city; ?>" class="form-control">

						<?php if ($error_payment_city) { ?>

							<div class="help-block error"><?php echo $error_payment_city; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" class="form-control">

						<?php if ($error_payment_postcode) { ?>

							<div class="help-block error"><?php echo $error_payment_postcode; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_country; ?></label>
					<div class="control-field col-sm-4">

						<select name="payment_country_id" data-provide="countries" data-target="payment" data-selected="<?php echo $payment_zone_id; ?>" class="form-control">

							<option value=""><?php echo $text_select; ?></option>

							<?php foreach ($countries as $country) { ?>

								<?php if ($country['country_id'] == $payment_country_id) { ?>

								<option value="<?php echo $country['country_id']; ?>" selected=""><?php echo $country['name']; ?></option>

								<?php } else { ?>

								<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>

								<?php } ?>

							<?php } ?>

						</select>

						<?php if ($error_payment_country) { ?>

							<div class="help-block error"><?php echo $error_payment_country; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_zone; ?></label>
					<div class="control-field col-sm-4">

						<select name="payment_zone_id" class="form-control"></select>

						<?php if ($error_payment_zone) { ?>

							<div class="help-block error"><?php echo $error_payment_zone; ?></div>

						<?php } ?>

					</div>

				</div>

			</div>

			<div id="tab-shipping" class="tab-pane">

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_address; ?></label>
					<div class="control-field col-sm-4">

						<select name="shipping_address" class="form-control">

							<option value="0" selected=""><?php echo $text_none; ?></option>

							<?php foreach ($addresses as $address) { ?>

								<option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>

							<?php } ?>

						</select>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_firstname; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" class="form-control">

						<?php if ($error_shipping_firstname) { ?>

							<div class="help-block error"><?php echo $error_shipping_firstname; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_lastname; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" class="form-control">

						<?php if ($error_shipping_lastname) { ?>

							<div class="help-block error"><?php echo $error_shipping_lastname; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_company; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" class="form-control">

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_address_1; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" class="form-control">

						<?php if ($error_shipping_address_1) { ?>

							<div class="help-block error"><?php echo $error_shipping_address_1; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><?php echo $entry_address_2; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" class="form-control">

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_city; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" class="form-control">

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
					<div class="control-field col-sm-4">

						<input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" class="form-control">

						<?php if ($error_shipping_postcode) { ?>

							<div class="help-block error"><?php echo $error_shipping_postcode; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_country; ?></label>
					<div class="control-field col-sm-4">

						<select name="shipping_country_id" data-provide="countries" data-target="shipping" data-selected="<?php echo $shipping_zone_id; ?>" class="form-control">

							<option value=""><?php echo $text_select; ?></option>

							<?php foreach ($countries as $country) { ?>

								<?php if ($country['country_id'] == $shipping_country_id) { ?>

								<option value="<?php echo $country['country_id']; ?>" selected=""><?php echo $country['name']; ?></option>

								<?php } else { ?>

								<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>

								<?php } ?>

							<?php } ?>

						</select>

						<?php if ($error_shipping_country) { ?>

							<div class="help-block error"><?php echo $error_shipping_country; ?></div>

						<?php } ?>

					</div>

				</div>

				<div class="form-group">

					<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_zone; ?></label>
					<div class="control-field col-sm-4">

						<select name="shipping_zone_id" class="form-control"></select>

						<?php if ($error_shipping_zone) { ?>

							<div class="help-block error"><?php echo $error_shipping_zone; ?></div>

						<?php } ?>

					</div>

				</div>

			</div>

			<div id="tab-product" class="tab-pane">

				<table class="table table-bordered table-striped table-hover">

					<thead>

						<tr>

							<th width="40"></th>

							<th><?php echo $column_product; ?></th>

							<th><?php echo $column_model; ?></th>

							<th class="text-right"><?php echo $column_quantity; ?></th>

							<th class="text-right"><?php echo $column_price; ?></th>

							<th class="text-right"><?php echo $column_total; ?></th>

						</tr>

					</thead>

					<?php $product_row = 0; ?>

					<?php $option_row = 0; ?>

					<?php $download_row = 0; ?>

					<tbody id="product">

						<?php if ($order_products) { ?>

						<?php foreach ($order_products as $order_product) { ?>

						<tr id="product-row<?php echo $product_row; ?>">

							<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-update').trigger('click');"><i class="fa fa-trash "></i></a></td>

							<td><?php echo $order_product['name']; ?><br>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_product_id]" value="<?php echo $order_product['order_product_id']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][name]" value="<?php echo $order_product['name']; ?>">

								<?php foreach ($order_product['option'] as $option) { ?>

								<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][order_option_id]" value="<?php echo $option['order_option_id']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>">

								<?php $option_row++; ?>

								<?php } ?>

								<?php foreach ($order_product['download'] as $download) { ?>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][order_download_id]" value="<?php echo $download['order_download_id']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][name]" value="<?php echo $download['name']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][filename]" value="<?php echo $download['filename']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][mask]" value="<?php echo $download['mask']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_download][<?php echo $download_row; ?>][remaining]" value="<?php echo $download['remaining']; ?>">

								<?php $download_row++; ?>

								<?php } ?></td>

							<td><?php echo $order_product['model']; ?>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][model]" value="<?php echo $order_product['model']; ?>"></td>

							<td class="text-right"><?php echo $order_product['quantity']; ?>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>"></td>

							<td class="text-right"><?php echo $order_product['price']; ?>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>"></td>

							<td class="text-right"><?php echo $order_product['total']; ?>

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][total]" value="<?php echo $order_product['total']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $order_product['tax']; ?>">

								<input type="hidden" name="order_product[<?php echo $product_row; ?>][reward]" value="<?php echo $order_product['reward']; ?>"></td>

						</tr>

						<?php $product_row++; ?>

						<?php } ?>

						<?php } else { ?>

						<tr>

							<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>

						</tr>

						<?php } ?>

					</tbody>

				</table>

				<fieldset>

					<legend><?php echo $text_product; ?></legend>

					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_product; ?></label>
						<div class="control-field col-sm-4">

							<input type="text" name="product" value="" id="order-product" class="form-control" autocomplete="off"><input type="hidden" name="product_id" value="" class="form-control">

						</div>

					</div>

					<div id="option"></div>

					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_quantity; ?></label>

						<div class="control-field col-sm-4">

							<input type="text" name="quantity" value="1" class="form-control">

						</div>

					</div>

					<div class="form-group">

						<div class="control-field col-sm-4 col-sm-offset-2">

							<button type="button" id="button-product" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_product; ?></button>

						</div>

					</div>

				</fieldset>

			</div>
 
			<div id="tab-total" class="tab-pane">

				<table class="table table-bordered table-striped table-hover">

					<thead>

						<tr>

							<th><?php echo $column_product; ?></th>

							<th><?php echo $column_model; ?></th>

							<th class="text-right"><?php echo $column_quantity; ?></th>

							<th class="text-right"><?php echo $column_price; ?></th>

							<th class="text-right"><?php echo $column_total; ?></th>

						</tr>

					</thead>

					<tbody id="total">

						<?php $total_row = 0; ?>

						<?php if ($order_products || $order_totals) { ?>

						<?php foreach ($order_products as $order_product) { ?>

						<tr>

							<td><?php echo $order_product['name']; ?>

							<?php foreach ($order_product['option'] as $option) { ?>

								<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>

							<?php } ?></td>

							<td><?php echo $order_product['model']; ?></td>

							<td class="text-right"><?php echo $order_product['quantity']; ?></td>

							<td class="text-right"><?php echo $order_product['price']; ?></td>

							<td class="text-right"><?php echo $order_product['total']; ?></td>

						</tr>

						<?php } ?>

						<?php foreach ($order_totals as $order_total) { ?>

						<tr id="total-row<?php echo $total_row; ?>">

							<td class="text-right" colspan="4"><?php echo $order_total['title']; ?>:

								<input type="hidden" name="order_total[<?php echo $total_row; ?>][order_total_id]" value="<?php echo $order_total['order_total_id']; ?>">

								<input type="hidden" name="order_total[<?php echo $total_row; ?>][code]" value="<?php echo $order_total['code']; ?>">

								<input type="hidden" name="order_total[<?php echo $total_row; ?>][title]" value="<?php echo $order_total['title']; ?>">

								<input type="hidden" name="order_total[<?php echo $total_row; ?>][text]" value="<?php echo $order_total['text']; ?>">

								<input type="hidden" name="order_total[<?php echo $total_row; ?>][value]" value="<?php echo $order_total['value']; ?>">

								<input type="hidden" name="order_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $order_total['sort_order']; ?>"></td>

							<td class="text-right"><?php echo $order_total['value']; ?></td>

						</tr>

						<?php $total_row++; ?>

						<?php } ?>

						<?php } else { ?>

						<tr>

							<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>

						</tr>

						<?php } ?>

					</tbody>

				</table>

				<fieldset>

					<legend><?php echo $text_order; ?></legend>

					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_shipping; ?></label>
						<div class="control-field col-sm-4">

							<select id="shipping" name="shipping" class="form-control">

								<option value=""><?php echo $text_select; ?></option>

								<?php if ($shipping_code) { ?>

								<option value="<?php echo $shipping_code; ?>" selected=""><?php echo $shipping_method; ?></option>

								<?php } ?>

							</select>

							<input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>">

							<input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>">

							<?php if ($error_shipping_method) { ?>

								<div class="help-block error"><?php echo $error_shipping_method; ?></div>

							<?php } ?>

						</div>

					</div>

					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_payment; ?></label>
						<div class="control-field col-sm-4">

							<select id="payment" name="payment" class="form-control">

								<option value=""><?php echo $text_select; ?></option>

								<?php if ($payment_code) { ?>

								<option value="<?php echo $payment_code; ?>" selected=""><?php echo $payment_method; ?></option>

								<?php } ?>

							</select>

							<input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>">

							<input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>">

							<?php if ($error_payment_method) { ?>

								<div class="help-block error"><?php echo $error_payment_method; ?></div>

							<?php } ?>

						</div>

					</div>
					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_order_status; ?></label>
						<div class="control-field col-sm-4">

							<select name="invoice_status_id" class="form-control">

								<?php foreach ($order_statuses as $order_status) { ?>

								<?php if ($order_status['invoice_status_id'] == $invoice_status_id) { ?>

								<option value="<?php echo $order_status['invoice_status_id']; ?>" selected=""><?php echo $order_status['name']; ?></option>

								<?php } else { ?>

								<option value="<?php echo $order_status['invoice_status_id']; ?>"><?php echo $order_status['name']; ?></option>

								<?php } ?>

								<?php } ?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_comment; ?></label>
						<div class="control-field col-sm-4">

							<textarea name="comment" class="form-control" rows="3"><?php echo $comment; ?></textarea>

						</div>

					</div>

					<div class="form-group">

						<label class="control-label col-sm-2"><?php echo $entry_affiliate; ?></label>
						<div class="control-field col-sm-4">

							<input type="text" name="affiliate" value="<?php echo $affiliate; ?>" class="form-control" autocomplete="off"><input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>" class="form-control">

						</div>

					</div>

					<div class="form-group">

						<div class="control-field col-sm-4 col-sm-offset-2">

							<button type="button" id="button-update" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_update_total; ?></button>

						</div>

					</div>

				</fieldset>

			</div>

		</div>

		</form>

	</div>

</div>

<input type="hidden" id="text_none" value="<?php echo $text_none; ?>">

<input type="hidden" id="text_select" value="<?php echo $text_select; ?>">

<input type="hidden" id="button_upload" value="<?php echo $button_upload; ?>">

<input type="hidden" id="store_url" value="<?php echo $store_url; ?>">

<input type="hidden" id="button_remove" value="<?php echo $button_remove; ?>">

<input type="hidden" id="text_no_results" value="<?php echo $text_no_results; ?>">

<script>
function validateForm() {
	// shipping
	var elem = document.getElementById("shipping") ;
   
	if (elem.options[elem.selectedIndex].value =="") {
		alert("Shipping Name must be filled out");
		return false;
	}
	
	// payment
	
	var elem = document.getElementById("payment");
	
	if (elem.options[elem.selectedIndex].value =="") {
		alert("Payment Name must be filled out");
		return false;
	}
}
$('#customer_group_id').change(function(){

	$('input[name="customer_group_id"]').val(this.value);

	

	var customer_group = [];

	

<?php foreach ($customer_groups as $customer_group) { ?>

	var i=<?php echo $customer_group['customer_group_id']; ?>;

	customer_group[i]=[];

	customer_group[i]['company_id_display']='<?php echo $customer_group['company_id_display']; ?>';

	customer_group[i]['company_id_required']='<?php echo $customer_group['company_id_required']; ?>';

	customer_group[i]['tax_id_display']='<?php echo $customer_group['tax_id_display']; ?>';

	customer_group[i]['tax_id_required']='<?php echo $customer_group['tax_id_required']; ?>';

<?php } ?>	



	if(customer_group[this.value]){

		if(customer_group[this.value]['company_id_display']==1){

			$('#company-id-display').show();

		}else{

			$('#company-id-display').hide();

		}

		if(customer_group[this.value]['company_id_required']==1){

			$('#company-id-required').show();

		}else{

			$('#company-id-required').hide();

		}

		if(customer_group[this.value]['tax_id_display']==1){

			$('#tax-id-display').show();

		}else{

			$('#tax-id-display').hide();

		}

		if(customer_group[this.value]['tax_id_required']==1){

			$('#tax-id-required').show();

		}else{

			$('#tax-id-required').hide();

		}	

	}

}).change();

</script>

<?php echo $footer; ?>