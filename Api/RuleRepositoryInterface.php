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
     * @param int $ruleId
     * @return RuleInterface
     * @throws NotFoundException
     */
    public function getById(int $ruleId): RuleInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return RuleSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): RuleSearchResultInterface;


    /**
     * @param RuleInterface $rule
     * @return void
     * @throws AlreadyExistsException
     */
    public function save(RuleInterface $rule): void;

    /**
     * @param RuleInterface $rule
     * @return void
     * @throws Exception
     */
    public function delete(RuleInterface $rule): void;
}
