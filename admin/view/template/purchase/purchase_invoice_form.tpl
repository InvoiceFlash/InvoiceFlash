<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
	<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo $heading_title; ?> <?php echo ($invoice_id) ? 'Nº ' . $invoice_id : ''; ?></div>
	<div class="pull-right">
		<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
		<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
	</div>
</div>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" onsubmit="return validateForm();" class="form-inline" enctype="multipart/form-data" id="form">
			<div class="card" id="tab-customer" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_supplier; ?>
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
						<div class="form-group col-sm-3">
							<label class="control-label col-sm-5"><?php echo $entry_store; ?></label>
							<div class="control-field col-sm-7">
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
						<div class="form-group col-sm-6">
							<label class="control-label col-sm-3"><?php echo $entry_supplier; ?></label>
							<div class="control-field input-group col-sm-9">
								<input type="text" name="company" value="<?php echo $company; ?>" id="purchase-supplier" autocomplete="off" class="form-control">
								<input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo $supplier_id; ?>">
								<div class="input-group-append"><button class="btn btn-default" type="button" id="searchSupplier" title="Buscar Proveedor"><i class="fa fa-search"></i></button></div>
								<div class="input-group-append"><button class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#CustomerModal"><i class="fa fa-eye"></i></button></div>
							</div>
						</div>
						<div class="form-group col-sm-3">
							<label class="control-label col-sm-6"><?php echo $entry_supplier_invoice_no; ?></label>
							<div class="control-field input-group col-sm-6">
								<input type="text" name="supplier_invoice_no" value="<?php echo $supplier_invoice_no; ?>" class="form-control">
								<?php if ($invoice_id) { ?>
								<div class="input-group-append">
									<label id="btn-upload-doc" class="btn btn-<?php echo $doc_exists ? 'success' : 'default'; ?> mb-0" title="Subir factura del proveedor" style="cursor:pointer;">
										<i class="fa fa-paperclip"></i>
										<input type="file" id="input-doc" style="display:none">
									</label>
								</div>
								<div class="input-group-append" style="margin-left:4px;">
									<a id="btn-view-doc" href="<?php echo str_replace('&amp;', '&', $view_doc_url); ?>" target="_blank"
									   class="btn btn-<?php echo $doc_exists ? 'info' : 'default'; ?> mb-0"
									   title="Ver factura del proveedor"
									   <?php if (!$doc_exists) { ?>style="pointer-events:none;opacity:.5;"<?php } ?>>
										<i class="fa fa-eye"></i>
									</a>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-3">
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
						<div class="form-group col-sm-3">
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
						<div class="form-group col-sm-3">
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
						<div class="form-group col-sm-3 d-flex align-items-center">
							<label class="control-label text-nowrap mb-0 pr-1"><?php echo $entry_global_discount; ?></label>
							<div class="control-field">
								<input type="text" name="global_discount" id="global_discount" value="<?php echo $global_discount; ?>" class="form-control text-right" inputmode="decimal" style="width:70px;">
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
								<th class="d-none d-sm-table-cell"><?php echo $column_model; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_discount; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
						</tr>
						</thead>
						<?php $product_row = 0; ?>
						<?php $option_row = 0; ?>
						<tbody id="product">
							<?php if ($invoice_products) { ?>
							<?php foreach ($invoice_products as $invoice_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-purchase-invoice-product').click();"><i class="fa fa-trash"></i></a></td>
								<td><?php echo $invoice_product['name']; ?><br>
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_product_id]" value="<?php echo $invoice_product['invoice_product_id']; ?>">
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $invoice_product['product_id']; ?>">
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][name]" value="<?php echo $invoice_product['name']; ?>">
									<?php foreach ($invoice_product['option'] as $option) { ?>
										<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
										<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_option][<?php echo $option_row; ?>][invoice_option_id]" value="<?php echo $option['invoice_option_id']; ?>">
										<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_option][<?php echo $option_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>">
										<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_option][<?php echo $option_row; ?>][product_option_value_id]" value="<?php echo $option['product_option_value_id']; ?>">
										<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_option][<?php echo $option_row; ?>][name]" value="<?php echo $option['name']; ?>">
										<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_option][<?php echo $option_row; ?>][value]" value="<?php echo $option['value']; ?>">
										<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][invoice_option][<?php echo $option_row; ?>][type]" value="<?php echo $option['type']; ?>">
									<?php $option_row++; ?>
									<?php } ?>
								</td>
								<td class="d-none d-sm-table-cell"><?php echo $invoice_product['model']; ?>
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][model]" value="<?php echo $invoice_product['model']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right pi-qty" name="invoice_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $invoice_product['quantity']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right pi-price" data-catalog-price="<?php echo $invoice_product['catalog_price_raw']; ?>" name="invoice_product[<?php echo $product_row; ?>][price]" value="<?php echo $invoice_product['price_raw']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right pi-discount" name="invoice_product[<?php echo $product_row; ?>][discount]" value="<?php echo $invoice_product['discount_raw']; ?>"></td>
								<td class="text-right"><?php echo $invoice_product['total']; ?>
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][total]" value="<?php echo $invoice_product['total']; ?>">
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][tax]" value="<?php echo $invoice_product['tax']; ?>"></td>
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
							<?php if ($invoice_totals) { ?>
							<?php foreach ($invoice_totals as $invoice_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="5"><?php echo $invoice_total['title']; ?>:
									<input type="hidden" name="invoice_total[<?php echo $total_row; ?>][invoice_total_id]" value="<?php echo $invoice_total['invoice_total_id']; ?>">
									<input type="hidden" name="invoice_total[<?php echo $total_row; ?>][code]" value="<?php echo $invoice_total['code']; ?>">
									<input type="hidden" name="invoice_total[<?php echo $total_row; ?>][title]" value="<?php echo $invoice_total['title']; ?>">
									<input type="hidden" name="invoice_total[<?php echo $total_row; ?>][text]" value="<?php echo $invoice_total['text']; ?>">
									<input type="hidden" name="invoice_total[<?php echo $total_row; ?>][value]" value="<?php echo $invoice_total['value']; ?>">
									<input type="hidden" name="invoice_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $invoice_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $invoice_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal Buscar Proveedor -->
			<div class="modal" tabindex="-1" role="dialog" id="SupplierSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Proveedor</h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm">
									<label class="control-label">Empresa / Nombre</label>
									<input type="text" id="ss-company" class="form-control" placeholder="Empresa / Nombre">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="ss-search" class="btn btn-primary">Actualizar</button>
								</div>
							</div>
							<div id="ss-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Empresa</th>
											<th>Email</th>
											<th>Teléfono</th>
										</tr>
									</thead>
									<tbody id="ss-results">
										<tr><td colspan="3" class="text-center">Pulsa Actualizar para listar los proveedores</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Buscar Proveedor -->
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
			<div class="modal" tab-index="-1" role="dialog" id="PurchaseInvoiceProductModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="pm-product-name"><?php echo $text_product; ?></h5>
							<button class="close" data-bs-dismiss="modal" arial-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<input type="hidden" name="product_id" id="product_id" value="0">
							<input type="hidden" name="product" id="invoice-product" value="">
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
							</div>
						</div>
						<div class="modal-footer">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-purchase-invoice-product" class="btn btn-info pull-right">
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
							<h5 class="modal-title"><?php echo $tab_supplier; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<ul class="nav nav-tabs">
								<li class="nav-item"><a href="#modal-tab-supplier" class="nav-link active" data-bs-toggle="tab">Supplier</a></li>
								<li class="nav-item"><a href="#tab-payment" class="nav-link" data-bs-toggle="tab">Payment</a></li>
								<li class="nav-item"><a href="#tab-shipping" class="nav-link" data-bs-toggle="tab">Shipping</a></li>
							</ul>
							<div class="tab-content mt-2">
								<div class="tab-pane active" id="modal-tab-supplier">
									<div class="form-horizontal">
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_email; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-3"><?php echo $entry_telephone; ?></label>
											<div class="control-field col-sm-8">
												<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
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
<script>
function validateForm(){

	// Supplier
	if ($('#supplier_id').val() == 0) {
		alert("Debe indicar un proveedor");
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
$('#PurchaseInvoiceProductModal').on('hidden.bs.modal', function () {
    $(this).find('#invoice-product').val('');
    $(this).find('#product_id').val(0);
    $(this).find('#pm-quantity').val(1);
    $(this).find('#price_override').val('');
    $(this).find('#pm-discount').val('');
    $(this).find('#pm-product-name').text('<?php echo $text_product; ?>');
    $(this).find('#option').html('');
});
$('#addProduct').click(function(e){
	if($('#supplier_id').val()==0){
		alert('Por favor, seleccione un proveedor primero');
		$('#purchase-supplier').focus();
	} else {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductSearchModal')).show();
	}
});

var psProducts = [];
var psToday = (function() {
	var d = new Date();
	return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
})();

function psDoSearch() {
	var btn = $('#ps-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#ps-results').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

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
		},
		error: function(jqXHR, textStatus) {
			var msg = (textStatus === 'parsererror') ? 'Tu sesi&oacute;n ha caducado. Recarga la p&aacute;gina e inicia sesi&oacute;n de nuevo.' : 'Error al buscar productos';
			$('#ps-warning').text(msg).show();
			$('#ps-results').html('<tr><td colspan="5" class="text-center">' + msg + '</td></tr>');
			psProducts = [];
		},
		complete: function() {
			btn.prop('disabled', false).html('Actualizar');
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
	$('#invoice-product').val(p.name);
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
	bootstrap.Modal.getOrCreateInstance(document.getElementById('PurchaseInvoiceProductModal')).show();
});

$('#button-purchase-invoice-product').on('click', function() {
	var a = $(this);
	var data = '#tab-customer input[type="text"],#tab-customer input[type="hidden"],';
	data += '#PurchaseInvoiceProductModal input[type="text"],#PurchaseInvoiceProductModal input[type="hidden"],#PurchaseInvoiceProductModal input[type="radio"]:checked,#PurchaseInvoiceProductModal input[type="checkbox"]:checked,#PurchaseInvoiceProductModal select,#PurchaseInvoiceProductModal textarea,';
	data += '#product input[type="text"],#product input[type="hidden"]';
	var ajaxData = $.param($(data));
	var $productModal = $('#PurchaseInvoiceProductModal');
	if ($productModal.length && $productModal.hasClass('show')) {
		bootstrap.Modal.getInstance($productModal[0]).hide();
	}
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('purchase/invoice/checkInvoice', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: ajaxData,
		dataType: 'json',
		beforeSend: function() {
			$('.alert,.text-error').remove();
			a.button('loading');
		},
		success: function(json) {
			if (json.error && json.error.product && json.error.product.option) {
				for (var i in json.error.product.option) {
					$('#option-' + i + ' .control-field').append('<div class="help-block text-danger">' + json.error.product.option[i] + '</div>');
				}
			}
			if (json.invoice_product) {
				var product_row = 0, option_row = 0, html = '';
				for (var i = 0; i < json.invoice_product.length; i++) {
					var product = json.invoice_product[i];
					html += '<tr id="product-row' + product_row + '">';
					html += '<td class="text-center"><a class="label label-danger" title="' + $('#button_remove').val() + '" onclick="$(\'#product-row' + product_row + '\').remove();$(\'#button-purchase-invoice-product\').click();"><i class="fa fa-trash"></i></a></td>';
					html += '<td>' + product.name + '<br><input type="hidden" name="invoice_product[' + product_row + '][invoice_product_id]" value=""><input type="hidden" name="invoice_product[' + product_row + '][product_id]" value="' + product.product_id + '"><input type="hidden" name="invoice_product[' + product_row + '][name]" value="' + product.name + '">';
					if (product.option) {
						for (var j = 0; j < product.option.length; j++) {
							var option = product.option[j];
							html += '<div class="help">' + option.name + ': ' + option.value + '</div>';
							html += '<input type="hidden" name="invoice_product[' + product_row + '][invoice_option][' + option_row + '][invoice_option_id]" value="">';
							html += '<input type="hidden" name="invoice_product[' + product_row + '][invoice_option][' + option_row + '][product_option_id]" value="' + option.product_option_id + '">';
							html += '<input type="hidden" name="invoice_product[' + product_row + '][invoice_option][' + option_row + '][name]" value="' + option.name + '">';
							html += '<input type="hidden" name="invoice_product[' + product_row + '][invoice_option][' + option_row + '][value]" value="' + option.value + '">';
							html += '<input type="hidden" name="invoice_product[' + product_row + '][invoice_option][' + option_row + '][type]" value="' + option.type + '">';
							option_row++;
						}
					}
					html += '</td>';
					html += '<td class="d-none d-sm-table-cell">' + product.model + '<input type="hidden" name="invoice_product[' + product_row + '][model]" value="' + product.model + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right pi-qty" name="invoice_product[' + product_row + '][quantity]" value="' + product.quantity + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right pi-price" data-catalog-price="' + product.catalog_price_raw + '" name="invoice_product[' + product_row + '][price]" value="' + product.price_raw + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right pi-discount" name="invoice_product[' + product_row + '][discount]" value="' + (product.discount != null ? product.discount : '') + '"></td>';
					html += '<td class="text-right">' + product.total + '<input type="hidden" name="invoice_product[' + product_row + '][total]" value="' + product.total + '"><input type="hidden" name="invoice_product[' + product_row + '][tax]" value=""></td>';
					html += '</tr>';
					product_row++;
				}
				$('#product').html(html);
			}
			if (json.invoice_total) {
				var total_row = 0, html2 = '';
				for (var i in json.invoice_total) {
					var total = json.invoice_total[i];
					html2 += '<tr id="total-row' + total_row + '">';
					html2 += '<td class="d-none d-sm-table-cell"></td><td class="text-right" colspan="5"><input type="hidden" name="invoice_total[' + total_row + '][invoice_total_id]" value=""><input type="hidden" name="invoice_total[' + total_row + '][code]" value="' + total.code + '"><input type="hidden" name="invoice_total[' + total_row + '][title]" value="' + total.title + '"><input type="hidden" name="invoice_total[' + total_row + '][text]" value="' + total.text + '"><input type="hidden" name="invoice_total[' + total_row + '][value]" value="' + total.value + '"><input type="hidden" name="invoice_total[' + total_row + '][sort_order]" value="' + total.sort_order + '">' + total.title + ':</td>';
					html2 += '<td class="text-right">' + total.text + '</td>';
					html2 += '</tr>';
					total_row++;
				}
				$('#total').html(html2);
			}
		},
		error: function(jqXHR, textStatus) {
			var msg = (textStatus === 'parsererror') ? 'Tu sesión ha caducado. Recarga la página e inicia sesión de nuevo.' : 'Error al añadir el producto';
			alert(msg);
		},
		complete: function() {
			a.button('reset');
		}
	});
});

$('#ProductSearchModal').on('hidden.bs.modal', function() {
	$('#ps-sku, #ps-name, #ps-model').val('');
	$('#ps-results').html('<tr><td colspan="5" class="text-center">Use los filtros para buscar productos</td></tr>');
	$('#ps-warning').hide();
	psProducts = [];
});

function piMarkPriceChanged(input) {
	var $input = $(input);
	var current = parseFloat($input.val().replace(',', '.')) || 0;
	var original = parseFloat($input.data('catalog-price')) || 0;

	$input.toggleClass('pi-price-changed', current.toFixed(2) !== original.toFixed(2));
}

$(document).on('input', '.pi-price', function() {
	piMarkPriceChanged(this);
});

$(document).on('change', '.pi-qty, .pi-price, .pi-discount', function() {
	piMarkPriceChanged(this);
	$('#button-purchase-invoice-product').click();
});

$(document).on('input', '.pi-discount, #global_discount, #pm-discount', function() {
	this.value = this.value.replace(/[^0-9.]/g, '');
});

$('#global_discount').on('change', function() {
	$('#button-purchase-invoice-product').click();
});

$('.pi-price').each(function() {
	piMarkPriceChanged(this);
});
</script>
<style>
.pi-price-changed {
	color: #b30000;
	font-weight: bold;
	border-color: #b30000;
	background-color: #fdf0f0;
}
</style>
<script>
$(function(){
	var supplierMapped = {};
	$('#purchase-supplier').typeahead({
		source: function(q, process) {
			return $.getJSON('index.php?route=purchase/supplier/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(q), function(json) {
				var data = [];
				$.each(json, function(i, item) {
					supplierMapped[item.name] = item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater: function(item) {
			var s = supplierMapped[item];
			$('#supplier_id').val(s.supplier_id);
			$('input[name="email"]').val(s.email);
			$('input[name="telephone"]').val(s.telephone);
			$('input[name="fax"]').val(s.fax);
			return item;
		}
	});
});
</script>
<script>
var ssSuppliers = [];

$('#searchSupplier').click(function(e) {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('SupplierSearchModal')).show();
});

function ssDoSearch() {
	var btn = $('#ss-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#ss-results').html('<tr><td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('purchase/supplier/searchSuppliers', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_company: $('#ss-company').val() },
		dataType: 'json',
		success: function(json) {
			if (json.warning) {
				$('#ss-warning').text(json.warning).show();
				$('#ss-results').html('<tr><td colspan="3" class="text-center">' + json.warning + '</td></tr>');
				ssSuppliers = [];
				return;
			}
			$('#ss-warning').hide();
			ssSuppliers = json;
			if (!json.length) {
				$('#ss-results').html('<tr><td colspan="3" class="text-center">No se encontraron proveedores</td></tr>');
				return;
			}
			var html = '';
			for (var i = 0; i < json.length; i++) {
				html += '<tr data-idx="' + i + '" style="cursor:pointer;">';
				html += '<td>' + (json[i].company || '') + '</td>';
				html += '<td>' + (json[i].email || '') + '</td>';
				html += '<td>' + (json[i].telephone || '') + '</td>';
				html += '</tr>';
			}
			$('#ss-results').html(html);
		},
		error: function() {
			$('#ss-warning').text('Error al buscar proveedores').show();
			$('#ss-results').html('<tr><td colspan="3" class="text-center">Error al buscar proveedores</td></tr>');
			ssSuppliers = [];
		},
		complete: function() {
			btn.prop('disabled', false).html('Actualizar');
		}
	});
}

$('#ss-search').click(ssDoSearch);

$('#ss-company').on('keypress', function(e) {
	if (e.which == 13) ssDoSearch();
});

$(document).on('dblclick', '#ss-results tr[data-idx]', function() {
	var idx = parseInt($(this).data('idx'));
	var s = ssSuppliers[idx];

	$('#purchase-supplier').val(s.company);
	$('#supplier_id').val(s.supplier_id);
	$('input[name="email"]').val(s.email);
	$('input[name="telephone"]').val(s.telephone);
	$('input[name="fax"]').val(s.fax);

	bootstrap.Modal.getInstance(document.getElementById('SupplierSearchModal')).hide();
});

$('#SupplierSearchModal').on('hidden.bs.modal', function() {
	$('#ss-company').val('');
	$('#ss-results').html('<tr><td colspan="3" class="text-center">Pulsa Actualizar para listar los proveedores</td></tr>');
	$('#ss-warning').hide();
	ssSuppliers = [];
});
</script>
<?php if ($invoice_id) { ?>
<script>
$('#input-doc').on('change', function() {
	var file = this.files[0];
	if (!file) return;
	var formData = new FormData();
	formData.append('doc', file);
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $upload_doc_url); ?>',
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		dataType: 'json',
		success: function(json) {
			if (json && json.success) {
				$('#btn-upload-doc').removeClass('btn-default btn-danger').addClass('btn-success');
				$('#btn-view-doc').removeClass('btn-default').addClass('btn-info').css({pointerEvents: '', opacity: ''});
			} else {
				alert(json && json.error ? json.error : 'Upload error');
				$('#btn-upload-doc').removeClass('btn-default btn-success').addClass('btn-danger');
			}
		},
		error: function(xhr) {
			alert('Upload error (' + xhr.status + '): ' + xhr.responseText.substring(0, 300));
		}
	});
});
</script>
<?php } ?>
<?php echo $footer; ?>
