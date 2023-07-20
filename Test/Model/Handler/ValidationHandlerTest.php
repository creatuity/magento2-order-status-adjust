<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Test\Model\Handler;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterface;
use Creatuity\OrderStatusAdjust\Model\Handler\ValidationHandler;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Phrase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ValidationHandlerTest extends TestCase
{
    private ValidationHandler $validationHandler;
    private MockObject|DataObjectFactory $dataObjectFactory;

    protected function setUp(): void
    {
        $this->dataObjectFactory = $this->createMock(DataObjectFactory::class);
        $this->validationHandler = new ValidationHandler($this->dataObjectFactory);
    }

    public function testExecuteWithValidData(): void
    {
        $rule = $this->createMock(RuleInterface::class);
        $data = ['key' => 'value'];

        $dataObject = new DataObject($data);

        $this->dataObjectFactory->expects($this->once())
            ->method('create')
            ->willReturn($dataObject);

        $rule->expects($this->once())
            ->method('validateData')
            ->with($dataObject)
            ->willReturn(true);

        $result = $this->validationHandler->execute($rule, $data);

        $this->assertEmpty($result);
    }

    public function testExecuteWithInvalidData(): void
    {
        $rule = $this->createMock(RuleInterface::class);
        $data = ['key' => 'value'];

        $dataObject = new DataObject($data);
        $errorMessage = __('Validation error message');

        $this->dataObjectFactory->expects($this->once())
            ->method('create')
            ->willReturn($dataObject);

        $rule->expects($this->once())
            ->method('validateData')
            ->with($dataObject)
            ->willReturn([$errorMessage]);

        $result = $this->validationHandler->execute($rule, $data);

        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(Phrase::class, $result);
        $this->assertSame((string)$errorMessage, (string)$result[0]);
    }
}
