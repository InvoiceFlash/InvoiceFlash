<div class="card-header clearfix">
	<div class="float-start h2"><i class="fa fa-<?php echo $fa; ?>"></i> <?php echo $heading_title; ?></div>
	<div class="float-end">
		<?php if (!empty($title_buttons)) { echo $title_buttons; } ?>
		<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="d-none d-lg-inline"> <?php echo $button_save; ?></span></button>
		<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="d-none d-lg-inline"> <?php echo $button_cancel; ?></span></a>
	</div>
</div>