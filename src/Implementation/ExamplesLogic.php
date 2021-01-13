<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\BoolValue;
use ADS\ValueObjects\DateTimeValue;
use ADS\ValueObjects\EnumValue;
use ADS\ValueObjects\Exception\ExamplesException;
use ADS\ValueObjects\FloatValue;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\Int\RangeValue;
use ADS\ValueObjects\Implementation\String\Base64EncodedStringValue;
use ADS\ValueObjects\Implementation\String\EmailValue;
use ADS\ValueObjects\Implementation\String\UrlValue;
use ADS\ValueObjects\Implementation\String\UuidValue;
use ADS\ValueObjects\IntValue;
use ADS\ValueObjects\ListValue;
use ADS\ValueObjects\StringValue;
use Faker\Factory;
use ReflectionClass;

trait ExamplesLogic
{
    /**
     * @inheritDoc
     */
    public static function example()
    {
        $reflection = new ReflectionClass(static::class);

        switch (true) {
            case $reflection->implementsInterface(DateTimeValue::class):
                return static::fromDateTime(Factory::create()->dateTime());

            case $reflection->isSubclassOf(UuidValue::class):
                return static::generate();

            case $reflection->isSubclassOf(EmailValue::class):
                return static::fromString(Factory::create()->email);

            case $reflection->isSubclassOf(UrlValue::class):
                return static::fromString(Factory::create()->url);

            case $reflection->isSubclassOf(Base64EncodedStringValue::class):
                return static::fromPlainString(Factory::create()->word());

            case $reflection->isSubclassOf(RangeValue::class):
                return static::fromInt(Factory::create()->numberBetween(static::minimum(), static::maximum()));

            case $reflection->implementsInterface(EnumValue::class):
                return static::fromValue(Factory::create()->randomElement(static::possibleValues()));

            case $reflection->implementsInterface(FloatValue::class):
                return static::fromFloat(Factory::create()->randomFloat());

            case $reflection->implementsInterface(IntValue::class):
                return static::fromInt(Factory::create()->randomNumber());

            case $reflection->implementsInterface(StringValue::class):
                return static::fromString(Factory::create()->word());

            case $reflection->implementsInterface(BoolValue::class):
                return static::fromBool(Factory::create()->boolean());

            case $reflection->implementsInterface(ListValue::class):
                $itemType = static::itemType();
                $reflectionItem = new ReflectionClass($itemType);

                if (! $reflectionItem->implementsInterface(HasExamples::class)) {
                    throw ExamplesException::noItemExamplesFound($itemType, static::class);
                }

                return static::fromItems(
                    [
                        $itemType::example(),
                        $itemType::example(),
                    ]
                );

            default:
                throw ExamplesException::noExamplesFound(static::class);
        }
    }

    /**
     * @inheritDoc
     */
    public static function examples(): array
    {
        return [static::example()];
    }
}
