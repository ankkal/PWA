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
abstract class Pwa_PaywithAmazon_Model_Mysql4_Log_Abstract extends Mage_Core_Model_Mysql4_Abstract {

    protected
        $_tableName = 'paywithamazon/log_abstract';

    protected function _construct() {
        $this->_init($this->_tableName, 'log_id');
    }

    /**
     * Perform operations before object save
     *
     * @param Mage_Cms_Model_Block $object
     * @return Pwa_PaywithAmazon_Model_Mysql4_Log_Abstract
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        if (!$object->getId()) {
            $object->setCreationTime(Mage::getSingleton('core/date')->gmtDate());
        }
        return parent::_beforeSave($object);
    }

    public function cleanLogs($dueDate = null) {
        if ($dueDate) {
            $this->_getWriteAdapter()->delete(
                $this->getTable($this->_tableName),
                $this->_getWriteAdapter()->quoteInto('creation_time < ?', $dueDate)
            );
        } else {
            $this->_getWriteAdapter()->delete(
                $this->getTable($this->_tableName)
            );
        }
    }

}
