<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem;">
				<div class="d-flex flex-wrap align-items-center" style="gap:.5rem;">
					<div class="form-check mb-0">
						<input type="radio" class="form-check-input" name="filter_mode" id="mode-number" value="number" <?php echo ($filter_mode == 'number') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-number"><?php echo $text_by_number; ?></label>
					</div>
					<input type="number" id="filter-number-start" class="form-control" style="width:90px;" value="<?php echo $filter_number_start; ?>" min="1" placeholder="<?php echo $entry_from; ?>">
					<input type="number" id="filter-number-end" class="form-control" style="width:90px;" value="<?php echo $filter_number_end; ?>" min="1" placeholder="<?php echo $entry_to; ?>">

					<div class="form-check mb-0 ms-3">
						<input type="radio" class="form-check-input" name="filter_mode" id="mode-date" value="date" <?php echo ($filter_mode != 'number') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="mode-date"><?php echo $text_by_date; ?></label>
					</div>
					<div class="input-group" style="width:150px;">
						<input type="text" id="filter-date-start" class="form-control date" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_from; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
					<div class="input-group" style="width:150px;">
						<input type="text" id="filter-date-end" class="form-control date" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_to; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="printJournal();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_entry; ?></td>
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
						<td class="text-left"><?php echo $row['entry_id'] . ' ' . $row['line_date']; ?></td>
						<td class="text-left"><?php echo $row['account']; ?></td>
						<td class="text-left"><?php echo $row['description']; ?></td>
						<td class="text-left"><?php echo $row['concept']; ?></td>
						<td class="text-right"><?php echo $row['debit']; ?></td>
						<td class="text-right"><?php echo $row['credit']; ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
function journalToggleMode() {
	var byNumber = $('#mode-number').is(':checked');

	$('#filter-number-start, #filter-number-end').prop('disabled', !byNumber);
	$('#filter-date-start, #filter-date-end').prop('disabled', byNumber);
}

$('input[name="filter_mode"]').on('change', journalToggleMode);
journalToggleMode();

function buildFilterParams() {
	var mode = $('input[name="filter_mode"]:checked').val();
	var params = '&filter_mode=' + mode;

	if (mode == 'number') {
		params += '&filter_number_start=' + encodeURIComponent($('#filter-number-start').val());
		params += '&filter_number_end=' + encodeURIComponent($('#filter-number-end').val());
	} else {
		params += '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
		params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());
	}

	return params;
}

function filter() {
	location = 'index.php?route=report/journal&token=<?php echo $token; ?>' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/journal/export&token=<?php echo $token; ?>' + buildFilterParams();
}

function printJournal() {
	window.open('index.php?route=report/journal/printout&token=<?php echo $token; ?>&format=pdf' + buildFilterParams(), '_blank');
}
//--></script>

<?php echo $footer; ?>
