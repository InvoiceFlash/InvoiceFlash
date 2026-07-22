<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-file-invoice-dollar"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem;">
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
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="printMod303();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>

		<?php if ($filtered) { ?>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_code; ?></td>
						<td class="left"><?php echo $column_name; ?></td>
						<td class="right"><?php echo $column_amount; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($rows) { ?>
					<?php foreach ($rows as $row) { ?>
					<tr<?php echo $row['bold'] ? ' class="fw-bold"' : ''; ?>>
						<td class="text-left"><?php echo $row['code']; ?></td>
						<td class="text-left"><?php echo $row['name']; ?></td>
						<td class="text-right"><?php echo $row['amount']; ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
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
	var params = '&filter_date_start=' + encodeURIComponent($('#filter-date-start').val());
	params += '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());

	return params;
}

function filter() {
	location = 'index.php?route=report/mod303&token=<?php echo $token; ?>&filtered=1' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/mod303/export&token=<?php echo $token; ?>' + buildFilterParams();
}

function printMod303() {
	window.open('index.php?route=report/mod303/printout&token=<?php echo $token; ?>&format=pdf' + buildFilterParams(), '_blank');
}
//--></script>

<?php echo $footer; ?>
