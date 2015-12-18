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
abstract class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract extends Pwa_PaywithAmazon_Model_Api_Model_Abstract {

    protected
        $_area = 'Amazon MWS Orders';

    protected function _prepareInput($data = null) {
        if (is_array($data) || is_null($data)) {
            if (!isset($data['SellerId'])) $data['SellerId'] = self::getConfigData('merchant_id');
        }
        return $data;
    }

    public function __construct($data = null) {
        parent::__construct($this->_prepareInput($data));
    }

    protected function _getNamespace() {
        return self::getConfigData('api_namespace', array('api' => 'mws_orders'));
    }

}
