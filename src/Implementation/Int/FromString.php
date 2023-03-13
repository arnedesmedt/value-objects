<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use function intval;

/** @method static self fromInt(int $integer) */
trait FromString
{
    public static function fromString(string $string): self
    {
        return self::fromInt(intval($string));
    }
}
