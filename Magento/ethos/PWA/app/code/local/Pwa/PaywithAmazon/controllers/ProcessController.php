<?php

/**
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) Pay with Amazon
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from Pwatech
 *
 * @category   Pwa
 * @package    Pwa_PaywithAmazon
 * @copyright  Pay with Amazon
 * @author     Pay with Amazon
 */
class Pwa_PaywithAmazon_ProcessController extends Mage_Core_Controller_Front_Action {
	var $RequestXML; 
	var $SignedOrder = false;
	var $Signature;
	var $UUID;
	var $Timestamp;
	var $NotificationData;
	var $NotificationType;  
	var $AWSAccessKeyId;
	var $AWSSecretAccessKey;
	var $IsAccessKeyListConfigured = false;
	var $AccessKeyToSecretKeyMap;
	
	protected function _getModel()
	{
		return Mage::getModel('paywithamazon/order');
	}
	protected function _getMwscalls()
	{
		return Mage::getModel('pwa_shipping/mwscalls');
	}
	protected function _getManager()
	{
		return Mage::getModel('paywithamazon/manager');
	}
	protected function _getSession()
	{
		return Mage::getSingleton('checkout/session');
	}
	
	public function processamzoneorderAction()
	{	 
		$params = $this->getRequest();
		$redirectUrl = Mage::getUrl('', array('_secure' => true));
		$cartId = $params->getParam('cart_id')?$cartId = $params->getParam('cart_id'):false;
		if($amazonOrderID = $params->getParam('amznPmtsOrderIds')){
			try{
				$adapter = Mage::getSingleton('core/resource')->getConnection('core_read');
				$adapter->beginTransaction();
				$cartXML = Mage::getModel('paywithamazon/log_xml')->getCollection()->lockId($cartId);
				if(!$orderData = $this->_getModel()->isNewOrder($amazonOrderID)){
					if($cartId)
						$this->_getModel()->createNewOrder($cartId);
					else
						 $this->_getModel()->createNewJungleeOrder();

					$redirectUrl = Mage::getUrl('paywithamazon/checkout/success', array('_secure' => true));
				}
				else{
					$this->_getModel()->setSuccessData($orderData['parent_id'], null);
					$redirectUrl = Mage::getUrl('paywithamazon/checkout/success', array('_secure' => true));
				}			
				if($params->getParam('amznPmtsPaymentStatus') != 'APPROVED'){
					$this->_getSession()->addNotice(Mage::helper('paywithamazon')->__('Payment was not successful. Please revise payment <a href="https://paywithamazon.amazon.in/overview"> here</a>'));
				}
			}
			catch(Exception $e){
				Mage::log('Exception String : '. $e->__toString(), null, 'pwa_error.log', true);
				Mage::log('Exception Previous : '. $e->getPrevious(), null, 'pwa_error.log', true);
			}
		}
		$this->_redirectUrl($redirectUrl);
	}
	
