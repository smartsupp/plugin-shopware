<?php

namespace SmartsuppLiveChat;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;

// require Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Class SmartsuppLiveChat
 * @package SmartsuppLiveChat
 */
class SmartsuppLiveChat extends Plugin
{
    const PLUGIN_NAME = 'SmartsuppLiveChat';

    /**
     * @param ActivateContext $activateContext context object
     */
    public function activate(ActivateContext $activateContext)
    {
        // on plugin activation clear the cache
        $activateContext->scheduleClearCache(ActivateContext::CACHE_LIST_DEFAULT);
    }

    /**
     * @param DeactivateContext $deactivateContext context object
     */
    public function deactivate(DeactivateContext $deactivateContext)
    {
        /** @var ConfigReader $configReader */
        $configReader = $this->container->get('shopware.plugin.config_reader');

        $config = $configReader->getByPluginName(self::PLUGIN_NAME);

        // on plugin deactivation with enabled account clear the cache
        if ($config['active']) {
            $deactivateContext->scheduleClearCache(ActivateContext::CACHE_LIST_DEFAULT);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SmartsuppLiveChat' => 'onGetBackendController'
        ];
    }

    /**
     * @return string path to backend controller
     */
    public function onGetBackendController()
    {
        return __DIR__ . '/Controllers/Backend/SmartsuppLiveChat.php';
    }
}
