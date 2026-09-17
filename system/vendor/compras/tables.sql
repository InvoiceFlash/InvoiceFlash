DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `company` varchar(92) NOT NULL,
  `company_id` varchar(32) NOT NULL,
  `tax_id` varchar(32) NOT NULL,
  `contable_account` decimal(10,0) DEFAULT NULL,
  `email` varchar(96) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `web` varchar(255) NOT NULL,
  `address_1` varchar(128) NOT NULL,
  `address_2` varchar(128) NOT NULL,
  `city` varchar(128) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `zone_id` int(11) NOT NULL DEFAULT '0',
  `comment` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`supplier_id`),
  KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supplier_contacts`;
CREATE TABLE `supplier_contacts` (
  `supplier_contacts_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  `cname` varchar(50) DEFAULT NULL,
  `cpuesto` varchar(50) DEFAULT NULL,
  `cemail` varchar(50) DEFAULT NULL,
  `ctelef1` varchar(14) DEFAULT NULL,
  `ctelef2` varchar(14) DEFAULT NULL,
  `mnotas` mediumtext,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nusualta` int(5) DEFAULT NULL,
  `caplalta` varchar(20) DEFAULT NULL,
  `tultmod` datetime DEFAULT NULL,
  `nusuultmod` int(5) DEFAULT NULL,
  `caplultmod` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`supplier_contacts_id`),
  KEY `cemail` (`cemail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supplier_document`;
CREATE TABLE `supplier_document` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `stored_filename` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`document_id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supplier_history`;
CREATE TABLE `supplier_history` (
  `supplier_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`supplier_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_order`;
