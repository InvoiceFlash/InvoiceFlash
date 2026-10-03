<div class="card-header clearfix">
	<div class="float-start h2"><i class="fa fa-<?php echo $fa; ?>"></i> <?php echo $heading_title; ?></div>
	<div class="float-end">
		<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="d-none d-lg-inline"> <?php echo $button_insert; ?></span></a>
		<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash"></i><span class="d-none d-lg-inline"> <?php echo $button_delete; ?></span></button>
	</div>
</div>