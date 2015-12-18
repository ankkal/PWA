<?php

/**
 * Amazon Checkout API: Promotion data type model
 *
 * Fields:
 * <ul>
 * <li>PromotionId: IdType</li>
 * <li>Description: string</li>
 * <li>Discount: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_Promotion extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PromotionId' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'Description' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Discount' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price')
        );
        parent::__construct($data);
    }

}
