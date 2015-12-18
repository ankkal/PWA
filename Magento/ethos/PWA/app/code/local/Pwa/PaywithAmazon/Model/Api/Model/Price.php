<?php

/**
 * Amazon Common API: Price data type model
 *
 * Fields:
 * <ul>
 * <li>Amount: NonNegativeDouble</li>
 * <li>CurrencyCode: string</li>
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
abstract class Pwa_PaywithAmazon_Model_Api_Model_Price extends Pwa_PaywithAmazon_Model_Api_Model_Abstract {

    protected function _prepareInput($data = null) {
        if (is_array($data) || is_null($data)) {
            if (!isset($data['CurrencyCode'])) $data['CurrencyCode'] = self::getConfigData('currency_code');
            if (isset($data['Amount'])) $data['Amount'] = Mage::helper('paywithamazon')->sanitizePrice($data['Amount']);
        }
        return $data;
    }

    public function __construct($data = null) {
        $this->_fields = array(
            'Amount' => array('FieldValue' => null, 'FieldType' => 'NonNegativeDouble'),
            'CurrencyCode' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($this->_prepareInput($data));
    }

}
