<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\Type\StringType;
use TeamBlue\ValueObjects\Exception\IpException;
use TeamBlue\ValueObjects\Exception\PatternException;

abstract class IpV4Value extends PatternValue
{
    protected function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (PatternException) {
            throw IpException::noValidIpv4($value, static::class);
        }
    }

    public static function pattern(): string
    {
        return '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$';
    }

    public static function example(): static
    {
        return static::fromString('1.2.3.4');
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [...parent::validationRules(), ...[StringType::FORMAT => 'ipv4']];
    }
}
