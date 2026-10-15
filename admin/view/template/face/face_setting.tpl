<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="float-start h2"><i class="fa fa-cog"></i> <?php echo $heading_title; ?></div>
		<div class="float-end">
			<button type="button" id="button-face-test" class="btn btn-default"><i class="fa fa-plug"></i> <span class="d-none d-lg-inline"><?php echo $button_test; ?></span></button>
			<button type="submit" form="form-face" class="btn btn-primary"><i class="fa fa-save"></i> <span class="d-none d-lg-inline"><?php echo $button_save; ?></span></button>
			<a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <span class="d-none d-lg-inline"><?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="card-body">
		<?php if ($requirements) { ?>
		<div class="alert alert-warning"><?php echo $requirements; ?></div>
		<?php } else { ?>
		<div class="alert alert-success"><?php echo $text_requirements_ok; ?></div>
		<?php } ?>
		<div id="face-alert"></div>

		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-face">
			<div class="form-group row mb-3">
				<label class="col-sm-3 control-label" for="input-active"><?php echo $entry_active; ?></label>
				<div class="col-sm-9">
					<select name="face_active" id="input-active" class="form-select">
						<option value="0"<?php echo !$face_active ? ' selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
						<option value="1"<?php echo $face_active ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
					</select>
				</div>
			</div>

			<div class="form-group row mb-3">
				<label class="col-sm-3 control-label" for="input-environment"><?php echo $entry_environment; ?></label>
				<div class="col-sm-9">
					<select name="face_environment" id="input-environment" class="form-select">
						<option value="test"<?php echo ($face_environment != 'production') ? ' selected="selected"' : ''; ?>><?php echo $text_test; ?> (se-face-webservice.redsara.es)</option>
						<option value="production"<?php echo ($face_environment == 'production') ? ' selected="selected"' : ''; ?>><?php echo $text_production; ?> (webservice.face.gob.es)</option>
					</select>
					<small class="text-muted"><?php echo $text_certificate_note; ?></small>
				</div>
			</div>

			<div class="form-group row mb-3">
				<label class="col-sm-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
				<div class="col-sm-9">
					<input type="text" name="face_email" id="input-email" value="<?php echo htmlspecialchars($face_email, ENT_QUOTES, 'UTF-8'); ?>" class="form-control<?php echo $error_email ? ' is-invalid' : ''; ?>" />
					<?php if ($error_email) { ?>
					<div class="text-danger"><?php echo $error_email; ?></div>
					<?php } ?>
					<small class="text-muted"><?php echo $text_email_note; ?></small>
				</div>
			</div>
		</form>

		<p class="text-muted"><?php echo $text_dir3_note; ?></p>
	</div>
</div>

<script type="text/javascript"><!--
$('#button-face-test').on('click', function() {
	var $button = $(this);

	$.ajax({
		url: '<?php echo $test_url; ?>',
		dataType: 'json',
		beforeSend: function() {
			$button.prop('disabled', true);
		},
		complete: function() {
			$button.prop('disabled', false);
		},
		success: function(json) {
			var $alert = $('<div class="alert"></div>').addClass(json['success'] ? 'alert-success' : 'alert-danger').html(json['success'] ? json['success'] : json['error']);

			$('#face-alert').empty().append($alert);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			$('#face-alert').html('<div class="alert alert-danger"></div>').find('.alert').text(thrownError);
		}
	});
});
//--></script>

<?php echo $footer; ?>
