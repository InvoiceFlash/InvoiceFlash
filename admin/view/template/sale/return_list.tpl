<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'undo'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_return_id; ?>"><?php echo $column_return_id; echo ($sort == 'r.return_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; echo ($sort == 'r.order_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; echo ($sort == 'customer') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_product; ?>"><?php echo $column_product; echo ($sort == 'r.product') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_model; ?>"><?php echo $column_model; echo ($sort == 'r.model') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'r.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'r.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=sale/return&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_return_id" value="<?php echo $filter_return_id; ?>" class="form-control text-right"></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" class="form-control text-right"></td>
						<td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" class="form-control" data-target="name" data-url="sale/customer" class="form-control"></td>
						<td class="hidden-xs"><input type="text" name="filter_product" value="<?php echo $filter_product; ?>" class="form-control" data-target="name" data-url="catalog/product" class="form-control"></td>
						<td class="hidden-xs"><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" class="form-control" data-target="model" data-url="catalog/product" class="form-control"></td>
						<td><select name="filter_return_status_id" class="form-control">
							<option value="*">&ndash;</option>
							<?php foreach ($return_statuses as $return_status) { ?>
							<?php if ($return_status['return_status_id'] == $filter_return_status_id) { ?>
							<option value="<?php echo $return_status['return_status_id']; ?>" selected=""><?php echo $return_status['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_modified" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($returns) { ?>
					<?php foreach ($returns as $return) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($return['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>">
							<?php } ?></td>
						<td class="text-right hidden-xs"><?php echo $return['return_id']; ?></td>
						<td class="text-right hidden-xs"><?php echo $return['order_id']; ?></td>
						<td><?php echo $return['customer']; ?></td>
						<td class="hidden-xs"><?php echo $return['product']; ?></td>
						<td class="hidden-xs"><?php echo $return['model']; ?></td>
						<td class="text-<?php echo strtolower($return['status']); ?>"><?php echo $return['status']; ?></td>
						<td class="hidden-xs"><?php echo $return['date_added']; ?></td>
						<td class="hidden-xs"><?php echo $return['date_modified']; ?></td>
						<td class="text-right"><?php foreach ($return['action'] as $action) { ?>
							<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
							<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?> 