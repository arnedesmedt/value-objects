<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface FloatValue extends CalcValueObject
{
    public static function fromFloat(float $value): static;

    public function toFloat(): float;
}
