<?php

namespace Creatuity\OrderStatusAdjust\Api;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Creatuity\OrderStatusAdjust\Api\Data\RuleSearchResultInterface;
use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NotFoundException;

interface RuleRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function getById(int $ruleId): RuleInterface;

    public function getList(SearchCriteriaInterface $searchCriteria): RuleSearchResultInterface;

    /**
     * @throws AlreadyExistsException
     */
    public function save(RuleInterface $rule): void;

    /**
     * @throws Exception
     */
    public function delete(RuleInterface $rule): void;
}
