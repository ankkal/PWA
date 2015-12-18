<?php

/**
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
class Pwa_PaywithAmazon_Model_Api_Iopn
    extends Pwa_PaywithAmazon_Model_Api_Abstract
    implements Pwa_PaywithAmazon_Model_Api_Interface_Iopn {

    protected $_area = 'Amazon IOPN';

    public function newOrderNotification(array $params) {
        $result = $this->_getApiServer()->newOrderNotification($params);
        if ($result->issetProcessedOrder()) {
            return array('code' => 200, 'order' => $result->getProcessedOrder());
        }
        return array('code' => 500, 'message' => 'Internal error');
    }

    public function orderCancelledNotification(array $params) {
        $result = $this->_getApiServer()->orderCancelledNotification($params);
        if ($result->issetProcessedOrder()) {
            return array('code' => 200, 'order' => $result->getProcessedOrder());
        }
        return array('code' => 500, 'message' => 'Internal error');
    }

    public function orderReadyToShipNotification(array $params) {
        $result = $this->_getApiServer()->orderReadyToShipNotification($params);
        if ($result->issetProcessedOrder()) {
            return array('code' => 200, 'order' => $result->getProcessedOrder());                
        }
        return array('code' => 500, 'message' => 'Internal error');
    }

}
