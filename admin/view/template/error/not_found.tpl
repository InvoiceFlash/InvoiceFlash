<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="h2"><i class="fa fa-exclamation-circle"></i><?php echo $heading_title; ?></div>
	</div>
	<div class="card-body">
		<div class="alert alert-danger alert-block center"><?php echo $text_not_found; ?></div>
	</div>
</div>
<?php echo $footer; ?>