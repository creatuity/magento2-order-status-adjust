<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Creatuity\OrderStatusAdjust\Model\Config\OrderStatusAdjustConfig;
use Creatuity\OrderStatusAdjust\Model\Processor\DefaultProcessor;
use Magento\Sales\Api\Data\OrderInterface;

class OrderStatusAdjust
{
    public function __construct(
        private readonly OrderStatusAdjustConfig $orderStatusAdjustConfig,
        private readonly CalculateOrderContainer $calculateOrderContainer,
        private readonly DefaultProcessor $defaultProcessor
    ) {
    }

    public function execute(OrderInterface $order): bool
    {
        if (!$this->orderStatusAdjustConfig->isEnabled()) {
            return false;
        }

        $container = $this->calculateOrderContainer->execute($order);

        return $this->defaultProcessor->execute($container, $order);
    }
}
