<?php

/**
 * Amazon Marketplace Feeds API: FeedSubmissionInfo data type model
 *
 * Fields:
 * <ul>
 * <li>FeedSubmissionId: string</li>
 * <li>FeedType: string</li>
 * <li>SubmittedDate: DateTime</li>
 * <li>FeedProcessingStatus: string</li>
 * <li>StartedProcessingDate: DateTime</li>
 * <li>CompletedProcessingDate: DateTime</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_FeedSubmissionInfo extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'FeedSubmissionId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'FeedType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'SubmittedDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'FeedProcessingStatus' => array('FieldValue' => null, 'FieldType' => 'string'),
            'StartedProcessingDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'CompletedProcessingDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

}
