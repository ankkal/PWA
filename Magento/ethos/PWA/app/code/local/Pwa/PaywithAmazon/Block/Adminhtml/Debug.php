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
class Pwa_PaywithAmazon_Block_Adminhtml_Debug extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('pwa/paywithamazon/debug.phtml');
    }

    protected function _prepareLayout() {
        
        $accordion = $this->getLayout()->createBlock('adminhtml/widget_accordion')->setId('amazonPaymentsDebug');
        
        $accordion->addItem('general', array(
            'title'     => Mage::helper('paywithamazon')->__('General info'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section')->setArea('general')->toHtml()
        ));

        $accordion->addItem('stores', array(
            'title'     => Mage::helper('paywithamazon')->__('Stores'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section_table')->setArea('stores')->toHtml()
        ));

        $accordion->addItem('amazon_general_settings', array(
            'title'     => Mage::helper('paywithamazon')->__('Amazon general settings'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section_table')->setArea('amazon_general_settings')->toHtml()
        ));

        $accordion->addItem('amazon_mws_settings', array(
            'title'     => Mage::helper('paywithamazon')->__('Amazon MWS settings'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section_table')->setArea('amazon_mws_settings')->toHtml()
        ));

        $accordion->addItem('amazon_appearance_settings', array(
            'title'     => Mage::helper('paywithamazon')->__('Amazon appearance settings'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section_table')->setArea('amazon_appearance_settings')->toHtml()
        ));

        $accordion->addItem('cronjobs', array(
            'title'     => Mage::helper('paywithamazon')->__('Amazon cronjobs'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section_table')->setArea('cronjobs')->setShowKeys(false)->toHtml()
        ));

        $accordion->addItem('cron_failures', array(
            'title'     => Mage::helper('paywithamazon')->__('Cronjob errors'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section')->setArea('cron_failures')->toHtml()
        ));

        $accordion->addItem('event_observers', array(
            'title'     => Mage::helper('paywithamazon')->__('Event observers'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section')->setArea('event_observers')->toHtml()
        ));

        $accordion->addItem('magento_general_settings', array(
            'title'     => Mage::helper('paywithamazon')->__('Magento settings'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section_table')->setArea('magento_general_settings')->toHtml()
        ));

        $accordion->addItem('magento_extensions', array(
            'title'     => Mage::helper('paywithamazon')->__('Installed Magento extensions'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section')->setArea('magento_extensions')->toHtml()
        ));

        $accordion->addItem('php_modules', array(
            'title'     => Mage::helper('paywithamazon')->__('Installed PHP modules'),
            'content'   => $this->getLayout()->createBlock('paywithamazon/adminhtml_debug_section')->setArea('php_modules')->toHtml()
        ));

        $this->setChild('debug_info', $accordion);

        return parent::_prepareLayout();
    }

}
