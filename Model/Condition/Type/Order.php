<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Condition\Type;

use Magento\Directory\Model\Config\Source\Allregion as RegionList;
use Magento\Directory\Model\Config\Source\Country as CountryList;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Model\AbstractModel;
use Magento\Payment\Model\Config\Source\Allmethods as PaymentMethodList;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Shipping\Model\Config\Source\Allmethods as ShippingMethodList;

class Order extends AbstractCondition
{
    private const ATTRIBUTE_BASE_SUBTOTAL_WITH_DISCOUNT = 'base_subtotal_with_discount';
    private const ATTRIBUTE_BASE_SUBTOTAL_TOTAL_INCL_TAX = 'base_subtotal_total_incl_tax';
    private const ATTRIBUTE_BASE_SUBTOTAL = 'base_subtotal';
    private const ATTRIBUTE_BASE_TAX_AMOUNT = 'base_tax_amount';
    private const ATTRIBUTE_BASE_GRAND_TOTAL = 'base_grand_total';
    private const ATTRIBUTE_BASE_DISCOUNT_AMOUNT = 'base_discount_amount';
    private const ATTRIBUTE_BASE_GIFT_CARDS_AMOUNT = 'base_gift_cards_amount';
    private const ATTRIBUTE_TOTAL_QTY_ORDERED = 'total_qty_ordered';
    private const ATTRIBUTE_WEIGHT = 'weight';
    private const ATTRIBUTE_PAYMENT_METHOD ='payment_method';
    private const ATTRIBUTE_SHIPPING_METHOD = 'shipping_method';

    private const FIELD_INPUT_SELECT = 'select';
    private const FIELD_INPUT_STRING = 'string';
    private const FIELD_INPUT_NUMERIC = 'numeric';

    private const FIELD_VALUE_TEXT = 'text';
    private const FIELD_VALUE_SELECT = 'select';

    public function __construct(
        Context $context,
        private readonly CountryList $countryList,
        private readonly RegionList $regionList,
        private readonly ShippingMethodList $shippingMethodList,
        private readonly PaymentMethodList $paymentMethodList,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function loadAttributeOptions(): self
    {
        $attributes = [
            self::ATTRIBUTE_BASE_SUBTOTAL_WITH_DISCOUNT => __('Subtotal (Excl. Tax)'),
            self::ATTRIBUTE_BASE_SUBTOTAL_TOTAL_INCL_TAX => __('Subtotal (Incl. Tax)'),
            self::ATTRIBUTE_BASE_SUBTOTAL => __('Subtotal'),
            self::ATTRIBUTE_BASE_TAX_AMOUNT => __('Tax Total'),
            self::ATTRIBUTE_BASE_GRAND_TOTAL => __('Grand Total'),
            self::ATTRIBUTE_BASE_DISCOUNT_AMOUNT => __('Discount Total'),
            self::ATTRIBUTE_BASE_GIFT_CARDS_AMOUNT => __('Gift Cards Amount'),
            self::ATTRIBUTE_TOTAL_QTY_ORDERED => __('Total Items Quantity Ordered'),
            self::ATTRIBUTE_WEIGHT => __('Total Weight'),
            self::ATTRIBUTE_PAYMENT_METHOD => __('Payment Method'),
            self::ATTRIBUTE_SHIPPING_METHOD => __('Shipping Method'),
        ];

        $this->setAttributeOption($attributes);

        return $this;
    }

    public function getAttributeElement(): AbstractElement
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);

        return $element;
    }

    public function getInputType(): string
    {
        switch ($this->getAttribute()) {
            case self::ATTRIBUTE_BASE_SUBTOTAL_WITH_DISCOUNT:
            case self::ATTRIBUTE_BASE_SUBTOTAL_TOTAL_INCL_TAX:
            case self::ATTRIBUTE_BASE_SUBTOTAL:
            case self::ATTRIBUTE_BASE_TAX_AMOUNT:
            case self::ATTRIBUTE_BASE_GRAND_TOTAL:
            case self::ATTRIBUTE_BASE_DISCOUNT_AMOUNT:
            case self::ATTRIBUTE_BASE_GIFT_CARDS_AMOUNT:
            case self::ATTRIBUTE_TOTAL_QTY_ORDERED:
            case self::ATTRIBUTE_WEIGHT:
                return self::FIELD_INPUT_NUMERIC;
            case self::ATTRIBUTE_PAYMENT_METHOD:
            case self::ATTRIBUTE_SHIPPING_METHOD:
                return self::FIELD_INPUT_SELECT;
        }

        return self::FIELD_INPUT_STRING;
    }

    public function getValueElementType(): string
    {
        switch ($this->getAttribute()) {
            case self::ATTRIBUTE_PAYMENT_METHOD:
            case self::ATTRIBUTE_SHIPPING_METHOD:
                return self::FIELD_VALUE_SELECT;
        }

        return self::FIELD_VALUE_TEXT;
    }

    public function getValueSelectOptions(): array
    {
        if (!$this->hasData('value_select_options')) {
            switch ($this->getAttribute()) {
                case self::ATTRIBUTE_SHIPPING_METHOD:
                    $options = $this->shippingMethodList->toOptionArray();
                    break;
                case self::ATTRIBUTE_PAYMENT_METHOD:
                    $options = $this->paymentMethodList->toOptionArray();
                    break;
                default:
                    $options = [];
                    break;
            }

            $this->setData('value_select_options', $options);
        }

        return $this->getData('value_select_options');
    }

    public function validate(AbstractModel $model): bool
    {
        $order = $model;
        if (!$order instanceof OrderInterface) {
            return false;
        }

        if (self::ATTRIBUTE_PAYMENT_METHOD == $this->getAttribute() && !$order->hasPaymentMethod()) {
            $order->setPaymentMethod($model->getPayment()->getMethod());
        }

        return parent::validate($order);
    }
}