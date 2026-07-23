<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fas fa-tools"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<h5><i class="fa fa-trash"></i> <?php echo $text_delete_data; ?></h5>
			</div>
			<div class="panel-body">
				<div class="alert alert-warning">
					<p><b><?php echo $text_warning_delete; ?></b></p>
					<p><?php echo $text_warning_keep; ?></p>
				</div>

				<form action="<?php echo $action; ?>" method="post" id="form-wipe">
					<button type="submit" id="btn-wipe" class="btn btn-danger"><i class="fa fa-trash"></i> <?php echo $button_delete; ?></button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$('#form-wipe').on('submit', function(e) {
	if (!confirm(<?php echo json_encode($text_warning_delete); ?>)) {
		e.preventDefault();
	}
});
</script>
<?php echo $footer; ?>
