<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use RuntimeException;
use function sprintf;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

abstract class RangeValue extends IntValue
{
    protected function __construct(int $value)
    {
        if ((static::included() && ($value > static::maximum() || $value < static::minimum()))
            || (! static::included() && ($value >= static::maximum() || $value <= static::minimum()))
        ) {
            $orEqual = static::included() ? 'or equal' : '';

            throw new RuntimeException(
                sprintf(
                    'The value \'%d\' for value object \'%s\', has to be lower%s than %d and greater%s than %d.',
                    $value,
                    static::class,
                    $orEqual,
                    static::maximum(),
                    $orEqual,
                    static::minimum()
                )
            );
        }

        parent::__construct($value);
    }

    public static function minimum() : int
    {
        return PHP_INT_MIN;
    }

    public static function maximum() : int
    {
        return PHP_INT_MAX;
    }

    public static function included() : bool
    {
        return true;
    }
}
