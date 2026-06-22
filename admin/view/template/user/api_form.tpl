<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'key'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_username; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="username" value="<?php echo $username; ?>" class="form-control" autofocus="">
					<?php if ($error_username) { ?>
						<div class="help-block error"><?php echo $error_username; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_key; ?></label>
				<div class="control-field col-sm-4">
					<textarea name="key" rows="5" id="input-key" class="form-control"><?php echo $key; ?></textarea>
					<br>
					<button type="button" id="button-generate" class="btn btn-primary"><i class="fa fa-refresh"></i> <?php echo $button_generate; ?></button>
					<?php if ($error_key) { ?>
						<div class="help-block error"><?php echo $error_key; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_status; ?></label>
				<div class="control-field col-sm-4">
					<select name="status" class="form-control">
						<?php if ($status) { ?>
						<option value="0"><?php echo $text_disabled; ?></option>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<?php } else { ?>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<option value="1"><?php echo $text_enabled; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$('#button-generate').on('click', function() {
	var rand = '';
	var string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	for (var i = 0; i < 256; i++) {
		rand += string[Math.floor(Math.random() * (string.length - 1))];
	}

	$('#input-key').val(rand);
});
</script>
<?php echo $footer; ?>
