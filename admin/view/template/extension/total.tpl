<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fab fa-btc"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th><?php echo $column_name; ?></th>
					<th class="hidden-xs"><?php echo $column_status; ?></th>
					<th class="text-right hidden-xs"><?php echo $column_sort_order; ?></th>
					<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
				</tr>
			</thead>
			<tbody data-link="row" class="rowlink">
				<?php if ($extensions) { ?>
				<?php foreach ($extensions as $extension) { ?>
				<tr>
					<td><?php echo $extension['name']; ?></td>
					<td class="hidden-xs text-<?php echo strtolower($extension['status']); ?>"><?php echo $extension['status'] ?></td>
					<td class="text-right hidden-xs"><?php echo $extension['sort_order']; ?></td>
					<td class="text-right"><?php foreach ($extension['action'] as $action) { ?>
						<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
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
	</div>
</div>
<?php echo $footer; ?>