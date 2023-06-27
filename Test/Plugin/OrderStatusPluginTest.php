<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Test\Plugin;

use Creatuity\OrderStatusAdjust\Model\OrderStatusAdjust;
use Creatuity\OrderStatusAdjust\Plugin\OrderStatusPlugin;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderStatusPluginTest extends TestCase
{
    private OrderStatusPlugin $subject;

    private OrderStatusAdjust|MockObject $orderStatusAdjust;
    private Order|MockObject $order;

    protected function setUp(): void
    {
        $this->orderStatusAdjust = $this->createMock(OrderStatusAdjust::class);
        $this->order = $this->createMock(Order::class);

        $this->subject = new OrderStatusPlugin(
            $this->orderStatusAdjust
        );
    }

    public function testGivenExecuteSuccess_thenPreventDefaultStatus(): void
    {
        $this->orderStatusAdjust->method('execute')->willReturn(true);

        $called = false;
        $statusPassed = '';

        $this->subject->aroundSetStatus($this->order, function(string $status) use (&$called, &$statusPassed) {
            $called = true;
            $statusPassed = $status;

            return $this->order;
        }, 'default status');

        $this->assertFalse($called);
        $this->assertEquals('', $statusPassed);
    }

    public function testGivenExecuteFailure_thenAssertSetDefaultStatus(): void
    {
        $this->orderStatusAdjust->method('execute')->willReturn(false);

        $called = false;
        $statusPassed = '';

        $this->subject->aroundSetStatus($this->order, function(string $status) use (&$called, &$statusPassed) {
            $called = true;
            $statusPassed = $status;

            return $this->order;
        }, 'default status');

        $this->assertTrue($called);
        $this->assertEquals('default status', $statusPassed);
    }
}
