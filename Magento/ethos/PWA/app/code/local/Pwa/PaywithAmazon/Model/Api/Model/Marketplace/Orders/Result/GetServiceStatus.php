<?php

/**
 * Amazon Marketplace Orders API: GetServiceStatus result model
 *
 * Fields:
 * <ul>
 * <li>Status: ServiceStatusEnum</li>
 * <li>Timestamp: string</li>
 * <li>MessageId: string</li>
 * <li>Messages: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_MessageList</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Result_GetServiceStatus extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Status' => array('FieldValue' => null, 'FieldType' => 'ServiceStatusEnum'),
            'Timestamp' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MessageId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Messages' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_MessageList')
        );
        parent::__construct($data);
    }

}
