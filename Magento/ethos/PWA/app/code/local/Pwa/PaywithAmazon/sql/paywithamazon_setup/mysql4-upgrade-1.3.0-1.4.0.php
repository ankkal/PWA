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
 * @copyright  Copyright (c) Pay with Amazon
 * @author     Pay with Amazon
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_iopn')};
    CREATE TABLE {$this->getTable('paywithamazon/log_iopn')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `notification_type` varchar(255) NOT NULL default '',
        `uuid` varchar(255) NOT NULL default '',
        `notification_content` longtext NULL,
        `processing_result` varchar(64) NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_iopn_order')};
    CREATE TABLE {$this->getTable('paywithamazon/log_iopn_order')} (
        `log_order_id` int(10) unsigned NOT NULL auto_increment,
        `log_id` int(10) unsigned NOT NULL,
        `order_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`log_order_id`),
        FOREIGN KEY (`log_id`) REFERENCES `{$this->getTable('paywithamazon/log_iopn')}` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_feed')};
    CREATE TABLE {$this->getTable('paywithamazon/log_feed')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `feed_type` varchar(255) NOT NULL default '',
        `submission_id` varchar(64) NOT NULL default '',
        `feed_content` longtext NULL,
        `processing_status` varchar(64) NULL,
        `processing_result` longtext NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_feed_order')};
    CREATE TABLE {$this->getTable('paywithamazon/log_feed_order')} (
        `log_order_id` int(10) unsigned NOT NULL auto_increment,
        `log_id` int(10) unsigned NOT NULL,
        `order_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`log_order_id`),
        FOREIGN KEY (`log_id`) REFERENCES `{$this->getTable('paywithamazon/log_feed')}` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_report')};
    CREATE TABLE {$this->getTable('paywithamazon/log_report')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `report_type` varchar(255) NOT NULL default '',
        `report_request_id` varchar(64) NOT NULL default '',
        `report_id` varchar(64) NOT NULL default '',
        `report_content` longtext NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_report_order')};
    CREATE TABLE {$this->getTable('paywithamazon/log_report_order')} (
        `log_order_id` int(10) unsigned NOT NULL auto_increment,
        `log_id` int(10) unsigned NOT NULL,
        `order_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`log_order_id`),
        FOREIGN KEY (`log_id`) REFERENCES `{$this->getTable('paywithamazon/log_report')}` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_exception')};
    CREATE TABLE {$this->getTable('paywithamazon/log_exception')} (
        `log_id` int(10) unsigned NOT NULL auto_increment,
        `message` longtext NOT NULL default '',
        `error_code` varchar(255) NOT NULL default '',
        `stack_trace` longtext NULL,
        `area` varchar(255) NOT NULL default '',
        `request_id` varchar(255) NULL default NULL,
        `creation_time` timestamp NOT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_exception_order')};
    CREATE TABLE {$this->getTable('paywithamazon/log_exception_order')} (
        `log_order_id` int(10) unsigned NOT NULL auto_increment,
        `log_id` int(10) unsigned NOT NULL,
        `order_id` int(10) unsigned NOT NULL,
        PRIMARY KEY (`log_order_id`),
        FOREIGN KEY (`log_id`) REFERENCES `{$this->getTable('paywithamazon/log_exception')}` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
