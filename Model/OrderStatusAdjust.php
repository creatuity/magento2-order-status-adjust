<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Creatuity\OrderStatusAdjust\Model\Config\OrderStatusAdjustConfig;
use Magento\Sales\Api\Data\OrderInterface;

class OrderStatusAdjust
{
    public function __construct(
        private readonly OrderStatusAdjustConfig $orderStatusAdjustConfig,
        private readonly RulesProcessor $rulesProcessor
    ) {
    }

    public function execute(OrderInterface $order): bool
    {
        if (!$this->orderStatusAdjustConfig->isEnabled()) {
            return false;
        }

        return $this->rulesProcessor->execute($order);
    }
}
