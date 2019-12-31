<?php

use Shopware\Components\CSRFWhitelistAware;


/**
 * Class Shopware_Controllers_Backend_SmartsuppLiveChat
 */
class Shopware_Controllers_Backend_SmartsuppLiveChat extends Enlight_Controller_Action implements CSRFWhitelistAware {

    public function indexAction()
    {
    }

    public function postDispatch()
    {
        $csrfToken = $this->container->get('BackendSession')->offsetGet('X-CSRF-Token');
        $this->View()->assign([ 'csrfToken' => $csrfToken ]);
    }

    public function getWhitelistedCSRFActions()
    {
        return ['index'];
    }
}