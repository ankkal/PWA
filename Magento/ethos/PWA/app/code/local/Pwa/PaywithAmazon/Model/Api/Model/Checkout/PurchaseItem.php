<?php

/**
 * Amazon Checkout API: PurchaseItem data type model
 *
 * Fields:
 * <ul>
 * <li>MerchantItemId: IdType</li>
 * <li>SKU: string</li>
 * <li>MerchantId: IdType</li>
 * <li>Title: string</li>
 * <li>Description: string</li>
 * <li>UnitPrice: Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price</li>
 * <li>Quantity: PositiveInteger</li>
 * <li>URL: string</li>
 * <li>Category: string</li>
 * <li>FulfillmentNetwork: FulfillmentNetwork</li>
 * <li>ItemCustomData: string</li>
 * <li>ProductType: ProductType</li>
 * <li>PhysicalProductAttributes: Pwa_PaywithAmazon_Model_Api_Model_Checkout_PhysicalProductAttributes</li>
 * <li>DigitalProductAttributes: Pwa_PaywithAmazon_Model_Api_Model_Checkout_DigitalProductAttributes</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Checkout_PurchaseItem extends Pwa_PaywithAmazon_Model_Api_Model_Checkout_Abstract {

    protected function _prepareInput($data = null) {
        if (is_array($data) || is_null($data)) {
            if (!isset($data['MerchantId'])) $data['MerchantId'] = self::getConfigData('merchant_id');
        }
        return $data;
    }

    public function __construct($data = null) {
        $this->_fields = array(
            'MerchantItemId' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'SKU' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MerchantId' => array('FieldValue' => null, 'FieldType' => 'IdType'),
            'Title' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Description' => array('FieldValue' => null, 'FieldType' => 'string'),
            'UnitPrice' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_Price'),
            'Quantity' => array('FieldValue' => null, 'FieldType' => 'PositiveInteger'),
            'URL' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Category' => array('FieldValue' => null, 'FieldType' => 'string'),
            'FulfillmentNetwork' => array('FieldValue' => null, 'FieldType' => 'FulfillmentNetwork'),
            'ItemCustomData' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ProductType' => array('FieldValue' => null, 'FieldType' => 'ProductType'),
            'PhysicalProductAttributes' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_PhysicalProductAttributes'),
            'DigitalProductAttributes' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Checkout_DigitalProductAttributes')
        );
        parent::__construct($this->_prepareInput($data));
    }

    public function setItemTax($tax) {
        if ($this->issetPhysicalProductAttributes()) {
            if ($this->getPhysicalProductAttributes()->issetItemCharges()) {
                $this->getPhysicalProductAttributes()->getItemCharges()->setTax($tax);
            } else {
                $chargesObject = $this->_getApiModel('charges');
                $chargesObject->setTax($tax);
                $this->getPhysicalProductAttributes()->setItemCharges($chargesObject);
            }
        } else {
            $physicalAttribsObj = $this->_getApiModel('physicalProductAttributes');
            $this->setPhysicalProductAttributes($physicalAttribsObj);
            $chargesObject = $this->_getApiModel('charges');
            $chargesObject->setTax($tax);
            $this->getPhysicalProductAttributes()->setItemCharges($chargesObject);
        }
        return $this;
    }

    public function setItemShipping($shipping) {
        if ($this->issetPhysicalProductAttributes()) {
            if ($this->getPhysicalProductAttributes()->issetItemCharges()) {
                $this->getPhysicalProductAttributes()->getItemCharges()->setShipping($shipping);
            } else {
                $chargesObject = $this->_getApiModel('charges');
                $chargesObject->setShipping($shipping);
                $this->getPhysicalProductAttributes()->setItemCharges($chargesObject);
            }
        } else {
            $physicalAttribsObj = $this->_getApiModel('physicalProductAttributes');
            $this->setPhysicalProductAttributes($physicalAttribsObj);
            $chargesObject = $this->_getApiModel('charges');
            $chargesObject->setShipping($shipping);
            $this->getPhysicalProductAttributes()->setItemCharges($chargesObject);
        }
        return $this;
    }

}
