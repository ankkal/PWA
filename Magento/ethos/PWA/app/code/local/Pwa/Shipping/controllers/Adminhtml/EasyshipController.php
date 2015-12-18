<?php

/**
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) Easyship
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from Pay with Amazon
 *
 * @category   Pwa
 * @package    Pwa_Shipping
 * @copyright  Copyright (c) Easyship
 * @author     Easyship
 */

class Pwa_Shipping_Adminhtml_EasyshipController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {

        $order_id=$this->getRequest()->getParam('order_id');       
        $response=Mage::getModel('pwa_shipping/mwscalls')->updateShippingStatusByGetOrder($order_id);
        
        if($response['response_flag']==1)
            Mage::getSingleton('adminhtml/session')->addSuccess('Amazon Order Data has been updated successfully'); 
        else
        {    
         
            Mage::getSingleton('adminhtml/session')->addError($response['message']); 
        }
               
        $url=Mage::helper("adminhtml")->getUrl("adminhtml/sales_order/view",array('order_id'=>$order_id));
        $this->getResponse()->setRedirect($url);
        
    }
}