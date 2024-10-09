<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

interface UuidValue extends StringValue
{
    public static function generate(): static;
}
