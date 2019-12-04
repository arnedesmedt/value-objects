<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface StringValue extends ValueObject
{
    /**
     * @return static
     */
    public static function fromString(string $value);

    public function toString() : string;
}
