<?php

$installer = $this;
$installer->startSetup();

//$installer->addAttribute("order", "easyshipable", array("type"=>"text"));
$installer = new Mage_Sales_Model_Mysql4_Setup;
$attribute  = array(
        'type'          => 'text',
        'backend_type'  => 'text',
        'frontend_input' => 'text',
        'is_user_defined' => true,
        'label'         => 'Shippable By Amazon',
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,   
        'searchable'    => false,
        'filterable'    => false,
        'comparable'    => false,
        'default'       => ''
);
$installer->addAttribute('order', 'easyshipable', $attribute);

$attribute2  = array(
        'type'          => 'text',
        'backend_type'  => 'text',
        'frontend_input' => 'text',
        'is_user_defined' => true,
        'label'         => 'TFM Shipment Status',
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,   
        'searchable'    => false,
        'filterable'    => false,
        'comparable'    => false,
        'default'       => ''
);
$installer->addAttribute('order', 'tfm_shipment_status', $attribute2);

$installer->run(
"CREATE TABLE IF NOT EXISTS `amazon_easyship_cron` (
  `entity_id` int(11) NOT NULL AUTO_INCREMENT,
  `last_executed_on` varchar(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

$installer->endSetup();


?>

