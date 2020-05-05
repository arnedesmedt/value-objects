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
use ReflectionClass;
use function array_filter;

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
            IntType::MINIMUM => $reflection->isSubclassOf(RangeValue::class) ?
                (static::included() ? static::minimum() : null)
                : null,
            IntType::MAXIMUM => $reflection->isSubclassOf(RangeValue::class) ?
                (static::included() ? static::maximum() : null)
                : null,
            IntType::EXCLUSIVE_MINIMUM => $reflection->isSubclassOf(RangeValue::class) ?
                (static::included() ? null : static::minimum())
                : null,
            IntType::EXCLUSIVE_MAXIMUM => $reflection->isSubclassOf(RangeValue::class) ?
                (static::included() ? null : static::maximum())
                : null,
            StringType::FORMAT => self::format($reflection),
            StringType::PATTERN => $reflection->isSubclassOf(UuidValue::class) ?
                '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
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
}
