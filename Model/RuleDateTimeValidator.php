<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class RuleDateTimeValidator
{
    public function __construct(
        private readonly TimezoneInterface $timezone
    ) {
    }

    public function canBeApplied(Rule $rule): bool
    {
        $now = $this->timezone->scopeDate(null, null, true);

        return ($this->compareDateFrom($now, $rule) && $this->compareDateTo($now, $rule));
    }

    private function compareDateFrom(\DateTime $now, Rule $rule): bool
    {
        if ($rule->getFromDate() !== null) {
            $dateFrom = $this->timezone->scopeDate(null, $rule->getFromDate(), true);

            return ($now > $dateFrom);
        }

        return true;
    }

    private function compareDateTo(\DateTime $now, Rule $rule): bool
    {
        if ($rule->getToDate() !== null) {
            $dateTo = $this->timezone->scopeDate(null, $rule->getToDate(), true);

            return ($now < $dateTo);
        }

        return true;
    }
}
