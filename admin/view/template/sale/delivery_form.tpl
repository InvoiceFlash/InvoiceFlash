<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<?php $fa='clipboard'; include DIR_TEMPLATE . 'common/template-title-form.tpl'; ?>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" onsubmit="return validateForm();" class="form-inline" enctype="multipart/form-data" id="form">
			<div class="card" id="tab-customer" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_customer; ?>
					<button class="btn btn-warning pull-right" type="button" title="Pedidos Pendientes de procesar" style="margin-right:4px;" onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('OrderSearchModal')).show();"><i class="fa fa-list-alt"></i> <span class="hidden-xs">Pedidos Pendientes</span></button>
				<button class="btn btn-info pull-right" type="button" data-bs-toggle="modal" data-bs-target="#CommentModal" style="margin-right:4px;"><i class="fas fa-comment"></i><span></span></button>
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
							<?php if ($delivery_products) { ?>
							<?php foreach ($delivery_products as $delivery_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-delivery-product').click();"><i class="fa fa-trash"></i></a></td>
								<td><?php echo $delivery_product['name']; ?><br>
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_product_id]" value="<?php echo $delivery_product['delivery_product_id']; ?>">
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $delivery_product['product_id']; ?>">
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][name]" value="<?php echo $delivery_product['name']; ?>">
									<?php foreach ($delivery_product['option'] as $option) { ?>
										<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
										<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_option][<?php echo $option_row; ?>][delivery_option_id]" value="<?php echo $option['delivery_option_id']; ?>">
										<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>">
										<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>">
										<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>">
										<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>">
										<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][delivery_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>">
									<?php $option_row++; ?>
									<?php } ?>
								</td>
								<td class="d-none d-sm-table-cell"><?php echo $delivery_product['model']; ?>
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][model]" value="<?php echo $delivery_product['model']; ?>"></td>
								<td class="text-right"><?php echo $delivery_product['quantity']; ?>
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $delivery_product['quantity']; ?>"></td>
								<td class="text-right"><?php echo $delivery_product['price']; ?>
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][price]" value="<?php echo $delivery_product['price']; ?>"></td>
								<td class="text-right"><?php echo $delivery_product['total']; ?>
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][total]" value="<?php echo $delivery_product['total']; ?>">
									<input type="hidden" name="delivery_product[<?php echo $product_row; ?>][tax]" value="<?php echo $delivery_product['tax']; ?>"></td>
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
							<?php if ($delivery_totals) { ?>
							<?php foreach ($delivery_totals as $delivery_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="4"><?php echo $delivery_total['title']; ?>:
									<input type="hidden" name="delivery_total[<?php echo $total_row; ?>][delivery_total_id]" value="<?php echo $delivery_total['delivery_total_id']; ?>">
									<input type="hidden" name="delivery_total[<?php echo $total_row; ?>][code]" value="<?php echo $delivery_total['code']; ?>">
									<input type="hidden" name="delivery_total[<?php echo $total_row; ?>][title]" value="<?php echo $delivery_total['title']; ?>">
									<input type="hidden" name="delivery_total[<?php echo $total_row; ?>][text]" value="<?php echo $delivery_total['text']; ?>">
									<input type="hidden" name="delivery_total[<?php echo $total_row; ?>][value]" value="<?php echo $delivery_total['value']; ?>">
									<input type="hidden" name="delivery_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $delivery_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $delivery_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal Pedidos Pendientes -->
			<div class="modal" tabindex="-1" role="dialog" id="OrderSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Pedidos pendientes</h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm-3">
									<label class="control-label">Nº Pedido</label>
									<input type="text" id="os-order-id" class="form-control" placeholder="Nº pedido">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label">Cliente</label>
									<input type="text" id="os-company" class="form-control" placeholder="Empresa">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="os-search" class="btn btn-primary">Actualizar</button>
								</div>
							</div>
							<div id="os-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Nº</th>
											<th>Cliente</th>
											<th>Fecha Pedido</th>
											<th class="text-right">Total</th>
											<th>Estado</th>
										</tr>
									</thead>
									<tbody id="os-results">
										<tr><td colspan="5" class="text-center">Use los filtros para buscar pedidos</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Pedidos Pendientes -->
			<!-- Modal Product -->
			<div class="modal" tab-index="-1" role="dialog" id="ProductModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $text_product; ?></h5>
							<button class="close" data-bs-dismiss="modal" arial-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_product; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" name="product" value="" id="delivery-product" class="form-control" autocomplete="off">
										<input type="hidden" name="product_id" id="product_id" value="" class="form-control">
									</div>
								</div>
								<div id="option"></div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_quantity; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" name="quantity" value="1" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-delivery-product" class="btn btn-info pull-right">
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
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_fax; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="fax" value="<?php echo $fax; ?>" class="form-control">
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
    $(this).find("#delivery-product").val('').end();
    $(this).find("#product_id").val('');
	$(this).find("#option").html('');
});
$('#addProduct').click(function(e){
	if($('#customer_id').val()==0){
		alert('Please, select a customer first');
		$('#order-customer').focus();
	} else {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductModal')).show();
	}
});
</script>
<script>
function osDoSearch() {
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('sale/delivery/searchOrders', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_order_id: $('#os-order-id').val(), filter_company: $('#os-company').val() },
		dataType: 'json',
		success: function(json) {
			$('#os-warning').hide();
			if (!json || !json.length) {
				$('#os-results').html('<tr><td colspan="5" class="text-center">No se encontraron pedidos</td></tr>');
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
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('sale/delivery/getOrderData', 'token=' . $this->session->data['token'], 'SSL')); ?>',
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

			// Productos
			var button_remove = $('#button_remove').val();
			var product_row = 0;
			var html = '';
			if (json.delivery_product && json.delivery_product.length) {
				for (var i = 0; i < json.delivery_product.length; i++) {
					var p = json.delivery_product[i];
					var option_row = 0;
					html += '<tr id="product-row' + product_row + '">';
					html += '<td class="text-center"><a class="label label-danger" title="' + button_remove + '" onclick="$(\'#product-row' + product_row + '\').remove();$(\'#button-delivery-product\').click();"><i class="fa fa-trash"></i></a></td>';
					html += '<td>' + p.name + '<br>';
					html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_product_id]" value="">';
					html += '<input type="hidden" name="delivery_product[' + product_row + '][product_id]" value="' + p.product_id + '">';
					html += '<input type="hidden" name="delivery_product[' + product_row + '][name]" value="' + p.name + '">';
					if (p.option && p.option.length) {
						for (var j = 0; j < p.option.length; j++) {
							var opt = p.option[j];
							html += '<div class="help">' + opt.name + ': ' + opt.value + '</div>';
							html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_option][' + option_row + '][delivery_option_id]" value="' + opt.delivery_option_id + '">';
							html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_option][' + option_row + '][product_option_id]" value="' + opt.product_option_id + '">';
							html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_option][' + option_row + '][product_option_value_id]" value="' + opt.product_option_value_id + '">';
							html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_option][' + option_row + '][name]" value="' + opt.name + '">';
							html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_option][' + option_row + '][value]" value="' + opt.value + '">';
							html += '<input type="hidden" name="delivery_product[' + product_row + '][delivery_option][' + option_row + '][type]" value="' + opt.type + '">';
							option_row++;
						}
					}
					html += '</td>';
					html += '<td class="d-none d-sm-table-cell">' + p.model + '<input type="hidden" name="delivery_product[' + product_row + '][model]" value="' + p.model + '"></td>';
					html += '<td class="text-right">' + p.quantity + '<input type="hidden" name="delivery_product[' + product_row + '][quantity]" value="' + p.quantity + '"></td>';
					html += '<td class="text-right">' + p.price + '<input type="hidden" name="delivery_product[' + product_row + '][price]" value="' + p.price + '"></td>';
					html += '<td class="text-right">' + p.total + '<input type="hidden" name="delivery_product[' + product_row + '][total]" value="' + p.total + '"><input type="hidden" name="delivery_product[' + product_row + '][tax]" value="' + p.tax + '"></td>';
					html += '</tr>';
					product_row++;
				}
			}
			$('#product').html(html);

			// Totales
			var total_row = 0;
			var thtml = '';
			if (json.delivery_total && json.delivery_total.length) {
				for (var i = 0; i < json.delivery_total.length; i++) {
					var t = json.delivery_total[i];
					thtml += '<tr id="total-row' + total_row + '">';
					thtml += '<td class="d-none d-sm-table-cell"></td>';
					thtml += '<td class="text-right" colspan="4">';
					thtml += '<input type="hidden" name="delivery_total[' + total_row + '][delivery_total_id]" value="">';
					thtml += '<input type="hidden" name="delivery_total[' + total_row + '][code]" value="' + t.code + '">';
					thtml += '<input type="hidden" name="delivery_total[' + total_row + '][title]" value="' + t.title + '">';
					thtml += '<input type="hidden" name="delivery_total[' + total_row + '][text]" value="' + t.text + '">';
					thtml += '<input type="hidden" name="delivery_total[' + total_row + '][value]" value="' + t.value + '">';
					thtml += '<input type="hidden" name="delivery_total[' + total_row + '][sort_order]" value="' + t.sort_order + '">';
					thtml += t.title + ':</td>';
					thtml += '<td class="text-right">' + t.text + '</td>';
					thtml += '</tr>';
					total_row++;
				}
			}
			$('#total').html(thtml);

			bootstrap.Modal.getInstance(document.getElementById('OrderSearchModal')).hide();
		}
	});
});

$('#OrderSearchModal').on('hidden.bs.modal', function() {
	$('#os-order-id, #os-company').val('');
	$('#os-results').html('<tr><td colspan="5" class="text-center">Use los filtros para buscar pedidos</td></tr>');
	$('#os-warning').hide();
});
</script>
<?php echo $footer; ?>