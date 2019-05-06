<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'info-circle'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
					<?php foreach ($languages as $language) { ?>
						<div class="input-group"><input type="text" name="attribute_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_group_description[$language['language_id']]) ? $attribute_group_description[$language['language_id']]['name'] :''; ?>" class="form-control">
						<span class="input-group-addon"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span>
						<?php if (isset($error_name[$language['language_id']])) { ?>
						<div class="help-block text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
						<?php } ?></div>
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