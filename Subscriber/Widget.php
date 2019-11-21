<?php
namespace SmartsuppLiveChat\Subscriber;

use Exception;
use Enlight_Controller_Action;
use Enlight_Controller_ActionEventArgs;
use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\ConfigReader;
use SmartsuppLiveChat\Components\ChatGenerator;

/**
 * Class Widget
 * @package SmartsuppLiveChat\Subscriber
 */
class Widget implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;
    /**
     * @var array
     */
    private $config;

    /**
     * @var ChatGenerator
     */
    protected $chatGenerator;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch'
        ];
    }

    /**
     * Widget constructor.
     * @param $pluginName
     * @param $pluginDirectory
     * @param ConfigReader $configReader
     * @param ChatGenerator $chatGenerator
     */
    public function __construct($pluginName, $pluginDirectory, ConfigReader $configReader, ChatGenerator $chatGenerator)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->config = $configReader->getByPluginName($pluginName);
        $this->chatGenerator = $chatGenerator;
    }

    /**
     * @param Enlight_Controller_ActionEventArgs $args
     * @throws Exception
     */
    public function onPostDispatch(Enlight_Controller_ActionEventArgs $args)
    {
        /** @var Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->assign('smartsupp_js', $this->chatGenerator->generateJS('3b3f4ef0a3fe414c5d68ae5758eb23334eebcd7e'));
    }
}
