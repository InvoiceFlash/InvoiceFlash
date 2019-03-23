<?php echo $header ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-shopping-cart"></i> <?php echo $heading_title ?></div>
		<div class="pull-right">
			<button onclick="validateGenerate();" class="btn btn-success btn-spacer"><i class="fa fa-print"></i> <span class="hidden-xs"><?php echo $button_remittances ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form action="<?php echo $invoice ?>" class="foe" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-right"><a href="<?php echo $sort_receipt ?>"><?php echo $column_remittance_id; echo ($sort=='o.remittance_id') ? '<i class="caret caret-"' . strtolower($order) . '></i>' : '' ?></a></th>
						<th class="text-right"><a href="<?php echo $sort_receipt ?>"><?php echo $column_receipt_id; echo ($sort=='o.receipt_id') ? '<i class="caret caret-"' . strtolower($order) . '></i>' : '' ?></a></th>
						<th class="text-right"><a href="<?php echo $sort_invoice ?>"><?php echo $column_invoice_id; echo ($sort=='o.invoice_id') ? '<i class="caret caret-"' . strtolower($order) . '></i>' : '' ?></a></th>
						<th><a href="<?php echo $sort_customer ?>"><?php echo $column_customer; echo ($sort=='o.customer') ? '<i class="caret caret-"' . strtolower($order) . '></i>' : '' ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_total; ?>"><?php echo $column_total; echo ($sort == 'o.total') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'o.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs hidden-sm"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'o.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td width="40" class="text-center"><a class="btn btn-default btn-block" href="index.php?route=sale/receipt&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td class="text-right"><input type="text" name="filter_remittance_id" value="<?php echo $filter_remittance_id ?>" class="form-control text-right"></td>
						<td class="text-right"><input type="text" name="filter_receipt_id" value="<?php echo $filter_receipt_id ?>" class="form-control text-right"></td>
						<td class="text-right"><input type="text" name="filter_invoice_id" value="<?php echo $filter_invoice_id ?>" class="form-control text-right"></td>
						<td><input type="text" class="form-control" value="<?php echo $filter_customer ?>" data-target="name" data-url="sale/customer"></td>
						<td class="hidden-xs"><select name="filter_status" class="form-control">
							<option value="*">&ndash;</option>
							<?php foreach ($order_statuses as $status): ?>
								<option value="<?php echo $status['order_status_id'] ?>" <?php echo ($status['order_status_id']==$filter_status) ? 'selected' : '' ?>><?php echo $status['name'] ?></option>
							<?php endforeach ?>
						</select></td>
						<td class="text-right hidden-xs"><input type="text" class="form-control text-right" value="<?php echo $filter_total ?>"></td>
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
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i> <span class="hidden-xs"><?php echo $button_filter ?></span></button></td>
					</tr>
					<?php if ($receipts): ?>
						<?php foreach ($receipts as $receipt): ?>
							<tr>
								<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $receipt['receipt_id'] ?>" <?php echo ($receipt['selected']) ? 'selected' : '' ?>></td>
								<td class="text-right"><?php echo $receipt['remittance_id'] ?></td>
								<td class="text-right"><?php echo $receipt['receipt_id'] ?></td>
								<td class="text-right"><?php echo $receipt['invoice_id'] ?></td>
								<td><?php echo $receipt['customer'] ?></td>
								<td><?php if ($receipt['status']==1): ?>
									<?php echo 'Pagado' ?>
								<?php else: ?>
									<?php echo 'Pendiente' ?>
								<?php endif ?></td>
								<td class="ext-right"><?php echo $receipt['total'] ?></td>
								<td class="text-left"><?php echo $receipt['date_added'] ?></td>
								<td class="text-left"><?php echo $receipt['date_modified'] ?></td>
								<td class="text-right"><?php foreach ($receipt['action'] as $action): ?>
									<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
								<?php endforeach ?></td>
							</tr>
						<?php endforeach ?>
					<?php else: ?>
						<tr>
							<td colspan="9" class="text-center"><?php echo $text_no_results ?></td>
						</tr>
					<?php endif ?>
				</tbody>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
//function generate(){
//	url = 'index.php?route=sale/receipt/generate&token=<?php echo $token; ?>';
//}
function filter() {
	url = 'index.php?route=sale/receipt&token=<?php echo $token; ?>';
	
	var filter_remittance_id = $('input[name=\'filter_remittance_id\']').attr('value');
	
	if (filter_remittance_id) {
		url += '&filter_remittance_id=' + encodeURIComponent(filter_remittance_id);
	}
	
	var filter_receipt_id = $('input[name=\'filter_receipt_id\']').attr('value');
	
	if (filter_receipt_id) {
		url += '&filter_receipt_id=' + encodeURIComponent(filter_receipt_id);
	}
	
	var filter_invoice_id = $('input[name=\'filter_invoice_id\']').attr('value');
	
	if (filter_invoice_id) {
		url += '&filter_invoice_id=' + encodeURIComponent(filter_invoice_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').attr('value');
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
				
	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
function validateGenerate() {
	if (!$('input[type="checkbox"]').is(':checked')) {
			alert('Select almost a Receipt');
	} else {
		$('#form').submit();
	}
}
//--></script>
<?php echo $footer ?>