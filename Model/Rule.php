<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Creatuity\OrderStatusAdjust\Model\Condition\Combine;
use Creatuity\OrderStatusAdjust\Model\Condition\CombineFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Rule\Model\AbstractModel;
use Magento\Rule\Model\Action\Collection;
use Magento\Rule\Model\Action\CollectionFactory as ActionsCollectionFactory;

class Rule extends AbstractModel implements RuleInterface
{
    protected $_eventPrefix = 'order_status_adjust_rule';
    protected $_eventObject = 'rule';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private readonly CombineFactory $conditionCombineFactory,
        private readonly ActionsCollectionFactory $actionsCollectionFactory,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        TimezoneInterface $localeDate,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(ResourceModel\Rule::class);
        $this->setIdFieldName('rule_id');
    }

    public function getConditionsInstance(): Combine
    {
        return $this->conditionCombineFactory->create();
    }

    public function getActionsInstance(): Collection
    {
        return $this->actionsCollectionFactory->create();
    }

    public function getConditionsFieldSetId(string $formName = ''): string
    {
        return $formName . 'rule_conditions_fieldset_' . $this->getId();
    }

    public function getFromDate(): ?string
    {
        return $this->getData('from_date');
    }

    public function getToDate(): ?string
    {
        return $this->getData('to_date');
    }
}
