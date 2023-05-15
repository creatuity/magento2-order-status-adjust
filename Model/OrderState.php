<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Magento\Sales\Model\Order;

class OrderState
{
    /**
     * @return array<array<string, string>>
     */
    public function toOptionArray(): array
    {
        $options = [['value' => '', 'label' => __('-- Please Select --')]];
        $options[] = ['value' => Order::STATE_NEW, 'label' => 'New'];
        $options[] = ['value' => Order::STATE_PENDING_PAYMENT, 'label' => 'Pending Payment'];
        $options[] = ['value' => Order::STATE_PROCESSING, 'label' => 'Processing'];
        $options[] = ['value' => Order::STATE_COMPLETE, 'label' => 'Complete'];
        $options[] = ['value' => Order::STATE_CLOSED, 'label' => 'Closed'];
        $options[] = ['value' => Order::STATE_CANCELED, 'label' => 'Canceled'];
        $options[] = ['value' => Order::STATE_HOLDED, 'label' => 'Holded'];
        $options[] = ['value' => Order::STATE_PAYMENT_REVIEW, 'label' => 'Payment Review'];

        return $options;
    }
}
