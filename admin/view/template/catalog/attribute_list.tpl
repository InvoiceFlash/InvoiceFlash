<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'list-alt'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'ad.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_attribute_group; ?>"><?php echo $column_attribute_group; echo ($sort == 'attribute_group') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; echo ($sort == 'a.sort_order') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($attributes) { ?>
					<?php foreach ($attributes as $attribute) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($attribute['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $attribute['attribute_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $attribute['attribute_id']; ?>">
							<?php } ?></td>
						<td><?php echo $attribute['name']; ?></td>
						<td><?php echo $attribute['attribute_group']; ?></td>
						<td class="text-right hidden-xs"><?php echo $attribute['sort_order']; ?></td>
						<td class="text-right"><?php foreach ($attribute['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>" class="btn btn-default"><i class="fa fa-edit"></i> <span class="d-none d-sm-none d-md-inline"><?php echo $action['text']; ?></span></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>