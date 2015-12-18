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

define('AWS_ACCESS_KEY_ID', Mage::getStoreConfig('paywithamazon/general/access_key'));
define('AWS_SECRET_ACCESS_KEY', Mage::getStoreConfig('paywithamazon/general/secret_key'));
define('APPLICATION_NAME', 'Order Gen');
define('APPLICATION_VERSION', 'v1.0');
define('MERCHANT_ID', Mage::getStoreConfig('paywithamazon/general/merchant_id'));
if(Mage::getStoreConfig('paywithamazon/general/sandbox_mode')==1)
	define('MARKETPLACE_ID', 'AXGTNDD750VEM');
else
	define('MARKETPLACE_ID', 'A3PY9OQTG31F3H');
class Pwa_PaywithAmazon_Model_Observer extends Pwa_PaywithAmazon_Model_Abstract {

    protected static $_lockApiCalls = false;
    protected static $_lockFeedSending = false;
	protected static $_previousState = '';
	protected static $_lockOrderSave = false;

    protected function _getAmazonManager() {
        return Mage::getSingleton('paywithamazon/manager');
    }

    protected function _assertSchedule($schedule, $timeDiff) {
        switch ($schedule) {
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_NEVER:
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_15_MINUTES:
                if ($timeDiff >= 900) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_30_MINUTES:
                if ($timeDiff >= 1800) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_1_HOUR:
                if ($timeDiff >= 3600) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_2_HOURS:
                if ($timeDiff >= 7200) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_4_HOURS:
                if ($timeDiff >= 14400) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_8_HOURS:
                if ($timeDiff >= 28800) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_12_HOURS:
                if ($timeDiff >= 43200) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_1_DAY:
                if ($timeDiff >= 86400) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_2_DAYS:
                if ($timeDiff >= 172800) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_3_DAYS:
                if ($timeDiff >= 259200) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_7_DAYS:
                if ($timeDiff >= 604800) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_14_DAYS:
                if ($timeDiff >= 1209600) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_15_DAYS:
                if ($timeDiff >= 1296000) return true;
                break;
            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_30_DAYS:
                if ($timeDiff >= 2592000) return true;
                break;
        }
        return false;
    }


    /* Cronjobs */

    public function callAmazonApi() {
        if (!self::$_lockApiCalls) {
            self::$_lockApiCalls = true;

            $currentStore = Mage::app()->getStore();
            $now = Mage::getModel('core/date')->gmtTimestamp();
            $schedule = self::getConfigData('report_schedule');

            // Amazon MWS Reports API
            $lastReportProcessing = self::getConfigData('last_report_processing');
            $lastReportTimeDiff = (int) $now - (int) $lastReportProcessing;

            if ($this->_assertSchedule($schedule, $lastReportTimeDiff)) {
                self::setConfigData('last_report_processing', $now);
                $stores = Mage::app()->getStores(false, false);
                foreach ($stores as $store) {
                    Mage::app()->setCurrentStore($store);
                    if (self::getConfigData('api_order_update')) {
                        switch ($schedule) {
                            case Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Reports_Abstract::SCHEDULE_NEVER:
                                break;
                            default:
                                $nextToken = null;
                                do {
                                    $nextToken = $this->_getAmazonManager()->retrieveAndHandleReportList($nextToken);
                                } while ($nextToken);
                        }
                        $this->_getAmazonManager()->manageReportSchedule($schedule);
                    }
                    Mage::app()->setCurrentStore($currentStore);
                }
            }

            // Amazon MWS Orders API
            $lastOrderProcessing = self::getConfigData('last_order_processing');
            $lastOrderTimeDiff = (int) ($now - (int) $lastOrderProcessing);

            if ($this->_assertSchedule($schedule, round($lastOrderTimeDiff / 2))) {
                self::setConfigData('last_order_processing', $now);
                $stores = Mage::app()->getStores(false, false);
                foreach ($stores as $store) {
                    Mage::app()->setCurrentStore($store);
                    if (self::getConfigData('api_order_update')) {
                        $this->_getAmazonManager()->updateCanceledOrders();
                    }
                    Mage::app()->setCurrentStore($currentStore);
                }

            }

            self::$_lockApiCalls = false;
        }
    }

    public function cleanAmazonLog() {
        Pwa_PaywithAmazon_Model_Logger::cleanLogs();
    }

