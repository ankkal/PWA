<?php

/**
 * Amazon Marketplace Orders API: ListOrders result model
 *
 * Fields:
 * <ul>
 * <li>NextToken: string</li>
 * <li>CreatedBefore: string</li>
 * <li>LastUpdatedBefore: string</li>
 * <li>Orders: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderList</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Result_ListOrders extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
            'CreatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
            'LastUpdatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Orders' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderList')
        );
        parent::__construct($data);
    }

}
