<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'cog'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><?php echo $column_name; ?></th>
						<th class="hidden-xs"><?php echo $column_url; ?></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($stores) { ?>
					<?php foreach ($stores as $store) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($store['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>">
							<?php } ?></td>
						<td><?php echo $store['name']; ?></td>
						<td class="hidden-xs"><?php echo $store['url']; ?></td>
						<td class="text-right"><?php foreach ($store['action'] as $action) { ?>
							<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
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
	</div>
</div>
<?php echo $footer; ?> 