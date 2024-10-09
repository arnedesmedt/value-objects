<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;

abstract class HostnameValue extends StringValue implements ProvidesValidationRules
{
    public static function example(): static
    {
        return static::fromString('example.com');
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'hostname'];
    }
}
