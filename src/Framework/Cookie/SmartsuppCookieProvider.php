<?php

namespace Supp\SmartsuppLiveChat\Framework\Cookie;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;

class SmartsuppCookieProvider implements CookieProviderInterface
{

    private const smartsuppCookies = [
        'snippet_name' => 'SuppSmartsuppLiveChat.cookie.name',
        'snippet_description' => 'SuppSmartsuppLiveChat.cookie.description',
        'value' => '1',
        'expiration' => '90',
        'cookie' => 'ssupp.consent',
    ];

    /**
     * @var CookieProviderInterface
     */
    private $originalService;

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    public function __construct(
        CookieProviderInterface $service,
        SystemConfigService $systemConfigService
    ) {
        $this->originalService = $service;
        $this->systemConfigService = $systemConfigService;
    }

    public function getCookieGroups(): array
    {
        if (!$this->systemConfigService->get('SuppSmartsuppLiveChat.config.hasSWConsentSupport')) {
            return $this->originalService->getCookieGroups();
        }

        return $this->addEntryToCookieGroup();
    }

    protected function addEntryToCookieGroup(): array
    {
        $cookieGroups = $this->originalService->getCookieGroups();

        foreach ($cookieGroups as &$group) {
            if ($group['snippet_name'] !== 'ssupp.consent') {
                continue;
            }
            
            $group['entries'] = array_merge($group['entries'], [self::smartsuppCookies]);

            return $cookieGroups;
        }

        return array_merge($cookieGroups, [self::smartsuppCookies]);
    }
}
