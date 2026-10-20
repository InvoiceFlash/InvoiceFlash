<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="card page-card">
	<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
		<div class="h2 mb-0"><i class="fa fa-file-code"></i> <?php echo $heading_title; ?></div>
		<a href="<?php echo $back; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_back; ?></a>
	</div>
	<div class="card-body">
		<p><?php echo $text_errors_intro; ?></p>
		<ul class="list-group">
			<?php foreach ($errors as $error) { ?>
			<li class="list-group-item list-group-item-danger"><?php echo $error; ?></li>
			<?php } ?>
		</ul>
	</div>
</div>

<?php echo $footer; ?>
