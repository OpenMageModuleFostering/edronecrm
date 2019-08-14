<?php

class Edrone_Base_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function validateToken($value, $token)
    {
        $helper = Mage::helper('edrone/config');
        $appId = $helper->getAppId();
        $appSecret = $helper->getAppSecret();

        $hash = hash('sha256', $value.$appId.$appSecret);

        if($hash == $token) return true;

        return false;
    }
    
    /**
     * 
     * @return string
     */
    public function utcNow() {
        $t = microtime(true);
        $micro = sprintf("%03d", ($t - floor($t)) * 1000000);
        return gmdate('Y-m-d\TH:i:s.', $t) . $micro . 'Z';
    }
}