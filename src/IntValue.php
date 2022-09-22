<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface IntValue extends CalcValueObject
{
    public static function fromInt(int $value): static;

    public function toInt(): int;
}
