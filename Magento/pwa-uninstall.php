<?php
$mageFile = getcwd() . '/app/Mage.php';
require_once($mageFile);
Mage::app();
$resource = Mage::getSingleton('core/resource');

$connection = $resource->getConnection('core_write');

$connection->beginTransaction();
try{
	$__condition = $connection->quoteInto('code=?', 'paywithamazon_setup');
	$connection->delete($resource->getTableName('core_resource'), $__condition);
	$core_config = $connection->quoteInto('path LIKE ?', 'paywithamazon%');
	$connection->delete($resource->getTableName('core_config_data'), $core_config);

	$connection->dropTable($resource->getTableName('amazon_log_exception'));
	$connection->dropTable($resource->getTableName('amazon_log_exception_order'));
	$connection->dropTable($resource->getTableName('amazon_log_feed'));
	$connection->dropTable($resource->getTableName('amazon_log_feed_order'));
	$connection->dropTable($resource->getTableName('amazon_log_iopn'));
	$connection->dropTable($resource->getTableName('amazon_log_iopn_order'));
	$connection->dropTable($resource->getTableName('amazon_log_report'));
	$connection->dropTable($resource->getTableName('amazon_log_report_order'));
	$connection->dropTable($resource->getTableName('amazon_log_api'));
	$connection->dropTable($resource->getTableName('amazon_log_api_order'));
	$connection->dropTable($resource->getTableName('amazon_log_cart_xml'));
	$connection->dropTable($resource->getTableName('amazon_lop_ship'));

	$connection->commit();
	echo "Pay with Amazon extension uninstalled successfully";
	exit;
}
catch(Exception $e){
	$connection->rollback();
	echo "There is error while uninstalling the extension : ". $e->getMessage();	
	echo '<br/>'.$e->__toString();
}
