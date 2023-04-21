<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Ui\DataProvider;

use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\CollectionFactory;
use Creatuity\OrderStatusAdjust\Model\Rule;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var Rule[] $loadedData
     */
    private array $loadedData;

    public function __construct(
        private readonly CollectionFactory $ruleCollectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $this->ruleCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return Rule[]
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var Rule[] $items */
        $items = $this->collection->getItems();

        $this->loadedData = [];
        foreach ($items as $rule) {
            $this->loadedData[$rule->getId()] = $rule->getData();
        }

        return $this->loadedData;
    }
}
