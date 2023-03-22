<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Handler;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Phrase;

class ValidationHandler
{
    public function __construct(
        private readonly DataObjectFactory $dataObjectFactory
    ) {
    }

    /**
     * @param RuleInterface $rule
     * @param array $data
     * @return Phrase[]|string[]
     */
    public function execute(RuleInterface $rule, array $data): array
    {
        $dataObject = $this->dataObjectFactory->create();
        $dataObject->setData($data);

        $validateResult = $rule->validateData($dataObject);

        if ($validateResult === true) {
            return [];
        }

        return $validateResult;
    }
}
