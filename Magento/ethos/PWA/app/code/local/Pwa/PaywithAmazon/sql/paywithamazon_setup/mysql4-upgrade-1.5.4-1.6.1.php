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

$installer->run("DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_iopn_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_feed_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_report_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_exception_order')};");

$installer->run("DROP TABLE IF EXISTS {$this->getTable('paywithamazon/log_api_order')};");

$installer->endSetup();
