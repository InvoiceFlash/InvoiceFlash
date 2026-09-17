DROP TABLE IF EXISTS `ctab61`;
CREATE TABLE `ctab61` (
  `ctab61_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) NOT NULL,
  `title` varchar(255) NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `vat_regime` varchar(1) NOT NULL DEFAULT '',
  `cif` varchar(14) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(96) NOT NULL DEFAULT '',
  `street_type` varchar(4) NOT NULL DEFAULT '',
  `street` varchar(100) NOT NULL DEFAULT '',
  `number` varchar(10) NOT NULL DEFAULT '',
  `postcode` varchar(10) NOT NULL DEFAULT '',
  `city` varchar(64) NOT NULL DEFAULT '',
  `province` varchar(64) NOT NULL DEFAULT '',
  `country` varchar(64) NOT NULL DEFAULT '',
  `country_fiscal_code` varchar(20) NOT NULL DEFAULT '',
  `eu_vat_code` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`ctab61_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=517 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ctab8`;
CREATE TABLE `ctab8` (
  `ctab8_id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL DEFAULT '0',
  `line_date` date DEFAULT NULL,
  `account` varchar(12) NOT NULL DEFAULT '',
  `concept` varchar(100) NOT NULL DEFAULT '',
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `date_added` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`ctab8_id`),
  KEY `entry_id` (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

