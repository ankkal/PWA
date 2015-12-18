<?php

/**
 * Amazon Marketplace Orders API: GetOrder request model
 *
 * Fields:
 * <ul>
 * <li>SellerId: string</li>
 * <li>AmazonOrderId: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderIdList</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Request_GetOrder extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderIdList')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetOrder';
        if ($this->issetSellerId()) {
            $params['SellerId'] = $this->getSellerId();
        }
        if ($this->issetAmazonOrderId()) {
            $amazonOrderId = $this->getAmazonOrderId();
            $amazonOrderIdIndex = 1;
            foreach ($amazonOrderId->getId() as $id) {
                $params['AmazonOrderId.Id.' . $amazonOrderIdIndex] = $id;
                $amazonOrderIdIndex++;
            }
        }
        return $params;
    }

}
