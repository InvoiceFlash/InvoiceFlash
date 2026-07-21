<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'balance-scale'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><?php echo $error_warning; ?></div>
		<?php } ?>
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_code; ?></label>
				<div class="col-sm-6">
					<input type="text" name="code" value="<?php echo $code; ?>" class="form-control" maxlength="12" autofocus="">
					<?php if ($error_code) { ?>
						<div class="help-block error"><?php echo $error_code; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_order_code; ?></label>
				<div class="col-sm-6">
					<input type="text" name="order_code" value="<?php echo $order_code; ?>" class="form-control" maxlength="12">
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
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_accounts; ?></label>
				<div class="col-sm-6">
					<input type="text" name="accounts" value="<?php echo $accounts_field; ?>" class="form-control" placeholder="700 701 702 703">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_level; ?></label>
				<div class="col-sm-6">
					<input type="number" name="level" value="<?php echo $level; ?>" class="form-control" min="0" max="9">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_list_after; ?></label>
				<div class="col-sm-6">
					<input type="text" name="list_after" value="<?php echo $list_after; ?>" class="form-control" maxlength="12">
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
