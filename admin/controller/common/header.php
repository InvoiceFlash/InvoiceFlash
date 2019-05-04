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
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		
		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->data['logged'] = '';

			$this->data['home'] = $this->url->link('common/login', '', 'SSL');
		} else {
			$this->data['logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
			
			$this->data['home'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		
			// Sistema de menu de open2302
			$this->data['menus'] = array();

			// Menu
			$this->data['menus'][] = array(
				'id' => 'dashboard',
				'name' => $this->language->get('text_dashboard'),
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'children' => array()
			);

			// Files
			$files = array();

			// Files - Mail
			if ($this->user->hasPermission('access', 'catalog/mail')) {
				$files[] = array(
					'name' => $this->language->get('text_mail'),
					'href' => $this->url->link('catalog/mail', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Files - Manufacturer
			if ($this->user->hasPermission('access', 'catalog/manufacturer')) {
				$files[] = array(
					'name' => $this->language->get('text_manufacturer'),
					'href' => $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Files - Categories
			$categories = array();

			if ($this->user->hasPermission('access', 'catalog/category')) {
				$categories[] = array(
					'name' => $this->language->get('text_category'),
					'href' => $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'catalog/product')) {
				$categories[] = array(
					'name' => $this->language->get('text_product'),
					'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'catalog/option')) {
				$categories[] = array(
					'name' => $this->language->get('text_option'),
					'href' => $this->url->link('catalog/option', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($categories) {
				$files[] = array(
					'name' => $this->language->get('text_category'),
					'href' => '',
					'children' => $categories
				);
			}

			// Files - Filter
			if ($this->user->hasPermission('access', 'catalog/filter')) {
				$files[] = array(
					'name' => $this->language->get('text_filter'),
					'href' => $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Files - Profiles
			$profiles = array();

			if ($this->user->hasPermission('access', 'catalog/profile')) {
				$profiles[] = array(
					'name' => $this->language->get('text_profile'),
					'href' => $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sale/recurring')) {
				$profiles[] = array(
					'name' => $this->language->get('text_recurring'),
					'href' => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($profiles) {
				$files[] = array(
					'name' => $this->language->get('text_profile'),
					'href' => '',
					'children' => $profiles
				);
			}

			// Files - Attributes
			$attributes = array();

			if ($this->user->hasPermission('access', 'catalog/attribute')) {
				$attributes[] = array(
					'name' => $this->language->get('text_attribute'),
					'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sale/attribute_group')) {
				$attributes[] = array(
					'name' => $this->language->get('text_attribute_group'),
					'href' => $this->url->link('sale/attribute_group', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($attributes) {
				$files[] = array(
					'name' => $this->language->get('text_attribute'),
					'href' => '',
					'children' => $attributes
				);
			}

			// Files - Downloads
			if ($this->user->hasPermission('access', 'catalog/download')) {
				$files[] = array(
					'name' => $this->language->get('text_download'),
					'href' => $this->url->link('catalog/download', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Files - Shipping
			if ($this->user->hasPermission('access', 'extension/shipping')) {
				$files[] = array(
					'name' => $this->language->get('text_shipping'),
					'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Files - Payments
			if ($this->user->hasPermission('access', 'extension/payment')) {
				$files[] = array(
					'name' => $this->language->get('text_payment'),
					'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Files - Total
			if ($this->user->hasPermission('access', 'extension/total')) {
				$files[] = array(
					'name' => $this->language->get('text_total'),
					'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($files) {
				$this->data['menus'][] = array(
					'id' => 'catalog',
					'name' => $this->language->get('text_catalog'),
					'href' => '',
					'children' => $files
				);
			}

			// Sales
			$sales = array();

			// Sales - Customers
			$customers = array();

			if ($this->user->hasPermission('access', 'sale/customer')) {
				$customers[] = array(
					'name' => $this->language->get('text_customer'),
					'href' => $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sale/customer_group')) {
				$customers[] = array(
					'name' => $this->language->get('text_customer_group'),
					'href' => $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sale/customer_ban_ip')) {
				$customers[] = array(
					'name' => $this->language->get('text_customer_ban_ip'),
					'href' => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($customers) {
				$sales[] = array(
					'name' => $this->language->get('text_customer'),
					'href' => '',
					'children' => $customers
				);
			}

			// Sales - Quotes
			if ($this->user->hasPermission('access', 'sale/quote')) {
				$sales[] = array(
					'name' => $this->language->get('text_quote'),
					'href' => $this->url->link('sale/quote', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Sales - Orders
			if ($this->user->hasPermission('access', 'sale/order')) {
				$sales[] = array(
					'name' => $this->language->get('text_order'),
					'href' => $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Sales - Delivery Notes
			if ($this->user->hasPermission('access', 'sale/delivery')) {
				$sales[] = array(
					'name' => $this->language->get('text_delivery'),
					'href' => $this->url->link('sale/delivery', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Sales - Invoices
			if ($this->user->hasPermission('access', 'sale/invoice')) {
				$sales[] = array(
					'name' => $this->language->get('text_invoice'),
					'href' => $this->url->link('sale/invoice', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Sales - Receipts
			if ($this->user->hasPermission('access', 'sale/receipt')) {
				$sales[] = array(
					'name' => $this->language->get('text_receipt'),
					'href' => $this->url->link('sale/receipt', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Sales - Remittances
			if ($this->user->hasPermission('access', 'sale/remittances')) {
				$sales[] = array(
					'name' => $this->language->get('text_remittances'),
					'href' => $this->url->link('sale/remittances', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Sales - Returns
			if ($this->user->hasPermission('access', 'sale/return')) {
				$sales[] = array(
					'name' => $this->language->get('text_return'),
					'href' => $this->url->link('sale/return', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($sales) {
				$this->data['menus'][] = array(
					'id' => 'sale',
					'name' => $this->language->get('text_sale'),
					'href' => '',
					'children' => $sales
				);
			}

			// Purchases
			$purchases = array();
			
			// Purchases - Supplier
			$supplier = array();
			
			if ($this->user->hasPermission('access', 'purchases/supplier')) {
				$supplier[] = array(
					'name' => $this->language->get('text_supplier'),
					'href' => $this->url->link('purchases/supplier', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'purchases/supplier_group')) {
				$supplier[] = array(
					'name' => $this->language->get('text_supplier_group'),
					'href' => $this->url->link('purchases/supplier_group', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($supplier) {
				$purchases[] = array(
					'name' => $this->language->get('text_supplier'),
					'href' => '',
					'children' => $supplier
				);
			}

			// Purchases - Quotes
			if ($this->user->hasPermission('access', 'purchases/quote')) {
				$purchases[] = array(
					'name' => $this->language->get('text_purchases_quotes'),
					'href' => $this->url->link('purchases/quote', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			
			// Purchases - P. Orders
			if ($this->user->hasPermission('access', 'purchases/order')) {
				$purchases[] = array(
					'name' => $this->language->get('text_purchases_orders'),
					'href' => $this->url->link('purchases/order', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Purchases - Receptions
			if ($this->user->hasPermission('access', 'purchases/receptions')) {
				$purchases[] = array(
					'name' => $this->language->get('text_purchases_receptions'),
					'href' => $this->url->link('purchases/receptions', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($purchases) {
				$this->data['menus'][] = array(
					'id' => 'purchases',
					'name' => $this->language->get('text_purchases'),
					'href' => '',
					'children' => $purchases
				);
			}

			// Marketing
			$marketing = array();

			// Marketing - Potentials
			$potentials = array();

			if ($this->user->hasPermission('access', 'sale/potentials')) {
				$potentials[] = array(
					'name' => $this->language->get('text_potentials'),
					'href' => $this->url->link('sale/potentials', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			
			if ($this->user->hasPermission('access', 'sale/potentials_group')) {
				$potentials[] = array(
					'name' => $this->language->get('text_potentials_group'),
					'href' => $this->url->link('sale/potentials_group', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($potentials) {
				$marketing[] = array(
					'name' => $this->language->get('text_potentials'),
					'href' => '',
					'children' => $potentials
				);
			}
			
			// Marketing - Mailings
			if ($this->user->hasPermission('access', 'sale/contact')) {
				$marketing[] = array(
					'name' => $this->language->get('text_contact'),
					'href' => $this->url->link('sale/contact', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Marketing - Affiliates
			if ($this->user->hasPermission('access', 'sale/affiliate')) {
				$marketing[] = array(
					'name' => $this->language->get('text_affiliate'),
					'href' => $this->url->link('sale/affiliate', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Marketing - Coupons
			if ($this->user->hasPermission('access', 'sale/coupon')) {
				$marketing[] = array(
					'name' => $this->language->get('text_coupon'),
					'href' => $this->url->link('sale/coupon', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Marketing - Newsletter Subcribers
			$newsletter = array();

			if ($this->user->hasPermission('access', 'sale/newssubscribers')) {
				$newsletter[] = array(
					'name' => $this->language->get('text_newssubscribe'),
					'href' => $this->url->link('sale/newssubcribers', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'sale/mailing_list')) {
				$newsletter[] = array(
					'name' => $this->language->get('text_mailing_list'),
					'href' => $this->url->link('sale/mailing_list', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($newsletter) {
				$marketing[] = array(
					'name' => $this->language->get('text_newssubscribe'),
					'href' => '',
					'children' => $newsletter
				);
			}

			if ($marketing) {
				$this->data['menus'][] = array(
					'id' => 'marketing',
					'name' => $this->language->get('text_marketing'),
					'href' => '',
					'children' => $marketing
				);
			}

			// Payroll
			$payroll = array();

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/accounting_system')) {
				$payroll[] = array(
					'name' => $this->language->get('text_accounting_system'),
					'href' => $this->url->link('payroll/accounting_system', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/accounting_period')) {
				$payroll[] = array(
					'name' => $this->language->get('text_accounting_period'),
					'href' => $this->url->link('payroll/accounting_period', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/subaccounts')) {
				$payroll[] = array(
					'name' => $this->language->get('text_subaccounts'),
					'href' => $this->url->link('payroll/subaccounts', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/accounting_profit_loss')) {
				$payroll[] = array(
					'name' => $this->language->get('text_accounting_profit_loss'),
					'href' => $this->url->link('payroll/accounting_profit_loss', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/balance_sheet')) {
				$payroll[] = array(
					'name' => $this->language->get('text_balance_sheet'),
					'href' => $this->url->link('payroll/balance_sheet', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/journal_book')) {
				$payroll[] = array(
					'name' => $this->language->get('text_journal_book'),
					'href' => $this->url->link('payroll/journal_book', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Payroll
			if ($this->user->hasPermission('access', 'payroll/report_journal_book')) {
				$payroll[] = array(
					'name' => $this->language->get('text_report_journal_book'),
					'href' => $this->url->link('payroll/report_journal_book', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($payroll) {
				$this->data['menus'][] = array(
					'id' => 'payroll',
					'name' => $this->language->get('text_payroll'),
					'href' => '',
					'children' => $payroll
				);
			}

			// Reports
			$reports = array();

			// Reports - Sales
			$r_sales = array();

			if ($this->user->hasPermission('access', 'report/sale_order')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_report_sale_order'),
					'href' => $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/sale_delivery')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_delivery'),
					'href' => $this->url->link('report/sale_delivery', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/sale_invoice')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_invoice'),
					'href' => $this->url->link('report/sale_invoice', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/sale_tax')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_report_sale_tax'),
					'href' => $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/sale_shipping')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_report_sale_shipping'),
					'href' => $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/sale_return')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_report_sale_return'),
					'href' => $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/sale_coupon')) {
				$r_sales[] = array(
					'name' => $this->language->get('text_report_sale_coupon'),
					'href' => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($r_sales) {
					$reports[] = array(
						'name' => $this->language->get('text_sale'),
						'href' => '',
						'children' => $r_sales
					);
			}

			// Reports - Products
			$r_products = array();

			if ($this->user->hasPermission('access', 'report/product_viewed')) {
				$r_products[] = array(
					'name' => $this->language->get('text_report_product_viewed'),
					'href' => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			if ($this->user->hasPermission('access', 'report/product_purchased')) {
				$r_products[] = array(
					'name' => $this->language->get('text_report_product_purchased'),
					'href' => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($r_products) {
				$reports[] = array(
					'name' => $this->language->get('text_product'),
					'href' => '',
					'children' => $r_products
				);
			}

			// Reports - Customers
			$r_customers = array();

			if ($this->user->hasPermission('access', 'report/customer_online')) {
				$r_customers[] = array(
					'name' => $this->language->get('text_report_customer_online'),
					'href' => $this->url->link('report/customer_online'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_referer')) {
				$r_customers[] = array(
					'name' => $this->language->get('text_report_customer_referer'),
					'href' => $this->url->link('report/customer_referer'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_order')) {
				$r_customers[] = array(
					'name' => $this->language->get('text_report_customer_order'),
					'href' => $this->url->link('report/customer_order'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_reward')) {
				$r_customers[] = array(
					'name' => $this->language->get('text_report_customer_reward'),
					'href' => $this->url->link('report/customer_reward'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_credit')) {
				$r_customers[] = array(
					'name' => $this->language->get('text_report_customer_credit'),
					'href' => $this->url->link('report/customer_credit'),
					'children' => array()
				);
			}

			if ($this->user->hasPermission('access', 'report/customer_support')) {
				$r_customers[] = array(
					'name' => $this->language->get('text_report_customer_support'),
					'href' => $this->url->link('report/customer_support', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($r_customers) {
				$reports[] = array(
					'name' => $this->language->get('text_customer'),
					'href' => '',
					'children' => $r_customers
				);
			}
			
			// Reports - Affiliates
			$r_affiliates = array();

			if ($this->user->hasPermission('access', 'report/affiliate_commission')) {
				$r_affiliates[] = array(
					'name' => $this->language->get('text_report_affiliate_commission'),
					'href' => $this->url->link('report/affiliate_commission', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($r_affiliates) {
				$reports[] = array(
					'name' => $this->language->get('text_affiliate'),
					'href' => '',
					'children' => $r_affiliates
				);
			}

			// Reports - Purchases
			$r_purchases = array();

			if ($this->user->hasPermission('access', 'report/purchases_orders')) {
				$r_purchases[] = array(
					'name' => $this->language->get('text_report_purchases_orders'),
					'href' => $this->url->link('report/purchases_orders', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($r_purchases) {
				$reports[] = array(
					'name' => $this->language->get('text_purchases'),
					'href' => '',
					'children' => $r_purchases
				);
			}

			if ($reports) {
				$this->data['menus'][] = array(
					'id' => 'reports',
					'name' => $this->language->get('text_reports'),
					'href' => '',
					'children' => $reports
				);
			}

			// Support
			$support = array();

			// Support - Tickets
			if ($this->user->hasPermission('access', 'tickets/tickets')) {
				$support[] = array(
					'name' => $this->language->get('text_tickets'),
					'href' => $this->url->link('tickets/tickets', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Support - Help Topics
			if ($this->user->hasPermission('access', 'tickets/helptopics')) {
				$support[] = array(
					'name' => $this->language->get('text_help_topics'),
					'href' => $this->url->link('tickets/helptopics', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Support - Tickets Priority
			if ($this->user->hasPermission('access', 'tickets/priority')) {
				$support[] = array(
					'name' => $this->language->get('text_tickets_priority'),
					'href' => $this->url->link('tickets/priority', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Support - Tickets Status
			if ($this->user->hasPermission('access', 'tickets/tickets_status')) {
				$support[] = array(
					'name' => $this->language->get('text_tickets_setting'),
					'href' => $this->url->link('tickets/tickets_status', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Support - Setting
			if ($this->user->hasPermission('access', 'tickets/setting')) {
				$support[] = array(
					'name' => $this->language->get('text_tickets_status'),
					'href' => $this->url->link('tickets/setting', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($support) {
				$this->data['menus'][] = array(
					'id' => 'support',
					'name' => $this->language->get('text_support'),
					'href' => '',
					'children' => $support
				);
			}
			
			// Tools
			$tools = array();

			// Settings
			if ($this->user->hasPermission('access', 'setting/store')) {
				$tools[] = array(
					'name' => $this->language->get('text_setting'),
					'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Design
			$design = array();

			// Design - Layout
			if ($this->user->hasPermission('access', 'design/layout')) {
				$design[] = array(
					'name' => $this->language->get('text_layout'),
					'href' => $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Design - Banners
			if ($this->user->hasPermission('access', 'design/banner')) {
				$design[] = array(
					'name' => $this->language->get('text_banner'),
					'href' => $this->url->link('design/banner', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			
			if ($design) {
				$tools[] = array(
					'name' => $this->language->get('text_design'),
					'href' => '',
					'children' => $design
				);
			}

			// Web
			$web = array();

			// Web - Modules
			if ($this->user->hasPermission('access', 'extension/module')) {
                $web[] = array(
                    'name' => $this->language->get('text_module'),
                    'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Web - Reviews
			if ($this->user->hasPermission('access', 'catalog/review')) {
                $web[] = array(
                    'name' => $this->language->get('text_review'),
                    'href' => $this->url->link('catalog/review', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Web - Information
			if ($this->user->hasPermission('access', 'catalog/information')) {
                $web[] = array(
                    'name' => $this->language->get('text_information'),
                    'href' => $this->url->link('catalog/information', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Web - Product Feeds
			if ($this->user->hasPermission('access', 'extension/feed')) {
                $web[] = array(
                    'name' => $this->language->get('text_feed'),
                    'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}			

			if ($web) {
				$tools[] = array(
					'name' => $this->language->get('text_web'),
					'href' => '',
					'children' => $web
				);
			}

			// Blog
			$blog = array();

			// Blog - Articles
			if ($this->user->hasPermission('access', 'catalog/blog')) {
                $blog[] = array(
                    'name' => $this->language->get('text_blog_articles'),
                    'href' => $this->url->link('catalog/blog', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}

			// Blog - Comments
			if ($this->user->hasPermission('access', 'catalog/comment')) {
                $blog[] = array(
                    'name' => $this->language->get('text_blog_comments'),
                    'href' => $this->url->link('catalog/comment', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}

			// Blog - Setting
			if ($this->user->hasPermission('access', 'catalog/blogsetting')) {
                $blog[] = array(
                    'name' => $this->language->get('text_blog_setting'),
                    'href' => $this->url->link('catalog/blogsetting', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}

			if ($blog) {
				$tools[] = array(
					'name' => $this->language->get('text_blog'),
					'href' => '',
					'children' => $blog
				);
			}

			// Users
			$users = array();

			// Users - Api
			if ($this->user->hasPermission('access', 'user/api')) {
				$users[] = array(
					'name' => $this->language->get('text_api'),
					'href' => $this->url->link('user/api', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Users - Users
			if ($this->user->hasPermission('access', 'user/user')) {
                $users[] = array(
                    'name' => $this->language->get('text_user'),
                    'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Users - User Groups
			if ($this->user->hasPermission('access', 'user/user_permission')) {
                $users[] = array(
                    'name' => $this->language->get('text_user_group'),
                    'href' => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			if ($users) {
				$tools[] = array(
					'name' => $this->language->get('text_users'),
					'href' => '',
					'children' => $users
				);
			}

			// Localisation
			$localisation = array();
			// Localisation - Languages
			if ($this->user->hasPermission('access', 'localisation/language')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_language'),
                    'href' => $this->url->link('localisation/language', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Localisation - Currencies
			if ($this->user->hasPermission('access', 'localisation/currency')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_currency'),
                    'href' => $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
            }
			
			// Localisation - Statuses
			$statuses = array();
		
			// Localisation - Statuses - Stock
			if ($this->user->hasPermission('access', 'localisation/stock_status')) {
				$statuses[] = array(
					'name' => $this->language->get('text_stock_status'),
					'href' => $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Localisation - Statuses - Order
			if ($this->user->hasPermission('access', 'localisation/order_status')) {
				$statuses[] = array(
					'name' => $this->language->get('text_order_status'),
					'href' => $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Localisation - Statuses - Purchase
			if ($this->user->hasPermission('access', 'localisation/c_status')) {
				$statuses[] = array(
					'name' => $this->language->get('text_c_status'),
					'href' => $this->url->link('localisation/c_status', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			// Localisation - Statuses - Invoice
			if ($this->user->hasPermission('access', 'localisation/invoice_status')) {
				$statuses[] = array(
					'name' => $this->language->get('text_invoice_status'),
					'href' => $this->url->link('localisation/invoice_status', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			if ($statuses) {
                $localisation[] = array(
                    'name' => $this->language->get('text_statuses'),
                    'href' => '',
                    'children' => $statuses
                );
			}
			
			// Localisation - Return
			$return = array();
			
			// Localisation - Return - Return Status
			if ($this->user->hasPermission('access', 'localisation/return_status')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_status'),
					'href'     => $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()		
				);
			}
			
			// Localisation - Return - Return Action
			if ($this->user->hasPermission('access', 'localisation/return_action')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_action'),
					'href'     => $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()		
				);		
			}
			
			// Localisation - Return - Return Reason
			if ($this->user->hasPermission('access', 'localisation/return_reason')) {
				$return[] = array(
					'name'	   => $this->language->get('text_return_reason'),
					'href'     => $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()		
				);
			}
			
			if ($return) {	
				$localisation[] = array(
					'name'	   => $this->language->get('text_return'),
					'href'     => '',
					'children' => $return		
				);
			}

			// Localisation - Countries
			if ($this->user->hasPermission('access', 'localisation/country')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_country'),
                    'href' => $this->url->link('localisation/country', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
            }
			
			// Localisation - Zones
			if ($this->user->hasPermission('access', 'localisation/zone')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_zone'),
                    'href' => $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
            }
			
			// Localisation - Geo Zones
			if ($this->user->hasPermission('access', 'localisation/geo_zone')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_geo_zone'),
                    'href' => $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
            }
			
			// Localisation - Taxes
			$taxes = array();

			// Localisation - Taxes - Tax Classes
			if ($this->user->hasPermission('access', 'localisation/tax_class')) {
               	$taxes[] = array(
                    'name' => $this->language->get('text_tax_class'),
                    'href' => $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Localisation - Taxes - Tax Rates
			if ($this->user->hasPermission('access', 'localisation/tax_rate')) {
               	$taxes[] = array(
                    'name' => $this->language->get('text_tax_rate'),
                    'href' => $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			if ($taxes) {
				$localisation[] = array(
					'name' => $this->language->get('text_tax'),
					'href' => '',
					'children' => $taxes
				);
			}

			// Localisation - Length Classes
			if ($this->user->hasPermission('access', 'localisation/length_class')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_length_class'),
                    'href' => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Localisation - Weight Classes
			if ($this->user->hasPermission('access', 'localisation/weight_class')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_weight_class'),
                    'href' => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Localisation - Payment Methods
			if ($this->user->hasPermission('access', 'localisation/payment')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_payment'),
                    'href' => $this->url->link('localisation/payment', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			// Localisation - Shipping Methods
			if ($this->user->hasPermission('access', 'localisation/shipping')) {
                $localisation[] = array(
                    'name' => $this->language->get('text_shipping'),
                    'href' => $this->url->link('localisation/shipping', 'token=' . $this->session->data['token'], 'SSL'),
                    'children' => array()
                );
			}
			
			if ($localisation) {
				$tools[] = array(
					'name' => $this->language->get('text_localisation'),
					'href' => '',
					'children' => $localisation
				);
			}

			// Error Logs
			if ($this->user->hasPermission('access', 'tool/error_log')) {
				$tools[] = array(
					'name' => $this->language->get('text_error_log'),
					'href' => $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Backup Restore
			if ($this->user->hasPermission('access', 'tool/backup')) {
				$tools[] = array(
					'name' => $this->language->get('text_backup'),
					'href' => $this->url->link('tool/backup', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Export Import
			if ($this->user->hasPermission('access', 'tool/export_import')) {
				$tools[] = array(
					'name' => $this->language->get('text_export_import'),
					'href' => $this->url->link('tool/export_import', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}

			// Scheduled Task.
			if ($this->user->hasPermission('access', 'setting/cron')) {
				$tools[] = array(
					'name' => $this->language->get('text_cron'),
					'href' => $this->url->link('setting/cron', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			
			// Upgrade
			if ($this->user->hasPermission('access', 'tool/upgrade')) {
				$tools[] = array(
					'name' => $this->language->get('text_upgrade'),
					'href' => $this->url->link('tool/upgrade', 'token=' . $this->session->data['token'], 'SSL'),
					'children' => array()
				);
			}
			
			if ($tools) {
				$this->data['menus'][] = array(
					'id' => 'tools',
					'name' => $this->language->get('text_system'),
					'href' => '',
					'children' => $tools
				);
			}

			// Front End
			$this->data['text_front'] = $this->language->get('text_front');

			// Help
			$this->data['text_help'] = $this->language->get('text_help');

			// Logout
			$this->data['text_logout'] = $this->language->get('text_logout');
			$this->data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');

			$this->data['text_documentation'] = $this->language->get('text_documentation');
			$this->data['text_support'] = $this->language->get('text_support');
			$this->data['text_invoiceflash'] = $this->language->get('text_invoiceflash');


			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			$this->data['store'] = HTTP_CATALOG;
			$this->data['stores'] = array();
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