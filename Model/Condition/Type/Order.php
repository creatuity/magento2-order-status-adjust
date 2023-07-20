<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Condition\Type;

use Creatuity\OrderStatusAdjust\Model\OrderState as StateList;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Model\AbstractModel;
use Magento\Payment\Model\Config\Source\Allmethods as PaymentMethodList;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Config\Source\Order\Status as StatusList;
use Magento\Shipping\Model\Config\Source\Allmethods as ShippingMethodList;
use Magento\Customer\Model\Config\Source\Group as CustomerGroupList;
use Magento\Config\Model\Config\Source\Store as StoreList;

class Order extends AbstractCondition
{
    private const ATTRIBUTE_STATUS = 'status';
    private const ATTRIBUTE_STATE = 'state';

    private const ATTRIBUTE_BASE_SUBTOTAL_WITH_DISCOUNT = 'base_subtotal_with_discount';
    private const ATTRIBUTE_BASE_SUBTOTAL_TOTAL_INCL_TAX = 'base_subtotal_total_incl_tax';
    private const ATTRIBUTE_BASE_SUBTOTAL = 'base_subtotal';
    private const ATTRIBUTE_BASE_TAX_AMOUNT = 'base_tax_amount';
    private const ATTRIBUTE_BASE_GRAND_TOTAL = 'base_grand_total';
    private const ATTRIBUTE_BASE_DISCOUNT_AMOUNT = 'base_discount_amount';
    private const ATTRIBUTE_BASE_GIFT_CARDS_AMOUNT = 'base_gift_cards_amount';
    private const ATTRIBUTE_BASE_TOTAL_CANCELED = 'base_total_canceled';
    private const ATTRIBUTE_BASE_TOTAL_INVOICED = 'base_total_invoiced';
    private const ATTRIBUTE_BASE_TOTAL_PAID = 'base_total_paid';
    private const ATTRIBUTE_BASE_TOTAL_REFUNDED = 'base_total_refunded';
    private const ATTRIBUTE_BASE_SHIPPING_AMOUNT = 'base_shipping_amount';
    private const ATTRIBUTE_BASE_SHIPPING_CANCELED = 'base_shipping_canceled';
    private const ATTRIBUTE_BASE_SHIPPING_INVOICED = 'base_shipping_invoiced';
    private const ATTRIBUTE_BASE_SHIPPING_REFUNDED = 'base_shipping_refunded';
    private const ATTRIBUTE_TOTAL_QTY_ORDERED = 'total_qty_ordered';
    private const ATTRIBUTE_WEIGHT = 'weight';
    private const ATTRIBUTE_PAYMENT_METHOD ='payment_method';
    private const ATTRIBUTE_SHIPPING_METHOD = 'shipping_method';

    private const ATTRIBUTE_ORDER_CURRENCY_CODE = 'order_currency_code';
    private const ATTRIBUTE_STORE_ID = 'store_id';
    private const ATTRIBUTE_COUPON_CODE = 'coupon_code';
    private const ATTRIBUTE_TOTAL_ITEM_COUNT = 'total_item_count';

    private const ATTRIBUTE_CUSTOMER_IS_GUEST = 'customer_is_guest';
    private const ATTRIBUTE_CUSTOMER_GROUP_ID = 'customer_group_id';
    private const ATTRIBUTE_CUSTOMER_TAXVAT = 'customer_taxvat';

    private const ATTRIBUTE_REMOTE_IP = 'remote_ip';

    private const FIELD_INPUT_SELECT = 'select';
    private const FIELD_INPUT_STRING = 'string';
    private const FIELD_INPUT_NUMERIC = 'numeric';

    private const FIELD_VALUE_TEXT = 'text';
    private const FIELD_VALUE_SELECT = 'select';

