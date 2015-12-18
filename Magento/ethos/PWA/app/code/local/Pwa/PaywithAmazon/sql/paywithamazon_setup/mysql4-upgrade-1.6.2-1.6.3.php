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
	DROP TABLE IF EXISTS {$this->getTable('amazon_lop_ship')};
		CREATE TABLE {$this->getTable('amazon_lop_ship')} (
  `ship_id` int(11) unsigned NOT NULL auto_increment,
  `order_id` int(11) unsigned NOT NULL,
  `confirm_status` smallint(1) unsigned NOT NULL,
  `feed_id` int(11) unsigned NOT NULL,
  `feed_status`  varchar(255) NULL ,
  `amazon_response` TEXT NOT NULL default '',
 PRIMARY KEY (ship_id),INDEX (order_id), FOREIGN KEY(order_id) REFERENCES sales_flat_order(entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

?>