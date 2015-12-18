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
    ALTER TABLE {$this->getTable('paywithamazon/log_feed')} ADD `store_id` smallint(6) unsigned NOT NULL default 0,
    ADD FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core_store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$installer->endSetup();
