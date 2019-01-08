<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'credit-card'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_title; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="title" value="<?php echo $title; ?>" class="form-control" autofocus="">
					<?php if ($error_title) { ?>
						<div class="help-block error"><?php echo $error_title; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_code; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="code" value="<?php echo $code; ?>" class="form-control">
					<?php if ($error_code) { ?>
						<div class="help-block error"><?php echo $error_code; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_symbol_left; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_symbol_right; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_decimal_place; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="decimal_place" value="<?php echo $decimal_place; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_value; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="value" value="<?php echo $value; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_status; ?></label>
				<div class="control-field col-sm-4">
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