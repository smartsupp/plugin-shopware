<?php

namespace SmartsuppLiveChat\Components;

use Exception;
use Shopware\Components\ShopwareReleaseStruct;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Customer\Customer;
use Shopware\Models\Order\Order;
use Shopware\Models\Order\Status;

/**
 * Class ChatGenerator is helper class over Smartsupp ChatGenerator library.
 * @package SmartsuppLiveChat\Components
 */
class ChatGenerator
{
    /**
     * @var string key of customer ID in session data
     */
    const CUSTOMER_ID_KEY = 'sUserId';

    /**
     * @var ModelManager $modelManager model manager to access database layer
     */
    protected $modelManager;

    /**
     * @var ShopwareReleaseStruct $shopwareVersion Shopware release version information
     */
    protected $shopwareVersion;

    /**
     * ChatGenerator constructor.
     * @param ModelManager $modelManager model manager to access database layer
     * @param ShopwareReleaseStruct $shopwareVersion Shopware release version information
     */
    public function __construct(ModelManager $modelManager, ShopwareReleaseStruct $shopwareVersion)
    {
        $this->modelManager = $modelManager;
        $this->shopwareVersion = $shopwareVersion;
    }

    /**
     * Generate Smartsupp chat JS code upon given parameters and obtained user information.
     *
     * @param string $key Smartsupp key
     * @param bool $async to load async
     * @return string chat JS code
     * @throws Exception
     */
    public function generateJS($key, $async = true)
    {
        $chat = new \Smartsupp\ChatGenerator($key);
        $chat->setPlatform('Shopware ' . $this->shopwareVersion->getVersion());

        // get logged in user ID from session
        $userId = Shopware()->Session()->get(self::CUSTOMER_ID_KEY);

        if ($userId) {
            try {
                /** @var Customer $user */
                $user = $this->modelManager->find('Shopware\\Models\\Customer\\Customer', $userId);
                $this->populateChatWithUserData($chat, $user);
            } catch (Exception $e) { } // in case when user with given ID not found, should never happen
        }
        // set if to load async after some delay
        $chat->setAsync($async);
        // may throw exception when key is not set, this should not occur if this class is used properly
        return $chat->render();
    }

    /**
     * Set logged in user information (if any) to Smartsupp JS code.
     *
     * @param \Smartsupp\ChatGenerator $chat
     * @param Customer $user customer database object
     */
    protected function populateChatWithUserData(\Smartsupp\ChatGenerator $chat, Customer $user)
    {
        $fullName = $user->getFirstname() . ' ' . $user->getLastname();

        $chat->setVariable('id', 'ID', $user->getId());
        $chat->setVariable('name', 'Name', $fullName);
        $chat->setVariable('email', 'Email', $user->getEmail());

        // may be null if no orders so far
        $shippingAddress = $user->getDefaultShippingAddress();

        if ($shippingAddress) {
            $chat->setVariable('phone', 'Phone', $shippingAddress->getPhone());
        }

        $orderPrice = 0;
        $orderCount = 0;

        /** @var Order $order */
        foreach ($user->getOrders() as $order) {
            /** @var Status $status */
            $status = $order->getOrderStatus();

            // just count with completed orders
            if (in_array($status, $this->getCompletedOrderStates())) {
                $orderPrice += $order->getInvoiceAmount();
                $orderCount++;
            }
        }

        // set order information, even when no orders those values are set to 0
        $chat->setVariable('spending', 'Spending', $orderPrice);
        $chat->setVariable('orders', 'Orders', $orderCount);
    }

    /**
     * Obtain a list of order states where it can be stated that order is tracked by Smartsupp JS
     * as completed.
     *
     * @return array list of order states we take as finished order
     */
    protected function getCompletedOrderStates()
    {
        return array(
            Status::ORDER_STATE_OPEN,
            Status::ORDER_STATE_IN_PROCESS,
            Status::ORDER_STATE_COMPLETED,
            Status::ORDER_STATE_COMPLETELY_DELIVERED,
            Status::ORDER_STATE_READY_FOR_DELIVERY,
            Status::ORDER_STATE_PARTIALLY_COMPLETED,
            Status::ORDER_STATE_PARTIALLY_DELIVERED,
        );
    }
}
