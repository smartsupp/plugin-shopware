<?php

namespace SmartsuppLiveChat\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager;

/**
 * Class TemplateRegistration setup template engine parameters.
 * @package SmartsuppLiveChat\Subscriber
 */
class TemplateRegistration implements SubscriberInterface
{
    /**
     * @var string plugin main root path
     */
    private $pluginDirectory;

    /**
     * @var Enlight_Template_Manager template manager object
     */
    private $templateManager;

    /**
     * Class constructor
     *
     * @param string $pluginDirectory plugin main root path
     * @param Enlight_Template_Manager $templateManager template manager object
     */
    public function __construct($pluginDirectory, Enlight_Template_Manager $templateManager)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->templateManager = $templateManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch'
        ];
    }

    /**
     * Setup template directory.
     */
    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }
}
