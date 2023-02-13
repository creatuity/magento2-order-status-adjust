<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Block\Adminhtml\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

abstract class Generic implements ButtonProviderInterface
{
    public function __construct(
        protected readonly Context $context,
        protected readonly PageRepositoryInterface $pageRepository
    ) {
    }

    public function getUrl($route = '', $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
