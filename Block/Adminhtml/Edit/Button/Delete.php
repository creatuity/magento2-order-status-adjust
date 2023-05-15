<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Block\Adminhtml\Edit\Button;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Delete implements ButtonProviderInterface
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
        $data = [];
        $ruleId = $this->context->getRequest()->getParam('rule_id');
        if ($ruleId) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to delete this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getDeleteUrl(): string
    {
        $ruleId = $this->context->getRequest()->getParam('rule_id');

        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['rule_id' => $ruleId]);
    }
}
