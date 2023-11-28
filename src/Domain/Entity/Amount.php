<?php

namespace Tm\OrangeMoneySDK\Domain\Entity;

use Tm\OrangeMoneySDK\Domain\Entity\Enum\Unit;

readonly class Amount
{
    private readonly float $value;

    private readonly Unit $unit;

    public function __construct(float $value, Unit $unit = Unit::XOF)
    {
        $this->value = $value;
        $this->unit = $unit;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }
}
