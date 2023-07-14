<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Creatuity\OrderStatusAdjust\Model\Config\OrderStatusAdjustConfig;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderStatusHistoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status\HistoryFactory;

class OrderStatusAdjustHistory
{
    public function __construct(
        private readonly OrderStatusAdjustConfig $orderStatusAdjustConfig,
        private readonly OrderStatusAdjusted $orderStatusAdjusted,
        private readonly HistoryFactory $historyFactory
    ) {
    }

    public function execute(OrderInterface $order): void
    {
        if ($this->orderStatusAdjustConfig->isEnableOrderComment()) {
            if ($this->orderStatusAdjusted->getComment() !== null) {
                return;
            }

            $order->setStatusHistories(array_merge($order->getStatusHistories(), [$this->createHistoryComment($order)]));
        }
    }

    private function createHistoryComment(OrderInterface $order): OrderStatusHistoryInterface
    {
        $this->orderStatusAdjusted->setComment(
            'Order Status was adjusted to ' . $this->orderStatusAdjusted->getStatus() .
            ', applied rule #' . $this->orderStatusAdjusted->getRule()?->getRuleId() .
            ' ' . $this->orderStatusAdjusted->getRule()?->getName()
        );
        $history = $this->historyFactory->create();
        $history->setStatus($order->getStatus())
            ->setComment($this->orderStatusAdjusted->getComment())
            ->setEntityName(Order::ENTITY)
            ->setIsVisibleOnFront(true);

        return $history;
    }
}
