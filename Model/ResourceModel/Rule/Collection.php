<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule;

use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule as RuleResource;
use Creatuity\OrderStatusAdjust\Model\Rule;
use Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(Rule::class, RuleResource::class);
    }

    /**
     * Filter collection by specified date.
     * Filter collection to only active rules.
     *
     * @param string|null $now
     * @use $this->addStoreGroupDateFilter()
     * @return $this
     */
    public function setValidationFilter($now = null)
    {
        if (!$this->getFlag('validation_filter')) {
            $this->addDateFilter($now);
            $this->addIsActiveFilter();
            $this->setOrder('sort_order', self::SORT_ORDER_DESC);
            $this->setFlag('validation_filter', true);
        }

        return $this;
    }

    /**
     * From date or to date filter
     *
     * @param $now
     * @return $this
     */
    public function addDateFilter($now)
    {
        $this->getSelect()->where(
            'from_date is null or from_date <= ?',
            $now
        )->where(
            'to_date is null or to_date >= ?',
            $now
        );

        return $this;
    }
}
