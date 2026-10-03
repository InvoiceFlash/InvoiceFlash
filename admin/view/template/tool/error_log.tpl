<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="float-start h2"><i class="fa fa-book"></i> <?php echo $heading_title; ?></div>
		<div class="float-end"><a href="<?php echo $clear; ?>" class="btn btn-default"><?php echo $button_clear; ?></a></div>
	</div>
	<div class="card-body">
		<textarea wrap="off" class="form-control" rows="24"><?php echo $log; ?></textarea>
	</div>
</div>
<?php echo $footer; ?>