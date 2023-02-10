<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Processor;

use Creatuity\OrderStatusAdjust\Model\Container\CalculatedOrderContainer;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;

class DefaultProcessor implements OrderStatusProcessorInterface
{
    public function execute(CalculatedOrderContainer $container, OrderInterface $order): bool
    {
        if ($container->getRefundedQty() > 0) {
            if ($container->getRefundedQty() === $container->getInvoicedQty()) {
                return $this->setStateAndStatus($order, Order::STATE_CLOSED, 'closed');
            } else {
                return $this->setStateAndStatus($order, Order::STATE_COMPLETE, 'complete');
            }
        }

        if ($container->getCancelledQty() > 0) {
            if ($container->getCancelledQty() === $container->getOrderedQty()) {
                return $this->setStateAndStatus($order, Order::STATE_CANCELED, 'canceled');
            } else {
                return $this->setStateAndStatus($order, Order::STATE_COMPLETE, 'complete');
            }
        }

        if ($container->getInvoicedQty() > 0) {
            return $this->setStateAndStatus($order, Order::STATE_COMPLETE, 'complete');
        }

        return false;
    }

    private function setStateAndStatus(OrderInterface $order, string $state, string $status): bool
    {
        /** @phpstan-ignore-next-line */
        $order->setData('state', $state);
        /** @phpstan-ignore-next-line */
        $order->setData('status', $status);

        return true;
    }
}
