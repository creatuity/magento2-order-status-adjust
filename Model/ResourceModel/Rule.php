<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\ResourceModel;

use Magento\Rule\Model\ResourceModel\AbstractResource;

class Rule extends AbstractResource
{
    public const TABLE_NAME = 'order_status_adjust_rule';
    public const ID_FIELD_NAME = 'rule_id';

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD_NAME);
    }
}
