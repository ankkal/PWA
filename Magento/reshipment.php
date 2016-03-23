<?php
    $mageFile = getcwd() . '/app/Mage.php';
    require_once($mageFile);
    Mage::app();
    ini_set('display_errors', 1);

    /*Include Library*/

    $listOrder = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Model' . DS . 'ListOrdersRequest.php';       
    require_once($listOrder);

    $clientFile = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Client.php';        
    require_once($clientFile); 

    define('AWS_ACCESS_KEY_ID', Mage::getStoreConfig('paywithamazon/general/access_key'));
    define('AWS_SECRET_ACCESS_KEY', Mage::getStoreConfig('paywithamazon/general/secret_key'));
    define('APPLICATION_NAME', 'Order Gen');
    define('APPLICATION_VERSION', 'v1.0');
    define('MERCHANT_ID', Mage::getStoreConfig('paywithamazon/general/merchant_id'));
    if(Mage::getStoreConfig('paywithamazon/general/sandbox_mode')==1)
        define('MARKETPLACE_ID', 'AXGTNDD750VEM');
    else
        define('MARKETPLACE_ID', 'A3PY9OQTG31F3H');
    
        $config = array (
        'ServiceURL' => "https://mws.amazonservices.in/Orders/2013-09-01",
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

        
    
    
        
    $LAST_PROCESSING = Mage::getStoreConfig('paywithamazon/general/last_reshipment_date');
    if($LAST_PROCESSING == ''){
	
	$days_ago = date('Y-m-d H:i:s',strtotime('-7 days', strtotime(date('Y-m-d H:i:s'))));
	
        Mage::getConfig()->saveConfig('paywithamazon/general/last_reshipment_date',$days_ago)->cleanCache();
        $LAST_PROCESSING = Mage::getStoreConfig('paywithamazon/general/last_reshipment_date');
    }

    $date = date('Y-m-d',strtotime($LAST_PROCESSING)).'T'.date('H:i:s',strtotime($LAST_PROCESSING)).'Z';


    $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
    $request->setSellerId(MERCHANT_ID); 
    $request->setLastUpdatedAfter($date);
    $request->setMarketplaceId(MARKETPLACE_ID);
    $request->setOrderStatus(array('0'=>'Unshipped','1'=>'PartiallyShipped')); 

    try {
        $response = $service->ListOrders($request);
	$dom = new DOMDocument();
	$dom->loadXML($response->toXML());
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$xml=simplexml_load_string($dom->saveXML());
        $dataResponse = json_decode( json_encode($xml) , 1);
    }
    catch (Exception $e) {

    }

    if(is_array($dataResponse) && !empty($dataResponse) && isset($dataResponse['ListOrdersResult']['Orders']['Order'])){
        $orders = $dataResponse['ListOrdersResult']['Orders']['Order'];
        foreach ($orders as $key => $value) {
            if($value['OrderStatus'] == 'Unshipped'){
                $AmazonOrderId = $value['AmazonOrderId'];
                $collection = Mage::getModel('paywithamazon/order')->isNewOrder($AmazonOrderId);
                if(isset($collection['parent_id'])){
                    $orderId = $collection['parent_id'];    
                    $order = Mage::getModel('sales/order')->load($orderId);

                    if(!$order->canShip() && !$order->canInvoice()){                        
                        Mage::getModel('paywithamazon/manager')->sendShipmentNotify($order);
                    }
                }
            }
        }
    }
    
