<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Handler;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Creatuity\OrderStatusAdjust\Api\Data\RuleInterfaceFactory;
use Creatuity\OrderStatusAdjust\Api\RuleRepositoryInterface;
use Magento\Framework\Exception\NotFoundException;

class LoadHandler
{
    public function __construct(
        private readonly RuleRepositoryInterface $ruleRepository,
        private readonly RuleInterfaceFactory $ruleFactory
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function execute(?int $ruleId): RuleInterface
    {
        if (null === $ruleId) {
            return $this->ruleFactory->create();
        }

        return $this->ruleRepository->getById($ruleId);
    }
}
