<?php

namespace Smartsupp\SmartsuppChat\Subscriber;

use Smartsupp\SmartsuppChat\Struct\ConfigStruct;
use Shopware\Core\Content\Cms\Events\CmsPageLoadedEvent;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\GenericPageLoadedEvent;
use Shopware\Storefront\Page\PageLoadedEvent;
use Shopware\Storefront\Pagelet\Header\HeaderPageletLoadedEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StorefrontSubscriber implements EventSubscriberInterface
{
    private $systemConfigService;

    public function __construct(SystemConfigService $systemConfigService){
        $this->systemConfigService = $systemConfigService;
    }

    public static function getSubscribedEvents()
    {
        return [
            GenericPageLoadedEvent::class => 'onPageLoaded',
        ];
    }

    public function onPageLoaded(GenericPageLoadedEvent $loadedEvent){

        $page = $loadedEvent->getPage();
        $config = $this->systemConfigService->get('SmartsuppChat.config');
        $chatKey = $this->systemConfigService->get('SmartsuppChat.config.smartSuppChatKey');
        $chatColor = $this->systemConfigService->get('SmartsuppChat.config.smartSuppColor');
        $chatConfig = $this->systemConfigService->get('SmartsuppChat.config.smartSuppConfig');
        $chatApi = $this->systemConfigService->get('SmartsuppChat.config.smartSuppAPI');

        if (!$config) {
            return;
        }
        
        if ($chatKey) {
            $smartsuppConfiguration = new ConfigStruct();
            $smartsuppConfiguration->setKey($chatKey);
            $page->addExtension('smartsuppConfiguration', $smartsuppConfiguration);
        }
        
        $smartsuppCookies = new ConfigStruct();
        $smartsuppCookies->setKey($config['hasSWConsentSupport']);
        $page->addExtension('smartsuppCookies', $smartsuppCookies);

        if ($chatColor) {
            $smartsuppColor = new ConfigStruct();
            $smartsuppColor->setKey($chatColor);
            $page->addExtension('smartsuppColor', $smartsuppColor);
        }

        if ($chatConfig) {
            $smartsuppConfig = new ConfigStruct();
            $smartsuppConfig->setKey($chatConfig);
            $page->addExtension('smartsuppConfig', $smartsuppConfig);
        }

        if ($chatApi) {
            $smartsuppApi = new ConfigStruct();
            $smartsuppApi->setKey($chatApi);
            $page->addExtension('smartsuppApi', $smartsuppApi);
        }
    }
}