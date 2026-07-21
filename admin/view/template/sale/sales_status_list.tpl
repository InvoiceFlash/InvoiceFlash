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
			<div class="row g-2">
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_customer; ?></label>
					<input type="text" id="filter-customer" class="form-control" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>">
				</div>
				<div class="col-12 col-sm-2">
					<label class="control-label"><?php echo $entry_currency; ?></label>
					<select id="filter-currency" class="form-control">
						<option value="">---</option>
						<?php foreach ($currencies as $currency) { ?>
						<option value="<?php echo $currency['currency_code']; ?>" <?php echo ($currency['currency_code'] == $filter_currency_code) ? 'selected' : ''; ?>><?php echo $currency['currency_code']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-12 col-sm-3">
					<label class="control-label"><?php echo $entry_reference; ?></label>
					<input type="text" id="filter-reference" class="form-control" value="<?php echo $filter_reference; ?>" placeholder="<?php echo $entry_reference; ?>">
				</div>
				<div class="col-12 col-sm-5">
					<label class="control-label"><?php echo $entry_invoices; ?></label>
					<div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="filter-pending" <?php echo $filter_pending ? 'checked' : ''; ?>>
							<label class="form-check-label" for="filter-pending"><?php echo $entry_pending; ?></label>
						</div>
						<div class="form-check form-check-inline">
							<input type="checkbox" class="form-check-input" id="filter-paid" <?php echo $filter_paid ? 'checked' : ''; ?>>
							<label class="form-check-label" for="filter-paid"><?php echo $entry_paid; ?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="row g-2 mt-1 align-items-end">
				<div class="col-12">
					<label class="control-label d-block"><?php echo $entry_period; ?></label>
					<div class="d-flex flex-wrap align-items-center gap-2">
						<div class="form-check form-check-inline">
							<input type="radio" class="form-check-input" name="period_mode" id="period-all" value="all" <?php echo ($period_mode == 'all') ? 'checked' : ''; ?>>
							<label class="form-check-label" for="period-all"><?php echo $text_all; ?></label>
						</div>
						<div class="form-check form-check-inline">
							<input type="radio" class="form-check-input" name="period_mode" id="period-quarter" value="quarter" <?php echo ($period_mode == 'quarter') ? 'checked' : ''; ?>>
							<label class="form-check-label" for="period-quarter"><?php echo $text_quarter; ?></label>
						</div>
						<select id="filter-quarter" class="form-control d-inline-block" style="width:70px;">
							<?php for ($q = 1; $q <= 4; $q++) { ?>
							<option value="<?php echo $q; ?>" <?php echo ($q == $filter_quarter) ? 'selected' : ''; ?>><?php echo $q; ?></option>
							<?php } ?>
						</select>
						<select id="filter-quarter-year" class="form-control d-inline-block" style="width:90px;">
							<?php foreach ($years as $year) { ?>
							<option value="<?php echo $year; ?>" <?php echo ($year == $filter_quarter_year) ? 'selected' : ''; ?>><?php echo $year; ?></option>
							<?php } ?>
						</select>

						<div class="form-check form-check-inline ms-3">
							<input type="radio" class="form-check-input" name="period_mode" id="period-month" value="month" <?php echo ($period_mode == 'month') ? 'checked' : ''; ?>>
							<label class="form-check-label" for="period-month"><?php echo $text_month; ?></label>
						</div>
						<select id="filter-month" class="form-control d-inline-block" style="width:130px;">
							<?php foreach ($months as $month) { ?>
							<option value="<?php echo $month['value']; ?>" <?php echo ($month['value'] == $filter_month) ? 'selected' : ''; ?>><?php echo $month['text']; ?></option>
							<?php } ?>
						</select>
						<select id="filter-month-year" class="form-control d-inline-block" style="width:90px;">
							<?php foreach ($years as $year) { ?>
							<option value="<?php echo $year; ?>" <?php echo ($year == $filter_month_year) ? 'selected' : ''; ?>><?php echo $year; ?></option>
							<?php } ?>
						</select>

						<div class="form-check form-check-inline ms-3">
							<input type="radio" class="form-check-input" name="period_mode" id="period-dates" value="dates" <?php echo ($period_mode == 'dates') ? 'checked' : ''; ?>>
							<label class="form-check-label" for="period-dates"><?php echo $text_dates; ?></label>
						</div>
						<input type="text" id="filter-date-start" class="form-control d-inline-block" style="width:120px;" placeholder="aaaa-mm-dd" value="<?php echo $filter_date_start; ?>">
						<input type="text" id="filter-date-end" class="form-control d-inline-block" style="width:120px;" placeholder="aaaa-mm-dd" value="<?php echo $filter_date_end; ?>">

						<button type="button" onclick="salesStatusFilter();" class="btn btn-info ms-auto"><i class="fa fa-sync"></i> <?php echo $button_filter; ?></button>
					</div>
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
<script type="text/javascript"><!--
function salesStatusFilter() {
	var params = '';

	var customer = $('#filter-customer').val();
	if (customer) params += '&filter_customer=' + encodeURIComponent(customer);

	var currency = $('#filter-currency').val();
	if (currency) params += '&filter_currency_code=' + encodeURIComponent(currency);

	var reference = $('#filter-reference').val();
	if (reference) params += '&filter_reference=' + encodeURIComponent(reference);

	var periodMode = $('input[name="period_mode"]:checked').val();
	params += '&period_mode=' + encodeURIComponent(periodMode);
	params += '&filter_quarter=' + encodeURIComponent($('#filter-quarter').val());
	params += '&filter_quarter_year=' + encodeURIComponent($('#filter-quarter-year').val());
	params += '&filter_month=' + encodeURIComponent($('#filter-month').val());
	params += '&filter_month_year=' + encodeURIComponent($('#filter-month-year').val());
	params += '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());

	params += '&filter_applied=1';
	if ($('#filter-pending').is(':checked')) params += '&filter_pending=1';
	if ($('#filter-paid').is(':checked')) params += '&filter_paid=1';

	location = 'index.php?route=sale/sales_status&token=<?php echo $token; ?>' + params;
}
//--></script>

<?php echo $footer; ?>
