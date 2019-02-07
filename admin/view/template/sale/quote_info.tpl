<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-file-alt"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<a class="btn btn-default" href="<?php echo $printPDF; ?>" target="_blank"><i class="fa fa-file-pdf"></i><span class="hidden-xs"> Print PDF</span></a> 
			<button class="btn btn-default" data-toggle="modal" data-target="#EmailModal" data-keyboard="true"><i class="fa fa-envelope"></i><span class="hidden-xs"> Email</span></button> 
			<a class="btn btn-success" href="<?php echo $invoice; ?>" target="_blank"><i class="fa fa-print"></i><span class="hidden-xs"> <?php echo $button_quote; ?></span></a> <a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<div class="tabbable">
			<ul class="nav nav-tabs"><li class="nav-item"><a class="nav-link active"href="#tab-quote" data-toggle="tab"><?php echo $tab_quote; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-payment" data-toggle="tab"><?php echo $tab_payment; ?></a></li>
				<?php if ($shipping_method) { ?>
				<li class="nav-item"><a class="nav-link" href="#tab-shipping" data-toggle="tab"><?php echo $tab_shipping; ?></a></li>
				<?php } ?>
				<li class="nav-item"><a class="nav-link" href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
				<?php if ($maxmind_id) { ?>
				<li class="nav-item"><a class="nav-link" href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
				<?php } ?>
			</ul>
			<div class="tab-content">
				<div id="tab-quote" class="tab-pane active">
					<table class="table table-bordered table-striped table-hover info-page">
						<tr>
							<td class="col-sm-3"><?php echo $text_quote_id; ?></td>
							<td>#<?php echo $quote_id; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_store_name; ?></td>
							<td><?php echo $store_name; ?></td>
						</tr>
						<?php if ($customer) { ?>
						<tr>
							<td><?php echo $text_customer; ?></td>
							<td><a href="<?php echo $customer; ?>"><?php echo $company; ?></a></td>
						</tr>
						<?php } else { ?>
						<tr>
							<td><?php echo $text_customer; ?></td>
							<td><?php echo $company; ?></td>
						</tr>
						<?php } ?>
						<?php if ($customer_group) { ?>
						<tr>
							<td><?php echo $text_customer_group; ?></td>
							<td><?php echo $customer_group; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_email; ?></td>
							<td><?php echo $email; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_telephone; ?></td>
							<td><?php echo $telephone; ?></td>
						</tr>
						<?php if ($fax) { ?>
						<tr>
							<td><?php echo $text_fax; ?></td>
							<td><?php echo $fax; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_total; ?></td>
							<td><?php if ($credit && $customer) { if (!$credit_total) { ?>
								<button type="button" class="btn btn-default" id="credit" data-action="add"><b class="badge badge-info"><?php echo $total; ?></b>&nbsp;<span><?php echo $text_credit_add; ?></span></button>
								<?php } else { ?>
								<button type="button" class="btn btn-default" id="credit" data-action="remove"><b class="badge badge-info"><?php echo $total; ?></b>&nbsp;<span><?php echo $text_credit_remove; ?></span></button>
								<?php } } else { echo $total; } ?></td>
						</tr>
						<?php if ($invoice_status) { ?>
						<tr>
							<td><?php echo $text_invoice_status; ?></td>
							<td id="invoice-status"><?php echo $invoice_status; ?></td>
						</tr>
						<?php } ?>
						<?php if ($comment) { ?>
						<tr>
							<td><?php echo $text_comment; ?></td>
							<td><?php echo $comment; ?></td>
						</tr>
						<?php } ?>
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
				<div id="tab-payment" class="tab-pane">
					<table class="table table-bordered table-striped table-hover info-page">
						<tr>
							<td class="col-sm-3"><?php echo $text_firstname; ?></td>
							<td><?php echo $payment_firstname; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_lastname; ?></td>
							<td><?php echo $payment_lastname; ?></td>
						</tr>
						<?php if ($payment_company) { ?>
						<tr>
							<td><?php echo $text_company; ?></td>
							<td><?php echo $payment_company; ?></td>
						</tr>
						<?php } ?>
						<?php if ($payment_company_id) { ?>
						<tr>
							<td><?php echo $text_company_id; ?></td>
							<td><?php echo $payment_company_id; ?></td>
						</tr>
						<?php } ?>	
						<?php if ($payment_tax_id) { ?>
						<tr>
							<td><?php echo $text_tax_id; ?></td>
							<td><?php echo $payment_tax_id; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_address_1; ?></td>
							<td><?php echo $payment_address_1; ?></td>
						</tr>
						<?php if ($payment_address_2) { ?>
						<tr>
							<td><?php echo $text_address_2; ?></td>
							<td><?php echo $payment_address_2; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_city; ?></td>
							<td><?php echo $payment_city; ?></td>
						</tr>
						<?php if ($payment_postcode) { ?>
						<tr>
							<td><?php echo $text_postcode; ?></td>
							<td><?php echo $payment_postcode; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_zone; ?></td>
							<td><?php echo $payment_zone; ?></td>
						</tr>
						<?php if ($payment_zone_code) { ?>
						<tr>
							<td><?php echo $text_zone_code; ?></td>
							<td><?php echo $payment_zone_code; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_country; ?></td>
							<td><?php echo $payment_country; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_payment_method; ?></td>
							<td><?php echo $payment_method; ?></td>
						</tr>
					</table>
				</div>
				<?php if ($shipping_method) { ?>
				<div id="tab-shipping" class="tab-pane">
					<table class="table table-bordered table-striped table-hover info-page">
						<tr>
							<td class="col-sm-3"><?php echo $text_firstname; ?></td>
							<td><?php echo $shipping_firstname; ?></td>
						</tr>
						<tr>
							<td><?php echo $text_lastname; ?></td>
							<td><?php echo $shipping_lastname; ?></td>
						</tr>
						<?php if ($shipping_company) { ?>
						<tr>
							<td><?php echo $text_company; ?></td>
							<td><?php echo $shipping_company; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_address_1; ?></td>
							<td><?php echo $shipping_address_1; ?></td>
						</tr>
						<?php if ($shipping_address_2) { ?>
						<tr>
							<td><?php echo $text_address_2; ?></td>
							<td><?php echo $shipping_address_2; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_city; ?></td>
							<td><?php echo $shipping_city; ?></td>
						</tr>
						<?php if ($shipping_postcode) { ?>
						<tr>
							<td><?php echo $text_postcode; ?></td>
							<td><?php echo $shipping_postcode; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_zone; ?></td>
							<td><?php echo $shipping_zone; ?></td>
						</tr>
						<?php if ($shipping_zone_code) { ?>
						<tr>
							<td><?php echo $text_zone_code; ?></td>
							<td><?php echo $shipping_zone_code; ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $text_country; ?></td>
							<td><?php echo $shipping_country; ?></td>
						</tr>
						<?php if ($shipping_method) { ?>
						<tr>
							<td><?php echo $text_shipping_method; ?></td>
							<td><?php echo $shipping_method; ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<?php } ?>
				<div id="tab-product" class="tab-pane">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_product; ?></th>
								<th><?php echo $column_model; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_price; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($products as $product) { ?>
							<tr>
								<td><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
									<?php foreach ($product['option'] as $option) { ?>
									<?php if ($option['type'] != 'file'){ ?>
									<div class="help"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
									<?php } else { ?>
									<div class="help"><?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></div>
									<?php } ?>
									<?php } ?></td>
								<td><?php echo $product['model']; ?></td>
								<td class="text-right"><?php echo $product['quantity']; ?></td>
								<td class="text-right"><?php echo $product['price']; ?></td>
								<td class="text-right"><?php echo $product['total']; ?></td>
							</tr>
							<?php } ?>
							<?php foreach ($totals as $total) { ?>
								<tr id="totals">
									<td colspan="4" class="text-right"><?php echo $total['title']; ?>:</td>
									<td class="text-right"><?php echo $total['text']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div id="tab-history" class="tab-pane">
					<div id="history" data-href="index.php?route=sale/quote/history&token=<?php echo $token; ?>&quote_id=<?php echo $quote_id; ?>"></div>
					<div class="form-horizontal">
						<div class="form-group">
							<label class="control-label col-sm-2"><?php echo $entry_invoice_status; ?></label>
							<div class="control-field col-sm-4">
								<select name="quote_status_id" class="form-control">
									<?php foreach ($invoice_statuses as $invoice_statuses) { ?>
									<?php if ($invoice_statuses['invoice_status_id'] == $invoice_status_id) { ?>
									<option value="<?php echo $invoice_statuses['invoice_status_id']; ?>" selected=""><?php echo $invoice_statuses['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $invoice_statuses['invoice_status_id']; ?>"><?php echo $invoice_statuses['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="notify"><?php echo $entry_notify; ?></label>
							<div class="control-field col-sm-4">
								<label class="checkbox-inline"><input type="checkbox" name="notify" value="1" id="notify"></label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="comment"><?php echo $entry_comment; ?></label>
							<div class="control-field col-sm-4">
								<textarea name="comment" class="form-control" rows="3" id="comment"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-history" data-action="quote" data-target="sale" data-id="<?php echo $quote_id; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_history; ?></button>
							</div>
						</div>
					</div>
				</div>
				<?php if ($maxmind_id) { ?>
				<div id="tab-fraud" class="tab-pane">
					<table class="table table-bordered table-striped table-hover">
						<?php if ($country_match) { ?>
						<tr>
							<td><?php echo $text_country_match; ?></td>
							<td><?php echo $country_match; ?></td>
						</tr>
						<?php } ?>
						<?php if ($country_code) { ?>
						<tr>
							<td><?php echo $text_country_code; ?></td>
							<td><?php echo $country_code; ?></td>
						</tr>
						<?php } ?>
						<?php if ($high_risk_country) { ?>
						<tr>
							<td><?php echo $text_high_risk_country; ?></td>
							<td><?php echo $high_risk_country; ?></td>
						</tr>
						<?php } ?>
						<?php if ($distance) { ?>
						<tr>
							<td><?php echo $text_distance; ?></td>
							<td><?php echo $distance; ?></td>
						</tr>
						<?php } ?>
						<?php if ($anonymous_proxy) { ?>
						<tr>
							<td><?php echo $text_anonymous_proxy; ?></td>
							<td><?php echo $anonymous_proxy; ?></td>
						</tr>
						<?php } ?>
						<?php if ($proxy_score) { ?>
						<tr>
							<td><?php echo $text_proxy_score; ?></td>
							<td><?php echo $proxy_score; ?></td>
						</tr>
						<?php } ?>
						<?php if ($is_trans_proxy) { ?>
						<tr>
							<td><?php echo $text_is_trans_proxy; ?></td>
							<td><?php echo $is_trans_proxy; ?></td>
						</tr>
						<?php } ?>
						<?php if ($free_mail) { ?>
						<tr>
							<td><?php echo $text_free_mail; ?></td>
							<td><?php echo $free_mail; ?></td>
						</tr>
						<?php } ?>
						<?php if ($carder_email) { ?>
						<tr>
							<td><?php echo $text_carder_email; ?></td>
							<td><?php echo $carder_email; ?></td>
						</tr>
						<?php } ?>
						<?php if ($high_risk_username) { ?>
						<tr>
							<td><?php echo $text_high_risk_username; ?></td>
							<td><?php echo $high_risk_username; ?></td>
						</tr>
						<?php } ?>
						<?php if ($high_risk_password) { ?>
						<tr>
							<td><?php echo $text_high_risk_password; ?></td>
							<td><?php echo $high_risk_password; ?></td>
						</tr>
						<?php } ?>
						<?php if ($bin_match) { ?>
						<tr>
							<td><?php echo $text_bin_match; ?></td>
							<td><?php echo $bin_match; ?></td>
						</tr>
						<?php } ?>
						<?php if ($bin_country) { ?>
						<tr>
							<td><?php echo $text_bin_country; ?></td>
							<td><?php echo $bin_country; ?></td>
						</tr>
						<?php } ?>
						<?php if ($bin_name_match) { ?>
						<tr>
							<td><?php echo $text_bin_name_match; ?></td>
							<td><?php echo $bin_name_match; ?></td>
						</tr>
						<?php } ?>
						<?php if ($bin_name) { ?>
						<tr>
							<td><?php echo $text_bin_name; ?></td>
							<td><?php echo $bin_name; ?></td>
						</tr>
						<?php } ?>
						<?php if ($bin_phone_match) { ?>
						<tr>
							<td><?php echo $text_bin_phone_match; ?></td>
							<td><?php echo $bin_phone_match; ?></td>
						</tr>
						<?php } ?>
						<?php if ($bin_phone) { ?>
						<tr>
							<td><?php echo $text_bin_phone; ?></td>
							<td><?php echo $bin_phone; ?></td>
						</tr>
						<?php } ?>
						<?php if ($customer_phone_in_billing_location) { ?>
						<tr>
							<td><?php echo $text_customer_phone_in_billing_location; ?></td>
							<td><?php echo $customer_phone_in_billing_location; ?></td>
						</tr>
						<?php } ?>
						<?php if ($ship_forward) { ?>
						<tr>
							<td><?php echo $text_ship_forward; ?></td>
							<td><?php echo $ship_forward; ?></td>
						</tr>
						<?php } ?>
						<?php if ($city_postal_match) { ?>
						<tr>
							<td><?php echo $text_city_postal_match; ?></td>
							<td><?php echo $city_postal_match; ?></td>
						</tr>
						<?php } ?>
						<?php if ($ship_city_postal_match) { ?>
						<tr>
							<td><?php echo $text_ship_city_postal_match; ?></td>
							<td><?php echo $ship_city_postal_match; ?></td>
						</tr>
						<?php } ?>
						<?php if ($score) { ?>
						<tr>
							<td><?php echo $text_score; ?></td>
							<td><?php echo $score; ?></td>
						</tr>
						<?php } ?>
						<?php if ($explanation) { ?>
						<tr>
							<td><?php echo $text_explanation; ?></td>
							<td><?php echo $explanation; ?></td>
						</tr>
						<?php } ?>
						<?php if ($risk_score) { ?>
						<tr>
							<td><?php echo $text_risk_score; ?></td>
							<td><?php echo $risk_score; ?></td>
						</tr>
						<?php } ?>
						<?php if ($queries_remaining) { ?>
						<tr>
							<td><?php echo $text_queries_remaining; ?></td>
							<td><?php echo $queries_remaining; ?></td>
						</tr>
						<?php } ?>
						<?php if ($maxmind_id) { ?>
						<tr>
							<td><?php echo $text_maxmind_id; ?></td>
							<td><?php echo $maxmind_id; ?></td>
						</tr>
						<?php } ?>
						<?php if ($error) { ?>
						<tr>
							<td><?php echo $text_error; ?></td>
							<td><?php echo $error; ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php 
$id = $quote_id;
include(DIR_TEMPLATE . 'sale/email_modal.tpl');
echo $footer; ?>