<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'globe'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="col-sm-6">
					<input type="text" name="name" value="<?php echo $name; ?>" class="form-control" autofocus="">
					<?php if ($error_name) { ?>
						<div class="help-block error"><?php echo $error_name; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_iso_code_2; ?></label>
				<div class="col-sm-6">
					<input type="text" name="iso_code_2" value="<?php echo $iso_code_2; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_iso_code_3; ?></label>
				<div class="col-sm-6">
					<input type="text" name="iso_code_3" value="<?php echo $iso_code_3; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_address_format; ?></label>
				<div class="control-field col-sm-8">
					<textarea name="address_format" class="form-control ckeditor" rows="8"><?php echo $address_format; ?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_postcode_required; ?></label>
				<div class="col-sm-4">
					<div class="btn-group" data-toggle="buttons">
						<?php if ($postcode_required) { ?>
							<label class="btn btn-default active"><input type="radio" name="postcode_required" value="1" checked>Yes</label>
							<label class="btn btn-default"><input type="radio" name="postcode_required" value="1">No</label>
						<?php } else { ?>
							<label class="btn btn-default"><input type="radio" name="postcode_required" value="1">Yes</label>
							<label class="btn btn-default active"><input type="radio" name="postcode_required" value="1" checked>No</label>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_status; ?></label>
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
		</form>
	</div>
</div>
<?php echo $footer; ?>