<?php

namespace Creatuity\OrderStatusAdjust\Model\Container;

interface CalculatedOrderContainerInterface
{
    /**
     * @return string
     */
    public function getPaymentMethod(): string;

    /**
     * @return string
     */
    public function getShippingMethod(): string;

    /**
     * @return int
     */
    public function getOrderedQty(): int;

    /**
     * @return int
     */
    public function getBackOrderedQty(): int;

    /**
     * @return int
     */
    public function getInvoicedQty(): int;

    /**
     * @return int
     */
    public function getShippedQty(): int;

    /**
     * @return int
     */
    public function getCancelledQty(): int;

    /**
     * @return int
     */
    public function getRefundedQty(): int;

    /**
     * @return int
     */
    public function getReturnedQty(): int;

    /**
     * @param string $paymentMethod
     * @return void
     */
    public function setPaymentMethod(string $paymentMethod): void;

    /**
     * @param string $shippingMethod
     * @return void
     */
    public function setShippingMethod(string $shippingMethod): void;

    /**
     * @param int $orderedQty
     * @return void
     */
    public function setOrderedQty(int $orderedQty): void;

    /**
     * @param int $backOrderedQty
     * @return void
     */
    public function setBackOrderedQty(int $backOrderedQty): void;

    /**
     * @param int $invoicedQty
     * @return void
     */
    public function setInvoicedQty(int $invoicedQty): void;

    /**
     * @param int $shippedQty
     * @return void
     */
    public function setShippedQty(int $shippedQty): void;

    /**
     * @param int $cancelledQty
     * @return void
     */
    public function setCancelledQty(int $cancelledQty): void;

    /**
     * @param int $refundedQty
     * @return void
     */
    public function setRefundedQty(int $refundedQty): void;

    /**
     * @param int $returnedQty
     * @return void
     */
    public function setReturnedQty(int $returnedQty): void;
}
