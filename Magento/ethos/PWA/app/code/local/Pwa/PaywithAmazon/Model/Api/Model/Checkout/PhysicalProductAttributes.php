<?php

/**
 * Amazon Checkout API: PhysicalProductAttributes data type model
 *
 * Fields:
 * <ul>
 * <li>Weight: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Weight</li>
 * <li>Condition: string</li>
 * <li>GiftOption: string</li>
 * <li>GiftMessage: string</li>
 * <li>DeliveryMethod: Pwa_PaywithAmazon_Model_Api_Model_Checkout_DeliveryMethod</li>
 * <li>ItemCharges: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Charges</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_PhysicalProductAttributes extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Weight' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Weight'),
            'Condition' => array('FieldValue' => null, 'FieldType' => 'string'),
            'GiftOption' => array('FieldValue' => null, 'FieldType' => 'string'),
            'GiftMessage' => array('FieldValue' => null, 'FieldType' => 'string'),
            'DeliveryMethod' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_DeliveryMethod'),
            'ItemCharges' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Charges')
        );
        parent::__construct($data);
    }

}
