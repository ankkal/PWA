<?php

/**
 * Instant Order Processing Notification API: ItemCharges data type model
 *
 * Fields:
 * <ul>
 * <li>Component: Array<Pwa_PaywithAmazon_Model_Api_Model_Iopn_ChargeComponent></li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Iopn_ItemCharges extends Pwa_PaywithAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Component' => array('FieldValue' => null, 'FieldType' => array('Pwa_PaywithAmazon_Model_Api_Model_Iopn_ChargeComponent'))
        );
        parent::__construct($data);
    }

}
