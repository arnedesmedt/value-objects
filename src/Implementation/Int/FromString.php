<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use function intval;

/** @method static static fromInt(int $integer) */
trait FromString
{
    public static function fromString(string $string): static
    {
        return static::fromInt(intval($string));
    }
}
