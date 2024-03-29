# Order Status Adjust module for Magento 2

## Installation
Use composer to install. To proceed, run these commands in your terminal:
```
composer require creatuity/magento2-order-status-adjust
php bin/magento module:enable Creatuity_OrderStatusAdjust
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## Example Use Cases
- set status "Hold - Liftgate" if order items total weight is >= 1000 lbs. and order state is "Processing"
- set status "Payment Review" if Check/Money payment method was used, order total was >= $1000.00 and order state is "Pending"
- set status "Pending - VIP" if "Exclusive Payment Method" was used and order state is "Pending"
- set status "Fraud" if Grand Total is >= $1000.00 and order state is "Pending"

## Usage
### Configuration
Go to Admin Panel -> Stores -> Settings -> Order Status Adjust Rules.

Click on "Add New Rule" button located in the upper-right corner of the screen to add new rule.

Fill all required and possibly optional fields:
- Enabled - yes/no. Indicates if rule is active. If not - it will not be taken into account during Order updates.
- Rule Name - text. Human-friendly name of the rule, to easily distinguish what is its purpose.
- Sort Order - integer. Allows to sort which rules will have precedence. Rules with lower value will be executed as the first ones. If sort order will be same - rule with lower ID will execute as the first one.
- Date and Time
  - Start - datetime. Allows to select date and time from which rule will be effective. In Store's timezone.
  - End - datetime. Allows to select date and time to which rule will be effective. In Store's timezone.
- Condition - complex. Combination of rules revolving around Orders that can be used to select particular orders and update their status to desired one conditionally.
- Action (Set Order Status) - select. Select desired order status that should be set if rule will be applied.

Save rule

### Troubleshooting
- My rules are not applying
  - verify enabled state
  - verify start and end date (ensure that they're in store timezone)
- Multiple rules are being applied
  - verify if your rules are excluding each other
    - example: Rule A sets "On Hold", Rule B sets "Cancelled". Make sure that Rule A is having condition "Status" is not 'On Hold' and Rule B is having condition "Status" is not 'Canceled' in their Conditions
- Module is throwing exceptions
  - Ensure that you're running on PHP 8.2 and Magento 2.4.6+
  - If issue is related to older version of Magento or PHP - feel free to open Issue and ask

### Available Conditions
| Condition                    | Internal Code                |
|------------------------------|------------------------------|
| Subtotal (Excl. Tax)         | base_subtotal_with_discount  |
| Subtotal (Incl. Tax)         | base_subtotal_total_incl_tax |
| Subtotal                     | base_subtotal                |
| Tax Total                    | base_tax_amount              |
| Grand Total                  | base_grand_total             |
| Discount Total               | base_discount_amount         |
| Gift Cards Amount            | base_gift_cards_amount       |
| Total Canceled               | base_total_canceled          |
| Total Invoiced               | base_total_invoiced          |
| Total Paid                   | base_total_paid              |
| Total Refunded               | base_total_refunded          |
| Shipping Amount              | base_shipping_amount         |
| Shipping Canceled            | base_shipping_canceled       |
| Shipping Invoiced            | base_shipping_invoiced       |
| Shipping Refunded            | base_shipping_refunded       |
| Total Items Quantity Ordered | total_qty_ordered            |
| Total Weight                 | weight                       |
| Payment Method               | payment_method               |
| Shipping Method              | shipping_method              |
| Order Currency Code          | order_currency_code          |
| Order Store ID               | store_id                     |
| Coupon Code                  | coupon_code                  |
| Total Item Count             | total_item_count             |
| Customer Is Guest            | customer_is_guest            |
| Customer Group ID            | customer_group_id            |
| Customer Tax Vat             | customer_taxvat              |
| Remote IP                    | remote_ip                    |
| Status                       | status                       |
| State                        | state                        |

## Compatibility
Module was developed using Adobe Commerce 2.4.5 on PHP 8.1
It should work on any Magento Open Source or Adobe Commerce 2.4.4+ versions though.

## Plugin Development
You can easily add more Order (or any other) conditions.

1. Extend `\Magento\Rule\Model\Condition\AbstractCondition` in a similar manner to how it is extended by `\Creatuity\OrderStatusAdjust\Model\Condition\Type\Order`
2. Open di.xml and add newly created class into conditionTypes argument here:
   ````
   <type name="Creatuity\OrderStatusAdjust\Model\Condition\Combine">
       <arguments>
           <argument name="conditionTypes" xsi:type="array">
               <item name="INSERT_TITLE" xsi:type ="object">INSERT_FULLY_QUALIFIED_CLASS_NAME_WITH_NAMESPACE</item>
           </argument>
       </arguments>
   </type>
   ````
3. Make sure you've enabled your newly created module and regenerated static files.
