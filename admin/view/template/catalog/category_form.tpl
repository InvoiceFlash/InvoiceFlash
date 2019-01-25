<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'sitemap'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link active"href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li></ul>
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tab-content">
				<div class="tab-pane active" id="tab-general">
					<div id="language-tabs">
						<ul class="nav nav-tabs">
						<?php foreach ($languages as $language) { ?>
							<li class="nav-item"><a class="nav-link" href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i> <?php echo $language['name']; ?></a></li>
						<?php } ?>
						</ul>
						<div class="tab-content" id="lang-pane">
							<?php foreach ($languages as $language) { ?>
							<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
								<div class="form-group">
									<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
									<div class="col-sm-6">
										<input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" class="form-control" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] :''; ?>" class="form-control">
										<?php if (isset($error_name[$language['language_id']])) { ?>
										<div class="help-block error"><?php echo $error_name[$language['language_id']]; ?></div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_meta_description; ?></label>
									<div class="control-field col-sm-6">
										<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" class="form-control" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] :''; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_meta_keyword; ?></label>
									<div class="control-field col-sm-6">
										<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" class="form-control" rows="3"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] :''; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2"><?php echo $entry_description; ?></label>
									<div class="control-field col-sm-8">
										<textarea name="category_description[<?php echo $language['language_id']; ?>][description]" class="ckeditor form-control" rows="10" spellcheck="false"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] :''; ?></textarea>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-data">
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_parent; ?></label>
						<div class="control-field col-sm-4">
							<div class="input-group">
								<input type="text" name="path" value="<?php echo $path; ?>" autocomplete="off" class="form-control">
								<span class="input-group-btn"><a class="btn btn-default" onclick="$('input[name=\'path\']').val('<?php echo $text_none; ?>');$('input[name=\'parent_id\']').val(0);" title="<?php echo $text_none; ?>"><i class="fa fa-ban"></i></a></span>
							</div>
							<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_filter; ?></label>
						<div class="control-field col-sm-4">
							<p><input type="text" name="filter" value="" class="form-control" data-target="category" autocomplete="off"></p>
							<div class="panel panel-default panel-scrollable">
								<div id="category-filter" class="list-group">
								<?php foreach ($category_filters as $category_filter) { ?>
								<div class="list-group-item" id="category-filter<?php echo $category_filter['filter_id']; ?>"><?php echo $category_filter['name']; ?><a class="label label-danger label-trash"><i class="fa fa-trash "></i></a>
								<input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>">
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
										<?php if (in_array(0, $category_store)) { ?>
										<input type="checkbox" name="category_store[]" value="0" checked=""><?php echo $text_default; ?>
										<?php } else { ?>
										<input type="checkbox" name="category_store[]" value="0"><?php echo $text_default; ?>
										<?php } ?>
									</label>
									<?php foreach ($stores as $store) { ?>
									<label class="list-group-item">
										<?php if (in_array($store['store_id'], $category_store)) { ?>
										<input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked=""><?php echo $store['name']; ?>
										<?php } else { ?>
										<input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?>
										<?php } ?>
									</label>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_keyword; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_image; ?></label>
						<div class="control-field col-sm-4">
							<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
							<img src="<?php echo $thumb; ?>" data-placeholder="<?php echo $no_image; ?>"/></a>
							<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_top; ?></label>
						<div class="control-field col-sm-4">
							<?php if ($top) { ?>
							<input type="checkbox" name="top" value="1" checked="">
							<?php } else { ?>
							<input type="checkbox" name="top" value="1">
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_column; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="column" value="<?php echo $column; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2"><?php echo $entry_sort_order; ?></label>
						<div class="control-field col-sm-4">
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control">
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
				<div class="tab-pane" id="tab-design">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $entry_store; ?></th>
								<th><?php echo $entry_layout; ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $text_default; ?></td>
								<td><select name="category_layout[0][layout_id]" class="form-control">
									<option value="">&ndash;</option>
									<?php foreach ($layouts as $layout) { ?>
									<?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
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
									<td><select name="category_layout[<?php echo $store['store_id']; ?>][layout_id]" class="form-control">
										<option value="">&ndash;</option>
										<?php foreach ($layouts as $layout) { ?>
										<?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
<script>
$(document).ready(function(){
	$('#language-tabs li a:first').addClass('active');
	$('#lang-pane .tab-pane:first').addClass('active');
});
</script>
<?php include(DIR_TEMPLATE . 'common/template-modal.tpl'); ?>
<?php echo $footer; ?>