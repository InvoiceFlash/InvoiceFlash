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
				<div id="dashboard" class="nav-item"><a href="<?php echo $home; ?>" class="nav-link"><?php echo $text_dashboard; ?></a></div>
				<div id="catalog" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_catalog; ?></a>
					<div class="dropdown-menu">
						
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_category; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $category; ?>" class="dropdown-item"><?php echo $text_category; ?></a>
								<a href="<?php echo $product; ?>" class="dropdown-item"><?php echo $text_product; ?></a>
								<a href="<?php echo $option; ?>" class="dropdown-item"><?php echo $text_option; ?></a>
							</div>
						</div>
						<a href="<?php echo $localisation_payment; ?>" class="dropdown-item"><?php echo $text_localisation_payment; ?></a>
						<a href="<?php echo $localisation_shipping; ?>" class="dropdown-item"><?php echo $text_localisation_shipping; ?></a>
					</div>
				</div>
				<div id="sale" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_sale; ?></a>
					<div class="dropdown-menu">
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_customer; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $customer; ?>" class="dropdown-item"><?php echo $text_customer; ?></a>
								<a href="<?php echo $customer_group; ?>" class="dropdown-item"><?php echo $text_customer_group; ?></a>
							</div>
						</div>
						<a href="<?php echo $quote; ?>" class="dropdown-item"><?php echo $text_quote; ?></a>
						<a href="<?php echo $invoice; ?>" class="dropdown-item"><?php echo $text_invoice; ?></a>
					</div>
				</div>
				<div id="reports" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_reports; ?></a>
					<div class="dropdown-menu">
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_sale; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_sale_invoice; ?>" class="dropdown-item"><?php echo $text_invoice; ?></a>
								<a href="<?php echo $report_sale_tax; ?>" class="dropdown-item"><?php echo $text_report_sale_tax; ?></a>
								<a href="<?php echo $report_sale_shipping; ?>" class="dropdown-item"><?php echo $text_report_sale_shipping; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_customer; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_customer_support; ?>" class="dropdown-item"><?php echo $text_report_customer_support; ?></a>
							</div>
						</div>
					</div>
				</div>
				<div id="system" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_system; ?></a>
					<div class="dropdown-menu">
						<a href="<?php echo $setting; ?>" class="dropdown-item"><?php echo $text_setting; ?></a>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_users; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $user; ?>" class="dropdown-item"><?php echo $text_user; ?></a>
								<a href="<?php echo $user_group; ?>" class="dropdown-item"><?php echo $text_user_group; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_localisation; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $language; ?>" class="dropdown-item"><?php echo $text_language; ?></a>
								<a href="<?php echo $currency; ?>" class="dropdown-item"><?php echo $text_currency; ?></a>
								<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_statuses; ?></a>
									<div class="dropdown-menu">
										<a href="<?php echo $stock_status; ?>" class="dropdown-item"><?php echo $text_stock_status; ?></a>
										<a href="<?php echo $invoice_status; ?>" class="dropdown-item"><?php echo $text_invoice_status; ?></a>
									</div>
								</div>
								<a href="<?php echo $country; ?>" class="dropdown-item"><?php echo $text_country; ?></a>
								<a href="<?php echo $zone; ?>" class="dropdown-item"><?php echo $text_zone; ?></a>
								<a href="<?php echo $geo_zone; ?>" class="dropdown-item"><?php echo $text_geo_zone; ?></a>
								<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_tax; ?></a>
									<div class="dropdown-menu">
										<a href="<?php echo $tax_class; ?>" class="dropdown-item"><?php echo $text_tax_class; ?></a>
										<a href="<?php echo $tax_rate; ?>" class="dropdown-item"><?php echo $text_tax_rate; ?></a>
									</div>
								</div>
								<a href="<?php echo $length_class; ?>" class="dropdown-item"><?php echo $text_length_class; ?></a>
								<a href="<?php echo $weight_class; ?>" class="dropdown-item"><?php echo $text_weight_class; ?></a>
							</div>
						</div>
						<a href="<?php echo $error_log; ?>" class="dropdown-item"><?php echo $text_error_log; ?></a>
						<a href="<?php echo $backup; ?>" class="dropdown-item"><?php echo $text_backup; ?></a>
						
					</div>
				</div>
				
				<div id="help" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_help; ?></a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="http://www.invoiceflash.com" target="_blank"><?php echo $text_invoiceflash; ?></a>
						<a class="dropdown-item" href="http://docs.invoiceflash.com/en-gb/introduction/" target="_blank"><?php echo $text_documentation; ?></a>
						<a class="dropdown-item" href="http://forum.invoiceflash.com/" target="_blank"><?php echo $text_support; ?></a>
					</div>
				</div>
			</div>
			<div class="nav navbar-nav navbar-right">
				<p class="navbar-text visible-lg"><i class="fa fa-lock fa-fw"></i>&nbsp;<?php echo $logged; ?></p>
				<a href="<?php echo $logout; ?>" class="nav-link"><?php echo $text_logout; ?></a>
			</div>
		</div>
	</nav>
</div>
<?php } ?>
<div class="clearfix"></div>
<div id="content" class="container-fluid">
	<div id="notification"></div>