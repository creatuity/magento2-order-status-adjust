<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Magento\Framework\App\ResourceConnection;

class GetStateByStatus
{
    private ResourceConnection $resourceConnection;

    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    public function execute(string $status): string
    {
        $connection = $this->resourceConnection->getConnection();

        $query = $connection->select()
            ->from('sales_order_status_state')
            ->reset('columns')
            ->columns(['state'])
            ->where('status = ?', $status);

        return $connection->fetchOne($query) ?? '';
    }
}
