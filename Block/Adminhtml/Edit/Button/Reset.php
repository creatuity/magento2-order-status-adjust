<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Block\Adminhtml\Edit\Button;

use Magento\Framework\Phrase;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Reset implements ButtonProviderInterface
{
    /**
     * @return array<string, Phrase|string|int>
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30,
        ];
    }
}
