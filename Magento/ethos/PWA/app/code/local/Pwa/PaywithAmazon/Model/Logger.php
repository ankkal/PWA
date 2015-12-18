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
 * @copyright  Pay with Amazon
 * @author     Pay with Amazon
 */
class Pwa_PaywithAmazon_Model_Logger extends Pwa_PaywithAmazon_Model_Abstract {

    protected static function _isEnabled() {
        return self::getConfigData('logger_enabled');
    }

    public static function cleanLogs() {
        $cleanAfter = self::getConfigData('clean_logs_after');
        if ($cleanAfter) {
            $dueDate = Mage::getModel('core/date')->date('Y-m-d H:i:s', '-' . $cleanAfter . ' days');
            Mage::getModel('paywithamazon/log_api')->cleanLogs($dueDate);
            Mage::getModel('paywithamazon/log_exception')->cleanLogs($dueDate);
            Mage::getModel('paywithamazon/log_feed')->cleanLogs($dueDate);
            Mage::getModel('paywithamazon/log_iopn')->cleanLogs($dueDate);
            Mage::getModel('paywithamazon/log_report')->cleanLogs($dueDate);
        }
    }

    public static function logException($message, $errorCode = null, $stackTrace = '', $area = 'General', $requestId = null) {
        if (self::_isEnabled()) {
            $log = Mage::getModel('paywithamazon/log_exception');
            $log->setMessage($message);
            $log->setErrorCode($errorCode);
            $log->setStackTrace($stackTrace);
            $log->setArea($area);
            $log->setRequestId($requestId);
            $log->save();
        }
    }

    public static function logFeed($feedType, $feedSubmissionId, $feedContent = null, $storeId = null) {
        if (self::_isEnabled()) {
            $log = Mage::getModel('paywithamazon/log_feed');
            $log->setFeedType($feedType);
            $log->setSubmissionId($feedSubmissionId);
            $log->setFeedContent($feedContent);
            $log->setProcessingResult(null);
            if ($storeId) $log->setStoreId($storeId);
            $log->save();
        }
    }

    public static function logIopn($notificationType, $uuid, $notificationContent = null, $processingResult = null) {
        if (self::_isEnabled()) {
            $log = Mage::getModel('paywithamazon/log_iopn');
            $log->setNotificationType($notificationType);
            $log->setUuid($uuid);
            $log->setNotificationContent($notificationContent);
            $log->setProcessingResult($processingResult);
            $log->save();
        }
    }

    public static function logReport($reportType, $reportId, $reportRequestId = null, $reportContent = null) {
        if (self::_isEnabled()) {
            $log = Mage::getModel('paywithamazon/log_report');
            $log->setReportType($reportType);
            $log->setReportId($reportId);
            $log->setReportRequestId($reportRequestId);
            $log->setReportContent($reportContent);
            $log->save();
        }
    }

    public static function logApiCall($host, $action, $requestMethod = 'GET', $headers = null, $getData = null, $postData = null, $fileData = null, $responseCode = null, $response = null) {
        if (self::_isEnabled()) {
            $log = Mage::getModel('paywithamazon/log_api');
            $log->setHost($host);
            $log->setAction($action);
            $log->setRequestMethod($requestMethod);
            $log->setHeaders($headers);
            $log->setGetData($getData);
            $log->setPostData($postData);
            $log->setFileData($fileData);
            $log->setResponseCode($responseCode);
            $log->setResponse($response);
            $log->save();
        }
    }

}
