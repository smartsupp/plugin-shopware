<?php


namespace Smartsupp\SmartsuppChat\Framework\Cookie;


use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Cookie\CookieProviderInterface;


class SmartsuppCookieProvider implements CookieProviderInterface
{

    private const smartsuppCookies = [
        'snippet_name' => 'Smartsupp Chat',
        'isRequired' => false,
        'snippet_description' => 'Allow functional cookies to show up the live chat widget. You can read everything about Smartsupp cookies here: <a target="_blank" href="https://www.smartsupp.com/help/privacy/">smartsupp.com/help/privacy</a>',
        'entries' => [
            [
                'snippet_name' => 'Functional cookies',
                'cookie' => 'cookieSmartsuppFunctional',
                'value'=> 1,
                'expiration' => '60'
            ],
            [
                'snippet_name' => 'Analytical cookies',
                'cookie' => 'cookieSmartsuppStatistics',
                'value'=> 1,
                'expiration' => '60'
            ],
            [
                'snippet_name' => 'Marketing cookies',
                'cookie' => 'cookieSmartsuppMarketing',
                'value'=> 1,
                'expiration' => '60'
            ]
        ],
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
        if (!$this->systemConfigService->get('SmartsuppChat.config.hasSWConsentSupport') || !$this->systemConfigService->get('SmartsuppChat.config.smartSuppChatKey')) {
            return $this->originalService->getCookieGroups();
        }

        return $this->addEntryToCookieGroup();
    }

    private function addEntryToCookieGroup(): array
    {
        $cookieGroups = $this->originalService->getCookieGroups();

        foreach ($cookieGroups as &$group) {
            if ($group['snippet_name'] !== 'Smartsupp Chat') {
                continue;
            }
            
            $group['entries'] = array_merge($group['entries'], [self::smartsuppCookies]);

            return $cookieGroups;
        }

        return array_merge($cookieGroups, [self::smartsuppCookies]);
    }
}
