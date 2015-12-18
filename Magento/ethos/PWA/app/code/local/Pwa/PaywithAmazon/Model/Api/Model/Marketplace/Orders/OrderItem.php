<?php

/**
 * Amazon Marketplace Orders API: OrderItem data type model
 *
 * Fields:
 * <ul>
 * <li>ASIN: string</li>
 * <li>SellerSKU: string</li>
 * <li>OrderItemId: string</li>
 * <li>Title: string</li>
 * <li>QuantityOrdered: int</li>
 * <li>QuantityShipped: int</li>
 * <li>ItemPrice: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ShippingPrice: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>GiftWrapPrice: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ItemTax: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ShippingTax: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>GiftWrapTax: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>ShippingDiscount: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>PromotionDiscount: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>PromotionIds: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PromotionIdList</li>
 * <li>CODFee: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>CODFeeDiscount: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money</li>
 * <li>GiftMessageText: string</li>
 * <li>GiftWrapLevel: string</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_OrderItem extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ASIN' => array('FieldValue' => null, 'FieldType' => 'string'),
            'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
            'OrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Title' => array('FieldValue' => null, 'FieldType' => 'string'),
            'QuantityOrdered' => array('FieldValue' => null, 'FieldType' => 'int'),
            'QuantityShipped' => array('FieldValue' => null, 'FieldType' => 'int'),
            'ItemPrice' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ShippingPrice' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'GiftWrapPrice' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ItemTax' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ShippingTax' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'GiftWrapTax' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'ShippingDiscount' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'PromotionDiscount' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'PromotionIds' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PromotionIdList'),
            'CODFee' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'CODFeeDiscount' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Money'),
            'GiftMessageText' => array('FieldValue' => null, 'FieldType' => 'string'),
            'GiftWrapLevel' => array('FieldValue' => null, 'FieldType' => 'string')
        );
        parent::__construct($data);
    }

}
