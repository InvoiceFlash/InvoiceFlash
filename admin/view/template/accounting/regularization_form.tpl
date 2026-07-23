<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fas fa-book"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><?php echo $error_warning; ?></div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><?php echo $success; ?></div>
		<?php } ?>

		<form id="form-regularization" action="<?php echo $save; ?>" method="post">
			<input type="hidden" name="line_date" id="line_date_hidden" value="">

			<div class="form-group row align-items-center">
				<label class="col-sm-2 col-form-label"><?php echo $entry_date; ?></label>
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" id="input-date" class="form-control date" value="<?php echo date('d-m-Y'); ?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
			</div>

			<div class="form-group row align-items-center">
				<label class="col-sm-2 col-form-label"><?php echo $entry_concept; ?></label>
				<div class="col-sm-6">
					<input type="text" name="concept" class="form-control" value="<?php echo $default_concept; ?>">
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<button type="submit" class="btn btn-primary"><i class="fas fa-file-invoice"></i> <?php echo $button_create; ?></button>
					<a href="<?php echo $exit; ?>" class="btn btn-default"><?php echo $button_exit; ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
$('#form-regularization').on('submit', function() {
	$('#line_date_hidden').val($('#input-date').val());
});
//--></script>
<?php echo $footer; ?>
