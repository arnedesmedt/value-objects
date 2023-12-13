<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use ADS\ValueObjects\Exception\RangeException;
use ADS\ValueObjects\HasExamples;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\IntType;

use function array_filter;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

abstract class RangeValue extends IntValue implements HasExamples, ProvidesValidationRules
{
    protected function __construct(int $value)
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

    public static function example(): static
    {
        return static::fromInt(14);
    }

    /** @return array<string, int> */
    public static function validationRules(): array
    {
        $minimum = static::minimum();
        $maximum = static::maximum();

        if ($minimum === PHP_INT_MIN) {
            $minimum = null;
        }

        if ($maximum === PHP_INT_MAX) {
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
