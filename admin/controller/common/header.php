<?php 
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle(); 

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');

		$this->language->load('common/header');
	
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');
		$this->data['text_attribute'] = $this->language->get('text_attribute');
		$this->data['text_attribute_group'] = $this->language->get('text_attribute_group');
		$this->data['text_backup'] = $this->language->get('text_backup');
		$this->data['text_banner'] = $this->language->get('text_banner');
		$this->data['text_catalog'] = $this->language->get('text_catalog');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_country'] = $this->language->get('text_country');
		$this->data['text_coupon'] = $this->language->get('text_coupon');
		$this->data['text_currency'] = $this->language->get('text_currency');			
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_potentials'] = $this->language->get('text_potentials');
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_potentials_group'] = $this->language->get('text_potentials_group');
		$this->data['text_customer_field'] = $this->language->get('text_customer_field');
		$this->data['text_customer_ban_ip'] = $this->language->get('text_customer_ban_ip');
		$this->data['text_custom_field'] = $this->language->get('text_custom_field');
		$this->data['text_sale'] = $this->language->get('text_sale');
		$this->data['text_design'] = $this->language->get('text_design');
		$this->data['text_documentation'] = $this->language->get('text_documentation');
		$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_error_log'] = $this->language->get('text_error_log');
		$this->data['text_web'] = $this->language->get('text_web');
		$this->data['text_feed'] = $this->language->get('text_feed');
		$this->data['text_filter'] = $this->language->get('text_filter');
		$this->data['text_front'] = $this->language->get('text_front');
		$this->data['text_geo_zone'] = $this->language->get('text_geo_zone');
		$this->data['text_dashboard'] = $this->language->get('text_dashboard');
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_language'] = $this->language->get('text_language');
		$this->data['text_layout'] = $this->language->get('text_layout');
		$this->data['text_localisation'] = $this->language->get('text_localisation');
		$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_manager'] = $this->language->get('text_manager');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_invoiceflash'] = $this->language->get('text_invoiceflash');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_product'] = $this->language->get('text_product'); 
		$this->data['text_profile'] = $this->language->get('text_profile');
		$this->data['text_reports'] = $this->language->get('text_reports');
		$this->data['text_report_sale_order'] = $this->language->get('text_report_sale_order');
		$this->data['text_report_sale_tax'] = $this->language->get('text_report_sale_tax');
		$this->data['text_report_sale_shipping'] = $this->language->get('text_report_sale_shipping');
		$this->data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$this->data['text_report_sale_coupon'] = $this->language->get('text_report_sale_coupon');
		$this->data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
		$this->data['text_report_product_purchased'] = $this->language->get('text_report_product_purchased');
		$this->data['text_report_customer_online'] = $this->language->get('text_report_customer_online');
		$this->data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$this->data['text_report_customer_reward'] = $this->language->get('text_report_customer_reward');
		$this->data['text_report_customer_credit'] = $this->language->get('text_report_customer_credit');
		$this->data['text_report_affiliate_commission'] = $this->language->get('text_report_affiliate_commission');
		$this->data['text_report_sale_return'] = $this->language->get('text_report_sale_return');
		$this->data['text_report_product_viewed'] = $this->language->get('text_report_product_viewed');
		$this->data['text_report_customer_order'] = $this->language->get('text_report_customer_order');
		$this->data['text_review'] = $this->language->get('text_review');
		$this->data['text_return'] = $this->language->get('text_return');
		$this->data['text_return_action'] = $this->language->get('text_return_action');
		$this->data['text_return_reason'] = $this->language->get('text_return_reason');
		$this->data['text_return_status'] = $this->language->get('text_return_status');
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['text_shipping'] = $this->language->get('text_shipping');
		$this->data['text_setting'] = $this->language->get('text_setting');
		$this->data['text_stock_status'] = $this->language->get('text_stock_status');
		$this->data['text_system'] = $this->language->get('text_system');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_tax_class'] = $this->language->get('text_tax_class');
		$this->data['text_tax_rate'] = $this->language->get('text_tax_rate');
		$this->data['text_total'] = $this->language->get('text_total');
		$this->data['text_user'] = $this->language->get('text_user');
		$this->data['text_user_group'] = $this->language->get('text_user_group');
		$this->data['text_users'] = $this->language->get('text_users');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_voucher_theme'] = $this->language->get('text_voucher_theme');
		$this->data['text_weight_class'] = $this->language->get('text_weight_class');
		$this->data['text_length_class'] = $this->language->get('text_length_class');
		$this->data['text_zone'] = $this->language->get('text_zone');

		$this->data['text_paypal_express'] = $this->language->get('text_paypal_manage');
		$this->data['text_paypal_express_search'] = $this->language->get('text_paypal_search');
		$this->data['text_recurring_profile'] = $this->language->get('text_recurring_profile');
		
		//add
		$this->data['text_invoice'] = $this->language->get('text_invoice');
		$this->data['text_quote'] = $this->language->get('text_quote');
		$this->data['text_delivery'] = $this->language->get('text_delivery');
		$this->data['text_receipt'] = $this->language->get('text_receipt');
		$this->data['text_remittances'] = $this->language->get('text_remittances');
		$this->data['text_export_import'] = $this->language->get('text_export_import');
		$this->data['text_mail'] = $this->language->get('text_mail');
		$this->data['text_purchases'] = $this->language->get('text_purchases');
		$this->data['text_marketing'] = $this->language->get('text_marketing');
		$this->data['text_newssubscribe'] = $this->language->get('text_newssubscribe');
		$this->data['text_mailing_list'] = $this->language->get('text_mailing_list');
		$this->data['text_supplier'] = $this->language->get('text_supplier');
		$this->data['text_supplier_group'] = $this->language->get('text_supplier_group');
		$this->data['text_blog'] = $this->language->get('text_blog');
		$this->data['text_blog_articles'] = $this->language->get('text_blog_articles');
		$this->data['text_blog_comments'] = $this->language->get('text_blog_comments');
		$this->data['text_blog_setting'] = $this->language->get('text_blog_setting');
		$this->data['text_report_customer_referer'] = $this->language->get('text_report_customer_referer');
		$this->data['text_report_customer_support'] = $this->language->get('text_report_customer_support');
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['text_tickets'] = $this->language->get('text_tickets');
		$this->data['text_help_topics'] = $this->language->get('text_help_topics');
		$this->data['text_tickets_priority'] = $this->language->get('text_tickets_priority');
		$this->data['text_tickets_setting'] = $this->language->get('text_tickets_setting');
		$this->data['text_tickets_status'] = $this->language->get('text_tickets_status');
		$this->data['text_purchases_orders'] = $this->language->get('text_purchases_orders');
		$this->data['text_purchases_receptions'] = $this->language->get('text_purchases_receptions');
		$this->data['text_purchases_quotes'] = $this->language->get('text_purchases_quotes');
		$this->data['text_report_purchases_orders'] = $this->language->get('text_report_purchases_orders');
		$this->data['text_purchases_payment'] = $this->language->get('text_purchases_payment');
		$this->data['text_purchases_shipping'] = $this->language->get('text_purchases_shipping');
		$this->data['text_payroll'] = $this->language->get('text_payroll');
		$this->data['text_accounting_system'] = $this->language->get('text_accounting_system');
		$this->data['text_accounting_period'] = $this->language->get('text_accounting_period');
		$this->data['text_accounting_subaccounts'] = $this->language->get('text_accounting_subaccounts');
		$this->data['text_accounting_profit_loss'] = $this->language->get('text_accounting_profit_loss');
		$this->data['text_balance_sheet'] = $this->language->get('text_balance_sheet');
		$this->data['text_journal_book'] = $this->language->get('text_journal_book');
		$this->data['text_report_journal_book'] = $this->language->get('text_report_journal_book');

		$this->data['text_statuses'] = $this->language->get('text_statuses');
		$this->data['text_c_status'] = $this->language->get('text_c_status');
		$this->data['text_invoice_status'] = $this->language->get('text_invoice_status');

		// prueba roundecube mail
		$this->data['text_roundcube'] = 'Roundcube';


		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->data['logged'] = '';

			$this->data['home'] = $this->url->link('common/login', '', 'SSL');
		} else {
			$this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
			$this->data['pp_express_status'] = $this->config->get('pp_express_status');

			$this->data['home'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['affiliate'] = $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['attribute'] = $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['attribute_group'] = $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['banner'] = $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['coupon'] = $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['potentials'] = $this->url->link('sale/potentials', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['customer_fields'] = $this->url->link('sale/customer_field', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['potentials_group'] = $this->url->link('sale/potentials_group', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['customer_ban_ip'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['custom_field'] = $this->url->link('design/custom_field', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['feed'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['filter'] = $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['layout'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['contact'] = $this->url->link('sale/contact', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['manager'] = $this->url->link('extension/manager', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['manufacturer'] = $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['option'] = $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['order_status'] = $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['payment'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['profile'] = $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_order'] = $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_tax'] = $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_shipping'] = $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_return'] = $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_coupon'] = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_product_viewed'] = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_product_purchased'] = $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_customer_order'] = $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_customer_reward'] = $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_customer_credit'] = $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_affiliate_commission'] = $this->url->link('report/affiliate_commission', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['return_action'] = $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['return_reason'] = $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['return_status'] = $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL');			
			$this->data['shipping'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['store'] = HTTP_CATALOG;
			$this->data['stock_status'] = $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['tax_class'] = $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['tax_rate'] = $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['total'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['voucher'] = $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['voucher_theme'] = $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['weight_class'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['length_class'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL');

			//add
			$this->data['delivery'] = $this->url->link('sale/delivery', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['quote'] = $this->url->link('sale/quote', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['invoice'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['receipt'] = $this->url->link('sale/receipt', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['remittances'] = $this->url->link('sale/remittances', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['export_import'] = $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['mail'] = $this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['newssubscribe'] = $this->url->link('sale/newssubscribers', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['mailing_list'] = $this->url->link('sale/mailing_list', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['blog'] = $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['blog_comments'] = $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['blog_setting'] = $this->url->link('catalog/blogsetting', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['supplier'] = $this->url->link('purchases/supplier', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['supplier_group'] = $this->url->link('purchases/supplier_group', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_customer_referer'] = $this->url->link('report/customer_referer', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_customer_support'] = $this->url->link('report/customer_support', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_delivery'] = $this->url->link('report/sale_delivery', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_sale_invoice'] = $this->url->link('report/sale_invoice', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['help_topics'] = $this->url->link('tickets/helptopics', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['tickets_priority'] = $this->url->link('tickets/priority', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['tickets'] = $this->url->link('tickets/tickets', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['tickets_setting'] = $this->url->link('tickets/setting', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['tickets_status'] = $this->url->link('tickets/tickets_status', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['purchases_orders'] = $this->url->link('purchases/order', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['purchases_receptions'] = $this->url->link('purchases/receptions', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['purchases_quote'] = $this->url->link('purchases/quote', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['purchases_payment'] = $this->url->link('purchases/payment', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['purchases_shipping'] = $this->url->link('purchases/shipping', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_purchases_orders'] = $this->url->link('report/purchases_orders', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['accounting_system'] = $this->url->link('payroll/accounting_system', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['accounting_period'] = $this->url->link('payroll/accounting_period', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['accounting_subaccounts'] = $this->url->link('payroll/accounting_subaccounts', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['accounting_profit_loss'] = $this->url->link('payroll/accounting_profit_loss', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['balance_sheet'] = $this->url->link('payroll/balance_sheet', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['journal_book'] = $this->url->link('payroll/journal_book', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['report_journal_book'] = $this->url->link('payroll/report_journal_book', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['openbay_show_menu'] = $this->config->get('openbaymanager_show_menu');

			$this->data['openbay_link_extension'] = $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_orders'] = $this->url->link('extension/openbay/orderList', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_items'] = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_ebay'] = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_ebay_settings'] = $this->url->link('openbay/openbay/settings', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_ebay_links'] = $this->url->link('openbay/openbay/viewItemLinks', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_ebay_orderimport'] = $this->url->link('openbay/openbay/viewOrderImport', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_amazon'] = $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_amazon_settings'] = $this->url->link('openbay/amazon/settings', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_amazon_links'] = $this->url->link('openbay/amazon/itemLinks', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_amazonus'] = $this->url->link('openbay/amazonus', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_amazonus_settings'] = $this->url->link('openbay/amazonus/settings', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['openbay_link_amazonus_links'] = $this->url->link('openbay/amazonus/itemLinks', 'token=' . $this->session->data['token'], 'SSL');

			$this->data['paypal_express'] = $this->url->link('payment/pp_express', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['paypal_express_search'] = $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['recurring_profile'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL');
			
			$this->data['c_status'] = $this->url->link('localisation/c_status', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['invoice_status'] = $this->url->link('localisation/invoice_status', 'token=' . $this->session->data['token'], 'SSL');

			// Prueba roundcube
			$this->data['roundcube'] = $this->url->link('catalog/roundcube', 'token=' . $this->session->data['token'], 'SSL');


			$this->data['stores'] = array();

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$this->data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}			
		}

		$this->template = 'common/header.tpl';

		$this->render();
	}
}
?>