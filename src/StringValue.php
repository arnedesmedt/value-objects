<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use Stringable;

interface StringValue extends ValueObject, Stringable
{
    public static function fromString(string $value): static;

    public function toString(): string;
}
