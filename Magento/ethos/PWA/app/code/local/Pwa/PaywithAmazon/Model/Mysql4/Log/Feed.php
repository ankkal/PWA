<?php

/**
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
class Pwa_PaywithAmazon_Model_Mysql4_Log_Feed extends Pwa_PaywithAmazon_Model_Mysql4_Log_Abstract {

    protected
        $_api = array(),
        $_tableName = 'paywithamazon/log_feed';

    protected function _getApi() {
        $storeId = Mage::app()->getStore()->getId();
        if (!isset($this->_api[$storeId])) {
            $this->_api[$storeId] = Mage::getModel('paywithamazon/api_marketplace_feeds');
        }
        return $this->_api[$storeId];
    }

    protected function _afterLoad(Mage_Core_Model_Abstract $object) {
        parent::_afterLoad($object);
        $objectChanged = false;

        $currentStore = Mage::app()->getStore();
        Mage::app()->setCurrentStore($object->getStoreId());

        if (($object->getProcessingStatus() != '_DONE_') && $object->getSubmissionId()) {
            try {
                $feedSubmissionList = $this->_getApi()->getFeedSubmissionList(array($object->getSubmissionId()));
            } catch (Exception $e) {}

            if ($feedSubmissionList && $feedSubmissionList->issetFeedSubmissionInfo()) {
                $feedSubmissionInfo = $feedSubmissionList->getFeedSubmissionInfo();
                if (is_array($feedSubmissionInfo)) {
                    foreach ($feedSubmissionInfo as $_feed) {
                        if ($_feed->issetFeedSubmissionId() && $_feed->issetFeedProcessingStatus() &&
                                ($_feed->getFeedSubmissionId() == $object->getSubmissionId()) &&
                                ($_feed->getFeedProcessingStatus() != $object->getProcessingStatus())) {
                            $object->setProcessingStatus($_feed->getFeedProcessingStatus());
                            $objectChanged = true;
                        }
                    }
                }
            }
        }

        if ($object->getProcessingStatus() == '_DONE_' && is_null($object->getProcessingResult())) {
            try {
                $feedSubmissionResult = $this->_getApi()->getFeedSubmissionResult($object->getSubmissionId());
                $object->setProcessingResult($feedSubmissionResult);
                $objectChanged = true;
            } catch (Exception $e) {
                // do not handle this exception, no result yet
            }
        }

        if ($objectChanged) $object->save();

        Mage::app()->setCurrentStore($currentStore);

        return $this;
    }

}
