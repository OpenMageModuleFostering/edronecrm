<?php

class Edrone_Base_Helper_Config extends Mage_Core_Helper_Abstract
{

    const APP_ID_CONFIG_PATH = "edrone/base/app_id";
    const APP_SECRET_CONFIG_PATH = "edrone/base/app_secret";
    const EXTERNAL_SCRIPT_URL_CONFIG_PATH = "edrone/base/external_script_url";
    const COLLECTOR_URL_CONFIG_PATH = "edrone/base/collector_url";
    const NEWSLETTER_SYNC_ENABLED_PATH = "edrone/newsletter/subscription_sync_enabled";

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
    public function getAppSecret()
    {
        return (string)Mage::getStoreConfig(self::APP_SECRET_CONFIG_PATH);
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

    /**
     * @return bool
     */
    public function isNewsletterSyncEnabled()
    {
        return Mage::getStoreConfig(self::NEWSLETTER_SYNC_ENABLED_PATH);
    }
}