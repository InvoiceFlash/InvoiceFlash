<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'tablet'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
		</ul>
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tab-content">
				<div class="tab-pane" id="tab-general">
					<div class="row">
						<div class="col-2">
							<div id="vtab-language" class="nav flex-column" role="tablist" aria-orientation="vertical">
								<?php foreach ($languages as $language) { ?>
									<a class="nav-link" href="#language<?php echo $language['language_id']; ?>" data-toggle="pill" role="tab" aria-selected="false"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i> <?php echo $language['name']; ?></a>
								<?php } ?>
							</div>
						</div>
						<div class="col-sm-10">
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
										<div class="form-group">
											<label class="control-label col-sm-2" for="name<?php echo $language['language_id']; ?>"><b class="required">*</b> <?php echo $entry_name; ?></label>
											<div class="col-sm-6">
												<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" class="form-control" id="name<?php echo $language['language_id']; ?>" class="form-control">
												<?php if (isset($error_name[$language['language_id']])) { ?>
												<div class="help-block error"><?php echo $error_name[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2" for="meta_description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-6">
												<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" class="form-control" rows="3" spellcheck="false" id="meta_description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2" for="meta_keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-6">
												<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" class="form-control" rows="3" spellcheck="false" id="meta_keyword<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2" for="description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" class="ckeditor form-control" rows="10" spellcheck="false"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] :''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-sm-2" for="description<?php echo $language['language_id']; ?>"><?php echo $entry_tag; ?></label>
											<div class="col-sm-6">
												<?php if (isset($product_tag)) { ?>
													<input type="text" name="product_tag[<?php echo $language['language_id']; ?>]" value="<?php echo isset($product_tag[$language['language_id']]) ? $product_tag[$language['language_id']] : ''; ?>" class="form-control">
												<?php } else { ?>
													<input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" class="form-control">
												<?php } ?>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-data">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label col-sm-4"><b class="required">*</b> <?php echo $entry_model; ?></label>
								<div class="col-sm-6">
									<input type="text" name="model" value="<?php echo $model; ?>" class="form-control">
									<?php if ($error_model) { ?>
									<div class="help-block error"><?php echo $error_model; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_sku; ?></label>
								<div class="col-sm-6">
									<input type="text" name="sku" value="<?php echo $sku; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_upc; ?></label>
								<div class="col-sm-6">
									<input type="text" name="upc" value="<?php echo $upc; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_ean; ?></label>
								<div class="col-sm-6">
									<input type="text" name="ean" value="<?php echo $ean; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_jan; ?></label>
								<div class="col-sm-6">
									<input type="text" name="jan" value="<?php echo $jan; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_isbn; ?></label>
								<div class="col-sm-6">
									<input type="text" name="isbn" value="<?php echo $isbn; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_mpn; ?></label>
								<div class="col-sm-6">
									<input type="text" name="mpn" value="<?php echo $mpn; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_location; ?></label>
								<div class="col-sm-6">
									<input type="text" name="location" value="<?php echo $location; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_price; ?></label>
								<div class="col-sm-6">
									<input type="text" name="price" value="<?php echo $price; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_tax_class; ?></label>
								<div class="col-sm-6">
									<select name="tax_class_id" class="form-control">
										<option value="0"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
										<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>" selected=""><?php echo $tax_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_quantity; ?></label>
								<div class="col-sm-6">
									<input type="text" name="quantity" value="<?php echo $quantity; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_minimum; ?></label>
								<div class="col-sm-6">
									<input type="text" name="minimum" value="<?php echo $minimum; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_subtract; ?></label>
								<div class="col-sm-6">
									<select name="subtract" class="form-control">
										<?php if ($subtract) { ?>
										<option value="1" selected=""><?php echo $text_yes; ?></option>
										<option value="0"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
										<option value="0" selected=""><?php echo $text_no; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_stock_status; ?></label>
								<div class="col-sm-6">
									<select name="stock_status_id" class="form-control">
										<?php foreach ($stock_statuses as $stock_status) { ?>
										<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
										<option value="<?php echo $stock_status['stock_status_id']; ?>" selected=""><?php echo $stock_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_shipping; ?></label>
								<div class="col-sm-6">
									<div class="btn-group" data-toggle="buttons">
										<?php if ($shipping) { ?>
										<label class="btn btn-default active"><input type="radio" name="shipping" value="1" checked=""><?php echo $text_yes; ?></label>
										<label class="btn btn-default"><input type="radio" name="shipping" value="0"><?php echo $text_no; ?></label>
										<?php } else { ?>
										<label class="btn btn-default"><input type="radio" name="shipping" value="1"><?php echo $text_yes; ?></label>
										<label class="btn btn-default active"><input type="radio" name="shipping" value="0" checked=""><?php echo $text_no; ?></label>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_keyword; ?></label>
								<div class="col-sm-6">
									<input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_date_available; ?></label>
								<div class="col-sm-6">
									<div class="input-group">
										<input type="text" name="date_available" class="form-control" value="<?php echo $date_available; ?>">
										<div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_dimension; ?></label>
								<div class="col-sm-6">
									<div class="slim-row">
										<div class="slim-col-sm-4">
											<input type="text" name="length" value="<?php echo $length; ?>" class="form-control">
										</div>
										<div class="slim-col-sm-4">
											<input type="text" name="width" value="<?php echo $width; ?>" class="form-control">
										</div>
										<div class="slim-col-sm-4">
											<input type="text" name="height" value="<?php echo $height; ?>" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_length; ?></label>
								<div class="col-sm-6">
									<select name="length_class_id" class="form-control">
										<?php foreach ($length_classes as $length_class) { ?>
										<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
										<option value="<?php echo $length_class['length_class_id']; ?>" selected=""><?php echo $length_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_weight; ?></label>
								<div class="col-sm-6">
									<input type="text" name="weight" value="<?php echo $weight; ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_weight_class; ?></label>
								<div class="col-sm-6">
									<select name="weight_class_id" class="form-control">
										<?php foreach ($weight_classes as $weight_class) { ?>
										<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>" selected=""><?php echo $weight_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_status; ?></label>
								<div class="col-sm-6">
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
							<div class="form-group">
								<label class="control-label col-sm-4"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-6">
									<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-links">
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_manufacturer; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" class="form-control" autocomplete="off">
							<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_category; ?></label>
						<div class="control-field col-sm-4">
							<p><input type="text" name="category" value="" class="form-control" data-target="product" autocomplete="off"></p>
							<div class="panel panel-default panel-scrollable">
								<div id="product-category" class="list-group">
								<?php foreach ($product_categories as $product_category) { ?>
									<div class="list-group-item" id="product-category<?php echo $product_category['category_id']; ?>">
									<a class="label label-danger label-trash"><i class="fa fa-trash "></i></a><?php echo $product_category['name']; ?>
									<input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>">
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_filter; ?></label>
						<div class="control-field col-sm-4">
							<p><input type="text" name="filter" value="" class="form-control" data-target="product" autocomplete="off"></p>
							<div class="panel panel-default panel-scrollable">
								<div id="product-filter" class="list-group">
								<?php foreach ($product_filters as $product_filter) { ?>
									<div class="list-group-item" id="product-filter<?php echo $product_filter['filter_id']; ?>">
									<a class="label label-danger label-trash"><i class="fa fa-trash "></i></a><?php echo $product_filter['name']; ?>
									<input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>">
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_store; ?></label>
						<div class="control-field col-sm-4">
							<div class="panel panel-default panel-scrollable">
								<div class="list-group list-group-hover">
									<label class="list-group-item">
										<?php if (in_array(0, $product_store)) { ?>
										<input type="checkbox" name="product_store[]" value="0" checked=""><?php echo $text_default; ?>
										<?php } else { ?>
										<input type="checkbox" name="product_store[]" value="0"><?php echo $text_default; ?>
										<?php } ?>
									</label>
									<?php foreach ($stores as $store) { ?>
									<label class="list-group-item">
										<?php if (in_array($store['store_id'], $product_store)) { ?>
										<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked=""><?php echo $store['name']; ?>
										<?php } else { ?>
										<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?>
										<?php } ?>
									</label>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_download; ?></label>
						<div class="control-field col-sm-4">
							<p><input type="text" name="download" value="" class="form-control" autocomplete="off"></p>
							<div class="panel panel-default panel-scrollable">
								<div id="product-download" class="list-group">
								<?php foreach ($product_downloads as $product_download) { ?>
									<div class="list-group-item" id="product-download<?php echo $product_download['download_id']; ?>">
									<a class="label label-danger label-trash"><i class="fa fa-trash "></i></a><?php echo $product_download['name']; ?>
									<input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>">
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_related; ?></label>
						<div class="control-field col-sm-4">
							<p><input type="text" name="related" value="" class="form-control" autocomplete="off"></p>
							<div class="panel panel-default panel-scrollable">
								<div id="product-related" class="list-group">
								<?php foreach ($product_related as $product_related) { ?>
									<div class="list-group-item" id="product-related<?php echo $product_related['product_id']; ?>">
									<a class="label label-danger label-trash"><i class="fa fa-trash "></i></a><?php echo $product_related['name']; ?>
									<input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>">
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-attribute">
					<table id="attribute" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="col-xs-4"><?php echo $entry_attribute; ?></th>
								<th><?php echo $entry_text; ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $attribute_row = 0; ?>
							<?php foreach ($product_attributes as $product_attribute) { ?>
								<tr id="attribute-row<?php echo $attribute_row; ?>">
									<td><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" class="form-control">
										<input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>"></td>
									<td><?php foreach ($languages as $language) { ?>
										<div class="input-group">
											<textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" class="form-control" rows="3"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] :''; ?></textarea>
											<span class="input-group-addon"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span>
										</div>
										<?php } ?></td>
									<td><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
								</tr>
							<?php $attribute_row++; ?>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"></td>
								<td><a onclick="addAttribute();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_add_attribute; ?></span></a></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="tab-pane" id="tab-option">
					<div class="row">
						<div class="col-sm-2">
							<div class="tabs-left">
								<ul id="vtab-option" class="nav nav-tabs flex-column">
									<?php $option_row = 0; ?>
									<?php foreach ($product_options as $product_option) { ?>
									<li class="nav-item"><a class="nav-link" href="#tab-option-<?php echo $option_row; ?>" id="option-<?php echo $option_row; ?>" data-toggle="tab">
										<span class="label label-danger" onclick="$('#vtab-option a:first').trigger('click');$('#option-<?php echo $option_row; ?>').remove();$('#tab-option-<?php echo $option_row; ?>').remove();return false;"><i class="fa fa-trash"></i></span>
										<?php echo $product_option['name']; ?>
									</a></li>
									<?php $option_row++; ?>
									<?php } ?>
									<li><div class="input-group mb-3">
										<input type="text" name="option" value="" class="form-control">
										<div class="input-group-append"><span class="input-group-text">
											<i class="fa fa-plus-circle " title="<?php echo $button_add_option; ?>"></i>
										</span></div>
									</div></li>
								</ul>
							</div>
						</div>
						<div class="col-sm-10">
							<div class="tab-content" id="option-container">
								<?php $option_row = 0; ?>
								<?php $option_value_row = 0; ?>
								<?php foreach ($product_options as $product_option) { ?>
								<div id="tab-option-<?php echo $option_row; ?>" class="tab-pane">
									<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>">
									<input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>">
									<input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>">
									<input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>">
									<div class="form-group">
										<label class="control-label col-sm-2"><?php echo $entry_required; ?></label>
										<div class="control-field col-sm-4">
											<select name="product_option[<?php echo $option_row; ?>][required]" class="form-control">
												<?php if ($product_option['required']) { ?>
												<option value="1" selected=""><?php echo $text_yes; ?></option>
												<option value="0"><?php echo $text_no; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<option value="0" selected=""><?php echo $text_no; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<?php if ($product_option['type'] == 'text') { ?>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="form-control">
											</div>
										</div>
									<?php } elseif ($product_option['type'] == 'textarea') { ?>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>
											<div class="col-sm-8">
												<textarea name="product_option[<?php echo $option_row; ?>][option_value]" class="form-control" rows="3"><?php echo $product_option['option_value']; ?></textarea>
											</div>
										</div>
									<?php } elseif ($product_option['type'] == 'file') { ?>
										<div class="form-group" style="display:none;">
											<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>
											<div class="control-field col-sm-4">
												<input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="form-control">
											</div>
										</div>
									<?php } elseif ($product_option['type'] == 'date') { ?>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>
											<div class="control-field col-sm-4">
												<label class="input-group">
													<input type="text" class="form-control date" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>">
													<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
												</label>
											</div>
										</div>
									<?php } elseif ($product_option['type'] == 'datetime') { ?>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>
											<div class="control-field col-sm-4">
												<label class="input-group">
													<input type="text" class="form-control datetime" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" autocomplete="off">
													<div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
												</label>
											</div>
										</div>
									<?php } elseif ($product_option['type'] == 'time') { ?>
										<div class="form-group">
											<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>
											<div class="control-field col-sm-4">
												<label class="input-group">
													<input type="text" class="form-control time" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" autocomplete="off">
													<div class="input-group-append"><span class="input-group-text"><i class="fas fa-clock"></i></span></div>
												</label>
											</div>
										</div>
									<?php } elseif ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
									<div class="table-responsive">
									<table id="option-value<?php echo $option_row; ?>" class="table table-bordered table-striped form-inline">
										<thead>
											<tr>
												<th><?php echo $entry_option_value; ?></th>
												<th class="text-right"><?php echo $entry_quantity; ?></th>
												<th><?php echo $entry_subtract; ?></th>
												<th class="text-right"><?php echo $entry_price; ?></th>
												<th class="text-right"><?php echo $entry_option_points; ?></th>
												<th class="text-right"><?php echo $entry_weight; ?></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
											<tr id="option-value-row<?php echo $option_value_row; ?>">
												<td><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]" class="form-control">
													<?php if (isset($option_values[$product_option['option_id']])) { ?>
													<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
													<?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
													<option value="<?php echo $option_value['option_value_id']; ?>" selected=""><?php echo $option_value['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
													<?php } ?>
													<?php } ?>
													<?php } ?>
												</select>
												<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>"></td>
												<td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" class="form-control"></td>
												<td><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" class="form-control">
													<?php if ($product_option_value['subtract']) { ?>
													<option value="1" selected=""><?php echo $text_yes; ?></option>
													<option value="0"><?php echo $text_no; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_yes; ?></option>
													<option value="0" selected=""><?php echo $text_no; ?></option>
													<?php } ?>
												</select></td>
												<td class="text-right"><div class="input-group">
													<span class="input-group-btn" data-toggle="buttons">
														<?php if ($product_option_value['price_prefix'] == '+') { ?>
															<label class="btn btn-default active"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" value="+" checked=""><i class="glyphicon glyphicon-plus"></i></label>
														<?php } else { ?>
															<label class="btn btn-default"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" value="+"><i class="glyphicon glyphicon-plus"></i></label>
														<?php } ?>
														<?php if ($product_option_value['price_prefix'] == '-') { ?>
															<label class="btn btn-default active"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" value="-" checked=""><i class="glyphicon glyphicon-minus"></i></label>
														<?php } else { ?>
															<label class="btn btn-default"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" value="-"><i class="glyphicon glyphicon-minus"></i></label>
														<?php } ?>
													</span>
													<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" class="form-control">
												</div></td>
												<td class="text-right"><div class="input-group">
													<span class="input-group-btn" data-toggle="buttons">
														<?php if ($product_option_value['points_prefix'] == '+') { ?>
															<label class="btn btn-default active"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" value="+" checked=""><i class="glyphicon glyphicon-plus"></i></label>
														<?php } else { ?>
															<label class="btn btn-default"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" value="+"><i class="glyphicon glyphicon-plus"></i></label>
														<?php } ?>
														<?php if ($product_option_value['points_prefix'] == '-') { ?>
															<label class="btn btn-default active"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" value="-" checked=""><i class="glyphicon glyphicon-minus"></i></label>
														<?php } else { ?>
															<label class="btn btn-default"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" value="-"><i class="glyphicon glyphicon-minus"></i></label>
														<?php } ?>
													</span>
													<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" class="form-control">
												</div></td>
												<td class="text-right"><div class="input-group">
													<span class="input-group-btn" data-toggle="buttons">
														<?php if ($product_option_value['weight_prefix'] == '+') { ?>
															<label class="btn btn-default active"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" value="+" checked=""><i class="glyphicon glyphicon-plus"></i></label>
														<?php } else { ?>
															<label class="btn btn-default"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" value="+"><i class="glyphicon glyphicon-plus"></i></label>
														<?php } ?>
														<?php if ($product_option_value['weight_prefix'] == '-') { ?>
															<label class="btn btn-default active"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" value="-" checked=""><i class="glyphicon glyphicon-minus"></i></label>
														<?php } else { ?>
															<label class="btn btn-default"><input type="radio" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" value="-"><i class="glyphicon glyphicon-minus"></i></label>
														<?php } ?>
													</span>
													<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" class="form-control">
												</div></td>
												<td><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
											</tr>
										<?php $option_value_row++; ?>
										<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="7" class="text-right"><a onclick="addOptionValue('<?php echo $option_row; ?>');" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_add_option_value; ?></span></a></td>
											</tr>
										</tfoot>
									</table>
									</div>
									<select id="option-values<?php echo $option_row; ?>" style="display:none;">
										<?php if (isset($option_values[$product_option['option_id']])) { ?>
										<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
										<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
									<?php } ?>
								</div>
								<?php $option_row++; ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-discount">
					<div class="table-responsive">
					<table id="discount" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><?php echo $entry_customer_group; ?></th>
								<th class="text-right"><?php echo $entry_quantity; ?></th>
								<th class="text-right"><?php echo $entry_priority; ?></th>
								<th class="text-right"><?php echo $entry_price; ?></th>
								<th class="col-xs-2"><?php echo $entry_date_start; ?></th>
								<th class="col-xs-2"><?php echo $entry_date_end; ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php $discount_row = 0; ?>
						<?php foreach ($product_discounts as $product_discount) { ?>
							<tr id="discount-row<?php echo $discount_row; ?>">
								<td><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control">
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>" selected=""><?php echo $customer_group['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
								<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" class="form-control"></td>
								<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" class="form-control"></td>
								<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" class="form-control"></td>
								<td><label class="input-group"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo strtotime($product_discount['date_start']) ? $product_discount['date_start'] : ''; ?>" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div></label></td>
								<td><label class="input-group"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo strtotime($product_discount['date_end']) ? $product_discount['date_end'] : ''; ?>" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div></label></td>
								<td><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
							</tr>
						<?php $discount_row++; ?>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6"></td>
								<td><a onclick="addDiscount();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_add_discount; ?></span></a></td>
							</tr>
						</tfoot>
					</table>
					</div>
				</div>
				<div class="tab-pane" id="tab-special">
					<div class="table-responsive">
					<table id="special" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><?php echo $entry_customer_group; ?></th>
								<th class="text-right"><?php echo $entry_priority; ?></th>
								<th class="text-right"><?php echo $entry_price; ?></th>
								<th class="col-xs-3"><?php echo $entry_date_start; ?></th>
								<th class="col-xs-3"><?php echo $entry_date_end; ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php $special_row = 0; ?>
						<?php foreach ($product_specials as $product_special) { ?>
							<tr id="special-row<?php echo $special_row; ?>">
								<td><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control">
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>" selected=""><?php echo $customer_group['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
								<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" class="form-control"></td>
								<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" class="form-control"></td>
								<td><label class="input-group"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo strtotime($product_special['date_start']) ? $product_special['date_start'] : ''; ?>" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div></label></td>
								<td><label class="input-group"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo strtotime($product_special['date_end']) ? $product_special['date_end'] : ''; ?>" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div></label></td>
								<td><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
							</tr>
						<?php $special_row++; ?>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5"></td>
								<td><a onclick="addSpecial();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="visible-desktop"> <?php echo $button_add_special; ?></span></a></td>
							</tr>
						</tfoot>
					</table>
					</div>
				</div>
				<div class="tab-pane" id="tab-image">
					<table id="images" class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo $entry_image; ?></th>
								<th class="text-right"><?php echo $entry_sort_order; ?></th>
								<th></th>
							</tr>
						</thead>
						
						<tbody>
						<tr>
							<td class="text-left"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
							<img src="<?php echo $thumb; ?>" alt="" title="" /></a>
							<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" /></td>
						</tr>
						<?php $image_row = 0; ?>
						<?php $sort_order = array(); foreach ($product_images as $key => $product_image) { $sort_order[$key] = $product_image['sort_order']; } array_multisort($sort_order, SORT_ASC, $product_images); ?>
						<?php foreach ($product_images as $product_image) { ?>
							<tr id="image-row<?php echo $image_row; ?>">
								<td><div class="media">
									<a class="pull-left" onclick="image_upload('image<?php echo $image_row; ?>','thumb<?php echo $image_row; ?>');"><img class="img-thumbnail" src="<?php echo $product_image['thumb']; ?>" width="100" height="100" alt="" id="thumb<?php echo $image_row; ?>"></a>
									<input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>">
									<div class="media-body hidden-xs">
										<a class="btn btn-default" onclick="image_upload('image<?php echo $image_row; ?>','thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;
										<a class="btn btn-default" onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').val('');"><?php echo $text_clear; ?></a>
									</div>
								</div></td>
								<td class="text-right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" class="form-control"></td>
								<td><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
							</tr>
						<?php $image_row++; ?>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"></td>
								<td><a onclick="addImage();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_add_image; ?></span></a></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="tab-pane" id="tab-reward">
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_points; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="points" value="<?php echo $points; ?>" class="form-control">
						</div>
					</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><?php echo $entry_customer_group; ?></th>
								<th><?php echo $entry_reward; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($customer_groups as $customer_group) { ?>
							<tr>
								<td><?php echo $customer_group['name']; ?></td>
								<td><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] :''; ?>" class="form-control"></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tab-design">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><?php echo $entry_store; ?></th>
								<th><?php echo $entry_layout; ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $text_default; ?></td>
								<td><select name="product_layout[0][layout_id]" class="form-control">
									<option value="">&ndash;</option>
									<?php foreach ($layouts as $layout) { ?>
									<?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
									<option value="<?php echo $layout['layout_id']; ?>" selected=""><?php echo $layout['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select></td>
							</tr>
							<?php foreach ($stores as $store) { ?>
								<tr>
									<td><?php echo $store['name']; ?></td>
									<td><select name="product_layout[<?php echo $store['store_id']; ?>][layout_id]" class="form-control">
										<option value="">&ndash;</option>
										<?php foreach ($layouts as $layout) { ?>
										<?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
										<option value="<?php echo $layout['layout_id']; ?>" selected=""><?php echo $layout['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include(DIR_TEMPLATE . 'common/template-modal.tpl'); ?>
<script>
var attribute_row=<?php echo $attribute_row; ?>;

function addAttribute(){
	html ='<tr id="attribute-row'+attribute_row+'">';
	html+='<td><input type="text" name="product_attribute['+attribute_row+'][name]" value="" class="form-control"><input type="hidden" name="product_attribute['+attribute_row+'][attribute_id]" value="" class="form-control"></td>';
	html+='<td>';
	<?php foreach ($languages as $language) { ?>
	html+='<div class="input-group"><textarea name="product_attribute['+attribute_row+'][product_attribute_description][<?php echo $language['language_id']; ?>][text]" class="form-control" rows="3"></textarea><span class="input-group-addon"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>';
	<?php } ?>
	html+='</td>';
	html+='<td><a onclick="$(\'#attribute-row'+attribute_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#attribute tbody').append(html);
	
	attributeautocomplete(attribute_row);
	
	$('input[name="product_attribute['+attribute_row+'][name]"]').select();
	
	attribute_row++;
}
</script>
<script>	
var option_row=<?php echo $option_row; ?>;

var a=$('input[name="option"]'),mapped={};
a.typeahead({
	source:function(q,process){
		return $.getJSON('index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name='+encodeURIComponent(q),function(json){
			var data=[];
			$.each(json,function(i,item){
				mapped[item.name]=item;
				data.push(item.name);
			});
			process(data);
		});
	},
	updater:function(item){
		html ='<div id="tab-option-'+option_row+'" class="tab-pane">';
		html+='<input type="hidden" name="product_option['+option_row+'][product_option_id]" value="">';
		html+='<input type="hidden" name="product_option['+option_row+'][name]" value="'+item+'">';
		html+='<input type="hidden" name="product_option['+option_row+'][option_id]" value="'+mapped[item].option_id+'">';
		html+='<input type="hidden" name="product_option['+option_row+'][type]" value="'+mapped[item].type+'">';
		html+='<div class="form-group">';
		html+='<label class="control-label col-sm-2"><?php echo $entry_required; ?></label>';
		html+='<div class="control-field col-sm-4">';
		html+='<select name="product_option['+option_row+'][required]" class="form-control">';
		html+='<option value="1"><?php echo $text_yes; ?></option>';
		html+='<option value="0"><?php echo $text_no; ?></option>';
		html+='</select>';
		html+='</div>';
		html+='</div>';
			
		if(mapped[item].type=='text'){
			html+='<div class="form-group">';
			html+='<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>';
			html+='<div class="control-field col-sm-4"><input type="text" name="product_option['+option_row+'][option_value]" value="" class="form-control" class="form-control"></div>';
			html+='</div>';
		}else if(mapped[item].type=='textarea'){
			html+='<div class="form-group">';
			html+='<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>';
			html+='<div class="control-field col-sm-4"><textarea name="product_option['+option_row+'][option_value]" class="form-control" rows="3"></textarea></div>';
			html+='</div>';	
		}else if(mapped[item].type=='file'){
			html+='<div class="form-group" style="display:none;">';
			html+='<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>';
			html+='<div class="control-field col-sm-4"><input type="text" name="product_option['+option_row+'][option_value]" value="" class="form-control" class="form-control"></div>';
			html+='</div>';
		}else if(mapped[item].type=='date'){
			html+='<div class="form-group">';
			html+='<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>';
			html+='<div class="control-field col-sm-4"><label class="input-group">';
			html+='<input type="text" class="form-control date" name="product_option['+option_row+'][option_value]" value="">';
			html+='<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>';
			html+='</label></div>';
		}else if(mapped[item].type=='datetime'){
			html+='<div class="form-group">';
			html+='<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>';
			html+='<div class="control-field col-sm-4"><label class="input-group">';
			html+='<input type="text" class="form-control datetime" name="product_option['+option_row+'][option_value]" value="" autocomplete="off">';
			html+='<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>';
			html+='</label></div>';
		}else if(mapped[item].type=='time'){
			html+='<div class="form-group">';
			html+='<label class="control-label col-sm-2"><?php echo $entry_option_value; ?></label>';
			html+='<div class="control-field col-sm-4"><label class="input-group">';
			html+='<input type="text" class="form-control time" name="product_option['+option_row+'][option_value]" value="" autocomplete="off">';
			html+='<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
			html+='</label></div>';
		}else if(mapped[item].type=='select'||mapped[item].type=='radio'||mapped[item].type=='checkbox'||mapped[item].type=='image'){
			html+='<div class="table-responsive">'; 
			html+='<table id="option-value'+option_row+'" class="table table-bordered table-striped">';
			html+='<thead>'; 
			html+='<tr>';
			html+='<th><?php echo $entry_option_value; ?></th>';
			html+='<th class="text-right"><?php echo $entry_quantity; ?></th>';
			html+='<th><?php echo $entry_subtract; ?></th>';
			html+='<th class="text-right"><?php echo $entry_price; ?></th>';
			html+='<th class="text-right"><?php echo $entry_option_points; ?></th>';
			html+='<th class="text-right"><?php echo $entry_weight; ?></th>';
			html+='<th></th>';
			html+='</tr>';
			html+='</thead>';
			html+='<tbody></tbody>';
			html+='<tfoot>';
			html+='<tr>';
			html+='<td colspan="6"></td>';
			html+='<td><a onclick="addOptionValue(\''+option_row+'\');" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_option_value; ?></a></td>';
			html+='</tr>';
			html+='</tfoot>';
			html+='</table>';
			html+='</div>';
			html+='<select id="option-values'+option_row+'" style="display:none;">';

			for(i=0;i<mapped[item].option_value.length;i++){
				html+='<option value="'+mapped[item].option_value[i]['option_value_id']+'">'+mapped[item].option_value[i]['name']+'</option>';
			}

			html+='</select>';
		}
		
		html+='</div>';
		
		$('#option-container').append(html);

		$('#option-add').before('<li class="nav-item"><a class="nav-link" href="#tab-option-'+option_row+'" id="option-'+option_row+'" data-toggle="tab"><span class="label label-danger" onclick="$(\'#vtab-option a:first\').trigger(\'click\'); $(\'#option-'+option_row+'\').remove();$(\'#tab-option-'+option_row+'\').remove();return false;"><i class="fa fa-trash"></i></span>'+item+'</a></li>');

		$('#option-'+option_row).click();

		option_row++;

		return '';
	}
});
</script>
<script>
var option_value_row=<?php echo $option_value_row; ?>;

function addOptionValue(option_row){
	html ='<tr id="option-value-row'+option_value_row+'">';
	html+='<td><select name="product_option['+option_row+'][product_option_value]['+option_value_row+'][option_value_id]" class="form-control">';
	html+=$('#option-values'+option_row).html();
	html+='</select><input type="hidden" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][product_option_value_id]" value=""></td>';
	html+='<td class="text-right"><input type="text" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][quantity]" value="" class="form-control"></td>'; 
	html+='<td><select name="product_option['+option_row+'][product_option_value]['+option_value_row+'][subtract]" class="form-control"><option value="1"><?php echo $text_yes; ?></option><option value="0"><?php echo $text_no; ?></option></select></td>';
	html+='<td class="text-right"><div class="input-group"><span class="input-group-btn" data-toggle="buttons"><label class="btn btn-default active"><input type="radio" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][price_prefix]" value="+" checked=""><i class="glyphicon glyphicon-plus"></i></label><label class="btn btn-default"><input type="radio" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][price_prefix]" value="-"><i class="glyphicon glyphicon-minus"></i></label></span><input type="text" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][price]" value="" class="form-control"></div></td>';
	html+='<td class="text-right"><div class="input-group"><span class="input-group-btn" data-toggle="buttons"><label class="btn btn-default active"><input type="radio" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][points_prefix]" value="+" checked=""><i class="glyphicon glyphicon-plus"></i></label><label class="btn btn-default"><input type="radio" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][points_prefix]" value="-"><i class="glyphicon glyphicon-minus"></i></label></span><input type="text" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][points]" value="" class="form-control"></div></td>';
	html+='<td class="text-right"><div class="input-group"><span class="input-group-btn" data-toggle="buttons"><label class="btn btn-default active"><input type="radio" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][weight_prefix]" value="+" checked=""><i class="glyphicon glyphicon-plus"></i></label><label class="btn btn-default"><input type="radio" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][weight_prefix]" value="-"><i class="glyphicon glyphicon-minus"></i></label></span><input type="text" name="product_option['+option_row+'][product_option_value]['+option_value_row+'][weight]" value="" class="form-control"></div></td>';
	html+='<td><a onclick="$(\'#option-value-row'+option_value_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#option-value'+option_row+' tbody').append(html);

	option_value_row++;
}
</script>
<script>
var discount_row=<?php echo $discount_row; ?>;

function addDiscount(){
	html ='<tr id="discount-row'+discount_row+'">';
	html+='<td><select name="product_discount['+discount_row+'][customer_group_id]" class="form-control">';
	<?php foreach ($customer_groups as $customer_group) { ?>
	html+='<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
	<?php } ?>
	html+='</select></td>';
	html+='<td class="text-right"><input type="text" name="product_discount['+discount_row+'][quantity]" value="" class="form-control"></td>';
	html+='<td class="text-right"><input type="text" name="product_discount['+discount_row+'][priority]" value="" class="form-control"></td>';
	html+='<td class="text-right"><input type="text" name="product_discount['+discount_row+'][price]" value="" class="form-control"></td>';
	html+='<td><label class="input-group"><input type="text" name="product_discount['+discount_row+'][date_start]" value="" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></label></td>';
	html+='<td><label class="input-group"><input type="text" name="product_discount['+discount_row+'][date_end]" value="" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></label></td>';
	html+='<td><a onclick="$(\'#discount-row'+discount_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#discount tbody').append(html);

	discount_row++;
}
</script>
<script>
var special_row=<?php echo $special_row; ?>;

function addSpecial(){
	html ='<tr id="special-row'+special_row+'">';
	html+='<td><select name="product_special['+special_row+'][customer_group_id]" class="form-control">';
	<?php foreach ($customer_groups as $customer_group) { ?>
	html+='<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
	<?php } ?>
	html+='</select></td>';
	html+='<td class="text-right"><input type="text" name="product_special['+special_row+'][priority]" value="" class="form-control"></td>';
	html+='<td class="text-right"><input type="text" name="product_special['+special_row+'][price]" value="" class="form-control"></td>';
	html+='<td><label class="input-group"><input type="text" name="product_special['+special_row+'][date_start]" value="" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></label></td>';
	html+='<td><label class="input-group"><input type="text" name="product_special['+special_row+'][date_end]" value="" class="form-control date" autocomplete="off"><div class="input-group-append"><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></label></td>';
	html+='<td><a onclick="$(\'#special-row'+special_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#special tbody').append(html);

	special_row++;
}
</script>
<script>
var image_row=<?php echo $image_row; ?>;

function addImage(){
	html ='<tr id="image-row'+image_row+'">';
	html+='<td><div class="media"><a class="pull-left" onclick="image_upload(\'image'+image_row+'\',\'thumb'+image_row+'\');"><img class="img-thumbnail" src="<?php echo $no_image; ?>" width="100" height="100" alt="" id="thumb'+image_row+'"></a>';
	html+='<input type="hidden" name="product_image['+image_row+'][image]" value="" id="image'+image_row+'">';
	html+='<div class="media-body hidden-xs">';
	html+='<a class="btn btn-default" onclick="image_upload(\'image'+image_row+'\',\'thumb'+image_row+'\');"><?php echo $text_browse; ?></a>&nbsp;';
	html+='<a class="btn btn-default" onclick="$(\'#thumb'+image_row+'\').attr(\'src\',\'<?php echo $no_image; ?>\'); $(\'#image'+image_row+'\').attr(\'value\',\'\');"><?php echo $text_clear; ?></a>';
	html+='</div></div></td>';
	html+='<td class="text-right"><input type="text" name="product_image['+image_row+'][sort_order]" value="" class="form-control"></td>';
	html+='<td><a onclick="$(\'#image-row'+image_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
</script>
<script>
$(document).ready(function(){
	$('#vtab-language a:first').attr('aria-selected', true).addClass('active show');
});
</script>
<?php echo $footer; ?>