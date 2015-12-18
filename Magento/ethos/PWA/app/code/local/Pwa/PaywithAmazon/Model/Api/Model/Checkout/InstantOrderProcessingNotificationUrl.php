<?php

/**
 * Amazon Checkout API: InstantOrderProcessingNotificationUrl data type model
 *
 * Fields:
 * <ul>
 * <li>IntegratorURL: string</li>
 * <li>MerchantURL: string</li>
 * </ul>
 *
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_InstantOrderProcessingNotificationUrl extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'IntegratorURL' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MerchantURL' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