    public function sendFeeds() {
        if (!self::$_lockFeedSending) {
            self::$_lockFeedSending = true;

            $currentStore = Mage::app()->getStore();
            $now = Mage::getModel('core/date')->gmtTimestamp();
            $schedule = self::getConfigData('feed_schedule');
            $lastFeedSending = self::getConfigData('last_feed_sending');
            $lastFeedTimeDiff = (int) $now - (int) $lastFeedSending;

            if ($this->_assertSchedule($schedule, $lastFeedTimeDiff)) {
                self::setConfigData('last_feed_sending', $now);
                $stores = Mage::app()->getStores(false, false);
                foreach ($stores as $store) {
                    Mage::app()->setCurrentStore($store);
                    if (self::getConfigData('feed_batching')) {
                        $this->_getAmazonManager()->sendFeeds();
                    }
                    Mage::app()->setCurrentStore($currentStore);
                }
            }

            self::$_lockFeedSending = false;
        }
    }

    /* Observers */

    public function cancelOrderInAmazon($observer) {
        try {
            $order = $observer->getEvent()->getPayment()->getOrder();
            if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod())) {
                $currentStore = Mage::app()->getStore();
                Mage::app()->setCurrentStore($order->getStore()->getId());
                $this->_getAmazonManager()->sendCancellationNotify($order);
                Mage::app()->setCurrentStore($currentStore);
            }
        } catch (Exception $e) {
            if (!($e instanceof Pwa_PaywithAmazon_Exception)) {
                Pwa_PaywithAmazon_Model_Logger::logException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getTraceAsString(),
                    'Amazon MWS Feeds',
                    null
                );
            }
        }
        return $this;
    }
    /*Function Not In Use*/
    /*public function confirmShipmentToAmazon($observer) {
        try {
            $order = $observer->getEvent()->getOrder();
            if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) &&
                    !in_array($order->getState(), array(Mage_Sales_Model_Order::STATE_COMPLETE, Mage_Sales_Model_Order::STATE_CLOSED, Mage_Sales_Model_Order::STATE_CANCELED))) {

                if ($order->getShipmentsCollection()->count()) {
                    $currentStore = Mage::app()->getStore();
                    Mage::app()->setCurrentStore($order->getStore()->getId());
                    $transactionSave = Mage::getModel('core/resource_transaction');
                    $invoices = $order->getInvoiceCollection();
                    if ($invoices->count()) {
                        foreach ($invoices as $invoice) {
                            $invoice->pay();
                            $transactionSave->addObject($invoice);
                        }
                    }
                    $order->addStatusHistoryComment(Mage::helper('paywithamazon')->__('<strong>%s</strong>: Order shipment has been notified to Amazon', 'Amazon MWS'));
                    $transactionSave->addObject($order)->save();
                    $this->_getAmazonManager()->sendShipmentNotify($order);
                    Mage::app()->setCurrentStore($currentStore);
                }
            }
        } catch (Exception $e) {
            if (!($e instanceof Pwa_PaywithAmazon_Exception)) {
                Pwa_PaywithAmazon_Model_Logger::logException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getTraceAsString(),
                    'Amazon MWS Feeds',
                    null
                );
            }
        }
        return $this;
    }*/
	
	public function sendShipmentToAmazon($observer){
		 try {
            $order = $observer->getEvent()->getOrder();
          if ($this->_isAmazonPaymentMethod($order->getPayment()->getMethod()) &&
                    ($order->getState() == Mage_Sales_Model_Order::STATE_COMPLETE) && (!self::$_lockOrderSave)) { 
					$logship = Mage::getModel('paywithamazon/log_ship')->load($order->getID(), 'order_id');
                if ($order->getShipmentsCollection()->count() && (!$logship->getData('confirm_status') || $logship->getData('confirm_status')==0)) {  
                    $currentStore = Mage::app()->getStore();
                    Mage::app()->setCurrentStore($order->getStore()->getId());
                    $transactionSave = Mage::getModel('core/resource_transaction');
                    $invoices = $order->getInvoiceCollection(); 
                    /*if ($invoices->count()) {
                        foreach ($invoices as $invoice) {
                            $invoice->pay();
                            $transactionSave->addObject($invoice);
                        }
                    } */
                    $order->addStatusHistoryComment(Mage::helper('paywithamazon')->__('<strong>%s</strong>: Order shipment has been notified to Amazon', 'Amazon MWS'));
					self::$_lockOrderSave = true;
                    $transactionSave->addObject($order)->save(); 
                    $this->_getAmazonManager()->sendShipmentNotify($order);
					Mage::getModel('paywithamazon/log_ship')->setOrderID($order->getID())->setConfirmStatus('1')->save(); 
                    Mage::app()->setCurrentStore($currentStore);
                }
            }
        } catch (Exception $e) {
            if (!($e instanceof Pwa_PaywithAmazon_Exception)) {
                Pwa_PaywithAmazon_Model_Logger::logException(
                    $e->getMessage(),
                    $e->getCode(),
                    $e->getTraceAsString(),
                    'Amazon MWS Feeds',
                    null
                );
            }
        }
        return $this;
		
	}

    public function logApiCall($observer) {
        $requestMethod = 'GET';
        $host = $observer->getEvent()->getHost();
        $action = $observer->getEvent()->getAction();
        $headers = $observer->getEvent()->getHeaders();
        $get = $observer->getEvent()->getGet();
        $post = $observer->getEvent()->getPost();
        $file = $observer->getEvent()->getFile();
        $responseCode = $observer->getEvent()->getResponseCode();
        $response = $observer->getEvent()->getResponse();

        if ($file):
            $requestMethod = 'FILE';
        elseif ($post):
            $requestMethod = 'POST';
        endif;

        Pwa_PaywithAmazon_Model_Logger::logApiCall($host, $action, $requestMethod, $headers, $get, $post, $file, $responseCode, $response);
        return $this;
    }
	
	public function setPreviousState(Varien_Event_Observer $observer)
	{
		$eventOrder = $observer->getEvent()->getOrder();
		$order = ($eventOrder->getId()) ? Mage::getModel('sales/order')->load($eventOrder->getId()) : $eventOrder;
		$paymentMethod = trim($order->getPayment()->getMethodInstance()->getCode());
		if(preg_match("/^paywithamazon/", $paymentMethod) === 0)
			return $this;
		self::$_previousState = $state = $order->getState();
		return $this;
	}
	
	// refered the core function to update the invetory "Mage_CatalogInventory_Model_Observer"
	public function setInventoryUpdate(Varien_Event_Observer $observer)
	{   
		$order = $observer->getEvent()->getOrder();
		$paymentMethod = trim($order->getPayment()->getMethodInstance()->getCode());
		$state = $order->getState();
		if(preg_match("/^paywithamazon/", $paymentMethod) === 0)
			return $this;
		
		if($order->getState() == Mage_Sales_Model_Order::STATE_PROCESSING && self::$_previousState == Mage_Sales_Model_Order::STATE_NEW){
			$quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
			$items = $this->_getProductsQty($quote->getAllItems());
			
			/**
			 * Remember items
			 */
			$itemsForReindex = Mage::getSingleton('cataloginventory/stock')->registerProductsSale($items);
			
			$productIds = array();
			foreach ($quote->getAllItems() as $item) {
				$productIds[$item->getProductId()] = $item->getProductId();
				$children   = $item->getChildrenItems();
				if ($children) {
					foreach ($children as $childItem) {
						$productIds[$childItem->getProductId()] = $childItem->getProductId();
					}
				}
			}

			if (count($productIds)) {
                Mage::log('Exception String : '.$order->getId().' reindexProducts  prince yadav <br>', null, 'pwa_error.log', true);
				Mage::getResourceSingleton('cataloginventory/indexer_stock')->reindexProducts($productIds);
			}

			// Reindex previously remembered items
			$productIds = array();
			foreach ($itemsForReindex as $item) {
				$item->save();
				$productIds[] = $item->getProductId();
			}
			Mage::getResourceSingleton('catalog/product_indexer_price')->reindexProductIds($productIds);
		}
		/*elseif($order->getState() == Mage_Sales_Model_Order::STATE_CANCELED && self::$_previousState == Mage_Sales_Model_Order::STATE_PROCESSING){
            Mage::log('Exception String : '.$order->getId().' backItemQty prince yadav <br>', null, 'pwa_error.log', true);
			foreach($order->getAllItems() as $item){
				$qty = $item->getQtyOrdered();
				$children = $item->getChildrenItems();
				$productId = $item->getProductId();
				if ($item->getId() && $productId && empty($children) && $qty) {
					Mage::getSingleton('cataloginventory/stock')->backItemQty($productId, $qty);
				}
			}
		}*/
		return $this;
	}
	
	protected function _addItemToQtyArray($quoteItem, &$items)
    {
        $productId = $quoteItem->getProductId();
        if (!$productId)
            return;
        if (isset($items[$productId])) {
            $items[$productId]['qty'] += $quoteItem->getTotalQty();
        } else {
            $stockItem = null;
            if ($quoteItem->getProduct()) {
                $stockItem = $quoteItem->getProduct()->getStockItem();
            }
            $items[$productId] = array(
                'item' => $stockItem,
                'qty'  => $quoteItem->getTotalQty()
            );
        }
    }

    /**
     * Prepare array with information about used product qty and product stock item
     * result is:
     * array(
     *  $productId  => array(
     *      'qty'   => $qty,
     *      'item'  => $stockItems|null
     *  )
     * )
     * @param array $relatedItems
     * @return array
     */
    protected function _getProductsQty($relatedItems)
    {
        $items = array();
        foreach ($relatedItems as $item) {
            $productId  = $item->getProductId();
            if (!$productId) {
                continue;
            }
            $children = $item->getChildrenItems();
            if ($children) {
                foreach ($children as $childItem) {
                    $this->_addItemToQtyArray($childItem, $items);
                }
            } else {
                $this->_addItemToQtyArray($item, $items);
            }
        }
        return $items;
    }
	
	public function cancelOrder()
	{
		Mage::log('Check Cancel Order in MWS Cron', null, 'mws.log', 1);
		$response = $this->getOrderByMWS(true);
		Mage::log(count($orders = $response->getListOrdersResult()->getOrders()), null, 'mws.log', 1);
		if(count($orders = $response->getListOrdersResult()->getOrders())){
			foreach($orders as $order){
				$amazonOrderId = (string)$order->getAmazonOrderId();
				Mage::log($amazonOrderId, null, 'mws.log', 1);
				if(!$orderData = Mage::getModel('paywithamazon/order')->isNewOrder($amazonOrderID)){
					Mage::getModel('paywithamazon/order')->cancelOrder($orderData['parent_id']);
				}
			}
		}
		return $this;
	}
	
	public function updateOrder()
	{
		$response = $this->getOrderByMWS();
		if(count($orders = $response->getListOrdersResult()->getOrders())){
			foreach($orders as $order){
				$amazonOrderId = (string)$order->getAmazonOrderId();
				if(!$orderData = Mage::getModel('paywithamazon/order')->isNewOrder($amazonOrderID)){
					$orderStatus = $order->getOrderStatus();
					if($orderStatus == 'Canceled'){
						Mage::getModel('paywithamazon/order')->cancelOrder($orderId);
					}
					elseif($orderStatus == 'Unshipped' || $orderStatus == 'PartiallyShipped' || $orderStatus == 'Shipped'|| $orderStatus == 'InvoiceUnconfirmed'){
						Mage::getModel('paywithamazon/order')->updateOrderMWS($orderData['parent_id'], $order);
					}
				}
			}
		}
		return $this;
	}
	
	public function getOrderByMWS($isCancel = false)
	{
		$clientFile = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Client.php';
		$listOrder = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Model' . DS . 'ListOrdersRequest.php';
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
		$request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
		$request->setSellerId(MERCHANT_ID);
		$request->setMarketplaceId(MARKETPLACE_ID);
		$request->setCreatedAfter(date('Y-m-d\TH:i:s\Z', strtotime("-1 days")));
		if($isCancel)
			$request->setOrderStatus('Canceled');
		// object or array of parameters
		try {
			return $response = $service->ListOrders($request);
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
		return false;
	}

    public function changeAttributeVisabilty(Varien_Event_Observer $observe)
    {        
        //$awsProductAttribute = new SimpleXMLElement(Mage::getConfig()->getNode('default/catalog/product/attribute')->asXml());

        /*foreach($awsProductAttribute as $key => $node)
        {   
            $array_attibute[] = $key;
        } */
        $arrDependEasyship = array(
            "easy_aws_length",
            "easy_aws_width",
            "easy_aws_height",        
            "easy_aws_gil",        
            "easy_aws_hazmat"
        );
        $arrDependPwa = array(        
            "easy_aws_hand_min",        
            "easy_aws_hand_max"        
        );

        if(Mage::helper('paywithamazon')->getAwsActiveStatus() ){            
            $eavConfig = Mage::getSingleton('eav/config'); 
            foreach ($arrDependPwa as $key => $value) {
                    $attribute = $eavConfig->getAttribute('catalog_product', $value);
                    $attribute->addData(array(
                        'is_visible' => 1
                    ));
                    $attribute->save();
                 }     
            
        }
        else{
            $eavConfig = Mage::getSingleton('eav/config');      
            foreach ($arrDependPwa as $key => $value) {
                    $attribute = $eavConfig->getAttribute('catalog_product', $value);
                    $attribute->addData(array(
                        'is_visible' => 0
                    ));
                    $attribute->save();
                 }  

        }
        if(Mage::helper('paywithamazon')->getAwsActiveStatus() && Mage::helper('paywithamazon')->getEasyShipStatus()){            
            $eavConfig = Mage::getSingleton('eav/config'); 
            foreach ($arrDependEasyship as $key => $value) {
                    $attribute = $eavConfig->getAttribute('catalog_product', $value);
                    $attribute->addData(array(
                        'is_visible' => 1
                    ));
                    $attribute->save();
                 }     
            
        }
        else{
            $eavConfig = Mage::getSingleton('eav/config');      
            foreach ($arrDependEasyship as $key => $value) {
                    $attribute = $eavConfig->getAttribute('catalog_product', $value);
                    $attribute->addData(array(
                        'is_visible' => 0
                    ));
                    $attribute->save();
                 }  

        }

        
    }
    public function coreBlockAbstractToHtmlBefore(Varien_Event_Observer $observer)
    {    
        if(!Mage::helper('paywithamazon')->getAwsActiveStatus()) 
            return true;

        /** @var $block Mage_Core_Block_Abstract */
        $block = $observer->getEvent()->getBlock();
        if ($block->getId() == 'sales_order_grid') {
             
            //add new column: payment method
            $paymentArray = Mage::getSingleton('payment/config')->getActiveMethods();
            $paymentMethods = array();
            foreach ($paymentArray as $code => $payment) {
                
                $paymentTitle = Mage::getStoreConfig('payment/'.$code.'/title');
                $paymentMethods[$code] = $paymentTitle;
            }
            // tfm_shipment_status
            $tfmShipmentOptions = Mage::helper('pwa_shipping')->tfmShipmentOptions();
            $block->addColumnAfter(
                'tfm_shipment_status',
                array(
                    'header'   => Mage::helper('sales')->__('Easy Ship Status'),
                    'align'    => 'left',
                    'type'     => 'options',
                    'options'  => $tfmShipmentOptions,
                    'index'    => 'tfm_shipment_status',
                    'filter_index'    => 'tfm_shipment_status' 
                     
                ),
                'shipping_name'
            );
            $block->addColumnAfter(
                'payment_method',
                array(
                    'header'   => Mage::helper('sales')->__('Payment Method'),
                    'align'    => 'left',
                    'type'     => 'options',
                    'options'  => $paymentMethods,
                    'index'    => 'payment_method',
                    'filter_index'    => 'payment.method',
                ),
                'shipping_name'
            );
             
            //similary you can addd new columns
            //...
 
            // Set the new columns order.. otherwise our column would be the last one
            $block->sortColumnsByOrder();
        }
    }
 
    /**
     * Moved to block class
     * @param Varien_Event_Observer $observer
     */
    public function salesOrderGridCollectionLoadBefore(Varien_Event_Observer $observer)
    {   if(!Mage::helper('paywithamazon')->getAwsActiveStatus()) 
            return true;
            
        $collection = $observer->getOrderGridCollection();
        $select = $collection->getSelect(); 
        $select->joinLeft(array('payment' => $collection->getTable('sales/order_payment')), 'payment.parent_id=main_table.entity_id',array('payment_method' => 'method'));
        
        
    }
    public function removeShipButtonOnOrderView(Varien_Event_Observer $event){
        
        $paramsarray = Mage::app()->getRequest()->getParams('order_id');
        if(isset($paramsarray['order_id'])){
            $orders = Mage::getModel('sales/order')->load($paramsarray['order_id']);
            if($orders->getEasyshipable() == 1){
                 $block = $event->getBlock();
                if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
                    $block->removeButton('order_ship');
                }
            }
        }
    }

}
