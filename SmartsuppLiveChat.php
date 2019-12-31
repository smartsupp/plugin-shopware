<?php

namespace SmartsuppLiveChat;

use Shopware\Components\Plugin;
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
    /**
     * @param ActivateContext $activateContext
     */
    public function activate(ActivateContext $activateContext)
    {
        // on plugin activation clear the cache
        $activateContext->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    /**
     * @param DeactivateContext $deactivateContext
     */
    public function deactivate(DeactivateContext $deactivateContext)
    {
        // on plugin deactivation clear the cache
        $deactivateContext->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SmartsuppLiveChat' => 'onGetBackendController'
        ];
    }

    /**
     * @return string
     */
    public function onGetBackendController()
    {
        return __DIR__ . '/Controllers/Backend/SmartsuppLiveChat.php';
    }
}
