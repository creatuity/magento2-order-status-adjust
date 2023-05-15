<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Block\Adminhtml\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Back implements ButtonProviderInterface
{
    public function __construct(
        private readonly Context $context
    ) {
    }

    /**
     * @return array<string, Phrase|string|int>
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10,
        ];
    }

    public function getBackUrl(): string
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/');
    }
}
