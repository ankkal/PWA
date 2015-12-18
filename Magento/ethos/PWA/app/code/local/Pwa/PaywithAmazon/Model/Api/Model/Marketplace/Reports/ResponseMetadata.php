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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_ResponseMetadata extends Pwa_PaywithAmazon_Model_Api_Model_ResponseMetadata {

    protected
        $_area = 'Amazon MWS Reports';

    protected function _getNamespace() {
        return self::getConfigData('api_namespace', array('api' => 'mws_reports'));
    }

}
