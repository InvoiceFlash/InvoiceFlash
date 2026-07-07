<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<?php $fa='edit'; include DIR_TEMPLATE . 'common/template-title-form.tpl'; ?>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" onsubmit="return validateForm();" class="form-inline" enctype="multipart/form-data" id="form">
			<div class="card" id="tab-customer" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_customer; ?>
					<button class="btn btn-info pull-right" type="button" data-bs-toggle="modal" data-bs-target="#CommentModal"><i class="fas fa-comment"></i><span></span></button>
					<!-- Modal -->
					<div class="modal fade" id="CommentModal" tabindex="-1" role="dialog" aria-labelledby="CommentModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="CommentModalLabel"><?php echo $entry_comment; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<div class="control-field">
									<textarea name="comment" class="form-control" cols="60" rows="10"><?php echo $comment; ?></textarea>
								</div>
							</div>
						</div>
						</div>
					</div>
					</div>
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
									<div class="help-block text-danger"><?php echo $error_shipping_method; ?></div>
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
									<div class="help-block text-danger"><?php echo $error_payment_method; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_invoice_status; ?></label>
							<div class="control-field col-sm-8">
								<select name="invoice_status_id" class="form-control">
									<?php foreach ($invoice_statuses as $invoice_status) { ?>
									<?php if ($invoice_status['invoice_status_id'] == $invoice_status_id) { ?>
									<option value="<?php echo $invoice_status['invoice_status_id']; ?>" selected=""><?php echo $invoice_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $invoice_status['invoice_status_id']; ?>"><?php echo $invoice_status['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="tab-product" style="width:100%;">
				<div class="card-header">
					Products and Totals 
					<button class="btn btn-info pull-right" type="button" id="addProduct"><i class="fa fa-plus-circle"></i> <span class="hidden-xs">Add Product</span></button>
				</div>
				<div class="card-body">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th><?php echo $column_product; ?></th>
								<th class="d-none d-sm-table-cell"><?php echo $column_model; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
						</tr>
						</thead>
						<?php $product_row = 0; ?>
						<?php $option_row = 0; ?>
						<tbody id="product">
							<?php if ($quote_products) { ?>
							<?php foreach ($quote_products as $quote_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-quote-product').click();"><i class="fa fa-trash"></i></a></td>
								<td><?php echo $quote_product['name']; ?><br>
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_product_id]" value="<?php echo $quote_product['quote_product_id']; ?>">
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $quote_product['product_id']; ?>">
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][name]" value="<?php echo $quote_product['name']; ?>">
									<?php foreach ($quote_product['option'] as $option) { ?>
										<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
										<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_option][<?php echo $option_row; ?>][quote_option_id]" value="<?php echo $option['quote_option_id']; ?>">
										<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>">
										<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>">
										<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>">
										<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>">
										<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quote_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>">
									<?php $option_row++; ?>
									<?php } ?>
								</td>
								<td class="d-none d-sm-table-cell"><?php echo $quote_product['model']; ?>
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][model]" value="<?php echo $quote_product['model']; ?>"></td>
								<td class="text-right"><?php echo $quote_product['quantity']; ?>
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $quote_product['quantity']; ?>"></td>
								<td class="text-right"><?php echo $quote_product['price']; ?>
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][price]" value="<?php echo $quote_product['price']; ?>"></td>
								<td class="text-right"><?php echo $quote_product['total']; ?>
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][total]" value="<?php echo $quote_product['total']; ?>">
									<input type="hidden" name="quote_product[<?php echo $product_row; ?>][tax]" value="<?php echo $quote_product['tax']; ?>"></td>
							</tr>
							<?php $product_row++; ?>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tbody id="total">
							<?php $total_row = 0; ?>
							<?php if ($quote_totals) { ?>
							<?php foreach ($quote_totals as $quote_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="4"><?php echo $quote_total['title']; ?>:
									<input type="hidden" name="quote_total[<?php echo $total_row; ?>][quote_total_id]" value="<?php echo $quote_total['quote_total_id']; ?>">
									<input type="hidden" name="quote_total[<?php echo $total_row; ?>][code]" value="<?php echo $quote_total['code']; ?>">
									<input type="hidden" name="quote_total[<?php echo $total_row; ?>][title]" value="<?php echo $quote_total['title']; ?>">
									<input type="hidden" name="quote_total[<?php echo $total_row; ?>][text]" value="<?php echo $quote_total['text']; ?>">
									<input type="hidden" name="quote_total[<?php echo $total_row; ?>][value]" value="<?php echo $quote_total['value']; ?>">
									<input type="hidden" name="quote_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $quote_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $quote_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal Buscar Producto -->
			<div class="modal" tabindex="-1" role="dialog" id="ProductSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Producto</h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm-3">
									<label class="control-label">Código / SKU</label>
									<input type="text" id="ps-sku" class="form-control" placeholder="SKU">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label">Descripción</label>
									<input type="text" id="ps-name" class="form-control" placeholder="Descripción">
								</div>
								<div class="col-12 col-sm-3">
									<label class="control-label">Modelo</label>
									<input type="text" id="ps-model" class="form-control" placeholder="Modelo">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="ps-search" class="btn btn-primary">Actualizar</button>
								</div>
							</div>
							<div id="ps-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>SKU</th>
											<th>Descripción</th>
											<th>Modelo</th>
											<th class="text-right">Precio</th>
											<th class="text-right">Stock</th>
										</tr>
									</thead>
									<tbody id="ps-results">
										<tr><td colspan="5" class="text-center">Use los filtros para buscar productos</td></tr>
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
							<input type="hidden" name="product" id="quote-product" value="">
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
							</div>
						</div>
						<div class="modal-footer">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-quote-product" class="btn btn-info pull-right">
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
											<label class="control-label col-3"><?php echo $entry_customer; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_company" value="<?php echo $payment_company !== '' ? $payment_company : $company; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_email; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
												<?php if ($error_email) { ?>
													<div class="help-block text-danger"><?php echo $error_email; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_telephone; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
												<?php if ($error_telephone) { ?>
													<div class="help-block text-danger"><?php echo $error_telephone; ?></div>
												<?php } ?>
											</div>
										</div>
										<input type="hidden" name="fax" value="<?php echo $fax; ?>">
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
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_address_1; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" class="form-control">
												<?php if ($error_payment_address_1) { ?>
													<div class="help-block text-danger"><?php echo $error_payment_address_1; ?></div>
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
													<div class="help-block text-danger"><?php echo $error_payment_city; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" class="form-control">
												<?php if ($error_payment_postcode) { ?>
													<div class="help-block text-danger"><?php echo $error_payment_postcode; ?></div>
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
													<div class="help-block text-danger"><?php echo $error_payment_country; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_zone; ?></label>
											<div class="control-field col-sm-8">
												<select name="payment_zone_id" class="form-control"></select>
												<?php if ($error_payment_zone) { ?>
													<div class="help-block text-danger"><?php echo $error_payment_zone; ?></div>
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
													<div class="help-block text-danger"><?php echo $error_shipping_address_1; ?></div>
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
													<div class="help-block text-danger"><?php echo $error_shipping_postcode; ?></div>
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
													<div class="help-block text-danger"><?php echo $error_shipping_country; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><b class="required">*</b> <?php echo $entry_zone; ?></label>
											<div class="control-field col-sm-8">
												<select name="shipping_zone_id" class="form-control"></select>
												<?php if ($error_shipping_zone) { ?>
													<div class="help-block text-danger"><?php echo $error_shipping_zone; ?></div>
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
<script>
function validateForm(){

	// Customer
	var customer = $('#customer_id');
	if (customer.val() == 0) {
		alert("Customer must be filled out");
		return false;
	}

	// Products
	if (!$("#product-row0").length > 0) {
		alert("Insert almost a Product");
		return false;
	}
	
	// Shipping
	var ship = $('#shipping').val();
	if (ship=="") {
		alert("Select a Shpping Method");
		return false;
	}

	// Payment
	var pay = $('#payment').val();
	if (pay==""){
		alert("Select a Payment Method");
		return false;
	}

}
</script>
<script>
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
<script>
$('#ProductModal').on('hidden.bs.modal', function () {
	$(this).find('#quote-product').val('');
	$(this).find('#product_id').val(0);
	$(this).find('#price_override').val('');
	$(this).find('#pm-quantity').val(1);
	$(this).find('#option').html('');
});

var qpProducts = [];
var qpToday = (function() {
	var d = new Date();
	return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
})();

$('#addProduct').click(function(e){
	if($('#customer_id').val()==0){
		alert('Please, select a customer first');
		$('#order-customer').focus();
	} else {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductSearchModal')).show();
	}
});

function qpDoSearch() {
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('catalog/product/searchProducts', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_sku: $('#ps-sku').val(), filter_name: $('#ps-name').val(), filter_model: $('#ps-model').val() },
		dataType: 'json',
		success: function(json) {
			if (json.warning) {
				$('#ps-warning').text(json.warning).show();
				$('#ps-results').html('<tr><td colspan="5" class="text-center">' + json.warning + '</td></tr>');
				qpProducts = [];
				return;
			}
			$('#ps-warning').hide();
			qpProducts = json;
			if (!json.length) {
				$('#ps-results').html('<tr><td colspan="5" class="text-center">No se encontraron productos</td></tr>');
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
		}
	});
}

$('#ps-search').click(qpDoSearch);

$('#ps-sku, #ps-name, #ps-model').on('keypress', function(e) {
	if (e.which == 13) qpDoSearch();
});

$(document).on('dblclick', '#ps-results tr[data-idx]', function() {
	var idx = parseInt($(this).data('idx'));
	var p = qpProducts[idx];
	$('#product_id').val(p.product_id);
	$('#quote-product').val(p.name);
	$('#pm-product-name').text(p.name);
	$('#pm-quantity').val(1);
	$('#price_override').val(p.price || '');

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
				html += '<div class="input-group"><input type="text" name="option[' + o.product_option_id + ']" value="' + qpToday + '" class="form-control date" placeholder="DD-MM-YYYY"><div class="input-group-append"><button class="btn btn-default" type="button" onclick="$(this).closest(\'.input-group\').find(\'.date\').focus();"><i class="fa fa-calendar"></i></button></div></div>';
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
	$('#ps-results').html('<tr><td colspan="5" class="text-center">Use los filtros para buscar productos</td></tr>');
	$('#ps-warning').hide();
	qpProducts = [];
});

$('#CustomerModal').on('show.bs.modal', function(e){
	if ($('#customer_id').val() == 0) {
		e.preventDefault();
		alert('Please, select a customer first');
		$('#order-customer').focus();
	}
});
</script>
<?php echo $footer; ?>