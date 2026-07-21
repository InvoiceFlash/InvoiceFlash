<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'balance-scale'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_code; ?></label>
				<div class="col-sm-2">
					<input type="text" name="code" id="code" value="<?php echo $code; ?>" maxlength="12" class="form-control" autofocus="" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); subaccountToggleClientDetails(this);" onblur="subaccountFormatCode(this);">
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
			<div id="client-details"<?php echo (substr($code, 0, 2) == '43') ? '' : ' style="display:none;"'; ?>>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_cif; ?></label>
					<div class="col-sm-3">
						<input type="text" name="cif" value="<?php echo $cif; ?>" maxlength="14" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_phone; ?></label>
					<div class="col-sm-2">
						<input type="text" name="phone" value="<?php echo $phone; ?>" maxlength="20" class="form-control">
					</div>
					<label class="col-form-label col-sm-10 col-md-1"><?php echo $entry_fax; ?></label>
					<div class="col-sm-2">
						<input type="text" name="fax" value="<?php echo $fax; ?>" maxlength="20" class="form-control">
					</div>
					<label class="col-form-label col-sm-10 col-md-1"><?php echo $entry_email; ?></label>
					<div class="col-sm-3">
						<input type="text" name="email" value="<?php echo $email; ?>" maxlength="96" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_street_type; ?></label>
					<div class="col-sm-1">
						<input type="text" name="street_type" value="<?php echo $street_type; ?>" maxlength="4" class="form-control">
					</div>
					<div class="col-sm-4">
						<input type="text" name="street" value="<?php echo $street; ?>" maxlength="100" class="form-control" placeholder="<?php echo $entry_street; ?>">
					</div>
					<label class="col-form-label col-sm-10 col-md-1"><?php echo $entry_number; ?></label>
					<div class="col-sm-1">
						<input type="text" name="number" value="<?php echo $number; ?>" maxlength="10" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_postcode; ?></label>
					<div class="col-sm-2">
						<input type="text" name="postcode" value="<?php echo $postcode; ?>" maxlength="10" class="form-control">
					</div>
					<label class="col-form-label col-sm-10 col-md-1"><?php echo $entry_city; ?></label>
					<div class="col-sm-3">
						<input type="text" name="city" value="<?php echo $city; ?>" maxlength="64" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_province; ?></label>
					<div class="col-sm-3">
						<input type="text" name="province" value="<?php echo $province; ?>" maxlength="64" class="form-control">
					</div>
					<label class="col-form-label col-sm-10 col-md-1"><?php echo $entry_country; ?></label>
					<div class="col-sm-3">
						<input type="text" name="country" value="<?php echo $country; ?>" maxlength="64" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_country_fiscal_code; ?></label>
					<div class="col-sm-3">
						<input type="text" name="country_fiscal_code" value="<?php echo $country_fiscal_code; ?>" maxlength="20" class="form-control">
					</div>
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_eu_vat_code; ?></label>
					<div class="col-sm-3">
						<input type="text" name="eu_vat_code" value="<?php echo $eu_vat_code; ?>" maxlength="20" class="form-control">
					</div>
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

	subaccountToggleClientDetails(input);
}

function subaccountToggleClientDetails(input) {
	document.getElementById('client-details').style.display = (input.value.substring(0, 2) === '43') ? '' : 'none';
}
</script>
<?php echo $footer; ?>
