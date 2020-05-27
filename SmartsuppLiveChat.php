<?php

namespace SmartsuppLiveChat;

use Enlight_Components_Db_Adapter_Pdo_Mysql;
use Shopware\Bundle\CookieBundle\CookieCollection;
use Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct;
use Shopware\Bundle\CookieBundle\Structs\CookieStruct;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\UninstallContext;

// require Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Class SmartsuppLiveChat
 * @package SmartsuppLiveChat
 */
class SmartsuppLiveChat extends Plugin
{
    /**
     * @var Enlight_Components_Db_Adapter_Pdo_Mysql
     */
    protected $db;

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
        // on plugin deactivation with enabled account clear the cache
        if ($this->isPluginActivated()) {
            $deactivateContext->scheduleClearCache([DeactivateContext::CACHE_TAG_TEMPLATE, DeactivateContext::CACHE_TAG_ROUTER, DeactivateContext::CACHE_TAG_PROXY,]);
        }
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        // remove only cache when is plugin activated (Smartsupp account set) and not deactivated in Shopware
        if ($this->isPluginActivated() && $this->isActive()) {
            $context->scheduleClearCache([DeactivateContext::CACHE_TAG_TEMPLATE, DeactivateContext::CACHE_TAG_CONFIG]);
        }

        $this->removeSnippetsData();
    }

    /**
     * Remove snippets automatically added by Shopware from database.
     */
    protected function removeSnippetsData()
    {
        // remove all the data in plugin namespace from s_core_snippets table
        /** @var Enlight_Components_Db_Adapter_Pdo_Mysql $db */
        $db = Shopware()->Container()->get('db');
        $pluginNamespace = self::PLUGIN_NAME . '/';

        $db->delete('s_core_snippets', "name LIKE \"%{$pluginNamespace}%\"");
    }

    /**
     * Register cookies
     *
     * @return CookieCollection|void
     */
    public function addComfortCookie()
    {
        // just to be safe as for older Shopware versions those classes does not exist
        if (!class_exists('\Shopware\Bundle\CookieBundle\CookieCollection')) {
            return;
        }

        // needs to put here full class path to not break in older releases without Cookie bundle
        $collection = new CookieCollection();

        // add only if plugin is assigned with Smartsupp account
        if ($this->isPluginActivated()) {
            $collection->add(new CookieStruct(
                'ssupp',
                '/^ssupp$/',
                'Smartsupp Livechat',
                CookieGroupStruct::COMFORT
            ));
        }

        return $collection;
    }

    /**
     * Check if plugin was assigned with Smartsupp account.
     *
     * @return boolean
     */
    protected function isPluginActivated()
    {
        /** @var ConfigReader $configReader */
        $configReader = $this->container->get('shopware.plugin.config_reader');
        $config = $configReader->getByPluginName(self::PLUGIN_NAME);

        return $config['active'];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SmartsuppLiveChat' => 'onGetBackendController',
            'CookieCollector_Collect_Cookies' => 'addComfortCookie',
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
