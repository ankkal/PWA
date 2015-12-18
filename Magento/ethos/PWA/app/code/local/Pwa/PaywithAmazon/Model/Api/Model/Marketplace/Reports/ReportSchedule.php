<?php

/**
 * Amazon Marketplace Reports API: ReportSchedule data type model
 *
 * Fields:
 * <ul>
 * <li>ReportType: string</li>
 * <li>Schedule: string</li>
 * <li>ScheduledDate: DateTime</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_ReportSchedule extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ReportType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Schedule' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ScheduledDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

}
