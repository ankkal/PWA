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
interface Pwa_PaywithAmazon_Model_Api_Interface_Iopn {

    public function newOrderNotification(array $params);

    public function orderCancelledNotification(array $params);

    public function orderReadyToShipNotification(array $params);

}
