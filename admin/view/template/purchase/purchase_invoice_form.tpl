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
								<?php } ?>
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
							<?php if ($invoice_products) { ?>
							<?php foreach ($invoice_products as $invoice_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();$('#button-invoice-product').click();"><i class="fa fa-trash"></i></a></td>
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
								<td class="text-right"><?php echo $invoice_product['quantity']; ?>
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $invoice_product['quantity']; ?>"></td>
								<td class="text-right"><?php echo $invoice_product['price']; ?>
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][price]" value="<?php echo $invoice_product['price']; ?>"></td>
								<td class="text-right"><?php echo $invoice_product['total']; ?>
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][total]" value="<?php echo $invoice_product['total']; ?>">
									<input type="hidden" name="invoice_product[<?php echo $product_row; ?>][tax]" value="<?php echo $invoice_product['tax']; ?>"></td>
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
							<?php if ($invoice_totals) { ?>
							<?php foreach ($invoice_totals as $invoice_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="4"><?php echo $invoice_total['title']; ?>:
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
										<input type="text" name="product" value="" id="invoice-product" class="form-control" autocomplete="off">
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
								<button type="button" id="button-invoice-product" class="btn btn-info pull-right">
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
		alert("Supplier must be filled out");
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
$('#ProductModal').on('hidden.bs.modal', function () {
    $(this).find("#invoice-product").val('').end();
    $(this).find("#product_id").val(0);
	$(this).find("#option").html('');
});
$('#addProduct').click(function(e){
	if($('#supplier_id').val()==0){
		alert('Please, select a supplier first');
		$('#purchase-supplier').focus();
	} else {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductModal')).show();
	}
});
</script>
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
<?php if ($invoice_id) { ?>
<script>
$('#input-doc').on('change', function() {
	var file = this.files[0];
	if (!file) return;
	var formData = new FormData();
	formData.append('doc', file);
	$.ajax({
		url: '<?php echo $upload_doc_url; ?>',
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function(json) {
			if (json.success) {
				$('#btn-upload-doc').removeClass('btn-default btn-danger').addClass('btn-success');
			} else {
				alert(json.error || 'Upload error');
				$('#btn-upload-doc').removeClass('btn-default btn-success').addClass('btn-danger');
			}
		},
		error: function() {
			alert('Upload error');
		}
	});
});
</script>
<?php } ?>
<?php echo $footer; ?>
