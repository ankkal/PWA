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

class Pwa_PaywithAmazon_Block_Checkout_Payment extends Pwa_PaywithAmazon_Block_Abstract {

    protected function _construct() {
        $this->getCheckout()->setStepData('payment', array(
            'label'     => Mage::helper('checkout')->__('Payment Information'),
            'is_show'   => $this->isShow()
        ));
        parent::_construct();
    }

    public function getWidgetWidth() {
        return Mage::getStoreConfig(self::XML_PATH_PAYMENT_WIDGET_WIDTH);
    }

    public function getWidgetHeight() {
        return Mage::getStoreConfig(self::XML_PATH_PAYMENT_WIDGET_HEIGHT);
    }

}
