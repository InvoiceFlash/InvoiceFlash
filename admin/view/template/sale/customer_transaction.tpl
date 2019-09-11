<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th><?php echo $column_date_added; ?></th>
			<th class="col-sm-10"><?php echo $column_description; ?></th>
			<th class="text-right"><?php echo $column_amount; ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ($transactions) { ?>
		<?php foreach ($transactions as $transaction) { ?>
		<tr>
			<td><?php echo $transaction['date_added']; ?></td>
			<td><?php echo $transaction['description']; ?></td>
			<td class="text-right"><?php echo $transaction['amount']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>&nbsp;</td>
			<td class="text-right"><?php echo $text_balance; ?></td>
			<td class="text-right"><?php echo $balance; ?></td>
		</tr>
		<?php } else { ?>
		<tr>
			<td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
<script>
<?php if ($error_warning) { ?>
alertMessage('danger','<?php echo $error_warning; ?>');
<?php } ?>
<?php if ($success) { ?>
alertMessage('success','<?php echo $success; ?>');
<?php } ?>
</script>