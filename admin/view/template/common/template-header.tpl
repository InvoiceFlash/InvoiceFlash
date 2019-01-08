<?php if ($breadcrumbs) { ?>
<ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li><a href="<?php echo $breadcrumb['href']; ?>" class="breadcrumb-item"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
</ul>
<?php } ?>
<?php if (!empty($error)) { ?>
<div class="alert alert-danger alert-dismissable"><?php echo $error; ?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
<?php } ?>
<?php if (!empty($error_warning)) { ?>
<div class="alert alert-danger alert-dismissable"><?php echo $error_warning; ?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
<?php } ?>
<?php if (!empty($success)) { ?>
<div class="alert alert-success alert-dismissable"><?php echo $success; ?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
<?php } ?>