<?php

class Pwa_Shipping_Helper_Data extends
    Mage_Core_Helper_Abstract
{
    const XML_EXPRESS_MAX_WEIGHT = 'carriers/inchoo_shipping/express_max_weight';

    /**
     * Get max weight of single item for express shipping
     *
     * @return mixed
     */
    public function getExpressMaxWeight()
    {
        return Mage::getStoreConfig(self::XML_EXPRESS_MAX_WEIGHT);
    }
    
    // Get Read/Write Connection
    public function getWriteConnection()
    {
        $writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');        
        return $writeConnection;        
    }
    public function getreadConnection()
    {
        $readConnection = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read');
        return $readConnection;        
    }
    // Get Last Execution of cron
    public function getLastCronExecution()
    {
        $readConnection = $this->getreadConnection();
        $query="SELECT `last_executed_on` from `amazon_easyship_cron` ORDER BY entity_id DESC LIMIT 1 ";
        $result = $readConnection->query( $query );
        $last_executed_on=null;
        while ($row = $result->fetch() ) {
            $last_executed_on=$row['last_executed_on'];
        }
        return $last_executed_on;
    }
    // Update Last Execution of cron
    public function updateLastCronExecution()
    {
        $now=date('Y-m-d H:i:s');
        $writeConnection = $this->getWriteConnection();
        
        $query="INSERT INTO `amazon_easyship_cron`( `last_executed_on`) VALUES ('".$now."')";
        $result = $writeConnection->query( $query );        
        
    }
    public function tfmShipmentOptions(){
        return  array(
                    ''                  => 'Status Not Available',
                    '0'                 => 'Not Easy Shippable',
                    '1'                 => 'PickUp Not scheduled Yet',
                    'PendingPickUp'     => 'Pending Pick Up',
                    'LabelCanceled'     => 'Label Canceled',
                    'PickUp'            => 'Pick Up',
                    'AtDestinationFC'   => 'At Destination FC',
                    'Delivered'         => 'Delivered',
                    'RejectedByBuyer'   => 'Rejected By Buyer',
                    'Undeliverable'     => 'Undeliverable',
                    'ReturendToSeller'  => 'Returend To Seller',
                    'Lost'              => 'Lost',
                    'OutForDelivery'    => 'Out For Delivery',
                ); 
    }
}
