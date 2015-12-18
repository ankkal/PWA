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
class Pwa_PaywithAmazon_Model_Lookup_Prefix extends Pwa_PaywithAmazon_Model_Lookup_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
            $options = trim(Mage::helper('customer/address')->getConfig('prefix_options'));
            if ($options) {
                $options = explode(';', $options);
                foreach ($options as &$v) {
                    $v = Mage::helper('core')->htmlEscape(trim($v));
                    $this->_options[] = array(
                        'value' => $v,
                        'label' => $v
                    );
                }
            }
            array_unshift($this->_options, array('value' => '', 'label' => '---'));
        }
        return $this->_options;
    }
}
