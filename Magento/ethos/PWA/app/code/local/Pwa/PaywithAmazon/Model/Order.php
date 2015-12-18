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
class Pwa_PaywithAmazon_Model_Order extends Mage_Core_Model_Abstract 
{
	
	protected $_amazonResponce = array();
	protected $_shippingMethod = 'pwa_shipping_large';
	protected $_returnUrlCart = null;
	protected $_helper = null;
	const DUMMY_EMAIL = 'amazonuser@amazon.com';
	const DUMMY_PRODUCT = 'Amazon Dummy Product';
	
	public function __construct()
	{
		$this->_helper = Mage::helper('paywithamazon');
	} 	
	public function updateOrderItemCalculation($item,$orderItem,$product=null,$itemPrice,$itemPrincipalPromo,$itemMainPrice){
		
		if($product){
	        $item['product_id'] =  $product->getID();
	        $item['product_type'] =  $product->getTypeId(); 
	        $item['weight'] =  $product->getWeight();
	        $item['is_virtual'] =  $product->getIsVirtual();
	        $item['sku'] =  $product->getSku();
	        $item['name'] =  $product->getName();
	        $item['description'] =  $product->getDescription(); 
    	}
        $item['qty_ordered'] =  $orderItem['Quantity'];
        $item['price'] =  $itemPrice;
        $item['base_price'] =  $itemPrice;
        $item['original_price'] =  $itemPrice;
        $item['base_original_price'] =  $itemPrice;

        $item['base_price_incl_tax'] =  $itemPrice;
        $item['price_incl_tax'] =  $itemPrice;

        $item['discount_amount'] =   $itemPrincipalPromo;
        $item['base_discount_amount'] =   $itemPrincipalPromo;          
        $item['row_total'] =   $itemMainPrice;
        $item['base_row_total_incl_tax'] =   $itemMainPrice;
        $item['row_total_incl_tax'] =   $itemMainPrice;
        //$item['base_row_total'] =   ($item['qty_ordered'] * $itemPrice ) - $item['base_discount_amount'];
        $item['base_row_total'] =   ( $itemMainPrice) ;
        //if(isset($item['item_id'])){
	        try {
	        	Mage::getModel('sales/order_item')->setData($item)->save();	
	        } catch (Exception $e) {
	        	
	        }
	    unset($item);
    	//}
        

    }
	public function createDummyProduct($storeId){
		if(!$this->_amazonProductId()):
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

		$product = Mage::getModel('catalog/product');
			try{
			$product
				->setWebsiteIds(array(1))
				->setAttributeSetId(Mage::getModel('catalog/product')->getDefaultAttributeSetId())
				->setTypeId('simple')
				->setSku(self::DUMMY_PRODUCT)
				->setName(self::DUMMY_PRODUCT)
				->setWeight(1)
				->setStatus(1)
				->setTaxClassId(0)
				->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
				->setPrice(1)
				->setMsrpEnabled(1)
				->setDescription(self::DUMMY_PRODUCT)
				->setShortDescription(self::DUMMY_PRODUCT)
			    ->setEasyAwsGil('Books')
	            ->setEasyAwsHazmat(0)
			    ->setDescription(self::DUMMY_PRODUCT)
			    ->setShortDescription(self::DUMMY_PRODUCT)
			    ->setStockData(array(
									'use_config_manage_stock' => 0, 
									'manage_stock' => 0, 
									'is_in_stock' => 1, 
			                   )
			    );
				$product->save();
			}catch(Exception $e){
				Mage::log($e->getMessage());
			}
		endif;
	}
	public function _amazonProductId(){
		return Mage::getModel('catalog/product')->getIdBySku(self::DUMMY_PRODUCT);
	}
	public function createNewJungleeOrder($amazonOrderId = null){
		$adapter = Mage::getSingleton('core/resource')->getConnection('core_read'); 
		$storeId = Mage::app()->getStore()->getStoreId(); //set default store id 
		$quote = Mage::getModel('sales/quote')->setStoreId($storeId); 
			
		// Add Dummy Address 
		if($quote->getCustomerEmail() == NULL){
			$quote->setCustomerId(null)
				->setCustomerEmail(self::DUMMY_EMAIL)
				->setCustomerIsGuest(true)
				->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
		}		
		// add product(s) dummy product 
		$this->createDummyProduct($storeId);
		$productId = $this->_amazonProductId();
		$product = Mage::getModel('catalog/product')->load($productId);
		// Set product quantity
 		$buyInfo = array(
			'qty' => 1,			
		);
		$quote->addProduct($product, new Varien_Object($buyInfo));		 

		$addressData = $this->_getAddressData(true);		
		$billingAddress = $quote->getBillingAddress()->addData($addressData);		
		$shippingAddress = $quote->getShippingAddress()->addData($addressData); 
		$shippingAddress->setShippingMethod($this->_shippingMethod)
							 ->setCollectShippingRates(true)
							 ->collectShippingRates()
							 ->collectTotals();
		$shippingAddress->setPaymentMethod($this->_getPaymentMethod());
		//Set Payment method
		$quote->getPayment()->importData( array('method' => $this->_getPaymentMethod()));
		$quote->setInventoryProcessed(true);
		try{

			$quote->save();
			//Convert Quote to Order
			$amazonOrderId = ($amazonOrderId) ? $amazonOrderId : Mage::app()->getRequest()->getParam('amznPmtsOrderIds');
			$service = Mage::getModel('sales/service_quote', $quote);
			$service->submitAll();
			$order = $service->getOrder();  
			$order->setEmailSent(0);
			$order->getPayment()->setLastTransId($amazonOrderId)->save(); 
			$quote->setIsActive(false)->save();
			//Update Grand Total in Order Grid table
			Mage::getResourceModel('sales/order')->updateGridRecords($order->getId());
			if($response != ''){
				$this->updateOrderIOPN($order->getId(), $response);
			}
			$this->setSuccessData(null, $order);			
			$adapter->commit();
			if(Mage::app()->getRequest()->getParam('amznPmtsOrderIds') === null){
				return  $order->getId();
			}
		}
		catch(Exception $e){
			$adapter->rollback();
			Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
			Mage::log('Exception String in Model Return order : '. $e->__toString(), null, 'IOPN-Exception.log', true);
		}
		return $this;
		
	}

