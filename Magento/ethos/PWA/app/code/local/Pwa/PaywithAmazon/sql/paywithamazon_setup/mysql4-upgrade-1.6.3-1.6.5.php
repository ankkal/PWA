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

if (version_compare(Mage::helper('paywithamazon')->getMagentoVersion(), '1.4.2') > 0) {

    $statuses = array(
        'pay_with_amazon' => Mage::getConfig()->getNode('global/sales/order/statuses/pay_with_amazon')->asArray()
    );

    foreach ($statuses as $statusCode => $statusInfo) {
        $data = array('status' => $statusCode, 'label' => (isset($statusInfo['label']) ? $statusInfo['label'] : ''));
        $installer->getConnection()->update($installer->getTable('sales/order_status'), $data, $installer->getConnection()->quoteInto('status=?', $statusCode));
    }
}

$installer->endSetup();
	