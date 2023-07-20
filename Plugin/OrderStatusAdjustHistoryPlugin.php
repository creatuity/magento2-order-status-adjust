<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Plugin;

use Creatuity\OrderStatusAdjust\Model\OrderStatusAdjustHistory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderRepository;

class OrderStatusAdjustHistoryPlugin
{
    public function __construct(
        private readonly OrderStatusAdjustHistory $orderStatusAdjustHistory,
    ) {
    }

    /**
     * @param OrderRepository $subject
     * @param OrderInterface $result
     * @param OrderInterface $entity
     * @return OrderInterface
     */
    public function afterSave(OrderRepository $subject, OrderInterface $result, OrderInterface $entity): OrderInterface
    {
        $this->orderStatusAdjustHistory->execute($result);

        return $result;
    }
}
