<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'cogs'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
					<?php foreach ($languages as $language) { ?>
						<div class="input-group">
							<input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_description[$language['language_id']]) ? $option_description[$language['language_id']]['name'] :''; ?>" class="form-control">
							<div class="input-group-append"><span class="input-group-text">
								<i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i>
							</span></div>
						<?php if (isset($error_name[$language['language_id']])) { ?>
						<div class="help-block error"><?php echo $error_name[$language['language_id']]; ?></div>
						<?php } ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_type; ?></label>
				<div class="control-field col-sm-4">
					<select name="type" class="form-control">
						<optgroup label="<?php echo $text_choose; ?>">
							<option value="select"<?php echo ($type == 'select') ? ' selected=""' : ''; ?>><?php echo $text_select; ?></option>
							<option value="radio"<?php echo ($type == 'radio') ? ' selected=""' : ''; ?>><?php echo $text_radio; ?></option>
							<option value="checkbox"<?php echo ($type == 'checkbox') ? ' selected=""' : ''; ?>><?php echo $text_checkbox; ?></option>
							<option value="image"<?php echo ($type == 'image') ? ' selected=""' : ''; ?>><?php echo $text_image; ?></option>
						</optgroup>
						<optgroup label="<?php echo $text_input; ?>">
							<option value="text"<?php echo ($type == 'text') ? ' selected=""' : ''; ?>><?php echo $text_text; ?></option>
							<option value="textarea"<?php echo ($type == 'textarea') ? ' selected=""' : ''; ?>><?php echo $text_textarea; ?></option>
						</optgroup>
						<optgroup label="<?php echo $text_file; ?>">
							<option value="file"<?php echo ($type == 'file') ? ' selected=""' : ''; ?>><?php echo $text_file; ?></option>
						</optgroup>
						<optgroup label="<?php echo $text_date; ?>">
							<option value="date"<?php echo ($type == 'date') ? ' selected=""' : ''; ?>><?php echo $text_date; ?></option>
							<option value="time"<?php echo ($type == 'time') ? ' selected=""' : ''; ?>><?php echo $text_time; ?></option>
							<option value="datetime"<?php echo ($type == 'datetime') ? ' selected=""' : ''; ?>><?php echo $text_datetime; ?></option>
						</optgroup>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_sort_order; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control">
				</div>
			</div>
			<table id="option-value" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="col-sm-6"><b class="required">*</b> <?php echo $entry_option_value; ?></th>
						<th><?php echo $entry_image; ?></th>
						<th class="text-right"><?php echo $entry_sort_order; ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php $option_value_row = 0; ?>
				<?php foreach ($option_values as $option_value) { ?>
					<tr id="option-value-row<?php echo $option_value_row; ?>">
						<td><input type="hidden" name="option_value[<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $option_value['option_value_id']; ?>">
							<?php foreach ($languages as $language) { ?>
							<div class="input-group"><input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] :''; ?>" class="form-control">
							<div class="input-group-append"><span class="input-group-text">
								<i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i>
							</span></div>
							<?php if (isset($error_option_value[$option_value_row][$language['language_id']])) { ?>
							<span class="text-danger"><?php echo $error_option_value[$option_value_row][$language['language_id']]; ?></span>
							<?php } ?></div>
							<?php } ?></td>
						<td><div class="media">
							<a class="pull-left" onclick="image_upload('image<?php echo $option_value_row; ?>','thumb<?php echo $option_value_row; ?>');"><img class="img-thumbnail" src="<?php echo $option_value['thumb']; ?>" width="100" height="100" alt="" id="thumb<?php echo $option_value_row; ?>"></a>
							<input type="hidden" name="option_value[<?php echo $option_value_row; ?>][image]" value="<?php echo $option_value['image']; ?>" id="image<?php echo $option_value_row; ?>">
							<div class="media-body hidden-xs">
								<a class="btn btn-default" onclick="image_upload('image<?php echo $option_value_row; ?>','thumb<?php echo $option_value_row; ?>');"><?php echo $text_browse; ?></a>
								<a class="btn btn-default" onclick="$('#thumb<?php echo $option_value_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $option_value_row; ?>').val('');"><?php echo $text_clear; ?></a>
							</div>
						</div></td>
						<td class="text-right"><input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" class="form-control"></td>
						<td><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
					</tr>
				<?php $option_value_row++; ?>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3"></td>
						<td><a onclick="addOptionValue();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_add_option_value; ?></span></a></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
<?php include(DIR_TEMPLATE . 'common/template-modal.tpl'); ?>
<script>
$('select[name="type"]').change(function(){
	if (this.value=='select'||this.value=='radio'||this.value=='checkbox'||this.value=='image'){
		$('#option-value').show();
	} else {
		$('#option-value').hide();
	}
}).change();

var option_value_row=<?php echo $option_value_row; ?>;

function addOptionValue(){
	html ='<tr id="option-value-row'+option_value_row+'">';
	html+='<td><input type="hidden" name="option_value['+option_value_row+'][option_value_id]" value="">';
	<?php foreach ($languages as $language) { ?>
	html+='<div class="input-group"><input type="text" name="option_value['+option_value_row+'][option_value_description][<?php echo $language['language_id']; ?>][name]" value="" class="form-control"> <span class="input-group-addon"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>" class="form-control"></i></span></div>';
	<?php } ?>
	html+='</td>';
	html+='<td><div class="media">';
	html+='<a class="pull-left" onclick="image_upload(\'image'+option_value_row+'\',\'thumb'+option_value_row+'\');"><img class="img-thumbnail" src="<?php echo $no_image; ?>" width="100" height="100" alt="" id="thumb'+option_value_row+'"></a>';
	html+='<input type="hidden" name="option_value['+option_value_row+'][image]" value="" id="image'+option_value_row+'">';
	html+='<div class="media-body hidden-xs">';
	html+='<a class="btn btn-default" onclick="image_upload(\'image'+option_value_row+'\',\'thumb'+option_value_row+'\');"><?php echo $text_browse; ?></a>&nbsp;';
	html+='<a class="btn btn-default" onclick="$(\'#thumb'+option_value_row+'\').attr(\'src\',\'<?php echo $no_image; ?>\'); $(\'#image'+option_value_row+'\').attr(\'value\',\'\');"><?php echo $text_clear; ?></a>';
	html+='</div>';
	html+='</div></td>';
	html+='<td class="text-right"><input type="text" name="option_value['+option_value_row+'][sort_order]" value="" class="form-control"></td>';
	html+='<td><a onclick="$(\'#option-value-row'+option_value_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#option-value tbody').append(html);
	
	option_value_row++;
}
</script>
<?php echo $footer; ?>