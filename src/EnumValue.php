<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface EnumValue extends ValueObject
{
    /**
     * @return array<int|string>
     */
    public static function possibleValues(): array;
}
