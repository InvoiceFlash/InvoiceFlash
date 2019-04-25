<?php echo $header ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="clearfix">
			<div class="pull-left h2"><i class="hidden-xs fa fa-shopping-cart"></i> <?php echo $heading_title; ?></div>
			<div class="pull-right">
				<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th class="text-right"><?php echo $column_remittance ?></th>
					<th class="text-left"><?php echo $column_customer ?></th>
					<th class="text-right"><?php echo $column_amount ?></th>
					<th class="text-right"><?php echo $column_date ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($receipts): ?>
					<?php foreach ($receipts as $receipt): ?>
						<tr>
							<td class="text-right"><?php echo $receipt['receipt_id'] ?></td>
							<td class="text-left"><?php echo $receipt['customer'] ?></td>
							<td class="text-right"><?php echo $receipt['amount'] ?></td>
							<td class="text-right"><?php echo $receipt['date_vto'] ?></td>
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<tr>
						<td class="text-center" colspan="4"><?php echo $text_no_results ?></td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo $pagination ?></div>
	</div>
</div>
<?php echo $footer ?>