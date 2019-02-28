<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'globe'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_country; ?>"><?php echo $column_country; echo ($sort == 'c.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'z.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_code; ?>"><?php echo $column_code; echo ($sort == 'z.code') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($zones) { ?>
					<?php foreach ($zones as $zone) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($zone['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $zone['zone_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $zone['zone_id']; ?>">
							<?php } ?></td>
						<td><?php echo $zone['country']; ?></td>
						<td><?php echo $zone['name']; ?></td>
						<td class="hidden-xs"><?php echo $zone['code']; ?></td>
						<td class="text-right"><?php foreach ($zone['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <span class="hidden-xs"><?php echo $action['text']; ?></span></a>
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