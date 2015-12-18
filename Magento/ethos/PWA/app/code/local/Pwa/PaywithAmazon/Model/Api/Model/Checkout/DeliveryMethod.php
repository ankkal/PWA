<?php

/**
 * Amazon Checkout API: DeliveryMethod data type model
 *
 * Fields:
 * <ul>
 * <li>ServiceLevel: ShippingServiceLevel</li>
 * <li>DisplayableShippingLabel: string</li>
 * <li>DestinationName: string</li>
 * <li>ShippingCustomData: string</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_DeliveryMethod extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ServiceLevel' => array('FieldValue' => null, 'FieldType' => 'ShippingServiceLevel'),
            'DisplayableShippingLabel' => array('FieldValue' => null, 'FieldType' => 'string'),
            'DestinationName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ShippingCustomData' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
