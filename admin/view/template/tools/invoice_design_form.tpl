<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'file-text'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_header; ?></label>
				<div class="control-field col-sm-10">
					<textarea name="header_html" class="ckeditor form-control" rows="10"><?php echo $header_html; ?></textarea>
					<span class="help-block"><?php echo $help_header; ?></span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_footer; ?></label>
				<div class="control-field col-sm-10">
					<textarea name="footer_html" class="ckeditor form-control" rows="10"><?php echo $footer_html; ?></textarea>
					<span class="help-block"><?php echo $help_footer; ?></span>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
