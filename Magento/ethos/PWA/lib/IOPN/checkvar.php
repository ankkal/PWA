<?php
echo $accessKeyID=Mage::helper('paywithamazon')->getConfigData('access_key_id');
   echo $secretKey=Mage::getStoreConfig('paywithamazon/general/secret_key');
echo "inside";
exit;


 ?>
