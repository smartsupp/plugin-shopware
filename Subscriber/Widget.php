<?php
namespace SmartsuppLiveChat\Subscriber;

use Exception;
use Enlight_Controller_Action;
use Enlight_Controller_ActionEventArgs;
use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\ConfigReader;
use SmartsuppLiveChat\Components\ChatGenerator;

/**
 * Class Widget implements Smartsupp front end widget logic and rendering.
 * @package SmartsuppLiveChat\Subscriber
 */
class Widget implements SubscriberInterface
{
    /**
     * @var array plugin config array
     */
    private $config;

    /**
     * @var ChatGenerator Smartsupp chat generator helper object
     */
    protected $chatGenerator;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch'
        ];
    }

    /**
     * Widget constructor.
     * @param string $pluginName this plugin name
     * @param ConfigReader $configReader config reader helper object
     * @param ChatGenerator $chatGenerator Smartsupp chat generator helper object
     */
    public function __construct($pluginName, ConfigReader $configReader, ChatGenerator $chatGenerator)
    {
        $this->config = $configReader->getByPluginName($pluginName);
        $this->chatGenerator = $chatGenerator;
    }

    /**
     * Add widget on post dispatch event.
     * @param Enlight_Controller_ActionEventArgs $args event arguments object
     * @throws Exception
     */
    public function onPostDispatch(Enlight_Controller_ActionEventArgs $args)
    {
        /** @var Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        // obtain config values or set default ones
        $active = $this->config['active'] ?? false;
        $smartsuppKey = $this->config['chatId'] ?? null;
        $optionalCode = $this->config['optionalCode'] ?? null;
        $widgetJs = null;

        // only render if chat is set as active
        if ($active) {
            // use chat generator helper object to process and add customer data (if any)
            $widgetJs = $this->chatGenerator->generateJS($smartsuppKey);

            // if optional code exists (not set in most cases) add it as well as separate SCRIPT tag
            if ($optionalCode) {
                $widgetJs .= '<script>' . stripcslashes($optionalCode) . '</script>';
            }
        }

        $view->assign('smartsupp_js', $widgetJs);
    }
}
