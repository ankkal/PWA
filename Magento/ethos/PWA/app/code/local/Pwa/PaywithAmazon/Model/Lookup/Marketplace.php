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
class Pwa_PaywithAmazon_Model_Lookup_Marketplace extends Pwa_PaywithAmazon_Model_Lookup_Abstract {

    protected
        $_availableMarketplaces = array('hi_IN');

    public function toOptionArray() {
        if (null === $this->_options) {
            $localeModel = Mage::getSingleton('core/locale');
            $languages = $localeModel->getLocale()->getTranslationList('language', $localeModel->getLocale());
            $countries = $localeModel->getCountryTranslationList();
            unset($localeModel);
            $this->_options = array();
            foreach ($this->_availableMarketplaces as $marketplace) {
                if (strstr($marketplace, '_')) {
                    $explodedLocale = explode('_', $marketplace);
                    if (isset($languages[$explodedLocale[0]]) && isset($countries[$explodedLocale[1]])) {
                        $this->_options[] = array(
                            'value' => $marketplace,
                            'label' => ucwords($languages[$explodedLocale[0]]) . ' (' . $countries[$explodedLocale[1]] . ')'
                        );
                    }
                }
            }
        }
        return $this->_options;
    }
}
