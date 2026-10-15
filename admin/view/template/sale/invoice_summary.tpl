<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<style>
	#invoice-summary .ifx-matrix th, #invoice-summary .ifx-matrix td { white-space: nowrap; text-align: right; }
	#invoice-summary .ifx-matrix th:first-child, #invoice-summary .ifx-matrix td:first-child { text-align: center; font-weight: 600; }
	#invoice-summary .ifx-matrix .ifx-zero { color: #adb5bd; }
	#invoice-summary .ifx-matrix tfoot td { font-weight: 600; background-color: #f1f3f5; }
	#invoice-summary .ifx-total-col { background-color: #f8f9fa; font-weight: 600; }
	#ifx-chart-modal .ifx-swatch { display: inline-block; width: 12px; height: 12px; border-radius: 2px; margin-right: 6px; vertical-align: -1px; }
	#ifx-chart-years { max-height: 260px; overflow-y: auto; }
</style>

<div class="card page-card" id="invoice-summary">

	<div class="card-header clearfix">
		<div class="h2"><i class="fa fa-chart-line"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="card-body">

		<form method="get" action="index.php" id="filter-form" class="mb-3">
			<input type="hidden" name="route" value="sale/invoice_summary">
			<input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
			<input type="hidden" name="tab" id="filter-tab" value="<?php echo (int)$tab; ?>">

			<div class="row g-2 align-items-end">
				<div class="col-12 col-sm-3">
					<label class="col-form-label pb-0"><?php echo $entry_customer; ?></label>
					<input type="text" name="filter_customer" class="form-control" value="<?php echo htmlspecialchars($filter_customer, ENT_QUOTES, 'UTF-8'); ?>">
				</div>
				<div class="col-6 col-sm-2">
					<label class="col-form-label pb-0"><?php echo $entry_date_start; ?></label>
					<div class="input-group mb-0">
						<input type="text" name="filter_date_start" class="form-control date" value="<?php echo htmlspecialchars($filter_date_start, ENT_QUOTES, 'UTF-8'); ?>">
						<div class="input-group-text"><i class="fas fa-calendar"></i></div>
					</div>
				</div>
				<div class="col-6 col-sm-2">
					<label class="col-form-label pb-0"><?php echo $entry_date_end; ?></label>
					<div class="input-group mb-0">
						<input type="text" name="filter_date_end" class="form-control date" value="<?php echo htmlspecialchars($filter_date_end, ENT_QUOTES, 'UTF-8'); ?>">
						<div class="input-group-text"><i class="fas fa-calendar"></i></div>
					</div>
				</div>
				<div class="col-6 col-sm-2">
					<label class="col-form-label pb-0"><?php echo $entry_status; ?></label>
					<select name="filter_status" class="form-select">
						<option value="0"><?php echo $text_all_statuses; ?></option>
						<?php foreach ($statuses as $status) { ?>
						<option value="<?php echo (int)$status['invoice_status_id']; ?>"<?php echo ($filter_status == $status['invoice_status_id']) ? ' selected' : ''; ?>><?php echo $status['name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-6 col-sm-2">
					<label class="col-form-label pb-0"><?php echo $entry_amount; ?></label>
					<select name="amount" class="form-select">
						<option value="total"<?php echo ($amount == 'total') ? ' selected' : ''; ?>><?php echo $text_amount_total; ?></option>
						<option value="base"<?php echo ($amount == 'base') ? ' selected' : ''; ?>><?php echo $text_amount_base; ?></option>
					</select>
				</div>
				<div class="col-12 col-sm-1">
					<button type="submit" class="btn btn-info w-100"><i class="fa fa-search"></i><span class="d-none d-lg-inline"> <?php echo $button_filter; ?></span></button>
				</div>
			</div>
		</form>

		<ul class="nav nav-tabs" id="summary-tabs">
			<li class="nav-item"><a class="nav-link" id="nav-tab-1" href="#tab-invoices" data-bs-toggle="tab" data-tab="1"><?php echo $tab_invoices; ?> <span class="text-muted">(<?php echo $count_invoices; ?>)</span></a></li>
			<li class="nav-item"><a class="nav-link" id="nav-tab-2" href="#tab-amounts" data-bs-toggle="tab" data-tab="2"><?php echo $tab_amounts; ?> <span class="text-muted">(<?php echo $count_amounts; ?>)</span></a></li>
			<li class="nav-item"><a class="nav-link" id="nav-tab-3" href="#tab-lines" data-bs-toggle="tab" data-tab="3"><?php echo $tab_lines; ?> <span class="text-muted">(<?php echo $count_lines; ?>)</span></a></li>
			<li class="nav-item"><a class="nav-link" id="nav-tab-4" href="#tab-units" data-bs-toggle="tab" data-tab="4"><?php echo $tab_units; ?> <span class="text-muted">(<?php echo $count_units; ?>)</span></a></li>
		</ul>

		<div class="tab-content pt-3">

			<!-- 1. Lista de facturas -->
			<div class="tab-pane" id="tab-invoices">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-sm align-middle">
						<thead class="table-light">
							<tr>
								<th><?php echo $column_date; ?></th>
								<th><?php echo $column_code; ?></th>
								<th><?php echo $column_nif; ?></th>
								<th><?php echo $column_customer_code; ?></th>
								<th><?php echo $column_customer; ?></th>
								<th><?php echo $column_account; ?></th>
								<th><?php echo $column_status; ?></th>
								<th class="text-end"><?php echo $column_amount; ?><br><span class="fw-normal">(<?php echo $sum_total; ?>)</span></th>
								<th class="text-end"><?php echo $column_discount_pct; ?></th>
								<th class="text-end"><?php echo $column_discount_amount; ?><br><span class="fw-normal">(<?php echo $sum_discount; ?>)</span></th>
								<th class="text-end"><?php echo $column_subtotal; ?><br><span class="fw-normal">(<?php echo $sum_sub_total; ?>)</span></th>
								<th class="text-end"><?php echo $column_tax; ?><br><span class="fw-normal">(<?php echo $sum_tax; ?>)</span></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($invoices) { ?>
							<?php foreach ($invoices as $invoice) { ?>
							<tr>
								<td class="text-nowrap"><?php echo $invoice['date']; ?></td>
								<td class="text-nowrap"><a href="<?php echo $invoice['href']; ?>"><?php echo $invoice['number']; ?></a></td>
								<td class="text-nowrap"><?php echo $invoice['nif']; ?></td>
								<td class="text-nowrap"><?php echo $invoice['code']; ?></td>
								<td><?php echo $invoice['customer']; ?></td>
								<td class="text-nowrap"><?php echo $invoice['account']; ?></td>
								<td><?php if ($invoice['status']) { ?><span class="badge <?php echo $invoice['paid'] ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $invoice['status']; ?></span><?php } ?></td>
								<td class="text-end text-nowrap<?php echo $invoice['negative'] ? ' text-danger' : ''; ?>"><?php echo $invoice['total']; ?></td>
								<td class="text-end text-nowrap"><?php echo $invoice['discount_pct']; ?></td>
								<td class="text-end text-nowrap"><?php echo $invoice['discount']; ?></td>
								<td class="text-end text-nowrap<?php echo $invoice['negative'] ? ' text-danger' : ''; ?>"><?php echo $invoice['sub_total']; ?></td>
								<td class="text-end text-nowrap"><?php echo $invoice['tax']; ?></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr><td class="text-center" colspan="12"><?php echo $text_no_results; ?></td></tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="pagination"><?php echo $pagination_invoices; ?></div>
			</div>

			<!-- 2. Resumen de importes -->
			<div class="tab-pane" id="tab-amounts">
				<div class="text-center mb-3">
					<button type="button" class="btn btn-default" onclick="ifxOpenChart('amount');"><i class="fa fa-chart-line text-success"></i> <?php echo $button_chart; ?></button>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-sm ifx-matrix">
						<thead class="table-light">
							<tr>
								<th><?php echo $column_year; ?></th>
								<th><?php echo $text_total_amounts; ?> (<?php echo $amount_total; ?>)</th>
								<?php for ($m = 1; $m <= 12; $m++) { ?><th><?php echo ${'text_month_' . $m}; ?></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($amount_table['rows'] as $row) { ?>
							<tr>
								<td><?php echo $row['year']; ?></td>
								<td class="ifx-total-col<?php echo $row['negative'] ? ' text-danger' : ''; ?>"><?php echo $row['total']; ?></td>
								<?php foreach ($row['cells'] as $cell) { ?>
								<td class="<?php echo $cell['negative'] ? 'text-danger' : ($cell['zero'] ? 'ifx-zero' : ''); ?>"><?php echo $cell['text']; ?></td>
								<?php } ?>
							</tr>
							<?php } ?>
							<?php if (!$amount_table['rows']) { ?>
							<tr><td class="text-center" colspan="14"><?php echo $text_no_results; ?></td></tr>
							<?php } ?>
						</tbody>
						<?php if ($amount_table['rows']) { ?>
						<tfoot>
							<tr>
								<td><?php echo $text_total; ?></td>
								<td><?php echo $amount_table['grand_text']; ?></td>
								<?php foreach ($amount_table['totals'] as $cell) { ?><td class="<?php echo $cell['negative'] ? 'text-danger' : ''; ?>"><?php echo $cell['text']; ?></td><?php } ?>
							</tr>
						</tfoot>
						<?php } ?>
					</table>
				</div>
			</div>

			<!-- 3. Detalle de articulos -->
			<div class="tab-pane" id="tab-lines">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-sm align-middle">
						<thead class="table-light">
							<tr>
								<th><?php echo $column_code; ?></th>
								<th><?php echo $column_description; ?></th>
								<th><?php echo $column_customer_code; ?></th>
								<th><?php echo $column_customer; ?></th>
								<th><?php echo $column_date; ?></th>
								<th class="text-end"><?php echo $column_quantity; ?><br><span class="fw-normal">(<?php echo $sum_quantity; ?>)</span></th>
								<th class="text-end"><?php echo $column_price; ?></th>
								<th class="text-end"><?php echo $column_discount_line; ?></th>
								<th class="text-end"><?php echo $column_amount; ?><br><span class="fw-normal">(<?php echo $sum_lines_total; ?>)</span></th>
								<th><?php echo $column_invoice_code; ?></th>
								<th><?php echo $column_delivery; ?></th>
								<th><?php echo $column_order; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($lines) { ?>
							<?php foreach ($lines as $line) { ?>
							<tr>
								<td class="text-nowrap"><?php echo $line['model']; ?></td>
								<td><?php echo $line['name']; ?></td>
								<td class="text-nowrap"><?php echo $line['code']; ?></td>
								<td><?php echo $line['customer']; ?></td>
								<td class="text-nowrap"><?php echo $line['date']; ?></td>
								<td class="text-end"><?php echo $line['quantity']; ?></td>
								<td class="text-end text-nowrap"><?php echo $line['price']; ?></td>
								<td class="text-end text-nowrap"><?php echo $line['discount']; ?></td>
								<td class="text-end text-nowrap<?php echo $line['negative'] ? ' text-danger' : ''; ?>"><?php echo $line['total']; ?></td>
								<td class="text-nowrap"><a href="<?php echo $line['href']; ?>"><?php echo $line['number']; ?></a></td>
								<td class="text-nowrap"><?php foreach ($line['deliveries'] as $k => $d) { ?><?php echo $k ? ', ' : ''; ?><a href="<?php echo $d['href']; ?>"><?php echo $d['id']; ?></a><?php } ?></td>
								<td class="text-nowrap"><?php foreach ($line['orders'] as $k => $o) { ?><?php echo $k ? ', ' : ''; ?><a href="<?php echo $o['href']; ?>"><?php echo $o['id']; ?></a><?php } ?></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr><td class="text-center" colspan="12"><?php echo $text_no_results; ?></td></tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="pagination"><?php echo $pagination_lines; ?></div>
			</div>

			<!-- 4. Resumen de unidades -->
			<div class="tab-pane" id="tab-units">
				<div class="text-center mb-3">
					<button type="button" class="btn btn-default" onclick="ifxOpenChart('units');"><i class="fa fa-chart-line text-success"></i> <?php echo $button_chart; ?></button>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-sm ifx-matrix">
						<thead class="table-light">
							<tr>
								<th><?php echo $column_year; ?></th>
								<th><?php echo $text_total_units; ?> (<?php echo $units_total; ?>)</th>
								<?php for ($m = 1; $m <= 12; $m++) { ?><th><?php echo ${'text_month_' . $m}; ?></th><?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($units_table['rows'] as $row) { ?>
							<tr>
								<td><?php echo $row['year']; ?></td>
								<td class="ifx-total-col<?php echo $row['negative'] ? ' text-danger' : ''; ?>"><?php echo $row['total']; ?></td>
								<?php foreach ($row['cells'] as $cell) { ?>
								<td class="<?php echo $cell['negative'] ? 'text-danger' : ($cell['zero'] ? 'ifx-zero' : ''); ?>"><?php echo $cell['text']; ?></td>
								<?php } ?>
							</tr>
							<?php } ?>
							<?php if (!$units_table['rows']) { ?>
							<tr><td class="text-center" colspan="14"><?php echo $text_no_results; ?></td></tr>
							<?php } ?>
						</tbody>
						<?php if ($units_table['rows']) { ?>
						<tfoot>
							<tr>
								<td><?php echo $text_total; ?></td>
								<td><?php echo $units_table['grand_text']; ?></td>
								<?php foreach ($units_table['totals'] as $cell) { ?><td class="<?php echo $cell['negative'] ? 'text-danger' : ''; ?>"><?php echo $cell['text']; ?></td><?php } ?>
							</tr>
						</tfoot>
						<?php } ?>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Ventana del grafico -->
<div class="modal fade" id="ifx-chart-modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ifx-chart-title"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $text_close; ?>"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-lg-9">
						<div style="position:relative; height:430px;"><canvas id="ifx-chart-canvas"></canvas></div>
						<div id="ifx-chart-empty" class="text-center text-muted py-5" style="display:none;"><?php echo $text_no_years; ?></div>
						<div class="text-center mt-2 fw-bold" id="ifx-chart-total" style="font-size:1.1rem; color:#1f7fbf;"></div>
					</div>
					<div class="col-lg-3">
						<div class="fw-bold mb-1"><?php echo $text_chart_years; ?></div>
						<div class="mb-2">
							<a href="#" class="me-2" onclick="ifxYears('all'); return false;"><?php echo $text_select_all; ?></a>
							<a href="#" class="me-2" onclick="ifxYears('last3'); return false;"><?php echo $text_select_last3; ?></a>
							<a href="#" onclick="ifxYears('none'); return false;"><?php echo $text_select_none; ?></a>
						</div>
						<div id="ifx-chart-years" class="border rounded p-2 mb-3"></div>
						<div class="form-check mb-1">
							<input class="form-check-input" type="checkbox" id="ifx-chart-cum">
							<label class="form-check-label" for="ifx-chart-cum" title="<?php echo htmlspecialchars(html_entity_decode($text_accumulated_help, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $text_accumulated; ?></label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="ifx-chart-vals">
							<label class="form-check-label" for="ifx-chart-vals"><?php echo $text_show_values; ?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $text_close; ?></button>
			</div>
		</div>
	</div>
</div>

<script src="view/javascript/chart/Chart.bundle.min.js"></script>
<script>
// Pestana activa: ?tab=N. common.js marca como activo el primer panel de cualquier grupo de pestanas,
// asi que se fuerza la pestana pedida con la API de Bootstrap.
$(function () {
	var tab = <?php echo (int)$tab; ?>;

	bootstrap.Tab.getOrCreateInstance(document.getElementById('nav-tab-' + tab)).show();

	$('#summary-tabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
		$('#filter-tab').val($(this).data('tab'));
	});
});

(function () {
	var C = <?php echo $chart_json; ?>;
	var SHORT = <?php echo $short_json; ?>;
	var LOCALE = <?php echo json_encode($locale); ?>;
	var TITLES = {amount: <?php echo $chart_amount_title; ?>, units: <?php echo $chart_units_title; ?>};
	var TOTAL_LABEL = <?php echo $text_total_js; ?>;
	var PALETTE = ['#1f77b4', '#d62728', '#2ca02c', '#ff7f0e', '#9467bd', '#8c564b', '#e377c2', '#17becf', '#bcbd22', '#7f7f7f', '#393b79', '#e6550d'];
	var kind = 'amount';
	var chart = null;

	function color(year) {
		return PALETTE[C.years.indexOf(year) % PALETTE.length];
	}

	// useGrouping 'always': es-ES no agrupa los numeros de 4 cifras por defecto (1391,5 en vez de 1.391,5).
	function fmt(v, fixed) {
		var dec = (kind === 'units') ? 0 : 2;

		return Number(v).toLocaleString(LOCALE, {minimumFractionDigits: fixed ? dec : 0, maximumFractionDigits: dec, useGrouping: 'always'});
	}

	function selectedYears() {
		var ys = [];

		$('.ifx-chart-year:checked').each(function () {
			ys.push(parseInt($(this).val(), 10));
		});

		return ys.sort(function (a, b) { return a - b; });
	}

	function buildYearBoxes() {
		var html = '';

		$.each(C.years, function (i, y) {
			html += '<div class="form-check"><input class="form-check-input ifx-chart-year" type="checkbox" id="ify-' + y + '" value="' + y + '">'
				+ '<label class="form-check-label" for="ify-' + y + '"><span class="ifx-swatch" style="background:' + color(y) + ';"></span>' + y + '</label></div>';
		});

		$('#ifx-chart-years').html(html);
	}

	// Los 3 anyos mas recientes (C.years va de mas reciente a mas antiguo).
	window.ifxYears = function (mode, noRender) {
		$('.ifx-chart-year').each(function () {
			var idx = C.years.indexOf(parseInt($(this).val(), 10));

			$(this).prop('checked', mode === 'all' || (mode === 'last3' && idx < 3));
		});

		if (!noRender) {
			render();
		}
	};

	var valuesPlugin = {
		afterDatasetsDraw: function (c) {
			if (!$('#ifx-chart-vals').prop('checked')) {
				return;
			}

			var ctx = c.ctx;

			$.each(c.data.datasets, function (i, ds) {
				var meta = c.getDatasetMeta(i);

				if (meta.hidden) {
					return;
				}

				$.each(meta.data, function (idx, pt) {
					var v = ds.data[idx];

					if (v === null || v === undefined || v === 0) {
						return;
					}

					ctx.save();
					ctx.fillStyle = ds.borderColor;
					ctx.font = '11px sans-serif';
					ctx.textAlign = 'center';
					ctx.fillText(fmt(v), pt._model.x, pt._model.y - 8);
					ctx.restore();
				});
			});
		}
	};

	function render() {
		var years = selectedYears();
		var cum = $('#ifx-chart-cum').prop('checked');
		var datasets = [];
		var grand = 0;

		if (chart) {
			chart.destroy();
			chart = null;
		}

		$('#ifx-chart-empty').toggle(!years.length);
		$('#ifx-chart-canvas').toggle(years.length > 0);

		if (!years.length) {
			$('#ifx-chart-total').text('');
			return;
		}

		$.each(years, function (i, y) {
			var raw = C[kind][y];
			var data = [];
			var run = 0;

			$.each(raw, function (m, v) {
				grand += v;
				run += v;

				// Los meses que aun no han llegado del anyo en curso no se dibujan.
				if (y === C.now_year && m + 1 > C.now_month) {
					data.push(null);
				} else {
					data.push(Math.round((cum ? run : v) * 100) / 100);
				}
			});

			datasets.push({
				label: String(y), data: data, borderColor: color(y), backgroundColor: color(y), fill: false,
				lineTension: 0, borderWidth: 2, pointRadius: 3, spanGaps: false
			});
		});

		chart = new Chart(document.getElementById('ifx-chart-canvas').getContext('2d'), {
			type: 'line',
			data: {labels: SHORT, datasets: datasets},
			options: {
				maintainAspectRatio: false,
				legend: {position: 'right'},
				scales: {yAxes: [{scaleLabel: {display: true, labelString: TITLES[kind]}, ticks: {suggestedMin: 0, callback: function (v) { return fmt(v); }}}]},
				tooltips: {
					mode: 'index', intersect: false,
					callbacks: {label: function (t, d) { return d.datasets[t.datasetIndex].label + ': ' + fmt(t.yLabel); }}
				}
			},
			plugins: [valuesPlugin]
		});

		$('#ifx-chart-total').text(TOTAL_LABEL + ': ' + fmt(grand, true));
	}

	window.ifxOpenChart = function (k) {
		kind = k;

		$('#ifx-chart-title').text(TITLES[kind]);

		if (!$('.ifx-chart-year').length) {
			buildYearBoxes();
			ifxYears('last3', true);
		}

		bootstrap.Modal.getOrCreateInstance(document.getElementById('ifx-chart-modal')).show();
	};

	// El canvas necesita la ventana ya visible para medir su tamanyo.
	$('#ifx-chart-modal').on('shown.bs.modal', render);
	$(document).on('change', '.ifx-chart-year, #ifx-chart-cum, #ifx-chart-vals', render);
})();
</script>

<?php echo $footer; ?>
