<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-4" style="gap:.5rem;">
				<div class="d-flex flex-wrap align-items-center" style="gap:.5rem;">
					<strong><?php echo $text_period; ?>:</strong>
					<div class="input-group" style="width:150px;">
						<input type="text" id="filter-date-start" class="form-control date" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_from; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
					<div class="input-group" style="width:150px;">
						<input type="text" id="filter-date-end" class="form-control date" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_to; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>

					<strong class="ms-3"><?php echo $text_accounts; ?>:</strong>
					<input type="text" id="filter-account-start" class="form-control" style="width:120px;" value="<?php echo $filter_account_start; ?>" placeholder="<?php echo $entry_from; ?>">
					<input type="text" id="filter-account-end" class="form-control" style="width:120px;" value="<?php echo $filter_account_end; ?>" placeholder="<?php echo $entry_to; ?>">

					<div class="form-check mb-0 ms-3">
						<input type="checkbox" class="form-check-input" id="show-opening-balance" <?php echo $show_opening_balance ? 'checked' : ''; ?>>
						<label class="form-check-label" for="show-opening-balance"><?php echo $text_show_opening_balance; ?></label>
					</div>
					<div class="form-check mb-0 ms-3">
						<input type="checkbox" class="form-check-input" id="new-page-per-account" <?php echo $new_page_per_account ? 'checked' : ''; ?>>
						<label class="form-check-label" for="new-page-per-account"><?php echo $text_new_page_per_account; ?></label>
					</div>
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="printLedger();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>

		<?php if ($accounts) { ?>
		<div class="ledger-report">
			<div class="report-title"><?php echo $heading_title; ?></div>
			<table class="meta">
				<tr>
					<td class="label"><?php echo $text_company; ?>:</td>
					<td><?php echo $company_name; ?></td>
					<td class="label"><?php echo $text_accounts; ?>:</td>
					<td><?php echo $account_range; ?></td>
				</tr>
				<tr>
					<td class="label"><?php echo $text_period; ?>:</td>
					<td><?php echo $period; ?></td>
					<td class="label"><?php echo $text_print_date; ?>:</td>
					<td><?php echo $print_date; ?></td>
				</tr>
			</table>

			<?php foreach ($accounts as $account) { ?>
			<hr>
			<table class="meta">
				<tr>
					<td class="label"><?php echo $text_account; ?>:</td>
					<td><?php echo $account['code']; ?></td>
					<td><?php echo $account['title']; ?></td>
				</tr>
			</table>
			<table class="lines">
				<thead>
					<tr>
						<th><?php echo $column_entry; ?></th>
						<th><?php echo $column_concept; ?></th>
						<th class="text-right"><?php echo $column_debit; ?></th>
						<th class="text-right"><?php echo $column_credit; ?></th>
						<th class="text-right"><?php echo $column_balance; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($account['rows'] as $row) { ?>
					<tr>
						<td><?php echo trim($row['entry_id'] . ' ' . $row['line_date']); ?></td>
						<td><?php echo $row['concept']; ?></td>
						<td class="text-right"><?php echo $row['debit']; ?></td>
						<td class="text-right"><?php echo $row['credit']; ?></td>
						<td class="text-right"><?php echo $row['balance']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2"><?php echo $text_total; ?></td>
						<td class="text-right"><?php echo $account['total_debit']; ?></td>
						<td class="text-right"><?php echo $account['total_credit']; ?></td>
						<td class="text-right"><?php echo $account['balance']; ?></td>
					</tr>
				</tfoot>
			</table>
			<?php } ?>
		</div>
		<?php } else { ?>
		<div class="table-responsive">
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
					<tr>
						<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
</div>
<style>
.ledger-report { font-family: helvetica, arial, sans-serif; margin-top: 15px; }
.ledger-report .report-title { text-align: center; font-size: 22px; font-weight: bold; padding-bottom: 14px; text-transform: uppercase; }
.ledger-report table.meta { margin-bottom: 6px; }
.ledger-report table.meta td { padding: 2px 6px; font-size: 14px; }
.ledger-report table.meta .label { font-weight: bold; white-space: nowrap; }
.ledger-report hr { border: none; border-top: 1px solid #000000; margin: 10px 0; }
.ledger-report table.lines { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.ledger-report table.lines th { text-align: left; border-bottom: 1px solid #000000; padding: 4px 6px; font-size: 12px; white-space: nowrap; }
.ledger-report table.lines td { padding: 3px 6px; font-size: 13px; }
.ledger-report table.lines tfoot td { border-top: 1px solid #000000; font-weight: bold; }
.ledger-report .text-right { text-align: right; }
</style>
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
