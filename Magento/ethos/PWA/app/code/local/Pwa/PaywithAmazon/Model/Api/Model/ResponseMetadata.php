<?php

/**
 * Amazon Common API: Response metadata model
 * 
 * Fields:
 * <ul>
 * <li>RequestId: string</li>
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
abstract class Pwa_PaywithAmazon_Model_Api_Model_ResponseMetadata extends Pwa_PaywithAmazon_Model_Api_Model_Abstract {

    public function __construct($data = null) {
        $this->_fields = array('RequestId' => array('FieldValue' => null, 'FieldType' => 'string'));
        parent::__construct($data);
    }

}
