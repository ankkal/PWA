<?php

/**
 * Amazon Common API: Weight data type model
 *
 * Fields:
 * <ul>
 * <li>Value: NonNegativeDouble</li>
 * <li>Unit: WeightUnit</li>
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
abstract class Pwa_PaywithAmazon_Model_Api_Model_Weight extends Pwa_PaywithAmazon_Model_Api_Model_Abstract {

    protected function _prepareInput($data = null) {
        if (is_array($data) || is_null($data)) {
            if (!isset($data['Unit'])) $data['Unit'] = self::getConfigData('weight_unit');
        }
        return $data;
    }

    public function __construct($data = null) {
        $this->_fields = array(
            'Value' => array('FieldValue' => null, 'FieldType' => 'NonNegativeDouble'),
            'Unit' => array('FieldValue' => null, 'FieldType' => 'WeightUnit')
        );
        parent::__construct($this->_prepareInput($data));
    }

}
