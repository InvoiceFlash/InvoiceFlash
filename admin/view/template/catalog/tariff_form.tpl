<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'percent'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="col-sm-6">
					<input type="text" name="name" value="<?php echo $name; ?>" maxlength="64" class="form-control">
					<?php if ($error_name) { ?><div class="help-block error"><?php echo $error_name; ?></div><?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_percent; ?></label>
				<div class="col-sm-6">
					<input type="text" name="percent" value="<?php echo $percent; ?>" class="form-control" style="max-width:160px;">
					<span class="help-block"><?php echo $help_percent; ?></span>
					<?php if ($error_percent) { ?><div class="help-block error"><?php echo $error_percent; ?></div><?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_date_end; ?></label>
				<div class="col-sm-6">
					<input type="date" name="date_end" value="<?php echo $date_end; ?>" class="form-control" style="max-width:200px;">
					<span class="help-block"><?php echo $help_date_end; ?></span>
					<?php if ($error_date_end) { ?><div class="help-block error"><?php echo $error_date_end; ?></div><?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_default; ?></label>
				<div class="col-sm-6">
					<input type="checkbox" name="is_default" value="1" <?php echo $is_default ? 'checked' : ''; ?>>
					<span class="help-block"><?php echo $help_default; ?></span>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
