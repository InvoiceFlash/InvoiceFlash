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
						<div class="input-group">
							<input type="text" name="ship_name[<?php echo $language['language_id'] ?>][name]" value="<?php echo isset($ship_name[$language['language_id']]['name']) ? $ship_name[$language['language_id']]['name'] : '' ?>" class="form-control">
							<div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>
							<?php if (isset($error_name[$language['language_id']])) { ?>
							<div class="help-block error"><?php echo $error_name[$language['language_id']]; ?></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>