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
						<input type="checkbox" class="form-check-input" id="include-zero-balance" <?php echo $include_zero_balance ? 'checked' : ''; ?>>
						<label class="form-check-label" for="include-zero-balance"><?php echo $text_include_zero_balance; ?></label>
					</div>
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="printTrialBalance();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>

			<div class="d-flex flex-wrap align-items-center" style="gap:1.5rem;">
				<div class="d-flex flex-wrap align-items-center" style="gap:.75rem;">
					<strong><?php echo $text_breakdown_levels; ?>:</strong>
					<div class="form-check mb-0">
						<input type="checkbox" class="form-check-input breakdown-level" id="level-1" value="1" <?php echo in_array('1', $filter_levels) ? 'checked' : ''; ?>>
						<label class="form-check-label" for="level-1"><?php echo $text_level_1; ?></label>
					</div>
					<div class="form-check mb-0">
						<input type="checkbox" class="form-check-input breakdown-level" id="level-2" value="2" <?php echo in_array('2', $filter_levels) ? 'checked' : ''; ?>>
						<label class="form-check-label" for="level-2"><?php echo $text_level_2; ?></label>
					</div>
					<div class="form-check mb-0">
						<input type="checkbox" class="form-check-input breakdown-level" id="level-3" value="3" <?php echo in_array('3', $filter_levels) ? 'checked' : ''; ?>>
						<label class="form-check-label" for="level-3"><?php echo $text_level_3; ?></label>
					</div>
					<div class="form-check mb-0">
						<input type="checkbox" class="form-check-input breakdown-level" id="level-4" value="4" <?php echo in_array('4', $filter_levels) ? 'checked' : ''; ?>>
						<label class="form-check-label" for="level-4"><?php echo $text_level_4; ?></label>
					</div>
					<div class="form-check mb-0">
						<input type="checkbox" class="form-check-input breakdown-level" id="level-sub" value="sub" <?php echo in_array('sub', $filter_levels) ? 'checked' : ''; ?>>
						<label class="form-check-label" for="level-sub"><?php echo $text_subaccounts_level; ?></label>
					</div>
				</div>

				<div class="d-flex flex-wrap align-items-center" style="gap:.75rem;">
					<strong><?php echo $text_balance_columns; ?>:</strong>
					<div class="form-check mb-0">
						<input type="radio" class="form-check-input" name="balance_columns" id="balance-columns-one" value="one" <?php echo ($balance_columns == 'one') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="balance-columns-one"><?php echo $text_one_column; ?></label>
					</div>
					<div class="form-check mb-0">
						<input type="radio" class="form-check-input" name="balance_columns" id="balance-columns-two" value="two" <?php echo ($balance_columns == 'two') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="balance-columns-two"><?php echo $text_two_columns; ?></label>
					</div>
				</div>
			</div>
		</div>

		<?php if ($filtered) { ?>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_account; ?></td>
						<td class="left"><?php echo $column_title; ?></td>
						<td class="right"><?php echo $column_debit; ?></td>
						<td class="right"><?php echo $column_credit; ?></td>
						<?php if ($balance_columns == 'one') { ?>
						<td class="right"><?php echo $column_balance; ?></td>
						<?php } else { ?>
						<td class="right"><?php echo $column_debit_balance; ?></td>
						<td class="right"><?php echo $column_credit_balance; ?></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php if ($result['rows']) { ?>
					<?php foreach ($result['rows'] as $row) { ?>
					<tr>
						<td><?php echo $row['code']; ?></td>
						<td><?php echo $row['title']; ?></td>
						<td class="text-right"><?php echo $row['debit']; ?></td>
						<td class="text-right"><?php echo $row['credit']; ?></td>
						<?php if ($balance_columns == 'one') { ?>
						<td class="text-right"><?php echo $row['balance']; ?></td>
						<?php } else { ?>
						<td class="text-right"><?php echo $row['debit_balance']; ?></td>
						<td class="text-right"><?php echo $row['credit_balance']; ?></td>
						<?php } ?>
					</tr>
					<?php } ?>
					<tr class="fw-bold">
						<td class="text-left" colspan="2"><?php echo $text_total; ?></td>
						<td class="text-right"><?php echo $result['total_debit']; ?></td>
						<td class="text-right"><?php echo $result['total_credit']; ?></td>
						<?php if ($balance_columns == 'one') { ?>
						<td class="text-right"><?php echo $result['total_balance']; ?></td>
						<?php } else { ?>
						<td class="text-right"><?php echo $result['total_debit_balance']; ?></td>
						<td class="text-right"><?php echo $result['total_credit_balance']; ?></td>
						<?php } ?>
					</tr>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="<?php echo ($balance_columns == 'one') ? '5' : '6'; ?>"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript"><!--
function buildFilterParams() {
	var levels = [];
	$('.breakdown-level:checked').each(function() { levels.push($(this).val()); });

	var params = '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());
	params += '&filter_account_start=' + encodeURIComponent($('#filter-account-start').val());
	params += '&filter_account_end=' + encodeURIComponent($('#filter-account-end').val());
	params += '&include_zero_balance=' + ($('#include-zero-balance').is(':checked') ? '1' : '');
	params += '&filter_levels=' + encodeURIComponent(levels.join(','));
	params += '&balance_columns=' + $('input[name="balance_columns"]:checked').val();

	return params;
}

function filter() {
	location = 'index.php?route=report/trial_balance&token=<?php echo $token; ?>&filtered=1' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/trial_balance/export&token=<?php echo $token; ?>' + buildFilterParams();
}

function printTrialBalance() {
	window.open('index.php?route=report/trial_balance/printout&token=<?php echo $token; ?>&format=pdf' + buildFilterParams(), '_blank');
}
//--></script>

<?php echo $footer; ?>
