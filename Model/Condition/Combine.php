<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Condition;

use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;

class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @param AbstractCondition[] $conditionTypes
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        private readonly array $conditionTypes,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->setType(Combine::class);
    }

    public function getNewChildSelectOptions(): array
    {
        $conditions = array_merge_recursive(
            parent::getNewChildSelectOptions(),
            [
                ['value' => Combine::class, 'label' => __('Conditions combination')],
            ]
        );

        foreach ($this->conditionTypes as $name => $condition) {
            $conditionAttributes = $condition->loadAttributeOptions()->getAttributeOption();

            $attributes = [];
            foreach ($conditionAttributes as $code => $label) {
                $attributes[] = [
                    'value' => get_class($condition) . '|' . $code,
                    'label' => $label,
                ];
            }

            $conditions = array_merge_recursive(
                $conditions,
                [
                    ['label' => __(ucfirst($name)), 'value' => $attributes],
                ]
            );
        }

        return $conditions;
    }
}
