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
class Pwa_PaywithAmazon_Block_Adminhtml_Logger_Notifications_View extends Mage_Adminhtml_Block_Widget_Container {

    protected
        $_model = null;

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_logger_notifications';
        $this->_headerText = $this->__('Notification');
        $this->setTemplate('pwa/paywithamazon/logger/notifications/view.phtml');

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick'   => 'window.location.href=\'' . $this->getUrl('*/*/') . '\'',
            'class'     => 'back',
        ));

        $this->_addButton('content_button', array(
            'label'     => Mage::helper('paywithamazon')->__('Show notification data'),
            'class'     => 'scalable'
        ));

    }

    public function getLogModel() {
        return $this->_model;
    }

    public function setLogModel($model) {
        $this->_model = $model;
        if ($this->getLogModel()->getId()) {
            $this->_headerText = $this->__('%s # %s | %s',
                $this->getNotificationType(),
                $this->getUuid(),
                $this->getCreationTime()
            );
            if ($this->getLogModel()->getNotificationContent()) {
                $this->_updateButton('content_button', 'onclick', 'Modalbox.show($(\'notification_data_code\'), {title: \'' . $this->__('%s #%s', $this->getNotificationType(), $this->getUuid()) . '\', width: \'95%\'})');
            } else {
                $this->_updateButton('content_button', 'disabled', true);
            }
        }
        return $this;
    }

    public function getCreationTime() {
        return $this->formatDate($this->getLogModel()->getCreationTime(), 'medium', true);
    }

    public function getNotificationType() {
        $notificationTypeOptions = Mage::getModel('paywithamazon/lookup_notification_type')->getOptions();
        if (isset($notificationTypeOptions[$this->getLogModel()->getNotificationType()])) return $notificationTypeOptions[$this->getLogModel()->getNotificationType()];
        return $this->getLogModel()->getNotificationType();
    }

    public function getFormattedNotificationContent() {
        if (!$this->getLogModel()->getNotificationContent()) return null;
        return $this->helper('paywithamazon')->prettifyXml($this->getLogModel()->getNotificationContent(), true);
    }

    public function getUuid() {
        return $this->getLogModel()->getUuid();
    }

    public function getProcessingResult() {
        return $this->getLogModel()->getProcessingResult();
    }

    public function getHeaderCssClass() {
        return 'icon-head head-' . strtr($this->_controller, '_', '-');
    }

}
