<?php

/**
 * Amazon Marketplace Reports API: GetReportList request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>MaxCount: string</li>
 * <li>ReportTypeList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_TypeList</li>
 * <li>Acknowledged: bool</li>
 * <li>AvailableFromDate: DateTime</li>
 * <li>AvailableToDate: DateTime</li>
 * <li>ReportRequestIdList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_IdList</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Request_GetReportList extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        
        $LAST_REPORT_PROCESSING = date('Y-m-d H:i:s',Mage::getStoreConfig('paywithamazon/api/last_report_processing')); 
        $now = now();
        $dateTimeFrom = new DateTime($LAST_REPORT_PROCESSING, new DateTimeZone('UTC'));
        $timeFrom = $dateTimeFrom->format(DATE_ISO8601);
        $dateTimeTo = new DateTime($now, new DateTimeZone('UTC'));
        $timeTo = $dateTimeTo->format(DATE_ISO8601);  
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MaxCount' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportTypeList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_TypeList'),
            'Acknowledged' => array('FieldValue' => null, 'FieldType' => 'bool'),
            'AvailableFromDate' => array('FieldValue' => $timeFrom , 'FieldType' => 'DateTime'),
            'AvailableToDate' => array('FieldValue' => $timeTo, 'FieldType' => 'DateTime'),
            'ReportRequestIdList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_IdList')
        );
        
        Mage::getModel('core/config')->saveConfig('paywithamazon/api/last_report_processing', strtotime($now));
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetReportList';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetMaxCount()) {
            $params['MaxCount'] = $this->getMaxCount();
        }
        if ($this->issetReportTypeList()) {
            $reportTypeList = $this->getReportTypeList();
            $reportTypeListIndex = 1;
            foreach ($reportTypeList->getType() as $type) {
                $params['ReportTypeList.Type.' . $reportTypeListIndex] = $type;
                $reportTypeListIndex++;
            }
        }
        if ($this->issetAcknowledged()) {
            $params['Acknowledged'] = $this->getAcknowledged() ? 'true' : 'false';
        }
        if ($this->issetAvailableFromDate()) {
            $params['AvailableFromDate'] = $this->getAvailableFromDate();
        }
        if ($this->issetAvailableToDate()) {
            $params['AvailableToDate'] = $this->getAvailableToDate();
        }
        if ($this->issetReportRequestIdList()) {
            $reportRequestIdList = $this->getReportRequestIdList();
            $reportRequestIdListIndex = 1;
            foreach ($reportRequestIdList->getId() as $id) {
                $params['ReportRequestIdList.Id.' . $reportRequestIdListIndex] = $id;
                $reportRequestIdListIndex++;
            }
        }
        return $params;
    }

}
