<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use ADS\ValueObjects\Exception\RangeException;
use ADS\ValueObjects\Implementation\Float\FloatValue;

use const PHP_FLOAT_MAX;
use const PHP_FLOAT_MIN;

abstract class FloatRangeValue extends FloatValue
{
    protected function __construct(float $value)
    {
        if (
            (static::included() && ($value > static::maximum() || $value < static::minimum()))
            || (! static::included() && ($value >= static::maximum() || $value <= static::minimum()))
        ) {
            throw RangeException::outsideRange(
                $value,
                static::class,
                static::minimum(),
                static::maximum(),
                static::included(),
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
