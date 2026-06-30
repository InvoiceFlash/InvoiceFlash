<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
	<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo $heading_title; ?> <?php echo ($order_id) ? 'Nº ' . $order_id : ''; ?></div>
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
					<button class="btn btn-info pull-right" type="button" data-bs-toggle="modal" data-bs-target="#CommentModal"><i class="fas fa-comment"></i><span></span></button>
					<!-- CommentModal -->
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
					<!-- EndModal -->
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
							<label class="control-label col-sm-4"><?php echo $entry_order_status; ?></label>
							<div class="control-field col-sm-8">
								<select name="order_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected=""><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
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
							<?php if ($order_products) { ?>
							<?php foreach ($order_products as $order_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-order-product').click();"><i class="fa fa-trash"></i></a></td>
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
								</td>
								<td class="d-none d-sm-table-cell"><?php echo $order_product['model']; ?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][model]" value="<?php echo $order_product['model']; ?>"></td>
								<td class="text-right"><?php echo $order_product['quantity']; ?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>"></td>
								<td class="text-right"><?php echo $order_product['price']; ?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>"></td>
								<td class="text-right"><?php echo $order_product['total']; ?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][total]" value="<?php echo $order_product['total']; ?>">
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $order_product['tax']; ?>"></td>
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
							<?php if ($order_totals) { ?>
							<?php foreach ($order_totals as $order_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="4"><?php echo $order_total['title']; ?>:
									<input type="hidden" name="order_total[<?php echo $total_row; ?>][order_total_id]" value="<?php echo $order_total['order_total_id']; ?>">
									<input type="hidden" name="order_total[<?php echo $total_row; ?>][code]" value="<?php echo $order_total['code']; ?>">
									<input type="hidden" name="order_total[<?php echo $total_row; ?>][title]" value="<?php echo $order_total['title']; ?>">
									<input type="hidden" name="order_total[<?php echo $total_row; ?>][text]" value="<?php echo $order_total['text']; ?>">
									<input type="hidden" name="order_total[<?php echo $total_row; ?>][value]" value="<?php echo $order_total['value']; ?>">
									<input type="hidden" name="order_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $order_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $order_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal búsqueda de artículos -->
			<div class="modal fade" tabindex="-1" role="dialog" id="ProductSearchModal">
				<div class="modal-dialog modal-lg" style="max-width:72%;" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><i class="fa fa-search"></i> Búsqueda de artículos</h5>
							<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="form-inline mb-3" style="gap:8px;flex-wrap:nowrap;align-items:flex-end;">
								<div style="flex:0 0 140px;">
									<label class="d-block mb-1">Código / SKU</label>
									<input type="text" id="ps-sku" class="form-control" placeholder="Código..." style="width:100%;">
								</div>
								<div style="flex:1 1 auto;">
									<label class="d-block mb-1">Descripción</label>
									<input type="text" id="ps-name" class="form-control w-100" placeholder="Descripción...">
								</div>
								<div style="flex:0 0 160px;">
									<label class="d-block mb-1">Modelo</label>
									<input type="text" id="ps-model" class="form-control" placeholder="Modelo..." style="width:100%;">
								</div>
								<div style="flex:0 0 auto;">
									<label class="d-block mb-1">&nbsp;</label>
									<button class="btn btn-primary" id="ps-search" type="button">
										<i class="fa fa-search"></i> Actualizar
									</button>
								</div>
							</div>
							<div class="table-responsive" style="max-height:420px;overflow-y:auto;">
								<table class="table table-bordered table-hover table-sm" id="ps-table">
									<thead class="thead-light">
										<tr>
											<th>Código</th>
											<th>Descripción</th>
											<th>Modelo</th>
											<th class="text-right">Precio</th>
											<th class="text-right">Stock</th>
										</tr>
									</thead>
									<tbody id="ps-tbody">
										<tr><td colspan="5" class="text-center text-muted py-3">Introduzca criterios y pulse <strong>Actualizar</strong></td></tr>
									</tbody>
								</table>
							</div>
							<small class="text-muted"><i class="fa fa-hand-o-up"></i> Doble clic en una fila para seleccionar el artículo</small>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal cantidad y precio -->
			<div class="modal" tabindex="-1" role="dialog" id="ProductModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="pm-product-name">Artículo</h5>
							<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="form-horizontal">
								<input type="hidden" name="product_id" id="product_id" value="">
								<input type="hidden" name="product" id="order-product" value="">
								<div id="option"></div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_quantity; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" name="quantity" id="pm-quantity" value="1" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Precio:</label>
									<div class="control-field col-sm-8">
										<input type="text" name="price_override" id="price_override" value="" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" id="button-order-product" class="btn btn-info">
								<i class="fa fa-plus-circle"></i> Añadir
							</button>
						</div>
					</div>
				</div>
			</div>
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
// Reset modal de cantidad/precio al cerrar
$('#ProductModal').on('hidden.bs.modal', function () {
    $(this).find('#order-product').val('');
    $(this).find('#product_id').val(0);
    $(this).find('#pm-quantity').val(1);
    $(this).find('#price_override').val('');
    $(this).find('#pm-product-name').text('Artículo');
    $(this).find('#option').html('');
});

// Abrir modal de búsqueda al pulsar "Add Product"
$('#addProduct').on('click', function(e) {
    if ($('#customer_id').val() == 0) {
        alert('Por favor, seleccione un cliente primero');
        $('#order-customer').focus();
    } else {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductSearchModal')).show();
    }
});

// Array de productos del último resultado de búsqueda
var psProducts = [];

function psEsc(s) {
    return $('<span>').text(s).html();
}

// Botón Actualizar
$('#ps-search').on('click', function() {
    var sku   = $.trim($('#ps-sku').val());
    var name  = $.trim($('#ps-name').val());
    var model = $.trim($('#ps-model').val());

    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');

    $.ajax({
        url: 'index.php?route=catalog/product/searchProducts&token=' + token +
             '&filter_sku='   + encodeURIComponent(sku) +
             '&filter_name='  + encodeURIComponent(name) +
             '&filter_model=' + encodeURIComponent(model),
        dataType: 'json',
        success: function(json) {
            btn.prop('disabled', false).html('<i class="fa fa-search"></i> Actualizar');
            if (json && json.warning) {
                psProducts = [];
                $('#ps-tbody').html('<tr><td colspan="5" class="text-center text-warning py-3"><i class="fa fa-exclamation-triangle"></i> ' + json.warning + '</td></tr>');
                return;
            }
            psProducts = json || [];
            var html = '';
            if (psProducts.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted py-3">No se encontraron artículos</td></tr>';
            } else {
                $.each(psProducts, function(i, p) {
                    html += '<tr style="cursor:pointer" data-idx="' + i + '">';
                    html += '<td>' + psEsc(p.sku)  + '</td>';
                    html += '<td>' + psEsc(p.name) + '</td>';
                    html += '<td>' + psEsc(p.model)+ '</td>';
                    html += '<td class="text-right">' + psEsc(p.price_formatted) + '</td>';
                    html += '<td class="text-right">' + p.quantity + '</td>';
                    html += '</tr>';
                });
            }
            $('#ps-tbody').html(html);
        },
        error: function() {
            btn.prop('disabled', false).html('<i class="fa fa-search"></i> Actualizar');
            alert('Error al buscar artículos');
        }
    });
});

// Enter en los campos de filtro lanza la búsqueda
$('#ps-sku,#ps-name,#ps-model').on('keypress', function(e) {
    if (e.which === 13) { $('#ps-search').trigger('click'); }
});

// Doble clic en fila de resultado
$('#ps-tbody').on('dblclick', 'tr[data-idx]', function() {
    var p = psProducts[parseInt($(this).attr('data-idx'), 10)];
    if (!p) return;

    bootstrap.Modal.getInstance(document.getElementById('ProductSearchModal')).hide();

    $('#product_id').val(p.product_id);
    $('#order-product').val(p.name);
    $('#pm-product-name').text(p.name);
    $('#pm-quantity').val(1);
    $('#price_override').val(p.price);

    // Fecha de hoy en formato DD-MM-YYYY (el que usa el datetimepicker del sistema)
    var psToday = (function() {
        var d = new Date();
        return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
    })();

    // Construir HTML de opciones
    var optHtml = '';
    if (p.option && p.option.length > 0) {
        $.each(p.option, function(i, o) {
            optHtml += '<div class="form-group" id="option-' + o.product_option_id + '">';
            optHtml += '<label class="control-label col-sm-4">';
            if (o.required == 1) optHtml += '<b class="required">* </b>';
            optHtml += psEsc(o.name) + ':</label>';
            optHtml += '<div class="control-field col-sm-8">';
            if (o.type === 'select') {
                optHtml += '<select name="option[' + o.product_option_id + ']" class="form-control"><option value="">--- Seleccionar ---</option>';
                $.each(o.option_value, function(j, ov) {
                    optHtml += '<option value="' + ov.product_option_value_id + '">' + psEsc(ov.name);
                    if (ov.price) optHtml += ' (' + ov.price_prefix + ov.price + ')';
                    optHtml += '</option>';
                });
                optHtml += '</select>';
            } else if (o.type === 'radio' || o.type === 'image') {
                $.each(o.option_value, function(j, ov) {
                    optHtml += '<div class="radio"><label><input type="radio" name="option[' + o.product_option_id + ']" value="' + ov.product_option_value_id + '"> ' + psEsc(ov.name);
                    if (ov.price) optHtml += ' (' + ov.price_prefix + ov.price + ')';
                    optHtml += '</label></div>';
                });
            } else if (o.type === 'checkbox') {
                $.each(o.option_value, function(j, ov) {
                    optHtml += '<div class="checkbox"><label><input type="checkbox" name="option[' + o.product_option_id + '][]" value="' + ov.product_option_value_id + '"> ' + psEsc(ov.name);
                    if (ov.price) optHtml += ' (' + ov.price_prefix + ov.price + ')';
                    optHtml += '</label></div>';
                });
            } else if (o.type === 'date' || o.type === 'datetime') {
                optHtml += '<div class="input-group">';
                optHtml += '<input type="text" name="option[' + o.product_option_id + ']" value="' + psToday + '" class="form-control date">';
                optHtml += '<div class="input-group-append"><span class="input-group-text" style="cursor:pointer" onclick="$(this).closest(\'.input-group\').find(\'.date\').focus();">';
                optHtml += '<i class="fa fa-calendar"></i></span></div></div>';
            } else if (o.type === 'time') {
                optHtml += '<div class="input-group">';
                optHtml += '<input type="text" name="option[' + o.product_option_id + ']" class="form-control time">';
                optHtml += '<div class="input-group-append"><span class="input-group-text" style="cursor:pointer" onclick="$(this).closest(\'.input-group\').find(\'.time\').focus();">';
                optHtml += '<i class="fa fa-clock-o"></i></span></div></div>';
            } else if (o.type === 'textarea') {
                optHtml += '<textarea name="option[' + o.product_option_id + ']" class="form-control"></textarea>';
            } else {
                optHtml += '<input type="text" name="option[' + o.product_option_id + ']" class="form-control">';
            }
            optHtml += '</div></div>';
        });
    }
    $('#option').html(optHtml);

    bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductModal')).show();
});
</script>
<?php echo $footer; ?>