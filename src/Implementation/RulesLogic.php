<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\EnumValue;
use ADS\ValueObjects\Implementation\Int\RangeValue;
use ADS\ValueObjects\Implementation\String\EmailValue;
use ADS\ValueObjects\Implementation\String\PatternValue;
use ADS\ValueObjects\Implementation\String\UuidValue;
use EventEngine\JsonSchema\Type\IntType;
use EventEngine\JsonSchema\Type\StringType;
use ReflectionClass;

trait RulesLogic
{
    /**
     * @inheritDoc
     */
    public static function rules() : array
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
                (static::included() ? static::minimum() : null)
                : null,
            IntType::EXCLUSIVE_MINIMUM => $reflection->isSubclassOf(RangeValue::class) ?
                (static::included() ? null : static::minimum())
                : null,
            IntType::EXCLUSIVE_MAXIMUM => $reflection->isSubclassOf(RangeValue::class) ?
                (static::included() ? null : static::maximum())
                : null,
            StringType::FORMAT => $reflection->isSubclassOf(EmailValue::class) ?
                'email'
                : null,
            StringType::PATTERN => $reflection->isSubclassOf(UuidValue::class) ?
                '[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}'
                : (
                    $reflection->isSubclassOf(PatternValue::class) ?
                        static::pattern()
                        : null
                ),
        ];
    }
}
