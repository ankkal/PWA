<?php

/**
 * Amazon Marketplace Orders API: Order data type model
 *
 * Fields:
 * <ul>
 * <li>AmazonOrderId: string</li>
 * <li>SellerOrderId: string</li>
 * <li>PurchaseDate: string</li>
 * <li>LastUpdateDate: string</li>
 * <li>OrderStatus: OrderStatusEnum</li>
 * <li>FulfillmentChannel: FulfillmentChannelEnum</li>
 * <li>SalesChannel: string</li>
 * <li>OrderChannel: string</li>
 * <li>ShipServiceLevel: string</li>
 * <li>ShippingAddress: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Address</li>
 * <li>OrderTotal: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>NumberOfItemsShipped: int</li>
 * <li>NumberOfItemsUnshipped: int</li>
 * <li>PaymentExecutionDetail: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PaymentExecutionDetailItemList</li>
 * <li>PaymentMethod: PaymentMethodEnum</li>
 * <li>MarketplaceId: string</li>
 * <li>BuyerEmail: string</li>
 * <li>BuyerName: string</li>
 * <li>ShipmentServiceLevelCategory: string</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Order extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'AmazonOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'SellerOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'PurchaseDate' => array('FieldValue' => null, 'FieldType' => 'string'),
            'LastUpdateDate' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderStatus' => array('FieldValue' => null, 'FieldType' => 'OrderStatusEnum'),
            'FulfillmentChannel' => array('FieldValue' => null, 'FieldType' => 'FulfillmentChannelEnum'),
            'SalesChannel' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderChannel' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ShipServiceLevel' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ShippingAddress' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Address'),
            'OrderTotal' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'NumberOfItemsShipped' => array('FieldValue' => null, 'FieldType' => 'int'),
            'NumberOfItemsUnshipped' => array('FieldValue' => null, 'FieldType' => 'int'),
            'PaymentExecutionDetail' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PaymentExecutionDetailItemList'),
            'PaymentMethod' => array('FieldValue' => null, 'FieldType' => 'PaymentMethodEnum'),
            'MarketplaceId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'BuyerEmail' => array('FieldValue' => null, 'FieldType' => 'string'),
            'BuyerName' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ShipmentServiceLevelCategory' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
