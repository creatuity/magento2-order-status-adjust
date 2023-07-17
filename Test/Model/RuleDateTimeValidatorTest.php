<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Test\Model;

use Creatuity\OrderStatusAdjust\Model\Rule;
use Creatuity\OrderStatusAdjust\Model\RuleDateTimeValidator;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RuleDateTimeValidatorTest extends TestCase
{
    private RuleDateTimeValidator $ruleDateTimeValidator;
    private TimezoneInterface|MockObject $timezoneMock;

    protected function setUp(): void
    {
        $this->timezoneMock = $this->createMock(TimezoneInterface::class);
        $this->ruleDateTimeValidator = new RuleDateTimeValidator($this->timezoneMock);
    }

    public function testCanBeAppliedWithNoFromDate(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule->expects($this->once())
            ->method('getFromDate')
            ->willReturn(null);

        $currentDate = new \DateTime('2023-07-16');
        $this->timezoneMock->expects($this->once())
            ->method('scopeDate')
            ->with(null, null, true)
            ->willReturn($currentDate);

        $result = $this->ruleDateTimeValidator->canBeApplied($rule);

        $this->assertTrue($result);
    }

    public function testCanBeAppliedWithValidFromDate(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule->expects($this->exactly(2))
            ->method('getFromDate')
            ->willReturn('2023-07-15');

        $rule->expects($this->exactly(1))
            ->method('getToDate')
            ->willReturn(null);

        $fromDate = new \Datetime('2023-07-15');
        $now = new \DateTime('2023-07-17');
        $this->timezoneMock
            ->method('scopeDate')
            ->willReturnCallback(fn ($a, $b, $c) => match (true) {
                $a === null && $b === null && $c === true => $now,
                $a === null && $b === '2023-07-15' && $c === true => $fromDate
            });

        $result = $this->ruleDateTimeValidator->canBeApplied($rule);

        $this->assertTrue($result);
    }

    public function testCanBeAppliedWithInvalidFromDate(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule->expects($this->exactly(2))
            ->method('getFromDate')
            ->willReturn('2023-07-18');

        $rule->expects($this->never())
            ->method('getToDate');

        $fromDate = new \Datetime('2023-07-18');
        $now = new \DateTime('2023-07-17');
        $this->timezoneMock
            ->method('scopeDate')
            ->willReturnCallback(fn ($a, $b, $c) => match (true) {
                $a === null && $b === null && $c === true => $now,
                $a === null && $b === '2023-07-18' && $c === true => $fromDate
            });

        $result = $this->ruleDateTimeValidator->canBeApplied($rule);

        $this->assertFalse($result);
    }

    public function testCanBeAppliedWithNoToDate(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule->expects($this->exactly(2))
            ->method('getFromDate')
            ->willReturn('2023-07-18');

        $rule->expects($this->exactly(1))
            ->method('getToDate')
            ->willReturn(null);

        $fromDate = new \Datetime('2023-07-18');
        $now = new \DateTime('2023-07-19');
        $this->timezoneMock
            ->method('scopeDate')
            ->willReturnCallback(fn ($a, $b, $c) => match (true) {
                $a === null && $b === null && $c === true => $now,
                $a === null && $b === '2023-07-18' && $c === true => $fromDate
            });

        $result = $this->ruleDateTimeValidator->canBeApplied($rule);

        $this->assertTrue($result);
    }

    public function testCanBeAppliedWithValidToDate(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule->expects($this->exactly(2))
            ->method('getFromDate')
            ->willReturn('2023-07-16');

        $rule->expects($this->exactly(2))
            ->method('getToDate')
            ->willReturn('2023-07-18');

        $fromDate = new \Datetime('2023-07-16');
        $toDate = new \Datetime('2023-07-18');
        $now = new \DateTime('2023-07-17');
        $this->timezoneMock
            ->method('scopeDate')
            ->willReturnCallback(fn ($a, $b, $c) => match (true) {
                $a === null && $b === null && $c === true => $now,
                $a === null && $b === '2023-07-16' && $c === true => $fromDate,
                $a === null && $b === '2023-07-18' && $c === true => $toDate
            });

        $result = $this->ruleDateTimeValidator->canBeApplied($rule);

        $this->assertTrue($result);
    }

    public function testCanBeAppliedWithInvalidToDate(): void
    {
        $rule = $this->createMock(Rule::class);
        $rule->expects($this->exactly(2))
            ->method('getFromDate')
            ->willReturn('2023-07-16');

        $rule->expects($this->exactly(2))
            ->method('getToDate')
            ->willReturn('2023-07-18');

        $fromDate = new \Datetime('2023-07-16');
        $toDate = new \Datetime('2023-07-18');
        $now = new \DateTime('2023-07-19');
        $this->timezoneMock
            ->method('scopeDate')
            ->willReturnCallback(fn ($a, $b, $c) => match (true) {
                $a === null && $b === null && $c === true => $now,
                $a === null && $b === '2023-07-16' && $c === true => $fromDate,
                $a === null && $b === '2023-07-18' && $c === true => $toDate
            });

        $result = $this->ruleDateTimeValidator->canBeApplied($rule);

        $this->assertFalse($result);
    }
}
