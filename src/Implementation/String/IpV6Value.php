<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

abstract class IpV6Value extends PatternValue
{
    public static function pattern(): string
    {
        return '^(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4}$';
    }
}