    public function __construct(
        Context                             $context,
        private readonly ShippingMethodList $shippingMethodList,
        private readonly PaymentMethodList  $paymentMethodList,
        private readonly CustomerGroupList  $customerGroupList,
        private readonly StoreList          $storeList,
        private readonly StatusList         $statusList,
        private readonly StateList          $stateList,
        array                               $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function loadAttributeOptions(): self
    {
        $attributes = [
            self::ATTRIBUTE_STATUS => __('Status'),
            self::ATTRIBUTE_STATE => __('State'),

            self::ATTRIBUTE_BASE_SUBTOTAL_WITH_DISCOUNT => __('Subtotal (Excl. Tax)'),
            self::ATTRIBUTE_BASE_SUBTOTAL_TOTAL_INCL_TAX => __('Subtotal (Incl. Tax)'),
            self::ATTRIBUTE_BASE_SUBTOTAL => __('Subtotal'),
            self::ATTRIBUTE_BASE_TAX_AMOUNT => __('Tax Total'),
            self::ATTRIBUTE_BASE_GRAND_TOTAL => __('Grand Total'),
            self::ATTRIBUTE_BASE_DISCOUNT_AMOUNT => __('Discount Total'),
            self::ATTRIBUTE_BASE_GIFT_CARDS_AMOUNT => __('Gift Cards Amount'),
            self::ATTRIBUTE_BASE_TOTAL_CANCELED => __('Total Canceled'),
            self::ATTRIBUTE_BASE_TOTAL_INVOICED => __('Total Invoiced'),
            self::ATTRIBUTE_BASE_TOTAL_PAID => __('Total Paid'),
            self::ATTRIBUTE_BASE_TOTAL_REFUNDED => __('Total Refunded'),
            self::ATTRIBUTE_BASE_SHIPPING_AMOUNT => __('Shipping Amount'),
            self::ATTRIBUTE_BASE_SHIPPING_CANCELED => __('Shipping Canceled'),
            self::ATTRIBUTE_BASE_SHIPPING_INVOICED => __('Shipping Invoiced'),
            self::ATTRIBUTE_BASE_SHIPPING_REFUNDED => __('Shipping Refunded'),
            self::ATTRIBUTE_TOTAL_QTY_ORDERED => __('Total Items Quantity Ordered'),
            self::ATTRIBUTE_WEIGHT => __('Total Weight'),
            self::ATTRIBUTE_PAYMENT_METHOD => __('Payment Method'),
            self::ATTRIBUTE_SHIPPING_METHOD => __('Shipping Method'),

            self::ATTRIBUTE_ORDER_CURRENCY_CODE => __('Order Currency Code'),
            self::ATTRIBUTE_STORE_ID => __('Order Store ID'),
            self::ATTRIBUTE_COUPON_CODE => __('Coupon Code'),
            self::ATTRIBUTE_TOTAL_ITEM_COUNT => __('Total Item Count'),

            self::ATTRIBUTE_CUSTOMER_IS_GUEST => __('Customer is Guest'),
            self::ATTRIBUTE_CUSTOMER_GROUP_ID => __('Customer Group ID'),
            self::ATTRIBUTE_CUSTOMER_TAXVAT => __('Customer Tax Vat'),

            self::ATTRIBUTE_REMOTE_IP => __('Remote IP'),
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
            case self::ATTRIBUTE_BASE_TOTAL_CANCELED:
            case self::ATTRIBUTE_BASE_TOTAL_INVOICED:
            case self::ATTRIBUTE_BASE_TOTAL_PAID:
            case self::ATTRIBUTE_BASE_TOTAL_REFUNDED:
            case self::ATTRIBUTE_BASE_SHIPPING_AMOUNT:
            case self::ATTRIBUTE_BASE_SHIPPING_CANCELED:
            case self::ATTRIBUTE_BASE_SHIPPING_INVOICED:
            case self::ATTRIBUTE_BASE_SHIPPING_REFUNDED:
            case self::ATTRIBUTE_TOTAL_QTY_ORDERED:
            case self::ATTRIBUTE_WEIGHT:
            case self::ATTRIBUTE_TOTAL_ITEM_COUNT:
            case self::ATTRIBUTE_CUSTOMER_IS_GUEST:
                return self::FIELD_INPUT_NUMERIC;
            case self::ATTRIBUTE_STATUS:
            case self::ATTRIBUTE_STATE:
            case self::ATTRIBUTE_PAYMENT_METHOD:
            case self::ATTRIBUTE_SHIPPING_METHOD:
            case self::ATTRIBUTE_CUSTOMER_GROUP_ID:
            case self::ATTRIBUTE_STORE_ID:
                return self::FIELD_INPUT_SELECT;
        }

        return self::FIELD_INPUT_STRING;
    }

    public function getValueElementType(): string
    {
        switch ($this->getAttribute()) {
            case self::ATTRIBUTE_STATUS:
            case self::ATTRIBUTE_STATE:
            case self::ATTRIBUTE_PAYMENT_METHOD:
            case self::ATTRIBUTE_SHIPPING_METHOD:
            case self::ATTRIBUTE_CUSTOMER_GROUP_ID:
            case self::ATTRIBUTE_STORE_ID:
                return self::FIELD_VALUE_SELECT;
        }

        return self::FIELD_VALUE_TEXT;
    }

    public function getValueSelectOptions(): array
    {
        if (!$this->hasData('value_select_options')) {
            switch ($this->getAttribute()) {
                case self::ATTRIBUTE_STATUS:
                    $options = $this->statusList->toOptionArray();
                    break;
                case self::ATTRIBUTE_STATE:
                    $options = $this->stateList->toOptionArray();
                    break;
                case self::ATTRIBUTE_SHIPPING_METHOD:
                    $options = $this->shippingMethodList->toOptionArray();
                    break;
                case self::ATTRIBUTE_PAYMENT_METHOD:
                    $options = $this->paymentMethodList->toOptionArray();
                    break;
                case self::ATTRIBUTE_CUSTOMER_GROUP_ID:
                    $options = $this->customerGroupList->toOptionArray();
                    break;
                case self::ATTRIBUTE_STORE_ID:
                    $options = $this->storeList->toOptionArray();
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
