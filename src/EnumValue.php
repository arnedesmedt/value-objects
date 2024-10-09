<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

interface EnumValue extends ValueObject
{
    /** @return array<string|int> */
    public static function possibleValues(): array;
}
