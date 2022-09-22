<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface UuidValue extends StringValue
{
    public static function generate(): static;
}
