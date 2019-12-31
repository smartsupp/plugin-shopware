<?php

namespace SmartsuppLiveChat\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

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
