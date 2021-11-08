<?php

namespace Supp\SmartsuppLiveChat\Subscriber;

use Supp\SmartsuppLiveChat\Struct\ConfigStruct;
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
        $config = $this->systemConfigService->get('SuppSmartsuppLiveChat.config');

        if (!$config) {
            return;
        }
        
        $smartsuppConfiguration = new ConfigStruct();
        $smartsuppConfiguration->setKey($config['smartSuppChatKey']);
        $page->addExtension('smartsuppConfiguration', $smartsuppConfiguration);

    }
}
