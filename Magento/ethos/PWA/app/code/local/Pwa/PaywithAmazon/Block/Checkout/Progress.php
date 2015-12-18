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

class Pwa_PaywithAmazon_Block_Checkout_Progress extends Pwa_PaywithAmazon_Block_Abstract {

    public function formatPrice($price) {
        return $this->getQuote()->getStore()->formatPrice($price);
    }

    public function getShippingMethod() {
        return $this->getQuote()->getShippingAddress()->getShippingMethod();
    }

    public function getShippingDescription() {
        return $this->getQuote()->getShippingAddress()->getShippingDescription();
    }

    public function getShippingPriceInclTax() {
        $exclTax = $this->getQuote()->getShippingAddress()->getShippingAmount();
        $taxAmount = $this->getQuote()->getShippingAddress()->getShippingTaxAmount();
        return $this->formatPrice($exclTax + $taxAmount);
    }

    public function getShippingPriceExclTax() {
        return $this->formatPrice($this->getQuote()->getShippingAddress()->getShippingAmount());
    }

    public function getShippingHtml() {
        return $this->getChildHtml('shipping_info');
    }

    public function getPaymentHtml() {
        return $this->getChildHtml('payment_info');
    }

}
