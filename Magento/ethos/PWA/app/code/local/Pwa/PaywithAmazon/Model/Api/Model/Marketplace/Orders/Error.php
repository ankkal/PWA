<?php

/**
 * Amazon Marketplace Orders API: Error data type model
 *
 * Fields:
 * <ul>
 * <li>Type: string</li>
 * <li>Code: string</li>
 * <li>Message: string</li>
 * <li>Detail: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Object</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Error extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Type' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Code' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Message' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Detail' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Object')
        );
        parent::__construct($data);
    }

}
