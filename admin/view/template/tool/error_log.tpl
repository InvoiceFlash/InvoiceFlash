<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-book"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right"><a href="<?php echo $clear; ?>" class="btn btn-default"><?php echo $button_clear; ?></a></div>
	</div>
	<div class="panel-body">
		<textarea wrap="off" class="form-control" rows="24"><?php echo $log; ?></textarea>
	</div>
</div>
<?php echo $footer; ?>