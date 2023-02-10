<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Creatuity\OrderStatusAdjust\Model\Container\CalculatedOrderContainer;
use Creatuity\OrderStatusAdjust\Model\Container\CalculatedOrderContainerFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class CalculateOrderContainer
{
    public function __construct(
        private readonly CalculatedOrderContainerFactory $calculatedOrderContainerFactory
    ) {
    }

    public function execute(OrderInterface $order): CalculatedOrderContainer
    {
        /** @var CalculatedOrderContainer $container */
        $container = $this->calculatedOrderContainerFactory->create();

        $container->setPaymentMethod($order->getPayment()?->getMethod() ?? '');
        /** @phpstan-ignore-next-line */
        $container->setShippingMethod($order->getShippingMethod() ?? '');

        /** @var OrderItemInterface $item */
        /** @phpstan-ignore-next-line */
        foreach ($order->getAllVisibleItems() as $item) {
            $container->setOrderedQty($container->getOrderedQty() + (int)$item->getQtyOrdered());
            $container->setBackOrderedQty($container->getBackOrderedQty() + (int)$item->getQtyBackordered());
            $container->setInvoicedQty($container->getInvoicedQty() + (int)$item->getQtyInvoiced());
            $container->setShippedQty($container->getShippedQty() + (int)$item->getQtyShipped());
            $container->setCancelledQty($container->getCancelledQty() + (int)$item->getQtyCanceled());
            $container->setRefundedQty($container->getRefundedQty() + (int)$item->getQtyRefunded());
            $container->setReturnedQty($container->getReturnedQty() + (int)$item->getQtyReturned());
        }

        return $container;
    }
}
