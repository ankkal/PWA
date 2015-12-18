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
class Pwa_PaywithAmazon_Model_Lookup_Abstract extends Varien_Object {

    protected $_options = null;

    public function getOptions() {
        $result = array();
        $_options = $this->toOptionArray();
        foreach ($_options as $_option) {
            if (isset($_option['label']) && isset($_option['value']))
                $result[$_option['value']] = $_option['label'];
        }
        return $result;
    }

}
