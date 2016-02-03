<?php 
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();
$currentStore = Mage::app()->getStore();
$stores = Mage::app()->getStores(false, false);

foreach ($stores as $store) {
    Mage::app()->setCurrentStore($store); 
    $nextToken = null;
    do {
        $nextToken = Mage::getModel('paywithamazon/manager')->retrieveAndHandleReportList($nextToken);
    } while ($nextToken);  
}
Mage::app()->setCurrentStore($currentStore);
?>         