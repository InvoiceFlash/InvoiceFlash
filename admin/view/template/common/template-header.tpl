<?php if ($breadcrumbs) { ?>
<ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li><a href="<?php echo $breadcrumb['href']; ?>" class="breadcrumb-item"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
</ul>
<?php } ?>
<div id="notification"></div>
<?php if (!empty($error)) { ?>
<div class="alert alert-danger alert-dismissible"><?php echo $error; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php } ?>
<?php if (!empty($error_warning)) { ?>
<div class="alert alert-danger alert-dismissible"><?php echo $error_warning; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php } ?>
<?php if (!empty($success)) { ?>
<div class="alert alert-success alert-dismissible"><?php echo $success; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php } ?>
<?php if (!empty($warning)) { ?>
<div class="alert alert-warning alert-dismissible"><?php echo $warning; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php } ?>