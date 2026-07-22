<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<style>
	#suppliers-status-table .col-po      { background-color: #fdf6d3; }
	#suppliers-status-table .col-invoice { background-color: #d3f1f7; }
	#suppliers-status-table .badge-paid    { background-color: #3fae4a; color: #fff; }
	#suppliers-status-table .badge-pending { background-color: #e05b5b; color: #fff; }
</style>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-chart-line"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="row g-2 align-items-end">
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_supplier; ?></label>
					<div style="position:relative;">
						<input type="text" id="filter-supplier" class="form-control" style="padding-right:2.5rem;" value="<?php echo $filter_supplier; ?>" placeholder="<?php echo $entry_supplier; ?>">
						<button type="button" id="searchSupplier" class="btn btn-default" style="position:absolute; top:0; right:0; height:calc(2.0625rem + 2px); border-left:0; border-top-left-radius:0; border-bottom-left-radius:0;" title="Buscar Proveedor"><i class="fa fa-search"></i></button>
					</div>
				</div>
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_reference; ?></label>
					<input type="text" id="filter-reference" class="form-control" value="<?php echo $filter_reference; ?>" placeholder="<?php echo $entry_reference; ?>">
				</div>
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_date_start; ?></label>
					<div class="input-group mb-0">
						<input type="text" id="filter-date-start" class="form-control date" value="<?php echo $filter_date_start; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_date_end; ?></label>
					<div class="input-group mb-0">
						<input type="text" id="filter-date-end" class="form-control date" value="<?php echo $filter_date_end; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-12 col-sm-2">
					<label class="control-label d-block">&nbsp;</label>
					<div class="d-flex align-items-center" style="height: calc(2.0625rem + 2px);">
						<div class="form-check form-check-inline mb-0">
							<input type="checkbox" class="form-check-input" id="filter-pending" <?php echo $filter_pending ? 'checked' : ''; ?>>
							<label class="form-check-label" for="filter-pending"><?php echo $entry_pending; ?></label>
						</div>
						<div class="form-check form-check-inline mb-0">
							<input type="checkbox" class="form-check-input" id="filter-paid" <?php echo $filter_paid ? 'checked' : ''; ?>>
							<label class="form-check-label" for="filter-paid"><?php echo $entry_paid; ?></label>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-2">
					<label class="control-label d-block">&nbsp;</label>
					<button type="button" onclick="suppliersStatusFilter();" class="btn btn-info w-100" style="height:calc(2.0625rem + 2px);"><i class="fa fa-sync"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped" id="suppliers-status-table">
				<thead>
					<tr>
						<th rowspan="2" class="align-middle"><?php echo $column_supplier; ?></th>
						<th colspan="3" class="text-center col-po"><?php echo $column_purchase_order; ?></th>
						<th colspan="3" class="text-center col-invoice"><?php echo $column_invoice; ?></th>
					</tr>
					<tr>
						<th class="col-po">Nº</th>
						<th class="col-po"><?php echo $column_date; ?></th>
						<th class="col-po"><?php echo $column_status; ?></th>
						<th class="col-invoice">Nº</th>
						<th class="col-invoice"><?php echo $column_date; ?></th>
						<th class="col-invoice"><?php echo $column_status; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($rows) { ?>
					<?php foreach ($rows as $row) { ?>
					<tr>
						<td><?php echo $row['supplier']; ?></td>
						<td class="col-po"><?php echo $row['purchase_order_id'] ? '<a href="' . $row['po_href'] . '">' . $row['purchase_order_id'] . '</a>' : ''; ?></td>
						<td class="col-po"><?php echo $row['po_date']; ?></td>
						<td class="col-po"><?php echo $row['po_status']; ?></td>
						<td class="col-invoice"><?php echo $row['invoice_id'] ? '<a href="' . $row['invoice_href'] . '">' . $row['invoice_id'] . '</a>' : ''; ?></td>
						<td class="col-invoice"><?php echo $row['invoice_date']; ?></td>
						<td class="col-invoice"><?php if ($row['invoice_id']) { ?><span class="badge <?php echo $row['invoice_paid'] ? 'badge-paid' : 'badge-pending'; ?>"><?php echo $row['invoice_status']; ?></span><?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
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
						<label class="control-label">Empresa</label>
						<input type="text" id="ss-company" class="form-control" placeholder="Empresa">
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
<script type="text/javascript"><!--
function suppliersStatusFilter() {
	var params = '';

	var supplier = $('#filter-supplier').val();
	if (supplier) params += '&filter_supplier=' + encodeURIComponent(supplier);

	var reference = $('#filter-reference').val();
	if (reference) params += '&filter_reference=' + encodeURIComponent(reference);

	params += '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());

	params += '&filter_applied=1';
	if ($('#filter-pending').is(':checked')) params += '&filter_pending=1';
	if ($('#filter-paid').is(':checked')) params += '&filter_paid=1';

	location = 'index.php?route=purchase/suppliers_status&token=<?php echo $token; ?>' + params;
}

var ssSuppliers = [];

$('#searchSupplier').click(function(e) {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('SupplierSearchModal')).show();
});

$('#SupplierSearchModal').on('shown.bs.modal', function() {
	$('#ss-company').trigger('focus');
});

function ssDoSearch() {
	var btn = $('#ss-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#ss-results').html('<tr><td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

	$.ajax({
		url: 'index.php?route=purchase/supplier/searchSuppliers&token=<?php echo $token; ?>',
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

	$('#filter-supplier').val(s.company);

	bootstrap.Modal.getInstance(document.getElementById('SupplierSearchModal')).hide();
});

$('#SupplierSearchModal').on('hidden.bs.modal', function() {
	$('#ss-company').val('');
	$('#ss-results').html('<tr><td colspan="3" class="text-center">Pulsa Actualizar para listar los proveedores</td></tr>');
	$('#ss-warning').hide();
	ssSuppliers = [];
});
//--></script>

<?php echo $footer; ?>
