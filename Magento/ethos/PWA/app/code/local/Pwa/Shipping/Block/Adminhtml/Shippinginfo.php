<?php

class Pwa_Shipping_Block_Adminhtml_Shippinginfo extends Mage_Adminhtml_Block_Sales_Order_View_Info {

    private $order;
    private $sellerCentralOrderUrl="https://sellercentral.amazon.in/gp/orders-v2/details/ref=cb_orddet_cont_myo?ie=UTF8&orderID=";
    protected function _prepareLayout()
    {
        $this->order=$this->getOrder();
        return parent::_prepareLayout();
    }

    protected function getAmazonOrderId(){
        return $this->order->getPayment()->getLastTransId();
    }
    protected function getRefreshStatusButtonUrl(){
        $order_id=$this->order->getId();
        return Mage::helper("adminhtml")->getUrl("easyship/adminhtml_easyship/index",array('order_id'=>$order_id));
    }
    protected function sellerCentralOrderUrl(){
        return $this->sellerCentralOrderUrl.$this->getAmazonOrderId();
    }
    protected function placedUsingAmazon(){
        return ($this->order->getPayment()->getLastTransId()!=null);
    }
    protected function orderInfoUpdatedOnce(){
        return ($this->order->getEasyshipable()!==null);
    }
    protected function getShipmentStatus(){
        $shipmentStatus=$this->order->getTfmShipmentStatus();

        $tfmShipmentOptions = Mage::helper('pwa_shipping')->tfmShipmentOptions();        
        return $tfmShipmentOptions[$shipmentStatus];
    }

    protected function isEasyShipable(){
       return ($this->order->getEasyshipable()==NULL || $this->order->getEasyshipable()==1);
    }
    protected function getSellerCentralOrderUrl(){
        return $this->sellerCentralOrderUrl.$this->getAmazonOrderId();
    }
    // Order is Easyshipable and Shipment Status does not exist(No pickup is schduled yet)
    protected function canSchedulePickup(){         
        return ($this->order->getEasyshipable() && ($this->order->getTfmShipmentStatus()==NULL || $this->order->getTfmShipmentStatus()==1));
    }
    protected function canPrintLabel(){
        return ($this->order->getEasyshipable() && !($this->order->getTfmShipmentStatus()==NULL || $this->order->getTfmShipmentStatus()==1));
    }
    protected function getSchedulePickupButtonHtml(){
        // Schedule Pickup Button
        $html='<a  class="button" 
                        target="_blank"
                        href="'.$this->getSellerCentralOrderUrl().'">
                        Schedule Pickup</a>';
        return $html;
    }
    protected function getPrintShippingLabelButtonHtml(){
        // Print Shipping Label Button                        
        $html='<a class="button" target="_blank"
                       href="'.$this->getSellerCentralOrderUrl().'">
                       Print Shipping Label</a>';
        return $html;
    }

}
