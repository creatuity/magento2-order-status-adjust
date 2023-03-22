<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Repository;

use Creatuity\OrderStatusAdjust\Api\Data\RuleSearchResultInterface;
use Creatuity\OrderStatusAdjust\Api\Data\RuleSearchResultInterfaceFactory;
use Creatuity\OrderStatusAdjust\Api\RuleRepositoryInterface;
use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Creatuity\OrderStatusAdjust\Api\Data\RuleInterfaceFactory;
use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule as RuleResource;
use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\CollectionFactory;
use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NotFoundException;

class RuleRepository implements RuleRepositoryInterface
{
    public function __construct(
        private readonly RuleResource $ruleResource,
        private readonly RuleInterfaceFactory $ruleFactory,
        private readonly RuleSearchResultInterfaceFactory $searchResultFactory,
        private readonly CollectionFactory $collectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor
    ) {
    }

    /**
     * @throws NotFoundException
     */
    public function getById(int $ruleId): RuleInterface
    {
        $rule = $this->ruleFactory->create();
        $this->ruleResource->load($rule, $ruleId);

        if (!$rule->getId()) {
            throw new NotFoundException(__('Rule was not found: ' . $ruleId));
        }

        return $rule;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): RuleSearchResultInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @throws AlreadyExistsException
     */
    public function save(RuleInterface $rule): void
    {
        $this->ruleResource->save($rule);
    }

    /**
     * @throws Exception
     */
    public function delete(RuleInterface $rule): void
    {
        $this->ruleResource->delete($rule);
    }
}
