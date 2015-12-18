<?php

/**
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) Pay with Amazon
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from Pay with Amazon
 *
 * @category   Pwa
 * @package    Pwa_PaywithAmazon
 * @copyright  Pay with Amazon
 * @author     Pay with Amazon
 */

$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('amazon_log_cart_xml')}` (
  `xml_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entity ID',
  `quote_id` int(10) unsigned DEFAULT NULL COMMENT 'Quote ID',
  `xml_data` text COMMENT 'xml encrypted cart data',
  PRIMARY KEY (`xml_id`),
  KEY `IDX_AMAZON_LOG_CART_XML_QUOTE_ID` (`quote_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Pay With Amazon encrypted cart details' AUTO_INCREMENT=0 ;

ALTER TABLE `{$this->getTable('amazon_log_cart_xml')}`
  ADD CONSTRAINT `FK_AMAZON_LOG_CART_XML_QUOTE_ID_SALES_FLAT_QUOTE_ENTITY_ID` FOREIGN KEY (`quote_id`) REFERENCES `{$this->getTable('sales_flat_quote')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
$installer->endSetup();
?>