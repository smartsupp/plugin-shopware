<?php

namespace SmartsuppLiveChat\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

/**
 * Class MenuItem implements subscriber event to put Smartsupp settings page link together with
 * icon into admin menu
 * @package SmartsuppLiveChat\Subscriber
 */
class MenuItem implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Index' => 'setupMenuIcon'
        ];
    }

    /**
     * Render template to add custom CSS class for menu item to include Smartsupp icon.
     *
     * @param Enlight_Event_EventArgs $args
     */
    public function setupMenuIcon(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->getSubject();
        $view = $controller->View();

        if ($view->hasTemplate()) {
            $view->extendsTemplate('backend/menuitem.tpl');
        }
    }
}
