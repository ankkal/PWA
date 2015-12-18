<?php

// Cron and Status Related Configuration
define('EASYSHIPABLE_MWS_FLAG', 'IN Exp Dom 2');
define('EASYSHIPABLE_IOPN_FLAG', 'Amazon Shipping');
define('ORDER_ID_ERROR_MSG', 'ERROR: Amazon Order Id does not exist');
define('ERROR_FLAG', 0);
define('SUCCESS_FLAG', 1);
define("CRON_INTIALIZATION_FLAG", '-16 day');

// Shipping status update email configuration
define('SEND_SHIPPING_UPDATE_EMAIL',Mage::getStoreConfig('paywithamazon/easyship/email_settings'));

// Include MWS Client Config File
require_once(Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Samples'.DS.'config_inc.php');

class Pwa_Shipping_Model_Mwscalls extends Mage_Core_Model_Abstract 
{   
    private $service;
    private $order;
    private $amazonOrderId;

    protected function _construct()
    {
        $config = array (
        'ServiceURL' => "https://mws.amazonservices.in/Orders/2013-09-01",
        'ProxyHost' => null,
        'ProxyPort' => -1,
        'ProxyUsername' => null,
        'ProxyPassword' => null,
        'MaxErrorRetry' => 3,
        );
        $this->getMwsClient();
       
        $service = new MarketplaceWebServiceOrders_Client(
            AWS_ACCESS_KEY_ID,
            AWS_SECRET_ACCESS_KEY,
            APPLICATION_NAME,
            APPLICATION_VERSION,
            $config);

        $this->service=$service;
    }
    protected function getMwsClient(){        
        $clientFile = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Client.php';        
        require_once($clientFile); 
    }
    protected function includeGetOrderAPIClient(){        
        $getOrder = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Model' . DS . 'GetOrderRequest.php';       
        require_once($getOrder);
    }
    protected function includeListOrderAPIClient(){        
        $listOrder = Mage::getBaseDir('lib') . DS .'mws'. DS .'src'. DS . 'MarketplaceWebServiceOrders' . DS . 'Model' . DS . 'ListOrdersRequest.php';       
        require_once($listOrder);
    }
    protected function loadOrder($order_id){
        return Mage::getModel('sales/order')->load($order_id);
    }
    protected function loadOrdersByAmazonOrderIds($amazonOrderIds){
        
        $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToSelect('entity_id')
                ->addFieldToSelect('increment_id')
                ->addFieldToSelect('easyshipable')
                ->addFieldToSelect('tfm_shipment_status')
                ->join(
                    array('payment' => 'sales/order_payment'),
                    'main_table.entity_id=payment.parent_id',
                    array('amazon_order_id' => 'payment.last_trans_id')
                );        
        $orders->addFieldToFilter('payment.last_trans_id',$amazonOrderIds );
        return $orders;
    }
    protected function responseXmlToArray($response){
        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        
        $xml=simplexml_load_string($dom->saveXML());
        return json_decode( json_encode($xml) , 1);
    }
    protected function getCronLastExecution(){        
        // get Cron Last Execution from Database        
        if(Mage::helper('pwa_shipping')->getLastCronExecution()==null)
            $last_executed_on=CRON_INTIALIZATION_FLAG;
        
        $dateTime = new DateTime($last_executed_on, new DateTimeZone('UTC'));        
        return $dateTime->format(DATE_ISO8601);
    }
    public function callGetOrderAPI($amazonOrderId){

        $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
        $request->setSellerId(MERCHANT_ID);
        $request->setAmazonOrderId($amazonOrderId);        
        try {
            $response = $this->service->GetOrder($request);            
            $response = $this->responseXmlToArray($response);
        }
        catch (MarketplaceWebServiceOrders_Exception $response) {}
        return $response;
    } 
    public function callGetOrderAPIForIopn($amazonOrderId){
        $this->includeGetOrderAPIClient();
        $request = new MarketplaceWebServiceOrders_Model_GetOrderRequest();
        $request->setSellerId(MERCHANT_ID);
        $request->setAmazonOrderId($amazonOrderId);        
        try {
            $response = $this->service->GetOrder($request);            
            $response = $this->responseXmlToArray($response);
        }
        catch (MarketplaceWebServiceOrders_Exception $response) {}
        return $response;
    }
    public function callListOrderAPI($lastUpdatedAfter){
        
        $request = new MarketplaceWebServiceOrders_Model_ListOrdersRequest();
        $request->setSellerId(MERCHANT_ID); 
        $request->setLastUpdatedAfter($lastUpdatedAfter);
        $request->setMarketplaceId(MARKETPLACE_ID);
         
        try {            
            $response = $this->service->ListOrders($request);
            $response = $this->responseXmlToArray($response);
             
        }
        catch (MarketplaceWebServiceOrders_Exception $response) {}
        return $response;
    }
    public function updateShippingStatusByGetOrder($order_id){

        $magentoOrder=$this->loadOrder($order_id);
        $amazonOrderId=$magentoOrder->getPayment()->getLastTransId();        
        $this->includeGetOrderAPIClient();
        $response=$this->callGetOrderAPI($amazonOrderId);
        // If we got error in response
        if($response instanceof MarketplaceWebServiceOrders_Exception)
        {
            // $response->getErrorCode(); //RequestThrottled
            $message="ERROR: ".$response->getMessage().". Please try after sometime";
            $response_flag=ERROR_FLAG;    
        }    
        else
        {   
            $orders=$response['GetOrderResult']['Orders'];
            if(!empty($orders))
            {
                $amazonOrder=$response['GetOrderResult']['Orders']['Order'];
                $this->updateOrderData($magentoOrder,$amazonOrder);
                $response_flag=1;
            }
            else
            {
                $message="ERROR: Invalid Amazon Order Id '".$amazonOrderId."'. Please check your Seller Panel Information in System->Configuration";
                $response_flag=ERROR_FLAG;
            }
        }        
        return array('response_flag'=>$response_flag,'message'=> $message);
    }

    public function updateShippingStatusByListOrders()
    {
        
        $this->includeListOrderAPIClient();
        $lastUpdatedAfter = $this->getCronLastExecution();
        $response=$this->callListOrderAPI($lastUpdatedAfter);

        if(!($response instanceof MarketplaceWebServiceOrders_Exception))
        {
            if(is_array($response))
            {
                $responseArray=array();
                $amazonOrders=$response['ListOrdersResult']['Orders']['Order'];
                foreach ($amazonOrders as $aOrder)
                    $responseArray[$aOrder['AmazonOrderId']]=$aOrder;                
            }
            
            $keys=array_keys($responseArray);
            if(!empty($keys))
            {                
                // Load Order Collection of selected Orders recieved from MWS Response
                $orderCollection=$this->loadOrdersByAmazonOrderIds($keys);
                foreach($orderCollection as $order)
                {
                    $amazonOrderId=$order->getAmazonOrderId();
                    $this->updateOrderData($order,$responseArray[$amazonOrderId]);
                }
                Mage::helper('pwa_shipping')->updateLastCronExecution();
            }
        }

    }

    public function updateOrderData($mageOrder,$amazonOrder){          

        $easyShipable=($amazonOrder['ShipServiceLevel']==EASYSHIPABLE_MWS_FLAG || $amazonOrder['DisplayableShippingLabel']==EASYSHIPABLE_IOPN_FLAG);
        if($easyShipable!=1){
            $easyShipable=0;
            $TFMShipmentStatus=0;
        }
        else
            $TFMShipmentStatus=(isset($amazonOrder['TFMShipmentStatus']) && $amazonOrder['TFMShipmentStatus'] != '')?$amazonOrder['TFMShipmentStatus']:1;
        
       
        if(isset($amazonOrder['PaymentMethod']) && $amazonOrder['PaymentMethod']=="COD")
            $mageOrder->getPayment()->setMethod('amazoncod')->save();

        $oldStatus=$mageOrder->getTfmShipmentStatus();        
        $mageOrder->setEasyshipable($easyShipable)
                ->setTfmShipmentStatus($TFMShipmentStatus)
                ->save();
        
        $this->performStatusChangedAction($mageOrder,$oldStatus,$TFMShipmentStatus);
    }
    public function performStatusChangedAction($mageOrder,$oldStatus,$newStatus){

        //  Shipping Status Updation and Shipping Update Email is Enabled
        if($oldStatus!==$newStatus && !($newStatus===0 || $newStatus===1)){           
            if($newStatus==='Delivered'){
                $this->createInvoice($mageOrder);
                $this->createShipment($mageOrder);                
                $mageOrder->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
                $mageOrder->addStatusToHistory(Mage_Sales_Model_Order::STATE_COMPLETE);
                $mageOrder->save();
            }
            $this->sendShippingUpdateEmail($mageOrder);
        }
    }

    public function getTemplateId($storeId){
        return Mage::getStoreConfig('paywithamazon/easyship/email_template', $storeId);
    }
    public function getSenderInfo($storeId){

        $ident=Mage::getStoreConfig('sales_email/shipment/identity', $storeId);
        $senderName=Mage::getStoreConfig('trans_email/ident_'.$ident.'/name', $storeId);
        $senderEmail=Mage::getStoreConfig('trans_email/ident_'.$ident.'/email', $storeId);
        return array('name' => $senderName,'email' => $senderEmail);
    }
    public function getRecepients($order,$storeId){
        $recepients=array();
        if(SEND_SHIPPING_UPDATE_EMAIL)
        $recepients[]=array('name'=> $order->getCustomerFirstname()." ".$order->getCustomerLastname(),
                        'email'=>$order->getCustomerEmail());

        // paywithamazon_easyship_send_copy_to
        $copyToEmail = Mage::getStoreConfig('paywithamazon/easyship/send_copy_to', $storeId);        
        
        // Get the destination email addresses to send copies to
        $copyToEmail = (!empty($copyToEmail))?explode(',', $copyToEmail):false;
        foreach ($copyToEmail as $copyEmail) {
            $recepients[]=array('name'=>'','email'=>$copyEmail);
        }
        return $recepients;
    }
    public function sendShippingUpdateEmail($order){

        // Get Store ID
        $storeId = $order->getStore()->getId();
        
        // Retrieve corresponding email template id and customer name
        $templateId = $this->getTemplateId($storeId);;

        $customerName = $order->getBillingAddress()->getName();

        $mailer=Mage::getModel('core/email_template');      
      
        $sender = $this->getSenderInfo($storeId);

        $recepients=$this->getRecepients($order,$storeId);
        
        // Set variables that can be used in email template. To get this variable write like {{var customerEmail}} in transactional Email.
         $vars = array(
            'order'        => $order,
            'shipment_status'=>$order->getTfmShipmentStatus()
         );
         
         $translate  = Mage::getSingleton('core/translate');
         try{
            if($templateId)
            {
                // Send Transactional Email                
                foreach ($recepients as $recepient)
                    Mage::getModel('core/email_template')                 
                         ->sendTransactional($templateId, $sender, $recepient['email'], $recepient['name'], $vars, $storeId);                
            }         
            $translate->setTranslateInline(true);
            
        }catch(Exception $e){}
    }
    public function createShipment($order){

        if($order->canShip())
        {
            $shipment = $order->prepareShipment();
            if( $shipment ) {
                 $shipment->register();
                 $order->setIsInProcess(true);
                 Mage::getModel('core/resource_transaction')
                        ->addObject($shipment)
                        ->addObject($shipment->getOrder())
                        ->save();
            }
        }
    }
    private function createInvoice($_order){
       if($_order->canInvoice()) {              
         $invoiceId = Mage::getModel('sales/order_invoice_api')
            ->create($_order->getIncrementId(), array() ,'Invoice Created By Amazon' ,1,1);
        } 
    }
}


        