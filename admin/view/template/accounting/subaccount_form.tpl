<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'balance-scale'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_code; ?></label>
				<div class="col-sm-2">
					<input type="text" name="code" id="code" value="<?php echo $code; ?>" maxlength="12" class="form-control" autofocus="" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" onblur="subaccountFormatCode(this);">
					<?php if ($error_code) { ?>
						<div class="help-block error"><?php echo $error_code; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_title; ?></label>
				<div class="col-sm-6">
					<input type="text" name="title" value="<?php echo $title; ?>" class="form-control">
					<?php if ($error_title) { ?>
						<div class="help-block error"><?php echo $error_title; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_vat_regime; ?></label>
				<div class="col-sm-1">
					<input type="text" name="vat_regime" value="<?php echo $vat_regime; ?>" maxlength="1" class="form-control">
				</div>
			</div>
		</form>
	</div>
</div>
<script>
function subaccountFormatCode(input) {
	var val = input.value;

	if (val.indexOf('.') !== -1) {
		var parts = val.split('.');
		var left = parts.shift();
		var right = parts.join('');
		var zerosNeeded = 10 - (left.length + right.length);

		if (zerosNeeded < 0) {
			zerosNeeded = 0;
		}

		input.value = left + '0'.repeat(zerosNeeded) + right;
	}
}
</script>
<?php echo $footer; ?>
