<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'btc'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="card-body">
		<form class="form-classic" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_total; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="low_order_fee_total" value="<?php echo $low_order_fee_total; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_fee; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="low_order_fee_fee" value="<?php echo $low_order_fee_fee; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_tax_class; ?></label>
				<div class="control-field col-sm-4">
					<select name="low_order_fee_tax_class_id" class="form-select">
						<option value="0"><?php echo $text_none; ?></option>
						<?php foreach ($tax_classes as $tax_class) { ?>
						<?php if ($tax_class['tax_class_id'] == $low_order_fee_tax_class_id) { ?>
						<option value="<?php echo $tax_class['tax_class_id']; ?>" selected=""><?php echo $tax_class['title']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_status; ?></label>
				<div class="control-field col-sm-4">
					<select name="low_order_fee_status" class="form-select">
						<?php if ($low_order_fee_status) { ?>
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
					<input type="text" name="low_order_fee_sort_order" value="<?php echo $low_order_fee_sort_order; ?>" class="form-control">
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?> 