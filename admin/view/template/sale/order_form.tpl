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
								<div class="input-group-append"><button class="btn btn-default" type="button" id="searchCustomer" title="Buscar Cliente"><i class="fa fa-search"></i></button></div>
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
							<label class="control-label col-sm-4"><?php echo $entry_global_discount; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="global_discount" id="global_discount" value="<?php echo $global_discount; ?>" class="form-control text-right" inputmode="decimal">
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
								<th><?php echo $column_product; ?></th>
								<th><?php echo $column_delivery_date; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_discount; ?></th>
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
								<td>
									<input type="text" class="form-control order-name" name="order_product[<?php echo $product_row; ?>][name]" value="<?php echo htmlspecialchars((string)$order_product['name'], ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_product_id]" value="<?php echo $order_product['order_product_id']; ?>">
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>">
									<?php foreach ($order_product['option'] as $option) { ?>
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][order_option_id]" value="<?php echo $option['order_option_id']; ?>">
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>">
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>">
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>">
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>">
										<input type="hidden" name="order_product[<?php echo $product_row; ?>][order_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>">
									<?php $option_row++; ?>
									<?php } ?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][model]" value="<?php echo $order_product['model']; ?>">
								</td>
								<td>
									<?php foreach ($order_product['option'] as $option) { ?>
										<div><?php echo $option['value']; ?></div>
									<?php } ?>
								</td>
								<td class="text-right"><input type="text" class="form-control text-right order-qty" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right order-price" data-catalog-price="<?php echo $order_product['catalog_price_raw']; ?>" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price_raw']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right order-discount" name="order_product[<?php echo $product_row; ?>][discount]" value="<?php echo $order_product['discount_raw']; ?>"></td>
								<td class="text-right"><?php echo $order_product['total']; ?>
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][total]" value="<?php echo $order_product['total']; ?>">
									<input type="hidden" name="order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $order_product['tax']; ?>"></td>
							</tr>
							<?php $product_row++; ?>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
						<tbody id="total">
							<?php $total_row = 0; ?>
							<?php if ($order_totals) { ?>
							<?php foreach ($order_totals as $order_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="5"><?php echo $order_total['title']; ?>:
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
			<!-- Modal Buscar Cliente -->
			<div class="modal" tabindex="-1" role="dialog" id="CustomerSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Cliente</h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm">
									<label class="control-label">Empresa / Nombre</label>
									<input type="text" id="cs-company" class="form-control" placeholder="Empresa / Nombre">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label">Nombre de Contacto</label>
									<input type="text" id="cs-contact" class="form-control" placeholder="Nombre de Contacto">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="cs-search" class="btn btn-primary">Actualizar</button>
								</div>
							</div>
							<div id="cs-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Empresa</th>
											<th>Grupo</th>
											<th>Email</th>
											<th>Teléfono</th>
										</tr>
									</thead>
									<tbody id="cs-results">
										<tr><td colspan="4" class="text-center">Pulsa Actualizar para listar los clientes</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Buscar Cliente -->
			<!-- Modal búsqueda de artículos -->
			<div class="modal fade" tabindex="-1" role="dialog" id="ProductSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><i class="fa fa-search"></i> Búsqueda de artículos</h5>
							<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-12 col-sm-3 mb-2">
									<label class="d-block mb-1">Código / SKU</label>
									<input type="text" id="ps-sku" class="form-control" placeholder="Código...">
								</div>
								<div class="col-12 col-sm mb-2">
									<label class="d-block mb-1">Descripción</label>
									<input type="text" id="ps-name" class="form-control" placeholder="Descripción...">
								</div>
								<div class="col-12 col-sm-auto mb-2 d-flex align-items-end">
									<button class="btn btn-primary w-100" id="ps-search" type="button">
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
											<th class="text-right">Precio</th>
											<th class="text-right">Stock</th>
										</tr>
									</thead>
									<tbody id="ps-tbody">
										<tr><td colspan="4" class="text-center text-muted py-3">Introduzca criterios y pulse <strong>Actualizar</strong></td></tr>
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
								<li class="nav-item"><a href="#modal-tab-customer" class="nav-link" data-toggle="tab">Customer</a></li>
								<li class="nav-item"><a href="#tab-payment" class="nav-link" data-toggle="tab">Payment</a></li>
								<li class="nav-item"><a href="#tab-shipping" class="nav-link" data-toggle="tab">Shipping</a></li>
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
		alert("Debe indicar un cliente");
		return false;
	}

	// Products
	if (!$("#product-row0").length > 0) {
		alert("Inserte al menos un producto");
		return false;
	}
	
	// Shipping
	var ship = $('#shipping').val();
	if (ship=="") {
		alert("Seleccione un método de envío");
		return false;
	}

	// Payment
	var pay = $('#payment').val();
	if (pay==""){
		alert("Seleccione una forma de pago");
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
    $(this).find('#pm-discount').val('');
    $(this).find('#pm-tax-rate').val('');
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

var csCustomers = [];

$('#searchCustomer').click(function(e) {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('CustomerSearchModal')).show();
});

$('#CustomerSearchModal').on('shown.bs.modal', function() {
	$('#cs-company').focus();
});

function csDoSearch() {
	var btn = $('#cs-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#cs-results').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

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
				$('#cs-results').html('<tr><td colspan="4" class="text-center">No se encontraron clientes</td></tr>');
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
			$('#cs-warning').text('Error al buscar clientes').show();
			$('#cs-results').html('<tr><td colspan="4" class="text-center">Error al buscar clientes</td></tr>');
			csCustomers = [];
		},
		complete: function() {
			btn.prop('disabled', false).html('Actualizar');
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
	$('#cs-results').html('<tr><td colspan="4" class="text-center">Pulsa Actualizar para listar los clientes</td></tr>');
	$('#cs-warning').hide();
	csCustomers = [];
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

    var btn = $(this);
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');

    $.ajax({
        url: 'index.php?route=catalog/product/searchProducts&token=' + token +
             '&filter_sku='   + encodeURIComponent(sku) +
             '&filter_name='  + encodeURIComponent(name),
        dataType: 'json',
        success: function(json) {
            btn.prop('disabled', false).html('<i class="fa fa-search"></i> Actualizar');
            if (json && json.warning) {
                psProducts = [];
                $('#ps-tbody').html('<tr><td colspan="4" class="text-center text-warning py-3"><i class="fa fa-exclamation-triangle"></i> ' + json.warning + '</td></tr>');
                return;
            }
            psProducts = json || [];
            var html = '';
            if (psProducts.length === 0) {
                html = '<tr><td colspan="4" class="text-center text-muted py-3">No se encontraron artículos</td></tr>';
            } else {
                $.each(psProducts, function(i, p) {
                    html += '<tr style="cursor:pointer" data-idx="' + i + '">';
                    html += '<td>' + psEsc(p.sku)  + '</td>';
                    html += '<td>' + psEsc(p.name) + '</td>';
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
$('#ps-sku,#ps-name').on('keypress', function(e) {
    if (e.which === 13) { $('#ps-search').trigger('click'); }
});

$('#ProductSearchModal').on('shown.bs.modal', function() {
    $('#ps-name').focus();
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
    $('#pm-discount').val('');
    $('#pm-tax-rate').val((p.tax_rate !== undefined && p.tax_rate !== null) ? p.tax_rate : '');

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

function orderMarkPriceChanged(input) {
	var $input = $(input);
	var current = parseFloat($input.val().replace(',', '.')) || 0;
	var original = parseFloat($input.data('catalog-price')) || 0;

	$input.toggleClass('order-price-changed', current.toFixed(2) !== original.toFixed(2));
}

$(document).on('input', '.order-price', function() {
	orderMarkPriceChanged(this);
});

$(document).on('change', '.order-qty, .order-price, .order-discount', function() {
	orderMarkPriceChanged(this);
	$('#button-order-product').click();
});

$(document).on('input', '.order-discount', function() {
	var value = $(this).val().replace(/[^0-9.]/g, '');
	var parts = value.split('.');
	if (parts.length > 2) {
		value = parts[0] + '.' + parts.slice(1).join('');
	}
	$(this).val(value);
});

$('#pm-discount').on('input', function() {
	var value = $(this).val().replace(/[^0-9.]/g, '');
	var parts = value.split('.');
	if (parts.length > 2) {
		value = parts[0] + '.' + parts.slice(1).join('');
	}
	$(this).val(value);
});

$('.order-price').each(function() {
	orderMarkPriceChanged(this);
});

$('#global_discount').on('input', function() {
	var value = $(this).val().replace(/[^0-9.]/g, '');
	var parts = value.split('.');
	if (parts.length > 2) {
		value = parts[0] + '.' + parts.slice(1).join('');
	}
	$(this).val(value);
});

$('#global_discount').on('change', function() {
	$('#button-order-product').click();
});
</script>
<style>
.order-price-changed {
	color: #b30000;
	font-weight: bold;
	border-color: #b30000;
	background-color: #fdf0f0;
}
</style>
<?php echo $footer; ?>