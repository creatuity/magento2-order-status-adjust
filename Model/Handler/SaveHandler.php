<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Handler;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Creatuity\OrderStatusAdjust\Api\RuleRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;

class SaveHandler
{
    public function __construct(
        private readonly RuleRepositoryInterface $ruleRepository
    ) {
    }

    /**
     * @throws AlreadyExistsException
     */
    public function execute(RuleInterface $rule, array $data): void
    {
        $data = $this->prepareData($data);

        $rule->loadPost($data);

        $this->ruleRepository->save($rule);
    }

    private function prepareData(array $data): array
    {
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        unset($data['rule']);

        return $data;
    }
}
