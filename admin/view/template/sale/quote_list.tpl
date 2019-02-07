<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button onclick="validate();" class="btn btn-success btn-spacer"><i class="fa fa-print"></i><span class="hidden-xs"> <?php echo $button_quote; ?></span></button>
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="foe" action="<?php echo $invoice; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-right"><a href="<?php echo $sort_quote; ?>"><?php echo $column_quote_id; echo ($sort == 'o.quote_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_company; ?>"><?php echo $column_customer; echo ($sort == 'company') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_total; ?>"><?php echo $column_total; echo ($sort == 'o.total') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'o.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs hidden-sm"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'o.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=sale/quote&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td class="text-right"><input type="text" name="filter_quote_id" value="<?php echo $filter_quote_id; ?>" class="form-control text-right"></td>
						<td><input type="text" name="filter_company" value="<?php echo $filter_company; ?>" class="form-control" data-target="company" data-url="sale/customer" class="form-control"></td>
						<td class="hidden-xs"><select name="filter_invoice_status_id" class="form-control">
							<option value="*">&ndash;</option>
							<?php if ($filter_invoice_status_id == '0') { ?>
							<option value="0" selected=""><?php echo $text_missing; ?></option>
							<?php } else { ?>
							<option value="0"><?php echo $text_missing; ?></option>
							<?php } ?>
							<?php foreach ($invoice_statuses as $invoice_status) { ?>
							<?php if ($invoice_status['invoice_status_id'] == $filter_invoice_status_id) { ?>
							<option value="<?php echo $invoice_status['invoice_status_id']; ?>" selected=""><?php echo $invoice_status['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $invoice_status['invoice_status_id']; ?>"><?php echo $invoice_status['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control text-right"></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_modified" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($quotes) { ?>
					<?php foreach ($quotes as $quote) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($quote['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $quote['quote_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $quote['quote_id']; ?>">
							<?php } ?></td>
						<td class="text-right"><?php echo $quote['quote_id']; ?></td>
						<td><?php echo $quote['company']; ?></td>
						<td class="hidden-xs text-<?php echo strtolower($quote['status']); ?>"><?php echo $quote['status']; ?></td>
						<td class="text-right hidden-xs"><?php echo $quote['total']; ?></td>
						<td class="hidden-xs"><?php echo $quote['date_added']; ?></td>
						<td class="hidden-xs hidden-sm"><?php echo $quote['date_modified']; ?></td>
						<td class="text-right"><?php foreach ($quote['action'] as $action) { ?>
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
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>