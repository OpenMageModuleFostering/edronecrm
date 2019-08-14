<?php

class Edrone_Base_Helper_Config extends Mage_Core_Helper_Abstract
{
    const APP_ID_CONFIG_PATH = "edrone/base/app_id";
    const EXTERNAL_SCRIPT_URL_CONFIG_PATH = "edrone/base/external_script_url";
    const COLLECTOR_URL_CONFIG_PATH = "edrone/base/collector_url";

    /**
     * @return string
     */
    public function getAppId()
    {
        return (string)Mage::getStoreConfig(self::APP_ID_CONFIG_PATH);
    }

    /**
     * @return string
     */
    public function getExternalScriptUrl()
    {
        return (string)Mage::getStoreConfig(self::EXTERNAL_SCRIPT_URL_CONFIG_PATH);
    }

    /**
     * @return string
     */
    public function getCollectorUrl()
    {
        return (string)Mage::getStoreConfig(self::COLLECTOR_URL_CONFIG_PATH);
    }
}