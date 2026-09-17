<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-dolly"></i> <?php echo $heading_title; ?> <?php echo ($reception_id) ? 'Nº ' . $reception_id : ''; ?></div>
		<div class="pull-right">
			<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" onsubmit="return validateReceptionForm();" class="form-inline" enctype="multipart/form-data" id="form">
			<input type="hidden" name="purchase_order_id" id="reception_purchase_order_id" value="<?php echo $purchase_order_id; ?>">
			<div class="card mb-3" id="tab-reception" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_reception; ?>
					<button class="btn btn-warning pull-right" type="button" title="Pedidos Pendientes de Recibir" onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('PurchaseOrderSearchModal')).show();"><i class="fa fa-list-alt"></i> <span class="hidden-xs">Pedidos Pendientes</span></button>
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
								<div class="input-group">
									<input type="text" name="supplier" value="<?php echo $supplier; ?>" id="reception-supplier" autocomplete="off" class="form-control">
									<input type="hidden" id="reception_supplier_id" name="supplier_id" value="<?php echo $supplier_id; ?>">
									<div class="input-group-append"><button class="btn btn-default" type="button" id="searchSupplier" title="Buscar Proveedor"><i class="fa fa-search"></i></button></div>
									<div class="input-group-append"><button class="btn btn-info" type="button" id="viewSupplier" title="<?php echo $text_supplier_details; ?>"><i class="fa fa-eye"></i></button></div>
								</div>
								<?php if ($error_supplier) { ?>
									<div class="help-block text-danger"><?php echo $error_supplier; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-5"><?php echo $entry_supplier_delivery_no; ?></label>
							<div class="control-field col-sm-7">
								<input type="text" name="supplier_delivery_no" value="<?php echo $supplier_delivery_no; ?>" class="form-control">
							</div>
						</div>
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
					</div>
					<div class="row">
						<?php if ($reception_id) { ?>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_reception_status; ?></label>
							<div class="control-field col-sm-8">
								<select name="reception_status_id" class="form-control">
									<?php foreach ($reception_statuses as $reception_status) { ?>
									<?php if ($reception_status['reception_status_id'] == $reception_status_id) { ?>
									<option value="<?php echo $reception_status['reception_status_id']; ?>" selected=""><?php echo $reception_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $reception_status['reception_status_id']; ?>"><?php echo $reception_status['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<?php } ?>
						<div class="form-group col-sm-3 d-flex align-items-center">
							<label class="control-label text-nowrap mb-0 pr-1"><?php echo $entry_global_discount; ?></label>
							<div class="control-field">
								<input type="text" name="global_discount" id="global_discount" value="<?php echo $global_discount; ?>" class="form-control text-right" inputmode="decimal" style="width:70px;">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-12">
							<label class="control-label col-sm-2"><?php echo $entry_comment; ?></label>
							<div class="control-field col-sm-10">
								<textarea name="comment" class="form-control" rows="2" style="width:100%;"><?php echo $comment; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card" id="tab-product" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_product; ?>
					<button class="btn btn-info pull-right" type="button" id="addReceptionProduct"><i class="fa fa-plus-circle"></i> <span class="hidden-xs"><?php echo $button_add_product; ?></span></button>
				</div>
				<div class="card-body">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th><?php echo $column_product; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_discount; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
						</tr>
						</thead>
						<?php $product_row = 0; ?>
						<tbody id="product">
							<?php if ($reception_products) { ?>
							<?php foreach ($reception_products as $reception_product) { ?>
							<tr id="product-row<?php echo $product_row; ?>">
								<td class="text-center"><a class="label label-danger" title="<?php echo $button_remove; ?>" onclick="$('#product-row<?php echo $product_row; ?>').remove();"><i class="fa fa-trash"></i></a></td>
								<td><?php echo $reception_product['name']; ?><br>
									<input type="hidden" name="reception_product[<?php echo $product_row; ?>][reception_product_id]" value="<?php echo $reception_product['reception_product_id']; ?>">
									<input type="hidden" name="reception_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $reception_product['product_id']; ?>">
									<input type="hidden" name="reception_product[<?php echo $product_row; ?>][name]" value="<?php echo $reception_product['name']; ?>">
									<input type="hidden" name="reception_product[<?php echo $product_row; ?>][model]" value="<?php echo $reception_product['model']; ?>">
								</td>
								<td class="text-right"><input type="text" class="form-control text-right rc-qty" name="reception_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $reception_product['quantity']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right rc-price" data-catalog-price="<?php echo $reception_product['catalog_price_raw']; ?>" name="reception_product[<?php echo $product_row; ?>][price]" value="<?php echo $reception_product['price_raw']; ?>"></td>
								<td class="text-right"><input type="text" class="form-control text-right rc-discount" name="reception_product[<?php echo $product_row; ?>][discount]" value="<?php echo $reception_product['discount_raw']; ?>"></td>
								<td class="text-right"><?php echo $reception_product['total']; ?>
									<input type="hidden" name="reception_product[<?php echo $product_row; ?>][total]" value="<?php echo $reception_product['total']; ?>">
									<input type="hidden" name="reception_product[<?php echo $product_row; ?>][tax]" value="<?php echo $reception_product['tax']; ?>"></td>
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
							<?php if ($reception_totals) { ?>
							<?php foreach ($reception_totals as $reception_total) { ?>
							<tr id="total-row<?php echo $total_row; ?>">
								<td class="d-none d-sm-table-cell"></td>
								<td class="text-right" colspan="4"><?php echo $reception_total['title']; ?>:
									<input type="hidden" name="reception_total[<?php echo $total_row; ?>][reception_total_id]" value="<?php echo $reception_total['reception_total_id']; ?>">
									<input type="hidden" name="reception_total[<?php echo $total_row; ?>][code]" value="<?php echo $reception_total['code']; ?>">
									<input type="hidden" name="reception_total[<?php echo $total_row; ?>][title]" value="<?php echo $reception_total['title']; ?>">
									<input type="hidden" name="reception_total[<?php echo $total_row; ?>][text]" value="<?php echo $reception_total['text']; ?>">
									<input type="hidden" name="reception_total[<?php echo $total_row; ?>][value]" value="<?php echo $reception_total['value']; ?>">
									<input type="hidden" name="reception_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $reception_total['sort_order']; ?>"></td>
								<td class="text-right"><?php echo $reception_total['text']; ?></td>
							</tr>
							<?php $total_row++; ?>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Modal Pedidos Pendientes -->
			<div class="modal" tabindex="-1" role="dialog" id="PurchaseOrderSearchModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Pedidos Pendientes de Recibir</h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="row g-2 mb-3">
								<div class="col-12 col-sm-3">
									<label class="control-label">Nº Pedido</label>
									<input type="text" id="pos-purchase-order-id" class="form-control" placeholder="Nº pedido">
								</div>
								<div class="col-12 col-sm">
									<label class="control-label">Proveedor</label>
									<input type="text" id="pos-supplier" class="form-control" placeholder="Proveedor">
								</div>
								<div class="col-12 col-sm-auto d-flex align-items-end">
									<button type="button" id="pos-search" class="btn btn-primary">Actualizar</button>
								</div>
							</div>
							<div id="pos-warning" class="alert alert-warning" style="display:none;"></div>
							<div style="overflow-x:auto;">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Nº</th>
											<th>Proveedor</th>
											<th>Fecha</th>
											<th class="text-right">Total</th>
											<th>Estado</th>
										</tr>
									</thead>
									<tbody id="pos-results">
										<tr><td colspan="5" class="text-center">Use los filtros para buscar pedidos</td></tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Pedidos Pendientes -->
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
			<!-- Modal Datos del Proveedor -->
			<div class="modal" tabindex="-1" role="dialog" id="SupplierDetailsModal">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo $text_supplier_details; ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_supplier; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-company"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_tax_id; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-tax-id"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_email; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-email"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_telephone; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-telephone"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_fax; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-fax"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_address_1; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-address-1"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_address_2; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-address-2"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_city; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-city"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_postcode; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-postcode"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_zone; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-zone"></p></div>
								</div>
								<div class="form-group">
									<label class="control-label col-3"><?php echo $entry_country; ?></label>
									<div class="control-field col-sm-8"><p class="form-control-static" id="sd-country"></p></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Fin Modal Datos del Proveedor -->
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
			<div class="modal" tab-index="-1" role="dialog" id="ReceptionProductModal">
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
										<input type="text" name="product" value="" id="reception-product" class="form-control" autocomplete="off">
										<input type="hidden" name="product_id" id="reception_product_id" value="" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_quantity; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" id="reception_quantity" name="quantity" value="1" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4"><?php echo $entry_discount; ?></label>
									<div class="control-field col-sm-8">
										<input type="text" id="pm-discount" name="discount" value="" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-reception-product" class="btn btn-info pull-right">
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
var token = '<?php echo $token; ?>';

function validateReceptionForm(){
	var supplier = $('#reception_supplier_id');
	if (!supplier.val() || supplier.val() == '0') {
		alert('Por favor, seleccione un proveedor');
		$('#reception-supplier').focus();
		return false;
	}

	if (!$("#product-row0").length > 0) {
		alert('Inserte al menos un producto');
		return false;
	}
}

var ssSuppliers = [];

$('#searchSupplier').click(function(e) {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('SupplierSearchModal')).show();
});

$('#viewSupplier').click(function(e) {
	var supplierId = $('#reception_supplier_id').val();

	if (!supplierId || supplierId == '0') {
		alert('Por favor, seleccione un proveedor primero');
		return;
	}

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('purchase/supplier/getDetails', 'token=' . $this->session->data['token'], 'SSL')); ?>&supplier_id=' + supplierId,
		type: 'get',
		dataType: 'json',
		success: function(json) {
			if (json.error) {
				alert('No se han podido cargar los datos del proveedor');
				return;
			}
			$('#sd-company').text(json.company || '');
			$('#sd-tax-id').text(json.tax_id || '');
			$('#sd-email').text(json.email || '');
			$('#sd-telephone').text(json.telephone || '');
			$('#sd-fax').text(json.fax || '');
			$('#sd-address-1').text(json.address_1 || '');
			$('#sd-address-2').text(json.address_2 || '');
			$('#sd-city').text(json.city || '');
			$('#sd-postcode').text(json.postcode || '');
			$('#sd-zone').text(json.zone || '');
			$('#sd-country').text(json.country || '');

			bootstrap.Modal.getOrCreateInstance(document.getElementById('SupplierDetailsModal')).show();
		},
		error: function() {
			alert('No se han podido cargar los datos del proveedor');
		}
	});
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

	$('#reception-supplier').val(s.company);
	$('#reception_supplier_id').val(s.supplier_id);

	bootstrap.Modal.getInstance(document.getElementById('SupplierSearchModal')).hide();
});

$('#SupplierSearchModal').on('hidden.bs.modal', function() {
	$('#ss-company').val('');
	$('#ss-results').html('<tr><td colspan="3" class="text-center">Pulsa Actualizar para listar los proveedores</td></tr>');
	$('#ss-warning').hide();
	ssSuppliers = [];
});

function posDoSearch() {
	var btn = $('#pos-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#pos-results').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('purchase/reception/searchPurchaseOrders', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_purchase_order_id: $('#pos-purchase-order-id').val(), filter_supplier: $('#pos-supplier').val() },
		dataType: 'json',
		success: function(json) {
			$('#pos-warning').hide();
			if (!json || !json.length) {
				$('#pos-results').html('<tr><td colspan="5" class="text-center">No se encontraron pedidos</td></tr>');
				return;
			}
			var html = '';
			for (var i = 0; i < json.length; i++) {
				html += '<tr data-purchase-order-id="' + json[i].purchase_order_id + '" style="cursor:pointer;">';
				html += '<td>' + json[i].purchase_order_id + '</td>';
				html += '<td>' + (json[i].supplier || '') + '</td>';
				html += '<td>' + json[i].date_added + '</td>';
				html += '<td class="text-right">' + json[i].total + '</td>';
				html += '<td>' + (json[i].status || '') + '</td>';
				html += '</tr>';
			}
			$('#pos-results').html(html);
		},
		complete: function() {
			btn.prop('disabled', false).html('Actualizar');
		}
	});
}

$('#pos-search').click(posDoSearch);

$('#pos-purchase-order-id, #pos-supplier').on('keypress', function(e) {
	if (e.which == 13) posDoSearch();
});

$(document).on('dblclick', '#pos-results tr[data-purchase-order-id]', function() {
	var purchaseOrderId = $(this).data('purchase-order-id');
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('purchase/reception/getPurchaseOrderData', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { purchase_order_id: purchaseOrderId },
		dataType: 'json',
		success: function(json) {
			if (json.error) {
				alert(json.error);
				return;
			}

			$('#reception_purchase_order_id').val(json.purchase_order_id);
			$('#reception_supplier_id').val(json.supplier_id);
			$('#reception-supplier').val(json.supplier);

			$('#shipping').val(json.shipping_code).trigger('change');
			$('#payment').val(json.payment_code).trigger('change');

			var button_remove = $('#button_remove').val();
			var product_row = 0;
			var html = '';
			if (json.reception_product && json.reception_product.length) {
				for (var i = 0; i < json.reception_product.length; i++) {
					var p = json.reception_product[i];
					html += '<tr id="product-row' + product_row + '">';
					html += '<td class="text-center"><a class="label label-danger" title="' + button_remove + '" onclick="$(\'#product-row' + product_row + '\').remove();"><i class="fa fa-trash"></i></a></td>';
					html += '<td>' + p.name + '<br>';
					html += '<input type="hidden" name="reception_product[' + product_row + '][reception_product_id]" value="">';
					html += '<input type="hidden" name="reception_product[' + product_row + '][product_id]" value="' + p.product_id + '">';
					html += '<input type="hidden" name="reception_product[' + product_row + '][name]" value="' + p.name + '">';
					html += '<input type="hidden" name="reception_product[' + product_row + '][model]" value="' + p.model + '">';
					html += '</td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right rc-qty" name="reception_product[' + product_row + '][quantity]" value="' + p.quantity + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right rc-price" data-catalog-price="' + p.catalog_price_raw + '" name="reception_product[' + product_row + '][price]" value="' + p.price_raw + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right rc-discount" name="reception_product[' + product_row + '][discount]" value="' + (p.discount || '') + '"></td>';
					html += '<td class="text-right">' + p.total + '<input type="hidden" name="reception_product[' + product_row + '][total]" value="' + p.total + '"><input type="hidden" name="reception_product[' + product_row + '][tax]" value="' + p.tax + '"></td>';
					html += '</tr>';
					product_row++;
				}
			}
			$('#product').html(html);

			var total_row = 0;
			var thtml = '';
			if (json.reception_total && json.reception_total.length) {
				for (var i = 0; i < json.reception_total.length; i++) {
					var t = json.reception_total[i];
					thtml += '<tr id="total-row' + total_row + '">';
					thtml += '<td class="d-none d-sm-table-cell"></td><td class="text-right" colspan="4">';
					thtml += '<input type="hidden" name="reception_total[' + total_row + '][reception_total_id]" value="">';
					thtml += '<input type="hidden" name="reception_total[' + total_row + '][code]" value="' + t.code + '">';
					thtml += '<input type="hidden" name="reception_total[' + total_row + '][title]" value="' + t.title + '">';
					thtml += '<input type="hidden" name="reception_total[' + total_row + '][text]" value="' + t.text + '">';
					thtml += '<input type="hidden" name="reception_total[' + total_row + '][value]" value="' + t.value + '">';
					thtml += '<input type="hidden" name="reception_total[' + total_row + '][sort_order]" value="' + t.sort_order + '">';
					thtml += t.title + ':</td>';
					thtml += '<td class="text-right">' + t.text + '</td>';
					thtml += '</tr>';
					total_row++;
				}
			}
			$('#total').html(thtml);

			bootstrap.Modal.getInstance(document.getElementById('PurchaseOrderSearchModal')).hide();
		}
	});
});

$('#PurchaseOrderSearchModal').on('hidden.bs.modal', function() {
	$('#pos-purchase-order-id, #pos-supplier').val('');
	$('#pos-results').html('<tr><td colspan="5" class="text-center">Use los filtros para buscar pedidos</td></tr>');
	$('#pos-warning').hide();
});

$('#ReceptionProductModal').on('hidden.bs.modal', function () {
	$(this).find('#reception-product').val('').end();
	$(this).find('#reception_product_id').val(0);
	$(this).find('#pm-discount').val('');
});
$('#addReceptionProduct').click(function(e){
	if($('#reception_supplier_id').val()==0){
		alert('Por favor, seleccione un proveedor primero');
		$('#reception-supplier').focus();
	} else {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ProductSearchModal')).show();
	}
});

var psProducts = [];

function psDoSearch() {
	var btn = $('#ps-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#ps-results').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $this->url->link('catalog/product/searchProducts', 'token=' . $this->session->data['token'], 'SSL')); ?>',
		type: 'post',
		data: { filter_sku: $('#ps-sku').val(), filter_name: $('#ps-name').val() },
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

$('#ps-sku, #ps-name').on('keypress', function(e) {
	if (e.which == 13) { e.preventDefault(); psDoSearch(); }
});

$(document).on('dblclick', '#ps-results tr[data-idx]', function() {
	var idx = parseInt($(this).data('idx'));
	var p = psProducts[idx];
	$('#reception_product_id').val(p.product_id);
	$('#reception-product').val(p.name);

	bootstrap.Modal.getInstance(document.getElementById('ProductSearchModal')).hide();
	bootstrap.Modal.getOrCreateInstance(document.getElementById('ReceptionProductModal')).show();
});

$('#ProductSearchModal').on('hidden.bs.modal', function() {
	$('#ps-sku, #ps-name').val('');
	$('#ps-results').html('<tr><td colspan="5" class="text-center">Use los filtros para buscar productos</td></tr>');
	$('#ps-warning').hide();
	psProducts = [];
});

$('#button-reception-product').on('click', function() {
	var a = $(this);
	var data = '#tab-reception input[type="text"],#tab-reception input[type="hidden"],';
	data += '#ReceptionProductModal input[type="text"],#ReceptionProductModal input[type="hidden"],';
	data += '#product input[type="text"],#product input[type="hidden"]';
	var ajaxData = $(data).serialize();

	var productModal = $('#ReceptionProductModal');
	if (productModal.length && productModal.hasClass('show')) {
		bootstrap.Modal.getInstance(productModal[0]).hide();
	}

	$.ajax({
		url: 'index.php?route=purchase/reception/checkReception&token=' + token,
		type: 'post',
		data: ajaxData,
		dataType: 'json',
		success: function(json) {
			if (json.reception_product) {
				var product_row = 0;
				var html = '';
				for (var i = 0; i < json.reception_product.length; i++) {
					var product = json.reception_product[i];
					html += '<tr id="product-row' + product_row + '">';
					html += '<td class="text-center"><a class="label label-danger" title="' + $('#button_remove').val() + '" onclick="$(\'#product-row' + product_row + '\').remove();"><i class="fa fa-trash"></i></a></td>';
					html += '<td>' + product.name + '<br><input type="hidden" name="reception_product[' + product_row + '][reception_product_id]" value=""><input type="hidden" name="reception_product[' + product_row + '][product_id]" value="' + product.product_id + '"><input type="hidden" name="reception_product[' + product_row + '][name]" value="' + product.name + '"><input type="hidden" name="reception_product[' + product_row + '][model]" value="' + product.model + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right rc-qty" name="reception_product[' + product_row + '][quantity]" value="' + product.quantity + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right rc-price" data-catalog-price="' + product.catalog_price_raw + '" name="reception_product[' + product_row + '][price]" value="' + product.price_raw + '"></td>';
					html += '<td class="text-right"><input type="text" class="form-control text-right rc-discount" name="reception_product[' + product_row + '][discount]" value="' + (product.discount != null ? product.discount : '') + '"></td>';
					html += '<td class="text-right">' + product.total + '<input type="hidden" name="reception_product[' + product_row + '][total]" value="' + product.total + '"><input type="hidden" name="reception_product[' + product_row + '][tax]" value="' + product.tax + '"></td>';
					html += '</tr>';
					product_row++;
				}
				$('#product').html(html);
			}
			if (json.reception_total) {
				var total_row = 0;
				var thtml = '';
				for (var i in json.reception_total) {
					var total = json.reception_total[i];
					thtml += '<tr id="total-row' + total_row + '">';
					thtml += '<td class="d-none d-sm-table-cell"></td><td class="text-right" colspan="4"><input type="hidden" name="reception_total[' + total_row + '][reception_total_id]" value=""><input type="hidden" name="reception_total[' + total_row + '][code]" value="' + total.code + '"><input type="hidden" name="reception_total[' + total_row + '][title]" value="' + total.title + '"><input type="hidden" name="reception_total[' + total_row + '][text]" value="' + total.text + '"><input type="hidden" name="reception_total[' + total_row + '][value]" value="' + total.value + '"><input type="hidden" name="reception_total[' + total_row + '][sort_order]" value="' + total.sort_order + '">' + total.title + ':</td>';
					thtml += '<td class="text-right">' + total.text + '</td>';
					thtml += '</tr>';
					total_row++;
				}
				$('#total').html(thtml);
			}
		}
	});
});

function rcMarkPriceChanged(input) {
	var $input = $(input);
	var current = parseFloat($input.val().replace(',', '.')) || 0;
	var original = parseFloat($input.data('catalog-price')) || 0;

	$input.toggleClass('rc-price-changed', current.toFixed(2) !== original.toFixed(2));
}

$(document).on('input', '.rc-price', function() {
	rcMarkPriceChanged(this);
});

$(document).on('change', '.rc-qty, .rc-price, .rc-discount', function() {
	rcMarkPriceChanged(this);
	$('#button-reception-product').click();
});

$('.rc-price').each(function() {
	rcMarkPriceChanged(this);
});

$(document).on('input', '.rc-discount, #global_discount, #pm-discount', function() {
	this.value = this.value.replace(/[^0-9.]/g, '');
});

$('#global_discount').on('change', function() {
	$('#button-reception-product').click();
});
</script>
<style>
.rc-price-changed {
	color: #b30000;
	font-weight: bold;
	border-color: #b30000;
	background-color: #fdf0f0;
}
</style>
<?php echo $footer; ?>
