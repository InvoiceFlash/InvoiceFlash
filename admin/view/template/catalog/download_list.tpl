<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'download'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'dd.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_remaining; ?>"><?php echo $column_remaining; echo ($sort == 'd.remaining') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($downloads) { ?>
					<?php foreach ($downloads as $download) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($download['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $download['download_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $download['download_id']; ?>">
							<?php } ?></td>
						<td><?php echo $download['name']; ?></td>
						<td class="text-right hidden-xs"><?php echo $download['remaining']; ?></td>
						<td class="text-right"><?php foreach ($download['action'] as $action) { ?>
							<span class="bracket"><a href="<?php echo $action['href']; ?>"><span class="d-none d-sm-none d-md-inline"><?php echo $action['text']; ?></span></a></span>
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