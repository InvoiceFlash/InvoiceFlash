<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'percent'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_percent; ?>"><?php echo $column_percent; echo ($sort == 'percent') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; echo ($sort == 'date_end') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($tariffs) { ?>
					<?php foreach ($tariffs as $tariff) { ?>
					<tr>
						<td class="rowlink-skip text-center"><input type="checkbox" name="selected[]" value="<?php echo $tariff['tariff_id']; ?>" <?php echo $tariff['selected'] ? 'checked' : ''; ?>></td>
						<td><?php echo $tariff['name']; ?></td>
						<td><?php echo $tariff['percent']; ?></td>
						<td><?php echo $tariff['date_end']; ?></td>
						<td class="text-right"><?php foreach ($tariff['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><?php echo $action['icon']; ?><?php echo $action['text']; ?></a>
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
