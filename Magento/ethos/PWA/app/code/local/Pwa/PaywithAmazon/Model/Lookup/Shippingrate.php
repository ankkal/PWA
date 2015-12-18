<?php

/**
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) Pay with Amazon
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from Pay with Amazon
 *
 *  Source_ShippingRate
 *
 *
 * @category   Pwa
 * @package    Pwa_PaywithAmazon
 * @copyright  Pay with Amazon
 * @author     Pay with Amazon
 */
class Pwa_PaywithAmazon_Model_Lookup_Shippingrate extends Pwa_PaywithAmazon_Model_Lookup_Abstract 
{

    public function toOptionArray()
    {
        $options  = array(
            //''=>'Please Select',
            'WeightBased'=>'WeightBased',            
            'ItemQuantityBased'=>'ItemQuantityBased',            
            'ShipmentBased'=>'ShipmentBased',            
        );
 
        return $options;
    }
}