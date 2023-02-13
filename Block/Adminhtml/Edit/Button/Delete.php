<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Block\Adminhtml\Edit\Button;

class Delete extends Generic
{
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

        return $this->getUrl('*/*/delete', ['rule_id' => $ruleId]);
    }
}
