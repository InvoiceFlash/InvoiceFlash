<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
	<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo ($draft_id) ? sprintf($text_heading_draft_no, $draft_id) : $heading_title; ?></div>
	<div class="pull-right">
		<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
		<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" onsubmit="return validateForm();" class="form-inline" enctype="multipart/form-data" id="form">
			<div class="card" id="tab-customer" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_customer; ?>
					<button class="btn btn-warning pull-right" type="button" title="<?php echo $title_pending_deliveries; ?>" style="margin-right:4px; margin-left:10px;" onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('OrderSearchModal')).show();"><i class="fa fa-list-alt"></i> <span class="hidden-xs"><?php echo $text_pending_deliveries; ?></span></button>
					<input type="hidden" name="comment" value="<?php echo $comment; ?>">
				</div>
				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_store; ?></label>
							<div class="control-field col-sm-8">
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
						<div class="form-group col-sm-8">
							<label class="control-label col-sm-2"><?php echo $entry_customer; ?></label>
							<div class="control-field input-group col-sm-10">
								<input type="text" name="company" value="<?php echo $company; ?>" id="order-customer" autocomplete="off" class="form-control">
								<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>">
								<input type="hidden" name="customer_group_id" value="<?php echo $customer_group_id; ?>">
								<div class="input-group-append"><button class="btn btn-default" type="button" id="searchCustomer" title="<?php echo $title_search_customer; ?>"><i class="fa fa-search"></i></button></div>
								<div class="input-group-append"><button class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#CustomerModal"><i class="fa fa-eye"></i></button></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_shipping; ?></label>
							<div class="control-field col-sm-8">
								<select id="shipping" name="shipping" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($shippings as $shipping): ?>
										<option value="<?php echo $shipping['shipping_id'] ?>" <?php echo ($shipping['shipping_id'] == $shipping_code ? 'selected' : '') ?>><?php echo $shipping['name'] ?></option>
									<?php endforeach ?>
								</select>
								<input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>">
								<input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>">
								<?php if ($error_shipping_method) { ?>
									<div class="help-block error"><?php echo $error_shipping_method; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_payment; ?></label>
							<div class="control-field col-sm-8">
								<select id="payment" name="payment" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($payments as $payment): ?>
										<option value="<?php echo $payment['payment_id'] ?>" <?php echo ($payment['payment_id'] == $payment_code ? 'selected' : '') ?>><?php echo $payment['name'] ?></option>
									<?php endforeach ?>
								</select>
								<input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>">
								<input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>">
								<?php if ($error_payment_method) { ?>
									<div class="help-block error"><?php echo $error_payment_method; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_simplified; ?></label>
							<div class="control-field col-sm-8 d-flex align-items-center" style="min-height:38px; gap:15px;">
								<label class="radio-inline m-0 p-0" style="padding-left:20px;"><input type="radio" name="simplified" value="0" <?php echo (!$simplified) ? 'checked=""' : ''; ?>> <?php echo $text_normal; ?></label>
								<label class="radio-inline m-0 p-0" style="padding-left:20px;"><input type="radio" name="simplified" value="1" <?php echo ($simplified) ? 'checked=""' : ''; ?>> <?php echo $text_simplified; ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="tab-product" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_product; ?>
					<button class="btn btn-info pull-right" type="button" id="addProduct"><i class="fa fa-plus-circle"></i> <span class="hidden-xs"><?php echo $button_add_product; ?></span></button>
				</div>
				<div class="card-body">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th style="width:50%;"><?php echo $column_product; ?></th>
								<th class="text-right" style="width:80px;"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_base; ?></th>
						</tr>
						</thead>
						<?php $product_row = 0; ?>
						<?php $option_row = 0; ?>
						<tbody id="product">
							<?php if ($draft_products) { ?>
							<?php foreach ($draft_products as $draft_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-draft-product').click();"><i class="fa fa-trash"></i></a></td>
								<td>
									<input type="text" class="form-control draft-name" name="draft_product[<?php echo $product_row; ?>][name]" value="<?php echo htmlspecialchars((string)$draft_product['name'], ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_product_id]" value="<?php echo $draft_product['draft_product_id']; ?>">
									<input type="hidden" name="draft_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $draft_product['product_id']; ?>">
									<?php foreach ($draft_product['option'] as $option) { ?>
										<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
										<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_option][<?php echo $option_row; ?>][draft_option_id]" value="<?php echo $option['draft_option_id']; ?>">
										<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>">
										<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>">
										<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>">
										<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>">
										<input type="hidden" name="draft_product[<?php echo $product_row; ?>][draft_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>">
									<?php $option_row++; ?>
									<?php } ?>
									<input type="hidden" name="draft_product[<?php echo $product_row; ?>][model]" value="<?php echo $draft_product['model']; ?>">
								</td>
								<td class="text-right"><input type="text" class="form-control text-right draft-qty" name="draft_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $draft_product['quantity']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right draft-price" data-catalog-price="<?php echo $draft_product['catalog_price_raw']; ?>" name="draft_product[<?php echo $product_row; ?>][price]" value="<?php echo $draft_product['price_raw']; ?>"></td>
								<td class="text-right"><?php echo $draft_product['total']; ?>
									<input type="hidden" name="draft_product[<?php echo $product_row; ?>][total]" value="<?php echo $draft_product['total']; ?>">
									<input type="hidden" name="draft_product[<?php echo $product_row; ?>][tax]" value="<?php echo $draft_product['tax']; ?>"></td>
							</tr>
							<?php $product_row++; ?>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tbody id="total">
							<?php $total_row = 0; ?>
							<?php if ($draft_totals) { ?>
							<?php foreach ($draft_totals as $draft_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="3"><?php echo $draft_total['title']; ?>:
									<input type="hidden" name="draft_total[<?php echo $total_row; ?>][draft_total_id]" value="<?php echo $draft_total['draft_total_id']; ?>">
									<input type="hidden" name="draft_total[<?php echo $total_row; ?>][code]" value="<?php echo $draft_total['code']; ?>">
									<input type="hidden" name="draft_total[<?php echo $total_row; ?>][title]" value="<?php echo $draft_total['title']; ?>">
									<input type="hidden" name="draft_total[<?php echo $total_row; ?>][text]" value="<?php echo $draft_total['text']; ?>">
									<input type="hidden" name="draft_total[<?php echo $total_row; ?>][value]" value="<?php echo $draft_total['value']; ?>">
									<input type="hidden" name="draft_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $draft_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $draft_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal Albaranes Pendientes -->
			<div class="modal" tabindex="-1" role="dialog" id="OrderSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $text_pending_deliveries; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm-3">
									<label class="control-label"><?php echo $entry_delivery_no; ?></label>
									<input type="text" id="os-order-id" class="form-control" placeholder="<?php echo $entry_delivery_no; ?>">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label"><?php echo $entry_customer_filter; ?></label>
									<input type="text" id="os-company" class="form-control" placeholder="<?php echo $entry_company_name; ?>">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="os-search" class="btn btn-primary"><?php echo $button_update; ?></button>
								</div>
							</div>
							<div id="os-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th><?php echo $column_delivery_no; ?></th>
											<th><?php echo $entry_customer_filter; ?></th>
											<th><?php echo $column_delivery_date; ?></th>
											<th class="text-right"><?php echo $column_total; ?></th>
											<th><?php echo $column_status; ?></th>
										</tr>
									</thead>
									<tbody id="os-results">
										<tr><td colspan="5" class="text-center"><?php echo $text_use_filters_deliveries; ?></td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Albaranes Pendientes -->
			<!-- Modal Buscar Cliente -->
			<div class="modal" tabindex="-1" role="dialog" id="CustomerSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $title_search_customer; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm">
									<label class="control-label"><?php echo $entry_company_name; ?></label>
									<input type="text" id="cs-company" class="form-control" placeholder="<?php echo $entry_company_name; ?>">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label"><?php echo $entry_contact_name; ?></label>
									<input type="text" id="cs-contact" class="form-control" placeholder="<?php echo $entry_contact_name; ?>">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="cs-search" class="btn btn-primary"><?php echo $button_update; ?></button>
								</div>
							</div>
							<div id="cs-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th><?php echo $column_company; ?></th>
											<th><?php echo $column_group; ?></th>
											<th><?php echo $column_email; ?></th>
											<th><?php echo $column_telephone; ?></th>
										</tr>
									</thead>
									<tbody id="cs-results">
										<tr><td colspan="4" class="text-center"><?php echo $text_press_update_customers; ?></td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Buscar Cliente -->
			<!-- Modal Buscar Producto -->
			<div class="modal" tabindex="-1" role="dialog" id="ProductSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $text_search_product_title; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm-3">
									<label class="control-label"><?php echo $entry_sku_search; ?></label>
									<input type="text" id="ps-sku" class="form-control" placeholder="SKU">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label"><?php echo $entry_description; ?></label>
									<input type="text" id="ps-name" class="form-control" placeholder="<?php echo $entry_description; ?>">
								</div>
								<div class="col-12 col-sm-3">
									<label class="control-label"><?php echo $column_model; ?></label>
									<input type="text" id="ps-model" class="form-control" placeholder="<?php echo $column_model; ?>">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="ps-search" class="btn btn-primary"><?php echo $button_update; ?></button>
								</div>
							</div>
							<div id="ps-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>SKU</th>
											<th><?php echo $entry_description; ?></th>
											<th><?php echo $column_model; ?></th>
											<th class="text-right"><?php echo $column_price; ?></th>
											<th class="text-right">Stock</th>
										</tr>
									</thead>
									<tbody id="ps-results">
										<tr><td colspan="5" class="text-center"><?php echo $text_use_filters_products; ?></td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Buscar Producto -->
			<!-- Modal Product -->
			<div class="modal" tab-index="-1" role="dialog" id="ProductModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="pm-product-name"><?php echo $text_product; ?></h5>
							<button class="close" data-bs-dismiss="modal" arial-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<input type="hidden" name="product_id" id="product_id" value="0">
							<input type="hidden" name="product" id="draft-product" value="">
							<div class="form-horizontal">
								<div id="option"></div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_quantity; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" name="quantity" id="pm-quantity" value="1" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_price; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" name="price_override" id="price_override" value="" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_discount; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" name="discount" id="pm-discount" value="" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_tax_rate; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" id="pm-tax-rate" value="" class="form-control" readonly>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-draft-product" class="btn btn-info pull-right">
									<i class="fa fa-plus-circle"></i> <span class="hidden-xs"><?php echo $button_add_product; ?></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Product -->
			<!-- Modal Customer -->
			<div class="modal" tabindex="-1" role="dialog" id="CustomerModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $tab_customer; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<ul class="nav nav-tabs">
								<li class="nav-item"><a href="#modal-tab-customer" class="nav-link" data-bs-toggle="tab">Customer</a></li>
								<li class="nav-item"><a href="#tab-payment" class="nav-link" data-bs-toggle="tab">Payment</a></li>
								<li class="nav-item"><a href="#tab-shipping" class="nav-link" data-bs-toggle="tab">Shipping</a></li>
							</ul>
							<div class="tab-content mt-2">
								<div class="tab-pane" id="modal-tab-customer">
									<div class="form-horizontal">
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_customer_group; ?></label>
											<div class="control-field col-sm-8">
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
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_email; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
												<?php if ($error_email) { ?>
													<div class="help-block error"><?php echo $error_email; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_telephone; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
												<?php if ($error_telephone) { ?>
													<div class="help-block error"><?php echo $error_telephone; ?></div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab-payment">
									<div class="form-horizontal">
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_address; ?></label>
											<div class="control-field col-sm-8">
												<select name="payment_address" class="form-control">
													<option value="0" selected=""><?php echo $text_none; ?></option>
													<?php foreach ($addresses as $address) { ?>
														<option value="<?php echo $address['address_id']; ?>"><?php echo $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_company; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_company" value="<?php echo $payment_company; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_address_1; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" class="form-control">
												<?php if ($error_payment_address_1) { ?>
													<div class="help-block error"><?php echo $error_payment_address_1; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_address_2; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_city; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_city" value="<?php echo $payment_city; ?>" class="form-control">
												<?php if ($error_payment_city) { ?>
													<div class="help-block error"><?php echo $error_payment_city; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" class="form-control">
												<?php if ($error_payment_postcode) { ?>
													<div class="help-block error"><?php echo $error_payment_postcode; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_country; ?></label>
											<div class="control-field col-sm-8">
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
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_zone; ?></label>
											<div class="control-field col-sm-8">
												<select name="payment_zone_id" class="form-control"></select>
												<?php if ($error_payment_zone) { ?>
													<div class="help-block error"><?php echo $error_payment_zone; ?></div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab-shipping">
									<div class="form-horizontal">
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_address; ?></label>
											<div class="control-field col-sm-8">
												<select name="shipping_address" class="form-control">
													<option value="0" selected=""><?php echo $text_none; ?></option>
													<?php foreach ($addresses as $address) { ?>
														<option value="<?php echo $address['address_id']; ?>"><?php echo $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_company; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_address_1; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" class="form-control">
												<?php if ($error_shipping_address_1) { ?>
													<div class="help-block error"><?php echo $error_shipping_address_1; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_address_2; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_city; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" class="form-control">
												<?php if ($error_shipping_postcode) { ?>
													<div class="help-block error"><?php echo $error_shipping_postcode; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_country; ?></label>
											<div class="control-field col-sm-8">
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
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_zone; ?></label>
											<div class="control-field col-sm-8">
												<select name="shipping_zone_id" class="form-control"></select>
												<?php if ($error_shipping_zone) { ?>
													<div class="help-block error"><?php echo $error_shipping_zone; ?></div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-bs-dismiss="modal">Save</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Customer -->
		</form>
	</div>
</div>
<input type="hidden" id="text_none" value="<?php echo $text_none; ?>">
<input type="hidden" id="text_select" value="<?php echo $text_select; ?>">
<input type="hidden" id="button_upload" value="<?php echo $button_upload; ?>">
<input type="hidden" id="store_url" value="<?php echo $store_url; ?>">
<input type="hidden" id="button_remove" value="<?php echo $button_remove; ?>">
<input type="hidden" id="text_no_results" value="<?php echo $text_no_results; ?>">
<input type="hidden" id="button_update" value="<?php echo $button_update; ?>">
<input type="hidden" id="text_searching" value="<?php echo $text_searching; ?>">
<input type="hidden" id="text_no_customers_found" value="<?php echo $text_no_customers_found; ?>">
<input type="hidden" id="error_search_customers" value="<?php echo $error_search_customers; ?>">
<input type="hidden" id="text_no_deliveries_found" value="<?php echo $text_no_deliveries_found; ?>">
<input type="hidden" id="error_search_deliveries" value="<?php echo $error_search_deliveries; ?>">
<input type="hidden" id="text_no_products_found" value="<?php echo $text_no_products_found; ?>">
<input type="hidden" id="error_search_products" value="<?php echo $error_search_products; ?>">
<input type="hidden" id="error_select_customer" value="<?php echo $error_select_customer; ?>">
<input type="hidden" id="error_select_product" value="<?php echo $error_select_product; ?>">
<input type="hidden" id="error_select_shipping" value="<?php echo $error_select_shipping; ?>">
<input type="hidden" id="error_select_payment" value="<?php echo $error_select_payment; ?>">
<input type="hidden" id="text_select_customer_first" value="<?php echo $text_select_customer_first; ?>">
<input type="hidden" id="text_use_filters_deliveries" value="<?php echo $text_use_filters_deliveries; ?>">
<input type="hidden" id="text_use_filters_products" value="<?php echo $text_use_filters_products; ?>">
<input type="hidden" id="text_press_update_customers" value="<?php echo $text_press_update_customers; ?>">
<script>
function validateForm(){

	// Customer
	var customer = $('#customer_id');
	if (customer.val() == 0) {
		alert($('#error_select_customer').val());
		return false;
	}

	// Products
	if (!$("#product-row0").length > 0) {
		alert($('#error_select_product').val());
		return false;
	}

	// Shipping
	var ship = $('#shipping').val();
	if (ship=="") {
		alert($('#error_select_shipping').val());
		return false;
	}

	// Payment
	var pay = $('#payment').val();
	if (pay==""){
		alert($('#error_select_payment').val());
		return false;
	}

}
</script>
<script>
$('#ProductModal').on('hidden.bs.modal', function () {
	$(this).find('#draft-product').val('');
	$(this).find('#product_id').val(0);
	$(this).find('#price_override').val('');
	$(this).find('#pm-quantity').val(1);
	$(this).find('#pm-discount').val('');
	$(this).find('#pm-tax-rate').val('');
	$(this).find('#option').html('');
});

var csCustomers = [];

$('#searchCustomer').click(function(e) {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('CustomerSearchModal')).show();
});

$('#CustomerSearchModal').on('shown.bs.modal', function() {
	$('#cs-company').trigger('focus');
});

function csDoSearch() {
	var btn = $('#cs-search');
	var searching = $('#text_searching').val();
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + searching);
	$('#cs-results').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> ' + searching + '</td></tr>');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('sale/customer/searchCustomers', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_company: $('#cs-company').val(), filter_contact: $('#cs-contact').val() },
		dataType: 'json',
		success: function(json) {
			if (json.warning) {
				$('#cs-warning').text(json.warning).show();
				$('#cs-results').html('<tr><td colspan="4" class="text-center">' + json.warning + '</td></tr>');
				csCustomers = [];
				return;
			}
			$('#cs-warning').hide();
			csCustomers = json;
			if (!json.length) {
				$('#cs-results').html('<tr><td colspan="4" class="text-center">' + $('#text_no_customers_found').val() + '</td></tr>');
				return;
			}
			var html = '';
			for (var i = 0; i < json.length; i++) {
				html += '<tr data-idx="' + i + '" style="cursor:pointer;">';
				html += '<td>' + (json[i].company || '') + '</td>';
				html += '<td>' + (json[i].customer_group || '') + '</td>';
				html += '<td>' + (json[i].email || '') + '</td>';
				html += '<td>' + (json[i].telephone || '') + '</td>';
				html += '</tr>';
			}
			$('#cs-results').html(html);
		},
		error: function() {
			$('#cs-warning').text($('#error_search_customers').val()).show();
			$('#cs-results').html('<tr><td colspan="4" class="text-center">' + $('#error_search_customers').val() + '</td></tr>');
			csCustomers = [];
		},
		complete: function() {
			btn.prop('disabled', false).html($('#button_update').val());
		}
	});
}

$('#cs-search').click(csDoSearch);

$('#cs-company, #cs-contact').on('keypress', function(e) {
	if (e.which == 13) csDoSearch();
});

$(document).on('dblclick', '#cs-results tr[data-idx]', function() {
	var idx = parseInt($(this).data('idx'));
	var c = csCustomers[idx];

	$('input[name="company"]').val(c.company);
	$('#customer_id').val(c.customer_id);
	$('input[name="customer_group_id"]').val(c.customer_group_id);
	$('select#customer_group_id').val(c.customer_group_id).change();
	$('input[name="email"]').val(c.email);
	$('input[name="telephone"]').val(c.telephone);

	var html = '<option value="0">&mdash;</option>';
	for (var i in c.address) {
		html += '<option value="' + c.address[i].address_id + '">' + c.address[i].firstname + ' ' + c.address[i].lastname + ',' + c.address[i].address_1 + ',' + c.address[i].city + ',' + c.address[i].country + '</option>';
	}
	$('select[name="shipping_address"]').html(html);
	$('select[name="payment_address"]').html(html);
	$('select[name="shipping_address"] option:nth-child(2)').attr('selected', true).change();
	$('select[name="payment_address"] option:nth-child(2)').attr('selected', true).change();

	bootstrap.Modal.getInstance(document.getElementById('CustomerSearchModal')).hide();
});

$('#CustomerSearchModal').on('hidden.bs.modal', function() {
	$('#cs-company').val('');
	$('#cs-contact').val('');
	$('#cs-results').html('<tr><td colspan="4" class="text-center">' + $('#text_press_update_customers').val() + '</td></tr>');
	$('#cs-warning').hide();
	csCustomers = [];
});

function osDoSearch() {
	var btn = $('#os-search');
	var searching = $('#text_searching').val();
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + searching);
	$('#os-results').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> ' + searching + '</td></tr>');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('sale/draft/searchOrders', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_order_id: $('#os-order-id').val(), filter_company: $('#os-company').val() },
		dataType: 'json',
		success: function(json) {
			$('#os-warning').hide();
			if (!json || !json.length) {
				$('#os-results').html('<tr><td colspan="5" class="text-center">' + $('#text_no_deliveries_found').val() + '</td></tr>');
				return;
			}
			var html = '';
			for (var i = 0; i < json.length; i++) {
				html += '<tr data-order-id="' + json[i].order_id + '" style="cursor:pointer;">';
				html += '<td>' + json[i].order_id + '</td>';
				html += '<td>' + json[i].company + '</td>';
				html += '<td>' + json[i].date_added + '</td>';
				html += '<td class="text-right">' + json[i].total + '</td>';
				html += '<td>' + (json[i].status || '') + '</td>';
				html += '</tr>';
			}
			$('#os-results').html(html);
		},
		error: function() {
			$('#os-warning').text($('#error_search_deliveries').val()).show();
			$('#os-results').html('<tr><td colspan="5" class="text-center">' + $('#error_search_deliveries').val() + '</td></tr>');
		},
		complete: function() {
			btn.prop('disabled', false).html($('#button_update').val());
		}
	});
}

$('#os-search').click(osDoSearch);

$('#os-order-id, #os-company').on('keypress', function(e) {
	if (e.which == 13) osDoSearch();
});

$(document).on('dblclick', '#os-results tr[data-order-id]', function() {
	var orderId = $(this).data('order-id');
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('sale/draft/getOrderData', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { order_id: orderId },
		dataType: 'json',
		success: function(json) {
			if (json.error) {
				alert(json.error);
				return;
			}

			// Cliente
			$('#customer_id').val(json.customer_id);
			$('input[name="customer_group_id"]').val(json.customer_group_id);
			$('#order-customer').val(json.company);

			// Envío y pago (dispara los handlers de common.js)
			$('#shipping').val(json.shipping_code).trigger('change');
			$('#payment').val(json.payment_code).trigger('change');

			// Productos: se insertan como filas provisionales y se recalculan
			// precios/impuestos/totales a través del flujo existente de checkDraft.
			var html = '';
			if (json.products && json.products.length) {
				for (var i = 0; i < json.products.length; i++) {
					var p = json.products[i];
					html += '<tr id="product-row' + i + '">';
					html += '<input type="hidden" name="draft_product[' + i + '][draft_product_id]" value="">';
					html += '<input type="hidden" name="draft_product[' + i + '][product_id]" value="' + p.product_id + '">';
					html += '<input type="hidden" name="draft_product[' + i + '][quantity]" value="' + p.quantity + '">';
					html += '<input type="hidden" name="draft_product[' + i + '][price]" value="' + p.price + '">';
					if (p.option && p.option.length) {
						for (var j = 0; j < p.option.length; j++) {
							var opt = p.option[j];
							html += '<input type="hidden" name="draft_product[' + i + '][draft_option][' + j + '][draft_option_id]" value="">';
							html += '<input type="hidden" name="draft_product[' + i + '][draft_option][' + j + '][product_option_id]" value="' + opt.product_option_id + '">';
							html += '<input type="hidden" name="draft_product[' + i + '][draft_option][' + j + '][product_option_value_id]" value="' + opt.product_option_value_id + '">';
							html += '<input type="hidden" name="draft_product[' + i + '][draft_option][' + j + '][type]" value="' + opt.type + '">';
						}
					}
					html += '</tr>';
				}
			}
			$('#product').html(html);
			$('#product_id').val(0);

			bootstrap.Modal.getInstance(document.getElementById('OrderSearchModal')).hide();

			$('#button-draft-product').click();
		}
	});
});

$('#OrderSearchModal').on('hidden.bs.modal', function() {
	$('#os-order-id, #os-company').val('');
	$('#os-results').html('<tr><td colspan="5" class="text-center">' + $('#text_use_filters_deliveries').val() + '</td></tr>');
	$('#os-warning').hide();
});

var psProducts = [];
var psToday = (function() {
	var d = new Date();
	return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
})();

$('#addProduct').click(function(e) {
	if ($('#customer_id').val() == 0) {
		alert($('#text_select_customer_first').val());
		$('#order-customer').focus();
	} else {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductSearchModal')).show();
	}
});

function psDoSearch() {
	var btn = $('#ps-search');
	var searching = $('#text_searching').val();
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + searching);
	$('#ps-results').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> ' + searching + '</td></tr>');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('catalog/product/searchProducts', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_sku: $('#ps-sku').val(), filter_name: $('#ps-name').val(), filter_model: $('#ps-model').val() },
		dataType: 'json',
		success: function(json) {
			if (json.warning) {
				$('#ps-warning').text(json.warning).show();
				$('#ps-results').html('<tr><td colspan="5" class="text-center">' + json.warning + '</td></tr>');
				psProducts = [];
				return;
			}
			$('#ps-warning').hide();
			psProducts = json;
			if (!json.length) {
				$('#ps-results').html('<tr><td colspan="5" class="text-center">' + $('#text_no_products_found').val() + '</td></tr>');
				return;
			}
			var html = '';
			for (var i = 0; i < json.length; i++) {
				html += '<tr data-idx="' + i + '" style="cursor:pointer;">';
				html += '<td>' + (json[i].sku || '') + '</td>';
				html += '<td>' + json[i].name + '</td>';
				html += '<td>' + (json[i].model || '') + '</td>';
				html += '<td class="text-right">' + json[i].price_formatted + '</td>';
				html += '<td class="text-right">' + json[i].quantity + '</td>';
				html += '</tr>';
			}
			$('#ps-results').html(html);
		},
		error: function() {
			$('#ps-warning').text($('#error_search_products').val()).show();
			$('#ps-results').html('<tr><td colspan="5" class="text-center">' + $('#error_search_products').val() + '</td></tr>');
			psProducts = [];
		},
		complete: function() {
			btn.prop('disabled', false).html($('#button_update').val());
		}
	});
}

$('#ps-search').click(psDoSearch);

$('#ps-sku, #ps-name, #ps-model').on('keypress', function(e) {
	if (e.which == 13) psDoSearch();
});

$(document).on('dblclick', '#ps-results tr[data-idx]', function() {
	var idx = parseInt($(this).data('idx'));
	var p = psProducts[idx];
	$('#product_id').val(p.product_id);
	$('#draft-product').val(p.name);
	$('#pm-product-name').text(p.name);
	$('#pm-quantity').val(1);
	$('#price_override').val((p.price !== undefined && p.price !== null) ? p.price : '');
	$('#pm-discount').val('');
	$('#pm-tax-rate').val((p.tax_rate !== undefined && p.tax_rate !== null) ? p.tax_rate : '');

	var html = '', s = $('#text_select').val();
	if (p.option && p.option.length) {
		for (var i = 0; i < p.option.length; i++) {
			var o = p.option[i];
			html += '<div class="form-group" id="option-' + o.product_option_id + '">';
			html += '<label class="col-form-label col-sm-4">';
			if (o.required == 1) html += '<b class="required">* </b>';
			html += o.name + ':</label><div class="control-field col-sm-8">';
			if (o.type == 'select') {
				html += '<select name="option[' + o.product_option_id + ']" class="form-control"><option value="">' + s + '</option>';
				for (var j = 0; j < o.option_value.length; j++) {
					html += '<option value="' + o.option_value[j].product_option_value_id + '">' + o.option_value[j].name + '</option>';
				}
				html += '</select>';
			} else if (o.type == 'radio' || o.type == 'image') {
				for (var j = 0; j < o.option_value.length; j++) {
					html += '<div class="radio"><label><input type="radio" name="option[' + o.product_option_id + ']" value="' + o.option_value[j].product_option_value_id + '"> ' + o.option_value[j].name + '</label></div>';
				}
			} else if (o.type == 'checkbox') {
				for (var j = 0; j < o.option_value.length; j++) {
					html += '<div class="checkbox"><label><input type="checkbox" name="option[' + o.product_option_id + '][]" value="' + o.option_value[j].product_option_value_id + '"> ' + o.option_value[j].name + '</label></div>';
				}
			} else if (o.type == 'date' || o.type == 'datetime') {
				html += '<div class="input-group"><input type="text" name="option[' + o.product_option_id + ']" value="' + psToday + '" class="form-control date" placeholder="DD-MM-YYYY"><div class="input-group-append"><button class="btn btn-default" type="button" onclick="$(this).closest(\'.input-group\').find(\'.date\').focus();"><i class="fa fa-calendar"></i></button></div></div>';
			} else if (o.type == 'time') {
				html += '<input type="text" name="option[' + o.product_option_id + ']" value="" class="form-control">';
			} else if (o.type == 'textarea') {
				html += '<textarea name="option[' + o.product_option_id + ']" class="form-control"></textarea>';
			} else {
				html += '<input type="text" name="option[' + o.product_option_id + ']" value="" class="form-control">';
			}
			html += '</div></div>';
		}
	}
	$('#option').html(html);

	bootstrap.Modal.getInstance(document.getElementById('ProductSearchModal')).hide();
	bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductModal')).show();
});

$('#ProductSearchModal').on('hidden.bs.modal', function() {
	$('#ps-sku, #ps-name, #ps-model').val('');
	$('#ps-results').html('<tr><td colspan="5" class="text-center">' + $('#text_use_filters_products').val() + '</td></tr>');
	$('#ps-warning').hide();
	psProducts = [];
});

function draftMarkPriceChanged(input) {
	var $input = $(input);
	var current = parseFloat($input.val().replace(',', '.')) || 0;
	var original = parseFloat($input.data('catalog-price')) || 0;

	$input.toggleClass('draft-price-changed', current.toFixed(2) !== original.toFixed(2));
}

$(document).on('input', '.draft-price', function() {
	draftMarkPriceChanged(this);
});

$(document).on('change', '.draft-qty, .draft-price', function() {
	draftMarkPriceChanged(this);
	$('#button-draft-product').click();
});

$('.draft-price').each(function() {
	draftMarkPriceChanged(this);
});
</script>
<style>
.draft-price-changed {
	color: #b30000;
	font-weight: bold;
	border-color: #b30000;
	background-color: #fdf0f0;
}
</style>
<?php echo $footer; ?>
