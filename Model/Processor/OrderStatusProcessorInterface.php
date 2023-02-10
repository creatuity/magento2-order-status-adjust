<?php

namespace Creatuity\OrderStatusAdjust\Model\Processor;

use Creatuity\OrderStatusAdjust\Model\Container\CalculatedOrderContainer;
use Magento\Sales\Api\Data\OrderInterface;

interface OrderStatusProcessorInterface
{
    public function execute(CalculatedOrderContainer $container, OrderInterface $order): bool;
}
