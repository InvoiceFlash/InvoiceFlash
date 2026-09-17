<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-dolly"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="button" data-url="<?php echo $invoice; ?>" onclick="submitReceptions(this.dataset.url);" class="btn btn-default btn-spacer"><i class="far fa-eye"></i><span class="hidden-xs"> View</span></button>
			<button type="button" data-url="<?php echo $printPDF; ?>" onclick="submitReceptions(this.dataset.url);" class="btn btn-default btn-spacer"><i class="fa fa-file-pdf"></i><span class="hidden-xs"> PDF</span></button>
			<button type="submit" form="form" formaction="<?php echo $convert; ?>" onclick="return confirm(text_confirm);" id="btn-convert" class="btn btn-success"><i class="fa fa-exchange-alt"></i><span class="hidden-xs"> <?php echo $button_convert_invoice; ?></span></button>
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="foe" method="post" enctype="multipart/form-data" id="form" name="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-right"><a href="<?php echo $sort_reception_id; ?>"><?php echo $column_reception_id; echo ($sort == 'r.reception_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_supplier; ?>"><?php echo $column_supplier; echo ($sort == 'supplier_company') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_total; ?>"><?php echo $column_total; echo ($sort == 'r.total') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'r.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs hidden-sm"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'r.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=purchase/reception&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td class="text-right"><input type="text" name="filter_reception_id" value="<?php echo $filter_reception_id; ?>" class="form-control text-right"></td>
						<td><input type="text" name="filter_supplier" value="<?php echo $filter_supplier; ?>" class="form-control"></td>
						<td class="hidden-xs"><select name="filter_reception_status_id" class="form-control">
							<option value="*">&ndash;</option>
							<?php if ($filter_reception_status_id == '0') { ?>
							<option value="0" selected=""><?php echo $text_missing; ?></option>
							<?php } else { ?>
							<option value="0"><?php echo $text_missing; ?></option>
							<?php } ?>
							<?php foreach ($reception_statuses as $reception_status) { ?>
							<?php if ($reception_status['reception_status_id'] == $filter_reception_status_id) { ?>
							<option value="<?php echo $reception_status['reception_status_id']; ?>" selected=""><?php echo $reception_status['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $reception_status['reception_status_id']; ?>"><?php echo $reception_status['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control text-right"></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="hidden-xs"></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($receptions) { ?>
					<?php foreach ($receptions as $reception) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($reception['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $reception['reception_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $reception['reception_id']; ?>">
							<?php } ?></td>
						<td class="text-right"><?php echo $reception['reception_id']; ?></td>
						<td><?php echo $reception['supplier']; ?></td>
						<td class="hidden-xs"><?php echo $reception['status']; ?></td>
						<td class="text-right hidden-xs"><?php echo $reception['total']; ?></td>
						<td class="hidden-xs"><?php echo $reception['date_added']; ?></td>
						<td class="hidden-xs hidden-sm"><?php echo $reception['date_modified']; ?></td>
						<td class="text-right"><?php foreach ($reception['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>" class="btn btn-<?php echo $action['color']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<script>
function submitReceptions(url) {
	if (!$('input[type="checkbox"]').is(':checked')) {
		alert('<?php echo $error_no_selection; ?>');
	} else {
		var form = document.getElementById('form');
		form.setAttribute('action', url);
		form.setAttribute('target', '_blank');
		document.form.submit();
	}
}
</script>
<?php echo $footer; ?>
