<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\EnumValue;
use ADS\ValueObjects\Implementation\Float\FloatRangeValue;
use ADS\ValueObjects\Implementation\Int\RangeValue;
use ADS\ValueObjects\Implementation\String\DateTimeValue;
use ADS\ValueObjects\Implementation\String\EmailValue;
use ADS\ValueObjects\Implementation\String\HostnameValue;
use ADS\ValueObjects\Implementation\String\IpV4Value;
use ADS\ValueObjects\Implementation\String\IpV6Value;
use ADS\ValueObjects\Implementation\String\PatternValue;
use ADS\ValueObjects\Implementation\String\UrlValue;
use ADS\ValueObjects\Implementation\String\UuidValue;
use ADS\ValueObjects\ListValue;
use EventEngine\JsonSchema\Type\ArrayType;
use EventEngine\JsonSchema\Type\IntType;
use EventEngine\JsonSchema\Type\StringType;
use Ramsey\Uuid\Uuid;
use ReflectionClass;

use function array_filter;

use const PHP_FLOAT_MAX;
use const PHP_FLOAT_MIN;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

trait RulesLogic
{
    /**
     * @inheritDoc
     */
    public static function validationRules(): array
    {
        $reflection = new ReflectionClass(static::class);

        $rules = [
            StringType::ENUM => $reflection->isSubclassOf(EnumValue::class) ?
                static::possibleValues()
                : null,
            IntType::MINIMUM => self::inclusiveMinimum($reflection),
            IntType::MAXIMUM => self::inclusiveMaximum($reflection),
            IntType::EXCLUSIVE_MINIMUM => self::exclusiveMinimum($reflection),
            IntType::EXCLUSIVE_MAXIMUM => self::exclusiveMaximum($reflection),
            StringType::FORMAT => self::format($reflection),
            StringType::PATTERN => $reflection->isSubclassOf(UuidValue::class) ?
                Uuid::VALID_PATTERN
                : (
                    $reflection->isSubclassOf(PatternValue::class) ?
                        static::pattern()
                        : null
                ),
            ArrayType::CONTAINS => $reflection->isSubclassOf(ListValue::class) ?
                static::containsType()
                : null,
            ArrayType::MIN_ITEMS => $reflection->isSubclassOf(ListValue::class) ?
                static::minItems()
                : null,
            ArrayType::MAX_ITEMS => $reflection->isSubclassOf(ListValue::class) ?
                static::maxItems()
                : null,
            ArrayType::UNIQUE_ITEMS => $reflection->isSubclassOf(ListValue::class) ?
                static::uniqueItems()
                : null,
        ];

        return array_filter($rules);
    }

    private static function format(ReflectionClass $reflection): ?string
    {
        return match (true) {
            $reflection->isSubclassOf(EmailValue::class) => 'email',
            $reflection->isSubclassOf(UrlValue::class) => 'uri',
            $reflection->isSubclassOf(DateTimeValue::class) => 'date-time',
            $reflection->isSubclassOf(HostnameValue::class) => 'hostname',
            $reflection->isSubclassOf(IpV4Value::class) => 'ipv4',
            $reflection->isSubclassOf(IpV6Value::class) => 'ipv6',
            default => null,
        };
    }

    private static function inclusiveMinimum(ReflectionClass $reflection): int|float|null
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (static::included() && static::minimum() !== PHP_INT_MIN) {
                    return static::minimum();
                }

                return null;

            case $reflection->isSubclassOf(FloatRangeValue::class):
                if (static::included() && static::minimum() !== PHP_FLOAT_MIN) {
                    return static::minimum();
                }

                return null;

            default:
                return null;
        }
    }

    private static function inclusiveMaximum(ReflectionClass $reflection): int|float|null
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (static::included() && static::maximum() !== PHP_INT_MAX) {
                    return static::maximum();
                }

                return null;

            case $reflection->isSubclassOf(FloatRangeValue::class):
                if (static::included() && static::maximum() !== PHP_FLOAT_MAX) {
                    return static::maximum();
                }

                return null;

            default:
                return null;
        }
    }

    private static function exclusiveMinimum(ReflectionClass $reflection): int|float|null
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (! static::included() && static::minimum() !== PHP_INT_MIN) {
                    return static::minimum();
                }

                return null;

            case $reflection->isSubclassOf(FloatRangeValue::class):
                if (! static::included() && static::minimum() !== PHP_FLOAT_MIN) {
                    return static::minimum();
                }

                return null;

            default:
                return null;
        }
    }

    private static function exclusiveMaximum(ReflectionClass $reflection): int|float|null
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (! static::included() && static::maximum() !== PHP_INT_MAX) {
                    return static::maximum();
                }

                return null;

            case $reflection->isSubclassOf(FloatRangeValue::class):
                if (! static::included() && static::maximum() !== PHP_FLOAT_MAX) {
                    return static::maximum();
                }

                return null;

            default:
                return null;
        }
    }
}
