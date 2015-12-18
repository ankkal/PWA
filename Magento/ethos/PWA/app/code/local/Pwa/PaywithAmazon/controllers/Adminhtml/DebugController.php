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
class Pwa_PaywithAmazon_Adminhtml_DebugController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu('pwa/paywithamazon/debug');
        return $this;
    }

    protected function _setBreadcrumbs() {
        return $this;
    }

    protected function _setTitle($title = null) {
        $this->_title(Mage::helper('paywithamazon')->__('Checkout by Amazon'))
            ->_title(Mage::helper('paywithamazon')->__('Extension Info'));
        if ($title) $this->_title(Mage::helper('paywithamazon')->__($title));
        return $this;
    }

    public function indexAction() {
        $this->_setTitle()->_initAction()->_setBreadcrumbs();
        $this->renderLayout();
    }

    public function downloadAction() {
        $debugInfo = Mage::helper('paywithamazon/debug')->getDebugInfo();
        $filename = str_replace(array('.', '/', '\\'), array('_'), parse_url(Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL), PHP_URL_HOST)) .
            '_amazon_debug_info_' . Mage::getModel('core/date')->gmtTimestamp() . '.log';
        $debugInfo = base64_encode(serialize($debugInfo));
        Mage::app()->getResponse()->setHeader('Content-type', 'application/base64');
        Mage::app()->getResponse()->setHeader('Content-disposition', 'attachment;filename=' . $filename);
        Mage::app()->getResponse()->setBody($debugInfo);
    }

}