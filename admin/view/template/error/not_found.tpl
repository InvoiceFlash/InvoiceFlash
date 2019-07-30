<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-exclamation-circle"></i><?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<div class="alert alert-danger alert-block center"><?php echo $text_not_found; ?></div>
	</div>
</div>
<?php echo $footer; ?>