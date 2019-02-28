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
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_code; ?></label>
				<div class="col-sm-6">
					<input type="text" name="code" value="<?php echo $code; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_country; ?></label>
				<div class="col-sm-6">
					<select name="country_id" class="form-control">
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