<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'balance-scale'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_code; ?></label>
				<div class="col-sm-6">
					<input type="text" name="code" value="<?php echo $code; ?>" class="form-control" autofocus="">
					<?php if ($error_code) { ?>
						<div class="help-block error"><?php echo $error_code; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="col-sm-6">
					<input type="text" name="name" value="<?php echo $name; ?>" class="form-control">
					<?php if ($error_name) { ?>
						<div class="help-block error"><?php echo $error_name; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_level; ?></label>
				<div class="col-sm-6">
					<input type="text" name="level" value="<?php echo $level; ?>" class="form-control">
					<?php if ($error_level) { ?>
						<div class="help-block error"><?php echo $error_level; ?></div>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
