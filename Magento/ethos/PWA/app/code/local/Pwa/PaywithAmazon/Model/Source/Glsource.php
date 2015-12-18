<?php
class Pwa_PaywithAmazon_Model_Source_Glsource extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        $options  = array(
			''=>'Please Select',
			'Books'=>'Books',
			'Software'=>'Software',
			'Business'=>'Business',
			'Computer Hardware & Accessories'=>'Computer Hardware & Accessories',
			'Apparel, Shoes & Accessories'=>'Apparel, Shoes & Accessories',
			'Sports & Recreation'=>'Sports & Recreation',
			'Hobbies, Toys & Games'=>'Hobbies, Toys & Games',
			'Automotive'=>'Automotive',
			'Home & Garden'=>'Home & Garden',
			'Jewelry & Watches'=>'Jewelry & Watches',
			'Electronics'=>'Electronics',
			'DVD'=>'DVD',
			'Music'=>'Music',
			'Musical Instruments'=>'Musical Instruments',
			'Video'=>'Video',
			'Video Games'=>'Video Games',
			'Camera'=>'Camera',
			'Watch'=>'Watch',
			'Digital Accessories'=>'Digital Accessories',
			'Mobile Electronics'=>'Mobile Electronics',
			'Wireless'=>'Wireless',
			'Shoes'=>'Shoes',
			'Luggage'=>'Luggage',
			'Home Improvement'=>'Home Improvement',
			'Kitchen'=>'Kitchen',
			'Lawn & Garden'=>'Lawn & Garden',
			'Physical Gift Cards'=>'Physical Gift Cards',
			'Email Gift Cards'=>'Email Gift Cards',
        );
 
        return $options;
    }

    public function toOptionArray(){
         return $this->getAllOptions();
	}
}