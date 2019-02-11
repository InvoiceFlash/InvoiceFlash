<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-user"></i><span> <?php echo $heading_title; ?></span></div>
		<div class="pull-right">
			<button class="btn btn-default" data-toggle="modal" data-target="#EmailModal" data-keyboard="true"><i class="fa fa-envelope"></i><span class="hidden-xs"> <?php echo $button_new_email ?></span></button>
			<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
        <ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<?php if ($customer_id) { ?>
			<li class="nav-item"><a class="nav-link" href="#tab-contacts" data-toggle="tab"><?php echo $tab_contacts; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-email" data-toggle="tab"><?php echo $tab_email; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-quotes" data-toggle="tab"><?php echo $tab_quotes; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-orders" data-toggle="tab"><?php echo $tab_orders; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-delivery" data-toggle="tab"><?php echo $tab_delivery; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-invoices" data-toggle="tab"><?php echo $tab_invoice; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-contracts" data-toggle="tab"><?php echo $tab_contracts; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-transaction" data-toggle="tab"><?php echo $tab_transaction; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
			<?php } ?>
			<li class="nav-item"><a class="nav-link" href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
		</ul>
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tab-content">				
				<div class="tab-pane" id="tab-general">
					<div class="row">
						<div class="col-2">
							<div class="nav flex-column" id="vtabs-address" role="tablist" aria-orientation="vertical">
								<a href="#tab-customer" class="nav-link active" role="tab" aria-selected="true" data-toggle="pill"><?php echo $tab_general; ?></a>
								<a href="#tab-notas" class="nav-link" role="tab" aria-selected="false" data-toggle="pill"><?php echo $tab_notes; ?></a>
								<a href="#tab-various" class="nav-link" role="tab" aria-selected="false" data-toggle="pill"><?php echo $tab_various; ?></a>
								<a href="#tab-info" class="nav-link" role="tab" aria-selected="false" data-toggle="pill"><?php echo $tab_info; ?></a>
								<?php $address_row=1; ?>
								<?php foreach ($addresses as $address) { ?>
									<a href="#tab-address-<?php echo $address_row; ?>" id="address-<?php echo $address_row; ?>" class="nav-link" role="tab" aria-selected="false" data-toggle="pill"><span class="btn btn-danger" onclick="$('#tab-general .nav-tabs a:first').trigger('click');$('#address-<?php echo $address_row; ?>').remove();$('#tab-address-<?php echo $address_row; ?>').remove();return false;"><i class="fa fa-trash"></i></span> <?php echo $tab_address . ' ' . $address_row; ?></a>
									<?php $address_row++; ?>
								<?php } ?>
								<a class="nav-link action" id="address-add" role="tab" aria-selected="false" data-toggle="pill"><button type="button" class="btn btn-info btn-block" onclick="addAddress();"><i class="fa fa-plus-circle"></i>&nbsp;<?php echo $button_add_address; ?></button></a>
							</div>
						</div>
						<div class="col-9">
							<div class="tab-content" id="customer-content">
								<div class="tab-pane fade show active" role="tab-panel" id="tab-customer">
									<div class="form-group">
										<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_company; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="company" value="<?php echo $company; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_email; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
											<?php $to = $email ?>
											<?php if ($error_email) { ?>
												<div class="help-block error"><?php echo $error_email; ?></div>
											<?php	} ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $entry_telephone; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
											<?php if ($error_telephone) { ?>
												<div class="help-block error"><?php echo $error_telephone; ?></div>
											<?php	} ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $entry_fax; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="fax" value="<?php echo $fax; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $entry_newsletter; ?></label>
										<div class="control-field col-sm-4">
											<select name="newsletter" class="form-control">
												<?php if ($newsletter) { ?>
												<option value="1" selected=""><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected=""><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $entry_customer_group; ?></label>
										<div class="control-field col-sm-4">
											<select name="customer_group_id" onchange="groupToggle();" class="form-control">
												<?php foreach ($customer_groups as $customer_group) { ?>
													<?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
													<option value="<?php echo $customer_group['customer_group_id']; ?>" selected=""><?php echo $customer_group['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $entry_status; ?></label>
										<div class="control-field col-sm-4">
											<select name="status" class="form-control">
												<?php if ($status) { ?>
													<option value="1" selected=""><?php echo $text_enabled; ?></option>
													<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<option value="0" selected=""><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" role="tab-panel" id="tab-notas">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="40" class="text-center"></th>
												<th><?php echo $column_comment; ?></th>
												<th><?php echo $column_user; ?></th>
												<th><?php echo $column_date; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php if ($notes): ?>
												<?php foreach ($notes as $note): ?>
													<tr>
														<td class="text-center"><input type="hidden" name="note_id" value="<?php echo $note['note_id'] ?>">
															<a href="<?php echo $note['delete'] ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
														</td>
														<td><?php echo $note['comment'] ?></td>
														<td><?php echo $note['user'] ?></td>
														<td><?php echo $note['date'] ?></td>
													</tr>
												<?php endforeach ?>
											<?php else: ?>
												<tr>
													<td colspan="4" class="text-center"><?php echo $text_no_results ?></td>
												</tr>
											<?php endif ?>
										</tbody>
										<tfoot>
											<td colspan="3"></td>
											<td class="text-right"><a href="<?php echo $add_note ?>" class="btn btn-info"><?php echo $button_add_note ?></a></td>
										</tfoot>
									</table>
								</div>
								<div class="tab-pane fade" role="tab-panel" id="tab-various">
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_date_support; ?></label>	
										<div class="input-group col-sm-4">
											<input type="text" name="date_support" value="<?php echo $date_support; ?>" class="form-control date">
											<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_bank_cc; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="bank_cc" value="<?php echo $bank_cc; ?>" class="form-control" >
										</div>										
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_bic; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="bic" value="<?php echo $bic; ?>" class="form-control" >
										</div>										
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_fiscal ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="efaccafi" value="<?php echo $efaccafi ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_receptor ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="efaccare" value="<?php echo $efaccare ?>" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_paid ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="efaccapa" value="<?php echo $efaccapa ?>" class="form-control">
										</div>
									</div>
								</div>
								<div class="tab-pane fade" role="tab-panel" id="tab-info">
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_datecreated; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="date_added" value="<?php echo $date_added; ?>" class="form-control" disabled>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $text_date_modified; ?></label>
										<div class="control-field col-sm-4">
											<input type="text" name="date_modified" value="<?php echo $date_modified; ?>" class="form-control" disabled>
										</div>
									</div>
								</div>
								<?php $address_row=1; ?>
								<?php foreach ($addresses as $address) { ?>
								<div class="tab-pane fade" role="tab-panel" id="tab-address-<?php echo $address_row; ?>">
									<input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>">
										<div class="form-group">
											<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_firstname; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" class="form-control">
												<?php if (isset($error_address_firstname[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_firstname[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_lastname; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" class="form-control">
												<?php if (isset($error_address_lastname[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_lastname[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_company; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group company-id-display">
											<label class="control-label col-sm-2"><?php echo $entry_company_id; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][company_id]" value="<?php echo $address['company_id']; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group tax-id-display">
											<label class="control-label col-sm-2"><?php echo $entry_tax_id; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][tax_id]" value="<?php echo $address['tax_id']; ?>" class="form-control">
												<?php if (isset($error_address_tax_id[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_tax_id[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_address_1; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" class="form-control">
												<?php if (isset($error_address_address_1[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_address_1[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_address_2; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_city; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" class="form-control">
												<?php if (isset($error_address_city[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_city[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> <?php echo $entry_postcode; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_country; ?></label>
											<div class="control-field col-sm-4">
												<select name="address[<?php echo $address_row; ?>][country_id]" onchange="country(this,'<?php echo $address_row; ?>','<?php echo $address['zone_id']; ?>');" class="form-control">
													<option value=""><?php echo $text_select; ?></option>
													<?php foreach ($countries as $country) { ?>
														<?php if ($country['country_id'] == $address['country_id']) { ?>
														<option value="<?php echo $country['country_id']; ?>" selected=""><?php echo $country['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select>
												<?php if (isset($error_address_country[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_country[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_zone; ?></label>
											<div class="control-field col-sm-4">
												<select name="address[<?php echo $address_row; ?>][zone_id]" class="form-control">
												</select>
												<?php if (isset($error_address_zone[$address_row])) { ?>
													<div class="help-block error"><?php echo $error_address_zone[$address_row]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2" for="default<?php echo $address_row; ?>"><?php echo $entry_default; ?></label>
											<div class="control-field col-sm-4">
												<label class="radio-inline"><?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
													<input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" id="default<?php echo $address_row; ?>" checked="">
												<?php } else { ?>
													<input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" id="default<?php echo $address_row; ?>">
												<?php } ?></label>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<?php if ($customer_id) { ?>
					<div class="tab-pane" id="tab-contacts">
		            <table class="table table-bordered table-striped table-hover">
		              <thead>
		                <tr>
		                  <th width="40" class="text-center"></th>
		                  <th><?php echo $column_name ?></th>
		                  <th><?php echo $column_contact_email ?></th>
		                  <th><?php echo $column_telephone ?></th>
		                  <th><?php echo $column_date ?></th>
		                  <th></th>
		                </tr>
		              </thead>
		              <tbody>
		                <?php if ($contacts): ?>
		                  <?php foreach ($contacts as $contact): ?>
		                    <tr>
		                      <td class="text-center"><input type="hidden" name="contact_id" value="<?php echo $contact['contact_id']; ?>"></td>
		                      <td><?php echo $contact['name']; ?></td>
		                      <td><?php echo $contact['email']; ?></td>
		                      <td><?php echo $contact['telephone']; ?></td>
		                      <td><?php echo $contact['date']; ?></td>
		                       <td class="text-right"><?php foreach ($contact['action'] as $action): ?>
		                        <a class="btn btn-default" href="<?php echo $action['href'] ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
		                      <?php endforeach ?></td>
		                    </tr>
		                  <?php endforeach ?>
						<?php else: ?>
						<tr><td colspan="6" class="text-center"><?php echo $text_no_results; ?></td></tr>
		                <?php endif ?>
		              </tbody>
		              <tfoot>
		                <tr>
		                  <td colspan="5"></td>
		                  <td class="text-right"><a href="<?php echo $add_contact ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_contact ?></a></td>
		                </tr>
		              </tfoot>
		            </table>
		          </div>
					<div class="tab-pane" id="tab-products">
						<table class="table table-bordered table-striped table-hover">
						<thead>

							<tr>

								<th><?php echo $column_product_id; ?></th>

								<th class="hidden-xs"><?php echo $column_product_name; ?></th>
								<th class="text-right"><?php echo $column_order; ?></th>
								<th class="text-left"><?php echo $column_order_date; ?></th>
								<th class="text-right"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($products) { ?>
							<?php foreach ($products as $product) { ?>
							<tr>
								<td class="left"><?php echo $product['product_id']; ?></td>
								<td class="left"><?php echo $product['name']; ?></td>
								<td class="left"><?php echo $product['order_id']; ?></td>
								<td class="left"><?php echo $product['date']; ?></td>
								<td class="right"><?php echo $product['quantity']; ?></td>
								<td class="right"><?php echo $product['total']; ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="tab-pane" id="tab-quotes">
						<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_quote; ?></th>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>	
								<th class="text-right"><?php echo $column_total; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($quotes) { ?>
						<?php foreach ($quotes as $quote) { ?>
							<tr>
								<td class="left"><?php echo $quote['quote_id']; ?></td>
								<td class="left"><?php echo $quote['date']; ?></td>
								<td class="text-right hidden-xs"><?php echo $quote['total']; ?></td>
								<td class="text-right"><?php foreach ($quote['action'] as $action) { ?>
									<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
								<?php } ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="tab-pane" id="tab-orders">
						<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_order; ?></th>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>							
								<th class="text-right"><?php echo $column_total; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($orders) { ?>
						<?php foreach ($orders as $order) { ?>
							<tr>
								<td class="left"><?php echo $order['order_id']; ?></td>
								<td class="left"><?php echo $order['date']; ?></td>
								<td class="text-right hidden-xs"><?php echo $order['total']; ?></td>
								<td class="text-right"><?php foreach ($order['action'] as $action) { ?>
								  <a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
								<?php } ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="tab-pane" id="tab-email">
						<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>
								<th class="text-right"><?php echo $column_email_subject; ?></th>
								<th class="text-right"><?php echo $column_email_text; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($emails) { ?>
						 <?php foreach ($emails as $email) { ?>
							<tr>
								<td class="left"><?php echo $email['date_added']; ?></td>
								<td class="left"><?php echo $email['subject']; ?></td>
								<td class="text-right hidden-xs"><?php echo $email['text']; ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="tab-pane" id="tab-delivery">
						<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_delivery; ?></th>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($deliveries) { ?>
						<?php foreach ($deliveries as $invoice) { ?>
							<tr>
								<td class="left"><?php echo $invoice['delivery_id']; ?></td>
								<td class="left"><?php echo $invoice['date']; ?></td>
								<td class="text-right hidden-xs"><?php echo $invoice['total']; ?></td>
								<td class="text-right"><?php foreach ($invoice['action'] as $action) { ?>
									  <a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
								<?php } ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="tab-pane" id="tab-invoices">
						<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_invoice; ?></th>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($invoices) { ?>
						<?php foreach ($invoices as $invoice) { ?>
							<tr>
								<td class="left"><?php echo $invoice['invoice_id']; ?></td>
								<td class="left"><?php echo $invoice['date']; ?></td>
								<td class="text-right hidden-xs"><?php echo $invoice['total']; ?></td>
								<td class="text-right"><?php foreach ($invoice['action'] as $action) { ?>
									  <a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
								<?php } ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					<div class="tab-pane" id="tab-contracts">
						<table class="table table-bordered table-striped table-hover">
			              <thead>
			                <tr>
			                  <th width="40" class="text-center"></th>
			                  <th><?php echo $column_article ?></th>
			                  <th><?php echo $column_quantity ?></th>
			                  <th><?php echo $column_end_support ?></th>
			                  <th></th>
			                </tr>
			              </thead>
			              <tbody>
			                <?php if ($contracts): ?>
			                  <?php foreach ($contracts as $contract): ?>
			                    <tr>
			                      <td class="text-center"><input type="hidden" name="contract_id" value="<?php echo $contract['nid']; ?>"></td>
			                      <td><?php echo $contract['product']; ?></td>
			                      <td><?php echo $contract['quantity']; ?></td>
			                      <td><?php echo $contract['end_support']; ?></td>
			                       <td class="text-right"><?php foreach ($contract['action'] as $action): ?>
			                        <a class="btn btn-default" href="<?php echo $action['href'] ?>"><?php echo $action['text']; ?></span></a>
			                      <?php endforeach ?></td>
			                    </tr>
			                  <?php endforeach ?>
							<?php else: ?>
							<tr><td colspan="5" class="text-center"><?php echo $text_no_results; ?></td></tr>
			                <?php endif ?>
			              </tbody>
			              <tfoot>
			                <tr>
			                  <td colspan="4"></td>
			                  <td class="text-right"><a href="<?php echo $add_contract ?>" class="btn btn-default"><?php echo $button_add_contract ?></a></td>
			                </tr>
			              </tfoot>
			            </table>
					</div>
					<div class="tab-pane" id="tab-transaction">
						<div id="transaction" data-href="index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>"></div>
						<div class="form-group">
							<label class="control-label col-sm-2"><?php echo $entry_description; ?></label>
							<div class="control-field col-sm-4">
								<input type="text" name="description" value="" class="form-control" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2"><?php echo $entry_amount; ?></label>
							<div class="control-field col-sm-4">
								<input type="text" name="amount" value="" class="form-control" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-transaction" data-target="customer" data-id="<?php echo $customer_id; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_transaction; ?></button>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-reward">
						<div id="reward" data-href="index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>"></div>
						<div class="form-group">
							<label class="control-label col-sm-2"><?php echo $entry_description; ?></label>
							<div class="control-field col-sm-4">
								<input type="text" name="description" value="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2"><?php echo $entry_points; ?></label>
							<div class="control-field col-sm-4">
								<input type="text" name="points" value="" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="control-field col-sm-4 col-sm-offset-2">
								<button type="button" id="button-reward" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_reward; ?></button>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="tab-pane" id="tab-ip">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_ip; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($ips) { ?>
						<?php foreach ($ips as $ip) { ?>
							<tr>
								<td><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
								<td class="text-right"><a href="<?php echo $ip['filter_ip']; ?>" target="_blank"><?php echo $ip['total']; ?></a></td>
								<td class="hidden-xs"><?php echo $ip['date_added']; ?></td>
								<td class="text-right"><?php if ($ip['ban_ip']) { ?>
									<span class="bracket"><a id="<?php echo str_replace('.', '-', $ip['ip']); ?>" onclick="removeBanIP('<?php echo $ip['ip']; ?>');"><?php echo $text_remove_ban_ip; ?></a></span>
									<?php } else { ?>
									<span class="bracket"><a id="<?php echo str_replace('.', '-', $ip['ip']); ?>" onclick="addBanIP('<?php echo $ip['ip']; ?>');"><?php echo $text_add_ban_ip; ?></a></span>
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
				</div>
			</div>
		</form>
	</div>
</div>
<!-- Modal -->
<div id="EmailModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <?php
    if (!extension_loaded('imap')) {?>
      <center><span class = "label label-danger"><?php echo $text_alert_imap ?></span>
    <?php }   ?>
        <form action="<?php echo $new_email; ?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="formEmail">
            <div class="form-group">
              <label for="to" class="control-label col-sm-2"><?php echo $text_to ?></label>
              <div class="col-sm-10">
                <input type="email" name="to" id="to" class="form-control" value="<?php echo $to; ?>">
              </div>
            </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="subject"><?php echo $text_subject ?></label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="subject" name="subject">
          </div>
          </div>
          <div class="form-group">
            <label for="message" class="control-label col-sm-2"><?php echo $text_message ?></label>
            <div class="col-sm-10"><textarea name="message" class="ckeditor form-control" spellcheck="false" id="message"></textarea></div>
          </div>
          <div class="form-group">
          	<label class="control-label col-sm-2">Attachment:</label>
          	<div class="control-field col-sm-10">
          		<div class="input-group">
          			<span class="input-group-btn">
          				<button type="button" id="button-upload" class="btn btn-info">
          					<i class="fa fa-upload"></i> Upload
          				</button>
          			</span>
          			<input type="hidden" name="filename" id="input-filename" class="form-control">
          			<input type="text" name="mask" class="form-control">
          		</div>
          	</div>
          </div>
         </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" type="submit" form="formEmail" id="send"> <?php echo $button_send ?></button>
         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>

function country(a,b,c){

	var $this=$('select[name="address['+b+'][country_id]"]');

	$.ajax({

		url:'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id='+a.value,

		dataType:'json',

		beforeSend:function(){

			$this.after($('<i>',{class:'icon-loading'}));

		},

		complete:function(){

			$('.icon-loading').remove();

		},

		success:function(json){

			if(json['postcode_required']=='1'){

				$('#postcode-required'+b).show();

			} else {

				$('#postcode-required'+b).hide();

			}

			

			html='<option value=""><?php echo $text_select; ?></option>';

			if((typeof(json['zone'])!='undefined')&&json['zone']!=''){

				for(i=0;i<json['zone'].length;i++){

					html+='<option value="'+json['zone'][i]['zone_id']+'"';

					if(json['zone'][i]['zone_id']==c){

						html+=' selected=""';

					}

					html+='>'+json['zone'][i]['name']+'</option>';

				}

			} else {

				html+='<option value="0"><?php echo $text_none; ?></option>';

			}

			

			$('select[name="address['+b+'][zone_id]"]').html(html);

		}

	});

}



$('select[name$="[country_id]"]').change();

</script>

<script>

function groupToggle(){

	var customer_group = [];

	

<?php foreach ($customer_groups as $customer_group) { ?>

	customer_group[<?php echo $customer_group['customer_group_id']; ?>]=[];

	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';

	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';

<?php } ?>
	var customer_group_id = $('select[name="customer_group_id"]').val();
	if(customer_group[customer_group_id]) {

		if(customer_group[customer_group_id]['company_id_display']==1){

			$('.company-id-display').show();

		} else {

			$('.company-id-display').hide();

		}
		if(customer_group[customer_group_id]['tax_id_display']==1){

			$('.tax-id-display').show();

		} else {

			$('.tax-id-display').hide();

		}

	}

}

groupToggle();

</script>

<script>
var address_row=<?php echo $address_row; ?>+1;

function addAddress(){	

	html ='<div class="tab-pane" id="tab-address-'+address_row+'">';

	html+='<input type="hidden" name="address['+address_row+'][address_id]" value="">';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_firstname; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][firstname]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_lastname; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][lastname]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><?php echo $entry_company; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][company]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group company-id-display">';

	html+='<label class="control-label col-sm-2"><?php echo $entry_company_id; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][company_id]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group tax-id-display">';

	html+='<label class="control-label col-sm-2"><?php echo $entry_tax_id; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][tax_id]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';		

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_address_1; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][address_1]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><?php echo $entry_address_2; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][address_2]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_city; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][city]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><span id="postcode-required'+address_row+'" class="required">*</span> <?php echo $entry_postcode; ?></label>';

	html+='<div class="control-field col-sm-4"><input type="text" name="address['+address_row+'][postcode]" value="" class="form-control" class="form-control"></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_country; ?></label>';

	html+='<div class="control-field col-sm-4"><select name="address['+address_row+'][country_id]" onchange="country(this, \''+address_row+'\', \'0\');" class="form-control">';

	html+='<option value=""><?php echo $text_select; ?></option>';

	<?php foreach ($countries as $country) { ?>

	html+='<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';

	<?php } ?>

	html+='</select></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_zone; ?></label>';

	html+='<div class="control-field col-sm-4"><select name="address['+address_row+'][zone_id]" class="form-control"><option value="false"><?php echo $this->language->get('text_none'); ?></option></select></div>';

	html+='</div>';

	html+='<div class="form-group">';

	html+='<label class="control-label col-sm-2" for="default'+address_row+'"><?php echo $entry_default; ?></label>';

	html+='<div class="control-field col-sm-4"><label class="radio-inline"><input type="radio" name="address['+address_row+'][default]" value="1" id="default'+address_row+'"></label></div>';

	html+='</div>';

	html+='</div>';

	

	$('#customer-content').append(html);

	

	$('select[name="address['+address_row+'][country_id]"]').change();

	

	$('#address-add').before('<a class="nav-link" href="#tab-address-'+address_row+'" id="address-'+address_row+'" data-toggle="pill" role="tab" aria-selected="false"><span class="btn btn-danger" onclick="$(\'#vtab-address a:first\').trigger(\'click\'); $(\'#address-'+address_row+'\').remove();$(\'#tab-address-'+address_row+'\').remove();return false;"><i class="fa fa-trash"></i></span> <?php echo $tab_address; ?> '+address_row+'</a>');

	
	$('#address-'+address_row).trigger('click');

	

	groupToggle();

	

	address_row++;

}

</script>

<script>

$('#button-reward').on('click',function(e){

	var btn=$(this);



	$.ajax({

		url:'index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',

		type:'post',

		dataType:'html',

		data:'description='+encodeURIComponent($('#tab-reward input[name="description"]').val())+'&points='+encodeURIComponent($('#tab-reward input[name="points"]').val()),

		beforeSend:function(){

			btn.button('loading');

			btn.append($('<i>',{class:'icon-loading'}));

		},

		success:function(html){

			btn.button('reset');

			$('#reward').html(html);
			$('#tab-reward input[name="points"],#tab-reward input[name="description"]').val('');

		}

	});

});
function addBanIP(ip){

	var id = ip.replace(/\./g, '-');

	

	$.ajax({

		url:'index.php?route=sale/customer/addbanip&token=<?php echo $token; ?>',

		type:'post',

		dataType:'json',

		data:'ip='+encodeURIComponent(ip),

		beforeSend:function(){

			alertMessage('warning','<?php echo $text_wait; ?>');

		},

		success:function(json){

			if(json['error']){

				alertMessage('danger',json['error']);

			}
			if(json['success']){

				alertMessage('success',json['success']);
				$('#'+id).replaceWith('<a id="'+id+'" onclick="removeBanIP(\''+ip+'\');"><?php echo $text_remove_ban_ip; ?></a>');

			}

		}

	});

}
function removeBanIP(ip) {

	var id = ip.replace(/\./g, '-');

	

	$.ajax({

		url:'index.php?route=sale/customer/removebanip&token=<?php echo $token; ?>',

		type:'post',

		dataType:'json',

		data:'ip='+encodeURIComponent(ip),

		beforeSend:function(){

			alertMessage('warning','<?php echo $text_wait; ?>');		

		},

		success:function(json){

			if(json['error']){

				alertMessage('danger',json['error']);

			}
			if(json['success']){

				alertMessage('success',json['success']);
				$('#'+id).replaceWith('<a id="'+id+'" onclick="addBanIP(\''+ip+'\');"><?php echo $text_add_ban_ip; ?></a>');

			}

		}

	});

};

</script>

<?php echo $footer; ?>