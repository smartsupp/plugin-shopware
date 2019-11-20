<?php

class Shopware_Plugins_Frontend_SmartsuppLiveChat_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Register the Composer autoloader
     */
    public function afterInit()
    {
        require_once $this->Path() . '/vendor/autoload.php';
    }
}