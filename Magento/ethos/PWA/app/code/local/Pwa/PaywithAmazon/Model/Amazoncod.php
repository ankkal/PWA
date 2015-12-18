<?php

/**
 * 
 * Amazon cod (Change status of order )
 * 
 * 
 * 
 */
class Pwa_PaywithAmazon_Model_Amazoncod extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'amazoncod'; 
    protected $_infoBlockType = 'paywithamazon/payment_info';
    protected $_isGateway = false;
    protected $_canAuthorize = true;
    protected $_canCapture = false;
    protected $_canCapturePartial = false;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = false;
    protected $_canVoid = true;
    protected $_canUseInternal = false;
    protected $_canUseCheckout = false;
    protected $_canUseForMultishipping = false;
    protected $_isInitializeNeeded = true;  

}
