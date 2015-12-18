<?php

/**
 * Amazon Marketplace Reports API: UpdateReportAcknowledgements request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>ReportIdList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_IdList</li>
 * <li>Acknowledged: bool</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Request_UpdateReportAcknowledgements extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ReportIdList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_IdList'),
            'Acknowledged' => array('FieldValue' => null, 'FieldType' => 'bool')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'UpdateReportAcknowledgements';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetReportIdList()) {
            $reportIdList = $this->getReportIdList();
            $reportIdListIndex = 1;
            foreach ($reportIdList->getId() as $id) {
                $params['ReportIdList.Id.' . $reportIdListIndex] = $id;
                $reportIdListIndex++;
            }
        }
        if ($this->issetAcknowledged()) {
            $params['Acknowledged'] = $this->getAcknowledged() ? 'true' : 'false';
        }
        return $params;
    }

}