CREATE TABLE `purchase_order` (
  `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT,
  `po_number` varchar(64) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `store_name` varchar(64) NOT NULL,
  `store_url` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `shipping_method` varchar(128) NOT NULL,
  `shipping_code` varchar(128) NOT NULL,
  `payment_method` varchar(128) NOT NULL,
  `payment_code` varchar(128) NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `purchase_order_status_id` int(11) NOT NULL DEFAULT '0',
  `invoice_no` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`purchase_order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_order_history`;
CREATE TABLE `purchase_order_history` (
  `purchase_order_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `purchase_order_status_id` int(5) NOT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`purchase_order_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_order_product`;
CREATE TABLE `purchase_order_product` (
  `purchase_order_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(4) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `discount` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`purchase_order_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_order_status`;
CREATE TABLE `purchase_order_status` (
  `purchase_order_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`purchase_order_status_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `purchase_order_status` (`purchase_order_status_id`,`language_id`,`name`) VALUES ('1','1','Pending');
INSERT INTO `purchase_order_status` (`purchase_order_status_id`,`language_id`,`name`) VALUES ('2','1','Ordered');
INSERT INTO `purchase_order_status` (`purchase_order_status_id`,`language_id`,`name`) VALUES ('3','1','Partially Received');
INSERT INTO `purchase_order_status` (`purchase_order_status_id`,`language_id`,`name`) VALUES ('4','1','Received');
INSERT INTO `purchase_order_status` (`purchase_order_status_id`,`language_id`,`name`) VALUES ('5','1','Cancelled');
INSERT INTO `purchase_order_status` (`purchase_order_status_id`,`language_id`,`name`) VALUES ('6','1','Invoice Received');

DROP TABLE IF EXISTS `purchase_order_total`;
CREATE TABLE `purchase_order_total` (
  `purchase_order_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`purchase_order_total_id`),
  KEY `idx_purchase_order_total_purchase_order_id` (`purchase_order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `reception`;
CREATE TABLE `reception` (
  `reception_id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(11) NOT NULL DEFAULT '0',
  `supplier_delivery_no` varchar(64) NOT NULL DEFAULT '',
  `store_id` int(11) NOT NULL DEFAULT '0',
  `store_name` varchar(64) NOT NULL,
  `store_url` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `shipping_method` varchar(128) NOT NULL,
  `shipping_code` varchar(128) NOT NULL,
  `payment_method` varchar(128) NOT NULL,
  `payment_code` varchar(128) NOT NULL,
  `comment` text NOT NULL,
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `reception_status_id` int(11) NOT NULL DEFAULT '0',
  `invoice_no` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`reception_id`),
  KEY `idx_reception_purchase_order_id` (`purchase_order_id`),
  KEY `idx_reception_supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `reception_history`;
CREATE TABLE `reception_history` (
  `reception_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `reception_id` int(11) NOT NULL,
  `reception_status_id` int(5) NOT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`reception_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `reception_product`;
CREATE TABLE `reception_product` (
  `reception_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `reception_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(4) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `discount` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`reception_product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `reception_status`;
CREATE TABLE `reception_status` (
  `reception_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`reception_status_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `reception_status` (`reception_status_id`,`language_id`,`name`,`color`) VALUES ('1','1','Pending',NULL);
INSERT INTO `reception_status` (`reception_status_id`,`language_id`,`name`,`color`) VALUES ('1','2','Pendiente',NULL);
INSERT INTO `reception_status` (`reception_status_id`,`language_id`,`name`,`color`) VALUES ('2','1','Received',NULL);
INSERT INTO `reception_status` (`reception_status_id`,`language_id`,`name`,`color`) VALUES ('2','2','Recibido',NULL);
INSERT INTO `reception_status` (`reception_status_id`,`language_id`,`name`,`color`) VALUES ('3','1','Invoiced','#fff9c4');
INSERT INTO `reception_status` (`reception_status_id`,`language_id`,`name`,`color`) VALUES ('3','2','Facturado','#fff9c4');

DROP TABLE IF EXISTS `reception_total`;
CREATE TABLE `reception_total` (
  `reception_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `reception_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`reception_total_id`),
  KEY `idx_reception_total_reception_id` (`reception_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_invoice`;
CREATE TABLE `purchase_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_no` int(11) NOT NULL DEFAULT '0',
  `invoice_prefix` varchar(26) NOT NULL,
  `supplier_invoice_no` varchar(64) NOT NULL,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `store_name` varchar(64) NOT NULL,
  `store_url` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(96) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `payment_company` varchar(92) NOT NULL,
  `payment_company_id` varchar(32) NOT NULL,
  `payment_tax_id` varchar(32) NOT NULL,
  `payment_address_1` varchar(128) NOT NULL,
  `payment_address_2` varchar(128) NOT NULL,
  `payment_city` varchar(128) NOT NULL,
  `payment_postcode` varchar(10) NOT NULL,
  `payment_country` varchar(128) NOT NULL,
  `payment_country_id` int(11) NOT NULL,
  `payment_zone` varchar(128) NOT NULL,
  `payment_zone_id` int(11) NOT NULL,
  `payment_address_format` text NOT NULL,
  `payment_method` varchar(128) NOT NULL,
  `payment_code` varchar(128) NOT NULL,
  `shipping_company` varchar(92) NOT NULL,
  `shipping_address_1` varchar(128) NOT NULL,
  `shipping_address_2` varchar(128) NOT NULL,
  `shipping_city` varchar(128) NOT NULL,
  `shipping_postcode` varchar(10) NOT NULL,
  `shipping_country` varchar(128) NOT NULL,
  `shipping_country_id` int(11) NOT NULL,
  `shipping_zone` varchar(128) NOT NULL,
  `shipping_zone_id` int(11) NOT NULL,
  `shipping_address_format` text NOT NULL,
  `shipping_method` varchar(128) NOT NULL,
  `shipping_code` varchar(128) NOT NULL,
  `comment` text NOT NULL,
  `attachment_path` varchar(500) NOT NULL DEFAULT '',
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `invoice_status_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_value` decimal(15,8) NOT NULL DEFAULT '1.00000000',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `entry_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoice_id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_invoice_history`;
CREATE TABLE `purchase_invoice_history` (
  `invoice_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `invoice_status_id` int(5) NOT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`invoice_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_invoice_option`;
CREATE TABLE `purchase_invoice_option` (
  `invoice_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `invoice_product_id` int(11) NOT NULL,
  `product_option_id` int(11) NOT NULL,
  `product_option_value_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(32) NOT NULL,
  PRIMARY KEY (`invoice_option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_invoice_product`;
CREATE TABLE `purchase_invoice_product` (
  `invoice_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(64) NOT NULL,
  `quantity` int(4) NOT NULL,
  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `discount` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`invoice_product_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `purchase_invoice_total`;
CREATE TABLE `purchase_invoice_total` (
  `invoice_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`invoice_total_id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


