<?php

namespace SmartsuppLiveChat;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;

class SmartsuppLiveChat extends Plugin
{
    public function activate(ActivateContext $activateContext)
    {
        // on plugin activation clear the cache
        $activateContext->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    public function deactivate(DeactivateContext $deactivateContext)
    {
        // on plugin deactivation clear the cache
        $deactivateContext->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }
}
