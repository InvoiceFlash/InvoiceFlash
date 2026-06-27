<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-truck"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $export; ?>" id="btn-export" class="btn btn-success"><i class="fa fa-file-excel"></i><span class="hidden-xs"> <?php echo $button_export; ?></span></button>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="foe" method="post" enctype="multipart/form-data" id="form" name="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_company; ?>"><?php echo $column_company; echo ($sort == 'company') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><?php echo $column_name; ?></th>
						<th><a href="<?php echo $sort_email; ?>"><?php echo $column_email; echo ($sort == 'email') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><?php echo $column_telephone; ?></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs hidden-sm"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=purchase/supplier&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td><input type="text" name="filter_company" value="<?php echo $filter_company; ?>" class="form-control"></td>
						<td class="hidden-xs"></td>
						<td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" class="form-control"></td>
						<td class="hidden-xs"></td>
						<td class="hidden-xs"><select name="filter_status" class="form-control">
							<option value="">&ndash;</option>
							<?php if ($filter_status === '1') { ?>
							<option value="1" selected=""><?php echo $text_enabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<?php } ?>
							<?php if ($filter_status === '0') { ?>
							<option value="0" selected=""><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select></td>
						<td class="hidden-xs hidden-sm"></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($suppliers) { ?>
					<?php foreach ($suppliers as $supplier) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($supplier['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $supplier['supplier_id']; ?>">
							<?php } ?></td>
						<td><?php echo $supplier['company']; ?></td>
						<td class="hidden-xs"><?php echo $supplier['name']; ?></td>
						<td><?php echo $supplier['email']; ?></td>
						<td class="hidden-xs"><?php echo $supplier['telephone']; ?></td>
						<td class="hidden-xs"><?php echo $supplier['status']; ?></td>
						<td class="hidden-xs hidden-sm"><?php echo $supplier['date_added']; ?></td>
						<td class="text-right"><?php foreach ($supplier['action'] as $action) { ?>
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
<?php echo $footer; ?>
