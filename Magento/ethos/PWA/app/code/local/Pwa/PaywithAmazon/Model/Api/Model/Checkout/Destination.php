<?php

/**
 * Amazon Checkout API: Destination data type model
 *
 * Fields:
 * <ul>
 * <li>DestinationName: string</li>
 * <li>DestinationType: DestinationType</li>
 * <li>PhysicalDestinationAttributes: Pwa_PaywithAmazon_Model_Api_Model_Checkout_PhysicalDestinationAttributes</li>
 * <li>DigitalDestinationAttributes: Pwa_PaywithAmazon_Model_Api_Model_Checkout_DigitalDestinationAttributes</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_Destination extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'DestinationName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'DestinationType' => array('FieldValue' => null, 'FieldType' => 'DestinationType'),
            'PhysicalDestinationAttributes' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_PhysicalDestinationAttributes'),
            'DigitalDestinationAttributes' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_DigitalDestinationAttributes')
        );
        parent::__construct($data);
    }

}
