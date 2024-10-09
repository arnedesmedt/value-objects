<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

interface IntValue extends CalcValueObject
{
    public static function fromInt(int $value): static;

    public function toInt(): int;
}