	public function updateJungleeOrderIopn($orderId , $response = null, $amazonOrderId = null){
		 
		$adapter = Mage::getSingleton('core/resource')->getConnection('core_read');
		$order = Mage::getModel('sales/order')->load($orderId);
		
		$addressData = $this->_getAddressData(false, $response['ProcessedOrder']['ShippingAddress'], $response['ProcessedOrder']['BuyerInfo'], true,$response);
		//Assign to Customer/Guest
		$this->_assignCustomer($order, $addressData);
		//Update Billing and Shipping Address
		$this->_updateAddress($order, $addressData);
		$order->save(); 

		/* new item calculation */
		$subTotal = 0.000; 
		$rowTotal = 0.000; 
		$rowdiscount_amount = 0.000; 
		$item = $order->getItemsCollection()->getFirstItem()->getData();
		
		
		if($item['product_id'] == $this->_amazonProductId()){	
			unset($item['item_id']);
			$order->getItemsCollection()->getFirstItem()->delete();
			if(array_key_exists(0, $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']))
				$responceOrderItems = $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'];
			else
				$responceOrderItems = array($response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']);

			foreach ($responceOrderItems as $orderItem) {
				$itemPrice =  0.0000;
				$itemPrincipalPromo  =  0.0000;
				$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $orderItem['SKU']);
				
				$itemPrice = $orderItem['Price']['Amount'];
				foreach($orderItem['ItemCharges']['Component'] as $itemComponent) {
					if($itemComponent['Type'] == 'PrincipalPromo'){
						$itemPrincipalPromo = $itemComponent['Charge']['Amount'];
					}
					if($itemComponent['Type'] == 'Principal'){
						$itemMainPrice = $itemComponent['Charge']['Amount'];
					} 
				}
				$this->updateOrderItemCalculation($item,$orderItem,$product,$itemPrice,$itemPrincipalPromo,$itemMainPrice);				
			} 	 
			
			$order = Mage::getModel('sales/order')->load($orderId); 
	        foreach ($order->getItemsCollection() as $orderItem) {
	            $rowTotal += $orderItem->getRowTotal();
	            $subTotal += $orderItem->getBaseRowTotal(); 
	            $rowdiscount_amount +=  $orderItem->getBaseDiscountAmount();
	        } 

	        
			$amazonShippingAmount = $this->_getShippingAmouunt($response['ProcessedOrder']['ProcessedOrderItems']);
			$shippingLabel = $response['ProcessedOrder']['DisplayableShippingLabel'];
			$orderGrandTotal = (($rowTotal - $rowdiscount_amount) - $order->getShippingAmount()) + $amazonShippingAmount;
			$shippingMethod = ($shippingLabel == 'Standard') ? 'pwa_shipping_large' : 'pwa_shippig_expedited';
			$shippingDesscription = ($shippingLabel == 'Expedited') ? 'Pay with Amazon Shipping Carrier - Expedited delivery': 'Pay with Amazon Shipping Carrier - Standard delivery';
			$order->setShippingMethod($shippingMethod);
			$order->setShippingDescription($shippingDesscription);

			$order->setBaseShippingAmount($amazonShippingAmount)
			->setShippingAmount($amazonShippingAmount)
			->setSubtotal($rowTotal)
			->setBaseSubtotal($rowTotal)
			->setDiscountAmount($rowdiscount_amount * -1)
			->setBaseGrandTotal($orderGrandTotal)
			->setGrandTotal($orderGrandTotal)
			->setTotalDue($orderGrandTotal)
			->setshippingInclTax($amazonShippingAmount)->save();
			 
			}
			
			$notificationtype =  Mage::app()->getRequest()->getParam('NotificationType');//'OrderReadyToShipNotification';//
			$readyToShiipStatusCode = Mage::getStoreConfig('paywithamazon/general/ship_order_status', true);
			$CancelStatusCode = Mage::getStoreConfig('paywithamazon/general/cancel_order_status', true); 

	 		
			if($notificationtype == "OrderReadyToShipNotification" && $order->getState() == Mage_Sales_Model_Order::STATE_NEW){
				 
				$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
				
				$order->setStatus($readyToShiipStatusCode, true);
				$order->addStatusToHistory($readyToShiipStatusCode, 'Processing status updated by amazon', false);
				foreach ($order->getItemsCollection() as $orderItem) {					
		           $this->updateJungleeInventory($orderItem->getData());
	        	} 
			}elseif ($notificationtype == "OrderCancelledNotification" && !in_array($order->getState(),  array(Mage_Sales_Model_Order::STATE_CANCELED, Mage_Sales_Model_Order::STATE_COMPLETE, Mage_Sales_Model_Order::STATE_CLOSED))) {
				$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
				$order->setStatus($CancelStatusCode, true);

				$order->addStatusToHistory($CancelStatusCode, 'Cancel status updated by amazon', false);
				Mage::getResourceModel('sales/order')->updateGridRecords($order->getId());
			}
			//echo "<pre>";print_r($order->getData());die;
			//$order->save();
			try{
				$order->save();
				if(!empty($shippingLabel)){
					$order->getPayment()->setAdditionalInformation($shippingLabel)->save();
				}
				//Update Grand Total in Order Grid table
				Mage::getResourceModel('sales/order')->updateGridRecords($order->getId());
				$adapter->commit();
			}
			catch(Exception $e){
				$adapter->rollback();
				Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
				Mage::log('Exception String in Model Update order : '. $e->__toString(), null, 'IOPN-Exception.log', true);
			}
			//Send New Order Email

			if (!$order->getEmailSent() && $notificationtype == "OrderReadyToShipNotification" && Mage::getStoreConfig('paywithamazon/general/order_confirmation')){
				 
				$order->sendNewOrderEmail();
			}

	}
	public function updateJungleeInventory($item){
		if(is_array($item))
		{
			$productId = $item['product_id'];
			$qty = $item['qty_ordered'];
			$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
	        if ($stockItem->getId() && Mage::helper('catalogInventory')->isQty($stockItem->getTypeId())) {
	            $stockItem->subtractQty($qty);
	            if ($stockItem->getCanBackInStock() && $stockItem->getQty() > $stockItem->getMinQty()) {
	                $stockItem->setIsInStock(true)
	                    ->setStockStatusChangedAutomaticallyFlag(true);
	            }
	            $stockItem->save();
	        }
    	}
        return $this;
	}
	public function createNewOrder($cartId, $storeId = null, $response = '', $amazonOrderId = null)
	{
		$adapter = Mage::getSingleton('core/resource')->getConnection('core_read');
		$cartDetails = Mage::getModel('paywithamazon/log_xml')->load($cartId);
		$storeId = (!$storeId)? Mage::app()->getRequest()->getParam('store_id') : $storeId;
		$quote = Mage::getModel('sales/quote')->setStoreId($storeId)->load($cartDetails->getQuoteId());
		$quoteId = $quote->getId();
		if($quote->getCustomerEmail() == NULL){
			$quote->setCustomerId(null)
				->setCustomerEmail(self::DUMMY_EMAIL)
				->setCustomerIsGuest(true)
				->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
		}
		//$this->_clearOldItemsAndAddNewItems($cartDetails->getXmlData(), $quote, $storeId);
		//Set Billing/Shipping Address
		$addressData = $this->_getAddressData(true);
		$quote->getBillingAddress()->addData($addressData);
		$quoteShippingAddress = $quote->getShippingAddress()->addData($addressData);
		//Set Shipping Method
		$quoteShippingAddress->setShippingMethod($this->_shippingMethod)
							 ->setCollectShippingRates(true)
							 ->collectShippingRates()
							 ->collectTotals();
		$quoteShippingAddress->setPaymentMethod($this->_getPaymentMethod());
		//Set Payment method
		$quote->getPayment()->importData( array('method' => $this->_getPaymentMethod()));
		//Apply Coupon
		$this->_applyCoupon($quote, $cartDetails->getXmlData());
		// Inventory will not update
		$quote->setInventoryProcessed(true);
		try{
			$quote->save();
			//Convert Quote to Order
			$amazonOrderId = ($amazonOrderId) ? $amazonOrderId : Mage::app()->getRequest()->getParam('amznPmtsOrderIds');
			$service = Mage::getModel('sales/service_quote', $quote);
			$service->submitAll();
			$order = $service->getOrder();  
			$order->setEmailSent(0);
			$order->getPayment()->setLastTransId($amazonOrderId)->save();

			$quote->setIsActive(false)->save();
			//Update Grand Total in Order Grid table
			Mage::getResourceModel('sales/order')->updateGridRecords($order->getId());
			if($response != ''){
				$this->updateOrderIOPN($order->getId(), $response);
			}
			$this->setSuccessData(null, $order);
			$adapter->commit();
		}
		catch(Exception $e){
			$adapter->rollback();
			Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
			Mage::log('Exception String in Model Return order : '. $e->__toString(), null, 'IOPN-Exception.log', true);
		}
		return $this;
	}
	
	public function setSuccessData($orderId = null, $order = null)
	{
		if($orderId){
			$order = Mage::getModel('sales/order')->load($orderId);
		}
		Mage::getSingleton('checkout/session')->setLastQuoteId($order->getQuoteId())->setLastSuccessQuoteId($order->getQuoteId())
					->setLastOrderId($order->getId())->setLastRealOrderId($order->getIncrementId());
		return $this;
	}
	
	//Filter order payment with amazon order id
	protected function _getOrderData($amazonOrderID = '')
	{
		return Mage::getModel('sales/order_payment')->getCollection()->addFieldToFilter('last_trans_id', array('eq' => $amazonOrderID))->getFirstItem();
	}
	/*
	* Check the Order is already created with Amazon Order Id
	*/
	public function isNewOrder($amazonOrderID = '')
	{
		Mage::log('Inside isNewOrder Function ', null, 'IOPN-Testing.log', true);
		return $this->_getOrderData($amazonOrderID)->getData();
	}
	// Get Address data from response
	public function _getAddressData($dummyData = false, $addressData = null, $buyerInfo = null, $isIOPN = false,$response = array())
	{
		//$data = $this->_amazonResponce;
		$dummyStreetAddress = 'Please check your Amazon Seller Central Account to find shipment address for confirmed orders. (If you are a customer please check your "Pay with Amazon" account at https://paywithamazon.amazon.in/)';
		$streetAddress = ($dummyData)? $dummyStreetAddress :(($isIOPN)?$addressData['AddressFieldOne'].' '.$addressData['AddressFieldTwo'] : $addressData['AddressLine1'].' '.$addressData['AddressLine2']);
		$emailAddress = ($dummyData)? self::DUMMY_EMAIL :(($isIOPN)? $buyerInfo['BuyerEmailAddress'] : $buyerInfo['BuyerEmail']);
		return array(
				'firstname'	=> ($dummyData) ? 'Amazon' : $response['ProcessedOrder']['ShippingAddress']['Name'],
				'lastname'	=> ($dummyData) ? 'User' : '',
				'street'	=> array('0' => $streetAddress),
				'city' 		=> ($dummyData) ? 'XXXXXXX' : $addressData['City'],
				'country_id'=> ($dummyData) ? $this->_getDefaultCountry() : $addressData['CountryCode'],
				'region' 	=> ($dummyData) ? '501' : $addressData['State'],
				'postcode' 	=> ($dummyData) ? 'XXXXXXX' : $addressData['PostalCode'],
				'telephone'	=> ($dummyData) ? 'XXXXXXX' : $addressData['PhoneNumber'],
				'email_address' => $emailAddress
			);
	}
	// Get Default Country from Configuration
	protected function _getDefaultCountry(){
        return Mage::getStoreConfig('general/country/default'); 
	}
	protected function _getPaymentMethod()
	{
		$mode = Mage::helper('paywithamazon')->getConfigData('mode');
		return ($mode == 'live') ? 'paywithamazon' : 'paywithamazon_sandbox';
	}
	
	protected function _clearOldItemsAndAddNewItems($xmlData, $quote, $storeId)
	{
		$this->_removeQuoteItems($quote);
		$cartXmlObject = simplexml_load_string($this->_helper->getDecodeCart($xmlData), 'SimpleXMLElement', LIBXML_NOCDATA);

		$cart = $cartXmlObject->Cart->Items;
		foreach($cart->children() as $item){
			$productDatas = unserialize((string)$item->ItemCustomData->ProductParams);
			$product = Mage::getModel('catalog/product')->load($productDatas['product']);
			try{
				$quote->addProduct($product, new Varien_Object($productDatas))->save();
			}
			catch(Exception $e){
				Mage::log('Exception in clearOldItemsAndAddnewItems Function : '. $e->__toString(), null, 'IOPN-Testing.log', true);
				Mage::log($e->getMessage(), 3, 'pwa_error.log', true);
			}
		}
		$quote->setTotalsCollectedFlag(false);
		return $quote;
	}
	protected function _removeQuoteItems($quote)
	{
		$items = $quote->getAllItems();
		foreach($items as $item){
			$quote->removeItem($item->getId())->save();
		}
		return $this;
	}
	protected function _applyCoupon($quote, $xmlData)
	{
		$xmlObject = simplexml_load_string($this->_helper->getDecodeCart($xmlData), 'SimpleXMLElement', LIBXML_NOCDATA);
		if(isset($xmlObject->Promotions->Promotion->PromotionId)){
			$couponCode = (string)$xmlObject->Promotions->Promotion->PromotionId;
			$codeLength = strlen($couponCode);
			$isCodeLengthValid = $codeLength && $codeLength <= Mage::helper('paywithamazon')->getCouponCodeMaxLength(); 
			$quote->getShippingAddress()->setCollectShippingRates(true);
			$quote->setCouponCode($isCodeLengthValid ? $couponCode : '')
					->collectTotals();
		}
		return $this;
	
	}
	
	public function cancelOrder($orderId)
	{
		$cancelstatuscode = Mage::getStoreConfig('paywithamazon/general/cancel_order_status', true); 
		$order = Mage::getModel('sales/order')->load($orderId);
		if($order->getState() != Mage_Sales_Model_Order::STATE_CANCELED){
			$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
			$order->setStatus($cancelstatuscode, true);
			$order->addStatusToHistory($cancelstatuscode, 'Canceled by amazon', false);
			try{
				$order->save();
			}
			catch(Exception $e){
				Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
			}
		}
		return $this;
	}
	public function _assignCustomer(&$order, $data)
	{
		if ($order->getCustomerIsGuest()) {
			$customer = Mage::getModel("customer/customer"); 
			$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
			$customer->loadByEmail($data['email_address']); 
			if($customer->getId()){
				$order->setCustomerId($customer->getId())
					->setCustomerGroupId($customer->getGroupId())
					->setCustomerIsGuest(false)
					->setCustomerFirstname($customer->getFirstname())
					->setCustomerLastname($customer->getLastname());
			}
			else{
				$order->setCustomerFirstname($data['firstname'])
					->setCustomerLastname($data['lastname']);
			}
		}
		$order->setCustomerEmail($data['email_address']);
		return $this;
	}
	/*protected function _getShippingAmouunt($data)
	{
		$amazonShippingAmount = 0;
		$ComponentArr = array();
		$ComponentArr = $data['ProcessedOrderItem']['ItemCharges']['Component'];
		foreach($ComponentArr as $key) {
			if($key['Type'] == "Shipping"){
				$amazonShippingAmount = $key['Charge']['Amount'];
			}
		}
		return ($amazonShippingAmount) ? $amazonShippingAmount : 0.00;
	}*/
	protected function _getShippingAmouunt($data)
	{
		$amazonShippingAmount = 0;
		$amazonShippingAmountPro = 0;
		$ComponentArr = array();
		if(array_key_exists(0,$data['ProcessedOrderItem'])){
			$ComponentArr = $data['ProcessedOrderItem'];	
			foreach($ComponentArr as $key) {
				foreach($key['ItemCharges']['Component'] as $key_pp) {
					if($key_pp['Type'] == "Shipping"){
						$amazonShippingAmount += $key_pp['Charge']['Amount'];
					}
					if($key_pp['Type'] == "ShippingPromo"){
						$amazonShippingAmountPro += $key_pp['Charge']['Amount'];
					}
				}
			}
		}
		else{
			$ComponentArr = $data['ProcessedOrderItem']['ItemCharges']['Component'];
				foreach($ComponentArr as $key) {
				if($key['Type'] == "Shipping"){
					$amazonShippingAmount += $key['Charge']['Amount'];
				}
				if($key['Type'] == "ShippingPromo"){
					$amazonShippingAmountPro += $key['Charge']['Amount'];
				}
			}
		}
		$amazonShippingAmount = $amazonShippingAmount - $amazonShippingAmountPro;
		return ($amazonShippingAmount) ? $amazonShippingAmount : 0.00;
		
	}
	public function _updateAddress($order, $addressData)
	{	
		$shippingAddress	= $order->getShippingAddress()->addData($addressData);
		$billingAddress		= $order->getBillingAddress()->addData($addressData);
		try{
			$shippingAddress->implodeStreetAddress()->save();
			$billingAddress->implodeStreetAddress()->save();
		}
		catch(Exception $e){
			Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
			Mage::log('Exception String in update address : '. $e->__toString(), null, 'IOPN-Testing.log', true);
		}
		return $this;
	}
	public function updateOrderIOPN($orderId, $response)
	{	
		
 		$amazonOrderId = (string)$response['ProcessedOrder']['AmazonOrderID'];
 		$transaction_flag = 0;
		$adapter = Mage::getSingleton('core/resource')->getConnection('core_read');
		
		$order = Mage::getModel('sales/order')->load($orderId);

		$addressData = $this->_getAddressData(false, $response['ProcessedOrder']['ShippingAddress'], $response['ProcessedOrder']['BuyerInfo'], true,$response);
		//Assign to Customer/Guest
		
		$this->_assignCustomer($order, $addressData);
		//Update Billing and Shipping Address
		$this->_updateAddress($order, $addressData);
		$notificationtype =  Mage::app()->getRequest()->getParam('NotificationType');
		$readyToShiipStatusCode = Mage::getStoreConfig('paywithamazon/general/ship_order_status', true);
		$CancelStatusCode = Mage::getStoreConfig('paywithamazon/general/cancel_order_status', true); 
		
		if($notificationtype == "OrderReadyToShipNotification" && $order->getState() == Mage_Sales_Model_Order::STATE_NEW){
		
			$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
			$order->setStatus($readyToShiipStatusCode, true);
			$order->addStatusToHistory($readyToShiipStatusCode, 'Processing status updated by amazon', false);
			$transaction_flag = 1;
		}elseif ($notificationtype == "OrderCancelledNotification" && !in_array($order->getState(),  array(Mage_Sales_Model_Order::STATE_CANCELED, Mage_Sales_Model_Order::STATE_COMPLETE, Mage_Sales_Model_Order::STATE_CLOSED))) {
			/*$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
			$order->setStatus($CancelStatusCode, true);*/
			$order->addStatusToHistory($CancelStatusCode, 'Cancel status updated by amazon', false);
			$order->cancel();
			Mage::getResourceModel('sales/order')->updateGridRecords($order->getId());
		}
		$order->save();
		/*custom order update */
		if($notificationtype != 'OrderCancelledNotification'){
		/*custom order update */	
		if(array_key_exists(0, $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']))
				$responceOrderItems = $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'];
			else
				$responceOrderItems = array($response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']);

		$flagDiffSku = 1;
		$checkSameProduct = array();
		foreach ($responceOrderItems as $key => $orderItem) {
			$productId = unserialize($orderItem['ItemCustomData']['ProductParams'])['product'];
			if(in_array($productId, $checkSameProduct)){
				
				$flagDiffSku = 0;
				break;
			}
			else{
				$checkSameProduct[] = $productId;
			}
		} 
		
		foreach ($responceOrderItems as $key => $orderItem) {
			$itemPrincipalPromo = $itemPrice =  $itemMainPrice = 0.0000;
			 	
			if($flagDiffSku){		
				$productId = unserialize($orderItem['ItemCustomData']['ProductParams'])['product'];
				$loadProduct = Mage::getModel('catalog/product')->load($productId); 
				$item = Mage::getModel('sales/order_item')->getCollection()->addAttributeToFilter('order_id',$orderId);
				$item->addAttributeToFilter('product_id',$productId);
				$item = $item->getFirstItem()->getData();	 
				
			}
			else{
				$quote_item_id = explode('_',$orderItem['SKU'])[0];
				$item = Mage::getModel('sales/order_item')->getCollection()->addAttributeToFilter('quote_item_id',$quote_item_id);
				$item = $item->getFirstItem()->getData();
			}
			
			if(!empty($item)){
					$itemPrice = $orderItem['Price']['Amount'];
					foreach($orderItem['ItemCharges']['Component'] as $itemComponent) {
						if($itemComponent['Type'] == 'PrincipalPromo'){
							$itemPrincipalPromo = $itemComponent['Charge']['Amount'];
						}
						if($itemComponent['Type'] == 'Principal'){
							$itemMainPrice = $itemComponent['Charge']['Amount'];
						} 
					} 
				}
			$item = $this->unsetTax($item);
			$this->updateOrderItemCalculation($item,$orderItem,null,$itemPrice,$itemPrincipalPromo,$itemMainPrice) ; 
		} 
	
		 
		
		$order->save();
		$order = Mage::getModel('sales/order')->load($orderId); 
        foreach ($order->getItemsCollection() as $orderItem) {
            $rowTotal += $orderItem->getRowTotal();
            $subTotal += $orderItem->getBaseRowTotal(); 
            $rowdiscount_amount +=  $orderItem->getBaseDiscountAmount();
        }  
        $amazonShippingAmount = $this->_getShippingAmouunt($response['ProcessedOrder']['ProcessedOrderItems']);
		$shippingLabel = $response['ProcessedOrder']['DisplayableShippingLabel'];
		$orderGrandTotal = ($rowTotal - $rowdiscount_amount) + $amazonShippingAmount;
		$shippingMethod = ($shippingLabel == 'Standard') ? 'pwa_shipping_large' : 'pwa_shippig_expedited';
		$shippingDesscription = ($shippingLabel == 'Expedited') ? 'Pay with Amazon Shipping Carrier - Expedited delivery': 'Pay with Amazon Shipping Carrier - Standard delivery';
		$order->setShippingMethod($shippingMethod);
		$order->setShippingDescription($shippingDesscription);

        $order->setBaseShippingAmount($amazonShippingAmount)
		->setShippingAmount($amazonShippingAmount)
		->setSubtotal($rowTotal)
		->setBaseTaxAmount(0.00)
		->setTaxAmount(0.00)
		->setBaseSubtotal($rowTotal)
		->setShippingAmount($amazonShippingAmount)
		->setDiscountAmount($rowdiscount_amount * -1)
		->setBaseGrandTotal($orderGrandTotal)
		->setGrandTotal($orderGrandTotal)
		->setTotalDue($orderGrandTotal)
		->setshippingInclTax($amazonShippingAmount)->save();
		}
		
		/*}*//*custom order update */	
		/*else{
			$amazonShippingAmount = $this->_getShippingAmouunt($response['ProcessedOrder']['ProcessedOrderItems']);
			$shippingLabel = $response['ProcessedOrder']['DisplayableShippingLabel'];
			$orderGrandTotal = ($order->getGrandTotal() - $order->getShippingAmount()) + $amazonShippingAmount;
			$shippingMethod = ($shippingLabel == 'Standard') ? 'pwa_shipping_large' : 'pwa_shippig_expedited';
			$shippingDesscription = ($shippingLabel == 'Expedited') ? 'Pay with Amazon Shipping Carrier - Expedited delivery': 'Pay with Amazon Shipping Carrier - Standard delivery';
			$order->setShippingMethod($shippingMethod);
			$order->setShippingDescription($shippingDesscription);
			
			$order->setBaseShippingAmount($amazonShippingAmount)
			->setShippingAmount($amazonShippingAmount)
			->setBaseGrandTotal($orderGrandTotal)
			->setGrandTotal($orderGrandTotal)
			->setTotalDue($orderGrandTotal)
			->setshippingInclTax($amazonShippingAmount);
		}	*/ 
		
		try{
			$order->save();
			if(!empty($shippingLabel)){
				$order->getPayment()->setAdditionalInformation($shippingLabel)->save();
			}
			//Update Grand Total in Order Grid table
			Mage::getResourceModel('sales/order')->updateGridRecords($order->getId());
			//$adapter->commit();
		}
		catch(Exception $e){
			$adapter->rollback();
			Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
			Mage::log('Exception String in Model Update order : '. $e->__toString(), null, 'IOPN-Exception.log', true);
		}
		
		if($transaction_flag){
			$payment = $order->getPayment();
			$payment->setTransactionId($amazonOrderId)
	            ->setCurrencyCode('INR')
	            ->setPreparedMessage('Amazon payment success')
	            ->setParentTransactionId()
	            ->setShouldCloseParentTransaction(true)
	            ->setIsTransactionClosed(1)
	            ->registerCaptureNotification($orderGrandTotal,true);
	            $order->save();        
        }

		//Send New Order Email

		if ($notificationtype == "OrderReadyToShipNotification" && !$order->getEmailSent()){
			$order->sendNewOrderEmail();
		}
	}
	public function unsetTax($item){ 
		$unsetTax = array('tax_percent','tax_amount','base_tax_amount','tax_invoiced','base_tax_invoiced','base_tax_before_discount','tax_before_discount','hidden_tax_amount','base_hidden_tax_amount','hidden_tax_invoiced','base_hidden_tax_invoiced','hidden_tax_refunded','base_hidden_tax_refunded','tax_canceled','hidden_tax_canceled','tax_refunded','base_tax_refunded','base_weee_tax_applied_amount','base_weee_tax_applied_row_amnt','weee_tax_applied_amount','weee_tax_applied_row_amount','weee_tax_applied','weee_tax_disposition','weee_tax_row_disposition','base_weee_tax_disposition','base_weee_tax_row_disposition');
		foreach ($unsetTax as $key => $value) {
			$item[$value] = 0.00;
		}
		return $item;
	}
	public function updateOrderMWS($orderId, $response)
	{
		$order = Mage::getModel('sales/order')->load($orderId);
		$buyerInfo = array(
						'BuyerName' => $response->getBuyerName(),
						'BuyerEmail' => $response->getBuyerEmail()
						);
		$shippingLabel = (string)$response->getShipmentServiceLevelCategory();
		$addressData = $this->_getAddressData(false, (array)$response->getShippingAddress(), $buyerEmail, false);
		//Assign to Customer/Guest
		$this->_assignCustomer($order, $addressData);
		//Update Billing and Shipping Address
		$this->_updateAddress($order, $addressData);
		$shippingPrice = $this->_getShippingPriceByMWS((string)$response->getAmazonOrderId());
		$amazonShippingAmount = ($shippingPrice) ? $shippingPrice : 0.00;
		
		$orderGrandTotal = ($order->getGrandTotal() - $order->getShippingAmount()) + $amazonShippingAmount;
		
		$order->setBaseShippingAmount($amazonShippingAmount)
			->setShippingAmount($amazonShippingAmount)
			->setBaseGrandTotal($orderGrandTotal)
			->setGrandTotal($orderGrandTotal)
			->setTotalDue($orderGrandTotal)
			->setshippingInclTax($amazonShippingAmount);
		
		if($order->getState() == Mage_Sales_Model_Order::STATE_NEW){			
			$readyToShiipStatusCode = Mage::getStoreConfig('paywithamazon/general/ship_order_status', true);
			$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
			$order->setStatus($readyToShiipStatusCode, true);
			$rder->addStatusToHistory($readyToShiipStatusCode, 'Processing status updated by amazon', false);
		}
		try{
			$order->save();
			if(!empty($shippingLabel)){
				$order->getPayment()->setAdditionalInformation($shippingLabel)->save();
			}
		}
		catch(Exception $e){
			Mage::log($e->getMessage(), 3, 'pwa_error.log', 1);
		}
		
		//Send New Order Email
		if (!$order->getEmailSent() && Mage::getStoreConfig('paywithamazon/general/order_confirmation')){
			$order->sendNewOrderEmail();
		}
	}
	protected function _getShippingPriceByMWS($amazonOrderId)
	{
		$clientFile = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Client.php';
		$listOrder = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Model' . DS . 'ListOrderItemsRequest.php';
		require_once($clientFile);
		require_once($listOrder);
		$serviceUrl = "https://mws.amazonservices.in/Orders/2013-09-01";
		$config = array (
		'ServiceURL' => $serviceUrl,
		'ProxyHost' => null,
		'ProxyPort' => -1,
		'ProxyUsername' => null,
		'ProxyPassword' => null,
		'MaxErrorRetry' => 5,
		);

		$service = new MarketplaceWebServiceOrders_Client(
			AWS_ACCESS_KEY_ID,
			AWS_SECRET_ACCESS_KEY,
			APPLICATION_NAME,
			APPLICATION_VERSION,
			$config);
		$requestItems = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();

		$requestItems->setSellerId(MERCHANT_ID);
		$requestItems->setAmazonOrderId($id);
		$response = $service->ListOrderItems($requestItems);
		$dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->saveXML();
        $xml = simplexml_load_string($dom->saveXML());
		$json = json_encode($xml);
		$result = json_decode($json,TRUE);
		return $result['ListOrderItemsResult']['OrderItems']['OrderItem']['ShippingPrice']['amount'];
	}
}
