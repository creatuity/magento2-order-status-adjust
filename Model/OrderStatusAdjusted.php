<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Model;

class OrderStatusAdjusted
{
    private ?string $status = null;
    private ?string $state = null;
    private ?string $comment = null;
    private ?Rule $rule = null;

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setRule(Rule $rule): void
    {
        $this->rule = $rule;
    }

    public function getRule(): ?Rule
    {
        return $this->rule;
    }
}
