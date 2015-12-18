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
abstract class Pwa_PaywithAmazon_Model_Api_Abstract extends Pwa_PaywithAmazon_Model_Abstract {

    protected $_apiClientClass = null;
    protected $_apiServerClass = null;

    protected $_apiClient = null;
    protected $_apiServer = null;

    protected $_area = 'General';

    public function __construct() {
        $modelSuffix = preg_replace('/^(.+_Model_)(Api)_(.+)$/', '$2_Model_$3', get_class($this));
        $modelSuffix = preg_replace('/(_.?)/e', "strtolower('$1')", $modelSuffix);
        $modelSuffix[0] = strtolower($modelSuffix[0]);
        $this->_modelClassPrefix = $this->_modulePrefix . '/' . $modelSuffix . '_';
    }

    /**
     * Function to create API client object
     */
    protected function _getApiClient() {
        if (null === $this->_apiClient) {
            $apiClientClass = $this->_apiClientClass ? $this->_apiClientClass : str_replace('_Model_Api_', '_Model_Api_Client_', get_class($this));
            if (class_exists($apiClientClass, true)) {
                $this->_apiClient = new $apiClientClass;
            } else {
                Mage::helper('paywithamazon')->throwException('API client class not specified or does not exist', null, array('area' => $this->_area));
            }

        }
        return $this->_apiClient;
    }

    /**
     * Function to create API server object
     */
    protected function _getApiServer() {
        if (null === $this->_apiServer) {
            $apiServerClass = $this->_apiServerClass ? $this->_apiServerClass : str_replace('_Model_Api_', '_Model_Api_Server_', get_class($this));
            if (class_exists($apiServerClass, true)) {
                $this->_apiServer = new $apiServerClass;
            } else {
                Mage::helper('paywithamazon')->throwException('API server class not specified or does not exist', null, array('area' => $this->_area));
            }

        }
        return $this->_apiServer;
    }

}