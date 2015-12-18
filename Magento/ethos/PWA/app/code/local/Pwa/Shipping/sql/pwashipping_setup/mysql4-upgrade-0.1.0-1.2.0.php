<?php
$installer = $this;
$installer->startSetup();
//$installer->addAttribute("order", "easyshipable", array("type"=>"text"));
$installer = new Mage_Sales_Model_Mysql4_Setup;
// for search add same coloum in sales_flat_order_grid
$installer->run("ALTER TABLE  {$this->getTable('sales_flat_order_grid')} ADD COLUMN tfm_shipment_status  text  NULL;");
$installer->endSetup();
?>

