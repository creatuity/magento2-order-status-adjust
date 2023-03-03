<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Magento\Sales\Api\Data\OrderInterface;
use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;

class RulesProcessor
{
    public function __construct(
        private readonly RuleCollectionFactory  $ruleCollectionFactory,
        private readonly GetStateByStatus $getStateByStatus
    ) {
    }

    public function execute(OrderInterface $order): bool
    {
        $collection = $this->ruleCollectionFactory->create();

        /** @var Rule $rule */
        foreach ($collection->getItems() as $rule) {
            /** @phpstan-ignore-next-line */
            if (!$rule->validate($order)) {
                continue;
            }

            return $this->applyRule($order, $rule);
        }

        return false;
    }

    private function applyRule(OrderInterface $order, Rule $rule): bool
    {
        $status = $rule->getOrderStatus();
        $state = $this->getStateByStatus->execute($status);

        /** @phpstan-ignore-next-line */
        $order->setData('state', $state);
        /** @phpstan-ignore-next-line */
        $order->setData('status', $status);

        return true;
    }
}
