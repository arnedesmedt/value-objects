<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface EnumValue extends ValueObject
{
    /**
     * @return array<mixed>
     */
    public static function possibleValues() : array;
}
