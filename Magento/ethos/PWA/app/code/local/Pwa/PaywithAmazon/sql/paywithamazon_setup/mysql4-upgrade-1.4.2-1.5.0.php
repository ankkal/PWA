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
 * @copyright  Copyright (c) 2012 Pay with Amazon (http://www.pwatech.com)
 * @author     Pay with Amazon
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_api')};
    CREATE TABLE {$this->getTable('paywithamazon/log_api')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `host` varchar(255) NOT NULL default '',
        `action` varchar(255) NOT NULL default '',
        `request_method` varchar(64) NOT NULL default 'GET',
        `headers` text NULL,
        `get_data` text NULL,
        `post_data` longtext NULL,
        `file_data` longtext NULL,
        `response_code` varchar(64) NULL,
        `response` longtext NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_api_order')};
    CREATE TABLE {$this->getTable('paywithamazon/log_api_order')} (
        `log_order_id` int(10) unsigned NOT NULL auto_increment,
        `log_id` int(10) unsigned NOT NULL,
        `order_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`log_order_id`),
        FOREIGN KEY (`log_id`) REFERENCES `{$this->getTable('paywithamazon/log_api')}` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
