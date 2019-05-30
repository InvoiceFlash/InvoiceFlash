<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'shopping-cart'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_status_id ?>"><?php echo $column_status_id; echo ($sort=='order_status_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($order_statuses) { ?>
					<?php foreach ($order_statuses as $order_status) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($order_status['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $order_status['order_status_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $order_status['order_status_id']; ?>">
							<?php } ?></td>
						<td><?php echo $order_status['order_status_id'] ?></td>
						<td><?php echo $order_status['name']; ?></td>
						<td class="text-right"><?php foreach ($order_status['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fas fa-edit"></i><?php echo $action['text']; ?></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?> 