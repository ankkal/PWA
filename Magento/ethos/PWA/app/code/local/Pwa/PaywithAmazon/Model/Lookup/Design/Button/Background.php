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
class Pwa_PaywithAmazon_Model_Lookup_Design_Button_Background extends Pwa_PaywithAmazon_Model_Lookup_Abstract {

    const BACKGROUND_WHITE  = 'white';
    const BACKGROUND_LIGHT  = 'light';
    const BACKGROUND_DARK   = 'dark';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::BACKGROUND_WHITE, 'label' => Mage::helper('paywithamazon')->__('White')),
                array('value' => self::BACKGROUND_LIGHT, 'label' => Mage::helper('paywithamazon')->__('Light coloured')),
                array('value' => self::BACKGROUND_DARK, 'label' => Mage::helper('paywithamazon')->__('Dark')),
            );
        }
        return $this->_options;
    }

}
