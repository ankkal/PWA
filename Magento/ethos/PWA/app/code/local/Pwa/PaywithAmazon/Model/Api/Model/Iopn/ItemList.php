<?php

/**
 * Instant Order Processing Notification API: ItemList data type model
 *
 * Fields:
 * <ul>
 * <li>ProcessedOrderItem: Array<Pwa_PaywithAmazon_Model_Api_Model_Iopn_OrderItem></li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Iopn_ItemList extends Pwa_PaywithAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ProcessedOrderItem' => array('FieldValue' => null, 'FieldType' => array('Pwa_PaywithAmazon_Model_Api_Model_Iopn_OrderItem'))
        );
        parent::__construct($data);
    }

}
