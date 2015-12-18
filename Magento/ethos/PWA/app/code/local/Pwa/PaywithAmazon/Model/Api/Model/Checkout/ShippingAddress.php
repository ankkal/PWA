<?php

/**
 * Amazon Checkout API: ShippingAddress data type model
 *
 * Fields:
 * <ul>
 * <li>Name: string</li>
 * <li>AddressLineOne: string</li>
 * <li>AddressLineTwo: string</li>
 * <li>AddressLineThree: string</li>
 * <li>City: string</li>
 * <li>StateOrProvinceCode: string</li>
 * <li>PostalCode: string</li>
 * <li>CountryCode: string</li>
 * <li>PhoneNumber: string</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_ShippingAddress extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Name' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AddressLineOne' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AddressLineTwo' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AddressLineThree' => array('FieldValue' => null, 'FieldType' => 'string'),
            'City' => array('FieldValue' => null, 'FieldType' => 'string'),
            'StateOrProvinceCode' => array('FieldValue' => null, 'FieldType' => 'string'),
            'PostalCode' => array('FieldValue' => null, 'FieldType' => 'string'),
            'CountryCode' => array('FieldValue' => null, 'FieldType' => 'string'),
            'PhoneNumber' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
