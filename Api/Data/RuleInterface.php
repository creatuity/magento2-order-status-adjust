<?php

namespace Creatuity\OrderStatusAdjust\Api\Data;

use Creatuity\OrderStatusAdjust\Model\Condition\Combine;
use Magento\Rule\Model\Action\Collection;

interface RuleInterface
{
    public const IS_ACTIVE = 'is_active';

    public function getConditionsInstance(): Combine;
    public function getActionsInstance(): Collection;
    public function getConditionsFieldSetId(string $formName = ''): string;

    public function getFromDate(): ?string;
    public function getToDate(): ?string;

    public function validateData(\Magento\Framework\DataObject $dataObject);
}
