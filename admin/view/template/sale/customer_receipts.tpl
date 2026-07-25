<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th><?php echo $column_invoice_id; ?></th>
			<th><?php echo $column_date_due; ?></th>
			<th class="text-right"><?php echo $column_total; ?></th>
			<th class="text-center"><?php echo $column_status; ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ($receipts) { ?>
		<?php foreach ($receipts as $receipt) { ?>
		<tr>
			<td><?php echo $receipt['invoice_id']; ?></td>
			<td><?php echo $receipt['date_due']; ?></td>
			<td class="text-right"><?php echo $receipt['total']; ?></td>
			<td class="text-center">
				<?php if ($receipt['paid']) { ?>
				<span class="badge badge-success"><i class="fa fa-check"></i> <?php echo $text_paid; ?></span>
				<?php } else { ?>
				<span class="badge badge-danger"><i class="fa fa-clock"></i> <?php echo $text_pending; ?></span>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<div class="pagination"><?php echo str_replace('....', '', $pagination); ?></div>
