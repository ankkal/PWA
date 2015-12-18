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
 * @copyright  Pay with Amazon
 * @author     Pay with Amazon
 */
$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();

$installer->addAttribute('catalog_product', 'easy_aws_length', array(
    'backend'           => '',
    'default'           => 0,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'group'             => 'General',
    'input'             => 'text',
    "note"              => "Please Insert Only Numeric Value in (cm)",
    'label'             => 'Easy Ship Length(cm)',
    'position'          => 100,
    'required'          => false,
    'source'            => '',
    'type'              => 'int',
    'user_defined'      => false,
    'visible'           => false,
    'visible_on_front'  => false,
));
$installer->addAttribute('catalog_product', 'easy_aws_width', array(
    'backend'           => '',
    'default'           => 0,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'group'             => 'General',
    'input'             => 'text',
    "note"              => "Please Insert Only Numeric Value in (cm)",
    'label'             => 'Easy Ship Width(cm)',
    'position'          => 101,
    'required'          => false,
    'source'            => '',
    'type'              => 'int',
    'user_defined'      => false,
    'visible'           => false,
    'visible_on_front'  => false,
));

$installer->addAttribute('catalog_product', 'easy_aws_height', array(
    'backend'           => '',
    'default'           => 0,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'group'             => 'General',
    'input'             => 'text',
    "note"              => "Please Insert Only Numeric Value in (cm)",
    'label'             => 'Easy Ship Height(cm)',
    'position'          => 103,
    'required'          => false,
    'source'            => '',
    'type'              => 'int',
    'user_defined'      => false,
    'visible'           => false,
    'visible_on_front'  => false,
));
$installer->addAttribute('catalog_product', 'easy_aws_gil',array(

    'type'              => 'varchar',
    'input'             => 'select',
    "note"              => "Please Select A  Value",
    'label'             => 'Easy Ship GL',
    'backend'           => 'eav/entity_attribute_backend_array',
    'frontend'          => '',
    'position'          => 104,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source'            => 'paywithamazon/source_glsource',
    'visible'           => false,
    'required'          => true,
    'group'             => 'General',
    'user_defined'      => false,
)); 
$installer->addAttribute('catalog_product', 'easy_aws_hazmat', array(
    
    'default'           => 0,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'group'             => 'General',
    'input'             => 'select',     
    'label'             => 'Easy Ship Hazmat',
    'position'          => 105,
    'required'          => true,     
    'type'              => 'int',
    'user_defined'      => false,
    'visible'           => false,
    'visible_on_front'  => false,
    'type'              => 'int',             
    'backend'           => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'visible'           => false,
    
     
));
$installer->addAttribute('catalog_product', 'easy_aws_hand_min', array(
    'backend'           => '',
    'default'           => 0,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'group'             => 'General',
    'input'             => 'text',
    "note"              => "Please Insert Only Numeric Value",
    'label'             => 'Easy Ship Handling Time Minimum (in days)',
    'position'          => 106,
    'required'          => false,
    'source'            => '',
    'type'              => 'int',
    'user_defined'      => false,
    'visible'           => false,
    'visible_on_front'  => false,
));
$installer->addAttribute('catalog_product', 'easy_aws_hand_max', array(
    'backend'           => '',
    'default'           => 0,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'group'             => 'General',
    'input'             => 'text',
    "note"              => "Please Insert Only Numeric Value",
    'label'             => 'Easy Ship Handling Time Maximum  (in days)',
    'position'          => 107,
    'required'          => false,
    'source'            => '',
    'type'              => 'int',
    'user_defined'      => false,
    'visible'           => false,
    'visible_on_front'  => false,
));
$installer->endSetup();
    