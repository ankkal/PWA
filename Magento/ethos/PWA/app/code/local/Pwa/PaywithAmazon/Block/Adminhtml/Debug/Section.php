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
class Pwa_PaywithAmazon_Block_Adminhtml_Debug_Section extends Mage_Adminhtml_Block_Template {

    protected
        $_id = null,
        $_area = 'general',
        $_showKeys = true;

    public function __construct() {
        parent::__construct();
        $this->setTemplate('pwa/paywithamazon/debug/section.phtml');
    }

    public function getDebugInfo() {
        return Mage::helper('paywithamazon/debug')->getDebugInfo($this->_area);
    }

    public function getSectionId() {
        if (null === $this->_id) {
            $this->_id = 'section-' . uniqid();
        }
        return $this->_id;
    }

    public function setArea($area) {
        $this->_area = $area;
        return $this;
    }

    public function setShowKeys($show) {
        $this->_showKeys = $show;
        return $this;
    }

    public function getShowKeys() {
        return $this->_showKeys;
    }

}
