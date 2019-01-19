<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'user'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
					<?php foreach ($languages as $language) { ?>
						<div class="input-group"><input type="text" name="customer_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['name'] :''; ?>" class="form-control">
						<div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>
						<?php if (isset($error_name[$language['language_id']])) { ?>
						<div class="help-block error"><?php echo $error_name[$language['language_id']]; ?></div>
						<?php } ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_description; ?></label>
				<div class="control-field col-sm-4">
				<?php foreach ($languages as $language) { ?>
					<div class="input-group"><textarea name="customer_group_description[<?php echo $language['language_id']; ?>][description]" class="form-control" rows="3"><?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['description'] :''; ?></textarea>
					<div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>
					</div>
				<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_approval; ?></label>
				<div class="control-field col-sm-4">
					<?php if ($approval) { ?>
					<label class="radio-inline"><input type="radio" name="approval" value="1" checked=""><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="approval" value="0"><?php echo $text_no; ?></label>
					<?php } else { ?>
					<label class="radio-inline"><input type="radio" name="approval" value="1"><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="approval" value="0" checked=""><?php echo $text_no; ?></label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_company_id_display; ?></label>
				<div class="control-field col-sm-4">
					<?php if ($company_id_display) { ?>
					<label class="radio-inline"><input type="radio" name="company_id_display" value="1" checked=""><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="company_id_display" value="0"><?php echo $text_no; ?></label>
					<?php } else { ?>
					<label class="radio-inline"><input type="radio" name="company_id_display" value="1"><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="company_id_display" value="0" checked=""><?php echo $text_no; ?></label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_company_id_required; ?></label>
				<div class="control-field col-sm-4">
					<?php if ($company_id_required) { ?>
					<label class="radio-inline"><input type="radio" name="company_id_required" value="1" checked=""><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="company_id_required" value="0"><?php echo $text_no; ?></label>
					<?php } else { ?>
					<label class="radio-inline"><input type="radio" name="company_id_required" value="1"><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="company_id_required" value="0" checked=""><?php echo $text_no; ?></label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_tax_id_display; ?></label>
				<div class="control-field col-sm-4">
					<?php if ($tax_id_display) { ?>
					<label class="radio-inline"><input type="radio" name="tax_id_display" value="1" checked=""><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="tax_id_display" value="0"><?php echo $text_no; ?></label>
					<?php } else { ?>
					<label class="radio-inline"><input type="radio" name="tax_id_display" value="1"><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="tax_id_display" value="0" checked=""><?php echo $text_no; ?></label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_tax_id_required; ?></label>
				<div class="control-field col-sm-4">
					<?php if ($tax_id_required) { ?>
					<label class="radio-inline"><input type="radio" name="tax_id_required" value="1" checked=""><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="tax_id_required" value="0"><?php echo $text_no; ?></label>
					<?php } else { ?>
					<label class="radio-inline"><input type="radio" name="tax_id_required" value="1"><?php echo $text_yes; ?></label>
					<label class="radio-inline"><input type="radio" name="tax_id_required" value="0" checked=""><?php echo $text_no; ?></label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_sort_order; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control">
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>