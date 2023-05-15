<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Ui\DataProvider;

use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

/**
 * todo: see how Cms listing data providers are written, adjust it to work (search criteria etc)
 */
class ListingDataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    public function __construct(
        private readonly CollectionFactory $ruleCollectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

//    public function getData(): array
//    {
//        $search = $this->getSearchResult();
//        //$collection = $this->ruleCollectionFactory->create();
//
//        return $search->getItems();
//    }
}
