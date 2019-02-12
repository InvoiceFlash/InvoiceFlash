<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'landmark'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'tr.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_rate; ?>"><?php echo $column_rate; echo ($sort == 'tr.rate') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_type; ?>"><?php echo $column_type; echo ($sort == 'tr.type') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_geo_zone; ?>"><?php echo $column_geo_zone; echo ($sort == 'gz.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'tr.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'tr.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($tax_rates) { ?>
					<?php foreach ($tax_rates as $tax_rate) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($tax_rate['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $tax_rate['tax_rate_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $tax_rate['tax_rate_id']; ?>">
							<?php } ?></td>
						<td><?php echo $tax_rate['name']; ?></td>
						<td class="text-right hidden-xs"><?php echo $tax_rate['rate']; ?></td>
						<td class="hidden-xs"><?php echo $tax_rate['type']; ?></td>
						<td class="hidden-xs"><?php echo $tax_rate['geo_zone']; ?></td>
						<td class="hidden-xs"><?php echo $tax_rate['date_added']; ?></td>
						<td class="hidden-xs"><?php echo $tax_rate['date_modified']; ?></td>
						<td class="text-right"><?php foreach ($tax_rate['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>