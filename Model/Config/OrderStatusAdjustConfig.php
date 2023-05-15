<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class OrderStatusAdjustConfig
{
    private const XML_PATH_SALES_ORDER_STATUS_ADJUST_ENABLED = 'sales/order_status_adjust/enabled';

    private bool $isEnabled = true;

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->isEnabledConfig() && $this->isEnabled;
    }

    public function disable(): void
    {
        $this->isEnabled = false;
    }

    public function enable(): void
    {
        $this->isEnabled = true;
    }

    public function isEnabledConfig(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SALES_ORDER_STATUS_ADJUST_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
