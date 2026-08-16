<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-exchange-alt"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<div id="filter" class="well">
			<div class="row">
				<div class="col-sm-3">
					<input type="text" class="form-control" name="filter_product" value="<?php echo $filter_product; ?>" placeholder="<?php echo $entry_product; ?>">
				</div>
				<div class="col-sm-2">
					<select name="filter_movement_type" title="<?php echo $entry_movement_type; ?>" class="form-control">
						<option value=""><?php echo $text_all; ?></option>
						<option value="in" <?php echo ($filter_movement_type == 'in') ? 'selected=""' : ''; ?>><?php echo $text_in; ?></option>
						<option value="out" <?php echo ($filter_movement_type == 'out') ? 'selected=""' : ''; ?>><?php echo $text_out; ?></option>
					</select>
				</div>
				<div class="col-sm-2">
					<select name="filter_document_type" title="<?php echo $entry_document_type; ?>" class="form-control">
						<option value=""><?php echo $text_all; ?></option>
						<option value="sale_delivery" <?php echo ($filter_document_type == 'sale_delivery') ? 'selected=""' : ''; ?>><?php echo $text_sale_delivery; ?></option>
						<option value="purchase_reception" <?php echo ($filter_document_type == 'purchase_reception') ? 'selected=""' : ''; ?>><?php echo $text_purchase_reception; ?></option>
					</select>
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" name="filter_party" value="<?php echo $filter_party; ?>" placeholder="<?php echo $entry_party; ?>">
				</div>
				<div class="col-sm-2 text-right">
					<button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control date" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control date" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-sm-6 text-right">
					<button type="button" onclick="exportExcel();" class="btn btn-success ms-1"><i class="fa fa-file-excel"></i> <?php echo $button_export; ?></button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td class="left"><?php echo $column_date_added; ?></td>
					<td class="left"><?php echo $column_product; ?></td>
					<td class="left"><?php echo $column_model; ?></td>
					<td class="left"><?php echo $column_movement_type; ?></td>
					<td class="right"><?php echo $column_quantity; ?></td>
					<td class="right"><?php echo $column_balance_after; ?></td>
					<td class="left"><?php echo $column_document; ?></td>
					<td class="left"><?php echo $column_party; ?></td>
					<td class="left"><?php echo $column_user; ?></td>
				</tr>
			</thead>
			<tbody>
				<?php if ($movements) { ?>
				<?php foreach ($movements as $movement) { ?>
				<tr>
					<td class="text-left"><?php echo $movement['date_added']; ?></td>
					<td class="text-left"><a href="<?php echo $movement['product_href']; ?>"><?php echo $movement['product_name']; ?></a></td>
					<td class="text-left"><?php echo $movement['model']; ?></td>
					<td class="text-left">
						<?php if ($movement['movement_type'] == 'in') { ?>
						<span class="badge bg-success"><?php echo $movement['movement_label']; ?></span>
						<?php } else { ?>
						<span class="badge bg-danger"><?php echo $movement['movement_label']; ?></span>
						<?php } ?>
					</td>
					<td class="text-right"><?php echo ($movement['movement_type'] == 'in' ? '+' : '-') . $movement['quantity']; ?></td>
					<td class="text-right"><?php echo $movement['balance_after']; ?></td>
					<td class="text-left"><?php if ($movement['document_href']) { ?><a href="<?php echo $movement['document_href']; ?>"><?php echo $movement['document_label']; ?></a><?php } else { ?><?php echo $movement['document_label']; ?><?php } ?></td>
					<td class="text-left"><?php echo $movement['party_name']; ?></td>
					<td class="text-left"><?php echo $movement['username']; ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $pagination; ?></div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
function buildFilterParams() {
	var params = '';
	var filter_product = $('input[name="filter_product"]').val();
	if (filter_product) params += '&filter_product=' + encodeURIComponent(filter_product);
	var filter_movement_type = $('select[name="filter_movement_type"]').val();
	if (filter_movement_type) params += '&filter_movement_type=' + encodeURIComponent(filter_movement_type);
	var filter_document_type = $('select[name="filter_document_type"]').val();
	if (filter_document_type) params += '&filter_document_type=' + encodeURIComponent(filter_document_type);
	var filter_party = $('input[name="filter_party"]').val();
	if (filter_party) params += '&filter_party=' + encodeURIComponent(filter_party);
	var filter_date_start = $('input[name="filter_date_start"]').val();
	if (filter_date_start) params += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	var filter_date_end = $('input[name="filter_date_end"]').val();
	if (filter_date_end) params += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	return params;
}
function filter() {
	location = 'index.php?route=report/stock_movement&token=<?php echo $token; ?>' + buildFilterParams();
}
function exportExcel() {
	location = 'index.php?route=report/stock_movement/export&token=<?php echo $token; ?>' + buildFilterParams();
}
//--></script>

<?php echo $footer; ?>
