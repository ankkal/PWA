<?php

/**
 * Amazon Marketplace Orders API: PromotionIdList data type model
 *
 * Fields:
 * <ul>
 * <li>PromotionId: Array<string></li>
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
class Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_PromotionIdList extends Pwa_PaywithAmazon_Model_Api_Model_Marketplace_Orders_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PromotionId' => array('FieldValue' => null, 'FieldType' => array('string'))
        );
        parent::__construct($data);
    }

}
