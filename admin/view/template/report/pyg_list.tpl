<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="d-flex flex-wrap align-items-center justify-content-between mb-2" style="gap:.5rem;">
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

					<div class="form-check mb-0 ms-3">
						<input type="checkbox" class="form-check-input" id="compare" <?php echo $compare ? 'checked' : ''; ?>>
						<label class="form-check-label" for="compare"><?php echo $text_compare; ?></label>
					</div>
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="printPyg();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>

			<div id="fields-compare" class="d-flex flex-wrap align-items-center<?php echo $compare ? '' : ' d-none'; ?>" style="gap:.5rem;">
				<div class="input-group" style="width:150px;">
					<input type="text" id="filter-date-start-prev" class="form-control date" value="<?php echo $filter_date_start_prev; ?>" placeholder="<?php echo $entry_from; ?>">
					<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
				</div>
				<div class="input-group" style="width:150px;">
					<input type="text" id="filter-date-end-prev" class="form-control date" value="<?php echo $filter_date_end_prev; ?>" placeholder="<?php echo $entry_to; ?>">
					<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
				</div>
			</div>
		</div>

		<?php if ($filtered) { ?>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_concept; ?></td>
						<td class="right"><?php echo $column_amount; ?></td>
						<?php if ($compare) { ?>
						<td class="right"><?php echo $column_amount_prev; ?></td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php if ($rows) { ?>
					<?php foreach ($rows as $row) { ?>
					<tr<?php echo $row['bold'] ? ' class="fw-bold"' : ''; ?>>
						<td class="text-left" style="padding-left:<?php echo (20 + $row['level'] * 20); ?>px;"><?php echo $row['name']; ?></td>
						<td class="text-right"><?php echo $row['amount']; ?></td>
						<?php if ($compare) { ?>
						<td class="text-right"><?php echo $row['amount_prev']; ?></td>
						<?php } ?>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="<?php echo $compare ? '3' : '2'; ?>"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript"><!--
function pygToggleCompare() {
	var checked = $('#compare').is(':checked');

	if (checked) {
		$('#fields-compare').removeClass('d-none');

		if (!$('#filter-date-start-prev').val() && $('#filter-date-start').val()) {
			$('#filter-date-start-prev').val(pygShiftYear($('#filter-date-start').val()));
		}

		if (!$('#filter-date-end-prev').val() && $('#filter-date-end').val()) {
			$('#filter-date-end-prev').val(pygShiftYear($('#filter-date-end').val()));
		}
	} else {
		$('#fields-compare').addClass('d-none');
	}
}

function pygShiftYear(value) {
	var parts = value.split('-');

	if (parts.length != 3) {
		return value;
	}

	return parts[0] + '-' + parts[1] + '-' + (parseInt(parts[2], 10) - 1);
}

$('#compare').on('change', pygToggleCompare);

function buildFilterParams() {
	var compare = $('#compare').is(':checked');

	var params = '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());
	params += '&compare=' + (compare ? '1' : '');
	params += '&filter_date_start_prev=' + encodeURIComponent($('#filter-date-start-prev').val());
	params += '&filter_date_end_prev=' + encodeURIComponent($('#filter-date-end-prev').val());

	return params;
}

function filter() {
	location = 'index.php?route=report/pyg&token=<?php echo $token; ?>&filtered=1' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/pyg/export&token=<?php echo $token; ?>' + buildFilterParams();
}

function printPyg() {
	window.open('index.php?route=report/pyg/printout&token=<?php echo $token; ?>&format=pdf' + buildFilterParams(), '_blank');
}
//--></script>

<?php echo $footer; ?>
