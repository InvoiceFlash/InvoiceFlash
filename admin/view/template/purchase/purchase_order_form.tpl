<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo $heading_title; ?> <?php echo ($purchase_order_id) ? 'Nº ' . $purchase_order_id : ''; ?></div>
		<div class="pull-right">
			<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" onsubmit="return validatePurchaseOrderForm();" class="form-inline" enctype="multipart/form-data" id="form">
			<div class="card mb-3" id="tab-supplier" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_supplier; ?>
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
							<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_supplier; ?></label>
							<div class="control-field col-sm-10">
								<input type="text" name="supplier" value="<?php echo $supplier; ?>" id="purchase-order-supplier" autocomplete="off" class="form-control">
								<input type="hidden" id="purchase_order_supplier_id" name="supplier_id" value="<?php echo $supplier_id; ?>">
								<?php if ($error_supplier) { ?>
									<div class="help-block text-danger"><?php echo $error_supplier; ?></div>
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
									<?php foreach ($shippings as $shipping) { ?>
										<option value="<?php echo $shipping['shipping_id']; ?>" <?php echo ($shipping['shipping_id'] == $shipping_code ? 'selected' : ''); ?>><?php echo $shipping['name']; ?></option>
									<?php } ?>
								</select>
								<input type="hidden" name="shipping_method" value="<?php echo $shipping_method; ?>">
								<input type="hidden" name="shipping_code" value="<?php echo $shipping_code; ?>">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_payment; ?></label>
							<div class="control-field col-sm-8">
								<select id="payment" name="payment" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($payments as $payment) { ?>
										<option value="<?php echo $payment['payment_id']; ?>" <?php echo ($payment['payment_id'] == $payment_code ? 'selected' : ''); ?>><?php echo $payment['name']; ?></option>
									<?php } ?>
								</select>
								<input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>">
								<input type="hidden" name="payment_code" value="<?php echo $payment_code; ?>">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_purchase_order_status; ?></label>
							<div class="control-field col-sm-8">
								<select name="purchase_order_status_id" class="form-control">
									<?php foreach ($purchase_order_statuses as $purchase_order_status) { ?>
									<?php if ($purchase_order_status['purchase_order_status_id'] == $purchase_order_status_id) { ?>
									<option value="<?php echo $purchase_order_status['purchase_order_status_id']; ?>" selected=""><?php echo $purchase_order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $purchase_order_status['purchase_order_status_id']; ?>"><?php echo $purchase_order_status['name']; ?></option>
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
					<?php echo $tab_product; ?>
					<button class="btn btn-info pull-right" type="button" id="addPurchaseOrderProduct"><i class="fa fa-plus-circle"></i> <span class="hidden-xs"><?php echo $button_add_product; ?></span></button>
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
						<tbody id="product">
							<?php if ($purchase_order_products) { ?>
							<?php foreach ($purchase_order_products as $purchase_order_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();"><i class="fa fa-trash"></i></a></td>
								<td><?php echo $purchase_order_product['name']; ?><br>
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][purchase_order_product_id]" value="<?php echo $purchase_order_product['purchase_order_product_id']; ?>">
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $purchase_order_product['product_id']; ?>">
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][name]" value="<?php echo $purchase_order_product['name']; ?>">
								</td>
								<td class="d-none d-sm-table-cell"><?php echo $purchase_order_product['model']; ?>
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][model]" value="<?php echo $purchase_order_product['model']; ?>"></td>
								<td class="text-right"><?php echo $purchase_order_product['quantity']; ?>
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $purchase_order_product['quantity']; ?>"></td>
								<td class="text-right"><?php echo $purchase_order_product['price']; ?>
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][price]" value="<?php echo $purchase_order_product['price']; ?>"></td>
								<td class="text-right"><?php echo $purchase_order_product['total']; ?>
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][total]" value="<?php echo $purchase_order_product['total']; ?>">
									<input type="hidden" name="purchase_order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $purchase_order_product['tax']; ?>"></td>
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
							<?php if ($purchase_order_totals) { ?>
							<?php foreach ($purchase_order_totals as $purchase_order_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="4"><?php echo $purchase_order_total['title']; ?>:
									<input type="hidden" name="purchase_order_total[<?php echo $total_row; ?>][purchase_order_total_id]" value="<?php echo $purchase_order_total['purchase_order_total_id']; ?>">
									<input type="hidden" name="purchase_order_total[<?php echo $total_row; ?>][code]" value="<?php echo $purchase_order_total['code']; ?>">
									<input type="hidden" name="purchase_order_total[<?php echo $total_row; ?>][title]" value="<?php echo $purchase_order_total['title']; ?>">
									<input type="hidden" name="purchase_order_total[<?php echo $total_row; ?>][text]" value="<?php echo $purchase_order_total['text']; ?>">
									<input type="hidden" name="purchase_order_total[<?php echo $total_row; ?>][value]" value="<?php echo $purchase_order_total['value']; ?>">
									<input type="hidden" name="purchase_order_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $purchase_order_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $purchase_order_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal Product -->
			<div class="modal" tab-index="-1" role="dialog" id="PurchaseOrderProductModal">
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
										<input type="text" name="product" value="" id="purchase-order-product" class="form-control" autocomplete="off">
										<input type="hidden" name="product_id" id="purchase_order_product_id" value="" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_quantity; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" id="purchase_order_quantity" name="quantity" value="1" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-purchase_order-product" class="btn btn-info pull-right">
									<i class="fa fa-plus-circle"></i> <span class="hidden-xs"><?php echo $button_add_product; ?></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Product -->
		</form>
	</div>
</div>
<input type="hidden" id="text_none" value="<?php echo $text_none; ?>">
<input type="hidden" id="text_select" value="<?php echo $text_select; ?>">
<input type="hidden" id="text_no_results" value="<?php echo $text_no_results; ?>">
<input type="hidden" id="button_remove" value="<?php echo $button_remove; ?>">
<script>
function validatePurchaseOrderForm(){
	var supplier = $('#purchase_order_supplier_id');
	if (!supplier.val() || supplier.val() == '0') {
		alert('Please select a supplier');
		$('#purchase-order-supplier').focus();
		return false;
	}

	if (!$("#product-row0").length > 0) {
		alert('Insert at least one Product');
		return false;
	}
}
$('#PurchaseOrderProductModal').on('hidden.bs.modal', function () {
	$(this).find('#purchase-order-product').val('').end();
	$(this).find('#purchase_order_product_id').val(0);
});
$('#addPurchaseOrderProduct').click(function(e){
	bootstrap.Modal.getOrCreateInstance(document.getElementById('PurchaseOrderProductModal')).show();
});
</script>
<?php echo $footer; ?>
