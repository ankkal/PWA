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
class Pwa_PaywithAmazon_Model_Log_Xml extends Pwa_PaywithAmazon_Model_Log_Abstract {

    protected $_resourceName = 'paywithamazon/log_xml';
	
	protected $_dataSaveAllowed = true;
	
	protected static $_cartXmlID = null;
	
	public function addXml($xmlData, $quoteId)
	{
		$data = array('quote_id'=>$quoteId, 'xml_data' => $xmlData);
		$model = Mage::getModel('paywithamazon/log_xml')->setData($data);
		try{
			$model->save();
		}
		catch(Exception $e){
			Mage::log($e->getMessage(), 3, 'pwa_error.log', true);
		}
		return self::$_cartXmlID;
	}
	
	protected function _beforeSave()
	{
		parent::_beforeSave();
		$model = $this->getCollection()->addFieldToFilter('quote_id',$this->getQuoteId())->addFieldToFilter('xml_data',$this->getXmlData());
		if($model->getSize()){
			$this->_dataSaveAllowed = false;
			self::$_cartXmlID = $model->getFirstItem()->getId();
		}
		return $this;
	}
	
	protected function _afterSave()
	{
		parent::_afterSave();
		self::$_cartXmlID = $this->getId();
		return $this;
	}
}
