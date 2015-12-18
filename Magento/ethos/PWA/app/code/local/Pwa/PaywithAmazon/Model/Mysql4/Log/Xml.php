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
class Pwa_PaywithAmazon_Model_Mysql4_Log_Xml extends Mage_Core_Model_Mysql4_Abstract {

    protected
        $_tableName = 'paywithamazon/log_xml';

    protected function _construct() {
        $this->_init($this->_tableName, 'xml_id');
    }
}
