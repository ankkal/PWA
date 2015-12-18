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
class Pwa_PaywithAmazon_Block_Adminhtml_Logger_Reports_View extends Mage_Adminhtml_Block_Widget_Container {

    protected
        $_model = null;

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_logger_reports';
        $this->_headerText = $this->__('Report request');
        $this->setTemplate('pwa/paywithamazon/logger/reports/view.phtml');

        $this->_addButton('back', array(
            'label'     => Mage::helper('adminhtml')->__('Back'),
            'onclick'   => 'window.location.href=\'' . $this->getUrl('*/*/') . '\'',
            'class'     => 'back',
        ));

        $this->_addButton('content_button', array(
            'label'     => Mage::helper('paywithamazon')->__('Show report content'),
            'class'     => 'scalable'
        ));

    }

    public function getLogModel() {
        return $this->_model;
    }

    public function setLogModel($model) {
        $this->_model = $model;
        if ($this->getLogModel()->getId()) {
            $this->_headerText = $this->__('Report request # %s | %s',
                $this->getReportRequestId(),
                $this->getCreationTime()
            );
            if ($this->getLogModel()->getReportContent()) {
                $this->_updateButton('content_button', 'onclick', 'Modalbox.show($(\'report_content_code\'), {title: \'' . $this->__('Report request # %s: content', $this->getReportRequestId()) . '\', width: \'95%\'})');
            } else {
                $this->_updateButton('content_button', 'disabled', true);
            }
        }
        return $this;
    }

    public function getCreationTime() {
        return $this->formatDate($this->getLogModel()->getCreationTime(), 'medium', true);
    }

    public function getReportType() {
        $reportTypeOptions = Mage::getModel('paywithamazon/lookup_report_type')->getOptions();
        if (isset($reportTypeOptions[$this->getLogModel()->getReportType()])) return $reportTypeOptions[$this->getLogModel()->getReportType()];
        return $this->getLogModel()->getReportType();
    }

    public function getFormattedReportContent() {
        if (!$this->getLogModel()->getReportContent()) return null;
        return $this->helper('paywithamazon')->prettifyXml($this->getLogModel()->getReportContent(), true);
    }

    public function getReportId() {
        return $this->getLogModel()->getReportId();
    }

    public function getReportRequestId() {
        return $this->getLogModel()->getReportRequestId();
    }

    public function getHeaderCssClass() {
        return 'icon-head head-' . strtr($this->_controller, '_', '-');
    }

}
