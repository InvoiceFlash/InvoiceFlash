<!DOCTYPE html>
<html class="no-js" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<base href="<?php echo $base; ?>">
	<?php if ($description) { ?>
	<meta name="description" content="<?php echo $description; ?>">
	<?php } ?>
	<?php if ($keywords) { ?>
	<meta name="keywords" content="<?php echo $keywords; ?>">
	<?php } ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<link type="text/css" href="view/stylesheet/main.css" rel="stylesheet"/>
	<link type="text/css" href="view/javascript/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
	<link type="text/css" href="view/javascript/font-awesome/css/all.min.css" rel="stylesheet"/>
	<script type="text/javascript" src="view/javascript/jquery/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="view/javascript/moment.js"></script>
	<script type="text/javascript" src="view/javascript/popper.min.js"></script>
	<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="view/javascript/common.js"></script>
	<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="view/javascript/bootstrap-typeahead.js"></script>
	<script type="text/javascript" src="view/javascript/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script>
	var text_confirm='<?php echo $text_confirm; ?>';
	</script>
	<link rel="shortcut icon" href="view/image/setting.png">
</head>
<body>
<?php if ($logged) { ?>
<div class="container-fluid">
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
		<div class="navbar-header">
			<a class="navbar-brand app-title" href="<?php echo $home; ?>">Invoice Flash</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="menu">
			<div class="navbar-nav mr-auto mt-2 mt-lg-0">
			<?php foreach ($menus as $menu) { ?>
				<?php if($menu['href']) { ?>
					<div id="<?php echo $menu['id']; ?>" class="nav-item"><a href="<?php echo $menu['href']; ?>" class="nav-link"><?php echo $menu['name']; ?></a></div>
				<?php } else { ?>
					<div id="<?php echo $menu['id']; ?>" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $menu['name']; ?></a>
						<div class="dropdown-menu">
						<?php foreach ($menu['children'] as $children_1) { ?>
							<?php if ($children_1['href']) { ?>
							<a href="<?php echo $children_1['href']; ?>" class="dropdown-item"><?php echo $children_1['name']; ?></a>
							<?php } else { ?>
							<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $children_1['name']; ?></a>
								<div class="dropdown-menu">
								<?php foreach ($children_1['children'] as $children_2) { ?>
									<?php if ($children_2['href']) { ?>
									<a href="<?php echo $children_2['href']; ?>" class="dropdown-item"><?php echo $children_2['name']; ?></a>
									<?php } else { ?>
									<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $children_2['name']; ?></a>
										<div class="dropdown-menu">
										<?php foreach ($children_2['children'] as $children_3) { ?>
										<a href="<?php echo $children_3['href']; ?>" class="dropdown-item"><?php echo $children_3['name']; ?></a>
										<?php } ?>
										</div>
									</div>
									<?php } ?>
								<?php } ?>
								</div>
							</div>
							<?php } ?>
						<?php } ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>	

				<div id="help" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_help; ?></a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="http://www.invoiceflash.com" target="_blank"><?php echo $text_invoiceflash; ?></a>
						<a class="dropdown-item" href="http://docs.invoiceflash.com/" target="_blank"><?php echo $text_documentation; ?></a>
						<a class="dropdown-item" href="http://forum.invoiceflash.com/" target="_blank"><?php echo $text_support; ?></a>
					</div>
				</div>
			</div>	
			<div class="nav navbar-nav navbar-right">
				<p class="navbar-text d-none d-xxl-inline-flex"><i class="fa fa-lock fa-fw"></i> <?php echo $logged; ?></p>
				<a href="<?php echo $logout; ?>" class="nav-link"><?php echo $text_logout; ?></a>
			</div>	
		</div>
	</nav>
</div>
<?php } ?>
<div id="content" class="container-fluid clearfix">