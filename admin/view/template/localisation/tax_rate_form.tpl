<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'money'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="name" value="<?php echo $name; ?>" class="form-control" autofocus="">
					<?php if ($error_name) { ?>
						<div class="help-block error"><?php echo $error_name; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_rate; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="rate" value="<?php echo $rate; ?>" class="form-control">
					<?php if ($error_rate) { ?>
						<div class="help-block error"><?php echo $error_rate; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_type; ?></label>
				<div class="control-field col-sm-4">
					<select name="type" class="form-control">
						<?php if ($type == 'P') { ?>
						<option value="P" selected=""><?php echo $text_percent; ?></option>
						<?php } else { ?>
						<option value="P"><?php echo $text_percent; ?></option>
						<?php } ?>
						<?php if ($type == 'F') { ?>
						<option value="F" selected=""><?php echo $text_amount; ?></option>
						<?php } else { ?>
						<option value="F"><?php echo $text_amount; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_customer_group; ?></label>
				<div class="control-field col-sm-4">
					<div class="panel panel-default panel-scrollable">
						<div class="list-group list-group-hover">
						<?php foreach ($customer_groups as $customer_group) { ?>
						<label class="list-group-item">
							<?php if (in_array($customer_group['customer_group_id'], $tax_rate_customer_group)) { ?>
							<input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="">
							<?php echo $customer_group['name']; ?>
							<?php } else { ?>
							<input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>">
							<?php echo $customer_group['name']; ?>
							<?php } ?>
						</label>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_geo_zone; ?></label>
				<div class="control-field col-sm-4">
					<select name="geo_zone_id" class="form-control">
						<?php foreach ($geo_zones as $geo_zone) { ?>
						<?php	if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected=""><?php echo $geo_zone['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>