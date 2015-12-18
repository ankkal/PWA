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
class Pwa_PaywithAmazon_Model_Lookup_Design_Button_Color extends Pwa_PaywithAmazon_Model_Lookup_Abstract {

    const COLOR_ORANGE  = 'orange';
    const COLOR_TAN     = 'tan';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::COLOR_ORANGE, 'label' => Mage::helper('paywithamazon')->__('Orange (recommended)')),
                array('value' => self::COLOR_TAN, 'label' => Mage::helper('paywithamazon')->__('Tan')),
            );
        }
        return $this->_options;
    }

}
