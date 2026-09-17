<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-envelope-open-text"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash"></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
			<a href="<?php echo $cancel; ?>" class="btn btn-warning"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<form class="foe" method="post" enctype="multipart/form-data" id="form" name="form">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
							<th><?php echo $column_subject; ?></th>
							<th class="hidden-xs"><?php echo $column_from; ?></th>
							<th><?php echo $column_supplier; ?></th>
							<th class="text-right hidden-xs"><?php echo $column_total; ?></th>
							<th class="hidden-xs"><?php echo $column_reason; ?></th>
							<th class="hidden-xs"><?php echo $column_date_added; ?></th>
							<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($pending_invoices) { ?>
						<?php foreach ($pending_invoices as $pending_invoice) { ?>
						<tr>
							<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $pending_invoice['pending_id']; ?>"></td>
							<td><?php echo $pending_invoice['subject']; ?></td>
							<td class="hidden-xs"><?php echo $pending_invoice['from_email']; ?></td>
							<td><?php echo $pending_invoice['supplier']; ?></td>
							<td class="text-right hidden-xs"><?php echo $pending_invoice['total']; ?></td>
							<td class="hidden-xs"><?php echo $pending_invoice['reason']; ?></td>
							<td class="hidden-xs"><?php echo $pending_invoice['date_added']; ?></td>
							<td class="text-right">
								<?php if ($pending_invoice['view']) { ?>
								<a href="<?php echo $pending_invoice['view']; ?>" target="_blank" class="btn btn-default" title="<?php echo $button_view; ?>"><i class="fa fa-eye"></i></a>
								<?php } ?>
								<a href="<?php echo $pending_invoice['incorporate']; ?>" class="btn btn-success" title="<?php echo $button_incorporate; ?>" onclick="return confirm(text_confirm);"><i class="fa fa-check"></i><span class="hidden-xs"> <?php echo $button_incorporate; ?></span></a>
							</td>
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
		</form>
		<div class="pagination"><?php echo str_replace('....', '', $pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>