	public function processiopnAction()
	{	

		$isIOPNActive= Mage::getStoreConfig('paywithamazon/general/iopn_active');
		if($isIOPNActive)
		{		 
			
			// NewOrderNotification OrderReadyToShipNotification
			$NotificationData = stripslashes($_POST['NotificationData']);
			// NewOrderNotification OrderReadyToShipNotification

			$xml = simplexml_load_string($NotificationData);

			$json = json_encode($xml);
			$response = json_decode($json,TRUE);
			 
			if(array_key_exists(0, $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'])){
			 		$value = $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'];	
			 		$clientRequestId = (string)$value[0]['ClientRequestId'];
			 	}
			 	else{			 		
			 		$clientRequestId = (string)$response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']['ClientRequestId'];
			 	}
			
			$amazonOrderId = (string)$response['ProcessedOrder']['AmazonOrderID'];

			$libIOPN = Mage::getBaseDir('lib') . DS . 'IOPN' . DS . 'source' . DS . 'config.php';
			require_once($libIOPN);
			$cbaiopn = new CBAIOPNProcessor();
			$data= $cbaiopn->ValidateNotificationData();
			if($clientRequestId == null){
				// Read merchant configurations.
				$cbaiopn->Initialize();
				// Authenticates the request. 
				$cbaiopn->AuthenticateRequest();
				//Validate the Notification data 
				$data= $cbaiopn->ValidateNotificationData();
				//Process the request xml. To be extended by merchant.
				$resp = $cbaiopn->ProcessRequestXML();
			}
			
			if($GLOBALS['iopn_validation']){ 
				/*  cartDetails depends on cart item */				
			 	if(array_key_exists(0, $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'])){
			 		$value = $response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem'];	
			 		$cartDetails = unserialize((string)$value[0]['CartCustomData']['cartDetails']);	
			 	}
			 	else{			 		
			 		$cartDetails = unserialize((string)$response['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']['CartCustomData']['cartDetails']);					
			 	}
				try{
						

					$cartXML = Mage::getModel('paywithamazon/log_xml')->getCollection()->lockId($cartDetails['cart_id']);
					// Check the order is already present in magento
					if(!$orderData = Mage::getModel('paywithamazon/order')->isNewOrder($amazonOrderId)):
						 
						$adapter = Mage::getSingleton('core/resource')->getConnection('core_read');
						$adapter->beginTransaction();
						$this->_getModel()->createNewOrder($cartDetails['cart_id'], $cartDetails['store_id'], $response, $amazonOrderId);
					else:	
						
						$this->_getModel()->updateOrderIOPN($orderData['parent_id'], $response);
					endif;
					/* Create Order Acknowledge XML for feeds*/
					$this->_getManager()->createFeedAcknowledgeXML($response['ProcessedOrder']);

					// update ship status 					
					if($xml->getName() == "NewOrderNotification" || $xml->getName() == "OrderReadyToShipNotification"){
						$order=$this->_getManager()->_getOrderByAmazonId($amazonOrderId);
						$this->_getMwscalls()->updateOrderData($order,$response['ProcessedOrder']);
					}
				}
				catch(Exception $e){
					Mage::log('Exception String : '. $e->__toString(), null, 'IOPN-Exception.log', true);
					Mage::log('Exception Previous : '. $e->getPrevious(), null, 'IOPN-Exception.log', true);
				}
				exit;
			}
			elseif($clientRequestId){

				try{
					if($xml->getName() == "OrderReadyToShipNotification"){ 
						$checkOrderSellerResponse = $this->_getMwscalls()->callGetOrderAPIForIopn($amazonOrderId);
						if(is_array($checkOrderSellerResponse) && empty($checkOrderSellerResponse['GetOrderResult']['Orders'])){
							Mage::log('Exception String : Different seller response', null, 'pwa_error.log', true);
							die;
						} 
					}

					// Check the order is already present in magento
					$order = $this->_getManager()->_getOrderByAmazonId($amazonOrderId); 
					if(empty($order))
						$orderId = $this->_getModel()->createNewJungleeOrder($amazonOrderId);
					else
						$orderId = $order->getId();
					
					$this->_getModel()->updateJungleeOrderIopn($orderId , $response); 
					/* Create Order Acknowledge XML for feeds*/
					$this->_getManager()->createFeedAcknowledgeJungleeXML($response['ProcessedOrder']);
					// update ship status 					
					if($xml->getName() == "NewOrderNotification" || $xml->getName() == "OrderReadyToShipNotification"){
						$order=$this->_getManager()->_getOrderByAmazonId($amazonOrderId);
						$this->_getMwscalls()->updateOrderData($order,$response['ProcessedOrder']);
					}
				}
				catch(Exception $e){
					Mage::log('Exception String : '. $e->__toString(), null, 'IOPN-Exception.log', true);
					Mage::log('Exception Previous : '. $e->getPrevious(), null, 'IOPN-Exception.log', true);
				}
				exit;
			}
			else{
				//To provide the response
				die();
			}
		}
		return $this;
	}
	public function processmwsAction()
	{
		require_once('MarketplaceWebServiceOrders/Client.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrderItemsRequest.php');
		require_once('MarketplaceWebServiceOrders/Model/ListOrdersRequest.php');
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
		$request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
		$request->setSellerId(MERCHANT_ID);
		$request->setMarketplaceId(MARKETPLACE_ID);
		$request->setCreatedAfter(date('Y-m-d\TH:i:s\Z',strtotime("-3 days")));
		// object or array of parameters
		try {
			$response = $service->ListOrders($request);
			if(count($orders = $response->getListOrdersResult()->getOrders())){
				foreach($orders as $order){
					$amazonOrderId = (string)$order->getAmazonOrderId();
					if(!$orderData = $this->_getModel()->isNewOrder($amazonOrderID)){
						$orderStatus = $order->getOrderStatus();
						if($orderStatus == 'Canceled'){
							$this->_getModel()->cancelOrder($orderId);
						}
						elseif($orderStatus == 'Unshipped' || $orderStatus == 'PartiallyShipped' || $orderStatus == 'Shipped'|| $orderStatus == 'InvoiceUnconfirmed'){
							$this->_getModel->updateOrderMWS($orderData['parent_id'], $order);
						}
					}
				}
			}

		} 
		catch (MarketplaceWebServiceOrders_Exception $ex) {
			Mage::log($ex->getMessage(), 3, 'pwa-mws-error.log', 1);
			Mage::log($ex->getStatusCode(), 3, 'pwa-mws-error.log', 1);
			Mage::log($ex->getErrorCode(), 3, 'pwa-mws-error.log', 1);
			Mage::log($ex->getErrorType(), 3, 'pwa-mws-error.log', 1);
			Mage::log($ex->getRequestId(), 3, 'pwa-mws-error.log', 1);
			Mage::log($ex->getXML(), 3, 'pwa-mws-error.log', 1);
			Mage::log($ex->getResponseHeaderMetadata(), 3, 'pwa-mws-error.log', 1);
		}
		return $this;
	}

public function processoldiopnAction()
{
 $isactiveiopn= Mage::getStoreConfig('paywithamazon/general/iopn_active');
 if($isactiveiopn)
 {
	$ExternalLibPath='/IOPN/source/config.php';

require_once ($ExternalLibPath);
	$cbaiopn = new CBAIOPNProcessor();
	// Read merchant configurations.
	$cbaiopn->Initialize();
	// Authenticates the request. 
	$cbaiopn->AuthenticateRequest();
	//Validate the Notification data 
	$data= $cbaiopn->ValidateNotificationData();
	//Process the request xml. To be extended by merchant.
	$resp=$cbaiopn->ProcessRequestXML();

	$timestamp=$_POST['Timestamp'];
	$Signature=$_POST['Signature'];
	$AWSAccessKeyId=$_POST['AWSAccessKeyId'];
	$UUID=$_POST['UUID'];
	$TimeStamp_now=time();
		if(!empty($UUID) && !empty($timestamp) && !empty($AWSAccessKeyId)){
		if($_POST['NotificationType']=="OrderReadyToShipNotification" || $_POST['NotificationType']=="OrderCancelledNotification"){
					$NotificationData=stripslashes($_POST['NotificationData']);
			$doc = new DOMDocument();
			$doc->loadXML($NotificationData);
			if($doc->schemaValidate(EVENT_NOTIFICATION_SCHEMA_FILE)){
						$xml = simplexml_load_string( $NotificationData);
				$json = json_encode($xml);
				$array = json_decode($json,TRUE);
				$ComponentArr = array();
				$amazonOrderId=$array['ProcessedOrder']['AmazonOrderID'];
				// Check the order is already present in magento
				if(!$this->_getModel($xml)->isNewOrder($amazonOrderId)):
					Mage::getModel('apywithamazon/order')->createNewOrder($xml);
				else:
					echo "";
				endif;
				$ComponentArr=$array['ProcessedOrder']['ProcessedOrderItems']['ProcessedOrderItem']['ItemCharges']['Component'];
				foreach ($ComponentArr as $key) {
								if($key['Type']=="Shipping"){
											$amazonShippingamount= $key['Charge']['Amount'];
								}
				}
				echo $shippingLabel=$array['ProcessedOrder']['DisplayableShippingLabel'];
				$fullname=$array['ProcessedOrder']['BuyerInfo']['BuyerName'];
				$email_address=$array['ProcessedOrder']['BuyerInfo']['BuyerEmailAddress'];
				$firstname=$fullname;
				$lastname=" ";
				$street=$array['ProcessedOrder']['ShippingAddress']['AddressFieldOne']." ".$array['ProcessedOrder']['ShippingAddress']['AddressFieldTwo'];
				$city=$array['ProcessedOrder']['ShippingAddress']['City'];
				$country_id=$array['ProcessedOrder']['ShippingAddress']['CountryCode'];
				$region=$array['ProcessedOrder']['ShippingAddress']['State'];
				$postcode=$array['ProcessedOrder']['ShippingAddress']['PostalCode'];
				$telephone=$array['ProcessedOrder']['ShippingAddress']['PhoneNumber'];
				$table_prefix = Mage::getConfig()->getTablePrefix();
				$orderCollection =  Mage::getModel('sales/order_payment')->getCollection()->addFieldToFilter('last_trans_id',$amazonOrderId);
				foreach($orderCollection as $order){
								$orderId=$order->getParentId();
					$order = Mage::getModel('sales/order')->load($orderId);
					$orderstatus= $order->getStatus();
						$ordergrandtotal=$order->getGrandTotal();
						$orderShippingAmount=$order->getShippingAmount();
						if($amazonShippingamount > 0 || !empty($amazonShippingamount)){
										$amazonShippingamount=$amazonShippingamount;
								}else{
										$amazonShippingamount=0.00;
						}					
		if($orderstatus=='pay_with_amazon'){
								if($_POST['NotificationType']=="OrderReadyToShipNotification"){
										$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
										$order->save();
								}elseif ($_POST['NotificationType']=="OrderCancelledNotification") {
										$order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
										$order->save();
										$this->cancelorderrevert($order);
								}else{
					
								}
					}
					$ordergrandtotal=$ordergrandtotal-$orderShippingAmount+$amazonShippingamount;
					$order->setCustomerEmail($email_address)
					->setCustomerFirstname($firstname)
					->setCustomerLastname($lastname)
					->setBaseShippingAmount($amazonShippingamount)
					->setShippingAmount($amazonShippingamount)
					->setBaseGrandTotal($ordergrandtotal)
					->setGrandTotal($ordergrandtotal)
					->setTotalDue($ordergrandtotal)
					->setshippingInclTax($amazonShippingamount)
					->save();
					if(!empty($shippingLabel)){
							$order->getPayment()->setAdditionalInformation($shippingLabel)->save();
					}
					$shippingAddress = Mage::getModel('sales/order_address')->load($order->getShippingAddress()->getId());
					$shippingAddress->setFirstname($firstname)->setLastname($lastname)
							->setStreet($street)
							->setCity($city)
							->setCountry_id($country_id)
							->setRegion($region)
							->setPostcode($postcode)
							->setTelephone($telephone)
							->save();
					$billingAddress = Mage::getModel('sales/order_address')->load($order->getBillingAddress()->getId());
					$billingAddress->setFirstname($firstname)
							->setLastname($lastname)
							->setStreet($street)
							->setCity($city)
							->setCountry_id($country_id)
							->setRegion($region)    
							->setPostcode($postcode)
							->setTelephone($telephone)
							->save();
					exit;
 
						}

			}
		}
	} 
}
}
public function cancelorderrevert($order)
{
		
		$curr_date = date('Y-m-d H:i:s');
foreach ($order->getItemsCollection() as $item) 
{ 
		$productId  = $item->getProductId();
		$qty = (int)$item->getQtyOrdered();
		$product = Mage::getModel('catalog/product')->load($productId);
		$stock_obj = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
		$stockData = $stock_obj->getData();
		$product_qty_before = (int)$stock_obj->getQty();
		$product_qty_after = (int)($product_qty_before + $qty); 
		$stockData['qty'] = $product_qty_after;
		/*
		 * it may be case that admin has enabled product add in stock, after product sold,
		 * he set is_in_stock = 0 and if order cancelled then we need to update only qty not is_in_stock status.
		 * make a note of it
		 */
		if($product_qty_after != 0) {
				$stockData['is_in_stock'] = 1;
		}else{
				$stockData['is_in_stock'] = 0;
		}

		$productInfoData = $product->getData();
		$productInfoData['updated_at'] = $curr_date;
		$product->setData($productInfoData);
		$product->setStockData($stockData);
		$product->save();
}

}
public function jungleeorderAction(){
$amazonOrderId=$_REQUEST['amznPmtsOrderIds'];
$serviceUrl = "https://mws.amazonservices.in/Orders/2013-09-01";
$config = array (
				 'ServiceURL' => $serviceUrl,
				 'ProxyHost' => null,
				 'ProxyPort' => -1,
				 'ProxyUsername' => null,
				 'ProxyPassword' => null,
				 'MaxErrorRetry' => 3,
			 );
$service = new MarketplaceWebServiceOrders_Client(
						AWS_ACCESS_KEY_ID,
						AWS_SECRET_ACCESS_KEY,
						APPLICATION_NAME,
						APPLICATION_VERSION,
						$config);
				
$request = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();
$request->setSellerId(MERCHANT_ID);
$request->setAmazonOrderId($amazonOrderId);
Mage::log("AmazonOrderID : ".$amazonOrderId, null, 'amazonid.log',true);
$sku=$this->invokeListOrderItems($service, $request); 
if(empty($sku)){
 	$sku=$this->invokeListOrderItems($service, $request); 
}else{
	$validateorder =  Mage::getModel('sales/order_payment')->getCollection()->addFieldToFilter('last_trans_id',$amazonOrderId);
	foreach($validateorder as $order){
		$orderId=$order->getParentId();
	}
	if(empty($orderId)){
		$product = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToFilter('sku', $sku)
		->addAttributeToSelect('*')
		->getFirstItem();
		$product->load($product->getId());
		$cart = Mage::getModel('checkout/cart');
		$cart->init();
		$cart->addProduct($product, array('qty' => $qty));
		$cart->save();
		Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
		$quote = $cart->getQuote();
		$shippingAddress = $quote->getShippingAddress();
		$_code="pwa_shipping_large";
		$amazonOrderId=$_REQUEST['amznPmtsOrderIds'];
		//$orderStatusAmzn=$_REQUEST['amznPmtsPaymentStatus'];
		$payments = Mage::getSingleton('payment/config')->getActiveMethods();
		$qid=Mage::getModel('checkout/cart')->getQuote()->getId();

		//$quote = Mage::getModel('sales/quote')->load($qid);
		$mode=Mage::helper('paywithamazon')->getConfigData('mode');
		$quote->setCustomerEmail("junglee@email.com");
		$addressData = array(
						'firstname' => 'Amazon',
						'lastname' => 'User',
						'street' => 'Please check your Amazon Seller Central Account to find shipment address for confirmed orders. (If you are a customer please check your "Pay with Amazon" account at https://paywithamazon.amazon.in/)',
						'city' => 'X',
						'postcode' => 'XXXXXX',
						'telephone' => 'XXXXXX',
						'country_id' => 'IN',
						'region_id' => "501" // id from directory_country_region table
						);
		$billingAddress = $quote->getBillingAddress()->addData($addressData);
		$shippingAddress = $quote->getShippingAddress()->addData($addressData);
		$shippingAddress->setShippingMethod($_code);               
		$shippingAddress->setCollectShippingRates(true);
		$shippingAddress->collectShippingRates();
		$quote->collectTotals(); // calls $address->collectTotals();

		$shippingAddress->setPaymentMethod('paywithamazon');  
		$quote->getPayment()->importData(array('method' => 'paywithamazon'));

		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder(); 

		$order->getPayment()->setLastTransId($amazonOrderId)->save();
		$order->setData('state', "new");

		Mage::getModel('checkout/session')->setLastQuoteId($qid)->setLastSuccessQuoteId($qid)->setLastOrderId($order->getId())->setLastRealOrderId($order->getIncrementId());
 		$url=Mage::getBaseUrl().'paywithamazon/checkout/success';

				//$this->_redirect('paywithamazon/checkout/success');
		Mage::app()->getResponse()->setRedirect($url)->sendResponse();	
		exit;		
	}
	else
	{
	$url=Mage::getBaseUrl();
	Mage::app()->getResponse()->setRedirect($url)->sendResponse();	
	}
}	
}		
function invokeListOrderItems(MarketplaceWebServiceOrders_Interface $service, $request)
	{
		$response = $service->ListOrderItems($request);
		$dom = new DOMDocument();
		$dom->loadXML($response->toXML());
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->saveXML();
		$xml = simplexml_load_string($dom->saveXML());
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		$sku=$array['ListOrderItemsResult']['OrderItems']['OrderItem']['SellerSKU'];
		if(empty($sku)){
			$this->invokeListOrderItems($service, $request); 
			Mage::log("Sku not found : ", null, 'orderitemreturn.log',true);
		}
		else{
			Mage::log("Sku returned : ".$sku, null, 'orderitemreturn.log',true);
			return $sku;
		}
}

 public function updateordercronAction()
 {

$orders = Mage::getModel('sales/order')->getCollection()
		 ->addFieldToFilter('status',"pay_with_amazon")
		 ->addFieldToFilter('customer_email',"junglee@email.com");
foreach ($orders as $order) {
	 $amazonId=$order->getPayment()->getLastTransId();
	
	if(!empty($amazonId)){

		$totalShipping=0;
		$totalPromotion=0;
		$serviceUrl = "https://mws.amazonservices.in/Orders/2013-09-01";
		$config = array (
				 'ServiceURL' => $serviceUrl,
				 'ProxyHost' => null,
				 'ProxyPort' => -1,
				 'ProxyUsername' => null,
				 'ProxyPassword' => null,
				 'MaxErrorRetry' => 3,
			 );
		$service = new MarketplaceWebServiceOrders_Client(
						AWS_ACCESS_KEY_ID,
						AWS_SECRET_ACCESS_KEY,
						APPLICATION_NAME,
						APPLICATION_VERSION,
						$config);
		
		$requestorder = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
 		$requestorder->setSellerId(MERCHANT_ID);
 		

 		$requestorder->setAmazonOrderId("$amazonId");
 		$orderresult=array();
 		$orderresult=$this->invokeorder($service, $requestorder);	
 	

 		if($orderresult['OrderStatus']=='Unshipped'){
 			
 			$requestItems = new MarketplaceWebServiceOrders_Model_ListOrderItemsRequest();

		$requestItems->setSellerId(MERCHANT_ID);
		$requestItems->setAmazonOrderId($amazonId);
		$orderresultitem=array();
		$orderresultitem=$this->invokeListOrderItemscron($service, $requestItems); 
		$orderId=$order->getId();
		$order = Mage::getModel('sales/order')->load($orderId);

		foreach ($orderresultitem as $orderItem) {
			 $totalShipping=$totalShipping+($orderItem['ShippingPrice']['Amount']*$orderItem['QuantityOrdered']);
			 $totalPromotion=$totalPromotion+ $orderItem['PromotionDiscount']['Amount'];
		}
	  $BuyerEmail=$orderresult['BuyerEmail'];
    $BuyerName=$orderresult['BuyerName'];
    $buyerLast='';
 		$ordergrandtotal=$orderresult['OrderTotal']['Amount'];
		$order->setCustomerEmail($BuyerEmail)
					->setCustomerFirstname($BuyerName)
					->setCustomerLastname($buyerLast)
					->setBaseShippingAmount($totalShipping)
					->setShippingAmount($totalShipping)
					->setBaseGrandTotal($totalShipping)
					->setGrandTotal($ordergrandtotal)
					->setTotalDue($ordergrandtotal)
					->setshippingInclTax($totalShipping)
					->setDiscountAmount($totalPromotion)
					->setBaseDiscountAmount($totalPromotion)
					->save();
		$ordered_items=$order->getAllItems();
					$qty=0;
					foreach($ordered_items as $item){  
					   	$qty=$qty+ $item->getQtyOrdered();
					     
						}
						$perItemDisc=$totalPromotion/$qty;
						foreach($ordered_items as $item){  
					   	$itemorder=Mage::getModel('sales/order_item')->load($item->getId());
					   	$itemdisc=$perItemDisc*$item->getQtyOrdered();
					   	$itemorder->setDiscountAmount($totalPromotion)
						->setBaseDiscountAmount($totalPromotion)
						->save();
					     
						}
		echo $firstname=$orderresult['ShippingAddress']['Name'];
		$lastname=" ";
		echo $street=$orderresult['ShippingAddress']['AddressLine1']." ".$array['ShippingAddress']['AddressLine2'];
		echo $city=$orderresult['ShippingAddress']['City'];
		echo $country_id=$orderresult['ShippingAddress']['CountryCode'];
		echo $region=$orderresult['ShippingAddress']['StateOrRegion'];
		echo $postcode=$orderresult['ShippingAddress']['PostalCode'];
		echo $telephone=$orderresult['ShippingAddress']['Phone'];
		
				$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
				$order->save();
				$shippingAddress = Mage::getModel('sales/order_address')->load($order->getShippingAddress()->getId());
					$shippingAddress->setFirstname($firstname)->setLastname($lastname)
							->setStreet($street)
							->setCity($city)
							->setCountryId($country_id)
							->setRegion($region)
							->setRegionId("")
							->setPostcode($postcode)
							->setTelephone($telephone)
							->save();
					$billingAddress = Mage::getModel('sales/order_address')->load($order->getBillingAddress()->getId());
					$billingAddress->setFirstname($firstname)
							->setLastname($lastname)
							->setStreet($street)
							->setCity($city)
							->setCountryId($country_id)
							->setRegion($region)
							->setRegionId("")  
							->setPostcode($postcode)
							->setTelephone($telephone)
							->save();
				echo $order->getId()."<br />";
		
 		}
 	}
 }
}


 public function invokeListOrderItemscron(MarketplaceWebServiceOrders_Interface $service, $request)
 {
 	try {

        $response = $service->ListOrderItems($request);
				$dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->saveXML();
        $xml = simplexml_load_string($dom->saveXML());
$json = json_encode($xml);
$array = json_decode($json,TRUE);
       return $array['ListOrderItemsResult']['OrderItems'];
 
     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        /*echo("Caught Exception: " . $ex->getMessage() . "\n");
        echo("Response Status Code: " . $ex->getStatusCode() . "\n");
        echo("Error Code: " . $ex->getErrorCode() . "\n");
        echo("Error Type: " . $ex->getErrorType() . "\n");
        echo("Request ID: " . $ex->getRequestId() . "\n");
        echo("XML: " . $ex->getXML() . "\n");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");*/
     }
 }
 public function invokeorder(MarketplaceWebServiceOrders_Interface $service, $request)
 {
 		try {
 			        $response = $service->GetOrder($request);
        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->saveXML();
        $xml = simplexml_load_string($dom->saveXML());
				$json = json_encode($xml);
				$array = json_decode($json,TRUE);
       	return $array['GetOrderResult']['Orders']['Order'];

     } catch (MarketplaceWebServiceOrders_Exception $ex) {
        /*echo("Caught Exception: " . $ex->getMessage() . "\n");
        echo("Response Status Code: " . $ex->getStatusCode() . "\n");
        echo("Error Code: " . $ex->getErrorCode() . "\n");
        echo("Error Type: " . $ex->getErrorType() . "\n");
        echo("Request ID: " . $ex->getRequestId() . "\n");
        echo("XML: " . $ex->getXML() . "\n");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");*/
     }
 }

}
