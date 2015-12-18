<?php

/**
 * Amazon Marketplace Feeds API: SubmitFeed request model
 *
 * Fields:
 * <ul>
 * <li>Marketplace: string</li>
 * <li>Merchant: string</li>
 * <li>MarketplaceIdList: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_IdList</li>
 * <li>FeedContent: string</li>
 * <li>FeedType: string</li>
 * <li>PurgeAndReplace: bool</li>
 * <li>ContentMd5: string</li>
 * <li>ContentType: Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_ContentType</li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_Request_SubmitFeed extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Merchant' => array('FieldValue' => null, 'FieldType' => 'string'),
            'MarketplaceIdList' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_IdList'),
            'FeedContent' => array('FieldValue' => null, 'FieldType' => 'string'),
            'FeedType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'PurgeAndReplace' => array('FieldValue' => null, 'FieldType' => 'bool'),
            'ContentMd5' => array('FieldValue' => null, 'FieldType' => 'string'),
            'ContentType' => array('FieldValue' => null, 'FieldType' => 'Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_ContentType')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'SubmitFeed';
        if ($this->issetMarketplace()) {
            $params['Marketplace'] = $this->getMarketplace();
        }
        if ($this->issetMerchant()) {
            $params['Merchant'] = $this->getMerchant();
        }
        if ($this->issetMarketplaceIdList()) {
            $marketplaceIdList = $this->getMarketplaceIdList();
            $marketplaceIdListIndex = 1;
            foreach ($marketplaceIdList->getId() as $id) {
                $params['MarketplaceIdList.Id.' . $marketplaceIdListIndex] = $id;
                $marketplaceIdListIndex++;
            }
        }
        if ($this->issetFeedContent()) {
            $params['FeedContent'] = $this->getFeedContent();
        }
        if ($this->issetFeedType()) {
            $params['FeedType'] = $this->getFeedType();
        }
        if ($this->issetPurgeAndReplace()) {
            $params['PurgeAndReplace'] = $this->getPurgeAndReplace() ? 'true' : 'false';
        }
        if ($this->issetContentMd5()) {
            $params['ContentMd5'] = $this->getContentMd5();
        }
        if ($this->issetContentType()) {
            $contentType = $this->getContentType();
            if ($contentType->issetContentType()) {
                $params['ContentType.ContentType'] = $contentType->getContentType();
            }
            $contentTypeIndex = 1;
            foreach ($contentType->getParameters() as $parameters) {
                $params['ContentType.Parameters.' . $contentTypeIndex] = $parameters;
                $contentTypeIndex++;
            }
        }
        return $params;
    }

}
