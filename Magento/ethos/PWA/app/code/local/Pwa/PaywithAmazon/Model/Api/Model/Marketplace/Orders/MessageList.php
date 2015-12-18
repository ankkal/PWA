<?php

/**
 * Amazon Marketplace Orders API: MessageList data type model
 *
 * Fields:
 * <ul>
 * <li>Message: Array<Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Message></li>
 * </ul>
 *
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_MessageList extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'Message' => array('FieldValue' => null, 'FieldType' => array('Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Message'))
        );
        parent::__construct($data);
    }

}
