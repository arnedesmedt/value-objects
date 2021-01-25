<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

abstract class IpV4Value extends PatternValue
{
    public static function pattern(): string
    {
        return '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$';
    }
}
