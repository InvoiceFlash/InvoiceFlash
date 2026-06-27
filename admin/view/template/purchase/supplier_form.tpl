<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card">
	<div class="card-header">
		<div class="float-left h2"><i class="hidden-xs fa fa-truck"></i><span> <?php echo $heading_title; ?></span></div>
		<div class="float-right">
			<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="card-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_company; ?></label>
				<div class="col-sm-6">
					<input type="text" name="company" value="<?php echo $company; ?>" class="form-control">
					<?php if ($error_company) { ?>
					<div class="help-block text-danger"><?php echo $error_company; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_firstname; ?></label>
				<div class="col-sm-6">
					<input type="text" name="firstname" value="<?php echo $firstname; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_lastname; ?></label>
				<div class="col-sm-6">
					<input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_email; ?></label>
				<div class="col-sm-6">
					<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
					<?php if ($error_email) { ?>
					<div class="help-block text-danger"><?php echo $error_email; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_telephone; ?></label>
				<div class="col-sm-6">
					<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_web; ?></label>
				<div class="col-sm-6">
					<input type="text" name="web" value="<?php echo $web; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_tax_id; ?></label>
				<div class="col-sm-6">
					<input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_address_1; ?></label>
				<div class="col-sm-6">
					<input type="text" name="address_1" value="<?php echo $address_1; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_address_2; ?></label>
				<div class="col-sm-6">
					<input type="text" name="address_2" value="<?php echo $address_2; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_city; ?></label>
				<div class="col-sm-6">
					<input type="text" name="city" value="<?php echo $city; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><span id="supplier-postcode-required"></span> <?php echo $entry_postcode; ?></label>
				<div class="col-sm-6">
					<input type="text" name="postcode" value="<?php echo $postcode; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_country; ?></label>
				<div class="col-sm-6">
					<select name="country_id" id="supplier-country" class="form-control">
						<option value=""><?php echo $text_select; ?></option>
						<?php foreach ($countries as $country) { ?>
						<?php if ($country['country_id'] == $country_id) { ?>
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
					<select name="zone_id" class="form-control"></select>
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
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_comment; ?></label>
				<div class="col-sm-6">
					<textarea name="comment" class="form-control" rows="3"><?php echo $comment; ?></textarea>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$('#supplier-country').on('change', function() {
	var $this = $(this);

	$.ajax({
		url: 'index.php?route=localisation/country/autocomplete&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$this.after($('<i>', {class: 'fas fa-spinner'}));
		},
		complete: function() {
			$('.fas.fa-spinner').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#supplier-postcode-required').show();
			} else {
				$('#supplier-postcode-required').hide();
			}

			var html = '<option value=""><?php echo $text_select; ?></option>';

			if (typeof(json['zone']) != 'undefined' && json['zone'] != '') {
				for (var i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == <?php echo (int)$zone_id; ?>) {
						html += ' selected=""';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name="zone_id"]').html(html);
		}
	});
});
$('#supplier-country').change();
</script>
<?php echo $footer; ?>
