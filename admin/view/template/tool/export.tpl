<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card">
	<div class="card-header">
		<h2><i class="fas fa-file-export"></i> <?php echo $heading_title; ?></h2>
	</div>
	<div class="card-body">
		<?php if ($success) { ?>
		<div class="alert alert-success"><?php echo $success; ?></div>
		<?php } ?>
		<?php foreach ($warnings as $warning) { ?>
		<div class="alert alert-warning"><?php echo $warning; ?></div>
		<?php } ?>

		<form method="post" target="_blank">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-3"><?php echo $entry_date_start; ?></label>
				<div class="col-sm-6">
					<input type="text" name="date_start" value="<?php echo $date_start; ?>" class="form-control date" placeholder="DD-MM-YYYY">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-3"><?php echo $entry_date_end; ?></label>
				<div class="col-sm-6">
					<input type="text" name="date_end" value="<?php echo $date_end; ?>" class="form-control date" placeholder="DD-MM-YYYY">
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-10 offset-md-3">
					<button type="submit" formaction="<?php echo $action_contaplus; ?>" class="btn btn-primary"><i class="fa fa-file-export"></i> <?php echo $text_contaplus; ?></button>
					<button type="submit" formaction="<?php echo $action_sage50; ?>" class="btn btn-primary"><i class="fa fa-file-export"></i> <?php echo $text_sage50; ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>
