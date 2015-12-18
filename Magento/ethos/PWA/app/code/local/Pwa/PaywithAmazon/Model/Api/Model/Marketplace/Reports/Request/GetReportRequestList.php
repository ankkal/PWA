<?php

/**
 * Amazon Marketplace Reports API: GetReportRequestList request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>ReportRequestIdList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_IdList</li>
 * <li>ReportTypeList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_TypeList</li>
 * <li>ReportProcessingStatusList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_StatusList</li>
 * <li>MaxCount: string</li>
 * <li>RequestedFromDate: DateTime</li>
 * <li>RequestedToDate: DateTime</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Request_GetReportRequestList extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportRequestIdList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_IdList'),
            'ReportTypeList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_TypeList'),
            'ReportProcessingStatusList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_StatusList'),
            'MaxCount' => array('FieldValue' => null, 'FieldType' => 'string'),
            'RequestedFromDate' => array('FieldValue' => null, 'FieldType' => 'DateTime'),
            'RequestedToDate' => array('FieldValue' => null, 'FieldType' => 'DateTime')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'GetReportRequestList';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetReportRequestIdList()) {
            $reportRequestIdList = $this->getReportRequestIdList();
            $reportRequestIdListIndex = 1;
            foreach ($reportRequestIdList->getId() as $id) {
                $params['ReportRequestIdList.Id.' . $reportRequestIdListIndex] = $id;
                $reportRequestIdListIndex++;
            }
        }
        if ($this->issetReportTypeList()) {
            $reportTypeList = $this->getReportTypeList();
            $reportTypeListIndex = 1;
            foreach ($reportTypeList->getType() as $type) {
                $params['ReportTypeList.Type.' . $reportTypeListIndex] = $type;
                $reportTypeListIndex++;
            }
        }
        if ($this->issetReportProcessingStatusList()) {
            $reportProcessingStatusList = $this->getReportProcessingStatusList();
            $reportProcessingStatusListIndex = 1;
            foreach ($reportProcessingStatusList->getStatus() as $status) {
                $params['ReportProcessingStatusList.Status.' . $reportProcessingStatusListIndex] = $status;
                $reportProcessingStatusListIndex++;
            }
        }
        if ($this->issetMaxCount()) {
            $params['MaxCount'] = $this->getMaxCount();
        }
        if ($this->issetRequestedFromDate()) {
            $params['RequestedFromDate'] = $this->getRequestedFromDate();
        }
        if ($this->issetRequestedToDate()) {
            $params['RequestedToDate'] = $this->getRequestedToDate();
        }
        return $params;
    }

}
