<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'user'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'cgd.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; echo ($sort == 'cg.sort_order') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($customer_groups) { ?>
					<?php foreach ($customer_groups as $customer_group) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($customer_group['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>">
							<?php } ?></td>
						<td><?php echo $customer_group['name']; ?></td>
						<td class="text-right hidden-xs"><?php echo $customer_group['sort_order']; ?></td>
						<td class="text-right"><?php foreach ($customer_group['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
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