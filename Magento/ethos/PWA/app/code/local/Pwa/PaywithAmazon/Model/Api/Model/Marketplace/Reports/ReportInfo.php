<?php

/**
 * Amazon Marketplace Reports API: ReportInfo data type model
 *
 * Fields:
 * <ul>
 * <li>ReportId: string</li>
 * <li>ReportType: string</li>
 * <li>ReportRequestId: string</li>
 * <li>AvailableDate: DateTime</li>
 * <li>Acknowledged: bool</li>
 * <li>AcknowledgedDate: DateTime</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_ReportInfo extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ReportId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportRequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'AvailableDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'Acknowledged' => array('FieldValue' => null, 'FieldType' => 'bool'),
            'AcknowledgedDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

}
