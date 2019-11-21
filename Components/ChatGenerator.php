<?php

namespace SmartsuppLiveChat\Components;

use Shopware\Components\Model\ModelManager;
use Shopware\Models\Customer\Customer;
use Shopware\Models\Order\Order;
use Shopware\Models\Order\Status;

/**
 * Class ChatGenerator
 * @package SmartsuppLiveChat\Components
 */
class ChatGenerator
{
    /**
     * @var ModelManager $modelManager
     */
    protected $modelManager;

    /**
     * ChatGenerator constructor.
     * @param ModelManager $modelManager
     */
    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
     * @var string key of customer ID in session data
     */
    const CUSTOMER_ID_KEY = 'sUserId';

    /**
     * @param $key
     * @return string
     * @throws \Exception
     */
    public function generateJS($key)
    {
        $chat = new \Smartsupp\ChatGenerator($key);

        // get logged in user ID from session
        $userId = Shopware()->Session()->get(self::CUSTOMER_ID_KEY);

        if ($userId) {
            /** @var Customer $user */
            $user = $this->modelManager->find('Shopware\\Models\\Customer\\Customer', $userId);
            $this->populateChatWithUserData($chat, $user);
        }

        return $chat->render();
    }

    /**
     * @param \Smartsupp\ChatGenerator $chat
     * @param Customer $user
     */
    protected function populateChatWithUserData(\Smartsupp\ChatGenerator $chat, Customer $user)
    {
        $fullName = $user->getFirstname() . ' ' . $user->getLastname();

        $chat->setVariable('id', 'ID', $user->getId());
        $chat->setVariable('name', 'Name', $fullName);
        $chat->setVariable('email', 'Email', $user->getEmail());

        $shippingAddress = $user->getDefaultShippingAddress();

        if ($shippingAddress) {
            $chat->setVariable('phone', 'Phone', $shippingAddress->getPhone());
        }

        $orderPrice = 0;
        $orderCount = 0;

        /** @var Order[] $order */
        foreach ($user->getOrders() as $order) {
            /** @var Status $status */
            $status = $order->getOrderStatus();

            // just count with completed orders
            if (in_array($status, $this->getCompletedOrderStates())) {
                $orderPrice += $order->getInvoiceAmount();
                $orderCount++;
            }
        }

        $chat->setVariable('spending', 'Spending', $orderPrice);
        $chat->setVariable('orders', 'Orders', $orderCount);
    }

    /**
     * @return array
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
