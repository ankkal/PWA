<?php

/**
 * Amazon Marketplace Orders API: ListOrders request model
 *
 * Fields:
 * <ul>
 * <li>SellerId: string</li>
 * <li>CreatedAfter: string</li>
 * <li>CreatedBefore: string</li>
 * <li>LastUpdatedAfter: string</li>
 * <li>LastUpdatedBefore: string</li>
 * <li>OrderStatus: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderStatusList</li>
 * <li>MarketplaceId: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_MarketplaceIdList</li>
 * <li>FulfillmentChannel: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_FulfillmentChannelList</li>
 * <li>PaymentMethod: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PaymentMethodList</li>
 * <li>BuyerEmail: string</li>
 * <li>SellerOrderId: string</li>
 * <li>MaxResultsPerPage: MaxResults</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Request_ListOrders extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'CreatedAfter' => array('FieldValue' => null, 'FieldType' => 'string'),
            'CreatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
            'LastUpdatedAfter' => array('FieldValue' => null, 'FieldType' => 'string'),
            'LastUpdatedBefore' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderStatus' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderStatusList'),
            'MarketplaceId' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_MarketplaceIdList'),
            'FulfillmentChannel' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_FulfillmentChannelList'),
            'PaymentMethod' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PaymentMethodList'),
            'BuyerEmail' => array('FieldValue' => null, 'FieldType' => 'string'),
            'SellerOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MaxResultsPerPage' => array('FieldValue' => null, 'FieldType' => 'MaxResults')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'ListOrders';
        if ($this->issetSellerId()) {
            $params['SellerId'] = $this->getSellerId();
        }
        if ($this->issetCreatedAfter()) {
            $params['CreatedAfter'] = $this->getCreatedAfter();
        }
        if ($this->issetCreatedBefore()) {
            $params['CreatedBefore'] = $this->getCreatedBefore();
        }
        if ($this->issetLastUpdatedAfter()) {
            $params['LastUpdatedAfter'] = $this->getLastUpdatedAfter();
        }
        if ($this->issetLastUpdatedBefore()) {
            $params['LastUpdatedBefore'] = $this->getLastUpdatedBefore();
        }
        if ($this->issetOrderStatus()) {
            $orderStatus = $this->getOrderStatus();
            $orderStatusIndex = 1;
            foreach ($orderStatus->getStatus() as $status) {
                $params['OrderStatus.Status.' . $orderStatusIndex] = $status;
                $orderStatusIndex++;
            }
        }
        if ($this->issetMarketplaceId()) {
            $marketplaceId = $this->getMarketplaceId();
            $marketplaceIdIndex = 1;
            foreach ($marketplaceId->getId() as $id) {
                $params['MarketplaceId.Id.' . $marketplaceIdIndex] = $id;
                $marketplaceIdIndex++;
            }
        }
        if ($this->issetFulfillmentChannel()) {
            $fulfillmentChannel = $this->getFulfillmentChannel();
            $fulfillmentChannelIndex = 1;
            foreach ($fulfillmentChannel->getChannel() as $channel) {
                $params['FulfillmentChannel.Channel.' . $fulfillmentChannelIndex] = $channel;
                $fulfillmentChannelIndex++;
            }
        }
        if ($this->issetPaymentMethod()) {
            $paymentMethod = $this->getPaymentMethod();
            $paymentMethodIndex = 1;
            foreach ($paymentMethod->getMethod() as $method) {
                $params['PaymentMethod.Method.' . $paymentMethodIndex] = $method;
                $paymentMethodIndex++;
            }
        }
        if ($this->issetBuyerEmail()) {
            $params['BuyerEmail'] = $this->getBuyerEmail();
        }
        if ($this->issetSellerOrderId()) {
            $params['SellerOrderId'] = $this->getSellerOrderId();
        }
        if ($this->issetMaxResultsPerPage()) {
            $params['MaxResultsPerPage'] = $this->getMaxResultsPerPage();
        }
        return $params;
    }

}
