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
	<link type="text/css" href="view/javascript/font-awesome/css/fontawesome-all.css" rel="stylesheet"/>
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
						<a href="<?php echo $mail; ?>" class="dropdown-item"><?php echo $text_mail; ?></a>
						<a href="<?php echo $manufacturer; ?>" class="dropdown-item"><?php echo $text_manufacturer; ?></a>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_category; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $category; ?>" class="dropdown-item"><?php echo $text_category; ?></a>
								<a href="<?php echo $product; ?>" class="dropdown-item"><?php echo $text_product; ?></a>
								<a href="<?php echo $option; ?>" class="dropdown-item"><?php echo $text_option; ?></a>
							</div>
						</div>
						<a href="<?php echo $filter; ?>" class="dropdown-item"><?php echo $text_filter; ?></a>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_profile; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $profile; ?>" class="dropdown-item"><?php echo $text_profile; ?></a>
								<a href="<?php echo $recurring_profile; ?>" class="dropdown-item"><?php echo $text_recurring_profile; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_attribute; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $attribute; ?>" class="dropdown-item"><?php echo $text_attribute; ?></a>
								<a href="<?php echo $attribute_group; ?>" class="dropdown-item"><?php echo $text_attribute_group; ?></a>
							</div>
						</div>
						<a href="<?php echo $download; ?>" class="dropdown-item"><?php echo $text_download; ?></a>
						<a href="<?php echo $shipping; ?>" class="dropdown-item"><?php echo $text_shipping; ?></a>
						<a href="<?php echo $payment; ?>" class="dropdown-item"><?php echo $text_payment; ?></a>
						<a href="<?php echo $total; ?>" class="dropdown-item"><?php echo $text_total; ?></a>
					</div>
				</div>
				<div id="sale" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_sale; ?></a>
					<div class="dropdown-menu">
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_customer; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $customer; ?>" class="dropdown-item"><?php echo $text_customer; ?></a>
								<a href="<?php echo $customer_group; ?>" class="dropdown-item"><?php echo $text_customer_group; ?></a>
								<a href="<?php echo $customer_ban_ip; ?>" class="dropdown-item"><?php echo $text_customer_ban_ip; ?></a>
							</div>
						</div>
						<a href="<?php echo $quote; ?>" class="dropdown-item"><?php echo $text_quote; ?></a>
						<a href="<?php echo $order; ?>" class="dropdown-item"><?php echo $text_order; ?></a>
						<a href="<?php echo $delivery; ?>" class="dropdown-item"><?php echo $text_delivery; ?></a>
						<a href="<?php echo $invoice; ?>" class="dropdown-item"><?php echo $text_invoice; ?></a>
						<a href="<?php echo $receipt; ?>" class="dropdown-item"><?php echo $text_receipt; ?></a>
						<a href="<?php echo $remittances; ?>" class="dropdown-item"><?php echo $text_remittances; ?></a>
						<a href="<?php echo $return; ?>" class="dropdown-item"><?php echo $text_return; ?></a>
					</div>
				</div>
				<div id="purchases" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_purchases; ?></a>
					<div class="dropdown-menu">
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_supplier; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $supplier; ?>" class="dropdown-item"><?php echo $text_supplier; ?></a>
								<a href="<?php echo $supplier_group; ?>" class="dropdown-item"><?php echo $text_supplier_group; ?></a>
							</div>
						</div>
						<a href="<?php echo $purchases_quote; ?>" class="dropdown-item"><?php echo $text_purchases_quotes; ?></a>
						<a href="<?php echo $purchases_orders; ?>" class="dropdown-item"><?php echo $text_purchases_orders; ?></a>
						<a href="<?php echo $purchases_receptions; ?>" class="dropdown-item"><?php echo $text_purchases_receptions; ?></a>
						<a href="<?php echo $purchases_payment; ?>" class="dropdown-item"><?php echo $text_purchases_payment; ?></a>
						<a href="<?php echo $purchases_shipping; ?>" class="dropdown-item"><?php echo $text_purchases_shipping; ?></a>
					</div>
				</div>
				<div id="marketing" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_marketing; ?></a>
					<div class="dropdown-menu">
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_potentials; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $potentials; ?>" class="dropdown-item"><?php echo $text_potentials; ?></a>
								<a href="<?php echo $potentials_group; ?>" class="dropdown-item"><?php echo $text_potentials_group; ?></a>
							</div>
						</div>
						<a href="<?php echo $contact; ?>" class="dropdown-item"><?php echo $text_contact; ?></a>
						<a href="<?php echo $affiliate; ?>" class="dropdown-item"><?php echo $text_affiliate; ?></a>
						<a href="<?php echo $coupon; ?>" class="dropdown-item"><?php echo $text_coupon; ?></a>
						<div class="dropdown-submenu">
							<a class="dropdown-item dropdown-toggle"><?php echo $text_newssubscribe; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $newssubscribe; ?>" class="dropdown-item"><?php echo $text_newssubscribe; ?></a>
								<a href="<?php echo $mailing_list; ?>" class="dropdown-item"><?php echo $text_mailing_list; ?></a></div>
						</div>
					</div>
				</div>
				<div id="payroll" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_payroll; ?></a>
					<div class="dropdown-menu">
						<a href="<?php echo $accounting_system; ?>" class="dropdown-item"><?php echo $text_accounting_system; ?></a>
						<a href="<?php echo $accounting_period; ?>" class="dropdown-item"><?php echo $text_accounting_period; ?></a>
						<a href="<?php echo $accounting_subaccounts; ?>" class="dropdown-item"><?php echo $text_accounting_subaccounts; ?></a>
						<a href="<?php echo $accounting_profit_loss; ?>" class="dropdown-item"><?php echo $text_accounting_profit_loss; ?></a>
						<a href="<?php echo $balance_sheet; ?>" class="dropdown-item"><?php echo $text_balance_sheet; ?></a>
						<a href="<?php echo $journal_book; ?>" class="dropdown-item"><?php echo $text_journal_book; ?></a>
						<a href="<?php echo $report_journal_book; ?>" class="dropdown-item"><?php echo $text_report_journal_book; ?></a>
					</div>
				</div>
				<div id="reports" class="nav-item dropdown">
					<a class="nav-link dropdown-toggle"><?php echo $text_reports; ?></a>
					<div class="dropdown-menu">
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_sale; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_sale_order; ?>" class="dropdown-item"><?php echo $text_report_sale_order; ?></a>
								<a href="<?php echo $report_sale_delivery; ?>" class="dropdown-item"><?php echo $text_delivery; ?></a>
								<a href="<?php echo $report_sale_invoice; ?>" class="dropdown-item"><?php echo $text_invoice; ?></a>
								<a href="<?php echo $report_sale_tax; ?>" class="dropdown-item"><?php echo $text_report_sale_tax; ?></a>
								<a href="<?php echo $report_sale_shipping; ?>" class="dropdown-item"><?php echo $text_report_sale_shipping; ?></a>
								<a href="<?php echo $report_sale_return; ?>" class="dropdown-item"><?php echo $text_report_sale_return; ?></a>
								<a href="<?php echo $report_sale_coupon; ?>" class="dropdown-item"><?php echo $text_report_sale_coupon; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_product; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_product_viewed; ?>" class="dropdown-item"><?php echo $text_report_product_viewed; ?></a>
								<a href="<?php echo $report_product_purchased; ?>" class="dropdown-item"><?php echo $text_report_product_purchased; ?></a>
							</div>
						</div>		
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_customer; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_customer_online; ?>" class="dropdown-item"><?php echo $text_report_customer_online; ?></a>
								<a href="<?php echo $report_customer_referer; ?>" class="dropdown-item"><?php echo $text_report_customer_referer; ?></a>
								<a href="<?php echo $report_customer_order; ?>" class="dropdown-item"><?php echo $text_report_customer_order; ?></a>
								<a href="<?php echo $report_customer_reward; ?>" class="dropdown-item"><?php echo $text_report_customer_reward; ?></a>
								<a href="<?php echo $report_customer_credit; ?>" class="dropdown-item"><?php echo $text_report_customer_credit; ?></a>
								<a href="<?php echo $report_customer_support; ?>" class="dropdown-item"><?php echo $text_report_customer_support; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_affiliate; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_affiliate_commission; ?>" class="dropdown-item"><?php echo $text_report_affiliate_commission; ?></a>
							</div>								
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_purchases; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $report_purchases_orders; ?>" class="dropdown-item"><?php echo $text_report_purchases_orders; ?></a>
							</div>
						</div>
					</div>
				</div>
				<div id="tickets" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_support; ?></a> 
					<div class="dropdown-menu">
						<a href="<?php echo $tickets; ?>" class="dropdown-item"><?php echo $text_tickets; ?></a>
						<a href="<?php echo $help_topics; ?>" class="dropdown-item"><?php echo $text_help_topics; ?></a>
						<a href="<?php echo $tickets_priority; ?>" class="dropdown-item"><?php echo $text_tickets_priority; ?></a>
						<a href="<?php echo $tickets_status; ?>" class="dropdown-item"><?php echo $text_tickets_status; ?></a>
						<a href="<?php echo $tickets_setting; ?>" class="dropdown-item"><?php echo $text_tickets_setting; ?></a>
					</div>
				</div>
				<div id="system" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_system; ?></a>
					<div class="dropdown-menu">
						<a href="<?php echo $setting; ?>" class="dropdown-item"><?php echo $text_setting; ?></a>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_design; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $layout; ?>" class="dropdown-item"><?php echo $text_layout; ?></a>
								<a href="<?php echo $banner; ?>" class="dropdown-item"><?php echo $text_banner; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_web; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $module; ?>" class="dropdown-item"><?php echo $text_module; ?></a>
								<a href="<?php echo $review; ?>" class="dropdown-item"><?php echo $text_review; ?></a>
								<a href="<?php echo $information; ?>" class="dropdown-item"><?php echo $text_information; ?></a>
								<a href="<?php echo $feed; ?>" class="dropdown-item"><?php echo $text_feed; ?></a>
							</div>
						</div>
						<div class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"><?php echo $text_blog; ?></a>
							<div class="dropdown-menu">
								<a href="<?php echo $blog; ?>" class="dropdown-item"><?php echo $text_blog_articles; ?></a>
								<a href="<?php echo $blog_comments; ?>" class="dropdown-item"><?php echo $text_blog_comments; ?></a>
								<a href="<?php echo $blog_setting; ?>" class="dropdown-item"><?php echo $text_blog_setting; ?></a>
							</div>
						</div>
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
								<a href="<?php echo $stock_status; ?>" class="dropdown-item"><?php echo $text_stock_status; ?></a>
								<a href="<?php echo $order_status; ?>" class="dropdown-item"><?php echo $text_order_status; ?></a>
								<a href="<?php echo $c_status; ?>" class="dropdown-item"><?php echo $text_c_status; ?></a>
								<div class="dropdown-submenu">
									<div class="dropdown-menu">
										<a href="<?php echo $return_status; ?>" class="dropdown-item"><?php echo $text_return_status; ?></a>
										<a href="<?php echo $return_action; ?>" class="dropdown-item"><?php echo $text_return_action; ?></a>
										<a href="<?php echo $return_reason; ?>" class="dropdown-item"><?php echo $text_return_reason; ?></a>
									</div>
								</div>
								<a href="<?php echo $country; ?>" class="dropdown-item"><?php echo $text_country; ?></a>
								<a href="<?php echo $zone; ?>" class="dropdown-item"><?php echo $text_zone; ?></a>
								<a href="<?php echo $geo_zone; ?>" class="dropdown-item"><?php echo $text_geo_zone; ?></a>
								<div class="dropdown-submenu">
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
						<a href="<?php echo $export_import; ?>" class="dropdown-item"><?php echo $text_export_import; ?></a>
					</div>
				</div>
				<div id="system" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_front; ?></a>
					<div class="dropdown-menu">
						<a href="<?php echo $store; ?>" target="_blank" class="dropdown-item"><?php echo $text_front; ?></a>
						<?php foreach ($stores as $stores) { ?>
						<a href="<?php echo $stores['href']; ?>" target="_blank" class="dropdown-item"><?php echo $stores['name']; ?></a>
						<?php } ?>
					</div>
				</div>
				<div id="help" class="nav-item dropdown"><a class="nav-link dropdown-toggle"><?php echo $text_help; ?></a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="http://www.ramonea.com" target="_blank"><?php echo $text_invoiceflash; ?></a>
						<a class="dropdown-item" href="http://docs.ramonea.com/en-gb/introduction/" target="_blank"><?php echo $text_documentation; ?></a>
						<a class="dropdown-item" href="http://forum.ramonea.com/" target="_blank"><?php echo $text_support; ?></a>
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
<div id="content" class="container-fluid">
	<div id="notification"></div>