<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="row g-2 align-items-center">
				<div class="col-auto"><strong><?php echo $text_period; ?>:</strong></div>
				<div class="col-auto"><?php echo $entry_from; ?></div>
				<div class="col-auto">
					<div class="input-group" style="width:160px;">
						<input type="text" id="filter-date-start" class="form-control date" value="<?php echo $filter_date_start; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-auto"><?php echo $entry_to; ?></div>
				<div class="col-auto">
					<div class="input-group" style="width:160px;">
						<input type="text" id="filter-date-end" class="form-control date" value="<?php echo $filter_date_end; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>

				<div class="col-auto ms-3"><strong><?php echo $text_accounts; ?>:</strong></div>
				<div class="col-auto"><?php echo $entry_from; ?></div>
				<div class="col-auto">
					<input type="text" id="filter-account-start" class="form-control" style="width:120px;" value="<?php echo $filter_account_start; ?>">
				</div>
				<div class="col-auto"><?php echo $entry_to; ?></div>
				<div class="col-auto">
					<input type="text" id="filter-account-end" class="form-control" style="width:120px;" value="<?php echo $filter_account_end; ?>">
				</div>
			</div>
			<div class="row g-2 mt-2">
				<div class="col-auto">
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="show-opening-balance" <?php echo $show_opening_balance ? 'checked' : ''; ?>>
						<label class="form-check-label" for="show-opening-balance"><?php echo $text_show_opening_balance; ?></label>
					</div>
				</div>
				<div class="col-auto ms-3">
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="new-page-per-account" <?php echo $new_page_per_account ? 'checked' : ''; ?>>
						<label class="form-check-label" for="new-page-per-account"><?php echo $text_new_page_per_account; ?></label>
					</div>
				</div>
			</div>
			<div class="row g-2 mt-1">
				<div class="col text-end">
					<button type="button" onclick="printLedger();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>

		<?php if ($accounts) { ?>
		<?php foreach ($accounts as $account) { ?>
		<div class="table-responsive">
			<h4><?php echo $account['code'] . ' - ' . $account['title']; ?></h4>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_entry; ?></td>
						<td class="left"><?php echo $column_concept; ?></td>
						<td class="right"><?php echo $column_debit; ?></td>
						<td class="right"><?php echo $column_credit; ?></td>
						<td class="right"><?php echo $column_balance; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($account['rows'] as $row) { ?>
					<tr>
						<td class="text-left"><?php echo trim($row['entry_id'] . ' ' . $row['line_date']); ?></td>
						<td class="text-left"><?php echo $row['concept']; ?></td>
						<td class="text-right"><?php echo $row['debit']; ?></td>
						<td class="text-right"><?php echo $row['credit']; ?></td>
						<td class="text-right"><?php echo $row['balance']; ?></td>
					</tr>
					<?php } ?>
					<tr class="fw-bold">
						<td class="text-left" colspan="2"><?php echo $text_total; ?></td>
						<td class="text-right"><?php echo $account['total_debit']; ?></td>
						<td class="text-right"><?php echo $account['total_credit']; ?></td>
						<td class="text-right"><?php echo $account['balance']; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php } ?>
		<?php } else { ?>
		<div class="text-center"><?php echo $text_no_results; ?></div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript"><!--
function buildFilterParams() {
	var params = '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());
	params += '&filter_account_start=' + encodeURIComponent($('#filter-account-start').val());
	params += '&filter_account_end=' + encodeURIComponent($('#filter-account-end').val());
	params += '&show_opening_balance=' + ($('#show-opening-balance').is(':checked') ? '1' : '');
	params += '&new_page_per_account=' + ($('#new-page-per-account').is(':checked') ? '1' : '');

	return params;
}

function filter() {
	location = 'index.php?route=report/ledger&token=<?php echo $token; ?>' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/ledger/export&token=<?php echo $token; ?>' + buildFilterParams();
}

function printLedger() {
	window.open('index.php?route=report/ledger/printout&token=<?php echo $token; ?>&format=pdf' + buildFilterParams(), '_blank');
}
//--></script>

<?php echo $footer; ?>
