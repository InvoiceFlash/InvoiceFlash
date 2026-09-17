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
					<input type="number" id="filter-year" class="form-control" style="width:100px;" value="<?php echo $filter_year; ?>">

					<strong class="ms-3"><?php echo $text_quarter; ?>:</strong>
					<select id="filter-quarter" class="form-control" style="width:170px;">
						<?php foreach ($quarters as $value => $label) { ?>
						<option value="<?php echo $value; ?>" <?php echo ($value == $filter_quarter) ? 'selected' : ''; ?>><?php echo $label; ?></option>
						<?php } ?>
					</select>

					<strong class="ms-3"><?php echo $text_casilla_29; ?>:</strong>
					<input type="text" id="filter-casilla-29" class="form-control text-right" style="width:120px;" value="<?php echo $filter_casilla_29; ?>">
				</div>

				<div style="white-space:nowrap;">
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
					<button type="button" onclick="filter();" class="btn btn-info ms-1"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>

		<div class="alert alert-warning"><?php echo $text_disclaimer; ?></div>

		<div class="mod111-grid">
			<div class="mod111-row">
				<span class="mod111-label"><?php echo $text_casilla_07; ?> <span class="badge bg-secondary"><?php echo $text_estimated; ?></span></span>
				<span class="mod111-amount"><?php echo $result['casilla_07']; ?></span>
			</div>
			<div class="mod111-row">
				<span class="mod111-label"><?php echo $text_casilla_08; ?> <span class="badge bg-secondary"><?php echo $text_estimated; ?></span></span>
				<span class="mod111-amount"><?php echo $result['casilla_08']; ?></span>
			</div>
			<div class="mod111-row">
				<span class="mod111-label"><?php echo $text_casilla_09; ?></span>
				<span class="mod111-amount"><?php echo $result['casilla_09']; ?></span>
			</div>
			<div class="mod111-row mod111-total">
				<span class="mod111-label"><?php echo $text_casilla_28; ?></span>
				<span class="mod111-amount"><?php echo $result['casilla_28']; ?></span>
			</div>
			<div class="mod111-row">
				<span class="mod111-label"><?php echo $text_casilla_29; ?></span>
				<span class="mod111-amount"><?php echo $result['casilla_29']; ?></span>
			</div>
			<div class="mod111-row mod111-total">
				<span class="mod111-label"><?php echo $text_casilla_30; ?></span>
				<span class="mod111-amount"><?php echo $result['casilla_30']; ?></span>
			</div>
		</div>
	</div>
</div>
<style>
.mod111-grid { font-family: helvetica, arial, sans-serif; margin-top: 15px; max-width: 700px; }
.mod111-row { display: flex; justify-content: space-between; padding: 8px 10px; border-bottom: 1px solid #eee; }
.mod111-amount { font-weight: bold; white-space: nowrap; margin-left: 20px; }
.mod111-total { background-color: #f5f5f5; font-weight: bold; border-top: 1px solid #000; border-bottom: 1px solid #000; }
</style>
<script type="text/javascript"><!--
function buildFilterParams() {
	var params = '&filter_year=' + encodeURIComponent($('#filter-year').val());
	params += '&filter_quarter=' + encodeURIComponent($('#filter-quarter').val());
	params += '&filter_casilla_29=' + encodeURIComponent($('#filter-casilla-29').val());

	return params;
}

function filter() {
	location = 'index.php?route=report/mod111&token=<?php echo $token; ?>' + buildFilterParams();
}

function exportExcel() {
	location = 'index.php?route=report/mod111/export&token=<?php echo $token; ?>' + buildFilterParams();
}
//--></script>

<?php echo $footer; ?>
