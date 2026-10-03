<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="h2"><i class="fas fa-tools"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="card-body">
		<div class="card page-card">
			<div class="card-header clearfix">
				<h5><i class="fa fa-trash"></i> <?php echo $text_delete_data; ?></h5>
			</div>
			<div class="card-body">
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
