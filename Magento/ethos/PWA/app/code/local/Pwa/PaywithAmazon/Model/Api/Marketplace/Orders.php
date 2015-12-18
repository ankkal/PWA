<?php

/**
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
class Pwa_PaywithAmazon_Model_Api_Marketplace_Orders
    extends Pwa_PaywithAmazon_Model_Api_Abstract
    implements Pwa_PaywithAmazon_Model_Api_Interface_Marketplace_Orders {

    protected $_area = 'Amazon MWS Orders';

    public function listOrdersByNextToken($request) {

    }

    public function listOrderItemsByNextToken($request) {

    }

    public function getOrder(array $orderIdList) {
        $request = $this->_getApiModel('request_getOrder', array(
            'AmazonOrderId' => array('Id' => $orderIdList)
        ));
        $response = $this->_getApiClient()->getOrder($request);
        if ($response->issetGetOrderResult()) {
            $getOrderResult = $response->getGetOrderResult();
            if ($getOrderResult->issetOrders()) {
                $orders = $getOrderResult->getOrders();
                if ($orders->issetOrder()) {
                    return $orders->getOrder();
                }
            }
        }
        return null;
    }

    public function listOrderItems($request) {

    }

    public function listOrders($request) {

    }

    public function getServiceStatus($request) {}

}
