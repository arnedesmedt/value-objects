<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use Faker\Factory;

abstract class HostnameValue extends StringValue implements ProvidesValidationRules
{
    public static function example(): static
    {
        $generator = Factory::create();

        return static::fromString($generator->domainName());
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'hostname'];
    }
}
