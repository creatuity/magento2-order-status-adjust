<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Condition;

use Creatuity\OrderStatusAdjust\Model\Condition\Type\Order as OrderCondition;
use Magento\Rule\Model\Condition\Context;

class Combine extends \Magento\Rule\Model\Condition\Combine
{
    public function __construct(
        private readonly OrderCondition $orderCondition,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->setType(Combine::class);
    }

    public function getNewChildSelectOptions(): array
    {
        $orderAttributes = $this->orderCondition->loadAttributeOptions()->getAttributeOption();

        $attributes = [];
        foreach ($orderAttributes as $code => $label) {
            $attributes[] = [
                'value' => OrderCondition::class . '|' . $code,
                'label' => $label,
            ];
        }

        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => Combine::class,
                    'label' => __('Conditions combination')
                ],
                ['label' => __('Order Attribute'), 'value' => $attributes]
            ]
        );

        return $conditions;
    }
}
