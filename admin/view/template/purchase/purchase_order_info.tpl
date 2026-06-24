<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-clipboard"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="nav-item"><a class="nav-link active" href="#tab-supplier" data-bs-toggle="tab"><?php echo $tab_supplier; ?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab-product" data-bs-toggle="tab"><?php echo $tab_product; ?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab-history" data-bs-toggle="tab"><?php echo $tab_history; ?></a></li>
			</ul>
			<div class="tab-content mt-2">
				<div id="tab-supplier" class="tab-pane active">
					<table class="table table-bordered table-striped table-hover info-page">
						<tr>
							<td class="col-sm-3"><?php echo $text_purchase_order_id; ?></td>
							<td>#<?php echo $purchase_order_id; ?> (<?php echo $po_number; ?>)</td>
						</tr>
						<tr>
							<td><?php echo $text_store; ?></td>
							<td><?php echo $store_name; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_supplier; ?></td>
							<td><?php if ($supplier) { ?><a href="<?php echo $supplier; ?>"><?php echo $supplier_company; ?></a><?php } else { ?><?php echo $supplier_company; ?><?php } ?></td>
						</tr>
						<?php if ($supplier_email) { ?>
						<tr>
							<td><?php echo $text_email; ?></td>
							<td><?php echo $supplier_email; ?></td>
						</tr>
						<?php } ?>
						<?php if ($supplier_telephone) { ?>
						<tr>
							<td><?php echo $text_telephone; ?></td>
							<td><?php echo $supplier_telephone; ?></td>
						</tr>
						<?php } ?>
						<?php if ($shipping_method) { ?>
						<tr>
							<td><?php echo $text_shipping_method; ?></td>
							<td><?php echo $shipping_method; ?></td>
						</tr>
						<?php } ?>
						<?php if ($payment_method) { ?>
						<tr>
							<td><?php echo $text_payment_method; ?></td>
							<td><?php echo $payment_method; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_total; ?></td>
							<td><?php echo $total; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_purchase_order_status; ?></td>
							<td id="purchase_order-status"><?php echo $purchase_order_status; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_date_added; ?></td>
							<td><?php echo $date_added; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_date_modified; ?></td>
							<td><?php echo $date_modified; ?></td>
						</tr>
					</table>
				</div>
				<div id="tab-product" class="tab-pane">
					<div class="table-responsive-sm">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_product; ?></th>
								<th class="d-none d-sm-table-cell"><?php echo $column_model; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($products as $product) { ?>
							<tr>
								<td><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
								<td class="d-none d-sm-table-cell"><?php echo $product['model']; ?></td>
								<td class="text-right"><?php echo $product['quantity']; ?></td>
								<td class="text-right"><?php echo $product['price']; ?></td>
								<td class="text-right"><?php echo $product['total']; ?></td>
							</tr>
							<?php } ?>
							<?php foreach ($totals as $total) { ?>
								<tr id="totals">
									<td class="d-none d-sm-table-cell"></td>
									<td colspan="3" class="text-right"><?php echo $total['title']; ?>:</td>
									<td class="text-right"><?php echo $total['text']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
				<div id="tab-history" class="tab-pane">
					<div id="history" data-href="index.php?route=purchase/purchase_order/history&token=<?php echo $token; ?>&purchase_order_id=<?php echo $purchase_order_id; ?>"></div>
					<div class="form-horizontal">
						<div class="form-group row">
							<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_purchase_order_status; ?></label>
							<div class="col-sm-6">
								<select name="purchase_order_status_id" class="form-control">
									<?php foreach ($purchase_order_statuses as $status) { ?>
									<?php if ($status['purchase_order_status_id'] == $purchase_order_status_id) { ?>
									<option value="<?php echo $status['purchase_order_status_id']; ?>" selected=""><?php echo $status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $status['purchase_order_status_id']; ?>"><?php echo $status['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-10 col-md-2" for="notify"><?php echo $entry_notify; ?></label>
							<div class="col-sm-6">
								<div class="toggle-flip"><label>
									<input type="hidden" name="notify">
									<input type="checkbox">
									<span class="flip-indecator" data-toggle-on="Yes" data-toggle-off="No"></span>
								</label></div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-10 col-md-2" for="comment"><?php echo $entry_comment; ?></label>
							<div class="col-sm-6">
								<textarea name="comment" class="form-control" rows="3" id="comment"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-history" data-action="purchase_order" data-target="purchase" data-id="<?php echo $purchase_order_id; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_history; ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
