<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\RangeException;

use const PHP_FLOAT_MAX;
use const PHP_FLOAT_MIN;

abstract class RangeByteValue extends ByteValue
{
    protected function __construct(string $value)
    {
        $floatValue = (float) $value;

        if (
            (static::included() && ($floatValue > static::maximum() || $floatValue < static::minimum()))
            || (! static::included() && ($floatValue >= static::maximum() || $floatValue <= static::minimum()))
        ) {
            throw RangeException::outsideRangeFromNumber(
                $floatValue,
                static::class,
                static::minimum(),
                static::maximum(),
                static::included()
            );
        }

        parent::__construct($value);
    }

    public static function minimum(): float
    {
        return PHP_FLOAT_MIN;
    }

    public static function maximum(): float
    {
        return PHP_FLOAT_MAX;
    }

    public static function included(): bool
    {
        return true;
    }
}
