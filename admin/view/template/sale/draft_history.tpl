<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th><?php echo $column_date_added; ?></th>
			<th class="col-sm-9"><?php echo $column_comment; ?></th>
			<th><?php echo $column_status; ?></th>
			<th><?php echo $column_notify; ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ($histories) { ?>
		<?php foreach ($histories as $history) { ?>
		<tr>
			<td><?php echo $history['date_added']; ?></td>
			<td><?php echo $history['comment'] ? $history['comment'] : '&ndash;'; ?></td>
			<td class="text-<?php echo strtolower($history['status']); ?>"><?php echo $history['status']; ?></td>
			<td><?php echo $history['notify']; ?></td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
<script>
<?php if ($error) { ?>
alertMessage('danger','<?php echo $error; ?>');
<?php } ?>
<?php if ($success) { ?>
alertMessage('success','<?php echo $success; ?>');
<?php } ?>
</script>