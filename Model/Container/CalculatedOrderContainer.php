<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model\Container;

class CalculatedOrderContainer implements CalculatedOrderContainerInterface
{
    private string $paymentMethod = '';
    private string $shippingMethod = '';

    private int $orderedQty = 0;
    private int $backOrderedQty = 0;
    private int $invoicedQty = 0;
    private int $shippedQty = 0;
    private int $cancelledQty = 0;
    private int $refundedQty = 0;
    private int $returnedQty = 0;

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return string
     */
    public function getShippingMethod(): string
    {
        return $this->shippingMethod;
    }

    /**
     * @param string $shippingMethod
     */
    public function setShippingMethod(string $shippingMethod): void
    {
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * @return int
     */
    public function getOrderedQty(): int
    {
        return $this->orderedQty;
    }

    /**
     * @param int $orderedQty
     */
    public function setOrderedQty(int $orderedQty): void
    {
        $this->orderedQty = $orderedQty;
    }

    /**
     * @return int
     */
    public function getBackOrderedQty(): int
    {
        return $this->backOrderedQty;
    }

    /**
     * @param int $backOrderedQty
     */
    public function setBackOrderedQty(int $backOrderedQty): void
    {
        $this->backOrderedQty = $backOrderedQty;
    }

    /**
     * @return int
     */
    public function getInvoicedQty(): int
    {
        return $this->invoicedQty;
    }

    /**
     * @param int $invoicedQty
     */
    public function setInvoicedQty(int $invoicedQty): void
    {
        $this->invoicedQty = $invoicedQty;
    }

    /**
     * @return int
     */
    public function getShippedQty(): int
    {
        return $this->shippedQty;
    }

    /**
     * @param int $shippedQty
     */
    public function setShippedQty(int $shippedQty): void
    {
        $this->shippedQty = $shippedQty;
    }

    /**
     * @return int
     */
    public function getCancelledQty(): int
    {
        return $this->cancelledQty;
    }

    /**
     * @param int $cancelledQty
     */
    public function setCancelledQty(int $cancelledQty): void
    {
        $this->cancelledQty = $cancelledQty;
    }

    /**
     * @return int
     */
    public function getRefundedQty(): int
    {
        return $this->refundedQty;
    }

    /**
     * @param int $refundedQty
     */
    public function setRefundedQty(int $refundedQty): void
    {
        $this->refundedQty = $refundedQty;
    }

    /**
     * @return int
     */
    public function getReturnedQty(): int
    {
        return $this->returnedQty;
    }

    /**
     * @param int $returnedQty
     */
    public function setReturnedQty(int $returnedQty): void
    {
        $this->returnedQty = $returnedQty;
    }
}
