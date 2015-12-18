<?php

/**
 * Instant Order Processing Notification API: OrderCancelledNotification request model
 *
 * Fields:
 * <ul>
 * <li>NotificationReferenceId: string</li>
 * <li>ProcessedOrder: Pwa_PaywithAmazon_Model_Api_Model_Iopn_Order</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification extends Pwa_PaywithAmazon_Model_Api_Model_Iopn_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'NotificationReferenceId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ProcessedOrder' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Iopn_Order')
        );
        parent::__construct($data);
    }

    /**
     * Construct Pwa_PaywithAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification from XML string
     *
     * @param string $xml XML string to construct from
     * @return Pwa_PaywithAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification
     */
    public static function fromXML($xml) {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('a', self::getConfigData('api_namespace', array('api' => 'iopn')));
        $response = $xpath->query('//a:OrderCancelledNotification');
        if ($response->length == 1) {
            return new Pwa_PaywithAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification($response->item(0));
        } else {
            Mage::helper('paywithamazon')->throwException(
                Mage::helper('paywithamazon')->__('Unable to construct %s from provided XML. Make sure that %s is a root element.', 'Pwa_PaywithAmazon_Model_Api_Model_Iopn_Request_OrderCancelledNotification', 'OrderCancelledNotification'),
                null,
                array('area' => 'Amazon IOPN')    
            );
        }
    }

}
