<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Test\Model;

use Creatuity\OrderStatusAdjust\Model\GetStateByStatus;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetStateByStatusTest extends TestCase
{
    private GetStateByStatus $getStateByStatus;
    private ResourceConnection|MockObject $resourceConnectionMock;
    private AdapterInterface|MockObject $connectionMock;
    private Select|MockObject $selectMock;

    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->createMock(ResourceConnection::class);
        $this->connectionMock = $this->createMock(AdapterInterface::class);

        $this->resourceConnectionMock
            ->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->connectionMock);

        $this->selectMock = $this->createMock(Select::class);
        $this->selectMock
            ->expects($this->once())
            ->method('from')
            ->with('sales_order_status_state')
            ->willReturnSelf();
        $this->selectMock
            ->expects($this->once())
            ->method('reset')
            ->with('columns')
            ->willReturnSelf();
        $this->selectMock
            ->expects($this->once())
            ->method('columns')
            ->with(['state'])
            ->willReturnSelf();

        $this->getStateByStatus = new GetStateByStatus($this->resourceConnectionMock);
    }

    public function testExecuteShouldReturnStateWhenStatusExists(): void
    {
        $status = 'pending';
        $state = 'processing';

        $this->selectMock
            ->expects($this->once())
            ->method('where')
            ->with('status = ?', $status)
            ->willReturnSelf();

        $this->connectionMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($this->selectMock);
        $this->connectionMock
            ->expects($this->once())
            ->method('fetchOne')
            ->with($this->selectMock)
            ->willReturn($state);

        $result = $this->getStateByStatus->execute($status);

        $this->assertSame($state, $result);
    }

    public function testExecuteShouldReturnEmptyStringWhenStatusDoesNotExist(): void
    {
        $status = 'nonexistent';

        $this->selectMock
            ->expects($this->once())
            ->method('where')
            ->with('status = ?', $status)
            ->willReturnSelf();

        $this->connectionMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($this->selectMock);
        $this->connectionMock
            ->expects($this->once())
            ->method('fetchOne')
            ->with($this->selectMock)
            ->willReturn(null);

        $result = $this->getStateByStatus->execute($status);

        $this->assertSame('', $result);
    }
}
