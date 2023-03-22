<?php

namespace Creatuity\OrderStatusAdjust\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface RuleSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Creatuity\OrderStatusAdjust\Api\Data\RuleInterface[]
     */
    public function getItems(): array;

    /**
     * @param \Creatuity\OrderStatusAdjust\Api\Data\RuleInterface[] $items
     * @return void
     */
    public function setItems(array $items): void;
}
