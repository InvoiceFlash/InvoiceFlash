<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'download'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
				<?php foreach ($languages as $language) { ?>
					<div class="input-group">
						<input type="text" name="download_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($download_description[$language['language_id']]) ? $download_description[$language['language_id']]['name'] :''; ?>" class="form-control">
						<div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>
					</div>
					<?php if (isset($error_name[$language['language_id']])) { ?>
					<div class="help-block text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
					<?php } ?>
				<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="input-filename"><?php echo $entry_filename; ?></label>
				<div class="control-field col-sm-4">
					<div class="input-group">
						<input type="text" name="filename" value="<?php echo $filename; ?>" id="input-filename" class="form-control">
						<span class="input-group-btn">
							<button type="button" id="button-download" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
						</span>
					</div>
					<?php if ($error_filename){ ?>
					<div class="help-block text-danger"><?php echo $error_filename; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="mask"><?php echo $entry_mask; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="mask" value="<?php echo $mask; ?>" id="mask" class="form-control">
					<?php if ($error_mask) { ?>
					<div class="help-block text-danger"><?php echo $error_mask; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="remaining"><?php echo $entry_remaining; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="remaining" value="<?php echo $remaining; ?>" id="remaining" class="form-control">
				</div>
			</div>
			<?php if ($download_id) { ?>
				<div class="form-group">
					<label class="control-label col-sm-2" for="update"><?php echo $entry_update; ?></label>
					<div class="control-field col-sm-4">
						<label class="checkbox-inline"><?php if ($update) { ?>
							<input type="checkbox" name="update" value="1" checked="" id="update">
						<?php } else { ?>
							<input type="checkbox" name="update" value="1" id="update">
						<?php } ?>
						</label>
					</div>
				</div>
			<?php } ?>
		</form>
	</div>
</div>
<?php echo $footer; ?>