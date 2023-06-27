<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Test\Model;

use Creatuity\OrderStatusAdjust\Model\GetStateByStatus;
use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\Collection;
use Creatuity\OrderStatusAdjust\Model\ResourceModel\Rule\CollectionFactory;
use Creatuity\OrderStatusAdjust\Model\Rule;
use Creatuity\OrderStatusAdjust\Model\RulesProcessor;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RulesProcessorTest extends TestCase
{
    private RulesProcessor $subject;

    private CollectionFactory|MockObject $collectionFactory;
    private GetStateByStatus|MockObject $getStateByStatus;
    private Order|MockObject $order;
    private Collection|MockObject $collection;

    protected function setUp(): void
    {
        $this->collection = $this->createMock(Collection::class);

        $this->collectionFactory = $this->createMock(CollectionFactory::class);
        $this->collectionFactory->method('create')->willReturn($this->collection);
        $this->collection->method('setOrder')->willReturn($this->collection);

        $this->getStateByStatus = $this->createMock(GetStateByStatus::class);
        $this->order = $this->createMock(Order::class);

        $this->subject = new RulesProcessor(
            $this->collectionFactory,
            $this->getStateByStatus
        );
    }

    public function testGivenEmptyCollection_thenReturnFalse(): void
    {
        $this->collection->method('getItems')->willReturn([]);

        $returned = $this->subject->execute($this->order);

        $this->assertFalse($returned);
    }

    public function testGivenCollection_thenProcessActiveValidatedAndSetStatus(): void
    {
        $rule1 = $this->createMock(Rule::class);
        $rule1->method('validate')->willReturn(false);
        $rule1->method('__call')->willReturnMap($this->getRuleMethodsMap(false, 'false-false'));

        $rule2 = $this->createMock(Rule::class);
        $rule2->method('validate')->willReturn(false);
        $rule2->method('__call')->willReturnMap($this->getRuleMethodsMap(true, 'true-false'));

        $rule3 = $this->createMock(Rule::class);
        $rule3->method('validate')->willReturn(true);
        $rule3->method('__call')->willReturnMap($this->getRuleMethodsMap(false, 'false-true'));

        $rule4 = $this->createMock(Rule::class);
        $rule4->method('validate')->willReturn(true);
        $rule4->method('__call')->willReturnMap($this->getRuleMethodsMap(true, 'true-true'));

        /** @var $rules Rule[]|MockObject[] */
        $rules = [
            $rule1, $rule2, $rule3, $rule4
        ];

        $this->collection->method('getItems')->willReturn($rules);

        $this->order->expects($this->exactly(2))->method('setData');

        $returned = $this->subject->execute($this->order);

        $this->assertTrue($returned);
    }

    private function getRuleMethodsMap(bool $isActive, string $orderStatus): array
    {
        return [
            ['getIsActive', [], $isActive],
            ['getOrderStatus', [], $orderStatus]
        ];
    }
}
