<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\BoolValue;
use ADS\ValueObjects\DateTimeValue;
use ADS\ValueObjects\EnumValue;
use ADS\ValueObjects\Exception\ExamplesException;
use ADS\ValueObjects\FloatValue;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\Float\FloatRangeValue;
use ADS\ValueObjects\Implementation\Int\RangeValue;
use ADS\ValueObjects\Implementation\String\Base64EncodedStringValue;
use ADS\ValueObjects\Implementation\String\EmailValue;
use ADS\ValueObjects\Implementation\String\HostnameValue;
use ADS\ValueObjects\Implementation\String\IpV4Value;
use ADS\ValueObjects\Implementation\String\IpV6Value;
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
        $generator = Factory::create();

        switch (true) {
            case $reflection->implementsInterface(DateTimeValue::class):
                return static::fromDateTime($generator->dateTime());

            case $reflection->isSubclassOf(UuidValue::class):
                return static::generate();

            case $reflection->isSubclassOf(EmailValue::class):
                return static::fromString($generator->email());

            case $reflection->isSubclassOf(IpV4Value::class):
                return static::fromString($generator->ipv4());

            case $reflection->isSubclassOf(IpV6Value::class):
                return static::fromString($generator->ipv6());

            case $reflection->isSubclassOf(HostnameValue::class):
                return static::fromString($generator->domainName());

            case $reflection->isSubclassOf(UrlValue::class):
                return static::fromString($generator->url());

            case $reflection->isSubclassOf(Base64EncodedStringValue::class):
                return static::fromPlainString($generator->word());

            case $reflection->isSubclassOf(RangeValue::class):
                return static::fromInt($generator->numberBetween(static::minimum(), static::maximum()));

            case $reflection->isSubclassOf(FloatRangeValue::class):
                return static::fromFloat($generator->randomFloat(
                    $generator->numberBetween(0, 1),
                    static::minimum(),
                    static::maximum()
                ));

            case $reflection->implementsInterface(EnumValue::class):
                return static::fromValue($generator->randomElement(static::possibleValues()));

            case $reflection->implementsInterface(FloatValue::class):
                return static::fromFloat($generator->randomFloat());

            case $reflection->implementsInterface(IntValue::class):
                return static::fromInt($generator->randomNumber());

            case $reflection->implementsInterface(StringValue::class):
                return static::fromString($generator->word());

            case $reflection->implementsInterface(BoolValue::class):
                return static::fromBool($generator->boolean());

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
