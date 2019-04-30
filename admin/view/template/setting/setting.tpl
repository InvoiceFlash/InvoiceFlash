<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'cog'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-local" data-toggle="tab"><?php echo $tab_local; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-ftp" data-toggle="tab"><?php echo $tab_ftp; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-mail" data-toggle="tab"><?php echo $tab_mail; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-payroll" data-toggle="tab"><?php echo $tab_payroll; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-fraud" data-toggle="tab"><?php echo $tab_fraud; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li></ul>
		<form class="form-horizontal mt-2" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tab-content">
				<div id="tab-general" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_name" value="<?php echo $config_name; ?>" class="form-control">
							<?php if ($error_name) { ?>
								<div class="help-block text-danger"><?php echo $error_name; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_owner; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_owner" value="<?php echo $config_owner; ?>" class="form-control">
							<?php if ($error_owner) { ?>
								<div class="help-block text-danger"><?php echo $error_owner; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_address; ?></label>
						<div class="col-sm-6">
							<textarea name="config_address" class="form-control" rows="3"><?php echo $config_address; ?></textarea>
							<?php if ($error_address) { ?>
								<div class="help-block text-danger"><?php echo $error_address; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_email; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_email" value="<?php echo $config_email; ?>" class="form-control">
							<?php if ($error_email) { ?>
								<div class="help-block text-danger"><?php echo $error_email; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_telephone; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" class="form-control">
							<?php if ($error_telephone) { ?>
								<div class="help-block text-danger"><?php echo $error_telephone; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_vat_id; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_vat_id" value="<?php echo $config_vat_id; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_geocode; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_geocode" value="<?php echo $config_geocode; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div id="tab-store" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_title; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_title" value="<?php echo $config_title; ?>" class="form-control">
							<?php if ($error_title) { ?>
								<div class="help-block text-danger"><?php echo $error_title; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_meta_description; ?></label>
						<div class="col-sm-6">
							<textarea name="config_meta_description" class="form-control" rows="3"><?php echo $config_meta_description; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_template; ?></label>
						<div class="col-sm-6">
							<select name="config_template" onchange="$('#template').load('index.php?route=setting/setting/template&token=<?php echo $token; ?>&template='+encodeURIComponent(this.value));" class="form-control">
								<?php foreach ($templates as $template) { ?>
									<?php if ($template == $config_template) { ?>
									<option value="<?php echo $template; ?>" selected=""><?php echo $template; ?></option>
									<?php } else { ?>
									<option value="<?php echo $template; ?>"><?php echo $template; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<div class="help-block" id="template"></div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_layout; ?></label>
						<div class="col-sm-6">
							<select name="config_layout_id" class="form-control">
								<?php foreach ($layouts as $layout) { ?>
									<?php if ($layout['layout_id'] == $config_layout_id) { ?>
									<option value="<?php echo $layout['layout_id']; ?>" selected=""><?php echo $layout['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div id="tab-local" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_country; ?></label>
						<div class="col-sm-6">
							<select name="config_country_id" data-id="<?php echo $config_zone_id; ?>" data-none="<?php echo $text_none; ?>" class="form-control">
								<?php foreach ($countries as $country) { ?>
									<?php if ($country['country_id'] == $config_country_id) { ?>
									<option value="<?php echo $country['country_id']; ?>" selected=""><?php echo $country['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_zone; ?></label>
						<div class="col-sm-6">
							<select name="config_zone_id" class="form-control"></select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_language; ?></label>
						<div class="col-sm-6">
							<select name="config_language" class="form-control">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_language) { ?>
									<option value="<?php echo $language['code']; ?>" selected=""><?php echo $language['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_admin_language; ?></label>
						<div class="col-sm-6">
							<select name="config_admin_language" class="form-control">
								<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_admin_language) { ?>
									<option value="<?php echo $language['code']; ?>" selected=""><?php echo $language['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_currency; ?></label>
						<div class="col-sm-6">
							<select name="config_currency" class="form-control">
								<?php foreach ($currencies as $currency) { ?>
									<?php if ($currency['code'] == $config_currency) { ?>
									<option value="<?php echo $currency['code']; ?>" selected=""><?php echo $currency['title']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_currency_auto; ?></label>
						<div class="col-sm-6">
							<?php if ($config_currency_auto) { ?>
								<label class="radio-inline"><input type="radio" name="config_currency_auto" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_currency_auto" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_currency_auto" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_currency_auto" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_length_class; ?></label>
						<div class="col-sm-6">
							<select name="config_length_class_id" class="form-control">
								<?php foreach ($length_classes as $length_class) { ?>
									<?php if ($length_class['length_class_id'] == $config_length_class_id) { ?>
									<option value="<?php echo $length_class['length_class_id']; ?>" selected=""><?php echo $length_class['title']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_weight_class; ?></label>
						<div class="col-sm-6">
							<select name="config_weight_class_id" class="form-control">
								<?php foreach ($weight_classes as $weight_class) { ?>
									<?php if ($weight_class['weight_class_id'] == $config_weight_class_id) { ?>
									<option value="<?php echo $weight_class['weight_class_id']; ?>" selected=""><?php echo $weight_class['title']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div id="tab-option" class="tab-pane">
					<div>
						<ul class="nav nav-tabs">
							<li class="nav-item"><a class="nav-link" href="#tab-items" data-toggle="tab"><?php echo $text_items; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-product" data-toggle="tab"><?php echo $text_product; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-voucher" data-toggle="tab"><?php echo $text_voucher; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-tax" data-toggle="tab"><?php echo $text_tax; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-account" data-toggle="tab"><?php echo $text_account; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-checkout" data-toggle="tab"><?php echo $text_checkout; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-stock" data-toggle="tab"><?php echo $text_stock; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-affiliate" data-toggle="tab"><?php echo $text_affiliate; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-return" data-toggle="tab"><?php echo $text_return; ?></a></li>
						</ul>
						<div class="tab-content mt-2">
							<div id="tab-items" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_catalog_limit; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" class="form-control">
										<?php if ($error_catalog_limit) { ?>
											<div class="help-block text-danger"><?php echo $error_catalog_limit; ?></div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_admin_limit; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_admin_limit" value="<?php echo $config_admin_limit; ?>" class="form-control">
										<?php if ($error_admin_limit) { ?>
											<div class="help-block text-danger"><?php echo $error_admin_limit; ?></div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div id="tab-product" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_product_count; ?></label>
									<div class="col-sm-6">
										<?php if ($config_product_count) { ?>
										<label class="radio-inline"><input type="radio" name="config_product_count" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_product_count" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_product_count" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_product_count" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_review; ?></label>
									<div class="col-sm-6">
										<?php if ($config_review_status) { ?>
										<label class="radio-inline"><input type="radio" name="config_review_status" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_review_status" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_review_status" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_review_status" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_download; ?></label>
									<div class="col-sm-6">
										<?php if ($config_download) { ?>
										<label class="radio-inline"><input type="radio" name="config_download" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_download" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_download" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_download" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>
							<div id="tab-voucher" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_voucher_min; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_voucher_min" value="<?php echo $config_voucher_min; ?>" class="form-control">
										<?php if ($error_voucher_min) { ?>
											<div class="help-block text-danger"><?php echo $error_voucher_min; ?></div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_voucher_max; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_voucher_max" value="<?php echo $config_voucher_max; ?>" class="form-control">
										<?php if ($error_voucher_max) { ?>
											<div class="help-block text-danger"><?php echo $error_voucher_max; ?></div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div id="tab-tax" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_tax; ?></label>
									<div class="col-sm-6">
										<?php if ($config_tax) { ?>
										<label class="radio-inline"><input type="radio" name="config_tax" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_tax" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_tax" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_tax" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_vat; ?></label>
									<div class="col-sm-6">
										<?php if ($config_vat) { ?>
										<label class="radio-inline"><input type="radio" name="config_vat" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_vat" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_vat" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_vat" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_tax_default; ?></label>
									<div class="col-sm-6">
										<select name="config_tax_default" class="form-control">
											<option value=""><?php echo $text_none; ?></option>
											<?php	if ($config_tax_default == 'shipping') { ?>
											<option value="shipping" selected=""><?php echo $text_shipping; ?></option>
											<?php } else { ?>
											<option value="shipping"><?php echo $text_shipping; ?></option>
											<?php } ?>
											<?php	if ($config_tax_default == 'payment') { ?>
											<option value="payment" selected=""><?php echo $text_payment; ?></option>
											<?php } else { ?>
											<option value="payment"><?php echo $text_payment; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_tax_customer; ?></label>
									<div class="col-sm-6">
										<select name="config_tax_customer" class="form-control">
											<option value=""><?php echo $text_none; ?></option>
											<?php	if ($config_tax_customer == 'shipping') { ?>
											<option value="shipping" selected=""><?php echo $text_shipping; ?></option>
											<?php } else { ?>
											<option value="shipping"><?php echo $text_shipping; ?></option>
											<?php } ?>
											<?php	if ($config_tax_customer == 'payment') { ?>
											<option value="payment" selected=""><?php echo $text_payment; ?></option>
											<?php } else { ?>
											<option value="payment"><?php echo $text_payment; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div id="tab-account" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_customer_online; ?></label>
									<div class="col-sm-6">
										<?php if ($config_customer_online) { ?>
										<label class="radio-inline"><input type="radio" name="config_customer_online" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_customer_online" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_customer_online" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_customer_online" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>	
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_customer_group; ?></label>
									<div class="col-sm-6">
										<select name="config_customer_group_id" class="form-control">
											<?php foreach ($customer_groups as $customer_group) { ?>
												<?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
												<option value="<?php echo $customer_group['customer_group_id']; ?>" selected=""><?php echo $customer_group['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_customer_group_display; ?></label>
									<div class="col-sm-6">
										<div class="panel panel-default panel-scrollable">
											<div class="list-group">
											<?php foreach ($customer_groups as $customer_group) { ?>
												<label class="list-group-item">
													<?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
													<input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="">
													<?php echo $customer_group['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>">
													<?php echo $customer_group['name']; ?>
													<?php } ?>
												</label>
											<?php } ?>
											</div>
										</div>
										<?php if ($error_customer_group_display) { ?>
											<div class="help-block text-danger"><?php echo $error_customer_group_display; ?></div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_customer_price; ?></label>
									<div class="col-sm-6">
										<?php if ($config_customer_price) { ?>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_account; ?></label>
									<div class="col-sm-6">
										<select name="config_account_id" class="form-control">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
												<?php if ($information['information_id'] == $config_account_id) { ?>
												<option value="<?php echo $information['information_id']; ?>" selected=""><?php echo $information['title']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div id="tab-checkout" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_cart_weight; ?></label>
									<div class="col-sm-6">
										<?php if ($config_cart_weight) { ?>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_guest_checkout; ?></label>
									<div class="col-sm-6">
										<?php if ($config_guest_checkout) { ?>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_checkout; ?></label>
									<div class="col-sm-6">
										<select name="config_checkout_id" class="form-control">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
												<?php if ($information['information_id'] == $config_checkout_id) { ?>
												<option value="<?php echo $information['information_id']; ?>" selected=""><?php echo $information['title']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_order_edit; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_order_edit" value="<?php echo $config_order_edit; ?>" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_invoice_prefix; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" class="form-control">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_order_status; ?></label>
									<div class="col-sm-6">
										<select name="config_order_status_id" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
												<?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
												<option value="<?php echo $order_status['order_status_id']; ?>" selected=""><?php echo $order_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_complete_status; ?></label>
									<div class="col-sm-6">
										<select name="config_complete_status_id" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
												<?php if ($order_status['order_status_id'] == $config_complete_status_id) { ?>
												<option value="<?php echo $order_status['order_status_id']; ?>" selected=""><?php echo $order_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div id="tab-stock" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_stock_display; ?></label>
									<div class="col-sm-6">
										<?php if ($config_stock_display) { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_stock_warning; ?></label>
									<div class="col-sm-6">
										<?php if ($config_stock_warning) { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_warning" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_warning" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_warning" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_warning" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_stock_checkout; ?></label>
									<div class="col-sm-6">
										<?php if ($config_stock_checkout) { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_stock_status; ?></label>
									<div class="col-sm-6">
										<select name="config_stock_status_id" class="form-control">
											<?php foreach ($stock_statuses as $stock_status) { ?>
												<?php if ($stock_status['stock_status_id'] == $config_stock_status_id) { ?>
												<option value="<?php echo $stock_status['stock_status_id']; ?>" selected=""><?php echo $stock_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div id="tab-affiliate" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_affiliate; ?></label>
									<div class="col-sm-6">
										<select name="config_affiliate_id" class="form-control">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
												<?php if ($information['information_id'] == $config_affiliate_id) { ?>
												<option value="<?php echo $information['information_id']; ?>" selected=""><?php echo $information['title']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_commission; ?></label>
									<div class="col-sm-6">
										<input type="text" name="config_commission" value="<?php echo $config_commission; ?>" class="form-control">
									</div>
								</div>
							</div>
							<div id="tab-return" class="tab-pane">
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_return; ?></label>
									<div class="col-sm-6">
										<select name="config_return_id" class="form-control">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
											<?php if ($information['information_id'] == $config_return_id) { ?>
											<option value="<?php echo $information['information_id']; ?>" selected=""><?php echo $information['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_return_status; ?></label>
									<div class="col-sm-6">
										<select name="config_return_status_id" class="form-control">
											<?php foreach ($return_statuses as $return_status) { ?>
												<?php if ($return_status['return_status_id'] == $config_return_status_id) { ?>
												<option value="<?php echo $return_status['return_status_id']; ?>" selected=""><?php echo $return_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="tab-image" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_logo; ?></label>
						<div class="col-sm-6">
							<a id="thumb-logo" data-toggle="image" class="img-thumbnail">
							<img src="<?php echo $logo; ?>" data-placeholder="<?php echo $no_image; ?>"/></a>
							<input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_icon; ?></label>
						<div class="col-sm-6">
							<a id="thumb-icon" data-toggle="image" class="img-thumbnail">
							<img src="<?php echo $icon; ?>" data-placeholder="<?php echo $no_image; ?>"/></a>
							<input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_category; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" class="form-control"></div>
							</div>							
							<?php if ($error_image_category) { ?>
								<div class="help-block text-danger"><?php echo $error_image_category; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_thumb; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_thumb) { ?>
								<div class="help-block text-danger"><?php echo $error_image_thumb; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_popup; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" class="form-control"></div>
							</div>							
							<?php if ($error_image_popup) { ?>
								<div class="help-block text-danger"><?php echo $error_image_popup; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_product; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_product) { ?>
								<div class="help-block text-danger"><?php echo $error_image_product; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_additional; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_additional) { ?>
								<div class="help-block text-danger"><?php echo $error_image_additional; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_related; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_related) { ?>
								<div class="help-block text-danger"><?php echo $error_image_related; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_compare; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_compare) { ?>
								<div class="help-block text-danger"><?php echo $error_image_compare; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_wishlist; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_wishlist) { ?>
								<div class="help-block text-danger"><?php echo $error_image_wishlist; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_image_cart; ?></label>
						<div class="col-sm-6">
							<div class="slim-row">
								<div class="slim-col-sm-6"><input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" class="form-control"></div>
								<div class="slim-col-sm-6"><input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" class="form-control"></div>
							</div>
							<?php if ($error_image_cart) { ?>
								<div class="help-block text-danger"><?php echo $error_image_cart; ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div id="tab-ftp" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_ftp_host; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_ftp_host" value="<?php echo $config_ftp_host; ?>" class="form-control">
							<?php if ($error_ftp_host) { ?>
							<div class="help-block text-danger"><?php echo $error_ftp_host; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_ftp_port; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_ftp_port" value="<?php echo $config_ftp_port; ?>" class="form-control">
							<?php if ($error_ftp_port) { ?>
							<div class="help-block text-danger"><?php echo $error_ftp_port; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_ftp_username; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_ftp_username" value="<?php echo $config_ftp_username; ?>" class="form-control">
							<?php if ($error_ftp_username) { ?>
							<div class="help-block text-danger"><?php echo $error_ftp_username; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_ftp_password; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_ftp_password" value="<?php echo $config_ftp_password; ?>" class="form-control">
							<?php if ($error_ftp_password) { ?>
							<div class="help-block text-danger"><?php echo $error_ftp_password; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_ftp_root; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_ftp_root" value="<?php echo $config_ftp_root; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_ftp_status; ?></label>
						<div class="col-sm-6">
							<?php if ($config_ftp_status) { ?>
							<label class="radio-inline"><input type="radio" name="config_ftp_status" value="1" checked=""><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="config_ftp_status" value="0"><?php echo $text_no; ?></label>
							<?php } else { ?>
							<label class="radio-inline"><input type="radio" name="config_ftp_status" value="1"><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="config_ftp_status" value="0" checked=""><?php echo $text_no; ?></label>
							<?php } ?>
						</div>
					</div>
				</div>
				<div id="tab-mail" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_mail_protocol; ?></label>
						<div class="col-sm-6">
							<select name="config_mail_protocol" class="form-control" id="protocol">
								<?php if ($config_mail_protocol == 'mail') { ?>
								<option value="mail" selected=""><?php echo $text_mail; ?></option>
								<?php } else { ?>
								<option value="mail"><?php echo $text_mail; ?></option>
								<?php } ?>
								<?php if ($config_mail_protocol == 'smtp') { ?>
								<option value="smtp" selected=""><?php echo $text_smtp; ?></option>
								<?php } else { ?>
								<option value="smtp"><?php echo $text_smtp; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_mail_parameter; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_mail_parameter" value="<?php echo $config_mail_parameter; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_smtp_host; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_smtp_host" id="host" value="<?php echo $config_smtp_host; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_smtp_username; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_smtp_username" id="user" value="<?php echo $config_smtp_username; ?>" class="form-control">
						</div>
						<!-- Boton de testeo de correo -->
						<span class="input-group-btn">
							<a onclick="test();" class="btn btn-primary"><?php echo $button_test; ?></a>
							<span class="help-block text-danger" id="mcResponse"></span>
						</span>
						<!-- fin boton -->
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_smtp_password; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_smtp_password" id="pass" value="<?php echo $config_smtp_password; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_smtp_port; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_smtp_port" id="port" value="<?php echo $config_smtp_port; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_smtp_timeout; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_smtp_timeout" value="<?php echo $config_smtp_timeout; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_alert_mail; ?></label>
						<div class="col-sm-6">
							<?php if ($config_alert_mail) { ?>
								<label class="radio-inline"><input type="radio" name="config_alert_mail" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_alert_mail" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_alert_mail" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_alert_mail" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_account_mail; ?></label>
						<div class="col-sm-6">
							<?php if ($config_account_mail) { ?>
								<label class="radio-inline"><input type="radio" name="config_account_mail" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_account_mail" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_account_mail" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_account_mail" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_alert_emails; ?></label>
						<div class="col-sm-6">
							<textarea name="config_alert_emails" class="form-control" rows="3"><?php echo $config_alert_emails; ?></textarea>
						</div>
					</div>
				</div>
				<div id="tab-payroll" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_accounting_period ?></label>
						<div class="col-sm-6"><input type="text" name="accounting_period" value="<?php echo $accounting_period ?>" class="form-control"></div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_iban ?></label>
						<div class="col-sm-6"><input type="text" name="iban" value="<?php echo $iban ?>" class="form-control"></div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_bic ?></label>
						<div class="col-sm-6"><input type="text" name="bic" value="<?php echo $bic ?>" class="form-control"></div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_creditor_id ?></label>
						<div class="col-sm-6"><input type="text" name="creditor_id" value="<?php echo $creditor_id ?>" class="form-control"></div>
					</div>
				</div>
				<div id="tab-fraud" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_fraud_detection; ?></label>
						<div class="col-sm-6">
							<?php if ($config_fraud_detection) { ?>
								<label class="radio-inline"><input type="radio" name="config_fraud_detection" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_fraud_detection" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_fraud_detection" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_fraud_detection" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_fraud_key; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_fraud_key" value="<?php echo $config_fraud_key; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_fraud_score; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_fraud_score" value="<?php echo $config_fraud_score; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_fraud_status; ?></label>
						<div class="col-sm-6">
							<select name="config_fraud_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $config_fraud_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected=""><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div id="tab-server" class="tab-pane">
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_secure; ?></label>
						<div class="col-sm-6">
							<?php if ($config_secure) { ?>
								<label class="radio-inline"><input type="radio" name="config_secure" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_secure" value="0"><?php echo $text_no; ?></label>
							<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_secure" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_secure" value="0" checked=""><?php echo $text_no; ?></label>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_shared; ?></label>
						<div class="col-sm-6">
							<?php if ($config_shared) { ?>
								<label class="radio-inline"><input type="radio" name="config_shared" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_shared" value="0"><?php echo $text_no; ?></label>
							<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_shared" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_shared" value="0" checked=""><?php echo $text_no; ?></label>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_robots; ?></label>
						<div class="col-sm-6">
							<textarea name="config_robots" class="form-control" rows="3"><?php echo $config_robots; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_seo_url; ?></label>
						<div class="col-sm-6">
							<?php if ($config_seo_url) { ?>
								<label class="radio-inline"><input type="radio" name="config_seo_url" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_seo_url" value="0"><?php echo $text_no; ?></label>
							<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_seo_url" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_seo_url" value="0" checked=""><?php echo $text_no; ?></label>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_file_extension_allowed; ?></label>
						<div class="col-sm-6">
							<textarea name="config_file_extension_allowed" class="form-control" rows="3"><?php echo $config_file_extension_allowed; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_file_mime_allowed; ?></label>
						<div class="col-sm-6">
							<textarea name="config_file_mime_allowed" class="form-control" rows="3"><?php echo $config_file_mime_allowed; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_maintenance; ?></label>
						<div class="col-sm-6">
							<?php if ($config_maintenance) { ?>
								<label class="radio-inline"><input type="radio" name="config_maintenance" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_maintenance" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_maintenance" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_maintenance" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_password; ?></label>
						<div class="col-sm-6">
							<?php if ($config_password) { ?>
								<label class="radio-inline"><input type="radio" name="config_password" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_password" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_password" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_password" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_encryption; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_compression; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_compression" value="<?php echo $config_compression; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_error_display; ?></label>
						<div class="col-sm-6">
							<?php if ($config_error_display) { ?>
								<label class="radio-inline"><input type="radio" name="config_error_display" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_error_display" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_error_display" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_error_display" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_error_log; ?></label>
						<div class="col-sm-6">
							<?php if ($config_error_log) { ?>
								<label class="radio-inline"><input type="radio" name="config_error_log" value="1" checked=""><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_error_log" value="0"><?php echo $text_no; ?></label>
								<?php } else { ?>
								<label class="radio-inline"><input type="radio" name="config_error_log" value="1"><?php echo $text_yes; ?></label>
								<label class="radio-inline"><input type="radio" name="config_error_log" value="0" checked=""><?php echo $text_no; ?></label>
								<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_error_filename; ?></label>
						<div class="col-sm-6">
							<input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" class="form-control">
							<?php if ($error_error_filename) { ?>
								<div class="help-block text-danger"><?php echo $error_error_filename; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_google_analytics; ?></label>
						<div class="col-sm-6">
							<textarea name="config_google_analytics" class="form-control" rows="3"><?php echo $config_google_analytics; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include(DIR_TEMPLATE . 'common/template-modal.tpl'); ?>
<?php echo $footer; ?>

<script>
	function test() {
		$('#mcResponse').html('<img src="view/image/ajax-loader.gif" width="25px" height="25px">');

		$.post("index.php?route=setting/setting/testEmail&token=<?php echo $token; ?>", { protocol: $('#protocol').val(), host: $('#host').val(), usermame: $('#user').val(), password: $('#pass').val(), port: $('#port').val() },
			function(data){
				$('#mcResponse').html('');

				if(data.search('Error')!=-1){
					alertMessage('danger', data); 
				} else {
					alertMessage('success', 'Test Mail sent. Check your inbox.');
				}
			}, "text"
		);
	}
	
</script>