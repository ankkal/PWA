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
class Pwa_PaywithAmazon_Model_Log_Abstract extends Mage_Core_Model_Abstract {

    protected
        $_resourceName = 'paywithamazon/log_abstract';

    protected function _construct() {
        $this->_init($this->_resourceName);
    }

    public function cleanLogs($dueDate = null) {
        $this->getResource()->cleanLogs($dueDate);
        return $this;
    }
}