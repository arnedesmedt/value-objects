<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Float;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\IntType;
use TeamBlue\ValueObjects\Exception\RangeException;
use TeamBlue\ValueObjects\HasExamples;

use function array_filter;

use const PHP_FLOAT_MAX;
use const PHP_FLOAT_MIN;

abstract class FloatRangeValue extends FloatValue implements HasExamples, ProvidesValidationRules
{
    protected function __construct(float $value)
    {
        if (
            (static::included() && ($value > static::maximum() || $value < static::minimum()))
            || (! static::included() && ($value >= static::maximum() || $value <= static::minimum()))
        ) {
            throw RangeException::outsideRangeFromNumber(
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

    public static function example(): static
    {
        return static::fromFloat(10.12);
    }

    /** @return array<string, float> */
    public static function validationRules(): array
    {
        $minimum = static::minimum();
        $maximum = static::maximum();

        if ($minimum === PHP_FLOAT_MIN) {
            $minimum = null;
        }

        if ($maximum === PHP_FLOAT_MAX) {
            $maximum = null;
        }

        $result = static::included()
            ? [
                IntType::MINIMUM => $minimum,
                IntType::MAXIMUM => $maximum,
            ]
            : [
                IntType::EXCLUSIVE_MINIMUM => $minimum,
                IntType::EXCLUSIVE_MAXIMUM => $maximum,
            ];

        return array_filter($result, static fn ($value) => $value !== null);
    }
}
