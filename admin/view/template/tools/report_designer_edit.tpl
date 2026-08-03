<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2">
			<i class="hidden-xs fa fa-file-text"></i> <?php echo $heading_title; ?> &mdash; <?php echo $format_name; ?>
			<?php if ($is_active) { ?>
			<span id="rf-active-badge" class="label label-success"><?php echo $text_active; ?></span>
			<?php } else { ?>
			<span id="rf-active-badge" class="label label-success" style="display:none;"><?php echo $text_active; ?></span>
			<?php } ?>
		</div>
		<div class="pull-right">
			<button type="button" id="btnSave" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<button type="button" id="btnActivate" class="btn btn-success"<?php echo ($is_active ? ' disabled' : ''); ?>><i class="fa fa-check"></i><span class="hidden-xs"> <?php echo $button_activate; ?></span></button>
			<button type="button" id="btnPreview" class="btn btn-info"><i class="fa fa-eye"></i><span class="hidden-xs"> <?php echo $button_preview; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<p class="text-muted"><i class="fa fa-info-circle"></i> <?php echo $text_merge_tags_hint; ?></p>
		<div class="rf-merge-tags">
			<?php foreach ($merge_tags as $tag) { ?>
			<code class="rf-merge-tag">{<?php echo $tag; ?>}</code>
			<?php } ?>
		</div>
		<textarea name="html_content" id="html_content" class="ckeditor form-control"><?php echo $html_content; ?></textarea>
	</div>
</div>
<style>
.rf-merge-tags {
	margin-bottom: 10px;
}
.rf-merge-tag {
	display: inline-block;
	margin: 0 4px 4px 0;
	padding: 2px 6px;
	background: #fff3bf;
	border: 1px solid #f08c00;
	border-radius: 3px;
	color: #663c00;
	font-size: 12px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	function rfGetHtmlContent() {
		if (window.CKEDITOR && CKEDITOR.instances['html_content']) {
			CKEDITOR.instances['html_content'].updateElement();
		}

		return $('#html_content').val();
	}

	$('#btnSave').on('click', function() {
		$.ajax({
			url: '<?php echo $save; ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				report_format_id: <?php echo (int)$report_format_id; ?>,
				html_content: rfGetHtmlContent()
			},
			success: function(json) {
				if (json.success) {
					$('#notification').html('<div class="alert alert-success alert-dismissable">' + json.success + '<button type="button" class="close" data-bs-dismiss="alert">&times;</button></div>');
				} else if (json.error) {
					alert(json.error);
				}
			}
		});
	});

	$('#btnActivate').on('click', function() {
		$.ajax({
			url: '<?php echo $activate; ?>',
			type: 'POST',
			dataType: 'json',
			data: { report_format_id: <?php echo (int)$report_format_id; ?> },
			success: function(json) {
				if (json.success) {
					$('#rf-active-badge').show();
					$('#btnActivate').prop('disabled', true);
					$('#notification').html('<div class="alert alert-success alert-dismissable">' + json.success + '<button type="button" class="close" data-bs-dismiss="alert">&times;</button></div>');
				} else if (json.error) {
					alert(json.error);
				}
			}
		});
	});

	$('#btnPreview').on('click', function() {
		$.ajax({
			url: '<?php echo $preview; ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				report_format_id: <?php echo (int)$report_format_id; ?>,
				html_content: rfGetHtmlContent()
			},
			success: function(json) {
				if (json.url) {
					window.open(json.url, '_blank');
				} else if (json.error) {
					alert(json.error);
				}
			}
		});
	});
});
</script>
<?php echo $footer; ?>
