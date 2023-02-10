<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Plugin;

use Creatuity\OrderStatusAdjust\Model\OrderStatusAdjust;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;

class OrderStatusPlugin
{
    public function __construct(
        private readonly OrderStatusAdjust $orderStatusAdjust
    ) {
    }

    /**
     * @param Order $subject
     * @param callable $proceed
     * @param string|null $status
     * @return OrderInterface
     */
    public function aroundSetStatus(Order $subject, callable $proceed, ?string $status): OrderInterface
    {
        if ($this->orderStatusAdjust->execute($subject)) {
            return $subject;
        }

        return $proceed($status);
    }
}
