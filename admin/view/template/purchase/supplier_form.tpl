<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-truck"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" class="form-inline" id="form">
			<div class="card mb-3" id="tab-general" style="width:100%;">
				<div class="card-header">
					<?php echo $tab_general; ?>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-6">
							<label class="control-label col-sm-4"><b class="required">*</b> <?php echo $entry_company; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="company" value="<?php echo $company; ?>" class="form-control">
								<?php if ($error_company) { ?>
									<div class="help-block text-danger"><?php echo $error_company; ?></div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group col-sm-6">
							<label class="control-label col-sm-4"><?php echo $entry_status; ?></label>
							<div class="control-field col-sm-8">
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
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_firstname; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="firstname" value="<?php echo $firstname; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_lastname; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_email; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
								<?php if ($error_email) { ?>
									<div class="help-block text-danger"><?php echo $error_email; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_telephone; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_fax; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="fax" value="<?php echo $fax; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_web; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="web" value="<?php echo $web; ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_company_id; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="company_id" value="<?php echo $company_id; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_tax_id; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_city; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="city" value="<?php echo $city; ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							<label class="control-label col-sm-4"><?php echo $entry_address_1; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="address_1" value="<?php echo $address_1; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-6">
							<label class="control-label col-sm-4"><?php echo $entry_address_2; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="address_2" value="<?php echo $address_2; ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><span id="supplier-postcode-required"></span> <?php echo $entry_postcode; ?></label>
							<div class="control-field col-sm-8">
								<input type="text" name="postcode" value="<?php echo $postcode; ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_country; ?></label>
							<div class="control-field col-sm-8">
								<select name="country_id" data-provide="countries" data-target="supplier" data-selected="<?php echo $zone_id; ?>" class="form-control">
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
						<div class="form-group col-sm-4">
							<label class="control-label col-sm-4"><?php echo $entry_zone; ?></label>
							<div class="control-field col-sm-8">
								<select name="zone_id" class="form-control"></select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-12">
							<label class="control-label col-sm-2"><?php echo $entry_comment; ?></label>
							<div class="control-field col-sm-10">
								<textarea name="comment" class="form-control" rows="3"><?php echo $comment; ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<input type="hidden" id="text_select" value="<?php echo $text_select; ?>">
<?php echo $footer; ?>
