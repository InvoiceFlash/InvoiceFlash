<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'cog'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<ul class="nav nav-tabs"><li class="nav-item"><a class="nav-link active"href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-local" data-toggle="tab"><?php echo $tab_local; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li></ul>
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tab-content">
				<div class="tab-pane" id="tab-general">
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_url; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_url" value="<?php echo $config_url; ?>" class="form-control">
							<?php if ($error_url) { ?>
								<div class="help-block error"><?php echo $error_url; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_ssl; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_ssl" value="<?php echo $config_ssl; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_name" value="<?php echo $config_name; ?>" class="form-control">
							<?php if ($error_name) { ?>
								<div class="help-block error"><?php echo $error_name; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_owner; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_owner" value="<?php echo $config_owner; ?>" class="form-control">
							<?php if ($error_owner) { ?>
								<div class="help-block error"><?php echo $error_owner; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_address; ?></label>
						<div class="control-field col-sm-4">
							<textarea name="config_address" class="form-control" rows="3"><?php echo $config_address; ?></textarea>
							<?php if ($error_address) { ?>
								<div class="help-block error"><?php echo $error_address; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_email; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_email" value="<?php echo $config_email; ?>" class="form-control">
							<?php if ($error_email) { ?>
								<div class="help-block error"><?php echo $error_email; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_telephone; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" class="form-control">
							<?php if ($error_telephone) { ?>
								<div class="help-block error"><?php echo $error_telephone; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_fax; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_fax" value="<?php echo $config_fax; ?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-store">
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_title; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_title" value="<?php echo $config_title; ?>" class="form-control">
							<?php if ($error_title) { ?>
								<div class="help-block error"><?php echo $error_title; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_meta_description; ?></label>
						<div class="control-field col-sm-4">
							<textarea name="config_meta_description" class="form-control" rows="3"><?php echo $config_meta_description; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_template; ?></label>
						<div class="control-field col-sm-4">
							<select name="config_template" onchange="$('#template').load('index.php?route=setting/store/template&token=<?php echo $token; ?>&template=' +encodeURIComponent(this.value));" class="form-control">
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
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_layout; ?></label>
						<div class="control-field col-sm-4">
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
				<div class="tab-pane" id="tab-local">
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_country; ?></label>
						<div class="control-field col-sm-4">
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
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_zone; ?></label>
						<div class="control-field col-sm-4">
							<select name="config_zone_id" class="form-control"></select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_language; ?></label>
						<div class="control-field col-sm-4">
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
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_currency; ?></label>
						<div class="control-field col-sm-4">
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
				</div>
				<div id="tab-option" class="tab-pane">
					<div>
						<ul class="nav nav-tabs">
							<li class="nav-item"><a class="nav-link" href="#tab-items" data-toggle="tab"><?php echo $text_items; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-tax" data-toggle="tab"><?php echo $text_tax; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-account" data-toggle="tab"><?php echo $text_account; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-checkout" data-toggle="tab"><?php echo $text_checkout; ?></a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-stock" data-toggle="tab"><?php echo $text_stock; ?></a></li>
						</ul>
						<div class="tab-content">
							<div id="tab-items" class="tab-pane">
								<div class="form-group">
									<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_catalog_limit; ?></label>
									<div class="control-field col-sm-4">
										<input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" class="form-control">
										<?php if ($error_catalog_limit) { ?>
											<div class="help-block error"><?php echo $error_catalog_limit; ?></div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div id="tab-tax" class="tab-pane">
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_tax; ?></label>
									<div class="control-field col-sm-4">
										<?php if ($config_tax) { ?>
										<label class="radio-inline"><input type="radio" name="config_tax" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_tax" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_tax" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_tax" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_tax_default; ?></label>
									<div class="control-field col-sm-4">
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
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_tax_customer; ?></label>
									<div class="control-field col-sm-4">
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
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_customer_group; ?></label>
									<div class="control-field col-sm-4">
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
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_customer_group_display; ?></label>
									<div class="control-field col-sm-4">
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
											<div class="help-block error"><?php echo $error_customer_group_display; ?></div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_customer_price; ?></label>
									<div class="control-field col-sm-4">
										<?php if ($config_customer_price) { ?>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_customer_price" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_account; ?></label>
									<div class="control-field col-sm-4">
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
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_cart_weight; ?></label>
									<div class="control-field col-sm-4">
										<?php if ($config_cart_weight) { ?>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_cart_weight" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_guest_checkout; ?></label>
									<div class="control-field col-sm-4">
										<?php if ($config_guest_checkout) { ?>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_guest_checkout" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_checkout; ?></label>
									<div class="control-field col-sm-4">
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
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_order_status; ?></label>
									<div class="control-field col-sm-4">
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
							</div>
							<div id="tab-stock" class="tab-pane">
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_stock_display; ?></label>
									<div class="control-field col-sm-4">
										<?php if ($config_stock_display) { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_display" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_stock_checkout; ?></label>
									<div class="control-field col-sm-4">
										<?php if ($config_stock_checkout) { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="1"><?php echo $text_yes; ?></label>
										<label class="radio-inline"><input type="radio" name="config_stock_checkout" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-image">
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_logo; ?></label>
						<div class="control-field col-sm-4">
							<div class="media">
								<a class="pull-left" onclick="image_upload('logo','thumb-logo');"><img class="img-thumbnail" src="<?php echo $logo; ?>" width="100" height="100" alt="" id="thumb-logo"></a>
								<input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo">
								<div class="media-body hidden-xs">
									<a class="btn btn-default" onclick="image_upload('logo','thumb-logo');"><?php echo $text_browse; ?></a>&nbsp;
									<a class="btn btn-default" onclick="$('#thumb-logo').attr('src', '<?php echo $no_image; ?>'); $('#logo').val('');"><?php echo $text_clear; ?></a>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_icon; ?></label>
						<div class="control-field col-sm-4">
							<div class="media">
								<a class="pull-left" onclick="image_upload('icon','thumb-icon');"><img class="img-thumbnail" src="<?php echo $icon; ?>" width="100" height="100" alt="" id="thumb-icon"></a>
								<input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon">
								<div class="media-body hidden-xs">
									<a class="btn btn-default" onclick="image_upload('icon','thumb-icon');"><?php echo $text_browse; ?></a>&nbsp;
									<a class="btn btn-default" onclick="$('#thumb-icon').attr('src', '<?php echo $no_image; ?>'); $('#icon').val('');"><?php echo $text_clear; ?></a>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_category; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" class="form-control"> <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" class="form-control">
							<?php if ($error_image_category) { ?>
								<div class="help-block error"><?php echo $error_image_category; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_thumb; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" class="form-control"> <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" class="form-control">
							<?php if ($error_image_thumb) { ?>
								<div class="help-block error"><?php echo $error_image_thumb; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_popup; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" class="form-control"> <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" class="form-control">
							<?php if ($error_image_popup) { ?>
								<div class="help-block error"><?php echo $error_image_popup; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_product; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" class="form-control"> <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" class="form-control">
							<?php if ($error_image_product) { ?>
								<div class="help-block error"><?php echo $error_image_product; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_additional; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" class="form-control"> <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" class="form-control">
							<?php if ($error_image_additional) { ?>
								<div class="help-block error"><?php echo $error_image_additional; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_related; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" class="form-control"> <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" class="form-control">
							<?php if ($error_image_related) { ?>
								<div class="help-block error"><?php echo $error_image_related; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_compare; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" class="form-control"> <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" class="form-control">
							<?php if ($error_image_compare) { ?>
								<div class="help-block error"><?php echo $error_image_compare; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_wishlist; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" class="form-control"> <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" class="form-control">
							<?php if ($error_image_wishlist) { ?>
								<div class="help-block error"><?php echo $error_image_wishlist; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_image_cart; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" class="form-control"> <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" class="form-control">
							<?php if ($error_image_cart) { ?>
								<div class="help-block error"><?php echo $error_image_cart; ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-server">
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_secure; ?></label>
						<div class="control-field col-sm-4">
							<?php if ($config_secure) { ?>
							<label class="radio-inline"><input type="radio" name="config_secure" value="1" checked=""><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="config_secure" value="0"><?php echo $text_no; ?></label>
							<?php } else { ?>
							<label class="radio-inline"><input type="radio" name="config_secure" value="1"><?php echo $text_yes; ?></label>
							<label class="radio-inline"><input type="radio" name="config_secure" value="0" checked=""><?php echo $text_no; ?></label>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include(DIR_TEMPLATE . 'common/template-modal.tpl'); ?>
<?php echo $footer; ?>