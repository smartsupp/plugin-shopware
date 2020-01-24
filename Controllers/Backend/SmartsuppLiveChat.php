<?php

use Shopware\Bundle\PluginInstallerBundle\Service\InstallerService;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Components\Plugin\ConfigWriter;
use Shopware\Components\ShopwareReleaseStruct;
use Shopware\Models\Shop\Repository;
use Shopware\Models\Shop\Shop;
use Smartsupp\Auth\Api;
use SmartsuppLiveChat\SmartsuppLiveChat;

/**
 * Class Shopware_Controllers_Backend_SmartsuppLiveChat implements logic for backend controller.
 */
class Shopware_Controllers_Backend_SmartsuppLiveChat extends Enlight_Controller_Action implements CSRFWhitelistAware
{
    /**
     * Smartsupp partner key for Shopware platform
     */
    const PARNER_KEY = 'eh1dyc2sz8';

    /**
     * The only controller action which handles all AJAX JS calls.
     *
     * @throws Exception if curl_init or json_decode is not present - required by Smartsupp auth library
     */
    public function indexAction()
    {
        $formAction = $message = null;

        $ssaction = $this->Request()->getParam('ssaction');
        $email = $this->Request()->getParam('email');
        $password = $this->Request()->getParam('password');
        $code = $this->Request()->getParam('code');
        $termsConsent = $this->Request()->getParam('termsConsent');

        switch ($ssaction) {
            case 'login':
            case 'register':
                $formAction = $ssaction;
                $api = new Api;
                $data = [
                    'email' => $email,
                    'password' => $password,
                    'consentTerms' => 1,
                    'platform' => 'Shopware ' . $this->getShopwareVersion(),
                    'partnerKey' => self::PARNER_KEY,
                ];
                try {
                    $response = $formAction === 'login' ? $api->login($data) : $api->create($data + ['lang' => 'en']);

                    if (isset($response['error'])) {
                        $message = $response['message'];
                    } else {
                        $this->activate($response['account']['key'], $email);
                    }
                } catch (Exception $e) {
                    $message = $e->getMessage();
                }
                break;
            case 'update':
                $message = 'Custom code was updated.';
                $this->updateOptions([
                    'optionalCode' => (string) $code,
                ]);
                break;
            case 'disable':
                $this->deactivate();
                break;
            /* no default clause. If there is some not existing action just do nothing  */
        }

        $this->View()->assign([
            'formAction' => $formAction,
            'message' => $message,
            'email' => $email ?: $this->_getOption('email'),
            'active' => (bool) $this->_getOption('active', false),
            'optionalCode' => $this->_getOption('optionalCode'),
            'termsConsent' => $termsConsent,
        ]);
    }

    /**
     * Will activate chat and set key and email to plugin config.
     *
     * @param string $chatId Smartsupp chat key
     * @param string $email user email
     *
     * @throws Exception
     */
    private function activate($chatId, $email)
    {
        $this->updateOptions([
            'active' => 1,
            'chatId' => (string) $chatId,
            'email' => (string) $email,
        ]);
    }

    /**
     * Set deactivated into config and clear information.
     *
     * @throws Exception
     */
    private function deactivate()
    {
        $this->updateOptions([
            'active' => 0,
            'chatId' => null,
            'email' => null
        ]);
    }

    /**
     * Helper method to update plugin config options.
     *
     * @param array $options plugin config options
     * @throws Exception if some DB layer error raised (Shop ID not found), should never happen
     */
    private function updateOptions(array $options)
    {
        /** @var ConfigWriter $configWriter */
        $configWriter = $this->container->get('shopware.plugin.config_writer');
        /** @var InstallerService $pluginManager */
        $pluginManager = $this->container->get('shopware_plugininstaller.plugin_manager');

        // get plugin by name
        $plugin = $pluginManager->getPluginByName(SmartsuppLiveChat::PLUGIN_NAME);

        /** @var Repository $shopRepository */
        $shopRepository = $this->get('models')->getRepository(Shop::class);

        /** @var Shop $shop */
        $shop = $shopRepository->find($shopRepository->getActiveDefault()->getId());

        $configWriter->savePluginConfig($plugin, $options, $shop);
    }

    /**
     * Get option from Shopware Smartsupp extension config.
     *
     * @param string $name option name
     * @param ?string $default default value
     *
     * @return string
     */
    private function _getOption($name, $default = null)
    {
        /** @var ConfigReader $configReader */
        $configReader = $this->container->get('shopware.plugin.config_reader');

        $config = $configReader->getByPluginName(SmartsuppLiveChat::PLUGIN_NAME);

        // may happen that index is not set
        if (!isset($config[$name])) {
            return $default;
        }

        return $config[$name];
    }

    /**
     * Obtain e-shop platform version.
     *
     * @return string version string
     */
    private function getShopwareVersion()
    {
        /** @var ShopwareReleaseStruct $release */
        $release = $this->container->get('shopware.release');

        return $release->getVersion();
    }

    /**
     * Set initial CSRF token.
     */
    public function postDispatch()
    {
        $csrfToken = $this->container->get('BackendSession')->offsetGet('X-CSRF-Token');
        $this->View()->assign([ 'csrfToken' => $csrfToken ]);
    }

    /**
     * Obtain CSRF whitelisted actions.
     *
     * @return array|string[]
     */
    public function getWhitelistedCSRFActions()
    {
        return ['index'];
    }
}
