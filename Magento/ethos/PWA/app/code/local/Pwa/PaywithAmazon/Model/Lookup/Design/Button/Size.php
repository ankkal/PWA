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
class Pwa_PaywithAmazon_Model_Lookup_Design_Button_Size extends Pwa_PaywithAmazon_Model_Lookup_Abstract {

    const SIZE_MEDIUM   = 'medium';
    const SIZE_LARGE    = 'large';
    const SIZE_XLARGE   = 'x-large';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::SIZE_MEDIUM, 'label' => Mage::helper('paywithamazon')->__('Medium')),
                array('value' => self::SIZE_LARGE, 'label' => Mage::helper('paywithamazon')->__('Large')),
                array('value' => self::SIZE_XLARGE, 'label' => Mage::helper('paywithamazon')->__('X-Large'))
            );
        }
        return $this->_options;
    }
}
