<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-search"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-4" style="gap:.5rem;">
				<div class="d-flex flex-wrap align-items-center" style="gap:.5rem;">
					<div class="form-check mb-0">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-all" value="all" <?php echo ($filter_mode == 'all') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-all"><?php echo $text_all; ?></label>
					</div>
					<div class="form-check mb-0 ms-2">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-number" value="number" <?php echo ($filter_mode == 'number') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-number"><?php echo $text_by_number; ?></label>
					</div>
					<div class="form-check mb-0 ms-2">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-date" value="date" <?php echo ($filter_mode == 'date') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-date"><?php echo $text_by_date; ?></label>
					</div>
					<div class="form-check mb-0 ms-2">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-amount" value="amount" <?php echo ($filter_mode == 'amount') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-amount"><?php echo $text_by_amount; ?></label>
					</div>
					<div class="form-check mb-0 ms-2">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-concept" value="concept" <?php echo ($filter_mode == 'concept') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-concept"><?php echo $text_by_concept; ?></label>
					</div>
					<div class="form-check mb-0 ms-2">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-account" value="account" <?php echo ($filter_mode == 'account') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-account"><?php echo $text_by_account; ?></label>
					</div>
					<div class="form-check mb-0 ms-2">
						<input type="radio" class="form-check-input review-mode" name="filter_mode" id="mode-unbalanced" value="unbalanced" <?php echo ($filter_mode == 'unbalanced') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-unbalanced"><?php echo $text_unbalanced; ?></label>
					</div>
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="reviewExportExcel();" class="btn btn-success"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="reviewFilter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>

			<div class="d-flex flex-wrap align-items-center justify-content-start mt-2 mb-3" style="gap:.5rem;">
				<div id="fields-number" class="d-none align-items-center" style="gap:.5rem;">
					<input type="number" id="filter-number-start" class="form-control text-left" style="width:140px;" value="<?php echo $filter_number_start; ?>" min="1" placeholder="<?php echo $entry_from; ?>">
					<input type="number" id="filter-number-end" class="form-control text-left" style="width:140px;" value="<?php echo $filter_number_end; ?>" min="1" placeholder="<?php echo $entry_to; ?>">
				</div>

				<div id="fields-date" class="d-none align-items-center" style="gap:.5rem;">
					<div class="input-group" style="width:190px;">
						<input type="text" id="filter-date-start" class="form-control date text-left" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_from; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
					<div class="input-group" style="width:190px;">
						<input type="text" id="filter-date-end" class="form-control date text-left" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_to; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>

				<div id="fields-amount" class="d-none">
					<input type="text" id="filter-amount" class="form-control text-right" style="width:120px;" value="<?php echo $filter_amount; ?>" placeholder="<?php echo $entry_amount; ?>">
				</div>

				<div id="fields-concept" class="d-none">
					<input type="text" id="filter-concept" class="form-control" style="width:220px;" value="<?php echo $filter_concept; ?>" placeholder="<?php echo $column_concept; ?>">
				</div>

				<div id="fields-account" class="d-none">
					<input type="text" id="filter-account" class="form-control" style="width:150px;" value="<?php echo $filter_account; ?>" placeholder="<?php echo $column_account; ?>">
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_entry; ?></td>
						<td class="left"><?php echo $column_date; ?></td>
						<td class="left"><?php echo $column_account; ?></td>
						<td class="left"><?php echo $column_description; ?></td>
						<td class="left"><?php echo $column_concept; ?></td>
						<td class="right"><?php echo $column_debit; ?></td>
						<td class="right"><?php echo $column_credit; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($rows) { ?>
					<?php foreach ($rows as $row) { ?>
					<tr>
						<td class="text-left"><?php echo $row['entry_id']; ?></td>
						<td class="text-left"><?php echo $row['line_date']; ?></td>
						<td class="text-left"><?php echo $row['account']; ?></td>
						<td class="text-left"><?php echo $row['description']; ?></td>
						<td class="text-left"><?php echo $row['concept']; ?></td>
						<td class="text-right"><?php echo $row['debit']; ?></td>
						<td class="text-right"><?php echo $row['credit']; ?></td>
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
<script type="text/javascript"><!--
function reviewToggleMode(focusField) {
	var mode = $('input.review-mode:checked').val();

	$('#fields-number, #fields-date, #fields-amount, #fields-concept, #fields-account').addClass('d-none').removeClass('d-flex');

	var $firstInput = null;

	if (mode == 'number') {
		$('#fields-number').removeClass('d-none').addClass('d-flex');
		$firstInput = $('#filter-number-start');
	} else if (mode == 'date') {
		$('#fields-date').removeClass('d-none').addClass('d-flex');
		$firstInput = $('#filter-date-start');
	} else if (mode == 'amount') {
		$('#fields-amount').removeClass('d-none');
		$firstInput = $('#filter-amount');
	} else if (mode == 'concept') {
		$('#fields-concept').removeClass('d-none');
		$firstInput = $('#filter-concept');
	} else if (mode == 'account') {
		$('#fields-account').removeClass('d-none');
		$firstInput = $('#filter-account');
	}

	if (focusField && $firstInput) {
		$firstInput.focus();
	}
}

$('input.review-mode').on('change', function() {
	reviewToggleMode(true);
});
reviewToggleMode();

function reviewBuildParams() {
	var mode = $('input.review-mode:checked').val();
	var params = '&filter_mode=' + mode;

	if (mode == 'number') {
		params += '&filter_number_start=' + encodeURIComponent($('#filter-number-start').val());
		params += '&filter_number_end=' + encodeURIComponent($('#filter-number-end').val());
	} else if (mode == 'date') {
		params += '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
		params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());
	} else if (mode == 'amount') {
		params += '&filter_amount=' + encodeURIComponent($('#filter-amount').val());
	} else if (mode == 'concept') {
		params += '&filter_concept=' + encodeURIComponent($('#filter-concept').val());
	} else if (mode == 'account') {
		params += '&filter_account=' + encodeURIComponent($('#filter-account').val());
	}

	return params;
}

function reviewFilter() {
	location = 'index.php?route=accounting/review&token=<?php echo $token; ?>' + reviewBuildParams();
}

function reviewExportExcel() {
	location = 'index.php?route=accounting/review/export&token=<?php echo $token; ?>' + reviewBuildParams();
}
//--></script>

<?php echo $footer; ?>
