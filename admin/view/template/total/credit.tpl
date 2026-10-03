<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'btc'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="card-body">
		<form class="form-classic" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_status; ?></label>
				<div class="control-field col-sm-4">
					<select name="credit_status" class="form-select">
						<?php if ($credit_status) { ?>
						<option value="1" selected=""><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected=""><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_sort_order; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="credit_sort_order" value="<?php echo $credit_sort_order; ?>" class="form-control">
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>