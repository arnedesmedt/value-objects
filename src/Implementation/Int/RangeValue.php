<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use ADS\ValueObjects\Exception\RangeException;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

abstract class RangeValue extends IntValue
{
    protected function __construct(int $value)
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
                static::included()
            );
        }

        parent::__construct($value);
    }

    public static function minimum(): int
    {
        return PHP_INT_MIN;
    }

    public static function maximum(): int
    {
        return PHP_INT_MAX;
    }

    public static function included(): bool
    {
        return true;
    }
}
