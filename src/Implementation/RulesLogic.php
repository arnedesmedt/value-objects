<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\EnumValue;
use ADS\ValueObjects\Implementation\Int\RangeValue;
use ADS\ValueObjects\Implementation\String\EmailValue;
use ADS\ValueObjects\Implementation\String\PatternValue;
use ADS\ValueObjects\Implementation\String\UrlValue;
use ADS\ValueObjects\Implementation\String\UuidValue;
use EventEngine\JsonSchema\Type\IntType;
use EventEngine\JsonSchema\Type\StringType;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use function array_filter;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

trait RulesLogic
{
    /**
     * @inheritDoc
     */
    public static function validationRules() : array
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
        ];

        return array_filter($rules);
    }

    private static function format(ReflectionClass $reflection) : ?string
    {
        switch (true) {
            case $reflection->isSubclassOf(EmailValue::class):
                return 'email';
            case $reflection->isSubclassOf(UrlValue::class):
                return 'uri';
            default:
                return null;
        }
    }

    private static function inclusiveMinimum(ReflectionClass $reflection) : ?int
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (static::included() && static::minimum() !== PHP_INT_MIN) {
                    return static::minimum();
                }

                return null;
            default:
                return null;
        }
    }

    private static function inclusiveMaximum(ReflectionClass $reflection) : ?int
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (static::included() && static::maximum() !== PHP_INT_MAX) {
                    return static::maximum();
                }

                return null;
            default:
                return null;
        }
    }

    private static function exclusiveMinimum(ReflectionClass $reflection) : ?int
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (! static::included() && static::minimum() !== PHP_INT_MIN) {
                    return static::minimum();
                }

                return null;
            default:
                return null;
        }
    }

    private static function exclusiveMaximum(ReflectionClass $reflection) : ?int
    {
        switch (true) {
            case $reflection->isSubclassOf(RangeValue::class):
                if (! static::included() && static::maximum() !== PHP_INT_MAX) {
                    return static::maximum();
                }

                return null;
            default:
                return null;
        }
    }
}
