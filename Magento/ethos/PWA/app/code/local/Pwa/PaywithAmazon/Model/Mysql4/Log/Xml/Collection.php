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
class Pwa_PaywithAmazon_Model_Mysql4_Log_Xml_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('paywithamazon/log_xml');
    }
	public function lockId($cartId)	{
		$adapter = Mage::getSingleton('core/resource')->getConnection('core_read');
		$table = $this->getTable('paywithamazon/log_xml');
		$select = $adapter->select()
					->from($table)
					->where('xml_id = ?', $cartId)
					->forUpdate(true);
		$adapter->query($select);
		return $this;
	}
}
