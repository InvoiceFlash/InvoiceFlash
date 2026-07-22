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
					<span class="text-muted"><?php echo $text_as_of; ?>:</span>
					<div class="input-group" style="width:150px;">
						<input type="text" id="filter-date-end" class="form-control date" value="<?php echo $filter_date_end; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="printBalanceSheet();" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $button_print; ?></button>
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>

		<div class="balance-sheet">
			<?php foreach ($statements as $statement) { ?>
			<div class="bs-statement-title"><?php echo $statement['title']; ?></div>
			<?php if ($statement['sections']) { ?>
			<?php foreach ($statement['sections'] as $section) { ?>
			<div class="bs-row bs-section">
				<span class="bs-label"><?php echo $section['label']; ?></span>
				<span class="bs-amount"><?php echo $section['total']; ?></span>
			</div>
			<?php foreach ($section['groups'] as $group) { ?>
			<div class="bs-row bs-group">
				<span class="bs-label"><?php echo $group['label']; ?></span>
				<span class="bs-amount"><?php echo $group['total']; ?></span>
			</div>
			<?php foreach ($group['rows'] as $row) { ?>
			<div class="bs-row bs-account">
				<span class="bs-label"><?php echo $row['code'] . ' ' . $row['title']; ?></span>
				<span class="bs-amount"><?php echo $row['amount']; ?></span>
			</div>
			<?php } ?>
			<?php } ?>
			<?php } ?>
			<div class="bs-row bs-total">
				<span class="bs-label"><?php echo $text_total; ?> <?php echo $statement['title']; ?></span>
				<span class="bs-amount"><?php echo $statement['total']; ?></span>
			</div>
			<?php } else { ?>
			<div class="bs-row"><span class="bs-label"><?php echo $text_no_results; ?></span></div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<style>
.balance-sheet { font-family: helvetica, arial, sans-serif; margin-top: 15px; max-width: 900px; }
.bs-statement-title { font-size: 20px; font-weight: bold; text-transform: uppercase; padding: 20px 0 10px 0; border-bottom: 1px solid #000000; margin-bottom: 8px; }
.bs-row { display: flex; justify-content: space-between; padding: 3px 0; }
.bs-section .bs-label, .bs-section .bs-amount { font-weight: bold; }
.bs-group { padding-left: 20px; }
.bs-group .bs-label, .bs-group .bs-amount { font-weight: bold; }
.bs-account { padding-left: 40px; }
.bs-account .bs-amount { color: #0645ad; }
.bs-total { border-top: 1px solid #000000; margin-top: 6px; padding-top: 6px; font-weight: bold; }
</style>
<script type="text/javascript"><!--
function buildFilterParams() {
	return '&filter_date_end=' + encodeURIComponent($('#filter-date-end').val());
}

function filter() {
	location = 'index.php?route=report/balance_sheet&token=<?php echo $token; ?>' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/balance_sheet/export&token=<?php echo $token; ?>' + buildFilterParams();
}

function printBalanceSheet() {
	window.open('index.php?route=report/balance_sheet/printout&token=<?php echo $token; ?>&format=pdf' + buildFilterParams(), '_blank');
}
//--></script>

<?php echo $footer; ?>
