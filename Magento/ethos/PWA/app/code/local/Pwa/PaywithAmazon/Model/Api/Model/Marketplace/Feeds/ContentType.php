<?php

/**
 * Amazon Marketplace Feeds API: ContentType data type model
 *
 * Fields:
 * <ul>
 * <li>ContentType: string</li>
 * <li>Parameters: Array<string></li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_ContentType extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Feeds_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'ContentType' => array('FieldValue' => null, 'FieldType' => 'string'),
            'Parameters' => array('FieldValue' => null, 'FieldType' => array('string'))
        );
        parent::__construct($data);
    }

}
