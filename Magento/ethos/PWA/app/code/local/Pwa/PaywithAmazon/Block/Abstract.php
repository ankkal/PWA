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
abstract class Pwa_PaywithAmazon_Block_Abstract extends Mage_Checkout_Block_Onepage_Abstract {

    const XML_PATH_REUSE_AMAZON_SESSION     = 'paywithamazon/general/reuse_amazon_session';

    const XML_PATH_BUTTON_SIZE              = 'paywithamazon/design/button_size';
    const XML_PATH_BUTTON_COLOR             = 'paywithamazon/design/button_color';
    const XML_PATH_BUTTON_BACKGROUND        = 'paywithamazon/design/button_background';
    const XML_PATH_ADDRESS_WIDGET_WIDTH     = 'paywithamazon/design/address_width';
    const XML_PATH_ADDRESS_WIDGET_HEIGHT    = 'paywithamazon/design/address_height';
    const XML_PATH_PAYMENT_WIDGET_WIDTH     = 'paywithamazon/design/payment_width';
    const XML_PATH_PAYMENT_WIDGET_HEIGHT    = 'paywithamazon/design/payment_height';
    const XML_PATH_PROGRESS_WIDGET_WIDTH    = 'paywithamazon/design/progress_width';
    const XML_PATH_PROGRESS_WIDGET_HEIGHT   = 'paywithamazon/design/progress_height';
    const XML_PATH_REVIEW_WIDGET_WIDTH      = 'paywithamazon/design/review_width';
    const XML_PATH_REVIEW_WIDGET_HEIGHT     = 'paywithamazon/design/review_height';
    // GLOBAL EDD Config
    const XML_PATH_GLOBAL_EDD_CATEGORY      = 'paywithamazon/global_edd/gl_category';
    const XML_PATH_GLOBAL_EDD_HAZMAT        = 'paywithamazon/global_edd/hazmat';
    const XML_PATH_GLOBAL_HANDLING_TIME_MIN = 'paywithamazon/global_edd/handling_time_min';
    const XML_PATH_GLOBAL_HANDLING_TIME_MAX = 'paywithamazon/global_edd/handling_time_max';

    protected function _isActive() {
        return Mage::helper('paywithamazon')->getConfigData('active') && $this->_isIpAllowed();
    }

    protected function _getMode() {
        return Mage::helper('paywithamazon')->getConfigData('mode');
    }

    protected function _getMarketplace() {
        return Mage::helper('paywithamazon')->getConfigData('marketplace');
    }

    protected function _isIpAllowed() {
        $allowedIps = Mage::helper('paywithamazon')->getConfigData('allowed_ips');
        if (is_array($allowedIps)) {
            if (!(isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $allowedIps))) return false;
        }
        return true;
    }

    public function getMerchantId() {
        return Mage::helper('paywithamazon')->getConfigData('merchant_id');
    }

    /**
     * @deprecated Deprecated since version 1.6.0
     */
    public function getPaymentWidgetJsUrl() {
        switch ($this->_getMarketplace()) {
            case 'de_DE':
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
                }

            case 'en_GB':
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
                }

            default:
                switch ($this->_getMode()) {
                    case 'live':
                        return 'https://static-na.payments-amazon.com/cba/js/us/PaymentWidgets.js';

                    case 'sandbox':
                        return 'https://static-na.payments-amazon.com/cba/js/us/sandbox/PaymentWidgets.js';
                }

        }
    }

    public function getPurchaseContractId() {
        if (Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId()) return Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId();
        return false;
    }

    /**
     * @deprecated Deprecated since version 1.6.0
     */
    public function isOverlayEnabled() {
        return false;
    }

    /**
     * @deprecated Deprecated since version 1.6.0
     */
    public function getOverlayWidth() {
        return 0;
    }

}
