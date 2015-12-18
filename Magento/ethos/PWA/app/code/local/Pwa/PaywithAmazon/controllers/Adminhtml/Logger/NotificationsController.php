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
class Pwa_PaywithAmazon_Adminhtml_Logger_NotificationsController extends Mage_Adminhtml_Controller_Action {

    protected function _getModel() {
        return Mage::getModel('paywithamazon/log_iopn');
    }

    protected function _initAction() {
        $this->loadLayout()->_setActiveMenu('creativestyle/paywithamazon/logger/notifications');
        return $this;
    }

    protected function _setBreadcrumbs() {
        return $this;
    }

    protected function _setTitle($title = null) {
        $this->_title(Mage::helper('paywithamazon')->__('Checkout by Amazon'))
            ->_title(Mage::helper('paywithamazon')->__('Instant Order Processing Notifications'));
        if ($title) $this->_title(Mage::helper('paywithamazon')->__($title));
        return $this;
    }

    public function indexAction() {
        $this->_setTitle()->_initAction()->_setBreadcrumbs();
        $this->renderLayout();
    }

    public function viewAction() {
        $id = $this->getRequest()->getParam('id');
        $logModel = $this->_getModel()->load($id);

        if ($logModel->getId()) {
            $this->_setTitle('View notification content')->_initAction()->_setBreadcrumbs();
            $this->_addContent($this->getLayout()->createBlock('paywithamazon/adminhtml_logger_notifications_view')->setLogModel($logModel));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('paywithamazon')->__('Log does not exist'));
            $this->_redirect('*/*/');
        }
    }

}