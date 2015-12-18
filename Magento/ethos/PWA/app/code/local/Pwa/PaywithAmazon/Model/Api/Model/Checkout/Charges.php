<?php

/**
 * Amazon Checkout API: Charges data type model
 *
 * Fields:
 * <ul>
 * <li>Tax: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>Shipping: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>GiftWrap: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>Promotions: Pwa_PaywithAmazon_Model_Api_Model_Checkout_PromotionList</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_Charges extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Tax' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price'),
            'Shipping' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price'),
            'GiftWrap' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price'),
            'Promotions' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_PromotionList')
        );
        parent::__construct($data);
    }

}
