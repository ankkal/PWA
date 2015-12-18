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
class Pwa_PaywithAmazon_Block_Checkout extends Pwa_PaywithAmazon_Block_Abstract {

    public function getSteps() {
        $steps = array();
        $stepCodes = array('shipping', 'shipping_method', 'payment', 'review');
        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
        return $steps;
    }

    public function getActiveStep() {
        return 'shipping';
    }

    public function getPurchaseContractId() {
        return Mage::getSingleton('checkout/session')->getAmazonPurchaseContractId();
    }
}
