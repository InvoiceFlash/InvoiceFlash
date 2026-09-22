<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="fa fa-puzzle-piece"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="button" id="btn-clear-cache" class="btn btn-default" onclick="modManagerClearCache();"><i class="fa fa-refresh"></i><span class="hidden-xs"> <?php echo $button_clear_cache; ?></span></button>
		</div>
	</div>

	<div class="panel-body">

		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><?php echo $column_id; ?></th>
					<th><?php echo $column_file; ?></th>
					<th><?php echo $column_version; ?></th>
					<th><?php echo $column_author; ?></th>
					<th class="text-center"><?php echo $column_status; ?></th>
					<th class="text-center"><?php echo $column_action; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($mods) { ?>
				<?php foreach ($mods as $mod) { ?>
				<tr>
					<td><?php echo $mod['id']; ?></td>
					<td><?php echo $mod['file']; ?></td>
					<td><?php echo $mod['version']; ?></td>
					<td><?php echo $mod['author']; ?></td>
					<td class="text-center">
						<?php if ($mod['enabled']) { ?>
						<span class="badge bg-success"><?php echo $text_enabled; ?></span>
						<?php } else { ?>
						<span class="badge bg-secondary"><?php echo $text_disabled; ?></span>
						<?php } ?>
					</td>
					<td class="text-center">
						<?php if ($mod['enabled']) { ?>
						<a href="<?php echo $toggle_url . '&file=' . urlencode($mod['file']); ?>" class="btn btn-sm btn-warning"><?php echo $button_disable; ?></a>
						<?php } else { ?>
						<a href="<?php echo $toggle_url . '&file=' . urlencode($mod['file']); ?>" class="btn btn-sm btn-success"><?php echo $button_enable; ?></a>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td colspan="6" class="text-center"><?php echo $text_no_mods; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

	</div>

</div>

<script type="text/javascript"><!--
function modManagerClearCache() {
	if (confirm('<?php echo $text_confirm_clear_cache; ?>')) {
		location = '<?php echo $clear_cache_url; ?>';
	}
}
//--></script>

<?php echo $footer; ?>
