<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Magento\Framework\Data\Collection;
use Magento\Sales\Api\Data\OrderInterface;
use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;

class RulesProcessor
{
    public function __construct(
        private readonly RuleCollectionFactory $ruleCollectionFactory,
        private readonly GetStateByStatus $getStateByStatus,
        private readonly OrderStatusAdjusted $orderStatusAdjusted,
        private readonly RuleDateTimeValidator $ruleDateTimeValidator,
        private readonly OrderStatusAdjustHistory $orderStatusAdjustHistory
    ) {
    }

    public function execute(OrderInterface $order): bool
    {
        $collection = $this->ruleCollectionFactory->create()
            ->addFieldToFilter(RuleInterface::IS_ACTIVE, ['eq' => 1])
            ->addFieldToFilter(RuleInterface::ORDER_STATUS, ['neq' => $order->getStatus()])
            ->setOrder('sort_order', Collection::SORT_ORDER_ASC);

        /** @var Rule $rule */
        foreach ($collection->getItems() as $rule) {
            /** @phpstan-ignore-next-line */
            if (!$rule->validate($order)) {
                continue;
            }

            if (!$this->ruleDateTimeValidator->canBeApplied($rule)) {
                continue;
            }

            return $this->applyRule($order, $rule);
        }

        return false;
    }

    private function applyRule(OrderInterface $order, Rule $rule): bool
    {
        $this->orderStatusAdjusted->setStatus($rule->getOrderStatus());
        $this->orderStatusAdjusted->setState($this->getStateByStatus->execute($rule->getOrderStatus()));
        $this->orderStatusAdjusted->setRule($rule);

        /** @phpstan-ignore-next-line */
        $order->setData('state', $this->orderStatusAdjusted->getState());
        /** @phpstan-ignore-next-line */
        $order->setData('status', $this->orderStatusAdjusted->getStatus());

        $this->orderStatusAdjustHistory->execute($order);

        return true;
    }
}
