<?php

/**
 * Amazon Checkout API: PurchaseContract data type model
 *
 * Fields:
 * <ul>
 * <li>Id: IdType</li>
 * <li>ExpirationTimeStamp: string</li>
 * <li>MerchantId: IdType</li>
 * <li>MarketplaceId: IdType</li>
 * <li>State: PurchaseContractState</li>
 * <li>Metadata: byte[]</li>
 * <li>Destinations: Pwa_PaywithAmazon_Model_Api_Model_Checkout_DestinationList</li>
 * <li>PurchaseItems: Pwa_PaywithAmazon_Model_Api_Model_Checkout_ItemList</li>
 * <li>Charges: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Charges</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_PurchaseContract extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    protected function _prepareInput($data = null) {
        if (is_array($data) || is_null($data)) {
            if (!isset($data['MerchantId'])) $data['MerchantId'] = self::getConfigData('merchant_id');
        }
        return $data;
    }

    public function __construct($data = null) {
        $this->_fields = array(
            'Id' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'ExpirationTimeStamp' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MerchantId' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'MarketplaceId' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'State' => array('FieldValue' => null, 'FieldType' => 'PurchaseContractState'),
            'Metadata' => array('FieldValue' => null, 'FieldType' => 'byte[]'),
            'Destinations' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_DestinationList'),
            'PurchaseItems' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_ItemList'),
            'Charges' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Charges')
        );
        parent::__construct($this->_prepareInput($data));
    }

}
