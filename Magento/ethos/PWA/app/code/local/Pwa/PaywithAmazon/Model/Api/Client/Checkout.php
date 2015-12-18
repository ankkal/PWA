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
class Pwa_PaywithAmazon_Model_Api_Client_Checkout extends Pwa_PaywithAmazon_Model_Api_Client_Abstract {

    protected
        $_area = 'Amazon Checkout API';

    public function __construct() {
        parent::__construct();
        $config = array(
            'ApiUrl' => self::getConfigData('api_url', array('api' => 'checkout')),
            'ApiVersion' => self::getConfigData('api_version', array('api' => 'checkout'))
        );
        $this->_config = array_merge($this->_config, $config);
    }

}
