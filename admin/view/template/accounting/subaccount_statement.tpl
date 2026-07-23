<table class="meta" style="margin-bottom:10px;">
	<tr>
		<td style="font-weight:bold; white-space:nowrap; padding:2px 6px 2px 0;"><?php echo $code; ?></td>
		<td style="padding:2px 6px;"><?php echo $title; ?></td>
	</tr>
</table>
<div class="table-responsive">
	<table class="table table-bordered table-striped table-sm">
		<thead>
			<tr>
				<th><?php echo $column_entry; ?></th>
				<th><?php echo $column_concept; ?></th>
				<th class="text-right"><?php echo $column_debit; ?></th>
				<th class="text-right"><?php echo $column_credit; ?></th>
				<th class="text-right"><?php echo $column_balance; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ($rows) { ?>
			<?php foreach ($rows as $row) { ?>
			<tr>
				<td><?php echo trim($row['entry_id'] . ' ' . $row['line_date']); ?></td>
				<td><?php echo $row['concept']; ?></td>
				<td class="text-right"><?php echo $row['debit']; ?></td>
				<td class="text-right"><?php echo $row['credit']; ?></td>
				<td class="text-right"><?php echo $row['balance']; ?></td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<?php if ($rows) { ?>
		<tfoot>
			<tr>
				<td colspan="2"><b><?php echo $text_total; ?></b></td>
				<td class="text-right"><b><?php echo $total_debit; ?></b></td>
				<td class="text-right"><b><?php echo $total_credit; ?></b></td>
				<td class="text-right"><b><?php echo $balance; ?></b></td>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
</div>
