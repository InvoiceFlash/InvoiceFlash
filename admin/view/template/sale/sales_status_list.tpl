<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<style>
	#sales-status-table .col-quote    { background-color: #e4e9fb; }
	#sales-status-table .col-order    { background-color: #fdf6d3; }
	#sales-status-table .col-delivery { background-color: #dcf5df; }
	#sales-status-table .col-invoice  { background-color: #d3f1f7; }
	#sales-status-table .badge-paid    { background-color: #3fae4a; color: #fff; }
	#sales-status-table .badge-pending { background-color: #e05b5b; color: #fff; }
</style>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-chart-line"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="row g-2 align-items-end">
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_customer; ?></label>
					<div style="position:relative;">
						<input type="text" id="filter-customer" class="form-control" style="padding-right:2.5rem;" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>">
						<button type="button" id="searchCustomer" class="btn btn-default" style="position:absolute; top:0; right:0; height:calc(2.0625rem + 2px); border-left:0; border-top-left-radius:0; border-bottom-left-radius:0;" title="Buscar Cliente"><i class="fa fa-search"></i></button>
					</div>
				</div>
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_reference; ?></label>
					<input type="text" id="filter-reference" class="form-control" value="<?php echo $filter_reference; ?>" placeholder="<?php echo $entry_reference; ?>">
				</div>
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_date_start; ?></label>
					<div class="input-group">
						<input type="text" id="filter-date-start" class="form-control date" value="<?php echo $filter_date_start; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-6 col-sm-2">
					<label class="control-label"><?php echo $entry_date_end; ?></label>
					<div class="input-group">
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
					<button type="button" onclick="salesStatusFilter();" class="btn btn-info w-100" style="height:calc(2.0625rem + 2px);"><i class="fa fa-sync"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped" id="sales-status-table">
				<thead>
					<tr>
						<th rowspan="2" class="align-middle"><?php echo $column_customer; ?></th>
						<th colspan="3" class="text-center col-quote"><?php echo $column_quote; ?></th>
						<th colspan="3" class="text-center col-order"><?php echo $column_order; ?></th>
						<th colspan="3" class="text-center col-delivery"><?php echo $column_delivery; ?></th>
						<th colspan="3" class="text-center col-invoice"><?php echo $column_invoice; ?></th>
					</tr>
					<tr>
						<th class="col-quote">Nº</th>
						<th class="col-quote"><?php echo $column_date; ?></th>
						<th class="col-quote"><?php echo $column_status; ?></th>
						<th class="col-order">Nº</th>
						<th class="col-order"><?php echo $column_date; ?></th>
						<th class="col-order"><?php echo $column_status; ?></th>
						<th class="col-delivery">Nº</th>
						<th class="col-delivery"><?php echo $column_date; ?></th>
						<th class="col-delivery"><?php echo $column_status; ?></th>
						<th class="col-invoice">Nº</th>
						<th class="col-invoice"><?php echo $column_date; ?></th>
						<th class="col-invoice"><?php echo $column_status; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($rows) { ?>
					<?php foreach ($rows as $row) { ?>
					<tr>
						<td><?php echo $row['customer']; ?></td>
						<td class="col-quote"><?php echo $row['quote_id'] ? '<a href="' . $row['quote_href'] . '">' . $row['quote_id'] . '</a>' : ''; ?></td>
						<td class="col-quote"><?php echo $row['quote_date']; ?></td>
						<td class="col-quote"><?php echo $row['quote_status']; ?></td>
						<td class="col-order"><?php echo $row['order_id'] ? '<a href="' . $row['order_href'] . '">' . $row['order_id'] . '</a>' : ''; ?></td>
						<td class="col-order"><?php echo $row['order_date']; ?></td>
						<td class="col-order"><?php echo $row['order_status']; ?></td>
						<td class="col-delivery"><?php echo $row['delivery_id'] ? '<a href="' . $row['delivery_href'] . '">' . $row['delivery_id'] . '</a>' : ''; ?></td>
						<td class="col-delivery"><?php echo $row['delivery_date']; ?></td>
						<td class="col-delivery"><?php echo $row['delivery_status']; ?></td>
						<td class="col-invoice"><?php echo $row['invoice_id'] ? '<a href="' . $row['invoice_href'] . '">' . $row['invoice_id'] . '</a>' : ''; ?></td>
						<td class="col-invoice"><?php echo $row['invoice_date']; ?></td>
						<td class="col-invoice"><?php if ($row['invoice_id']) { ?><span class="badge <?php echo $row['invoice_paid'] ? 'badge-paid' : 'badge-pending'; ?>"><?php echo $row['invoice_status']; ?></span><?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="13"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
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
						<label class="control-label">Empresa</label>
						<input type="text" id="cs-company" class="form-control" placeholder="Empresa">
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
								<th>Email</th>
								<th>Teléfono</th>
							</tr>
						</thead>
						<tbody id="cs-results">
							<tr><td colspan="3" class="text-center">Pulsa Actualizar para listar los clientes</td></tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Fin Modal Buscar Cliente -->
<script type="text/javascript"><!--
function salesStatusFilter() {
	var params = '';

	var customer = $('#filter-customer').val();
	if (customer) params += '&filter_customer=' + encodeURIComponent(customer);

	var reference = $('#filter-reference').val();
	if (reference) params += '&filter_reference=' + encodeURIComponent(reference);

	params += '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());

	params += '&filter_applied=1';
	if ($('#filter-pending').is(':checked')) params += '&filter_pending=1';
	if ($('#filter-paid').is(':checked')) params += '&filter_paid=1';

	location = 'index.php?route=sale/sales_status&token=<?php echo $token; ?>' + params;
}

var csCustomers = [];

$('#searchCustomer').click(function(e) {
	bootstrap.Modal.getOrCreateInstance(document.getElementById('CustomerSearchModal')).show();
});

$('#CustomerSearchModal').on('shown.bs.modal', function() {
	$('#cs-company').trigger('focus');
});

function csDoSearch() {
	var btn = $('#cs-search');
	btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
	$('#cs-results').html('<tr><td colspan="3" class="text-center"><i class="fa fa-spinner fa-spin"></i> Buscando...</td></tr>');

	$.ajax({
		url: 'index.php?route=sale/customer/searchCustomers&token=<?php echo $token; ?>',
		type: 'post',
		data: { filter_company: $('#cs-company').val(), filter_contact: $('#cs-contact').val() },
		dataType: 'json',
		success: function(json) {
			if (json.warning) {
				$('#cs-warning').text(json.warning).show();
				$('#cs-results').html('<tr><td colspan="3" class="text-center">' + json.warning + '</td></tr>');
				csCustomers = [];
				return;
			}
			$('#cs-warning').hide();
			csCustomers = json;
			if (!json.length) {
				$('#cs-results').html('<tr><td colspan="3" class="text-center">No se encontraron clientes</td></tr>');
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
			$('#cs-results').html(html);
		},
		error: function() {
			$('#cs-warning').text('Error al buscar clientes').show();
			$('#cs-results').html('<tr><td colspan="3" class="text-center">Error al buscar clientes</td></tr>');
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

	$('#filter-customer').val(c.company);

	bootstrap.Modal.getInstance(document.getElementById('CustomerSearchModal')).hide();
});

$('#CustomerSearchModal').on('hidden.bs.modal', function() {
	$('#cs-company').val('');
	$('#cs-contact').val('');
	$('#cs-results').html('<tr><td colspan="3" class="text-center">Pulsa Actualizar para listar los clientes</td></tr>');
	$('#cs-warning').hide();
	csCustomers = [];
});
//--></script>

<?php echo $footer; ?>
